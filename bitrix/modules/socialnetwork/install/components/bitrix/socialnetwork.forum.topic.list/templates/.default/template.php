<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?php 
if (!$this->__component->__parent || empty($this->__component->__parent->__name)):
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/themes/blue/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/styles/additional.css');
endif;
IncludeAJAX();
$GLOBALS["APPLICATION"]->AddHeadScript("/bitrix/js/main/utils.js");
$GLOBALS['APPLICATION']->AddHeadString('<script src="/bitrix/components/bitrix/forum.interface/templates/.default/script.js"></script>', true);
$GLOBALS['APPLICATION']->AddHeadString('<script src="/bitrix/components/bitrix/forum.interface/templates/popup/script.js"></script>', true);

/********************************************************************
				Input params
********************************************************************/
/***************** BASE ********************************************/
$arParams["PATH_TO_ICON"] = (empty($arParams["PATH_TO_ICON"]) ? $templateFolder."/images/icon/" : $arParams["PATH_TO_ICON"]);
$arParams["PATH_TO_ICON"] = str_replace("//", "/", $arParams["PATH_TO_ICON"]."/");
$arParams["SHOW_AUTHOR_COLUMN"] = ($arParams["SHOW_AUTHOR_COLUMN"] == "Y" ? "Y" : "N");
/*$arParams["SHOW_RSS"] = ($arParams["SHOW_RSS"] == "N" ? "N" : "Y");
if ($arParams["SHOW_RSS"] == "Y"):
	$arParams["SHOW_RSS"] = (!$USER->IsAuthorized() ? "Y" : (CForumNew::GetUserPermission($arParams["FID"], array(2)) > "A" ? "Y" : "N"));
	if ($arParams["SHOW_RSS"] == "Y"):
		$APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" href="'.$arResult["URL"]["RSS"].'" />');
	endif;
endif;
*/
$arParams["TMPLT_SHOW_ADDITIONAL_MARKER"] = trim($arParams["TMPLT_SHOW_ADDITIONAL_MARKER"]);
$iIndex = rand();
/********************************************************************
				/Input params
********************************************************************/
if (!empty($arResult["ERROR_MESSAGE"])): 
?>
<div class="forum-note-box forum-note-error">
	<div class="forum-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"], "forum-note-error");?></div>
</div>
<?php 
endif;
if (!empty($arResult["OK_MESSAGE"])): 
?>
<div class="forum-note-box forum-note-success">
	<div class="forum-note-box-text"><?=ShowNote($arResult["OK_MESSAGE"], "forum-note-success")?></div>
</div>
<?php 
endif;

?>
<div class="forum-navigation-box forum-navigation-top">
	<div class="forum-page-navigation">
		<?=$arResult["NAV_STRING"]?>
	</div>
<?php 
if ($arResult["CanUserAddTopic"] == true):
?>
	<div class="forum-new-post">
		<a href="<?=$arResult["URL"]["TOPIC_NEW"]?>" title="<?=GetMessage("F_NEW_TOPIC_TITLE")?>"><span><?=GetMessage("F_NEW_TOPIC")?></span></a>
	</div>
<?php 
endif;
?>
	<div class="forum-clear-float"></div>
</div>

<div class="forum-header-box">
	<div class="forum-header-options"><?php 

if($arResult["EMAIL_INTEGRATION"]):
?>
	<span class="forum-option-subscribe"><noindex>
	<?php if($arResult["USER"]["SUBSCRIBE"]=="Y"):?>
		<a rel="nofollow" title="<?=GetMessage("F_UNSUBSCRIBE_TO_NEW_POSTS")?>" href="<?=$APPLICATION->GetCurPageParam("ACTION=FORUM_UNSUBSCRIBE&".bitrix_sessid_get(), array("ACTION", "sessid"))?>"><?=GetMessage("F_UNSUBSCRIBE")?></a>
	<?php else:?>
		<a rel="nofollow" title="<?=GetMessage("F_SUBSCRIBE_TO_NEW_POSTS")?>" href="<?=$APPLICATION->GetCurPageParam("ACTION=FORUM_SUBSCRIBE&".bitrix_sessid_get(), array("ACTION", "sessid"))?>"><?=GetMessage("F_SUBSCRIBE")?></a>
	<?php endif?>
	</noindex></span>
<?php 
endif;
?>
	</div>
	<div class="forum-header-title"><span><?=$arResult["FORUM"]["NAME"]?></span></div>
</div>
<?php 

