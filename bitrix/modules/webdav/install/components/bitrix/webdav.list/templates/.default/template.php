<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="news-list">
<?php if(!empty($arResult["ITEMS"])):?>
<?php if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?php endif;?>
<?php foreach($arResult["ITEMS"] as $arItem):?>
	<p class="docs-item">
        <?php if($arParams["DISPLAY_PICTURE"]!="N"):?> 
            <?php if (is_array($arItem["PREVIEW_PICTURE"])):?>
                <?php if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img class="preview_picture" border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>" height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" title="<?=$arItem["NAME"]?>" style="float:left" /></a>
                <?php else:?>
                    <img class="preview_picture" border="0" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>" height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" title="<?=$arItem["NAME"]?>" style="float:left" />
                <?php endif;?>
            <?php else:?>
                    <div class="element-icon ic<?=substr($arItem["FILE_EXTENTION"], 1)?>"></div>
            <?php endif?>
        <?php endif?>
		<?php if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
			<div class="news-date-time intranet-date"><?php echo $arItem["DISPLAY_ACTIVE_FROM"]?></div>
		<?php endif?>
		<?php if($arParams["DISPLAY_DATE"]!="N" && $arItem["FIELDS"]["TIMESTAMP_X"]):
			?>
			<div class="news-date-time intranet-date"><?php echo $arItem["FIELDS"]["TIMESTAMP_X"]?></div>
		<?php 
			unset($arItem["FIELDS"]["TIMESTAMP_X"]);
		endif?>
		<?php if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
			<?php if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
				<a href="<?php echo $arItem["DETAIL_PAGE_URL"]?>"><?php echo $arItem["NAME"]?></a><br />
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
		<?php foreach($arItem["FIELDS"] as $code=>$value):?>
            <?php if ($code == "TIMESTAMP_X" && $arParams["DISPLAY_DATE"]!="Y") continue; ?>  
			<small>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			</small><br />
		<?php endforeach;?>
		<?php foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
			<small>
			<?=$arProperty["NAME"]?>:&nbsp;
			<?php if(is_array($arProperty["DISPLAY_VALUE"])):?>
				<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
			<?php else:?>
				<?=$arProperty["DISPLAY_VALUE"];?>
			<?php endif?>
			</small><br />
		<?php endforeach;?>
	</p>
<?php endforeach;?>
<?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?php endif;?>
<?php endif;?>
</div>
