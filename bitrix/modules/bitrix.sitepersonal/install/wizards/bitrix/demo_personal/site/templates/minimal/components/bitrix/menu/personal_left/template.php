<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult)):?>
	<ul id="user-menu">
<?php foreach($arResult as $arItem):?>
	<?php if ($arItem["PERMISSION"] > "D"):?>
		<li<?php if ($arItem["SELECTED"]):?> class="selected"<?php endif?>><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
	<?php endif?>
<?php endforeach?>

	</ul>
<?php endif?>