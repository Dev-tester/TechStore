<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

\Bitrix\Main\Localization\Loc::loadLanguageFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/header.php");

CUtil::InitJSCore(array("popup", "fx"));
?><!DOCTYPE html>
<html>
<head>
	<title><?php $APPLICATION->ShowTitle();?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="robots" content="noindex, nofollow" />
	<?php if (IsModuleInstalled("bitrix24")):?>
	<meta name="apple-itunes-app" content="app-id=561683423">
	<link rel="apple-touch-icon-precomposed" href="/images/iphone/57x57.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/images/iphone/72x72.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/images/iphone/114x114.png" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/images/iphone/144x144.png" />
	<?php endif?>
	<?php $APPLICATION->ShowHead();?>
</head>
<body>
<?php 
/*
This is commented to avoid Project Quality Control warning
$APPLICATION->ShowPanel();
*/
?>
<table class="log-main-table">
	<tr>
		<td class="log-top-cell">
			<a class="main-logo main-logo-<?php if (LANGUAGE_ID === "ru"):?>ru<?php elseif(LANGUAGE_ID === "ua"):?>ua<?php else:?>en<?php endif?>" href="/" title="<?=GetMessage("BITRIX24_TITLE")?>"></a>
		</td>
	</tr>
	<tr>
		<td class="log-main-cell">
			<div class="log-popup-wrap <?php echo $APPLICATION->ShowProperty("popup_class","")?>" id="login-popup-wrap">
				<div class="log-popup" id="login-popup">
