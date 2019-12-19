<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var CMain $APPLICATION */
/** @var array $arResult*/
/** @var array $arParams*/

global $APPLICATION;

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID ?>" lang="<?=LANGUAGE_ID ?>">
<head>
	<script type="text/javascript">
		// Prevent loading page without header and footer
		if(window === window.top)
		{
			window.location = "<?=CUtil::JSEscape($APPLICATION->GetCurPageParam('', array('IFRAME'))); ?>";
		}
	</script>
	<?php $APPLICATION->ShowHead();?>
	<title><?php $APPLICATION->ShowTitle()?></title>
	<script type="text/javascript">
		BX.ready(function () {
			if (!BX.message.SITE_ID)
			{
				BX.message['SITE_ID'] = '';
			}
		});
	</script>
</head>
<body class="sender-slider-frame-popup template-<?=(defined('SITE_TEMPLATE_ID') ? SITE_TEMPLATE_ID  : 'def')?> <?php $APPLICATION->ShowProperty('BodyClass');?>">
	<div class="pagetitle-wrap">
		<div class="pagetitle-inner-container">
			<div class="pagetitle-menu" id="pagetitle-menu">
				<?php  $APPLICATION->ShowViewContent("pagetitle"); ?>
			</div>
			<div class="pagetitle">
				<span id="pagetitle" class="pagetitle-item"><?php  $APPLICATION->ShowTitle(); ?></span>
				<span id="pagetitle_edit" class="pagetitle-edit-button" style="display: none;"></span>
				<input id="pagetitle_input" type="text" class="pagetitle-item" style="display: none;">
			</div>

			<?php  $APPLICATION->ShowViewContent("inside_pagetitle"); ?>
		</div>
	</div>

	<div id="sender-frame-popup-workarea">
		<div id="sidebar"><?php  $APPLICATION->ShowViewContent("sidebar"); ?></div>
		<div id="workarea-content">
			<div class="workarea-content-paddings sender-workarea-content-paddings">

				<?php 
				$APPLICATION->IncludeComponent(
					$arParams['POPUP_COMPONENT_NAME'],
					$arParams['POPUP_COMPONENT_TEMPLATE_NAME'],
					$arParams['POPUP_COMPONENT_PARAMS']
				);
				?>

				<?php 
				if (!empty($arParams['BUTTON_LIST']))
				{
					$APPLICATION->IncludeComponent(
						"bitrix:sender.ui.button.panel",
						"",
						$arParams['BUTTON_LIST'],
						false
					);
				}
				?>
			</div>


		</div>
	</div>
</body>
</html>