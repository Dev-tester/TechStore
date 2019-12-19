<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("photogallery");
$arParams["SHOW_TAGS"] = ($arParams["SHOW_TAGS"] == "Y" ? "Y" : "N");
$arParams["MAX_LENGTH"] = 200;
$arParams["THUMBS_SIZE"] = (intVal($arParams["THUMBS_SIZE"]) > 0 ? intVal($arParams["THUMBS_SIZE"]) : 120);

?><div class="photo-controls"><?php 
if (!empty($arResult["SECTION_TOP_LINK"])):
	?><a href="<?=$arResult["SECTION_TOP_LINK"]?>" title="<?=GetMessage("P_UP_TITLE")?>" class="photo-action back-to-album" <?php 
	?>><?=GetMessage("P_UP")?></a><?php 
endif;
?></div>
<div class="empty-clear"></div><?php 

if(IsModuleInstalled("search") && $arParams["SHOW_TAGS"] == "Y")
{
?><div class="tags-cloud">
<table cellpadding="0" cellspacing="0" border="0" class="tab-header">
	<tr class="top">
		<td class="left"><div class="empty"></div></td>
		<td class="center"><div class="empty"></div></td>
		<td class="right"><div class="empty"></div></td>
	</tr>
	<tr class="middle">
		<td class="left"><div class="empty"></div></td>
		<td class="body-text">
			<div class="photo-head"><?=GetMessage("P_TAGS")?></div><?php 
	$APPLICATION->IncludeComponent("bitrix:search.tags.cloud", ".default",
		Array(
		"SEARCH" => $arResult["REQUEST"]["~QUERY"],
		"TAGS" => $arResult["REQUEST"]["~TAGS"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
		"SORT" => $arParams["TAGS_SORT"],
		"PAGE_ELEMENTS" => $arParams["TAGS_PAGE_ELEMENTS"],
		"PERIOD" => $arParams["TAGS_PERIOD"],
		"URL_SEARCH" => $arResult["SEARCH_LINK"],
		"TAGS_INHERIT" => $arParams["TAGS_INHERIT"],
		"FONT_MAX" => (empty($arParams["FONT_MAX"]) ? "30" : $arParams["FONT_MAX"]),
		"FONT_MIN" => (empty($arParams["FONT_MIN"]) ? "8" : $arParams["FONT_MIN"]),
		"COLOR_NEW" => (empty($arParams["COLOR_NEW"]) ? "707C8F" : $arParams["COLOR_NEW"]),
		"COLOR_OLD" => (empty($arParams["COLOR_OLD"]) ? "C0C0C0" : $arParams["COLOR_OLD"]),
		"PERIOD_NEW_TAGS" => $arParams["PERIOD_NEW_TAGS"],
		"SHOW_CHAIN" => $arParams["SHOW_CHAIN"],
		"COLOR_TYPE" => $arParams["COLOR_TYPE"],
		"WIDTH" => $arParams["WIDTH"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"RESTART" => $arParams["RESTART"],
		"arrFILTER" => array("iblock_".$arParams["IBLOCK_TYPE"]),
		"arrFILTER_iblock_".$arParams["IBLOCK_TYPE"] => array($arParams["IBLOCK_ID"])
		), $component);
?>		</td>
		<td class="right"><div class="empty"></div></td>
	</tr>
	<tr class="bottom">
		<td class="left"><div class="empty"></div></td>
		<td class="center"><div class="empty"></div></td>
		<td class="right"><div class="empty"></div></td>
	</tr>
</table>
</div>
<div class="empty-clear"></div>
<br />
<?php 
}

/*?><form action="" method="get"><?php 
	if($arResult["REQUEST"]["HOW"]=="d"):
		?><input type="hidden" name="how" value="d" /><?php 
	endif;
	?><input type="hidden" name="tags" value="<?php echo $arResult["REQUEST"]["TAGS"]?>" />
	<input type="text" name="q" value="<?=$arResult["REQUEST"]["QUERY"]?>" size="40" />
	&nbsp;<input type="submit" value="<?=GetMessage("SEARCH_GO")?>" />
</form><br /><?php 
*/
?><div class="search-page"><?php 
if($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false):
	?><?php 
elseif($arResult["ERROR_CODE"]!=0):
	?><p><?=GetMessage("SEARCH_ERROR")?></p><?php 
	ShowError($arResult["ERROR_TEXT"]);
	?><p><?=GetMessage("SEARCH_CORRECT_AND_CONTINUE")?></p>
	<br /><br />
	<p><?=GetMessage("SEARCH_SINTAX")?><br /><b><?=GetMessage("SEARCH_LOGIC")?></b></p>
	<table border="0" cellpadding="5">
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_OPERATOR")?></td><td valign="top"><?=GetMessage("SEARCH_SYNONIM")?></td>
			<td><?=GetMessage("SEARCH_DESCRIPTION")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_AND")?></td><td valign="top">and, &amp;, +</td>
			<td><?=GetMessage("SEARCH_AND_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_OR")?></td><td valign="top">or, |</td>
			<td><?=GetMessage("SEARCH_OR_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_NOT")?></td><td valign="top">not, ~</td>
			<td><?=GetMessage("SEARCH_NOT_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top">( )</td>
			<td valign="top">&nbsp;</td>
			<td><?=GetMessage("SEARCH_BRACKETS_ALT")?></td>
		</tr>
	</table><?php 

elseif(count($arResult["SEARCH"])>0):
?><div class="empty-clear"></div><?php 

	$counter = 0;
	foreach ($arResult["SEARCH"] as $arItem):
	$counter++;
	if ($arParams["CELL_COUNT"] > 0 && $counter > $arParams["CELL_COUNT"]):
		?><div class="empty-clear"></div><?php 
		$counter = 1;
	endif;
	?><table cellpadding="0" cellspacing="0" border="0" class="result" style="width:<?=intVal($arParams["THUMBS_SIZE"] + 2 + 20 + 2)?>px; height:<?=intVal($arParams["THUMBS_SIZE"] + 2 + 10 + 2 + 70)?>px;" onmouseover="ShowDescription('<?=$arItem["ELEMENT"]["ID"]?>')" onmouseout="HideDescription('<?=$arItem["ELEMENT"]["ID"]?>')"><tr><th class="result_image"><?php 
		?><div class="photo-image-outer"><?php 
			?><table class="shadow" cellpadding="0" cellspacing="0" border="0" width="0"><?php 
				?><tr valign="middle"><td colspan="2" class="photo-image" align="center" <?php 
					?>style="width:<?=$arParams["THUMBS_SIZE"]?>px; height:<?=$arParams["THUMBS_SIZE"]?>px;"><?php 
	if(is_array($arItem["ELEMENT"]["PREVIEW_PICTURE"])):
		$sTitle = htmlspecialcharsEx($arItem["ELEMENT"]["~NAME"]);
		if($arResult["USER_HAVE_ACCESS"]):
			?><a href="<?=$arItem["ELEMENT"]["URL"]?>"><?php 
				?><?=CFile::ShowImage($arItem["ELEMENT"]["PREVIEW_PICTURE"], $arParams["THUMBS_SIZE"], $arParams["THUMBS_SIZE"],
					"border=\"0\" vspace=\"0\" hspace=\"0\" ".
					"alt=\"".$sTitle."\" title=\"".$sTitle."\"", "", true);?><?php 
			?></a><?php 
		else:
			?><?=CFile::ShowImage($arItem["ELEMENT"]["PREVIEW_PICTURE"], $arParams["THUMBS_SIZE"], $arParams["THUMBS_SIZE"],
				"border=\"0\" vspace=\"0\" hspace=\"0\" ".
				"alt=\"".$sTitle."\" title=\"".$sTitle."\"", "", true);?><?php 
		endif;
	endif;
				?></td></tr><?php 
				?><tr class="b"><?php 
					?><td class="l"><div class="empty"></div></td><?php 
					?><td class="r"><div class="empty"></div></td><?php 
				?></tr><?php 
			?></table><?php 
		?></div><?php 
		?></th></tr><?php 
		?><tr><td class="result_text"><?php 

		if (!empty($arItem)):
		?><div style="position:relative;"><?php 
		?><div class="photo-image-inner" id="item_<?=$arItem["ELEMENT"]["ID"]?>" <?php 
				if (PhotoGetBrowser() == "opera"):
					?> style="overflow:auto; height:150px;"<?php 
				endif;
		?>><?php 
			?><div class="photo-title"><?php 
			if($arResult["USER_HAVE_ACCESS"]):
				?><a href="<?=$arItem["ELEMENT"]["URL"]?>"><?=$arItem["TITLE_FORMATED"]?></a><?php 
			else:
				?><?=$arItem["TITLE_FORMATED"]?><?php 
			endif;

			?></div><?php 

			?><div class="photo-date"><?=$arItem["DATE_CHANGE"]?></div><?php 

		if ($arParams["SHOW_TAGS"] == "Y"):
			?><div class="photo-tags"><?php 
			if (!empty($arItem["TAGS"]))
			{
				$first = true;
				foreach ($arItem["TAGS"] as $tags):
					if (!$first)
					{
						?>, <?php 
					}
					?><a href="<?=$tags["URL"]?>"><?=$tags["TAG_NAME"]?></a><?php 
					$first = false;
				endforeach;
			}
			?></div><?php 
		endif;
			?><div class="photo-description"><?=$arItem["ELEMENT"]["DETAIL_TEXT"]?></div><?php 
		?></div><?php 
		?></div><?php 
		endif;
		?><div class="photo-title" style="width:<?=intVal($arParams["THUMBS_SIZE"] + 14)?>px;overflow:hidden;"><?=$arItem["ELEMENT"]["NAME"]?></div><?php 

		?></td></tr><?php 
	?></table><?php 
	endforeach;
?><div class="empty-clear"></div><?php 


	?><div class="photo-navigation"><?=$arResult["NAV_STRING"]?></div>
	<div class="photogallery-navigation pages"><?php 
	if($arResult["REQUEST"]["HOW"]=="d"):
		?><a href="<?=$arResult["SEARCH_URL"]?>"><?=GetMessage("SEARCH_SORT_BY_RANK")?></a> <span class="active"><?=GetMessage("SEARCH_SORTED_BY_DATE")?></span><?php 
	else:
		?><span class="active"><?=GetMessage("SEARCH_SORTED_BY_RANK")?></a> <a href="<?=$arResult["SEARCH_URL"]?>&amp;how=d"><?=GetMessage("SEARCH_SORT_BY_DATE")?></a><?php 
	endif;
	?></div><?php 
else:
	ShowNote(GetMessage("SEARCH_NOTHING_TO_FOUND"));
endif;
?></div>
<script>
function HideDescription(id)
{
	if (document.getElementById('item_' + id))
		document.getElementById('item_' + id).style.display = 'none';
}
function ShowDescription(id)
{
	if (document.getElementById('item_' + id))
		document.getElementById('item_' + id).style.display = 'block';
}
</script>
