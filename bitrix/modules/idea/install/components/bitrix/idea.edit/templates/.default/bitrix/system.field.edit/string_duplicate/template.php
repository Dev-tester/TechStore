<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php foreach ($arResult["VALUE"] as $res):?>
	<input type="text" name="<?=$arParams["arUserField"]["FIELD_NAME"]?>" value="<?=$res?>"<?php 
	if (intVal($arParams["arUserField"]["SETTINGS"]["SIZE"]) > 0):
		?> size="<?=$arParams["arUserField"]["SETTINGS"]["SIZE"]?>"<?php 
	endif;
	if (intVal($arParams["arUserField"]["SETTINGS"]["MAX_LENGTH"]) > 0):
		?> maxlength="<?=$arParams["arUserField"]["SETTINGS"]["MAX_LENGTH"]?>"<?php 
	endif;
	if ($arParams["arUserField"]["EDIT_IN_LIST"]!="Y"):
		?> disabled="disabled"<?php 
	endif;
?> class="fields string">
<?php endforeach;?>