if ($arParams["PERMISSION"] >= "Q"):
?>
<form class="forum-form" action="<?=POST_FORM_ACTION_URI?>" method="POST" onsubmit="return Validate(this)" name="TOPICS_<?=$iIndex?>" id="TOPICS_<?=$iIndex?>">
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="PAGE_NAME" value="topic_list" />
	<input type="hidden" name="FID" value="<?=$arParams["FID"]?>" />
	<input type="hidden" name="topic_edit" value="Y" />
<?php 
endif;

?>
<div class="forum-block-container">
	<div class="forum-block-outer">
		<div class="forum-block-inner">
			<table cellspacing="0" class="forum-table forum-topic-list">

<?php 
if (empty($arResult["TOPICS"])):
?>
			<tbody>
				<tr class="forum-row-first forum-row-last forum-row-odd">
					<td class="forum-column-alone">
						<div class="forum-empty-message"><?=GetMessage("F_NO_TOPICS_HERE")?><br />
<?php 
if ($arResult["CanUserAddTopic"] == true):
?>
						<?=str_replace("#HREF#", $arResult["URL"]["TOPIC_NEW"], GetMessage("F_CREATE_NEW_TOPIC"))?></div>
<?php 
endif;
?>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td class="forum-column-footer">
						<div class="forum-footer-inner">&nbsp;</div>
					</td>
				</tr>
			</tfoot> 
<?php 
else:
?>
			<thead>
				<tr>
					<th class="forum-column-title" colspan="2"><div class="forum-head-title"><span><?=GetMessage("F_HEAD_TOPICS")?></span></div></th>
<?php 
if ($arParams["SHOW_AUTHOR_COLUMN"] == "Y"):
?>
	<th class="forum-column-replies"><span><?=GetMessage("F_HEAD_AUTHOR")?></span></th>
<?php 
endif;
?>
					<th class="forum-column-replies"><span><?=GetMessage("F_HEAD_POSTS")?></span></th>
					<th class="forum-column-views"><span><?=GetMessage("F_HEAD_VIEWS")?></span></th>
					<th class="forum-column-lastpost"><span><?=GetMessage("F_HEAD_LAST_POST")?></span></th>
				</tr>
			</thead>
			<tbody>

<?php 
$iCount = 0;
foreach ($arResult["TOPICS"] as $res):
	$iCount++;
