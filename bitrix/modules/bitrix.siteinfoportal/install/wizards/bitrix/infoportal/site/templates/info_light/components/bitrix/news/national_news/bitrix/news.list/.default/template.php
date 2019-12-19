<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="news-list">
<?php if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?php endif;?>
<?php $showHr = false; $q = RandString(5);?>
<?php foreach($arResult["ITEMS"] as $arItem):?>
	<?php 
	$this->AddEditAction($arItem['ID']."_".$q, $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID']."_".$q, $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"));
	?>
	<div class="news-item" id="<?=$this->GetEditAreaId($arItem['ID']."_".$q);?>">
		<?php if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_IMG_SMALL"])):?>
			<div class="news-picture"><?php if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img class="preview_picture" border="0" src="<?=$arItem["PREVIEW_IMG_SMALL"]["SRC"]?>" width="<?=$arItem["PREVIEW_IMG_SMALL"]["WIDTH"]?>" height="<?=$arItem["PREVIEW_IMG_SMALL"]["HEIGHT"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" /></a>
			<?php else:?>
				<img class="preview_picture" border="0" src="<?=$arItem["PREVIEW_IMG_SMALL"]["SRC"]?>" width="<?=$arItem["PREVIEW_IMG_SMALL"]["WIDTH"]?>" height="<?=$arItem["PREVIEW_IMG_SMALL"]["HEIGHT"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
			<?php endif;?>
			</div>
		<?php endif?>
		<div class="news-text">
		<?php if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<span class="news-date-time"><?php echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
		<?php endif?>
		<?php if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<div class="news-name">
			<?php if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?php echo $arItem["DETAIL_PAGE_URL"]?>"><b><?php echo $arItem["NAME"]?></b></a><br />
			<?php else:?>
				<b><?php echo $arItem["NAME"]?></b><br />
			<?php endif;?>
			</div>
		<?php endif;?>
		<?php if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
			<span class="news-preview-text"><?php echo $arItem["PREVIEW_TEXT"];?></span>
		<?php endif;?>
		<?php foreach($arItem["FIELDS"] as $code=>$value):?>
			<?php if($code == 'SHOW_COUNTER' && empty($value)) $value = 0; ?>
			<span class="news-show-property"><?php if($code == 'SHOW_COUNTER'):?><?=GetMessage("IBLOCK_REVIEWS")?><?php else:?><?=GetMessage("IBLOCK_FIELD_".$code)?><?php endif;?>:&nbsp;<?=$value;?></span>
		<?php endforeach;?>
		<?php foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
		
			<span class="news-show-property"><?php if($pid == 'FORUM_MESSAGE_CNT'):?><?=GetMessage("IBLOCK_COMMENT")?><?php else:?><?=$arProperty["NAME"]?><?php endif;?>:&nbsp;
			<?php if(is_array($arProperty["DISPLAY_VALUE"])):?>
				<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
			<?php else:?>
				<?=$arProperty["DISPLAY_VALUE"];?>
			<?php endif?>
			</span>
		<?php endforeach;?>
		<?php if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<div style="clear:both"></div>
		<?php endif?>		
		</div>
	</div>
<?php endforeach;?>
<?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?php endif;?>
</div>
