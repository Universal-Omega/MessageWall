<?php

/**
 * Message Wall history class
 *
 * @file
 * @ingroup Extensions
 * @author Universal Omega
 * @license GPL-3.0-or-later
 */

class MessageWallHistory {

	/**
	 * The ID of the thread
	 *
	 * @var int
	 */
	protected $threadId;

	/**
	 * The messages and replies in the thread
	 *
	 * @var array
	 */
	protected $history = array();

	/**
	 * Construct a new MessageWallHistory object
	 *
	 * @param int $threadId The ID of the thread
	 */
	public function __construct( $threadId ) {
		$this->threadId = $threadId;
	}

	/**
	 * Get the messages and replies in the thread
	 *
	 * @return array
	 */
	public function getHistory() {
		return $this->history;
	}

	/**
	 * Add a message or reply to the thread history
	 *
	 * @param MessageWallMessage $message The message or reply to add
	 */
	public function addToHistory( MessageWallMessage $message ) {
		$this->history[] = $message;
	}

}
