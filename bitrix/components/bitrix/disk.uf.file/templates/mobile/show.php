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
	empty($arResult['IMAGES'])
	&& empty($arResult['FILES'])
	&& empty($arResult['DELETED_FILES'])
)
{
	return;
}

if (!empty($arResult['IMAGES']))
{
	?><div id="wdif-block-img-<?=$arResult['UID']?>" class="post-item-attached-img-wrap"><?php 
	$jsIds = "";
	foreach($arResult['IMAGES'] as $id => $file)
	{
		$id = "disk-attach-".$file['ID'];
		$jsIds .= $jsIds !== "" ? ', "'.$id.'"' : '"'.$id.'"';
		?><div class="post-item-attached-img-block"<?=(!empty($file['HIDDEN']) && $file['HIDDEN'] == 'Y' ? ' style="display: none;"' : '')?>><?php 
			?><img<?php 
				?> class="post-item-attached-img"<?php 
				?> id="<?=$id?>"<?php 
				?> src="<?=CMobileLazyLoad::getBase64Stub()?>"<?php 
				?> data-src="<?=$file["THUMB"]["src"]?>"<?php 
				?> alt="<?=htmlspecialcharsbx($file["NAME"])?>"<?php 
				?> border="0"<?php 
				?> data-bx-title="<?=htmlspecialcharsbx($file["NAME"])?>"<?php 
				?> data-bx-size="<?=$file["SIZE"]?>"<?php 
				?> data-bx-width="<?=$file["BASIC"]["width"]?>"<?php 
				?> data-bx-height="<?=$file["BASIC"]["height"]?>"<?php 
				?> bx-attach-file-id="<?=$file['FILE_ID']?>"<?php 
				if ($file['XML_ID']): ?> bx-attach-xml-id="<?=$file['XML_ID']?>"<?php endif;
				?> data-bx-src="<?=$file["BASIC"]["src"] ?>"<?php 
				?> data-bx-preview="<?=$file["PREVIEW"]["src"] ?>"<?php 
				?> data-bx-image="<?=$file["BASIC"]["src"] ?>" /><?php 
		?></div><?php 
	}
	?></div><?php 

	if (strlen($jsIds) > 0)
	{
		?><script>BitrixMobile.LazyLoad.registerImages([<?=$jsIds?>], typeof oMSL != 'undefined' ? oMSL.checkVisibility : false);</script><?php 
	}
}

if (!empty($arResult['FILES']))
{
	?><div id="wdif-block-<?=$arResult['UID']?>" class="post-item-attached-file-wrap"><?php 
	foreach($arResult['FILES'] as $file)
	{
		$attributes = array(
			"id" => "disk-attach-".$file['ID'],
			"bx-attach-file-id" => $file['FILE_ID'],
			"data-bx-title" => htmlspecialcharsbx($file["NAME"]),
			"title" => htmlspecialcharsbx($file['NAVCHAIN'])
		);
		if ($file['XML_ID'])
			$attributes["bx-attach-xml-id"] = $file['XML_ID'];
		$t = "";
		foreach ($attributes as $k => $v)
		{
			$t .= $k.'="'.$v.'" ';
		}
		$attributes = $t;

		?><div id="wdif-doc-<?=$file['ID']?>" class="post-item-attached-file"><?php 
			if (in_array(tolower($file["EXTENSION"]), array("exe")))
			{
				?><span <?=$attributes?>><span><?=htmlspecialcharsbx($file['NAME'])?></span><span>(<?=$file['SIZE']?>)</span></span><?php 
			}
			else
			{
				?><a <?=$attributes
					?>onclick="app.openDocument({'url' : '<?=$file['DOWNLOAD_URL']?>'}); return BX.PreventDefault(event);" <?php 
					?>href="javascript:void();" <?php 
					?>class="post-item-attached-file-link"><span><?=htmlspecialcharsbx($file['NAME'])?></span><span>(<?=$file['SIZE']?>)</span></a><?php 
			}
		?></div><?php 
	}
	?></div><?php 
}
if(!empty($arResult['DELETED_FILES']))
{
	?><div id="wdif-block-deleted-files-<?=$arResult['UID']?>" class="post-item-attached-file-wrap"><?php 
	foreach($arResult['DELETED_FILES'] as $file)
	{
		?><div id="wdif-doc-<?=$file['ID']?>" class="post-item-attached-file"><?php 
		?><span style="display: none;"></span><span class="post-item-attached-file-deleted-name"><?=htmlspecialcharsbx($file['NAME'])?><span style="display: none;"></span><span> (<?=$file['SIZE']?>)</span><span class="post-item-attached-file-text-notice" href="#"><?= GetMessage('DISK_UF_FILE_IS_DELETED') ?></span></span><?php 
		?></div><?php 
	}
	?></div><?php 
}
?>

