<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="news-list">

<?php if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?php endif;?>


<?php foreach($arResult["ITEMS"] as $arItem):?>
	<p class="news-item">
		<?php if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img class="preview_picture" border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>" height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" title="<?=$arItem["NAME"]?>" style="float:left" /></a>
		<?php endif?>

		<?php if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<?php if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?php echo $arItem["DETAIL_PAGE_URL"]?>"><b><?php echo $arItem["NAME"]?></b></a><br />
			<?php else:?>
				<b><?php echo $arItem["NAME"]?></b><br />
			<?php endif;?>
		<?php endif;?>
		<?php if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
			<?php echo $arItem["PREVIEW_TEXT"];?>
		<?php endif;?>
		<?php if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
			<div style="clear:both"></div>
		<?php endif?>
		
		<?php if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<br /><span class="news-date-time"><img src="<?=$templateFolder?>/images/clocks.gif" width="9" height="9" border="0" alt="">&nbsp;<?php echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
		<?php endif?>

		<?php if (isset($arItem["DISPLAY_PROPERTIES"]["FORUM_MESSAGE_CNT"])):?>
			<span class="news-date-time">|&nbsp;<img src="<?=$templateFolder?>/images/comments.gif" width="10" height="10" border="0" alt="">&nbsp;<?=GetMessage("NEWS_COMMENTS")?>: <?=$arItem["DISPLAY_PROPERTIES"]["FORUM_MESSAGE_CNT"]["VALUE"]?></span>
		<?php endif?>

		<?php if (isset($arItem["DISPLAY_PROPERTIES"]["rating"])):?>
			<span class="news-date-time">|&nbsp;<img src="<?=$templateFolder?>/images/rating.gif" width="11" height="11" border="0" alt="">&nbsp;<?=GetMessage("NEWS_RATING")?>: <?=$arItem["DISPLAY_PROPERTIES"]["rating"]["VALUE"]?></span>
		<?php endif?>
	</p>
<?php endforeach;?>

<?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?php endif;?>
</div>