?>
				<tr class="<?=($iCount == 1 ? "forum-row-first " : "")?><?php 
					?><?=($iCount == count($arResult["TOPICS"]) ? "forum-row-last " : "")?><?php 
					?><?=($iCount%2 == 1 ? "forum-row-odd " : "forum-row-even ")?><?php 
					?><?=(intVal($res["SORT"]) != 150 ? "forum-row-sticky " : "")?><?php 
					?><?=($res["STATE"] != "Y" && $res["STATE"] != "L" ? "forum-row-closed " : "")?><?php 
					?><?=($res["TopicStatus"] == "MOVED" ? "forum-row-moved " : "")?><?php 
					?><?=($res["APPROVED"] != "Y" ? " forum-row-hidden ": "")?><?php 
					?>">
					<td class="forum-column-icon">
						<div class="forum-icon-container">
							<div class="forum-icon <?php 
							$title = ""; $class = "";
							if (intVal($res["SORT"]) != 150):
								$title = GetMessage("F_PINNED_TOPIC");
								if ($res["TopicStatus"] == "NEW"):
									$title .= " (".GetMessage("F_HAVE_NEW_MESS").")";
									?> forum-icon-sticky-newposts <?php 
								else:
									?> forum-icon-sticky <?php 
								endif;
							elseif ($res["TopicStatus"] == "MOVED"):
								$title = GetMessage("F_MOVED_TOPIC");
								?> forum-icon-moved <?php 
							elseif ($res["STATE"] != "Y" && $res["STATE"] != "L"):
								$title = (intVal($res["SORT"]) != 150 ? GetMessage("F_PINNED_CLOSED_TOPIC") : GetMessage("F_CLOSED_TOPIC"));
								if ($res["TopicStatus"] == "NEW"):
									$title .= " (".GetMessage("F_HAVE_NEW_MESS").")";
									?> forum-icon-closed-newposts <?php 
								else:
									?> forum-icon-closed <?php 
								endif;
							elseif ($res["TopicStatus"] == "NEW"):
								$title .= (empty($title) ? GetMessage("F_HAVE_NEW_MESS") : " (".GetMessage("F_HAVE_NEW_MESS").")");
								?> forum-icon-newposts <?php 
							else:
								$title .= (empty($title) ? GetMessage("F_NO_NEW_MESS") : "");
								?> forum-icon-default <?php 
							endif;
							
							?>" title="<?=$title?>"><!-- ie --></div>
						</div>
					</td>
					<td class="forum-column-title">
						<div class="forum-item-info">
							<div class="forum-item-name"><?php 
						if (intVal($res["SORT"]) != 150 && $res["STATE"]!="Y"):
								?><span class="forum-status-sticky"><?=GetMessage("F_PINNED")?></span>, <?php 
							if ($res["STATE"] != "L"):
								?><span class="forum-status-closed"><?=GetMessage("F_CLOSED")?></span>:&nbsp;<?php 
							else:
								?><span class="forum-status-moved"><?=GetMessage("F_MOVED")?></span>:&nbsp;<?php 
							endif;
						elseif ($res["TopicStatus"] == "MOVED" || $res["STATE"]=="L"):
								?><span class="forum-status-moved"><?=GetMessage("F_MOVED")?></span>:&nbsp;<?php 
						elseif (intVal($res["SORT"]) != 150):
								?><span class="forum-status-sticky"><?=GetMessage("F_PINNED")?></span>:&nbsp;<?php 
						elseif (($res["STATE"]!="Y") && ($res["STATE"]!="L")):
								?><span class="forum-status-closed"><?=GetMessage("F_CLOSED")?></span>:&nbsp;<?php 
						endif;
								?><span class="forum-item-title"><?php 
						if (false && strLen($res["IMAGE"]) > 0):
								?><img src="<?=$arParams["PATH_TO_ICON"].$res["IMAGE"];?>" alt="<?=$res["IMAGE_DESCR"];?>" border="0" width="15" height="15"/><?php 
						endif;
								?><a href="<?=$res["URL"]["TOPIC"]?>" title="<?=GetMessage("F_TOPIC_START")?> <?=$res["START_DATE"]?>"><?=$res["TITLE"]?></a><?php 
						if ($res["TopicStatus"] == "NEW" && strLen($arParams["TMPLT_SHOW_ADDITIONAL_MARKER"]) > 0):
								?><a href="<?=$res["URL"]["MESSAGE_UNREAD"]?>" class="forum-new-message-marker"><?=$arParams["TMPLT_SHOW_ADDITIONAL_MARKER"]?></a><?php 
						endif;
								?></span><?php 
						if ($res["PAGES_COUNT"] > 1):
								?> <span class="forum-item-pages">(<?php 
							$iCountPages = intVal($res["PAGES_COUNT"] > 5 ? 3 : $res["PAGES_COUNT"]);
							for ($ii = 1; $ii <= $iCountPages; $ii++):
								?><a href="<?=ForumAddPageParams($res["URL"]["~TOPIC"], array("PAGEN_".$arParams["PAGEN"] => $ii))?>"><?php 
									?><?=$ii?></a><?=($ii < $iCountPages ? ",&nbsp;" : "")?><?php 
							endfor;
							if ($iCountPages < $res["PAGES_COUNT"]):
								?>&nbsp;...&nbsp;<a href="<?=ForumAddPageParams($res["URL"]["~TOPIC"], 
									array("PAGEN_".$arParams["PAGEN"] => $res["PAGES_COUNT"]))?>"><?=$res["PAGES_COUNT"]?></a><?php 
							endif;
								?>)</span><?php 
						endif;
							?></div>
<?php 
						if (!empty($res["DESCRIPTION"])):
?>
							<span class="forum-item-desc"><?=$res["DESCRIPTION"]?></span><span class="forum-item-desc-sep"><?php 
							?><?=($arParams["SHOW_AUTHOR_COLUMN"] != "Y" ? "&nbsp;&middot; " : "")?></span>
<?php 
						endif;
						if ($arParams["SHOW_AUTHOR_COLUMN"] != "Y"):
?>
							<span class="forum-item-author"><span><?=GetMessage("F_AUTHOR")?></span>&nbsp;<?=$res["USER_START_NAME"]?></span>
<?php 
						endif;
?>
						</div>
					</td>
<?php 
						if ($arParams["SHOW_AUTHOR_COLUMN"] == "Y"):
?>
					<td class="forum-column-author"><span><?php 
							if ($res["USER_START_ID"] > 0):
						?><a href="<?=$res["URL"]["USER_START"]?>"><?=$res["USER_START_NAME"]?></a><?php 
							else:
						?><?=$res["USER_START_NAME"]?><?php 
							endif;
					?></span></td>
<?php 
						endif;

						if ($arParams["PERMISSION"] >= "Q" && $res["mCnt"] > 0):
