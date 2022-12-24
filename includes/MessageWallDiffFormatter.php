<?php

/**
 * Message Wall diff formatter class
 *
 * @file
 * @ingroup Extensions
 * @author Universal Omega
 * @license GPL-3.0-or-later
 */

class MessageWallDiffFormatter {

	/**
	 * The ID of the thread
	 *
	 * @var int
	 */
	protected $threadId;

	/**
	 * The ID of the message or reply
	 *
	 * @var int
	 */
	protected $messageId;

	/**
	 * The old version of the message or reply
	 *
	 * @var MessageWallMessage
	 */
	protected $oldMessage;

	/**
	 * The new version of the message or reply
	 *
	 * @var MessageWallMessage
	 */
	protected $newMessage;

	/**
	 * Construct a new MessageWallDiffFormatter object
	 *
	 * @param int $threadId The ID of the thread
	 * @param int $messageId The ID of the message or reply
	 * @param MessageWallMessage $oldMessage The old version of the message or reply
	 * @param MessageWallMessage $newMessage The new version of the message or reply
	 */
	public function __construct( $threadId, $messageId, MessageWallMessage $oldMessage, MessageWallMessage $newMessage ) {
		$this->threadId = $threadId;
		$this->messageId = $messageId;
		$this->oldMessage = $oldMessage;
		$this->newMessage = $newMessage;
	}

	/**
	 * Format the differences between the old and new versions of the message or reply
	 *
	 * @return string
	 */
	public function format() {
		// Compare the old and new versions of the message or reply text
		$textDiff = new DifferenceEngine;
		$textDiff->setText( $this->oldMessage->getText(), $this->newMessage->getText() );
		$formattedTextDiff = $textDiff->getDiff( 'context' );

		// Compare the old and new versions of the message or reply signature
		$signatureDiff = new DifferenceEngine;
		$signatureDiff->setText( $this->oldMessage->getSignature(), $this->newMessage->getSignature() );
		$formattedSignatureDiff = $signatureDiff->getDiff( 'context' );

		// Combine the formatted text and signature diffs
		$diff = $formattedTextDiff . "\n" . $formattedSignatureDiff;

		// Add a header and footer to the diff
		$header = Html::element( 'h2', [], wfMessage( 'message-wall-diff-header', $this->threadId, $this->messageId )->text() );
		$footer = Html::element( 'p', [], wfMessage( 'message-wall-diff-footer' )->text() );
		$diff = $header . "\n" . $diff . "\n" . $footer;

		// Return the formatted diff
		return $diff;
	}
}

