<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$tabIndex = $arParams["tabIndex"];
$id = 'POST_MESSAGE';
$fileControlId = 'forumfiles'.$arResult["FORUM"]["ID"];?>
<script type="text/javascript">
BX.message({
	recover_message : '<?=GetMessageJS('F_MESSAGE_RECOVER')?>',
	no_topic_name : '<?=GetMessageJS("JERROR_NO_TOPIC_NAME")?>',
	no_message : '<?=GetMessageJS("JERROR_NO_MESSAGE")?>',
	max_len : '<?=GetMessageJS("JERROR_MAX_LEN")?>',
	author : ' <?=GetMessageJS("JQOUTE_AUTHOR_WRITES")?>:\n',
	vote_drop_answer_confirm : '<?=GetMessageJS("F_VOTE_DROP_ANSWER_CONFIRM")?>',
	vote_drop_question_confirm : '<?=GetMessageJS("F_VOTE_DROP_QUESTION_CONFIRM")?>',
	MPL_HAVE_WRITTEN : '<?=GetMessageJS('MPL_HAVE_WRITTEN')?>'
});
BX.Forum.Init({
	formID : '<?=$arParams["FORM_ID"]?>',
	captcha : '<?=($arParams["FORUM"]["USE_CAPTCHA"]=="Y" && !$USER->IsAuthorized() ? "Y" : "N")?>',
	bVarsFromForm : '<?=$arParams["bVarsFromForm"]?>',
	autosave : '<?=($arParams['AUTOSAVE'] ? "Y" : "N")?>',
	ajaxPost : '<?=$arParams["AJAX_POST"]?>'
});
</script>
<a name="postform"></a>
<div class="forum-header-box">
	<div class="forum-header-title"><span><?php 
if ($arParams["MESSAGE_TYPE"] == "NEW"):
	?><?=GetMessage("F_CREATE_FORM")?><?php 
elseif ($arParams["MESSAGE_TYPE"] == "REPLY"):
	?><?=GetMessage("F_REPLY_FORM")?><?php 
else:
	?><?=GetMessage("F_EDIT_FORM")?> <a href="<?=$arResult["URL"]["READ"]?>"><?=htmlspecialcharsEx($arResult["TOPIC_FILTER"]["TITLE"])?></a> <?php 
endif;	
	?></span></div>
</div>

<div class="forum-reply-form">
<?php 
if (!empty($arResult["ERROR_MESSAGE"])): 
?>
<div class="forum-note-box forum-note-error">
	<div class="forum-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"], "forum-note-error");?></div>
</div>
<?php 
endif;
?>

<form name="<?=$arParams["FORM_ID"]?>" id="<?=$arParams["FORM_ID"]?>" action="<?=POST_FORM_ACTION_URI?>#postform"<?php 
	?> method="post" enctype="multipart/form-data" class="forum-form">
	<input type="hidden" name="PAGE_NAME" value="<?=$arParams["PAGE_NAME"];?>" />
	<input type="hidden" name="FID" value="<?=$arParams["FID"]?>" />
	<input type="hidden" name="TID" value="<?=$arParams["TID"]?>" />
	<input type="hidden" name="MID" value="<?=$arParams["MID"];?>" />
	<input type="hidden" name="MESSAGE_TYPE" value="<?=$arParams["MESSAGE_TYPE"];?>" />
	<input type="hidden" name="AUTHOR_ID" value="<?=$arResult["DATA"]["AUTHOR_ID"];?>" />
	<input type="hidden" name="forum_post_action" value="save" />
	<input type="hidden" name="MESSAGE_MODE" value="NORMAL" />
	<input type="hidden" name="AJAX_POST" value="<?=$arParams["AJAX_POST"]?>" />
	<?=bitrix_sessid_post()?>
	<?php  if ($arParams['AUTOSAVE']) $arParams['AUTOSAVE']->Init(); ?>
	<?php 
