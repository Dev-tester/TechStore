<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=<?=SITE_CHARSET?>"/>
	<link href="<?=CUtil::GetAdditionalFileURL(SITE_TEMPLATE_PATH."/template_styles.css")?>" type="text/css" rel="stylesheet" />
	<?php 
	\Bitrix\Main\UI\Extension::load([
		'sidepanel',
		'intranet.sidepanel.bindings',
		'intranet.sidepanel.external',
		'socialnetwork.slider',
	]);
	?>
	<?php $APPLICATION->ShowCSS(true, true);?>
	<?php $APPLICATION->ShowHeadStrings();?>
	<?php $APPLICATION->ShowHeadScripts();?>
<title><?php $APPLICATION->ShowTitle()?></title>
</head>
<body class="<?=$APPLICATION->ShowProperty("BodyClass");?>">