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

// Register the hooks
$wgHooks['BeforePageDisplay'][] = 'MessageWallHooks::onBeforePageDisplay';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'MessageWallHooks::onLoadExtensionSchemaUpdates';
$wgHooks['ArticleSaveComplete'][] = 'MessageWallHooks::onArticleSaveComplete';
$wgHooks['ArticleDeleteComplete'][] = 'MessageWallHooks::onArticleDeleteComplete';
$wgHooks['UserProfileBeginLeft'][] = 'MessageWallHooks::onUserProfileBeginLeft';

// Register the extension's ResourceLoader modules
$wgResourceModules += array_merge( $wgResourceModules, array(
	'ext.messageWall.styles' => array(
		'class' => 'ResourceLoaderModule',
		'remoteBasePath' => $GLOBALS['wgScriptPath'],
		'localBasePath' => __DIR__,
		'resources' => array(
			array(
				'type' => 'style',
				'src' => 'resources/messageWall.css'
			)
		)
	),
	'ext.messageWall.scripts' => array(
		'class' => 'ResourceLoaderModule',
		'remoteBasePath' => $GLOBALS['wgScriptPath'],
		'localBasePath' => __DIR__,
		'resources' => array(
			array(
				'type' => 'script',
				'src' => 'resources/messageWall.js'
			)
		)
	)
) );
