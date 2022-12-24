<?php

/**
 * Message Wall board class
 *
 * @file
 * @ingroup Extensions
 * @author Universal Omega
 * @license GPL-3.0-or-later
 */

class MessageWallBoard {

	/**
	 * The user whose talk page this is
	 *
	 * @var User
	 */
	protected $user;

	/**
	 * The threads on the board
	 *
	 * @var array
	 */
	protected $threads = array();

	/**
	 * Construct a new MessageWallBoard object
	 *
	 * @param User $user The user whose talk page this is
	 */
	public function __construct( User $user ) {
		$this->user = $user;
	}

	/**
	 * Get the threads on the board
	 *
	 * @return array
	 */
	public function getThreads() {
		return $this->threads;
	}

	/**
	 * Add a thread to the board
	 *
	 * @param MessageWallThread $thread The thread to add
	 */
	public function addThread( MessageWallThread $thread ) {
		$this->threads[] = $thread;
	}

}
