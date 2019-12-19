<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
$arCategoryList = array();
if(CModule::IncludeModule('iblock') && CModule::IncludeModule('idea') && CIdeaManagment::getInstance()->Idea()->GetCategoryListID()>0)
	$arCategoryList = CIdeaManagment::getInstance()->Idea()->GetCategoryList();
?>
<?php 
if(strlen($arParams["arUserField"]["VALUE"])==0 && array_key_exists("idea", $_REQUEST) && strlen($_REQUEST["idea"])>0)
	$arParams["arUserField"]["VALUE"] = htmlspecialcharsbx($_REQUEST["idea"]);
?>
<div class="field-<?=$arParams["arUserField"]["FIELD_NAME"]?>">
	<select name="<?=$arParams["arUserField"]["FIELD_NAME"]?>">
		<?php foreach($arCategoryList as $opt):?>
		<option value="<?=ToUpper($opt["CODE"])?>"<?php if(ToUpper($arParams["arUserField"]["VALUE"]) == ToUpper($opt["CODE"])):?> selected<?php endif;?>><?=str_repeat("&bull; ", $opt["DEPTH_LEVEL"]-1)?><?=$opt["NAME"]?></option>
		<?php endforeach;?>
	</select>
</div>