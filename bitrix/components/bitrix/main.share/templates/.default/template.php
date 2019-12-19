<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if (strlen($arResult["PAGE_URL"]) > 0)
{
	?><div class="share-window-parent">
	<div id="share-dialog<?php echo $arResult["COUNTER"]?>" class="share-dialog share-dialog-<?=$arParams["ALIGN"]?>" style="display: <?=(array_key_exists("HIDE", $arParams) && $arParams["HIDE"] == "Y" ? "none" : "block")?>;">
		<div class="share-dialog-inner share-dialog-inner-<?=$arParams["ALIGN"]?>"><?php 
		if (is_array($arResult["BOOKMARKS"]) && count($arResult["BOOKMARKS"]) > 0)
		{
			?><table cellspacing="0" cellpadding="0" border="0" class="bookmarks-table">
			<tr><?php 
			foreach($arResult["BOOKMARKS"] as $name => $arBookmark)
			{
				?><td class="bookmarks"><?=$arBookmark["ICON"]?></td><?php 
			}
			?></tr>		
			</table><?php 
		}
		?></div>		
	</div>
	</div>
	<a class="share-switch" href="#" onClick="return ShowShareDialog(<?php echo $arResult["COUNTER"]?>);" title="<?=GetMessage("SHARE_SWITCH")?>"></a><?php 
}
else
{
	?><?=GetMessage("SHARE_ERROR_EMPTY_SERVER")?><?php 
}
?>