$( function () {
	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		// Initialize the message wall
		$content.find( '.message-wall' ).MessageWall();
	} );
} );

/**
 * MessageWall extension
 *
 * @class mw.MessageWall
 * @singleton
 */
( function ( $, mw ) {
	'use strict';

	var MessageWall;

	MessageWall = function () {
		var self = this;

		// Set up the message wall
		self.init();
	};

	MessageWall.prototype.init = function () {
		var self = this;

		// Set up event handlers
		self.setupEventHandlers();
	};

	MessageWall.prototype.setupEventHandlers = function () {
		var self = this;

		// Add a new reply to a thread
		$( '.message-wall-add-reply' ).on( 'click', function ( e ) {
			e.preventDefault();

			// Show the reply form
			self.showReplyForm();
		} );

		// Cancel adding a reply
		$( '.message-wall-cancel-reply' ).on( 'click', function ( e ) {
			e.preventDefault();

			// Hide the reply form
			self.hideReplyForm();
		} );

		// Submit a reply
		$( '.message-wall-submit-reply' ).on( 'click', function ( e ) {
			e.preventDefault();

			// Add the reply to the thread
			self.addReply();
		} );
	};

	MessageWall.prototype.showReplyForm = function () {
		// Show the reply form
		$( '.message-wall-reply-form' ).show();
	};

	MessageWall.prototype.hideReplyForm = function () {
		// Hide the reply form
		$( '.message-wall-reply-form' ).hide();
	};

	MessageWall.prototype.addReply = function () {
		var self = this,
			$form = $( '.message-wall-reply-form' ),
			threadId = $form.data( 'thread-id' ),
			parentId = $form.data( 'parent-id' ),
			message = $form.find( '.message-wall-reply-message' ).val();

	// Make an AJAX request to add the reply to the database
	$.post(
		mw.config.get( 'wgScriptPath' ) + '/api.php',
		{
			action: 'addreply',
			format: 'json',
			threadid: threadId,
			parentid: parentId,
			message: message
		},
		function ( data ) {
			if ( data.result === 'success' ) {
				// Reload the page to show the new reply
				location.reload();
			} else {
				// Show an error message
				self.showError( data.error.info );
			}
		}
	);
};

MessageWall.prototype.showError = function ( message ) {
	// Show the error message
	$( '.message-wall-error' ).text( message ).show();
};

// Create the message wall
mw.MessageWall = new MessageWall();

}( jQuery, mediaWiki ) );