if (($arResult["SHOW_PANEL"]["TOPIC"] == "Y" || $arResult["SHOW_PANEL"]["GUEST"] == "Y") && $arParams["AJAX_CALL"] == "N")
{
?>
<div class="forum-reply-fields">
	<?php /* NEW TOPIC */
	if ($arResult["SHOW_PANEL"]["TOPIC"] == "Y") { ?>
	<div class="forum-reply-field forum-reply-field-title">
		<label for="TITLE<?=$arParams["form_index"]?>"><?=GetMessage("F_TOPIC_NAME")?><span class="forum-required-field">*</span></label>
		<input name="TITLE" id="TITLE<?=$arParams["form_index"]?>" type="text" value="<?=$arResult["DATA"]["TITLE"];?>" tabindex="<?=$tabIndex++;?>" size="70" /></div>
	<div class="forum-reply-field forum-reply-field-desc">
		<label for="DESCRIPTION<?=$arParams["form_index"]?>"><?=GetMessage("F_TOPIC_DESCR")?></label>
		<input name="DESCRIPTION" id="DESCRIPTION<?=$arParams["form_index"]?>" type="text" value="<?=$arResult["DATA"]["DESCRIPTION"];?>" <?php 
			?>tabindex="<?=$tabIndex++;?>" size="70"/></div>
	<?php }
	/* GUEST PANEL */
	if ($arResult["SHOW_PANEL"]["GUEST"] == "Y") { ?>
	<div class="forum-reply-field-user">
		<div class="forum-reply-field forum-reply-field-author"><label for="AUTHOR_NAME<?=$arParams["form_index"]?>"><?=GetMessage("F_TYPE_NAME")?><?php 
			?><span class="forum-required-field">*</span></label>
			<span><input name="AUTHOR_NAME" id="AUTHOR_NAME<?=$arParams["form_index"]?>" size="30" type="text" value="<?=$arResult["DATA"]["AUTHOR_NAME"]?>"<?php 
				?> tabindex="<?=$tabIndex++;?>" /></span></div><?php 
		if ($arResult["FORUM"]["ASK_GUEST_EMAIL"] == "Y") { ?>
			<div class="forum-reply-field-user-sep">&nbsp;</div>
			<div class="forum-reply-field forum-reply-field-email"><label for="AUTHOR_EMAIL<?=$arParams["form_index"]?>"><?=GetMessage("F_TYPE_EMAIL")?><?php 
				?><span class="forum-required-field">*</span></label>
				<span><input type="text" name="AUTHOR_EMAIL" id="AUTHOR_EMAIL<?=$arParams["form_index"]?>" size="30" value="<?=$arResult["DATA"]["AUTHOR_EMAIL"];?>"<?php 
					?> tabindex="<?=$tabIndex++;?>" /></span></div><?php 
		}?>
		<div class="forum-clear-float"></div>
	</div><?php 
	}

	if (($arResult["SHOW_PANEL"]["TOPIC"] & $arResult["SHOW_PANEL"]["TAGS"]) == "Y") {
		$iIndex = $tabIndex++;
	?><div class="forum-reply-field forum-reply-field-tags" <?php 
		if (!empty($arResult["DATA"]["TAGS"])) { ?> style="display:block;"<?php  }?>>
		<label for="TAGS"><?=GetMessage("F_TOPIC_TAGS")?></label>
		<?php 
		if ($arResult["SHOW_SEARCH"] == "Y") {
			$APPLICATION->IncludeComponent(
				"bitrix:search.tags.input",
				"",
				(
					array(
						"VALUE" => $arResult["DATA"]["~TAGS"],
						"NAME" => "TAGS",
						"TEXT" => 'tabindex="'.$iIndex.'" size="70" onmouseover="BX.Forum.CorrectTags(this)"',
						"TMPL_IFRAME" => "N") +
					(
						$arParams["MODE"] == "GROUP"
						?
						array(
							"arrFILTER" => "socialnetwork",
							"arrFILTER_socialnetwork" => $arParams["SOCNET_GROUP_ID"]
						)
						:
						array(
							"arrFILTER" => "socialnetwork_user",
							"arrFILTER_socialnetwork_user" => $arParams["USER_ID"]
						)
					)
				),
				$component,
				array("HIDE_ICONS" => "Y"));
			?><iframe id="TAGS_div_frame" name="TAGS_div_frame" src="javascript:void(0)" style="display:none;"/></iframe><?php 
		}
		else
		{
			?><input name="TAGS" id="TAGS" type="text" value="<?=$arResult["DATA"]["TAGS"]?>" tabindex="<?=$iIndex?>" size="70" /><?php 
		}
		?>
		<div class="forum-clear-float"></div>
	</div><?php  }

	if (($arResult["SHOW_PANEL"]["TOPIC"] & ($arResult["SHOW_PANEL"]["VOTE"]|$arResult["SHOW_PANEL"]["TAGS"])) == "Y" &&
		(empty($arResult["DATA"]["TAGS"]) || empty($arResult["QUESTIONS"]))) {
	?><div class="forum-reply-field forum-reply-field-switcher"><?php 
		if (empty($arResult["DATA"]["TAGS"]) && $arResult["SHOW_PANEL"]["TAGS"] == "Y") {
			?><span class="forum-reply-field forum-reply-field-addtags"><?php 
				?><a href="javascript:void(0);" onclick="return BX.Forum.AddTags(this);" <?php 
					?>onfocus="BX.Forum.AddTags(this);" tabindex="<?=$iIndex?>"><?=GetMessage("F_TOPIC_TAGS_DESCRIPTION")?></a><?php 
			?>&nbsp;&nbsp;</span><?php }
		if (empty($arResult["QUESTIONS"]) && $arResult["SHOW_PANEL"]["VOTE"] == "Y") {
			?><span class="forum-reply-field forum-reply-field-vote"><?php 
				?><a href="javascript:void(0);" onclick="return BX.Forum.ShowVote(this);" <?php 
					?>onfocus="BX.Forum.ShowVote(this);" tabindex="<?=$tabIndex++?>"><?=GetMessage("F_ADD_VOTE")?></a>
			</span><?php 
		}?></div><?php 
	}?>
</div><?php 

if ($arResult["SHOW_PANEL"]["TOPIC"] == "Y" && $arResult["SHOW_PANEL"]["VOTE"] == "Y") {
	ob_start();
	?><li id="ANS_#Q#__#A#_"><input type="text" name="ANSWER[#Q#][#A#]" value="#A_VALUE#" /><?php 
		?><label>[<a onclick="return vote_remove_answer(this)" title="<?=GetMessage("F_VOTE_DROP_ANSWER")?>" href="#">X</a>]</label></li><?php 
	$sAnswer = ob_get_clean();
	ob_start();
	?><div class="forum-reply-field-vote-question"><?php 
		?><div id="QST_#Q#_" class="forum-reply-field-vote-question-title"><?php 
			?><input type="text" name="QUESTION[#Q#]" id="QUESTION_#Q#" value="#Q_VALUE#" /><?php 
			?><label for="QUESTION_#Q#">[<a onclick="return vote_remove_question(this)" title="<?=GetMessage("F_VOTE_DROP_QUESTION")?>" href="#">X</a>]</label><?php 
		?></div><?php 
		?><div class="forum-reply-field-vote-question-options"><?php 
			?><input type="checkbox" value="Y" name="MULTI[#Q#]" id="MULTI_#Q#" #Q_MULTY# /><?php 
			?><label for="MULTI_#Q#"><?=GetMessage("F_VOTE_MULTI")?></label><?php 
		?></div><?php 
		?><ol class="forum-reply-field-vote-answers">#Q_ANSWERS#<?php 
			?><li>[<a onclick="return vote_add_answer(this)" name="addA#Q#" href="#"><?=GetMessage("F_VOTE_ADD_ANSWER")?></a>]</li><?php 
		?></ol><?php 
	?></div><?php 
	$sQuestion = ob_get_clean();
	?>
<script type="text/javascript">
	var arVoteParams = {
		'qCount': <?=(empty($arResult["QUESTIONS"]) ? 1 : count($arResult["QUESTIONS"]))?>,
		'qNum': <?=(empty($arResult["QUESTIONS"]) ? 0 : count($arResult["QUESTIONS"]) - 1)?>,
		'template_answer' : '<?=CUtil::JSEscape(str_replace("#A_VALUE#", "", $sAnswer))?>',
		'template_question' : '<?=CUtil::JSEscape(str_replace(
			array("#Q_VALUE#", "#Q_MULTY#", "#Q_ANSWERS#", "#A#", "#A_VALUE#"),
			array("", "", $sAnswer, 1, ""), $sQuestion
		))?>'
	}
