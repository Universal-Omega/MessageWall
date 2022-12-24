<?php

/**
 * Message Wall thread class
 *
 * @file
 * @ingroup Extensions
 * @author Universal Omega
 * @license GPL-3.0-or-later
 */

class MessageWallThread {

	/**
	 * The ID of the thread
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * The user who started the thread
	 *
	 * @var User
	 */
	protected $user;

	/**
	 * The title of the thread
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * The messages in the thread
	 *
	 * @var array
	 */
	protected $messages = array();

	/**
	 * Construct a new MessageWallThread object
	 *
	 * @param int $id The ID of the thread
	 * @param User $user The user who started the thread
	 * @param string $title The title of the thread
	 */
	public function __construct( $id, User $user, $title ) {
		$this->id = $id;
		$this->user = $user;
		$this->title = $title;
	}

	/**
	 * Get the ID of the thread
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Get the user who started the thread
	 *
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Get the title of the thread
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Get the messages in the thread
	 *
	 * @return array
	 */
	public function getMessages() {
		return $this->messages;
	}

	/**
	 * Add a message to the thread
	 *
	 * @param MessageWallMessage $message The message to add
	 */
	public function addMessage( MessageWallMessage $message ) {
		$this->messages[] = $message;
	}
}
