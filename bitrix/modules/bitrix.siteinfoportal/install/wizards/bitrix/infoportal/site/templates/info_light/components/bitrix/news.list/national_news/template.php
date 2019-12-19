<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php if(count($arResult["ITEMS"])):?>
<div class="news-list national-news">
<div class="main-news-title"><h2><?=$arParams["NAME_BLOCK"]?></h2></div>
<div class="national-news-add"><a href="<?=SITE_DIR?>nationalnews/add_news/?edit=Y"><?=GetMessage("NATIONAL_NEWS_ADD")?></a></div>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<?php foreach($arResult["ITEMS"] as $arItem):?>
	<?php 
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"));
	?>
	<?php if($cell%$arParams["LINE_NEWS_COUNT"] == 0):?>
	<tr>
	<?php endif;?>

	<td valign="top" width="<?=round(100/$arParams["LINE_NEWS_COUNT"])?>%" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<div class="news-item <?php if($cell%$arParams["LINE_NEWS_COUNT"] == 0):?>news-item-left<?php endif;?>">
			<?php if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_IMG_SMALL"])):?>
			<div class="news-picture">
				<?php if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img class="preview_picture" border="0" src="<?=$arItem["PREVIEW_IMG_SMALL"]["SRC"]?>" width="<?=$arItem["PREVIEW_IMG_SMALL"]["WIDTH"]?>" height="<?=$arItem["PREVIEW_IMG_SMALL"]["HEIGHT"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" /></a>
				<?php else:?>
					<img class="preview_picture" border="0" src="<?=$arItem["PREVIEW_IMG_SMALL"]["SRC"]?>" width="<?=$arItem["PREVIEW_IMG_SMALL"]["WIDTH"]?>" height="<?=$arItem["PREVIEW_IMG_SMALL"]["HEIGHT"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>"/>
				<?php endif;?>
			</div>
			<?php endif?>
			<div class="news-text">
			<?php if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
				<span class="news-date-time"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></span><br/>
			<?php endif?>
			<?php if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
				<div class="news-name">
				<?php if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
					<a href="<?php echo $arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a><br />
				<?php else:?>
					<b><?php echo $arItem["NAME"]?></b><br />
				<?php endif;?>
				</div>
			<?php endif;?>
			<?php if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
				<span class="news-preview-text"><?=$arItem["PREVIEW_TEXT"];?></span>
			<?php endif;?>
			<?php //print_r($arItem);?>
			<?php if(isset($arItem["SHOW_COUNTER"])):?>
			<?php if($arItem["SHOW_COUNTER"] == '') $arItem["SHOW_COUNTER"] = 0;?>
			<span class="news-show-counter"><?=GetMessage("SHOW_COUNTER_TITLE")?><?=$arItem["SHOW_COUNTER"]?></span>
			<?php endif;?>
			
			</div>
		</div>
	</td>
	<?php $cell++;
	if($cell%$arParams["LINE_NEWS_COUNT"] == 0):?>
		</tr>
	<?php endif?>

<?php endforeach; // foreach($arResult["ITEMS"] as $arElement):?>

<?php if($cell%$arParams["LINE_NEWS_COUNT"] != 0):?>
	<?php while(($cell++)%$arParams["LINE_NEWS_COUNT"] != 0):?>
		<td>&nbsp;</td>
	<?php endwhile;?>
</tr>
<?php endif?>
</table>
</div>
<?php endif?>