<?php

/**
 * Message Wall user talk archive class
 *
 * @file
 * @ingroup Extensions
 * @author Universal Omega
 * @license GPL-3.0-or-later
 */

class MessageWallUserTalkArchive {

	/**
	 * The user whose talk page this is
	 *
	 * @var User
	 */
	protected $user;

	/**
	 * The threads in the archive
	 *
	 * @var array
	 */
	protected $threads = array();

	/**
	 * Construct a new MessageWallUserTalkArchive object
	 *
	 * @param User $user The user whose talk page this is
	 */
	public function __construct( User $user ) {
		$this->user = $user;
	}

	/**
	 * Get the threads in the archive
	 *
	 * @return array
	 */
	public function getThreads() {
		return $this->threads;
	}

	/**
	 * Add a thread to the archive
	 *
	 * @param MessageWallThread $thread The thread to add
	 */
	public function addThread( MessageWallThread $thread ) {
		$this->threads[] = $thread;
	}

}
