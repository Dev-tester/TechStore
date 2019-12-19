<?php 
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}
\Bitrix\Main\UI\Extension::load("ui.buttons");
\Bitrix\Main\UI\Extension::load("ui.buttons.icons");
\CJSCore::init(["sidepanel", "popup", "clipboard"]);

if($arResult['IS_SLIDER'])
{
	$APPLICATION->RestartBuffer();
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<script data-skip-moving="true">
			// Prevent loading page without header and footer
			if (window === window.top)
			{
				window.location = "<?=CUtil::JSEscape((new \Bitrix\Main\Web\Uri(\Bitrix\Main\Application::getInstance()->getContext()->getRequest()->getRequestUri()))->deleteParams(['IFRAME', 'IFRAME_TYPE']));?>" + window.location.hash;
			}
		</script>
		<?php $APPLICATION->ShowHead(); ?>
	</head>
	<body>
	<div class="docs-placeholder-wrap docs-placeholder-wrap-slider">
<?php }
else
{
	$APPLICATION->SetTitle($arResult['TITLE']);?>
	<div class="docs-template-wrap">
<?php }
if(!$arResult['TOP_VIEW_TARGET_ID'])
{?>
	<div class="pagetitle-wrap">
	<div class="docs-template-pagetitle-wrap">
	<div class="docs-template-pagetitle-inner pagetitle-inner-container">
	<div class="pagetitle">
		<span class="docs-template-pagetitle-item pagetitle-item" id="pagetitle"><?=$arResult['TITLE'];?></span>
	</div>
<?php }
else
{
	$this->SetViewTarget($arResult['TOP_VIEW_TARGET_ID']);
}?>
	<div class="pagetitle-container pagetitle-flexible-space pagetitle-container-docs-template">
		<?php  $APPLICATION->IncludeComponent(
			"bitrix:main.ui.filter",
			"",
			$arResult['FILTER']
		); ?>
	</div>
<?php if(!$arResult['TOP_VIEW_TARGET_ID'])
{?>
	</div>
	</div>
	</div>
<?php }
else
{
	$this->EndViewTarget();
}?>
	<div class="docs-template-grid">
		<?php $APPLICATION->IncludeComponent(
			"bitrix:main.ui.grid",
			"",
			$arResult['GRID']
		);?>
	</div>
	</div>
	<script>
		BX.ready(function()
		{
			BX.DocumentGenerator.Placeholders.init(<?=\Bitrix\Main\Web\Json::encode($arResult['params']);?>);
			<?='BX.message('.\CUtil::PhpToJSObject(\Bitrix\Main\Localization\Loc::loadLanguageFile(__FILE__)).');'?>
		});
	</script>
<?php 
if($arResult['IS_SLIDER'])
{
	?>
	</body>
	</html><?php 
	\Bitrix\Main\Application::getInstance()->terminate();
}