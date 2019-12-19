<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (sizeof($arResult['IMAGES']) > 0)
{
	?><div id="wdif-block-img-<?=$arResult['UID']?>" class="post-item-attached-img-wrap"><?php 
	$jsIds = "";
	foreach($arResult['IMAGES'] as $id => $arWDFile)
	{
		$id = "webdav-attached-".$id."-".randString(4);
		$jsIds .= $jsIds !== "" ? ', "'.$id.'"' : '"'.$id.'"';
		?><div class="post-item-attached-img-block"><?php 
			?><img 
				class="post-item-attached-img" 
				id="<?=$id?>" 
				src="<?=CMobileLazyLoad::getBase64Stub()?>" 
				data-src="<?=$arWDFile["THUMB_SRC"]?>" 
				alt="" 
				border="0" 
				data-bx-image="<?=$arWDFile['PATH']?>" /><?php 
		?></div><?php 
	}
	?></div><?php 
	if (strlen($jsIds) > 0)
	{
		?><script>BitrixMobile.LazyLoad.registerImages([<?=$jsIds?>], oMSL.checkVisibility);</script><?php 
	}
}

if (sizeof($arResult['FILES']) > 0)
{
	?><div id="wdif-block-<?=$arResult['UID']?>" class="post-item-attached-file-wrap"><?php 

	foreach ($arResult['FILES'] as $id => $arWDFile)
	{
		?><div id="wdif-doc-<?=$arWDFile['ID']?>" class="post-item-attached-file"><?php 
			if (in_array(ToLower($arWDFile["EXTENSION"]), array("exe")))
			{
				?><span title="<?=htmlspecialcharsbx($arWDFile['NAVCHAIN'])?>"><span><?=htmlspecialcharsbx($arWDFile['NAME'])?></span><span>(<?=$arWDFile['SIZE']?>)</span></span><?php 
			}
			else
			{
				?><a onclick="app.openDocument({'url' : '<?=$arWDFile['PATH']?>'});" href="javascript:void()" class="post-item-attached-file-link" title="<?=htmlspecialcharsbx($arWDFile['NAVCHAIN'])?>"><span><?=htmlspecialcharsbx($arWDFile['NAME'])?></span><span>(<?=$arWDFile['SIZE']?>)</span></a><?php 
			}
		?></div><?php 
	}

	?></div><?php 
}
?>
