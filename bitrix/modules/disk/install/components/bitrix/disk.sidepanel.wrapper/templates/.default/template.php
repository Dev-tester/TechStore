<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var CMain $APPLICATION */
/** @var array $arResult*/
/** @var array $arParams*/

global $APPLICATION;
CJSCore::Init(array('sidepanel'));

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID ?>" lang="<?=LANGUAGE_ID ?>">
<head>
	<script type="text/javascript">
		// Prevent loading page without header and footer
		if(window == window.top)
		{
			window.location = "<?=CUtil::JSEscape($APPLICATION->GetCurPageParam('', array('IFRAME'))); ?>";
		}
	</script>
	<?php $APPLICATION->ShowHead();?>
	<title><?php $APPLICATION->ShowTitle()?></title>
</head>
<body class="disk-slider-frame-popup template-<?= SITE_TEMPLATE_ID ?> <?php  $APPLICATION->ShowProperty('BodyClass'); ?>">
	<div class="disk-pagetitle-wrap">
		<div class="disk-pagetitle-inner-container">
			<div class="disk-pagetitle-menu" id="pagetitle-menu">
				<?php  $APPLICATION->ShowViewContent("pagetitle"); ?>
			</div>
			<div class="disk-pagetitle">
				<span id="pagetitle"><?php  $APPLICATION->ShowTitle(); ?></span>
			</div>
			<?php $APPLICATION->ShowViewContent("inside_pagetitle")?>
		</div>
	</div>

	<div id="disk-frame-popup-workarea">
		<div id="sidebar"><?php  $APPLICATION->ShowViewContent("sidebar"); ?></div>
		<div id="workarea-content">
			<div class="disk-workarea-content-paddings">
				<?php 
				$APPLICATION->IncludeComponent(
					$arParams['POPUP_COMPONENT_NAME'],
					$arParams['POPUP_COMPONENT_TEMPLATE_NAME'],
					$arParams['POPUP_COMPONENT_PARAMS']
				);
				?>
			</div>
		</div>
	</div>
</body>
</html>