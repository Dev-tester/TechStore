<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => '/bitrix/js/ui/tutor/ui.tutor.css',
	'js' => '/bitrix/js/ui/tutor/dist/tutor.bundle.js',
	'rel' => [
		'main.core',
		'ui.tour',
		'main.loader',
	],
	'skip_core' => false,
];