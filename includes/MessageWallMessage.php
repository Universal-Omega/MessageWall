<?php

/**
 * Message Wall message class
 *
 * @file
 * @ingroup Extensions
 * @author Universal Omega
 * @license GPL-3.0-or-later
 */

class MessageWallMessage {

	/**
	 * The text of the message
	 *
	 * @var string
	 */
	protected $text;

	/**
	 * The user who posted the message
	 *
	 * @var User
	 */
	protected $user;

	/**
	 * The timestamp when the message was posted
	 *
	 * @var string
	 */
	protected $timestamp;

	/**
	 * Construct a new MessageWallMessage object
	 *
	 * @param string $text The text of the message
	 * @param User $user The user who posted the message
	 * @param string $timestamp The timestamp when the message was posted
	 */
	public function __construct( $text, User $user, $timestamp = null ) {
		$this->text = $text;
		$this->user = $user;
		if ( $timestamp === null ) {
			$this->timestamp = wfTimestampNow();
		} else {
			$this->timestamp = $timestamp;
		}
	}

	/**
	 * Get the text of the message
	 *
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * Get the user who posted the message
	 *
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Get the timestamp when the message was posted
	 *
	 * @return string
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * Render the message to HTML
	 *
	 * @return string
	 */
	public function render() {
		$output = '<p><strong>' . $this->user->getName() . '</strong> (' . $this->timestamp . '):</p>';
		$output .= '<p>' . $this->text . '</p>';

		return $output;
	}
}
