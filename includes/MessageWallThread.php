<?php

/**
 * Class representing a message wall thread
 */
class MessageWallThread {

	/**
	 * Save a message wall thread
	 *
	 * @param WikiPage $article
	 * @param int $revisionID
	 */
	public static function save( WikiPage $article, $revisionID ) {
		global $wgUser;
		
		$dbw = wfGetDB( DB_MASTER );
		
		// Get the thread ID and parent thread ID from the article's title
		$title = $article->getTitle();
		$threadId = $title->getText();
		$parentId = $title->getParentText();
		
		// Get the user who created the thread
		$userId = $wgUser->getId();
		
		// Insert or update the thread in the database
		$dbw->upsert(
			'message_wall',
			array(
				'mw_thread_id' => $threadId,
				'mw_parent_id' => $parentId,
				'mw_user_id' => $userId,
				'mw_timestamp' => $dbw->timestamp(),
				'mw_latest_rev_id' => $revisionID
			),
			array( 'mw_thread_id' ),
			array(
				'mw_parent_id' => $parentId,
				'mw_user_id' => $userId,
				'mw_timestamp' => $dbw->timestamp(),
				'mw_latest_rev_id' => $revisionID
			),
			__METHOD__
		);
	}
	
	/**
	 * Get a message wall thread
	 *
	 * @param string $threadId
	 * @return array|null
	 */
	public static function get( $threadId ) {
		$dbr = wfGetDB( DB_REPLICA );
		
		$row = $dbr->selectRow(
			'message_wall',
			array(
				'mw_thread_id',
				'mw_parent_id',
				'mw_user_id',
				'mw_timestamp',
				'mw_latest_rev_id'
			),
			array( 'mw_thread_id' => $threadId ),
			__METHOD__
		);

		if ( $row ) {
			return array(
				'threadId' => $row->mw_thread_id,
				'parentId' => $row->mw_parent_id,
				'userId' => $row->mw_user_id,
				'timestamp' => $row->mw_timestamp,
				'latestRevId' => $row->mw_latest_rev_id
			);
		}
	
		return null;
	}

	/**
	 * Get the replies to a message wall thread
	 *
	 * @param string $parentId
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public static function getReplies( $parentId, $limit, $offset ) {
		$dbr = wfGetDB( DB_REPLICA );
	
		$res = $dbr->select(
			'message_wall',
			array(
				'mw_thread_id',
				'mw_parent_id',
				'mw_user_id',
				'mw_timestamp',
				'mw_latest_rev_id'
			),
			array( 'mw_parent_id' => $parentId ),
			__METHOD__,
			array( 'LIMIT' => $limit, 'OFFSET' => $offset )
		);
	
		$replies = array();
	
		foreach ( $res as $row ) {
			$replies[] = array(
				'threadId' => $row->mw_thread_id,
				'parentId' => $row->mw_parent_id,
				'userId' => $row->mw_user_id,
				'timestamp' => $row->mw_timestamp,
				'latestRevId' => $row->mw_latest_rev_id
			);
		}
	
		return $replies;
	}
	
	/**
	 * Get the number of replies to a message wall thread
	 *
	 * @param string $parentId
	 * @return int
	 */
	public static function getReplyCount( $parentId ) {
		$dbr = wfGetDB( DB_REPLICA );
	
		$res = $dbr->select(
			'message_wall',
			array( 'COUNT(*) AS reply_count' ),
			array( 'mw_parent_id' => $parentId ),
			__METHOD__
		);
	
		$row = $res->fetchRow();

		return (int)$row->reply_count;
	}

	/**
	 * Delete a message wall thread
	 *
	 * @param string $threadId
	 */
	public static function delete( $threadId ) {
		$dbw = wfGetDB( DB_MASTER );

		// Delete the thread from the database
		$dbw->delete(
			'message_wall',
			array( 'mw_thread_id' => $threadId ),
			__METHOD__
		);
	}
}