</script>
<div id="vote_params" <?php if (empty($arResult["QUESTIONS"])) { ?>style="display:none;"<?php  }?>>
	<div class="forum-reply-header"><?=GetMessage("F_VOTE")?></div>
	<div class="forum-reply-fields">
		<div class="forum-reply-field forum-reply-field-vote-duration">
			<label><?=GetMessage('VOTE_DURATION')?></label>
			<?php $APPLICATION->IncludeComponent(
				"bitrix:main.calendar",
				"",
				array(
					"SHOW_INPUT"=>"Y",
					"SHOW_TIME"=>"N",
					"INPUT_NAME"=>"DATE_END",
					"INPUT_VALUE"=>$arResult['DATE_END'],
					"FORM_NAME"=>$arParams["FORM_ID"],
				),
				$component,
				array("HIDE_ICONS"=>true)
			);?>
		</div>
		<div class="forum-reply-field forum-reply-field-vote"><?php 
		$arResult["QUESTIONS"] = array_values($arResult["QUESTIONS"]);
		foreach ($arResult["QUESTIONS"] as $qq => $arQuestion)
		{
			?><input type="hidden" name="QUESTION_ID[<?=$qq?>]" value="<?=$arQuestion["ID"]?>" /><?php 
			?><input type="hidden" name="QUESTION_DEL[<?=$qq?>]" value="<?=($arQuestion["DEL"] == "Y" ? "Y" : "N")?>" /><?php 

			if ($arQuestion["DEL"] == "Y")
				continue;

			$arAnswers = array();
			$arQuestion["ANSWERS"] = array_values($arQuestion["ANSWERS"]);
			foreach ($arQuestion["ANSWERS"] as $aa => $arAnswer)
			{
				?><input type="hidden" name="ANSWER_ID[<?=$qq?>][<?=$aa?>]" value="<?=$arAnswer["ID"]?>" /><?php 
				?><input type="hidden" name="ANSWER_DEL[<?=$qq?>][<?=$aa?>]" value="<?=$arAnswer["DEL"]?>" /><?php 
				if ($arAnswer["DEL"] == "Y")
					continue;
				$arAnswers[] = str_replace(
					array("#A#", "#A_VALUE#"),
					array($aa, $arAnswer["MESSAGE"]),
					$sAnswer);
			}
			?><?=str_replace(
				array("#Q_VALUE#", "#Q_MULTY#", "#Q_ANSWERS#", "#Q#"),
				array($arQuestion["QUESTION"], ($arQuestion["MULTI"] == "Y" ? "checked" : ""), implode("", $arAnswers), $qq),
				$sQuestion
			);?><?php 
		}
		if (empty($arResult["QUESTIONS"]))
		{
			$qq = 1;
			?><?=str_replace(
			array("#Q_VALUE#", "#Q_MULTY#", "#Q_ANSWERS#", "#Q#", "#A#", "#A_VALUE#"),
			array("", "", $sAnswer, 1, 1, ""),
			$sQuestion
			)?><?php 
		}
			?><div class="forum-reply-field-vote-question" id="vote_question_add"><?php 
				?><a onclick="return vote_add_question(this.parentNode, '<?=$qq?>');" href="#"><?=GetMessage("F_VOTE_ADD_QUESTION")?></a><?php 
			?></div>
		</div>
	</div>