?>
					<td class="forum-column-replies forum-cell-hidden"><span><?=$res["POSTS"]?> <?php 
						?>(<a href="<?=$res["URL"]["TOPIC"]?>" title="<?=GetMessage("F_MESSAGE_NOT_APPROVED")?>"><?=$res["mCnt"]?></a>)</span></td>
<?php 
						else:
?>
					<td class="forum-column-replies"><span><?=$res["POSTS"]?></span></td>
<?php 
						endif;
?>
					<td class="forum-column-views"><span><?=$res["VIEWS"]?></span></td>
					<td class="forum-column-lastpost"><?php 
						if ($arParams["PERMISSION"] >= "Q"):
?>
						<div class="forum-select-box"><input type="checkbox" name="TID[]" value="<?=$res["ID"]?>" onclick="SelectRow(this.parentNode.parentNode.parentNode)" /></div>
<?php 
						endif;
						if ($res["LAST_MESSAGE_ID"] > 0):
?>
						<div class="forum-lastpost-box">
							<span class="forum-lastpost-date"><a href="<?=$res["URL"]["LAST_MESSAGE"]?>"><?=$res["LAST_POST_DATE"]?></a></span>
							<span class="forum-lastpost-title"><span class="forum-lastpost-author"><?=$res["LAST_POSTER_NAME"]?></span></span>
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
		endforeach;
?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="<?=($arParams["SHOW_AUTHOR_COLUMN"] == "Y" ? "6" : "5")?>" class="forum-column-footer">
						<div class="forum-footer-inner">
<?php 
						if ($arParams["PERMISSION"] >= "Q"):
?>
							<div class="forum-topics-moderate">
								<select name="ACTION">
									<option value=""><?=GetMessage("F_MANAGE_TOPICS")?></option>
									<option value="SET_TOP"><?=GetMessage("F_MANAGE_PIN")?></option>
									<option value="SET_ORDINARY"><?=GetMessage("F_MANAGE_UNPIN")?></option>
									<option value="STATE_Y"><?=GetMessage("F_MANAGE_OPEN")?></option>
									<option value="STATE_N"><?=GetMessage("F_MANAGE_CLOSE")?></option>
<?php 
						if ($arParams["PERMISSION"] >= "U"):
?>
									<option value="DEL_TOPIC"><?=GetMessage("F_MANAGE_DELETE")?></option>
<?php 
						endif;
?>
								</select>&nbsp;<input type="submit" value="OK" />
							</div>
							<span class="forum-footer-option forum-footer-selectall forum-footer-option-first"><?php 
								?><a href="javascript:void(0);" onclick="SelectRows('<?=$iIndex?>');" name=""><?=GetMessage("F_SELECT_ALL")?></a></span>
<?php 
						else:
?>
							&nbsp;
<?php 
						endif;
						
?>
						</div>
					</td>
				</tr>
			</tfoot>
<?php 
endif;
?>
			</table>
		</div>
	</div>
</div>
<?php 
if ($arParams["PERMISSION"] >= "Q"):
?>
</form>
<?php 
endif;
?>
<div class="forum-navigation-box forum-navigation-bottom">
	<div class="forum-page-navigation">
		<?=$arResult["NAV_STRING"]?>
	</div>
<?php 
if ($arResult["USER"]["RIGHTS"]["CAN_ADD_TOPIC"] == "Y"):
?>
	<div class="forum-new-post">
		<a href="<?=$arResult["URL"]["TOPIC_NEW"]?>" title="<?=GetMessage("F_NEW_TOPIC_TITLE")?>"><span><?=GetMessage("F_NEW_TOPIC")?></span></a>
	</div>
<?php 
endif;
?>
	<div class="forum-clear-float"></div>
</div>
<?php 

if (!empty($arResult["ERROR_MESSAGE"])): 
?>
<div class="forum-note-box forum-note-error">
	<div class="forum-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"], "forum-note-error");?></div>
</div>
<?php 
endif;
if (!empty($arResult["OK_MESSAGE"])): 
?>
<div class="forum-note-box forum-note-success">
	<div class="forum-note-box-text"><?=ShowNote($arResult["OK_MESSAGE"], "forum-note-success")?></div>
</div>
<?php 
endif;

?>
<script>
if (typeof oText != "object")
		var oText = {};
oText['empty_action'] = '<?=GetMessageJS("JS_NO_ACTION")?>';
oText['empty_topics'] = '<?=GetMessageJS("JS_NO_TOPICS")?>';
oText['del_topics'] = '<?=GetMessage("JS_DEL_TOPICS")?>';
</script>

