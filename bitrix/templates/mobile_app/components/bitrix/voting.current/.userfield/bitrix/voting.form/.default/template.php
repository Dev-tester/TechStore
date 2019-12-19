<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$uid = $arParams["UID"];
$controller = "BX('vote-".$uid."')";
$form = "vote-form-".$uid;

if (!empty($arResult["ERROR_MESSAGE"])):?>
<div class="vote-note-box vote-note-error">
	<div class="vote-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"])?></div>
</div>
<?php endif;

if (empty($arResult["QUESTIONS"])):
	return true;
endif;
?>
<form action="<?=str_replace(array("view_form=Y", "view_form"), "", POST_FORM_ACTION_URI)?>" method="post" class="vote-form" name="<?=$form?>" id="<?=$form?>">
	<input type="hidden" name="vote" value="Y" />
	<input type="hidden" name="revote" value="Y" />
	<input type="hidden" name="PUBLIC_VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>" />
	<input type="hidden" name="VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>" />
	<input type="hidden" name="REVOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>" />
	<?=bitrix_sessid_post()?>
	<ol class="bx-vote-question-list" id="vote-<?=$uid?>">
	<?php foreach ($arResult["QUESTIONS"] as $arQuestion):?>
		<li id="question<?=$arQuestion["ID"]?>" <?php if($arQuestion["REQUIRED"]=="Y"){?> class="bx-vote-question-required"<?php }?>>
			<?php if (!empty($arQuestion["IMAGE"]) && !empty($arQuestion["IMAGE"]["SRC"])) { ?><div class="bx-vote-question-image"><img src="<?=$arQuestion["IMAGE"]["SRC"]?>" /></div><?php  } ?>
			<div class="bx-vote-question-title"><?=$arQuestion["QUESTION"]?></div>
			<div class="bx-vote-answer-list-wrap">
				<table class="bx-vote-answer-list" cellspacing="0">
				<?php foreach ($arQuestion["ANSWERS"] as $arAnswer):?>
					<tr id="answer<?=$arAnswer["ID"]?>" class="bx-vote-answer-item" bx-voters-count="<?=$arAnswer["COUNTER"]?>">
						<td>
							<div class="bx-vote-answer-wrap">
							<?php 
				switch ($arAnswer["FIELD_TYPE"]):
					case 0://radio
					?><span class="bx-vote-block-input-wrap bx-vote-block-radio-wrap"><?php 
						?><input type="radio" name="vote_radio_<?=$arAnswer["QUESTION_ID"]?>" <?php 
							?>id="vote_radio_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>" <?php 
							?>value="<?=$arAnswer["ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?> /><?php 
						?><label for="vote_radio_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>"><?=$arAnswer["MESSAGE"]?></label><?php 
					?></span><?php 
					break;
					case 1://checkbox
						?><span class="bx-vote-block-input-wrap bx-vote-block-checbox-wrap"><?php 
							?><input <?=$value?> type="checkbox" name="vote_checkbox_<?=$arAnswer["QUESTION_ID"]?>[]" value="<?=$arAnswer["ID"]?>" <?php 
								?> id="vote_checkbox_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?> /><?php 
							?><label for="vote_checkbox_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>"><?=$arAnswer["MESSAGE"]?></label><?php 
						?></span><?php 
					break;
					case 2://select
						?><span class="bx-vote-block-input-wrap bx-vote-block-dropdown-wrap"><?php 
							?><select name="vote_dropdown_<?=$arAnswer["QUESTION_ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?>><?php 
							foreach ($arAnswer["DROPDOWN"] as $arDropDown):
								?><option value="<?=$arDropDown["ID"]?>" <?=$arDropDown["~FIELD_PARAM"]?>><?=$arDropDown["MESSAGE"]?></option><?php 
							endforeach;
							?></select><?php 
						?></span><?php 
					break;
					case 3://multiselect
						?><span class="bx-vote-block-input-wrap bx-vote-block-multiselect-wrap"><?php 
							?><select name="vote_multiselect_<?=$arAnswer["QUESTION_ID"]?>[]" <?=$arAnswer["~FIELD_PARAM"]?> multiple="multiple"><?php 
							foreach ($arAnswer["MULTISELECT"] as $arMultiSelect):
								?><option value="<?=$arMultiSelect["ID"]?>" <?=$arMultiSelect["~FIELD_PARAM"]?>><?=$arMultiSelect["MESSAGE"]?></option><?php 
							endforeach;
							?></select><?php 
						?></span><?php 
					break;
					case 4://text field
						?><span class="bx-vote-block-input-wrap bx-vote-block-textfield-wrap"><?php 
							?><label for="vote_field_<?=$arAnswer["ID"]?>"><?=$arAnswer["MESSAGE"]?></label><?php 
							?><input type="text" name="vote_field_<?=$arAnswer["ID"]?>" id="vote_field_<?=$arAnswer["ID"]?>" <?php 
								?>value="<?=htmlspecialcharsbx($arAnswer["~FIELD_TEXT"])?>" size="<?=$arAnswer["FIELD_WIDTH"]?>" <?=$arAnswer["~FIELD_PARAM"]?> /><?php 
						?></span><?php 
					break;
					case 5://memo
						?><span class="bx-vote-block-input-wrap bx-vote-block-memo-wrap"><?php 
							?><label for="vote_memo_<?=$arAnswer["ID"]?>"><?=$arAnswer["MESSAGE"]?></label><br /><?php 
							?><textarea name="vote_memo_<?=$arAnswer["ID"]?>" id="vote_memo_<?=$arAnswer["ID"]?>" <?php 
								?><?=$arAnswer["~FIELD_PARAM"]?> cols="<?=$arAnswer["FIELD_WIDTH"]?>" <?php 
								?>rows="<?=$arAnswer["FIELD_HEIGHT"]?>"><?=htmlspecialcharsbx($arAnswer["~FIELD_TEXT"])?></textarea><?php 
						?></span><?php 
					break;
				endswitch;?>
								<div class="bx-vote-answer-bg"></div>
								<div class="bx-vote-answer-bar"></div>
							</div>
						</td>
						<td>
							<div class="bx-vote-data-percent"></div>
						</td>
					</tr><?php 
					?><script>BX.bind(BX('answer<?=$arAnswer["ID"]?>'), 'click', function(e){BX.eventCancelBubble(e);});</script><?php 
					?><?php endforeach;?>
				</table>
			</div>
		</li>
	<?php endforeach;?>
		<li class="bx-vote-answer-result">
			<div class="bx-vote-answer-list-wrap">
				<table class="bx-vote-answer-list" cellspacing="0">
					<tr>
						<td><?=GetMessage("VOTE_RESULTS")?></td>
						<td class="bx-vote-events-count"><span><?=$arResult["VOTE"]["COUNTER"]?></span><span class="post-vote-color">%</span></td>
					</tr>
				</table>
			</div>
		</li>
	</ol>
	<?php 
if (isset($arResult["CAPTCHA_CODE"]))
{ ?>
<div class="bx-vote-captcha">
	<input type="hidden" name="captcha_code" value="<?=$arResult["CAPTCHA_CODE"]?>" />
	<span class="vote-captcha-image">
		<img src="/bitrix/tools/captcha.php?captcha_code=<?=$arResult["CAPTCHA_CODE"]?>" />
	</span>
	<span class="bx-vote-captcha-input">
		<label for="captcha_word"><?=GetMessage("F_CAPTCHA_PROMT")?></label>
		<input type="text" size="20" name="captcha_word" id="captcha_word" />
	</span>
</div>
<?php  } // CAPTCHA_CODE ?>
	<div class="vote-buttons" style="display:none;">
		<input type="submit" value="<?=GetMessage("VOTE_SUBMIT_BUTTON")?>" />
	</div>
</form>
<?php 
$this->__component->arParams["RETURN"] = array(
	"uid" => $uid,
	"controller" => $controller,
	"form" => $form);
?>