</div>
<?php 
	}
}
?>
<div class="forum-reply-header" style="clear:left;"><span><?=GetMessage("F_MESSAGE_TEXT")?></span><span class="forum-required-field">*</span></div>
	<div class="forum-reply-fields">
		<div class="forum-reply-field forum-reply-field-text">
			<?php 
			$APPLICATION->IncludeComponent(
				"bitrix:main.post.form",
				"",
				Array(
					"FORM_ID" => $arParams["FORM_ID"],
					"SHOW_MORE" => "Y",
					"PARSER" => forumTextParser::GetEditorToolbar(array('forum' => $arResult['FORUM'])),

					"LHE" => array(
						'id' => $id,
						'bSetDefaultCodeView' => ($arParams['EDITOR_CODE_DEFAULT'] == 'Y'),
						'bResizable' => true,
						'bAutoResize' => true,
						'bManualResize' => false,
						"documentCSS" => "body {color:#434343; font-size: 14px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 20px;}",
						'setFocusAfterShow' => false
					),

					"ADDITIONAL" => array(),

					"TEXT" => Array(
						"ID" => "POST_MESSAGE",
						"NAME" => "POST_MESSAGE",
						"VALUE" => isset($arResult['DATA']["~POST_MESSAGE"]) ? $arResult['DATA']["~POST_MESSAGE"] : "",
						"SHOW" => "Y",
						"HEIGHT" => "200px"),

					"UPLOAD_FILE" => array(
						'CONTROL_ID' => $fileControlId,
						"INPUT_NAME" => 'FILES',
						"INPUT_VALUE" => (!empty($arResult["DATA"]["FILES"]) ? array_keys($arResult["DATA"]["FILES"]) : false),
						"MAX_FILE_SIZE" => COption::GetOptionString("forum", "file_max_size", 5242880),
						"MULTIPLE" => "Y",
						"MODULE_ID" => "forum",
						"ALLOW_UPLOAD" => ($arResult["FORUM"]["ALLOW_UPLOAD"] == "N" || array_key_exists("UF_FORUM_MESSAGE_DOC", $arResult["USER_FIELDS"]) ? 'N' :
				($arResult["FORUM"]["ALLOW_UPLOAD"] == "Y" ? "I" : $arResult["FORUM"]["ALLOW_UPLOAD"])),
						"ALLOW_UPLOAD_EXT" => $arResult["FORUM"]["ALLOW_UPLOAD_EXT"],
						"TAG" => "FILE ID"
					),
					"PROPERTIES" => array(
						$arResult["USER_FIELDS"]["UF_FORUM_MESSAGE_DOC"]
					),

					"UPLOAD_FILE_PARAMS" => array("width" => $arParams["IMAGE_SIZE"], "height" => $arParams["IMAGE_SIZE"]),

//					"DESTINATION" => array(),

//					"TAGS" => Array(),

					"SMILES" => COption::GetOptionInt("forum", "smile_gallery_id", 0),
					"NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
				)
			);
			?>
		</div>