<?php if($arResult["EMAIL_INTEGRATION"] || is_array($arResult["MAILBOXES"])):?>
<?php echo GetMessage("FTL_EMAIL_INTEGRATION")?> 
<?php if($arResult["EMAIL_INTEGRATION"] && $arResult["EMAIL_INTEGRATION"]["EMAIL_FORUM_ACTIVE"]=="Y"):?>
<a href="mailto:<?=urlencode($arResult["EMAIL_INTEGRATION"]["EMAIL"])?>" title="<?php echo GetMessage("FTL_EMAIL_MAIL_TITLE")?>"><?=$arResult["EMAIL_INTEGRATION"]["EMAIL"]?></a>
<?php else:?>
<span style="color:red"><?php echo GetMessage("FTL_EMAIL_MAIL_OFF")?> </span>
<?php endif;?>
<?php if(is_array($arResult["MAILBOXES"])):?>
| <a href="javascript:void(0);" onclick="return ShowEMailSettings()"><?php echo GetMessage("FTL_EMAIL_MAIL_SET")?></a>
<?php endif?>
<?php 
if($arResult["EMAIL_INTEGRATION"]):
?>
|
	<?php if($arResult["USER"]["SUBSCRIBE"]=="Y"):?>
		<a rel="nofollow" title="<?=GetMessage("F_UNSUBSCRIBE_TO_NEW_POSTS")?>" href="<?=$APPLICATION->GetCurPageParam("ACTION=FORUM_UNSUBSCRIBE&".bitrix_sessid_get(), array("ACTION", "sessid"))?>"><?=GetMessage("F_UNSUBSCRIBE")?></a>
	<?php else:?>
		<a rel="nofollow" title="<?=GetMessage("F_SUBSCRIBE_TO_NEW_POSTS")?>" href="<?=$APPLICATION->GetCurPageParam("ACTION=FORUM_SUBSCRIBE&".bitrix_sessid_get(), array("ACTION", "sessid"))?>"><?=GetMessage("F_SUBSCRIBE")?></a>
	<?php endif?>
<?php 
endif;
?>
<?php 
if(is_array($arResult["MAILBOXES"])):

	require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/interface/init_admin.php");
	CUtil::InitJSCore(array("window", "ajax"));
?>
<div id="xcvc"></div>
<div id="xcv" style="display:none">
<form class="forum-form" action="<?=POST_FORM_ACTION_URI?>" method="POST" name="EMF_<?=$iIndex?>" id="EMF_<?=$iIndex?>">
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="PAGE_NAME" value="topic_list" />
	<input type="hidden" name="FID" value="<?=$arParams["FID"]?>" />
	<input type="hidden" name="SAVE_EMAIL_FORUM" value="Y" />

<?php 
$arWRes = $arResult['EMAIL_INTEGRATION'];
if(!is_array($arWRes))
	$arWRes = Array("EMAIL_GROUP");
?>
<table>
<tr valign="top">
	<td align="right"><label for="EMAIL_FORUM_ACTIVE"><?php echo GetMessage("FTL_EMAIL_ACTIVE_CHECKBOX")?></label></td>
	<td><input type="checkbox" name="EMAIL_FORUM_ACTIVE" id="EMAIL_FORUM_ACTIVE" onclick="ChActMBx(this)" value="Y"<?php if($arWRes['EMAIL_FORUM_ACTIVE']=='Y')echo ' checked'?>></td>
</tr>
<tr valign="top">
	<td align="right"><?php ShowJSHint(GetMessage("FTL_EMAIL_MAILBOX_HINT"))?> <?php echo GetMessage("FTL_EMAIL_MAILBOX")?></td>
	<td nowrap>
	<?php 
	$arMBoxes = Array();
	foreach($arResult["MAILBOXES"] as $arMB)
	{
		$mbid = ($arMB['ID']>0 ? $arMB['ID'] : 'M'.$arMB['MAILBOX_ID']);
		$arMBoxes[$mbid] = Array("name"=>$arMB["MAILBOX_NAME"], "server_type"=>$arMB['MAILBOX_TYPE'], 'domains'=>preg_split("/[\r\n]+/", $arMB["DOMAINS"], -1, PREG_SPLIT_NO_EMPTY));
	}
	echo '<script>var arMailBoxes = '.CUtil::PhpToJSObject($arMBoxes, false).';</script>';
	?>
		<select name="EMAIL_FORUM_MAILBOX" onchange="ChMBx(this)" id="EI_0">
			<option value=""><?php echo GetMessage("FTL_EMAIL_MAILBOX_SEL")?></option>
			<option value="!"><?php echo GetMessage("FTL_EMAIL_MAILBOX_NEW")?></option>
			<?php foreach($arMBoxes as $mbid=>$arMB):?>
				<option value="<?=$mbid?>"<?php if($arWRes['MAIL_FILTER_ID']==$mbid)echo ' selected'?>><?=$arMB["name"]?></option>
			<?php endforeach?>
		</select> <a href="/bitrix/admin/mail_mailbox_admin.php?lang=<?=LANGUAGE_ID?>" target="_blank"><?php echo GetMessage("FTL_EMAIL_MAILBOX_LINK")?></a>
	</td>
