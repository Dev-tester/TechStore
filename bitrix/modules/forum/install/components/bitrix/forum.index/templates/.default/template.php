<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!$this->__component->__parent || empty($this->__component->__parent->__name)):
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/themes/blue/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/styles/additional.css');
endif;
IncludeAJAX();
/********************************************************************
				Input params
********************************************************************/
/***************** BASE ********************************************/
$arParams["WORD_WRAP_CUT"] = intVal($arParams["WORD_WRAP_CUT"]);
$arParams["SHOW_RSS"] = ($arParams["SHOW_RSS"] == "N" ? "N" : "Y");
$arParams["SHOW_RSS"] = ($arParams["SHOW_RSS"] == "Y" && !empty($arResult["FORUMS_FOR_GUEST"]) ? "Y" : "N");
if ($arParams["SHOW_RSS"] == "Y"):
	$APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" href="'.$arResult["URL"]["RSS_DEFAULT"].'" />');
endif;
$arResult["USER"]["HIDDEN_GROUPS"] = explode("/", $_COOKIE[COption::GetOptionString("main", "cookie_name", "BITRIX_SM")."_FORUM_GROUP"]);
$arParams["TMPLT_SHOW_ADDITIONAL_MARKER"] = trim($arParams["TMPLT_SHOW_ADDITIONAL_MARKER"]);
/********************************************************************
				/Input params
********************************************************************/
if (!empty($arResult["NAV_STRING"]) && $arResult["NAV_RESULT"]->NavPageCount > 1):
?>
<div class="forum-navigation-box forum-navigation-top">
	<div class="forum-page-navigation">
		<?=$arResult["NAV_STRING"]?>
	</div>
	<div class="forum-clear-float"></div>
</div>
<?php 
endif;
?>
<div class="forum-header-box">
<?php 
if (!empty($arParams["GID"])):
?>
	<div class="forum-header-options">
		<span class="forum-option-feed"><a href="<?=$arResult["URL"]["INDEX"]?>"><?=GetMessage("F_FORUMS")?></a></span>
	</div>
	<div class="forum-header-title"><span><?=$arResult["GROUP"]["NAME"]?></span></div>
<?php 
else:
?>
	<div class="forum-header-title"><span><?=GetMessage("F_FORUMS")?></span></div>
<?php 
endif;
?>
</div>

<div class="forum-block-container">
	<div class="forum-block-outer">
		<div class="forum-block-inner">
			<table cellspacing="0" class="forum-table forum-forum-list<?=(!empty($arResult["NAV_STRING"]) && $arResult["NAV_RESULT"]->NavPageCount > 1 ? 
				"forum-forum-list-part" : "")?>">
<?php 
if (!empty($arResult["FORUMS"]["FORUMS"]) || ($arResult["GROUP"]["ID"] > 0 && !empty($arResult["FORUMS"]["GROUPS"][$arResult["GROUP"]["ID"]]["FORUMS"]))):
?>
			<thead>
				<tr>
					<th class="forum-column-title" colspan="2"><div class="forum-head-title"><span><?=GetMessage("F_FORUM_NAME")?></span></div></th>
					<th class="forum-column-topics"><span><?=GetMessage("F_TOPICS")?></span></th>
					<th class="forum-column-replies"><span><?=GetMessage("F_POSTS")?></span></th>
					<th class="forum-column-lastpost"><span><?=GetMessage("F_LAST_POST")?></span></th>
				</tr>
			</thead>
