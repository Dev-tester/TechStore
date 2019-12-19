<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="fields integer" id="main_<?=$arParams["arUserField"]["FIELD_NAME"]?>"><?php 

$showTime = !(isset($arParams['bShowTime']) && $arParams['bShowTime'] === false);

foreach ($arResult["VALUE"] as $res):
	$name = $arParams["arUserField"]["FIELD_NAME"];

?><div class="fields datetime">
<input type="text" name="<?=$name?>_from" value="<?=$res[0]?>"<?php 
	if (intVal($arParams["arUserField"]["SETTINGS"]["SIZE"]) > 0):
		?> size="<?=$arParams["arUserField"]["SETTINGS"]["SIZE"]?>"<?php 
	else:
		?> size="10" <?php 
	endif;
	if ($arParams["arUserField"]["EDIT_IN_LIST"]!="Y"):
		?> readonly="readonly"<?php 
	endif;
?> class="filter-date-interval"><?php 
	$GLOBALS['APPLICATION']->IncludeComponent(
		"bitrix:main.calendar",
		"",
		array(
			"SHOW_INPUT" => "N",
			"FORM_NAME" => $arParams["form_name"],
			"INPUT_NAME" => $name.'_from',
            "SHOW_TIME" => $showTime ? 'Y' : 'N'),
		$component,
		array("HIDE_ICONS" => "Y"));
?><span class="date-interval-hellip">&hellip;</span><input type="text" name="<?=$name?>_to" value="<?=$res[1]?>"<?php 
	if (intVal($arParams["arUserField"]["SETTINGS"]["SIZE"]) > 0):
		?> size="<?=$arParams["arUserField"]["SETTINGS"]["SIZE"]?>"<?php 
	else:
		?> size="10" <?php 		
	endif;
	if ($arParams["arUserField"]["EDIT_IN_LIST"]!="Y"):
		?> readonly="readonly"<?php 
	endif;
?> class="filter-date-interval">
<?php 
	$GLOBALS['APPLICATION']->IncludeComponent(
		"bitrix:main.calendar",
		"",
		array(
			"SHOW_INPUT" => "N",
			"FORM_NAME" => $arParams["form_name"],
			"INPUT_NAME" => $name.'_to',
            "SHOW_TIME" => $showTime ? 'Y' : 'N'),
		$component,
		array("HIDE_ICONS" => "Y"));
?></div><?php 
endforeach;
?></div><?php 
