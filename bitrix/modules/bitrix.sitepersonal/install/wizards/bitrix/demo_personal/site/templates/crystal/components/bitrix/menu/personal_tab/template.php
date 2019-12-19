<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult)):?>
	<ul>
<?php foreach($arResult as $arItem):?>
	<?php if ($arItem["PERMISSION"] > "D"):?>
		<li<?php if ($arItem["SELECTED"]):?> class="selected"<?php endif?>><a href="<?=$arItem["LINK"]?>"><span><?=$arItem["TEXT"]?></span></a></li>
	<?php endif?>
<?php endforeach?>

	</ul>
<?php endif?>