<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if ($arResult["PAGE_URL"])
{
	?>
	<ul class="bx-share-social">
		<?php 
		if (is_array($arResult["BOOKMARKS"]) && count($arResult["BOOKMARKS"]) > 0)
		{
			foreach(array_reverse($arResult["BOOKMARKS"]) as $name => $arBookmark)
			{
				?><li class="bx-share-icon"><?=$arBookmark["ICON"]?></li><?php 
			}
		}
		?>
	</ul>
	<?php 
}
else
{
	?><?=GetMessage("SHARE_ERROR_EMPTY_SERVER")?><?php 
}
?>