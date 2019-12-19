<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Web\Json;
use Bitrix\Intranet\Integration\Templates\Bitrix24\ThemePicker;

/** @var $this \CBitrixComponentTemplate */
/** @var \CAllMain $APPLICATION */
/** @var array $arResult*/
/** @var array $arParams*/

$this->addExternalCss($this->GetFolder() . '/template.css');
$this->addExternalJs($this->GetFolder() . '/template.js');
\Bitrix\Main\UI\Extension::load("sidepanel");

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID ?>" lang="<?=LANGUAGE_ID ?>">
<head>
	<script type="text/javascript">
		// Prevent loading page without header and footer
		if(window === window.top)
		{
			window.location = "<?=\CUtil::JSEscape($APPLICATION->GetCurPageParam('', ['IFRAME'])); ?>";
		}
	</script>
	<?php $APPLICATION->ShowHead();?>
	<title><?php $APPLICATION->ShowTitle()?></title>
	<?php if ($arParams['EDITABLE_TITLE_SELECTOR']):?>
		<style>
			<?=$arParams['EDITABLE_TITLE_SELECTOR']?> {
				display: none;
			}
		</style>
	<?php endif;?>

	<?php 
	if ($arResult["SHOW_BITRIX24_THEME"] == "Y")
	{
		$themePicker = new ThemePicker(SITE_TEMPLATE_ID, false, $arParams["POPUP_COMPONENT_BITRIX24_THEME_FOR_USER_ID"]);
		$themePicker->showHeadAssets();
	}
	?>
</head>

<?php 
$bodyClass = "ui-page-slider-wrapper";
if (!$arParams['PLAIN_VIEW'])
{
	$bodyClass .= " ui-page-slider-padding";
}

$bodyClass .= " template-".(defined('SITE_TEMPLATE_ID') ? SITE_TEMPLATE_ID  : 'def');

if ($arResult["SHOW_BITRIX24_THEME"] == "Y")
{
	$bodyClass .= " bitrix24-".$themePicker->getCurrentBaseThemeId()."-theme";
}
else
{
	$bodyClass .= " ui-page-slider-wrapper-default-theme";
}
?>
<body class="<?=$bodyClass?> <?php $APPLICATION->ShowProperty('BodyClass');?>">
<?php 
if ($arResult["SHOW_BITRIX24_THEME"] == "Y")
{
	$themePicker->showBodyAssets();
}
?>
	<div id="left-panel"><?php  $APPLICATION->ShowViewContent("left-panel"); ?></div>
	<div class="pagetitle-above"><?php $APPLICATION->ShowViewContent("above_pagetitle")?></div>
	<?php  if(!isset($arParams['USE_UI_TOOLBAR']) || $arParams['USE_UI_TOOLBAR'] !== 'Y')
	{
	?>
		<div class="pagetitle-wrap" style="<?=($arParams['PLAIN_VIEW'] ? 'display: none;' : '')?>">
			<div class="pagetitle-inner-container">
				<div class="pagetitle-menu pagetitle-last-item-in-a-row" id="pagetitle-menu">
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
	<?php 
	}
	else
	{
		$APPLICATION->IncludeComponent("bitrix:ui.toolbar", '', []);
	}
	?>
	<div class="pagetitle-below"><?php $APPLICATION->ShowViewContent("below_pagetitle")?></div>

	<div id="ui-page-slider-workarea">
		<div id="sidebar"><?php  $APPLICATION->ShowViewContent("sidebar"); ?></div>
		<div id="workarea-content">
			<div class="<?=($arParams['USE_PADDING'] ? 'ui-page-slider-workarea-content-padding' : '')?>">
				<?php 
				include ('content.php');

				if (!empty($arParams['BUTTONS']))
				{
					$APPLICATION->IncludeComponent(
						"bitrix:ui.button.panel",
						"",
						["BUTTONS" => $arParams['BUTTONS']],
						false
					);
				}
				?>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		BX.ready(function () {
			BX.UI.SidePanel.Wrapper.init(<?=Json::encode([
				'containerId' => 'workarea-content',
				'isCloseAfterSave' => $arParams['CLOSE_AFTER_SAVE'],
				'isReloadGridAfterSave' => $arParams['RELOAD_GRID_AFTER_SAVE'],
				'isReloadPageAfterSave' => $arParams['RELOAD_PAGE_AFTER_SAVE'],
				'skipNotification' => $arResult['SKIP_NOTIFICATION'],
				'useLinkTargetsReplacing' => $arParams['USE_LINK_TARGETS_REPLACING'],
				'title' => [
					'defaultTitle' => $arParams['EDITABLE_TITLE_DEFAULT'],
					'selector' => $arParams['EDITABLE_TITLE_SELECTOR']
				],
				'notification' => $arParams['NOTIFICATION'],
			])?>);
		});
	</script>
</body>
</html>