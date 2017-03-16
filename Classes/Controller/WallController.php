<?php
namespace Bluforce\StreamchartzApi\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Marko Ellermaa <m.ellermaa@bluforce.at>, bluforce
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Curl\Curl;
use Bluforce\StreamchartzApi\Hooks\ItemsProcFunc;
use Bluforce\StreamchartzApi\Hooks\RestApi;

/**
 * WallController
 */
class WallController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

	const debug = false;
	const remoteGetApi = 'streamchartz.com/fwall/posts';

	private $token;

    /**
     * wallRepository
     *
     * @var \Bluforce\StreamchartzApi\Domain\Repository\WallRepository
     * @inject
     */
    protected $wallRepository = NULL;

    /**
     * action list
     *
     * @return void
     */
    public function listAction() {

    	try {

			$data = $this->configurationManager->getContentObject ()->data;

			// Append flexform values
			$this->configurationManager->getContentObject ()->readFlexformIntoConf ( $data ['pi_flexform'], $data );

			$ht = '';

			if ($data ['settings.wallId']) {

				// view is not found (selected) use first automatically
				if(!isset($data ['settings.viewId'])) {

					$ts = $this->loadTs($data['pid']);

					$this->token = $ts["api."]["accessToken"];

					$api = new RestApi();
					$api->setAccessToken ($this->token);

					$walls = $api->getWallInfo($data ['settings.wallId'], 'out');

					$items = [];

					if(isset($walls->out)) {
						foreach ($walls->out as $out) {
							array_push($items, [ $out->description, $out->id, $out->wall_key]);
						}
					}

					$dk = [$items[0][1], $items[0][2]];
				} else {
					$dk = explode ( '-', $data['settings.viewId'] );
				}

				$ht = $this->getWall ( [
						'wall_id' => $dk[0],
						'key' => $dk[1],
						'lang' => $GLOBALS ['TSFE']->sys_language_isocode
				] );
			} else {
				$ht = 'Failed to load wallId. '
				.(	isset($data['pi_flexform']) ? htmlspecialchars( var_export($data['pi_flexform'], true)) : 'pi_flexform missing.');
			}
		} catch ( \Exception $e ) {
			$ht = 'Caught exception: ' . $e->getMessage ();
		}

		// $walls = $this->wallRepository->findAll();
		$this->view->assign ( 'walls', $ht );

		return null;
    }

	/*
	 * Load wall from remote server
	 * */
	private function getWall($query) {

		$curl = new Curl();
		$curl->setUserAgent('streamchartz client v1.0');
		$curl->setReferer((self::isSecure() ? 'https://' : 'http://' ).$_SERVER['HTTP_HOST']);
		$curl->setTimeout(20); // stop query when takes longer than 20 sec
		$curl->get((self::isSecure() ? 'https://' : 'http://' ).self::remoteGetApi, $query);

		if ($curl->error) {
			if(self::debug) {
				echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage.' '.$curl->response ;
			}
		   return false;
		} else {
		    return $curl->response;
		}

		$curl->close();

	}

	static private function isSecure() {
	  return
	    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
	    || $_SERVER['SERVER_PORT'] == 443;
	}

	private function loadTs($pid) {

		$wq = new ItemsProcFunc();

		return $wq->loadTS($pid);
	}

}