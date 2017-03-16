<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Bluforce.' . $_EXTKEY,
	'Streamshow',
	array(	
		'Wall' => 'list',
	),	
	array(
		'Wall' => 'list',
	)
);

if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['streamchartz_mycache'])) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['streamchartz_mycache'] = array();
}

\Bluforce\StreamchartzApi\Utility\ClassLoader::registerAutoloader();
