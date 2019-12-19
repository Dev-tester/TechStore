<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?php 
/**
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @var array $arParams
 * @var array $arResult
 */
if (empty($arResult["TOPIC"]))
	return 0;

// ************************* Input params***************************************************************
$arParams["SHOW_NAV"] = (is_array($arParams["SHOW_NAV"]) ? $arParams["SHOW_NAV"] : array());
$arParams["SHOW_COLUMNS"] = (is_array($arParams["SHOW_COLUMNS"]) ? $arParams["SHOW_COLUMNS"] : array());
$arParams["SHOW_SORTING"] = ($arParams["SHOW_SORTING"] == "Y" ? "Y" : "N");
$arParams["SEPARATE"] = (empty($arParams["SEPARATE"]) ? GetMessage("FTP_IN_FORUM") : $arParams["SEPARATE"]);
// *************************/Input params***************************************************************

?>
<div class="forum-topic-last">
<?php 

if (in_array("TOP", $arParams["SHOW_NAV"])):
?><div class="forum-navigation"><?=$arResult["NAV_STRING"]?></div><?php 
endif;
?>
<div class="forum-topic-last-table">
<table class="data-table">
<?php 
	if ($arParams["SHOW_SORTING"] == "Y")
	{
?>
	<tr>
		<th><div><?=GetMessage("FTP_SORTING_TITLE")?></div><span><?=$arResult["SortingEx"]["TITLE"]?></span></th>
<?php 
		
		if (in_array("USER_START_NAME", $arParams["SHOW_COLUMNS"])):
		?><th><div><?=GetMessage("FTP_SORTING_USER_START_NAME")?></div><span><?=$arResult["SortingEx"]["USER_START_NAME"]?></span></th><?php 
		endif;
		
		if (in_array("POSTS", $arParams["SHOW_COLUMNS"])):
		?><th><div><?=GetMessage("FTP_SORTING_POSTS")?></div><span><?=$arResult["SortingEx"]["POSTS"]?></span></th><?php 
		endif;
		
		if (in_array("VIEWS", $arParams["SHOW_COLUMNS"])):
		?><th><div><?=GetMessage("FTP_SORTING_VIEWS")?></div><span><?=$arResult["SortingEx"]["VIEWS"]?></span></th><?php 
		endif;
		
		if (in_array("LAST_POST_DATE", $arParams["SHOW_COLUMNS"])):
		?><th><div><?=GetMessage("FTP_SORTING_LAST_POST_DATE")?></div><span><?=$arResult["SortingEx"]["LAST_POST_DATE"]?></span></th><?php 
		endif;
?>
	</tr>
<?php 
	}
	
	foreach ($arResult["TOPIC"] as $res)
	{
?>
	<tr>
		<td><a href="<?=$res["read"]?>" class="forum-title<?=($res["SORT"] == 100 ? " pinned" : "")?>"><?=$res["TITLE"]?></a>  <?php 
			?><?=str_replace("#FORUM#", 
				"<a href =\"".$arResult["FORUM"][$res["FORUM_ID"]]["URL"]["LIST"]."\">".$arResult["FORUM"][$res["FORUM_ID"]]["NAME"]."</a>", 
				$arParams["SEPARATE"])?>
			<?php if(isset($res['MESSAGE'])):?>
				<div class="forum-topic-last-message">
					<?=$res['MESSAGE']['POST_MESSAGE_TEXT']?>
					<span class="forum-topic-last-author">
					</span>
					( <?=$res['MESSAGE']['AUTHOR_NAME']?> @ <?=$res['MESSAGE']['POST_DATE']?> )
				</div>
			<?php endif?>
		</td>
<?php 
		
		if (in_array("USER_START_NAME", $arParams["SHOW_COLUMNS"])):
?>
		<td class="user_start_name">
<?php 
			if (intVal($res["USER_START_ID"]) > 0 ):
				?><a href="<?=$res["user_start_id_profile"]?>" class="forum-user"><?=$res["USER_START_NAME"]?></a><?php 
			else:
				?><?=$res["USER_START_NAME"]?><?php 
			endif;
?>
		</td>
<?php 
		endif;
		
		if (in_array("POSTS", $arParams["SHOW_COLUMNS"])):
?>
		<td class="posts"><?=$res["POSTS"]?></td>
<?php 
		endif;
		
		if (in_array("VIEWS", $arParams["SHOW_COLUMNS"])):
?>
			<td class="views"><?=$res["VIEWS"]?></td>
<?php 
		endif;
		
		if (in_array("LAST_POST_DATE", $arParams["SHOW_COLUMNS"])):
?>
		<td class="last_post_date">
			<a href="<?=$res["read"]?>" title="<?=GetMessage("FTP_LAST_MESS")?>"><?=$res["LAST_POST_DATE"]?></a>
		</td>
<?php 
		endif;
?>
	</tr>
<?php 
	}
?>
</table></div>
<?php 

if (in_array("BOTTOM", $arParams["SHOW_NAV"])):
?><div class="forum-navigation"><?=$arResult["NAV_STRING"]?></div><?php 
endif;

?></div>