<?php 
endif;
if (!function_exists("__PrintForumGroupsAndForums"))
{
	function __PrintForumGroupsAndForums($arRes, $arResult, $arParams, $depth = -1)
	{
		static $bInsertSeparator = false;
		
		$arGroup = $arRes;
		if (!is_array($arRes))
			return false;
		

		if (intVal($arGroup["ID"]) > 0 && $arGroup["ID"] != $arResult["GROUP"]["ID"])
		{
			if ($bInsertSeparator):
?>
		<tbody class="forum-category-separator">
			<tr>
				<td class="forum-category-separator" colspan="5"></td>
			</tr>
		</tbody>
<?php 
			endif;
?>
		<thead>
			<tr>
				<th class="forum-column-title" colspan="2" scope="col"><div class="forum-head-title"><span><?php 
					?><noindex><a rel="nofollow" href="<?=$arResult["URL"]["GROUP_".$arGroup["ID"]]?>"><?php 
					?><?=$arGroup["NAME"]?></a></noindex></span></div></th>
				<th class="forum-column-topics" scope="col"><span><?=GetMessage("F_TOPICS")?></span></th>
				<th class="forum-column-replies" scope="col"><span><?=GetMessage("F_POSTS")?></span></th>
				<th class="forum-column-lastpost" scope="col"><span><?=GetMessage("F_LAST_POST")?></span></th>
			</tr>
		</thead>
<?php 
			$bInsertSeparator = true;
		}
		$iCountRows = 1;
		if (array_key_exists("FORUMS", $arRes))
		{
?>
			<tbody>
<?php 
			foreach ($arGroup["FORUMS"] as $res)
			{
				
				if ($arParams["WORD_WRAP_CUT"] > 0):
					$res["TITLE"] = (strLen($res["~TITLE"]) > $arParams["WORD_WRAP_CUT"] ? 
						htmlspecialcharsbx(substr($res["~TITLE"], 0, $arParams["WORD_WRAP_CUT"]))."..." : $res["TITLE"]);
					$res["LAST_POSTER_NAME"] = (strLen($res["~LAST_POSTER_NAME"]) > $arParams["WORD_WRAP_CUT"] ? 
						htmlspecialcharsbx(substr($res["~LAST_POSTER_NAME"], 0, $arParams["WORD_WRAP_CUT"]))."..." : $res["LAST_POSTER_NAME"]);
				endif;
?>
			<tr class="<?=($iCountRows == 1 ? "forum-row-first " : "")?><?php 
				?><?=($iCountRows == count($arGroup["FORUMS"]) ? "forum-row-last " : "")
				?><?=($iCountRows%2 == 1 ? "forum-row-odd " : "forum-row-even ")?><?=($res["ACTIVE"] != "Y" ? " forum-row-inactive" : "")?>" <?php 
				if ($res["ACTIVE"] != "Y"):
					?> title="<?=GetMessage("F_NOT_ACTIVE_FORUM")?>" <?php 
				endif;
				?>>
					<td class="forum-column-icon">
						<div class="forum-icon-container">
<?php 
				if ($res["NewMessage"] == "Y")
				{
?>
							<div class="forum-icon forum-icon-newposts" title="<?=GetMessage("F_HAVE_NEW_MESS")?>"><!-- ie --></div>
<?php 
				}
				else
				{
?>
							<div class="forum-icon forum-icon-default" title="<?=GetMessage("F_NO_NEW_MESS")?>"><!-- ie --></div>
<?php 
				}
?>
						</div>
					</td>
					<td class="forum-column-title">
						<div class="forum-item-info">
							<div class="forum-item-name"><span class="forum-item-title"><a href="<?=$res["URL"]["TOPICS"]?>"><?php 
								?><?=$res["~NAME"];?></a><?php 
				if ($res["NewMessage"] == "Y" && strLen($arParams["TMPLT_SHOW_ADDITIONAL_MARKER"]) > 0):
								?><noindex><a rel="nofollow" href="<?=$res["URL"]["TOPICS"]?>" class="forum-new-message-marker"><?=$arParams["TMPLT_SHOW_ADDITIONAL_MARKER"]?></a></noindex><?php 
				endif;
								?></span></div>
							<span class="forum-item-desc"><?=$res["~DESCRIPTION"]?></span>
<?php 
				if ($res["PERMISSION"] >= "Q" && ($res["MODERATE"]["TOPICS"] > 0 || $res["MODERATE"]["POSTS"] > 0)):
?>
							<div class="forum-moderator-stat"><?=GetMessage("F_NOT_APPROVED")?>&nbsp;<?php 
							if ($res["MODERATE"]["TOPICS"] > 0):
								?><?=GetMessage("F_NOT_APPROVED_TOPICS")?>:&nbsp;<span><?=$res["MODERATE"]["TOPICS"]?></span><?=($res["MODERATE"]["POSTS"] > 0 ? ", " : "")?><?php 
							endif;
							if ($res["MODERATE"]["POSTS"] > 0):
								?><?=GetMessage("F_NOT_APPROVED_POSTS")?>:&nbsp;<span><?php 
									?><noindex><a rel="nofollow" href="<?=$res["URL"]["MODERATE_MESSAGE"]?>"><?=$res["MODERATE"]["POSTS"]?></a></noindex></span><?php 
							endif;
				endif;
?>
						</div>
					</td>
					<td class="forum-column-topics"><span><?=$res["TOPICS"]?></span></td>
					<td class="forum-column-replies"><span><?=$res["POSTS"]?></span></td>
					<td class="forum-column-lastpost">
<?php 
					if (intVal($res["LAST_MESSAGE_ID"]) > 0):
?>
						<div class="forum-lastpost-box">
							<span class="forum-lastpost-title"><?php 
								?><noindex><a rel="nofollow" href="<?=$res["URL"]["MESSAGE"]?>" title="<?=htmlspecialcharsbx($res["~TITLE"]." (".$res["~LAST_POSTER_NAME"].")")?>"><?php 
									?><?=$res["TITLE"]?> <span class="forum-lastpost-author">(<?=$res["LAST_POSTER_NAME"]?>)</span></a></noindex></span>
							<span class="forum-lastpost-date"><?=$res["LAST_POST_DATE"]?></span>
						</div>
<?php 
					else:
?>
						&nbsp;
<?php 
					endif;
?>
					</td>
				</tr>
<?php 
				$iCountRows++;
			}
?>
			</tbody>
<?php 
		}
		
		$iCountRows = 0;
		if (array_key_exists("GROUPS", $arRes)):
			if ($depth >= 1)
			{
?>
			<tbody>
<?php 
				foreach ($arRes["GROUPS"] as $key => $res)
				{
					$iCountRows++;
					
?>				<tr class="<?=($iCountRows == 1 ? "forum-row-first " : "")?><?php 
					?><?=($iCountRows == $iCountRows ? "forum-row-last " : "")
						?><?=($iCountRows%2 == 1 ? "forum-row-odd " : "forum-row-even ")?>" >
						<td class="forum-column-icon">
						<div class="forum-icon-container">
<?php 
				if ($res["NewMessage"] == "Y")
				{
?>
							<div class="forum-icon forum-icon-newposts" title="<?=GetMessage("F_HAVE_NEW_MESS")?>"><!-- ie --></div>
<?php 
				}
				else
				{
?>
							<div class="forum-icon forum-icon-default" title="<?=GetMessage("F_NO_NEW_MESS")?>"><!-- ie --></div>
<?php 
				}
?>
						</div>
					
					</td>
					<td class="forum-column-title">
						<div class="forum-item-info">
							<div class="forum-item-name"><span class="forum-item-title"><?php 
							?><noindex><a rel="nofollow" href="<?=$arResult["URL"]["GROUP_".$res["ID"]]?>"><?php 
								?><?=$res["~NAME"];?></a></noindex></span></div>
							<span class="forum-item-desc"><?php 
				
				if (array_key_exists("FORUMS", $res)):
					?><?=GetMessage("F_SUBFORUMS")?> <?php 
					$bFirst = true;
					foreach ($res["FORUMS"] as $val):
						if (!$bFirst):
							?>, <?php 
						endif;
						?><a href="<?=$val["URL"]["TOPICS"]?>"><?=$val["~NAME"]?></a><?php 
						$bFirst = false;
					endforeach;
				else:
					?><?=GetMessage("F_SUBGROUPS")?> <?php 
					$bFirst = true;
					foreach ($res["GROUPS"] as $val):
						if (!$bFirst):
							?>, <?php 
						endif;
						?><noindex><a rel="nofollow" href="<?=$arResult["URL"]["GROUP_".$val["ID"]]?>"><?=$val["~NAME"]?></a></noindex><?php 
						$bFirst = false;
					endforeach;
				endif;
					
							?></span>
							
<?php 
				if ($res["MODERATE"]["TOPICS"] > 0 || $res["MODERATE"]["POSTS"] > 0):
?>
							<div class="forum-moderator-stat"><?=GetMessage("F_NOT_APPROVED")?>&nbsp;<?php 
							if ($res["MODERATE"]["TOPICS"] > 0):
								?><?=GetMessage("F_NOT_APPROVED_TOPICS")?>:&nbsp;<span><?=$res["MODERATE"]["TOPICS"]?></span><?php 
									?><?=($res["MODERATE"]["POSTS"] > 0 ? ", " : "")?><?php 
							endif;
							if ($res["MODERATE"]["POSTS"] > 0):
								?><?=GetMessage("F_NOT_APPROVED_POSTS")?>:&nbsp;<span><?php 
									?><noindex><a rel="nofollow" href="<?=$arResult["URL"]["GROUP_".$res["ID"]]?>"><?php 
									?><?=$res["MODERATE"]["POSTS"]?></a></noindex></span><?php 
							endif;
				endif;
?>
						</div>
					</td>
					<td class="forum-column-topics"><span><?=$res["TOPICS"]?></span></td>
					<td class="forum-column-replies"><span><?=$res["POSTS"]?></span></td>
					<td class="forum-column-lastpost">
<?php 
					if (intVal($res["LAST_MESSAGE_ID"]) > 0):
?>
						<div class="forum-lastpost-box">
							<span class="forum-lastpost-title"><?php 
								?><noindex><a rel="nofollow" href="<?=$res["URL"]["MESSAGE"]?>" title="<?=htmlspecialcharsbx($res["~TITLE"]." (".$res["~LAST_POSTER_NAME"].")")?>"><?php 
									?><?=$res["TITLE"]?> <span class="forum-lastpost-author">(<?=$res["LAST_POSTER_NAME"]?>)</span></a></noindex></span>
							<span class="forum-lastpost-date"><?=$res["LAST_POST_DATE"]?></span>
						</div>
<?php 
					else:
?>
						&nbsp;
<?php 
					endif;
?>
					</td>
				</tr>
<?php 
				}
?>
			</tbody>
<?php 
			}
			else 
			{
				$depth++;
				foreach ($arRes["GROUPS"] as $key => $val)
				{
					__PrintForumGroupsAndForums($arRes["GROUPS"][$key], $arResult, $arParams, $depth);
				}
			}
		endif;
	}
}
if (!empty($arResult["FORUMS"])):
	if ($arResult["GROUP"]["ID"] > 0):
		__PrintForumGroupsAndForums($arResult["FORUMS"]["GROUPS"][$arResult["GROUP"]["ID"]], $arResult, $arParams, 0);
	else:
		__PrintForumGroupsAndForums($arResult["FORUMS"], $arResult, $arParams, 0);
	endif;
