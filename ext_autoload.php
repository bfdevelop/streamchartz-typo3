<?php

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('streamchartz_api');

return array(
	'Bluforce\\StreamchartzApi' => $extensionPath . '/Classes',
	'Curl\\' => $extensionPath . 'Vendors/php-curl-class/src/Curl',
	'Curl\\Curl' => $extensionPath . 'Vendors/php-curl-class/src/Curl/Curl.php',	
	'Curl\\CaseInsensitiveArray' => $extensionPath . 'Vendors/php-curl-class/src/Curl/CaseInsensitiveArray.php',
	'RestClient' => $extensionPath . 'Vendors/php-restclient-master/restclient.php',
);
?>