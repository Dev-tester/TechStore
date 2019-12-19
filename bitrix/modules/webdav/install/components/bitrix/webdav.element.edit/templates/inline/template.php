<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->RestartBuffer(); // ajax usage only
	while (ob_end_clean()) {true;}

/** @var CWebDavIblock $ob */
$ob = $arParams['OBJECT'];

if (!empty($arResult["ERROR_MESSAGE"]))
{
	ShowError($arResult["ERROR_MESSAGE"]);
}
if (!empty($arResult["NOTIFY_MESSAGE"]))
{
    ShowNote($arResult["NOTIFY_MESSAGE"]);
}

if (empty($arResult['ERROR_MESSAGE']))
{
	$title = '';
	$dropped = $createdFile = false;
	if ($ob->_isInMeta($arResult["ELEMENT"]["ID"], "DROPPED"))
	{
		$savedMetaData = CWebDavIblock::getDroppedMetaData();

		$title = GetMessage('WD_MY_LIBRARY');
		$title .= ' / ' . $savedMetaData['alias'];
		$dropped = true;
	}
	elseif (isset($ob->attributes['user_id']) && ($ob->attributes['user_id'] == $GLOBALS['USER']->GetID()))
	{
		$title = GetMessage('WD_MY_LIBRARY');
		if(CIBlockWebdavSocnet::isCreatedDocFolder($ob->IBLOCK_ID, $arResult['ELEMENT']['IBLOCK_SECTION_ID'], $GLOBALS['USER']->GetID()))
		{
			$createdFile = true;
			$title .= ' / ' . CIBlockWebdavSocnet::getNameCreatedDocFolder();
		}
	}
	elseif (isset($ob->attributes['group_id']))
	{
		$title = $ob->arRootSection['NAME'];
		if ($title == GetMessage("SONET_GROUP_PREFIX")) // workaround of an old bug, folder don't contain group name
		{
			$arGroup = CSocNetGroup::GetByID($ob->attributes['group_id']);
			$title = GetMessage("SONET_GROUP_PREFIX").$arGroup['NAME'];
		}
	}
	else
	{
		$title = $arParams['STR_TITLE'];
	}
?>
<table>
	<tr class="wd-inline-file" id="wd-doc<?=intval($arResult["ELEMENT"]['ID'])?>">
		<td class="files-name">
			<span class="files-text">
				<span class="f-wrap"><?=htmlspecialcharsEx($arResult["ELEMENT"]['NAME'])?></span>
<?php 		if (CFile::IsImage($arResult["ELEMENT"]['NAME'], $arResult["ELEMENT"]['FILE']["CONTENT_TYPE"])) { ?>
				<span class="wd-files-icon files-preview-wrap">
					<span class="files-preview-border">
						<span class="files-preview-alignment">
							<img class="files-preview" src="<?=$arResult["ELEMENT"]["URL"]["DOWNLOAD"]?>" <?php 
								?> data-bx-width="<?=$arResult["ELEMENT"]['FILE']['WIDTH']?>"<?php 
								?> data-bx-height="<?=$arResult["ELEMENT"]['FILE']['HEIGHT']?>"<?php 
								?> data-bx-document="<?=$arResult["ELEMENT"]["URL"]['~DOWNLOAD']?>"<?php 
								?> />
						</span>
					</span>
				</span>
<?php 		} else { ?>
				<span class="wd-files-icon feed-file-icon-<?=GetFileExtension($arResult["ELEMENT"]['NAME'])?>"></span>
<?php 		}?>
				<a class="file-edit" href="<?=$arResult['ELEMENT']['URL']['EDIT']?>">edit</a>
				<a class="file-section" href="<?=$arResult['ELEMENT']['URL']['SECTION']?>">section</a>
			</span>
		</td>
		<td class="files-size"><?=$arResult["ELEMENT"]["FILE_SIZE"]?></td>
		<td class="files-storage">
			<div class="files-storage-block">
<?php  if ($dropped || $createdFile) { ?>
				<span class="files-storage-text">
					<?=GetMessage("WD_SAVED_PATH")?>:
				</span>
				<a class="files-path" href="javascript:void(0);"><?=htmlspecialcharsEx($title)?></a>
				<span class="edit-stor"></span>
<?php  } else { ?>
				<span class="files-placement"><?=htmlspecialcharsEx($title)?></span>
<?php  } ?>
			</div>
		</td>
	</tr>
</table>
<?php 
}
?>
<?php die();?>
