<?php

/**
 * MessageWall extension for MediaWiki
 *
 * @file
 * @ingroup Extensions
 * @author Universal Omega
 * @license GPL-3.0-or-later
 */

// Register the extension with MediaWiki
$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'MessageWall',
	'author' => 'Your Name',
	'url' => 'https://www.mediawiki.org/wiki/Extension:MessageWall',
	'descriptionmsg' => 'message-wall-description',
	'version' => '1.0',
);

// Autoload the extension classes
$autoloadClasses = array(
	'SpecialMessageWall' => 'SpecialMessageWall.php',
	'MessageWallHooks' => 'MessageWallHooks.php',
	'MessageWallController' => 'MessageWallController.php',
	'MessageWallThread' => 'MessageWallThread.php',
	'MessageWallHistory' => 'MessageWallHistory.php',
	'MessageWallUserTalkArchive' => 'MessageWallUserTalkArchive.php',
	'MessageWallDiffFormatter' => 'MessageWallDiffFormatter.php',
	'MessageWallBoard' => 'MessageWallBoard.php',
);

foreach ( $autoloadClasses as $class => $file ) {
	$wgAutoloadClasses[$class] = __DIR__ . '/includes/' . $file;
}

// Register the special page
$wgSpecialPages['MessageWall'] = 'SpecialMessageWall';

// Register the hook
$wgHooks['BeforePageDisplay'][] = 'MessageWallHooks::onBeforePageDisplay';

// Register the messages
$wgExtensionMessagesFiles['MessageWall'] = __DIR__ . '/MessageWall.i18n.php';

// Register the resource files
$wgResourceModules['ext.messageWall'] = array(
	'styles' => 'resources/messageWall.css',
	'scripts' => 'resources/messageWall.js',
	'messages' => array(
		'message-wall-message-count',
		'message-wall-history',
		'message-wall-thread-deleted',
		'message-wall-button-to-preview-comment',
		'message-wall-button-cancel-preview',
		'message-wall-button-post',
		'message-wall-button-preview',
		'message-wall-button-cancel',
		'message-wall-loading',
		'message-wall-preview',
		'message-wall-confirm-delete',
	),
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'MessageWall',
);

