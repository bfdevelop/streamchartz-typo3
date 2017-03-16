<?php

namespace Bluforce\StreamchartzApi\Hooks;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Bluforce\StreamchartzApi\Hooks\RestApi;

/**
 * Userfunc to render alternative label for media elements
 *
*/
class ItemsProcFunc
{

    private $ts;
    private $pid;
    private $uid;
    private $token;

    /**
     * Modifies the select box
          *
     * @param array &$config configuration array
     * @return void
     */
    public function wallList(array &$config) {

		$this->getPageId($config);

	    $this->loadTS($this->pid); //id set by parse_str

	    if(!empty($this->token)) {

	        $api = new RestApi();
	        $api->setAccessToken ($this->token);
	        $walls = $api->getWallList('out', 1);

	        if(empty($walls)) return;
	        $list = $walls->items;

	        for ($i=2; $i < $walls->_meta->pageCount+1; $i++) {

				$walls = $api->getWallList('out', $i);
				if($walls) {
					$list = array_merge($list, $walls->items);
				}
	        }

	        foreach ($list as $wall) {
	            array_push($config['items'], [ $wall->name, $wall->id]);
	        }

			$v = [];
			// Obtain a list of columns
			foreach ($config['items'] as $key => $row) {
				$v[$key]  = $row[0];
			}

			// Sort the data with volume descending, edition ascending
			// Add $data as the last parameter, to sort by the common key
			array_multisort($v, SORT_ASC, $config['items']);

	    } else {
	        array_push($config['items'], [ 0 => 'Access token missing']);
	    }

    }

   public function outList(&$config) {

   		$this->getPageId($config);

		$this->loadTS($this->pid); //id set by parse_str

        if(!empty($this->token)) {

            $row = $this->getContentElementRow($this->uid);

            if (is_array($row) && !empty($row['pi_flexform'])) {

                $flexformConfig = GeneralUtility::xml2array($row['pi_flexform']);
                // check if there is a flexform configuration
                if (isset($flexformConfig['data']['sDEF']['lDEF'])) {

                    if(!$flexformConfig['data']['sDEF']['lDEF']['settings.wallId']['vDEF']) return;

                    $api = new RestApi();
                    $api->setAccessToken ($this->token);

                    $walls = $api->getWallInfo($flexformConfig['data']['sDEF']['lDEF']['settings.wallId']['vDEF'], 'out');

                    $config['items'] = [];

                    if(isset($walls->out)) {
                        foreach ($walls->out as $out) {
                            array_push($config['items'], [ $out->description, $out->id.'-'.$out->wall_key]);
                        }
                    }

                }

          }
        } else {
            array_push($config['items'], [ 0 => 'Access token missing']);
        }
    }

    function getPageId($config) {

    	$this->pid = !empty($config['row']['pid']) ? $config['row']['pid'] : $config['flexParentDatabaseRow']['pid'];
    	$this->uid = !empty($config['row']['uid']) ? $config['row']['uid'] : $config['flexParentDatabaseRow']['uid'];

    	//$this->pid = 0;

    	if(!$this->pid || !$this->uid ) { // no page id found

    		$g_uid = array_keys($_GET['edit']['tt_content']);

    		$row = $this->getContentElementRow(intval($g_uid[0]));

    		$this->uid = $row['uid'];
    		$this->pid = $row['pid'];

    	}

    }

    /**
     * Get tt_content record
     *
     * @param int $uid
     * @return array
     */
    protected function getContentElementRow($uid)
    {
        return BackendUtilityCore::getRecord('tt_content', $uid);
    }

    /**
     * Returns LanguageService
     *
     * @return \TYPO3\CMS\Lang\LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

    public function loadTS($pageUid=NULL) {

		$pageUid = ($pageUid && \TYPO3\CMS\Core\Utility\MathUtility::canBeInterpretedAsInteger($pageUid)) ? $pageUid : \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('id');
        $sysPageObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');

		$TSObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\TemplateService');
        $TSObj->tt_track = false;
        $TSObj->debug = false;
        $TSObj->init();

		$rootData = $sysPageObj->getRootLine($pageUid);

        $TSObj->runThroughTemplates($sysPageObj->getRootLine($pageUid));
        $TSObj->generateConfig();

        $this->ts = $TSObj->setup['plugin.']["tx_streamchartz."];

        if(empty($this->ts["api."]) || empty($this->ts["api."]["accessToken"])) {
        	$this->token = 'none';
        } else {
        	$this->token = $this->ts["api."]["accessToken"];
        }

        return $this->ts;
    }


}