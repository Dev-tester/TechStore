<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Localization\Loc;

return [
	'block' => [
		// 'name' => Loc::getMessage('LANDING_BLOCK_MENU_25-NAME'),
		'section' => array('sidebar', 'menu'),
		'dynamic' => false,
		'subtype' => 'menu',
		'subtype_params' => array(
			'source' => 'catalog',
		),
		'version' => '19.0.300',	//and may be more
	],
	'cards' => [
		'.landing-block-node-list-item' => [
			'name' => Loc::getMessage('LANDING_BLOCK_MENU_25-LINK'),
			'label' => ['.landing-block-node-link'],
		],
	],
	'nodes' => [
		'.landing-block-node-link' => [
			'name' => Loc::getMessage('LANDING_BLOCK_MENU_25-LINK'),
			'type' => 'link',
		],
	],
	'style' => [
		'block' => [
			'type' => ['block-default', 'block-border'],
		],
		'nodes' => [
			'.landing-block-node-list-item' => [
				'name' => Loc::getMessage('LANDING_BLOCK_MENU_25-LINK'),
				'type' => ['border-color', 'border-width'],
			],
			'.landing-block-node-link' => [
				'name' => Loc::getMessage('LANDING_BLOCK_MENU_25-LINK'),
				'type' => ['text-align', 'typo-simple'],
			],
			'.landing-block-node-navbar' => [
				'name' => Loc::getMessage('LANDING_BLOCK_MENU_25-NAVBAR'),
				'type' => ['navbar'],
			],
		],
	],
	'assets' => [
		'ext' => ['landing_menu'],
	],
];