<?php

/**
 * Message Wall controller class
 *
 * @file
 * @ingroup Extensions
 * @author Universal Omega
 * @license GPL-3.0-or-later
 */

class MessageWallController {

	/**
	 * Save a message to the database
	 *
	 * @param string $username The username of the user whose wall the message is being posted on
	 * @param string $message The message being posted
	 * @param User $user The user who is posting the message
	 */
	public static function saveMessage( $username, $message, User $user ) {
		global $wgDBname;
		$dbw = wfGetDB( DB_PRIMARY );
		$dbw->insert(
			"$wgDBname.message_wall",
			array(
				'mw_username' => $username,
				'mw_user_id' => $user->getId(),
				'mw_user_name' => $user->getName(),
				'mw_message' => $message,
				'mw_timestamp' => wfTimestampNow(),
			),
			__METHOD__
		);
	}

	/**
	 * Get the messages for a given user
	 *
	 * @param string $username The username of the user whose messages are being retrieved
	 * @return array An array of messages
	 */
	public static function getMessages( $username ) {
		global $wgDBname;
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select(
			"$wgDBname.message_wall",
			array(
				'mw_message',
			),
			array( 'mw_username' => $username ),
			__METHOD__,
			array( 'ORDER BY' => 'mw_timestamp DESC' )
		);
		$messages = array();
		foreach ( $res as $row ) {
			$messages[] = array( 'message' => $row->mw_message );
		}
		return $messages;
	}

}
