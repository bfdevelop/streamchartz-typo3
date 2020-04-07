<?php

return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:streamchartz_api/Resources/Private/Language/locallang_db.xlf:tx_streamchartzapi_domain_model_wall',
		'label' => 'wall_id',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',

		'enablecolumns' => array(

		),
		'searchFields' => 'wall_id,view_id,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('streamchartz_api') . 'Resources/Public/Icons/tx_streamchartzapi_domain_model_wall.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, wall_id, view_id',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, wall_id, view_id, '),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_streamchartzapi_domain_model_wall',
				'foreign_table_where' => 'AND tx_streamchartzapi_domain_model_wall.pid=###CURRENT_PID### AND tx_streamchartzapi_domain_model_wall.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		'wall_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:streamchartz_api/Resources/Private/Language/locallang_db.xlf:tx_streamchartzapi_domain_model_wall.wall_id',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'view_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:streamchartz_api/Resources/Private/Language/locallang_db.xlf:tx_streamchartzapi_domain_model_wall.view_id',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		
	),
);