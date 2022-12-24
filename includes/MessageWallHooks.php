<?php

/**
 * Hook functions for the Message Wall extension
 *
 * @file
 * @ingroup Extensions
 * @author Universal Omega
 * @license GPL-3.0-or-later
 */

class MessageWallHooks {

	/**
	 * Hook function called before a page is displayed
	 *
	 * @param OutputPage $out
	 * @param Skin $skin
	 * @return bool
	 */
	public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
		// Add the extension's styles and scripts to the page
		$out->addModules( 'ext.messageWall.styles' );
		$out->addModules( 'ext.messageWall.scripts' );

		return true;
	}

	/**
	 * Hook function called when the extension is installed or upgraded
	 *
	 * @param DatabaseUpdater $updater
	 * @return bool
	 */
	public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		// Run the SQL scripts to create or update the extension's database tables
		$updater->addExtensionTable( 'message_wall', __DIR__ . '/sql/message_wall.sql' );
		$updater->addExtensionTable( 'message_wall_history', __DIR__ . '/sql/message_wall_history.sql' );
		$updater->addExtensionTable( 'message_wall_user_talk_archive', __DIR__ . '/sql/message_wall_user_talk_archive.sql' );

		return true;
	}

	/**
	 * Hook function called after an article is saved
	 *
	 * @param WikiPage $article
	 * @param User $user
	 * @param string $summary
	 * @param bool $minoredit
	 * @param bool $watchthis
	 * @param bool $sectionanchor
	 * @param int $flags
	 * @param int $revision
	 * @param int $status
	 * @param int $baseRevId
	 * @return bool
	 */
	public static function onPageSaveComplete( WikiPage $wikiPage, MediaWiki\User\UserIdentity $user, string $summary, int $flags, MediaWiki\Revision\RevisionRecord $revisionRecord, MediaWiki\Storage\EditResult $editResult ) {
		// Check if the article is a message wall thread
		if ( $wikiPage->getTitle()->getNamespace() == NS_MESSAGE_WALL ) {
			// Save the message wall thread
			MessageWallThread::save( $wikiPage, $revisionRecord->getID() );
		}

		return true;
	}

	/**
	 * Hook function called after an article is deleted
	 *
	 * @param WikiPage $article
	 */
	public static function onArticleDeleteComplete( WikiPage $article, User $user, $reason, $articleId ) {
		// Check if the article is a message wall thread
		if ( $article->getTitle()->getNamespace() == NS_MESSAGE_WALL ) {
			// Delete the message wall thread
			MessageWallThread::delete( $articleId );
		}

		return true;
	}


	/**
	 * Called when a user profile is being displayed
	 *
	 * @param User $user
	 * @param array &$profile
	 * @return bool
	 */
	public static function onUserProfileBeginLeft( User $user, array &$profile ) {
		// Add the Message Wall tab to the user profile
		$profile['message_wall'] = array(
			'text' => wfMessage( 'message-wall-tab' )->text(),
			'href' => SpecialPage::getTitleFor( 'MessageWall', $user->getName() )->getLocalURL(),
		);

		return true;
	}

	/**
	 * Called when a new message is being posted on the Message Wall
	 *
	 * @param User $user
	 * @param string $message
	 * @param Title $title
	 * @return bool
	 */
	public static function onMessageWallPost( User $user, $message, Title $title ) {
		// Send a notification email to the user whose wall the message was posted on
		$recipient = User::newFromName( $title->getText() );
		if ( $recipient ) {
			$subject = wfMessage( 'message-wall-notification-subject' )->text();
			$body = wfMessage( 'message-wall-notification-body' )->text();
			$recipient->sendMail( $subject, $body );
		}

		return true;
	}
}
