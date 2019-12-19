<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeAJAX();
$GLOBALS['APPLICATION']->AddHeadScript("/bitrix/js/main/utils.js");
$GLOBALS['APPLICATION']->AddHeadScript("/bitrix/components/bitrix/forum.interface/templates/popup/script.js");
$arParams['FORM_METHOD_GET'] = (isset($arParams['FORM_METHOD_GET']) && ($arParams['FORM_METHOD_GET'] == 'Y')) ? 'Y' : 'N';
$iIndex = rand();
$method = ($arParams['FORM_METHOD_GET'] == 'Y' ? 'get' : 'post');
$action = ($method === 'get' ? htmlspecialcharsbx($APPLICATION->GetCurPage()) : POST_FORM_ACTION_URI );
?>
	<form name="forum_form" id="forum_form_<?=$arResult["id"]?>" action="<?=$action?>" method="<?=$method?>" class="forum-form">
<?php 
	if ($arParams['FORM_METHOD_GET'] != 'Y')
	{
		echo bitrix_sessid_post();
	}
	foreach ($arResult["FIELDS"] as $key => $res):
		if ($res["TYPE"] == "HIDDEN"):
?>
	<input type="hidden" name="<?=$res["NAME"]?>" value="<?=$res["VALUE"]?>" />
<?php 
			unset($arResult["FIELDS"][$key]);
		endif;
	endforeach;

	$counter = 0;
	foreach ($arResult["FIELDS"] as $key => $res):
		$res["ID"] = (empty($res["ID"]) ? $res["NAME"]."_".$iIndex : $res["ID"]);
?>
	<div class="forum-filter-field <?=$res["CLASS"]?>">
		<label class="forum-filter-field-title" for="<?=$res["ID"]?>"><?=$res["TITLE"]?>:</label>
		<span class="forum-filter-field-item">
<?php 

		if ($res["TYPE"] == "SELECT"):
			if (!empty($_REQUEST["del_filter"]))
				$res["ACTIVE"] = array();
			else if (!is_array($res["ACTIVE"]))
				$res["ACTIVE"] = array($res["ACTIVE"]);
?>
			<select name="<?=$res["NAME"]?>" class="<?=$res["CLASS"]?>" id="<?=$res["ID"]?>" <?=($res["MULTIPLE"] == "Y" ? "multiple='multiple' size='5'" : "")?>>
<?php 
			foreach ($res["VALUE"] as $key => $val)
			{
				if ($val["TYPE"] == "OPTGROUP"):
?>
				<optgroup label="<?=str_replace(array(" ", "&amp;nbsp;") , "&nbsp;", $val["NAME"])?>" class="<?=$val["CLASS"]?>"></optgroup>
<?php 
				else:
?>
				<option value="<?=$key?>" <?=(in_array($key, $res["ACTIVE"]) ? " selected='selected'" : "")?>><?=str_replace(
					array(" ", "&amp;nbsp;"), "&nbsp;", $val["NAME"])?></option>
<?php 
				endif;
			}
?>
			</select>
<?php 
		elseif ($res["TYPE"] == "PERIOD"):
			if (!empty($_REQUEST["del_filter"]))
			{
				$res["VALUE"] = "";
				$res["VALUE_TO"] = "";
			}
			?><?php $APPLICATION->IncludeComponent("bitrix:main.calendar", "",
				array(
					"SHOW_INPUT" => "Y",
					"INPUT_NAME" => $res["NAME"], 
					"INPUT_NAME_FINISH" => $res["NAME_TO"],
					"INPUT_VALUE" => $res["VALUE"], 
					"INPUT_VALUE_FINISH" => $res["VALUE_TO"],
					"FORM_NAME" => "forum_form"),
				$component,
				array(
					"HIDE_ICONS" => "Y"));?><?php 
		elseif ($res["TYPE"] == "CHECKBOX"):
?>
			<input type="checkbox" name="<?=$res["NAME"]?>" id="<?=$res["ID"]?>" value="<?=$res["VALUE"]?>" class="<?=$res["CLASS"]?>" <?php 
				?><?=($res["ACTIVE"] == $res["VALUE"] ? " checked='checked' " : "")?> />
			<label for="<?=$res["ID"]?>"><?=$res["LABEL"]?></label>
<?php 
		else:
			if (!empty($_REQUEST["del_filter"]))
			{
				$res["VALUE"] = "";
			}
?>
			<input type="text" name="<?=$res["NAME"]?>" id="<?=$res["ID"]?>" value="<?=$res["VALUE"]?>" class="<?=$res["CLASS"]?>" />
<?php 
		endif;
?>
		</span>
		<div class="forum-clear-float"></div>
	</div>
<?php 
	endforeach;
?>
	<div class="forum-filter-field forum-filter-footer">
<?php 
	if (empty($arResult["BUTTONS"])):
?>
		<span class="forum-filter-first"><input type="submit" name="set_filter" value="<?=GetMessage("FORUM_BUTTON_FILTER")?>" /></span>
		<span><input type="submit" name="del_filter" value="<?=GetMessage("FORUM_BUTTON_RESET")?>" /></span>
<?php 
	else:
		$counter = 0; 
		foreach ($arResult["BUTTONS"] as $res):
		
?>
		<span class="<?=($counter == 0 ? "forum-filter-first" : "")?>">
			<input type="submit" name="<?=$res["NAME"]?>" value="<?=$res["VALUE"]?>" />
		</span>
<?php 
		endforeach;
	endif;
?>
		<div class="forum-clear-float"></div>
	</div>
</form><?php 
