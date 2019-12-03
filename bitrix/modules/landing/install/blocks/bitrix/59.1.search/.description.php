<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Localization\Loc;

return [
	'block' => [
		// 'name' => Loc::getMessage('LANDING_BLOCK_59_1_NAME'),
		'section' => array('other'),
		'type' => 'knowledge',
		'version' => '19.0.300',	//and may be more
	],
	'nodes' => [
		'.landing-block-node-bgimage' => [
			'name' => Loc::getMessage('LANDING_BLOCK_59_1_BGIMAGE'),
			'type' => 'img',
			'dimensions' => ['width' => 1920, 'height' => 1080],
			'allowInlineEdit' => false,
		],
		'.landing-block-node-title' => [
			'name' => Loc::getMessage('LANDING_BLOCK_59_1_TITLE'),
			'type' => 'text',
		],
		'.landing-block-node-text' => [
			'name' => Loc::getMessage('LANDING_BLOCK_59_1_TEXT'),
			'type' => 'text',
		],
	],
	'style' => [
		'block' => [
			'type' => ['block-default-background-overlay-height-vh'],
		],
		'nodes' => [
			'.landing-block-node-title' => [
				'name' => Loc::getMessage('LANDING_BLOCK_59_1_TITLE'),
				'type' => ['typo'],
			],
			'.landing-block-node-text' => [
				'name' => Loc::getMessage('LANDING_BLOCK_59_1_TEXT'),
				'type' => ['typo'],
			],
		],
	],
	'attrs' => [
		'.landing-block-node-form' => [
			'name' => 'Search result page',
			'attribute' => 'action',
			'type' => 'url',
			'disableBlocks' => true,
			'disableCustomURL' => true,
		],
	],
];