<?php 
/* ATTACH FILES */
if (!empty($arResult["USER_FIELDS"]))
{
	ob_start();
	foreach ($arResult["USER_FIELDS"] as $k => $v)
	{
		if ($k != "UF_FORUM_MESSAGE_DOC")
		{
			$v["VALUE"] = (!empty($_REQUEST[$k]) ? $_REQUEST[$k] : $v["VALUE"]);
			?><dt><?=$v["EDIT_FORM_LABEL"]?><?php if ($v["MANDATORY"] == "Y") { ?><span class="forum-required-field">*</span><?php  } ?></dt><dd><?php 
			$GLOBALS["APPLICATION"]->IncludeComponent(
				"bitrix:system.field.edit",
				$v["USER_TYPE"]["USER_TYPE_ID"],
				array("arUserField" => $v, "bVarsFromForm" => ($arParams["bVarsFromForm"] == "Y")),
				null,
				array("HIDE_ICONS" => "Y")
			);?></dd><?php 
		}
	}
	$res = ob_get_clean();
	if (!empty($res)) {
		?><dl><?=$res?></dl><?php 
	}
}

/* EDIT PANEL */

if ($arResult["SHOW_PANEL"]["EDIT_INFO"] == "Y"):
?>
	<div class="forum-reply-field forum-reply-field-lastedit"><?php 
	$checked = true;
	if ($arParams["PERMISSION"] >= "Q"):
		$checked = ($_REQUEST["EDIT_ADD_REASON"]=="Y");
		?><div class="forum-reply-field-lastedit-view"><?php 
			?><input type="checkbox" id="EDIT_ADD_REASON" name="EDIT_ADD_REASON<?=$arParams["form_index"]?>" <?=($checked ? "checked=\"checked\"" : "")?> value="Y" <?php 
				?>onclick="BX.Forum.ShowLastEditReason(this.checked, this.parentNode.nextSibling)" />&nbsp;<?php 
			?><label for="EDIT_ADD_REASON<?=$arParams["form_index"]?>"><?=GetMessage("F_EDIT_ADD_REASON")?></label>
		</div><?php 
	endif;
		?><div class="forum-reply-field-lastedit-reason" <?php if (!$checked) { ?> style="display:none;" <?php }?>><?php 
		if ($arResult["SHOW_EDIT_PANEL_GUEST"] == "Y") {
			?><input name="EDITOR_NAME" type="hidden" value="<?=$arResult["EDITOR_NAME"];?>" /><?php 
			if ($arResult["FORUM"]["ASK_GUEST_EMAIL"] == "Y") {
			?><input name="EDITOR_EMAIL" type="hidden" value="<?=$arResult["EDITOR_EMAIL"];?>" /></br><?php  }
		}?>
			<label for="EDIT_REASON"><?=GetMessage("F_EDIT_REASON")?></label>
			<input type="text" name="EDIT_REASON" id="EDIT_REASON" size="70" value="<?=$arResult["DATA"]["EDIT_REASON"]?>" />
		</div>
	</div><?php 
