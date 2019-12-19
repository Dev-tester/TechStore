<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="news-detail">
	<?php if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
	<div class="news-picture">
		<img class="detail_picture" border="0" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["NAME"]?>"  title="<?=$arResult["NAME"]?>" />
	</div>
	<?php endif?>
	
	<?php if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<span class="news-date-time"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
	<?php endif;?>
	<?php if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<h3><?=$arResult["NAME"]?></h3>
	<?php endif;?>
	<div class="news-text">
	<?php if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<p><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
	<?php endif;?>
	<?php if($arResult["NAV_RESULT"]):?>
		<?php if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?php endif;?>
		<?php echo $arResult["NAV_TEXT"];?>
		<?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?php endif;?>
 	<?php elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?php echo $arResult["DETAIL_TEXT"];?>
 	<?php else:?>
		<?php echo $arResult["PREVIEW_TEXT"];?>
	<?php endif?>
	<div style="clear:both"></div>

	<?php foreach($arResult["FIELDS"] as $code=>$value):?>
		<?php if ($code != 'PREVIEW_PICTURE'):?>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			<br />
		<?php endif?>
	<?php endforeach;?>
	
	<?php foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
		<?php if($pid != "THEME"):?>
			<div class="news-property"><?=$arProperty["NAME"]?>:&nbsp;
			<?php if(is_array($arProperty["DISPLAY_VALUE"])):?>
				<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
			<?php else:?>
				<?=$arProperty["DISPLAY_VALUE"];?>
			<?php endif?>
			</div>
		<?php endif?>
	<?php endforeach;?>
		<div class="news-property">
			<?=GetMessage("T_NEWS_SHORT_URL");
			$shortPageURL = (CMain::IsHTTPS()) ? "https://" : "http://";
			$host = (SITE_SERVER_NAME == "") ?  $_SERVER['HTTP_HOST'] : SITE_SERVER_NAME;
			$shortPageURL.= htmlspecialcharsbx($host).CBXShortUri::GetShortUri($arResult["~DETAIL_PAGE_URL"]);
			?>
			<a href="<?=$shortPageURL?>"><?=$shortPageURL?></a>
		</div>
	</div>
	<div class="news-detail-back"><a href="<?=$arResult["SECTION_URL"]?>"><?=GetMessage("T_NEWS_DETAIL_BACK")?></a></div>
	<?php 
	if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
	{
		?>
		<div class="news-detail-share">
			<noindex>
			<?php 
			$APPLICATION->IncludeComponent("bitrix:main.share", "", array(
					"HANDLERS" => $arParams["SHARE_HANDLERS"],
					"PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
					"PAGE_TITLE" => $arResult["~NAME"],
					"SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
					"SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
					"HIDE" => $arParams["SHARE_HIDE"],
				),
				$component,
				array("HIDE_ICONS" => "Y")
			);
			?>
			</noindex>
		</div>
		<?php 
	}
	?>
	<?php foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
		<?php if($pid == "THEME" && count($arResult["ITEMS_THEME"]) > 0 ):?>
			<div class="news-detail-theme">
			<div class="news-theme-title"><?=GetMessage("T_NEWS_DETAIL_THEME")?>:&nbsp;
				<?php if(is_array($arProperty["DISPLAY_VALUE"])):?>
				<?=implode(",&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
			<?php else:?>
				<?=$arProperty["DISPLAY_VALUE"];?>
			<?php endif?>
			</div>
			<?php foreach($arResult["ITEMS_THEME"] as $pid=>$arProperty):?>
				<div class="news-theme-item"><div class="news-theme-date"><?=$arProperty["ACTIVE_FROM"]?></div><div class="news-theme-url"><a href="<?=$arProperty["DETAIL_PAGE_URL"]?>"><?=$arProperty["NAME"]?></a></div></div>
			<?php endforeach;?>
			<div class="br"></div>
			</div>
		<?php endif?>
	<?php endforeach;?>
	
	
</div>