</tr>
<tr style="display:none" id="EI_1">
	<td align="right"> <span class="required">*</span><?php echo GetMessage("FTL_EMAIL_MAILBOX_NAME")?></td>
	<td><input type="text" name="EMAIL_FORUM_MAILBOX_NAME" size="50" value="<?php ?>" id="EI_9"></td>
</tr>
<tr style="display:none" id="EI_2">
	<td align="right"> <span class="required">*</span><?php echo GetMessage("FTL_EMAIL_MAILBOX_SERVER")?></td>
	<td nowrap><input type="text" name="EMAIL_FORUM_MAILBOX_SERVER" size="40" value="" id="EI_10">:<input type="text" size="5" name="EMAIL_FORUM_MAILBOX_PORT" value="110" id="EI_11"> <input type="checkbox" name="EMAIL_FORUM_MAILBOX_SSL" value="Y" id="EI_12">SSL</td>
</tr>
<tr style="display:none" id="EI_3">
	<td align="right"> <span class="required">*</span><?php echo GetMessage("FTL_EMAIL_MAILBOX_LOGIN")?></td>
	<td><input type="text" name="EMAIL_FORUM_MAILBOX_LOGIN" value="" id="EI_13"></td>
</tr>
<tr style="display:none" id="EI_4">
	<td align="right"> <span class="required">*</span><?php echo GetMessage("FTL_EMAIL_MAILBOX_PASSW")?></td>
	<td ><input type="password" name="EMAIL_FORUM_MAILBOX_PASSWORD" value="" id="EI_14">
	<br><a href="javascript:void(0)" onclick="return ChMBAcc();"><?php echo GetMessage("FTL_EMAIL_MAILBOX_CHECK")?></a></td>
</tr>
<tr style="display:none" id="EI_5">
	<td align="right"><?php echo GetMessage("FTL_EMAIL_MAILBOX_DEL")?></td>
	<td><input type="checkbox" name="EMAIL_FORUM_MAILBOX_DELETE_MESSAGES" value="Y" id="EI_21" checked></td>
</tr>
<?php 
$email = $arWRes["EMAIL"];
if($arMBoxes[$arWRes['MAIL_FILTER_ID']]['server_type']=='smtp' && count($arMBoxes[$arWRes['MAIL_FILTER_ID']]['domains'])>0)
{
	$tmp = explode("@", $email);
	$email = $tmp[0];
	$domain = $tmp[1];
}
?>
<tr valign="top" id="EI_20">
	<td align="right"><?php ShowJSHint(GetMessage("FTL_EMAIL_FROM_HINT"));?> <span class="required">*</span><?php echo GetMessage("FTL_EMAIL_FROM")?></td>
	<td valign="top" nowrap>
		<input type="text" size="50" name="EMAIL" value="<?=$email?>" onchange="OnChEM(this)" id="EI_15"><span id="EMD">@<select name="EMAIL_DOMAIN" id="EI_22">
		<?php if($arMBoxes[$arWRes['MAIL_FILTER_ID']]['server_type']=='smtp' && count($arMBoxes[$arWRes['MAIL_FILTER_ID']]['domains'])>0):
			foreach($arMBoxes[$arWRes['MAIL_FILTER_ID']]['domains'] as $d):
			?><option value="<?=$d?>"<?php if($d==$domain)echo ' selected'?>><?=$d?></option>
			<?php endforeach;
		endif;?>
		</select></span>
	</td>
</tr>
<tr valign="top"  id="EI_6">
	<td align="right"><?php ShowJSHint(GetMessage("FTL_EMAIL_SHOW_EMAIL_HINT"));?> <label for="WUSE_EMAIL"><?php echo GetMessage("FTL_EMAIL_SHOW_EMAIL")?></label></td>
	<td valign="top">
		<input type="checkbox" name="USE_EMAIL" value="Y" <?php if($arWRes["USE_EMAIL"]=="Y") echo "checked"?> id="WUSE_EMAIL" id="EI_15">
	</td>
