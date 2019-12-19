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
$this->setFrameMode(false);
?>
<?='<?php xml version="1.0" encoding="'.SITE_CHARSET.'"?>'?>
<rss version="2.0"<?php if($arParams["YANDEX"]) echo ' xmlns="http://backend.userland.com/rss2" xmlns:yandex="http://news.yandex.ru"';?>>
<channel>
<title><?=$arResult["NAME"].(strlen($arResult["SECTION"]["NAME"])>0?" / ".$arResult["SECTION"]["NAME"]:"")?></title>
<link><?=CHTTP::URN2URI("", $arResult["SERVER_NAME"])?></link>
<description><?=strlen($arResult["SECTION"]["DESCRIPTION"])>0?$arResult["SECTION"]["DESCRIPTION"]:$arResult["DESCRIPTION"]?></description>
<lastBuildDate><?=date("r")?></lastBuildDate>
<ttl><?=$arResult["RSS_TTL"]?></ttl>
<?php if(is_array($arResult["PICTURE"])):?>
	<?php $image = substr($arResult["PICTURE"]["SRC"], 0, 1) == "/"? CHTTP::URN2URI($arResult["PICTURE"]["SRC"], $arResult["SERVER_NAME"]): $arResult["PICTURE"]["SRC"];?>
	<?php if($arParams["YANDEX"]):?>
		<yandex:logo><?=$image?></yandex:logo>
		<?php 
		$squareSize = min($arResult["PICTURE"]["WIDTH"], $arResult["PICTURE"]["HEIGHT"]);
		if ($squareSize > 0)
		{
			$squarePicture = CFile::ResizeImageGet(
				$arResult["PICTURE"],
				array("width" => $squareSize, "height" => $squareSize),
				BX_RESIZE_IMAGE_EXACT
			);
			if ($squarePicture)
			{
				$squareImage = substr($squarePicture["src"], 0, 1) == "/"? CHTTP::URN2URI($squarePicture["src"], $arResult["SERVER_NAME"]): $squarePicture["src"];
				?><yandex:logo type="square"><?=$squareImage?></yandex:logo><?php 
			}
		}
		?>
	<?php else:?>
		
		<image>
			<title><?=$arResult["NAME"]?></title>
			<url><?=$image?></url>
			<link><?=CHTTP::URN2URI("", $arResult["SERVER_NAME"])?></link>
			<width><?=$arResult["PICTURE"]["WIDTH"]?></width>
			<height><?=$arResult["PICTURE"]["HEIGHT"]?></height>
		</image>
	<?php endif?>
<?php endif?>
<?php foreach($arResult["ITEMS"] as $arItem):?>
<item>
	<title><?=$arItem["title"]?></title>
	<link><?=$arItem["link"]?></link>
	<description><?=$arItem["description"]?></description>
	<?php if(is_array($arItem["enclosure"])):?>
		<enclosure url="<?=$arItem["enclosure"]["url"]?>" length="<?=$arItem["enclosure"]["length"]?>" type="<?=$arItem["enclosure"]["type"]?>"/>
	<?php endif?>
	<?php if($arItem["category"]):?>
		<category><?=$arItem["category"]?></category>
	<?php endif?>
	<?php if($arParams["YANDEX"]):?>
		<yandex:full-text><?=$arItem["full-text"]?></yandex:full-text>
	<?php endif?>
	<pubDate><?=$arItem["pubDate"]?></pubDate>
</item>
<?php endforeach?>
</channel>
</rss>
