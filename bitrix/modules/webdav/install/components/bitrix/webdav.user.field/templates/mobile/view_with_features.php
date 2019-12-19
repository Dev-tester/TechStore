<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (sizeof($arResult['FILES']) <= 0)
	return;
WDUFLoadStyle();

$jsIds = "";
foreach ($arResult['FILES'] as $id => $arWDFile)
{
	if (CFile::IsImage($arWDFile['NAME']))
	{
		$nodeId = "webdav-inline-".$id."-".randString(4);
		$jsIds .= $jsIds !== "" ? ', "'.$nodeId.'"' : '"'.$nodeId.'"';
		?><img src="<?=CMobileLazyLoad::getBase64Stub()?>" <?php 
			?> border="0" <?php 
			?> data-preview-src="<?=$arWDFile['SMALL_SRC']?>" <?php 
			?> data-src="<?=$arWDFile['SRC']?>" <?php 
			?> title="<?=htmlspecialcharsbx($arWDFile['NAME'])?>" <?php 
			?> alt="<?=htmlspecialcharsbx($arWDFile['NAME'])?>" <?php 
			?> data-bx-image="<?=$arWDFile['PATH']?>" <?php 
			?> width="<?=round($arWDFile['WIDTH']/2)?>" <?php 
			?> height="<?=round($arWDFile['HEIGHT']/2)?>" <?php 
			?> id="<?=$nodeId?>" />
			<?php 
	}
	else
	{
		?><a target="_blank" href="<?=htmlspecialcharsbx($arWDFile['PATH'])?>" <?php 
			?>id="wdif-doc-<?=$arWDFile['ID']?>" <?php 
			?>title="<?=htmlspecialcharsbx($arWDFile['NAVCHAIN'])?>" <?php 
			?>alt="<?=htmlspecialcharsbx($arWDFile['NAME'])?>" class="feed-com-file-wrap"><?php 
			?><span class="feed-com-file-icon feed-file-icon-<?=htmlspecialcharsbx($arWDFile['EXTENSION'])?>"></span><?php 
			?><span class="feed-com-file-name"><?=htmlspecialcharsbx($arWDFile['NAME'])?></span><?php 
			?><span class="feed-com-file-size">(<?=$arWDFile['SIZE']?>)</span><?php 
		?></a><?php 
	}
}

if (strlen($jsIds) > 0)
{
	?><script>BitrixMobile.LazyLoad.registerImages([<?=$jsIds?>], oMSL.checkVisibility);</script><?php 
}
?>