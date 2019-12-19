<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
__IncludeLang(dirname(__FILE__).'/lang/'.LANGUAGE_ID.'/'.basename(__FILE__));

if (sizeof($arResult['FILES']) <= 0)
	return;
WDUFLoadStyle();
?><script>BX.message({'WDUF_FILE_TITLE_REV_HISTORY':'<?=GetMessageJS("WDUF_FILE_TITLE_REV_HISTORY")?>'});</script><?php 
foreach ($arResult['FILES'] as $id => $arWDFile)
{
	if (CFile::IsImage($arWDFile['NAME'], $arWDFile["FILE"]["CONTENT_TYPE"]))
	{
		?><div id="wdif-doc-<?=$arWDFile['ID']?>" class="feed-com-file-inline feed-com-file-wrap wduf-files-entity"><?php 
			?><span class="feed-com-file-inline feed-com-img-wrap feed-com-img-load" style="width:<?=$arWDFile["width"]?>px;height:<?=$arWDFile["height"]?>px;"><?php 
				?><img onload="this.parentNode.className='feed-com-img-wrap';" <?php 
				?> src="<?=$arWDFile["src"]?>" <?php 
				?> width="<?=$arWDFile["width"]?>"<?php 
				?> height="<?=$arWDFile["height"]?>"<?php 
				?> alt="<?=htmlspecialcharsbx($arWDFile["NAME"])?>"<?php 
				?> data-bx-viewer="image"<?php 
				?> data-bx-title="<?=htmlspecialcharsbx($arWDFile["NAME"])?>"<?php 
				?> data-bx-src="<?=$arWDFile["basic"]["src"] ?>"<?php 
				?> data-bx-download="<?=$arWDFile["VIEW"] . '?&ncc=1&force_download=1'?>"<?php 
				?> data-bx-document="<?=$arWDFile['EDIT'] ?>"<?php 
				?> data-bx-width="<?=$arWDFile["basic"]["width"]?>"<?php 
				?> data-bx-height="<?=$arWDFile["basic"]["height"]?>"<?php 
				if (!empty($arWDFile["original"])) {
				?> data-bx-full="<?=$arWDFile["original"]["src"]?>"<?php 
				?> data-bx-full-width="<?=$arWDFile["original"]["width"]?>" <?php 
				?> data-bx-full-height="<?=$arWDFile["original"]["height"]?>"<?php 
				?> data-bx-full-size="<?=$arWDFile["SIZE"]?>"<?php  }
				?> /><?php 
			?></span><?php 
		?></div><?php 
	}
	else
	{
		$possiblePreview = isset($arResult['allowExtDocServices']) && $arResult['allowExtDocServices'] && in_array(ltrim($arWDFile["EXTENSION"], '.'), CWebDavExtLinks::$allowedExtensionsGoogleViewer);
		if($possiblePreview && $arWDFile["FILE"]['FILE_SIZE'] < CWebDavExtLinks::$maxSizeForView){
		?><a target="_blank" href="<?=htmlspecialcharsbx($arWDFile["PATH"])?>" <?php 
			?>title="<?=htmlspecialcharsbx($arWDFile["NAVCHAIN"])?>" <?php 
			?>onclick="WDInlineElementClickDispatcher(this, 'wdif-doc-<?=$arWDFile['ID']?>'); return false;" <?php 
			?> alt="<?=htmlspecialcharsbx($arWDFile["NAME"])?>" class="feed-com-file-inline feed-com-file-wrap wduf-files-entity"><?php 
			?><span class="feed-com-file-inline feed-com-file-icon feed-file-icon-<?=htmlspecialcharsbx($arWDFile["EXTENSION"])?>"></span><?php 
			?><span class="feed-com-file-inline feed-com-file-name"><?=htmlspecialcharsbx($arWDFile["NAME"])?></span><?php 
			?><span class="feed-com-file-inline feed-com-file-size">(<?=$arWDFile["SIZE"]?>)</span><?php 
		?></a><?php  }else{
		?><a target="_blank" href="<?=htmlspecialcharsbx($arWDFile["PATH"])?>" <?php 
			?>title="<?=htmlspecialcharsbx($arWDFile["NAVCHAIN"])?>" <?php 
			?>alt="<?=htmlspecialcharsbx($arWDFile["NAME"])?>" class="feed-com-file-inline feed-com-file-wrap"><?php 
			?><span class="feed-com-file-inline feed-com-file-icon feed-file-icon-<?=htmlspecialcharsbx($arWDFile["EXTENSION"])?>"></span><?php 
			?><span class="feed-com-file-inline feed-com-file-name"><?=htmlspecialcharsbx($arWDFile["NAME"])?></span><?php 
			?><span class="feed-com-file-inline feed-com-file-size">(<?=$arWDFile["SIZE"]?>)</span><?php 
		?></a><?php 
		}
	}
}
?>