</tr>
<tr valign="top" id="EI_7">
	<td align="right"><?php ShowJSHint(GetMessage("FTL_EMAIL_FILTER_HINT"))?> <?php echo GetMessage("FTL_EMAIL_FILTER")?></td>
	<td valign="top" nowrap>
		<span id="W_EMAIL_GROUPT">
			<input type="checkbox" onclick="ChEMGR(this)"<?php if($arWRes["EMAIL_GROUP"]!='' || !is_array($arResult['EMAIL_INTEGRATION']))echo ' checked';?> id="EI_16">
			<label for="EI_16"><?php echo GetMessage("FTL_EMAIL_FILTER_EMAIL")?></label> 
			<input type="text" name="EMAIL_GROUP" id="W_EMAIL_GROUP" value="<?=$arWRes["EMAIL_GROUP"]?>"<?php if($arWRes["EMAIL_GROUP"]=='' && is_array($arResult['EMAIL_INTEGRATION']))echo ' disabled';?>><br>
		</span>
		<input type="checkbox" onclick="ChSSU(this)" <?php if($arWRes["SUBJECT_SUF"]!='')echo ' checked';?>  id="EI_17">
		<label for="EI_17"><?php echo GetMessage("FTL_EMAIL_FILTER_SUBJECT")?></label> 
		<input type="text" name="SUBJECT_SUF" value="<?=$arWRes["SUBJECT_SUF"]?>"<?php if($arWRes["SUBJECT_SUF"]=='')echo ' disabled';?> id="W_SUBJECT_SUF">
	</td>
</tr>
<tr valign="top" id="EI_8">
	<td align="right"><?php ShowJSHint(GetMessage("FTL_EMAIL_GROUP"))?> <label for="EI_18"><?php echo GetMessage("FTL_EMAIL_GROUP_NAME")?></label></td>
	<td valign="top">
		<input type="checkbox" name="USE_SUBJECT" value="Y" <?php if($arWRes["USE_SUBJECT"]=="Y" || !is_array($arResult['EMAIL_INTEGRATION'])) echo "checked"?> 
		onclick="ChWUSES(this)" id="EI_18">
	</td>
</tr>
<tr valign="top" id="EI_19">
	<td align="right"><?php ShowJSHint(GetMessage("FTL_EMAIL_PUBLIC_HINT"))?> <label for="WNOT_MEMBER_POST"><?php echo GetMessage("FTL_EMAIL_PUBLIC")?></label></td>
	<td valign="top">
		<input type="checkbox" name="NOT_MEMBER_POST" value="Y" <?php if($arWRes["NOT_MEMBER_POST"]=="Y") echo "checked"?> id="WNOT_MEMBER_POST" id="EI_20">
	</td>
</tr>
</table>
</form>
</div>
<?php CUtil::InitJSCore();?>
<script>
var wEmailSettings;
BX.ready(function()
	{
		ChActMBx();
		wEmailSettings = new BX.CDialog({'content':'<div id="EMDC_<?=$iIndex?>"></div>', 'title': '<?=GetMessage("FTL_EMAIL_TITLE")?>'});
		
		wEmailSettings.SetButtons
		(
			[
				{
					title: "<?php echo GetMessage("FTL_EMAIL_SAVE")?>",
					name: 'mailsave',
					id: 'btnmailsave',
					action: function () {
						OnSaveEMF();
						//this.Close();
					}
				},
				{
					title: "<?php echo GetMessage("FTL_EMAIL_CANCEL")?>",
					name: 'mailcancel',
					id: 'btnmailcancel',
					action: function () {
						document.getElementById('xcv').style.display = 'none';
						document.getElementById('xcvc').appendChild(document.getElementById('xcv'));
						this.parentWindow.Close();
					}
				}
			]
		
		);
	}
);


function ShowEMailSettings()
{
	wEmailSettings.Show();
	document.getElementById('EMDC_<?=$iIndex?>').appendChild(document.getElementById('xcv'));
	document.getElementById('xcv').style.display = 'block';
	return false;
}

function ChMBAcc()
{
	stacc = false;
	var serv = BX.util.urlencode(document.getElementById("EI_10").value);
	var port = BX.util.urlencode(document.getElementById("EI_11").value);
	var ssl = (document.getElementById("EI_12").checked?"Y":"N");
	var login = BX.util.urlencode(document.getElementById("EI_13").value);
	var passw = BX.util.urlencode(document.getElementById("EI_14").value);

	var url = '/bitrix/admin/mail_check_mailbox.php?lang=<?=LANGUAGE_ID?>';
	BX.showWait(document.getElementById('xcv'));
	BX.ajax({
		'url':url,
		'method':'POST',
		'data' : 'serv='+serv+'&port='+port+'&ssl='+ssl+'&login='+login+'&passw='+passw,
		'dataType': 'json',
		'timeout': 5,
		'async': false,
		'start': true,
		'onsuccess': ChMBAccY, 
		'onfailure': ChMBAccN
	});
}

