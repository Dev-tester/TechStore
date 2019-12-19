<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if($arParams["USE_RSS"] == "Y"){
	if(method_exists($APPLICATION, 'addheadstring'))
		$APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" title="'.$arParams["TITLE_RSS"].'" href="'.SITE_DIR.'rss_news.php" />');
}
?>
<?php if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?php endif;?>
<?php if(count($arResult["ITEMS"]) > 0):?>
<div class="news-list">
	<?php if($arParams["USE_RSS"] == "Y"):?>
	<a target="_self" title="rss" href="<?=SITE_DIR?>rss_news.php"><img border="0" align="right" src="<?=SITE_TEMPLATE_PATH?>/images/feed-icon-16x16.gif" alt="RSS"></a>
	<?php endif?>
<?php foreach($arResult["ITEMS"] as $arItem):?>
	<?php 
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"));
	?>
	<div class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<?php $classPict = '';?>
		<?php if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_IMG_SMALL"])):?>
		<?php $classPict = 'news-text-pict';?>
		<div class="news-picture">
			<?php if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img class="preview_picture" border="0" src="<?=$arItem["PREVIEW_IMG_SMALL"]["SRC"]?>" width="<?=$arItem["PREVIEW_IMG_SMALL"]["WIDTH"]?>" height="<?=$arItem["PREVIEW_IMG_SMALL"]["HEIGHT"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" /></a>
			<?php else:?>
				<img class="preview_picture" border="0" src="<?=$arItem["PREVIEW_IMG_SMALL"]["SRC"]?>" width="<?=$arItem["PREVIEW_IMG_SMALL"]["WIDTH"]?>" height="<?=$arItem["PREVIEW_IMG_SMALL"]["HEIGHT"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>"/>
			<?php endif;?>
		</div>
		<?php endif?>
		<div class="news-text <?=$classPict?>">
		<?php if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<div class="news-name">
			<?php if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?php echo $arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a><br />
			<?php else:?>
				<b><?php echo $arItem["NAME"]?></b><br />
			<?php endif;?>
			</div>
		<?php endif;?>
		<?php if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<span class="news-date-time"><?php echo $arItem["DISPLAY_ACTIVE_FROM"]?> / <?=$arItem["SECTION_URL"]?></span> <br/>
		<?php endif?>
		<?php if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
			<span class="news-preview-text"><?=$arItem["PREVIEW_TEXT"];?></span>
		<?php endif;?>
		</div>
		<?php if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<div style="clear:both"></div>
		<?php endif?>
	</div>
<?php endforeach;?>
</div>
<?php endif;?>
<?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?php endif;?>
