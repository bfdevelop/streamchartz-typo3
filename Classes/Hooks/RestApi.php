<?php

namespace Bluforce\StreamchartzApi\Hooks;

use RestClient;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RestApi {

   const API_URL = 'http://streamchartz.com';

   private $access_token = null;

   public function getAccessToken () {
        return $this->access_token;
   }

   public function setAccessToken ($token) {
        $this->access_token = $token;
   }

   function getWallList ($expand = '', $page = 1) {

        $q = [];
        if ($expand) {
           $q['expand'] = $expand;
        }
        $q['page'] = $page;

        return $this->_call ('/api/wall', $q);

   }

   function getWallInfo ($id, $expand = '') {

        $q = ['id' => $id];
        if ($expand) {
           $q['expand'] = $expand;
        }

        return $this->_call ('/api/wall/view', $q);

   }

   private function _call ($url, $params = array()) {

        $params['access-token'] = $this->access_token;

        $api = new RestClient(array(
            'base_url' => self::API_URL,
            //'format' => "json",
            'headers' => array('Accept' => 'application/json'),
        ));
		$cid = sha1($url.'_'.implode($params));

		if (($entry = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('streamchartz_mycache')->get($cid)) !== FALSE) {
			return json_decode($entry);
		}
		
		$result = $api->get($url, $params);

		if($result->info->http_code == 200) {
			$responce = $result->decode_response();
			
			// Save value in cache
			GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('streamchartz_mycache')->set($cid, json_encode($responce), ['babba'], 1200);
			
			return $responce;
		}
		
		

        return false;
   }


}