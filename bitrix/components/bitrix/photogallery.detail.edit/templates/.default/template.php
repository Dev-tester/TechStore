<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (!$this->__component->__parent || strpos($this->__component->__parent->__name, "photogallery") === false)
{
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/photogallery/templates/.default/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/photogallery/templates/.default/themes/gray/style.css');
}

/*************************************************************************
	Processing of received parameters
*************************************************************************/
$arParams["SHOW_TAGS"] = ($arParams["SHOW_TAGS"] == "N" ? "N" : "Y");
$arParams["SHOW_PUBLIC"] = ($arParams["SHOW_PUBLIC"] == "N" ? "N" : "Y");
$arParams["SHOW_APPROVE"] = ($arParams["SHOW_APPROVE"] == "N" ? "N" : "Y");

/*************************************************************************
	/Processing of received parameters
*************************************************************************/

if ($arParams["AJAX_CALL"] == "Y")
	$APPLICATION->ShowAjaxHead();

?>
<?php if ($arResult["ERROR_MESSAGE"] != ""):?>
<script>
window.oPhotoEditDialogError = "<?= CUtil::JSEscape($arResult["ERROR_MESSAGE"]); ?>";
</script>
<?php 
die();
endif;
?>

<div class="photo-window-edit" id="photo_photo_edit">
<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="form_photo" id="form_photo" onsubmit="return CheckForm(this);" class="photo-form">
	<input type="hidden" name="edit" value="Y" />
	<input type="hidden" name="sessid" value="<?=bitrix_sessid()?>" />
	<input type="hidden" name="SECTION_ID" value="<?=$arResult["ELEMENT"]["~IBLOCK_SECTION_ID"]?>" />
	<input type="hidden" name="ELEMENT_ID" value="<?=$arResult["ELEMENT"]["~ID"]?>" />

	<table class="photo-dialog-table">
	<tr id="bxph_error_row" style="display: none;">
		<td class="photo-dialog-warning" colSpan="2"></td>
	</tr>
	<?php if ($arParams['SHOW_TITLE'] == "Y"):?>
	<tr>
		<td class="photo-dialog-prop-title photo-dialog-req"><label for="bxph_title"><?=GetMessage("P_TITLE")?>:</label></td>
		<td class="photo-dialog-prop-param photo-inp-width"><input name="TITLE" id="bxph_title" value="<?=$arResult["ELEMENT"]["NAME"]?>" size="20"/></td>
	</tr>
	<?php endif;?>

	<tr>
		<td class="photo-dialog-prop-title"><label for="DATE_CREATE"><?=GetMessage("P_DATE")?>:</label></td>
		<td class="photo-dialog-prop-param-date"><?php 
			$APPLICATION->IncludeComponent(
				"bitrix:main.calendar",
				"",
				array(
					"SHOW_INPUT" => "Y",
					"FORM_NAME" => "form_photo",
					"INPUT_NAME" => "DATE_CREATE",
					"INPUT_VALUE" => $arResult["ELEMENT"]["DATE_CREATE"]),
				null,
				array("HIDE_ICONS" => "Y"));
			?>
		</td>
	</tr>

	<?php  if (is_array($arResult["SECTION_LIST"])):?>
	<tr>
		<td class="photo-dialog-prop-title"><label for="bxph_to_section_id"><?=GetMessage("P_ALBUMS")?>:</label></td>
		<td class="photo-dialog-prop-param">
			<select id="bxph_to_section_id" name="TO_SECTION_ID">
			<?php foreach ($arResult["SECTION_LIST"] as $key => $val):?>
			<option value="<?=$key?>" <?= ($arResult["ELEMENT"]["IBLOCK_SECTION_ID"] == $key ? "selected" : "")?>><?=$val?></option>
			<?php  endforeach;?>
			</select>
		</td>
	</tr>
	<?php endif;?>

	<?php  if ($arParams["BEHAVIOUR"] == "USER"):?>
	<tr>
		<td class="photo-dialog-prop-title"><input type="checkbox" name="PUBLIC_ELEMENT" id="bxph_photo_public_element" value="Y" <?=($arResult["ELEMENT"]["PROPERTIES"]["PUBLIC_ELEMENT"]["VALUE"] == "Y" ? " checked='checked'" : "")?> /></td>
		<td class="photo-dialog-prop-param">
			<label for="bxph_photo_public_element"><?=GetMessage("P_PUBLIC_ELEMENT")?></label>
		</td>
	</tr>

	<?php if ($arParams["ABS_PERMISSION"] >= "W"):?>
	<tr>
		<td class="photo-dialog-prop-title"><input type="checkbox" name="APPROVE_ELEMENT" id="bxph_photo_approve_element" value="Y" <?=($arResult["ELEMENT"]["PROPERTIES"]["APPROVE_ELEMENT"]["VALUE"] == "Y" ? " checked='checked'" : "")?> /></td>
		<td class="photo-dialog-prop-param">
			<label for="bxph_photo_approve_element"><?=GetMessage("P_APPROVE_ELEMENT")?></label>
		</td>
	</tr>
	<tr>
		<td class="photo-dialog-prop-title"><input type="checkbox" name="ACTIVE" id="bxph_photo_active" value="Y" <?=($arResult["ELEMENT"]["ACTIVE"] == "Y" ? " checked='checked'" : "")?> /></td>
		<td class="photo-dialog-prop-param">
			<label for="bxph_photo_active"><?=GetMessage("P_ACTIVE_ELEMENT")?></label>
		</td>
	</tr>
	<?php endif; /* $arParams["ABS_PERMISSION"] >= "W" */?>
	<?php endif; /* $arParams["BEHAVIOUR"] == "USER" */?>

	<?php if ($arParams["SHOW_TAGS"] == "Y"):?>
	<tr>
		<td class="photo-dialog-prop-title"><label for="TAGS"><?=GetMessage("P_TAGS")?>:</label></td>
		<td class="photo-dialog-prop-param photo-inp-width">
			<?php if (IsModuleInstalled("search")):?>
			<?php $APPLICATION->IncludeComponent(
				"bitrix:search.tags.input",
				"",
				array(
					"VALUE" => $arResult["ELEMENT"]["~TAGS"],
					"NAME" => "TAGS"),
				null,
				array(
					"HIDE_ICONS" => "Y"));?>
			<?php else:?>
			<input type="text" name="TAGS" id="TAGS" value="<?=$arResult["ELEMENT"]["TAGS"]?>" />
			<?php endif;?>
		</td>
	</tr>
	<?php endif; /* $arParams["SHOW_TAGS"] == "Y" */?>

	<tr>
		<td class="photo-dialog-prop-title" valign="top"><label for="bxph_description"><?=GetMessage("P_DESCRIPTION")?>:</label></td>
		<td class="photo-dialog-prop-param">
			<textarea name="DESCRIPTION" id="bxph_description"><?=$arResult["ELEMENT"]["DETAIL_TEXT"]?></textarea>
		</td>
	</tr>

</table>
</form>
</div>

<?php 
if ($arParams["AJAX_CALL"] == "Y")
	die();
?>