<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php 
$found = false;
foreach ($arResult["ITEMS"] as $key => $arItem):

	if ($arItem["SELECTED"]):?>

		<?php if ($arItem["TYPE"] == "CD"):?>
			<div class="learn-course-start"></div>&nbsp;<a href="<?=$arResult["ITEMS"][1]["URL"]?>"><?=GetMessage("LEARNING_START_COURSE")?></a>
		<?php return;endif?>

		<?php if (isset($arResult["ITEMS"][$key-1]) && $key > 1):?>
			<div class="learn-course-back"></div>&nbsp;<a href="<?=$arResult["ITEMS"][$key-1]["URL"]?>"><?=$arResult["ITEMS"][$key-1]["NAME"]?></a> |
		<?php endif?>

		<a href="<?=$arResult["ITEMS"][0]["URL"]?>"><?=$arResult["ITEMS"][0]["NAME"]?></a>

		<?php if (isset($arResult["ITEMS"][$key+1])):?>
			| <a href="<?=$arResult["ITEMS"][$key+1]["URL"];?>"> <?=$arResult["ITEMS"][$key+1]["NAME"]?></a>&nbsp;<div class="learn-course-next">&nbsp;&nbsp;&nbsp;</div>
		<?php endif?>

		<?php 
		$found = true;
		break;

	endif;

endforeach;?>

<?php if ($found === false):?>
	<div class="learn-course-start"></div>&nbsp;<a href="<?=$arResult["ITEMS"][1]["URL"]?>"><?=GetMessage("LEARNING_START_COURSE")?></a>
<?php endif?>