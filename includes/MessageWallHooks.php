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
