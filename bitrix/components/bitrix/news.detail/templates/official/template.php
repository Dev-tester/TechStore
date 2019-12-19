<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="news-detail">
	<?php if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
		<img
			class="detail_picture"
			border="0"
			src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
			width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
			height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
			alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
			title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
			/>
	<?php endif?>

	<table cellpadding="0" cellspacing="0" width="100">
		<tr>
			<td width="0%" style="padding-right: 5px"><?php 
				$image = $templateFolder."/images/".strtolower($arResult["DISPLAY_PROPERTIES"]["DOC_TYPE"]["VALUE_XML_ID"]).".gif";
				if(file_exists($_SERVER["DOCUMENT_ROOT"].$image)):
					?><img border="0" src="<?=$image?>" width="30" height="30" alt="<?=$arResult["DISPLAY_PROPERTIES"]["DOC_TYPE"]["VALUE_ENUM"]?>" hspace="0" vspace="0" title="<?=$arResult["DISPLAY_PROPERTIES"]["DOC_TYPE"]["VALUE_ENUM"]?>" style="float:left" /><?php 
				endif?><br /></td>
			<td width="100%" valign="top" class="news-date-time"><?php if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<span style="white-space: nowrap;"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span><?php endif;?></td>
		</tr>
	</table>
	<br />

	<?php if(false && $arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<h3><?=$arResult["NAME"]?></h3><br/>
	<?php endif;?>
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
	<br />
	<?php foreach($arResult["FIELDS"] as $code=>$value):?>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			<br />
	<?php endforeach;?>
	<?php foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>

		<?=$arProperty["NAME"]?>:&nbsp;<b>
		<?php if(is_array($arProperty["DISPLAY_VALUE"])):?>
			<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
		<?php else:?>
			<?=$arProperty["DISPLAY_VALUE"];?>
		<?php endif?>
		<br /></b>
	<?php endforeach;?>
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
	<p><a href="<?php echo $arResult["LIST_PAGE_URL"];?>"><?=GetMessage("NEWS_BACK_TEXT")?></a></p>
</div>