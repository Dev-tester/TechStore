<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

\Bitrix\Main\Localization\Loc::loadLanguageFile(__DIR__ . '/../.default/show.php');

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
if (
	sizeof($arResult['FILES']) <= 0
)
{
	return;
}

$jsIds = "";
foreach ($arResult['FILES'] as $file)
{
	if($file['IS_MARK_DELETED'])
	{
		?><span <?php 
		?>title="<?=htmlspecialcharsbx($file["NAVCHAIN"])?>" <?php 
		?> class="post-item-inline-attached-file post-item-attached-file-deleted-name"<?php 
		?>><?php 
		?><span class="feed-com-file-icon feed-file-icon-<?=htmlspecialcharsbx($file['EXTENSION'])?>"></span><?php 
		?><span class="feed-com-file-name"><?=htmlspecialcharsbx($file["NAME"])?></span><?php 
		?><span class="feed-com-file-size"> (<?=$file['SIZE']?>)</span><?php 
		?><?php 
		?></span><?php 
	}
	elseif (array_key_exists("IMAGE", $file))
	{
		$nodeId = "webdav-inline-".$file["ID"]."-".$this->getComponent()->randString(4);
		$jsIds .= $jsIds !== "" ? ', "'.$nodeId.'"' : '"'.$nodeId.'"';
		?><img src="<?=CMobileLazyLoad::getBase64Stub()?>" <?php 
			?> border="0" <?php 
			?> data-preview-src="<?=$file["SMALL"]["src"]?>" <?php 
			?> data-src="<?=$file["INLINE"]['src']?>" <?php  // inline
			?> title="<?=htmlspecialcharsbx($file['NAME'])?>" <?php 
			?> alt="<?=htmlspecialcharsbx($file['NAME'])?>" <?php 
			?> data-bx-image="<?=$file["BASIC"]["src"]?>" <?php  // gallery
			?> data-bx-preview="<?=$file["PREVIEW"]["src"]?>" <?php  // gallery preview
			?> width="<?=round($file["INLINE"]["width"]/2)?>" <?php 
			?> height="<?=round($file["INLINE"]["height"]/2)?>" <?php 
			?> id="<?=$nodeId?>" /><?php 
	}
	elseif (array_key_exists("VIDEO", $file))
	{
		echo $file['VIDEO'];
	}
	else
	{
		?><a onclick="app.openDocument({'url' : '<?=$file['DOWNLOAD_URL']?>'}); return BX.PreventDefault(event);" href="javascript:void()" <?php 
			?>id="wdif-doc-<?=$file['ID']?>" <?php 
			?>title="<?=htmlspecialcharsbx($file['NAVCHAIN'])?>" <?php 
			?>alt="<?=htmlspecialcharsbx($file['NAME'])?>" class="feed-com-file-wrap post-item-inline-attached-file"><?php 
			?><span class="feed-com-file-icon feed-file-icon-<?=htmlspecialcharsbx($file['EXTENSION'])?>"></span><?php 
			?><span class="feed-com-file-name"><?=htmlspecialcharsbx($file['NAME'])?></span><?php 
			?><span class="feed-com-file-size"> (<?=$file['SIZE']?>)</span><?php 
		?></a><?php 
	}
}

if (strlen($jsIds) > 0)
{
	?><script>BitrixMobile.LazyLoad.registerImages([<?=$jsIds?>], typeof oMSL != 'undefined' ? oMSL.checkVisibility : false);</script><?php 
}