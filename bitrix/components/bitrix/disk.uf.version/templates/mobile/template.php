<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
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

?><div id="wdif-version-block-<?=$arResult['UID']?>" class="post-item-file-version-wrap"><?php 
foreach($arResult['VERSIONS'] as $version)
{
	$title = Loc::getMessage('DISK_UF_VERSION_HISTORY_FILE_MOBILE', array('#NUMBER#' => $version['GLOBAL_CONTENT_VERSION']));
	if($arResult['ONLY_HEAD_VERSION'])
	{
		$title = Loc::getMessage('DISK_UF_HEAD_VERSION_HISTORY_FILE_MOBILE');
	}

	?><div class="post-item-file-version"><?= Loc::getMessage('DISK_UF_VERSION_HISTORY_FILE_MOBILE', array('#NUMBER#' => $version['GLOBAL_CONTENT_VERSION'])) ?></div><?php 
	?><div id="wdif-doc-version-<?=$version['ID'] . $arResult['UID']?>" class="post-item-attached-file"><?php 
		if (in_array(ToLower($version["EXTENSION"]), array("exe")))
		{
			?><span><?php 
				?><span><?=htmlspecialcharsbx($version['NAME'])?></span><span>(<?=$version['SIZE']?>)</span><?php 
			?></span><?php 
		}
		else
		{
			?><a 
				onclick="app.openDocument({'url' : '<?=$version['DOWNLOAD_URL']?>'});" 
				href="javascript:void()" 
				class="post-item-attached-file-link" 
			><?php 
					?><span><?=htmlspecialcharsbx($version['NAME'])?></span><?php 
					?><span>(<?=$version['SIZE']?>)</span><?php 
			?></a><?php 
		}
	?></div><?php 
}
?></div>
