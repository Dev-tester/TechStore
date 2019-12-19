<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="fields string" id="main_<?=$arParams["arUserField"]["FIELD_NAME"]?>"><?php 
foreach ($arResult["VALUE"] as $res):
?><div class="fields string"><?php 
	if($arParams["arUserField"]["SETTINGS"]["ROWS"] < 2):
?><input type="text" name="<?=$arParams["arUserField"]["FIELD_NAME"]?>" value="<?=$res?>"<?php 
	if (intVal($arParams["arUserField"]["SETTINGS"]["SIZE"]) > 0):
		?> size="<?=$arParams["arUserField"]["SETTINGS"]["SIZE"]?>"<?php 
	endif;
	if (intVal($arParams["arUserField"]["SETTINGS"]["MAX_LENGTH"]) > 0):
		?> maxlength="<?=$arParams["arUserField"]["SETTINGS"]["MAX_LENGTH"]?>"<?php 
	endif;
	if ($arParams["arUserField"]["EDIT_IN_LIST"]!="Y"):
		?> disabled="disabled"<?php 
	endif;
?> class="fields string"><?php 
	else:
?><textarea class="fields string" name="<?=$arParams["arUserField"]["FIELD_NAME"]?>"<?php 
	?> cols="<?=$arParams["arUserField"]["SETTINGS"]["SIZE"]?>"<?php 
	?> rows="<?=$arParams["arUserField"]["SETTINGS"]["ROWS"]?>" <?php 
	if (intVal($arParams["arUserField"]["SETTINGS"]["MAX_LENGTH"]) > 0):
		?> maxlength="<?=$arParams["arUserField"]["SETTINGS"]["MAX_LENGTH"]?>"<?php 
	endif;
	if ($arParams["arUserField"]["EDIT_IN_LIST"]!="Y"):
		?> disabled="disabled"<?php 
	endif;
?>><?=$res?></textarea><?php 	
	endif;
?></div><?php 
endforeach;
?></div>