function ChMBAccY(o)
{
	BX.closeWait(document.getElementById('xcv'));
	if(o[0])
		alert('<?=GetMessage("FTL_EMAIL_CHECK_OK")?> '+o[1]);
	else
		alert(o[1]);
}

function ChMBAccN(v, s)
{
	BX.closeWait(document.getElementById('xcv'));
	alert('<?=GetMessage("FTL_EMAIL_CHECK_F")?>');
}
setTimeout("ChMBx()", 0);
function ChMBx()
{
	var f = BX('EI_0');
	var email_input = document.getElementById('EI_15');

	if(f.value=="!") // new pop3 mailbox
	{
		for(var i=1; i<6; i++)
			document.getElementById('EI_'+i).style.display = '';
	}
	else
	{
		for(var i=1; i<6; i++)
			document.getElementById('EI_'+i).style.display = 'none';
	}
	
	var email_domain_select = document.getElementById('EI_22');
	
	if(f.value.length>0 && arMailBoxes[f.value] && arMailBoxes[f.value]['server_type']=='smtp') // smtp
	{
		if(arMailBoxes[f.value]['domains'].length>0) //smtp with defined domains
		{
			email_input.size = '20';
			var si = email_domain_select.selectedIndex;
			email_domain_select.options.length = 0;
			for(var ix=0; ix<arMailBoxes[f.value]['domains'].length; ix++)
				email_domain_select.options[ix] = new Option(arMailBoxes[f.value]['domains'][ix], arMailBoxes[f.value]['domains'][ix]);

			if(email_domain_select.selectedIndex<email_domain_select.options.length)
				email_domain_select.selectedIndex = si;

			BX('W_EMAIL_GROUPT').style.display = 'none';
			BX('EMD').style.display = '';
		}
		else
		{
			email_input.size = '50';
			BX('W_EMAIL_GROUPT').style.display = '';
			BX('EMD').style.display = 'none';
		}
	}
	else //pop3
	{
		email_input.size = '50';
		BX('W_EMAIL_GROUPT').style.display = '';
		BX('EMD').style.display = 'none';
	}
}

function ChActMBx()
{
	var f = document.getElementById('EMAIL_FORUM_ACTIVE');
	for(var i=0; i<22; i++)
	{
		document.getElementById('EI_'+i).disabled = !f.checked;
	}
}

function OnSaveEMF()
{
	if(document.getElementById('EMAIL_FORUM_ACTIVE').checked)
	{
		var sel = document.getElementById('EI_0');	
		if(sel.value == '')
		{
			alert("<?php echo GetMessage("FTL_EMAIL_SAVE_ERR1")?>");
			return false;
		}
		else if(sel.value == '!' && 
			(document.getElementById('EI_9').value=='' || document.getElementById('EI_10').value=='' || document.getElementById('EI_11').value=='' || document.getElementById('EI_13').value=='' || document.getElementById('EI_14').value=='')
		)
		{
			alert("<?php echo GetMessage("FTL_EMAIL_SAVE_ERR2")?>");
			return false;
		}
		
		if(document.getElementById('EI_15').value=='')
		{
			alert("<?php echo GetMessage("FTL_EMAIL_SAVE_ERR3")?>");
			return false;
		}
	}
	BX.showWait(document.getElementById('xcv'));
	document.getElementById('EMF_<?=$iIndex?>').submit();
}

function OnChEM(f)
{
	if((document.getElementById('W_EMAIL_GROUP').value==' ' || document.getElementById('W_EMAIL_GROUP').value=='') && document.getElementById("EI_16").checked)
		document.getElementById('W_EMAIL_GROUP').value=document.getElementById('EI_15').value;
}

function ChSSU(f)
{
	document.getElementById('W_SUBJECT_SUF').disabled = !f.checked;
	if(f.checked)
		document.getElementById('W_SUBJECT_SUF').focus();
}


function ChEMGR(f)
{
	document.getElementById('W_EMAIL_GROUP').disabled = !f.checked;
	if(f.checked)
	{
		document.getElementById('W_EMAIL_GROUP').value=document.getElementById('EI_15').value;
		document.getElementById('W_EMAIL_GROUP').focus();
	}
}

function ChWUSES(f)
{
	if(f.checked)
		alert('<?=GetMessage("FTL_EMAIL_WARNING")?>');
}
</script>
<?php endif?>
<?php endif?>