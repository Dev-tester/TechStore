<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="news-list">
<?php if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?php endif;?>
<dl class="block-list">
<?php foreach($arResult["ITEMS"] as $arItem):?>
		<dt><?php echo $arItem["DISPLAY_ACTIVE_FROM"]?></dt>
		<dd><a href="<?php echo $arItem["DETAIL_PAGE_URL"]?>"><?php echo $arItem["PREVIEW_TEXT"]?></a></dd>
<?php endforeach;?>
</dl>
<?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?php endif;?>
</div>
