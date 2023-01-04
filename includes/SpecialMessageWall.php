<?php

/**
 * Special page to display a message wall
 *
 * @ingroup SpecialPage
 */
class SpecialMessageWall extends SpecialPage {
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'MessageWall' );
	}

	/**
	 * Main execution point
	 *
	 * @param string|null $subPage
	 */
	public function execute( $subPage ) {
		global $wgOut;

		// Check user permissions
		$this->checkPermissions();

		// Set the page title and add CSS
		$this->setHeaders();
		$wgOut->addModuleStyles( 'ext.MessageWall.styles' );

		// Get the thread ID and parent ID from the subpage parameter
		$threadId = $subPage;
		$parentId = null;
		if ( strpos( $subPage, '/' ) !== false ) {
			list( $threadId, $parentId ) = explode( '/', $subPage );
		}

		// Get the thread from the database
		$thread = MessageWallThread::get( $threadId );

		// Check if the thread exists
		if ( !$thread ) {
			$wgOut->addWikiMsg( 'messagewall-thread-doesnt-exist' );
			return;
		}

		// Output the thread and its replies
		$wgOut->addHTML( self::getThreadHTML( $thread ) );
		$wgOut->addHTML( self::getRepliesHTML( $threadId, $parentId ) );
	}

	/**
	 * Get the HTML for a message wall thread
	 *
	 * @param array $thread
	 * @return string
	 */
	public static function getThreadHTML( $thread ) {
		// Get the user who created the thread
		$user = User::newFromId( $thread['userId'] );

		// Get the latest revision of the thread
		$latestRevision = Revision::newFromId( $thread['latestRevId'] );

		// Get the thread's content
		$content = ContentHandler::getContentText( $latestRevision->getContent() );

		// Build the HTML for the thread
		$html = Html::openElement( 'div', array( 'class' => 'message-wall-thread' ) );
		$html .= Html::openElement( 'div', array( 'class' => 'message-wall-thread-header' ) );
		$html .= Html::element( 'a', array( 'href' => $user->getUserPage()->getLinkURL() ), $user->getName() );
		$html .= Html::closeElement( 'div' );
		$html .= Html::openElement( 'div', array( 'class' => 'message-wall-thread-content' ) );
		$html .= $content;
		$html .= Html::closeElement( 'div' );
		$html .=
		$html .= Html::openElement( 'div', array( 'class' => 'message-wall-thread-footer' ) );
		$html .= Html::element( 'a', array( 'class' => 'message-wall-add-reply', 'href' => '#' ), wfMessage( 'messagewall-add-reply' )->plain() );
		$html .= Html::closeElement( 'div' );
		$html .= Html::closeElement( 'div' );

		return $html;
	}

	/**
	 * Get the HTML for the replies to a message wall thread
	 *
	 * @param string $threadId
	 * @param string|null $parentId
	 * @return string
	 */
	public static function getRepliesHTML( $threadId, $parentId ) {
		// Get the replies from the database
		$replies = MessageWallThread::getReplies( $threadId, 10, 0 );

		// Build the HTML for the replies
		$html = Html::openElement( 'div', array( 'class' => 'message-wall-replies' ) );
		foreach ( $replies as $reply ) {
			$user = User::newFromId( $reply['userId'] );
			$revision = Revision::newFromId( $reply['revId'] );
			$content = ContentHandler::getContentText( $revision->getContent() );
			$html .= Html::openElement( 'div', array( 'class' => 'message-wall-reply' ) );
			$html .= Html::openElement( 'div', array( 'class' => 'message-wall-reply-header' ) );
			$html .= Html::element( 'a', array( 'href' => $user->getUserPage()->getLinkURL() ), $user->getName() );
			$html .= Html::closeElement( 'div' );
			$html .= Html::openElement( 'div', array( 'class' => 'message-wall-reply-content' ) );
			$html .= $content;
			$html .= Html::closeElement( 'div' );
			$html .= Html::openElement( 'div', array( 'class' => 'message-wall-reply-footer' ) );
			$html .= Html::element( 'a', array( 'class' => 'message-wall-add-reply', 'href' => '#' ), wfMessage( 'messagewall-add-reply' )->plain() );
			$html .= Html::closeElement( 'div' );
			$html .= Html::closeElement( 'div' );
		}
		$html .= Html::closeElement( 'div' );

		return $html;
	}
}
