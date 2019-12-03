<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/extranet/public/search/.left.menu.php");
$aMenuLinks = Array(
	Array(
		GetMessage("EXTRANET_SEARCH_LEFT_MENU_SEARCH"),
		"/extranet/search/index.php", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		GetMessage("EXTRANET_SEARCH_LEFT_MENU_MAP"),
		"/extranet/search/map.php", 
		Array(), 
		Array(), 
		"" 
	)
);
?>