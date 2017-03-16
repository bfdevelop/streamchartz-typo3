<?php

namespace Bluforce\StreamchartzApi\Hooks;
/*
require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('frontend') . 'Classes/Page/PageRepository.php';
require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('core') . 'Classes/TypoScript/TemplateService.php';
require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('core') . 'Classes/TypoScript/ExtendedTemplateService.php';
*/
/**
 * some tools and helpers
 *
 */
class HelperTools {

    /**
     * @param int $pageUid [optional] the current pageuid
     * @return type
     */
    public static function loadTS($pageUid=NULL) {
        
		$pageUid = ($pageUid && \TYPO3\CMS\Core\Utility\MathUtility::canBeInterpretedAsInteger($pageUid)) ? $pageUid : \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id');
        $sysPageObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
        $TSObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\TemplateService');
        $TSObj->tt_track = 0;
        $TSObj->init();
        $TSObj->runThroughTemplates($sysPageObj->getRootLine($pageUid));
        $TSObj->generateConfig();
		
        return $TSObj->setup;
    }

}

