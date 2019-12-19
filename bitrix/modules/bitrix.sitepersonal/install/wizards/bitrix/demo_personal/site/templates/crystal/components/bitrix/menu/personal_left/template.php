<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult)):?>
	<ul id="user-menu">
<?php foreach($arResult as $arItem):?>
	<?php if ($arItem["PERMISSION"] > "D"):?>
		<li<?php if ($arItem["SELECTED"]):?> class="selected"<?php endif?>>
			<b class="r2"></b>
			<b class="r1"></b>
			<b class="r0"></b>
			<a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
			<b class="r0"></b>
			<b class="r1"></b>
			<b class="r2"></b>
		</li>
	<?php endif?>
<?php endforeach?>

	</ul>
<?php endif?>