else:
?>
			<tbody>
				<tr class="forum-row-first forum-row-odd">
					<td class="forum-column-alone">
						<div class="forum-empty-message"><?=GetMessage("F_EMPTY_FORUMS")?></div>
					</td>
				</tr>
			</tbody>
<?php 
endif;
?>
			<tfoot>
				<tr>
					<td colspan="5" class="forum-column-footer">
						<div class="forum-footer-inner">
<?php 
		if ($arParams["SHOW_RSS"] == "Y"):
?>
							<span class="forum-footer-option forum-footer-rss forum-footer-option-first"><noindex><?php 
								?><a rel="nofollow" href="<?=$arResult["URL"]["RSS_DEFAULT"]?>" onclick="window.location='<?=addslashes(htmlspecialcharsbx($arResult["URL"]["~RSS"]))?>'; return false;"><?php 
									?><?=GetMessage("F_SUBSCRIBE_TO_NEW_TOPICS")?><?php 
									?></a></noindex></span>
<?php 		
		endif;
		if ($USER->IsAuthorized()):
?>
							<span class="forum-footer-option forum-footer-markread<?=($arParams["SHOW_RSS"] == "Y" ? "" : " forum-footer-option-first")?>"><?php 
								?><noindex><a rel="nofollow" <?php 
									?>href="<?=$APPLICATION->GetCurPageParam("ACTION=SET_BE_READ", array("ACTION", "sessid"))?>" <?php 
									?>onclick="return this.href+=('&sessid='+BX.bitrix_sessid());";><?php 
									?><?=GetMessage("F_SET_FORUMS_READ")?></a></noindex></span>
<?php 		
		elseif ($arParams["SHOW_RSS"] != "Y"):
?>
							&nbsp;
<?php 		
		endif;
		
?>
						</div>
					</td>
				</tr>
			</tfoot>
			</table>
		</div>
	</div>
</div>
<?php 

if (!empty($arResult["NAV_STRING"]) && $arResult["NAV_RESULT"]->NavPageCount > 1):
?>
<div class="forum-navigation-box forum-navigation-bottom">
	<div class="forum-page-navigation">
		<?=$arResult["NAV_STRING"]?>
	</div>
	<div class="forum-clear-float"></div>
</div>
<?php 
endif;
?>
