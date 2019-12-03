<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Localization\Loc;

return [
	'block' => [
		// 'name' => Loc::getMessage('LANDING_BLOCK_59_2-NAME'),
		'section' => array('sidebar', 'other'),
		'type' => 'knowledge',
		'version' => '19.0.300',	//and may be more
	],
	'nodes' => [
		// todo: need for style hover. If not exist nodes elemtent - button will be pointer-events:none
		// '.landing-block-node-button' => [
		// 	'name' => Loc::getMessage('LANDING_BLOCK_59_2-BUTTON'),
		// 	'type' => 'link',
		// ],
	],
	'style' => [
		'.landing-block-node-button' => [
			'name' => Loc::getMessage('LANDING_BLOCK_59_2-BUTTON'),
			'type' => ['button'],
		],
	],
	'attrs' => [
		'.landing-block-node-form' => [
			'name' => 'Search result page',
			'attribute' => 'action',
			'type' => 'url',
			'disableBlocks' => true,
			'disableCustomURL' => true
		]
	]
];