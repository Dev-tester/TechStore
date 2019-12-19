<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$this->IncludeLangFile("show.php");
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
/** @var \Bitrix\Disk\Internals\BaseComponent $component */
if (sizeof($arResult['FILES']) <= 0)
{
	return;
}

$jsIds = "";

foreach ($arResult['FILES'] as $id => $file)
{
	if (array_key_exists("IMAGE", $file))
	{
		?><img id="<?=$id?>" src="<?=$file["INLINE"]["src"]?>" <?php 
		?> width="<?=$file["INLINE"]["width"]?>"<?php 
		?> height="<?=$file["INLINE"]["height"]?>"<?php 
		?> alt="<?=htmlspecialcharsbx($file["NAME"])?>"<?php 
		?> /><?php 
	}
	else
	{
		?><a href="<?=$file["VIEW_URL"]?>" style="color: #146cc5;"><?=$file["NAME"]?> (<?=$file["SIZE"]?>)</a><?php 
	}
}