endif;

/* CAPTHCA */
if (!empty($arResult["DATA"]["CAPTCHA_CODE"])):
?>
	<div class="forum-reply-field forum-reply-field-captcha">
		<input type="hidden" name="captcha_code" value="<?=$arResult["DATA"]["CAPTCHA_CODE"]?>"/>
		<div class="forum-reply-field-captcha-label">
			<label for="captcha_word"><?=GetMessage("F_CAPTCHA_PROMT")?><span class="forum-required-field">*</span></label>
			<input type="text" size="30" name="captcha_word" id="captcha_word" tabindex="<?=$tabIndex++;?>" autocomplete="off" />
		</div>
		<div class="forum-reply-field-captcha-image">
			<img src="/bitrix/tools/captcha.php?captcha_code=<?=$arResult["DATA"]["CAPTCHA_CODE"]?>" alt="<?=GetMessage("F_CAPTCHA_TITLE")?>" />
		</div>
	</div>
<?php 
endif;

?>
	<div class="forum-reply-field forum-reply-field-settings">
<?php 
/* SMILES */
if ($arResult["FORUM"]["ALLOW_SMILES"] == "Y")
{
?>
	<div class="forum-reply-field-setting">
		<input type="checkbox" name="USE_SMILES" id="USE_SMILES<?=$arParams["form_index"]?>" <?php 
		?>value="Y" <?=($arResult["DATA"]["USE_SMILES"]=="Y") ? "checked=\"checked\"" : "";?> <?php 
		?>tabindex="<?=$tabIndex++;?>" /><?php 
	?>&nbsp;<label for="USE_SMILES<?=$arParams["form_index"]?>"><?=GetMessage("F_WANT_ALLOW_SMILES")?></label></div>
<?php 
};
?>
	</div>
<?php 

?>
	<div class="forum-reply-buttons">
		<input name="send_button" type="submit" value="<?=$arResult["SUBMIT"]?>" tabindex="<?=$tabIndex++;?>" <?php 
			?>onclick="this.form.MESSAGE_MODE.value = 'NORMAL';" />
		<input name="view_button" type="submit" value="<?=GetMessage("F_VIEW")?>" tabindex="<?=$tabIndex++;?>" <?php 
			?>onclick="this.form.MESSAGE_MODE.value = 'VIEW';" />
	</div>
</div>
</form>
</div>