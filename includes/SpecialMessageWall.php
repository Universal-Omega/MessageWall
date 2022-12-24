<?php

/**
 * Special page for the Message Wall extension
 *
 * @file
 * @ingroup Extensions
 * @author Universal Omega
 * @license GPL-3.0-or-later
 */

class SpecialMessageWall extends SpecialPage {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'MessageWall', 'read' );
	}

	/**
	 * Main method for the special page
	 *
	 * @param string|null $subPage
	 */
	public function execute( $subPage ) {
		$out = $this->getOutput();
		$request = $this->getRequest();
		$user = $this->getUser();

		// Check if the user has the required permissions
		/* if ( !$user->isAllowed( 'messagewall' ) ) {
			$out->permissionRequired( 'messagewall' );
			return;
		} */

		// Set the page title and add CSS and JavaScript files
		$out->setPageTitle( wfMessage( 'message-wall-title' ) );
		$out->addModules( 'ext.messageWall' );
		$out->addModuleStyles( 'ext.messageWall.styles' );

		// Get the user whose message wall is being displayed
		$targetUser = User::newFromName( $subPage );
		if ( !$targetUser || !$targetUser->isLoggedIn() ) {
			$out->addWikiMsg( 'message-wall-invalid-user' );
			return;
		}

		// Check if the user is trying to post a message
		if ( $request->wasPosted() && $request->getVal( 'action' ) === 'post' ) {
			$message = $request->getVal( 'message' );
			if ( $message ) {
				// Save the message to the database and send a notification
				MessageWall::saveMessage( $targetUser->getName(), $message, $user );
				MessageWallHooks::onMessageWallPost( $user, $message, $this->getPageTitle() );
				$out->addWikiMsg( 'message-wall-message-posted' );
			} else {
				$out->addWikiMsg( 'message-wall-message-empty' );
			}
		}

		// Display the message wall form
		$form = HTMLForm::factory( 'ooui', array(
			'message' => array(
				'type' => 'textarea',
				'label-message' => 'message-wall-message',
				'rows' => 5,
			),
			'submit' => array(
				'type' => 'submit',
				'value' => wfMessage( 'message-wall-submit' )->text(),
			),
		), $this->getContext() );
		$form->setWrapperLegendMsg( 'message-wall-form-legend' );
		$form->setAction( $this->getPageTitle()->getLocalURL() );
		$form->setMethod( 'post' );
		$form->addHiddenField( 'action', 'post' );
		$form->setSubmitCallback( function () {
			return true;
		} );
		$form->prepareForm();
		$form->displayForm( false );

		// Display the messages
		$messages = MessageWall::getMessages( $targetUser->getName() );
		$out->addHTML( '<div class="message-wall-messages">' );
		foreach ( $messages as $message ) {
			$out->addHTML( '<div class="message-wall-message">' );
			$out->addWikiText( $message['message'] );
			$out->addHTML( '</div>' );
		}
		$out->addHTML( '</div>' );
	}
}
