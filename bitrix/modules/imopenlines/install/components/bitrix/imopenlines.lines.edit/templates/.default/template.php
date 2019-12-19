<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

\Bitrix\Main\UI\Extension::load("ui.buttons");
\Bitrix\Main\UI\Extension::load("ui.alerts");
\Bitrix\Main\UI\Extension::load("ui.hint");
CUtil::InitJSCore(array("socnetlogdest", "sidepanel"));
if (\Bitrix\Main\Loader::includeModule("bitrix24"))
{
	\CBitrix24::initLicenseInfoPopupJS();
}

$APPLICATION->SetTitle($arResult["PAGE_TITLE"]);
?>
<script>
	BX.ready(function(){
		BX.OpenLinesConfigEdit.init();
	});
	BX.message({
		'IMOL_CONFIG_EDIT_POPUP_LIMITED_TITLE': '<?=GetMessageJS("IMOL_CONFIG_EDIT_POPUP_LIMITED_TITLE")?>',
		'IMOL_CONFIG_EDIT_POPUP_LIMITED_QUEUE_ALL_NEW': '<?=GetMessageJS("IMOL_CONFIG_EDIT_POPUP_LIMITED_QUEUE_ALL_NEW")?>',
		'IMOL_CONFIG_EDIT_POPUP_LIMITED_VOTE': '<?=GetMessageJS("IMOL_CONFIG_EDIT_POPUP_LIMITED_VOTE")?>',
		'IMOL_CONFIG_EDIT_NO_ANSWER_RULE_FORM': '<?=GetMessageJS("IMOL_CONFIG_EDIT_NO_ANSWER_RULE_FORM")?>',
		'IMOL_CONFIG_EDIT_NO_ANSWER_RULE_TEXT': '<?=GetMessageJS("IMOL_CONFIG_EDIT_NO_ANSWER_RULE_TEXT")?>',
		'IMOL_CONFIG_EDIT_NO_ANSWER_RULE_QUEUE': '<?=GetMessageJS("IMOL_CONFIG_EDIT_NO_ANSWER_RULE_QUEUE")?>',
		'IMOL_CONFIG_EDIT_NO_ANSWER_RULE_NONE': '<?=GetMessageJS("IMOL_CONFIG_EDIT_NO_ANSWER_RULE_NONE")?>',
		'IMOL_CONFIG_EDIT_QUEUE_TIME': '<?=GetMessageJS("IMOL_CONFIG_EDIT_QUEUE_TIME_NEW")?>',
		'IMOL_CONFIG_EDIT_NA_TIME_NEW': '<?=GetMessageJS("IMOL_CONFIG_EDIT_NA_TIME_NEW")?>',
		'IMOL_CONFIG_EDIT_POPUP_LIMITED_ACTIVE': '<?=GetMessageJS("IMOL_CONFIG_EDIT_POPUP_LIMITED_ACTIVE")?>'
	});
</script>
<?php 
if (!$arResult["IFRAME"])
{
	?>
	<div class="imopenlines-page-menu-sidebar">
		<?php $APPLICATION->ShowViewContent("left-panel");?>
	</div>
	<?php 
}

$APPLICATION->IncludeComponent("bitrix:ui.sidepanel.wrappermenu", "", array(
	"ITEMS" => $arResult["CONFIG_MENU"],
	"TITLE" => Loc::getMessage("IMOL_CONFIG_CONFIG"),
	'RELOAD_PAGE_AFTER_SAVE' => true
));
?>
<div id="imopenlines-field-container" <?php if(!$arResult["IFRAME"]){?>class="imopenlines-page-field-container"<?php }?>>
	<form action="<?=$arResult['ACTION_URI']?>"
		  method="POST"
		  id="imol_config_edit_form"
	<?php if ($arResult["IFRAME"]):?>class="imopenlines-form-settings-wrap"<?php endif;?>>
		<?=bitrix_sessid_post()?>
		<input type="hidden" name="CONFIG_ID" id="imol_config_id" value="<?=$arResult["CONFIG"]["ID"]?>" />
		<input type="hidden" name="form" value="imopenlines_edit_form" />
		<input type="hidden" name="action" value="apply" id="imol_config_edit_form_action" />
		<input type="hidden" name="PAGE" value="<?=$arResult["PAGE"]?>" id="imol_config_current_page">
		<?php 
		foreach ($arResult["CONFIG_MENU"] as $key => $menuItem)
		{
			?>
			<div data-imol-page="<?=$key?>" class="<?php if($key == $arResult["PAGE"]){?>imopenlines-page-show<?php }else{?>imopenlines-page-hide invisible<?php }?>">
				<?php include $menuItem["PAGE"]; ?>
				<div data-imol-title="<?=$menuItem["NAME"]?>" class="invisible"></div>
			</div>
			<?php 
		}
		?>

		<?php 
		if ($arResult["CAN_EDIT"])
		{
			$APPLICATION->IncludeComponent(
				"bitrix:ui.button.panel",
				"",
				array(
					"BUTTONS" => $arResult["PANEL_BUTTONS"],
					"ALIGN" => "center"
				),
				false
			);
		}
		?>
	</form>
</div>
