<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (!empty($arResult["ERROR_MESSAGE"])):
?>
<div class="vote-note-box vote-note-error">
	<div class="vote-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"])?></div>
</div>
<?php 
endif;

if (!empty($arResult["OK_MESSAGE"])): 
?>
<div class="vote-note-box vote-note-note">
	<div class="vote-note-box-text"><?=ShowNote($arResult["OK_MESSAGE"])?></div>
</div>
<?php 
endif;

if (empty($arResult["VOTE"])):
	return false;
elseif (empty($arResult["QUESTIONS"])):
	return true;
endif;

?>
<div class="voting-form-box">
<form action="<?=POST_FORM_ACTION_URI?>" method="post" class="vote-form">
	<input type="hidden" name="vote" value="Y">
	<input type="hidden" name="PUBLIC_VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>">
	<input type="hidden" name="VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>">
	<?=bitrix_sessid_post()?>
	
<ol class="vote-items-list vote-question-list">
<?php 
	$iCount = 0;
	foreach ($arResult["QUESTIONS"] as $arQuestion):
		$iCount++;

?>
	<li class="vote-item-vote <?=($iCount == 1 ? "vote-item-vote-first " : "")?><?php 
				?><?=($iCount == count($arResult["QUESTIONS"]) ? "vote-item-vote-last " : "")?><?php 
				?><?=($iCount%2 == 1 ? "vote-item-vote-odd " : "vote-item-vote-even ")?><?php 
				?>">

		<div class="vote-item-header">

<?php 
		if ($arQuestion["IMAGE"] !== false):
?>
			<div class="vote-item-image"><img src="<?=$arQuestion["IMAGE"]["SRC"]?>" width="30" height="30" /></div>
<?php 
		endif;
?>
			<div class="vote-item-title vote-item-question"><?=$arQuestion["QUESTION"]?><?php if($arQuestion["REQUIRED"]=="Y"){echo "<span class='starrequired'>*</span>";}?></div>
			<div class="vote-clear-float"></div>
		</div>
		
		<ol class="vote-items-list vote-answers-list">
<?php 
		$iCountAnswers = 0;
		foreach ($arQuestion["ANSWERS"] as $arAnswer):
			$iCountAnswers++;

?>
			<li class="vote-item-vote <?=($iCountAnswers == 1 ? "vote-item-vote-first " : "")?><?php 
						?><?=($iCountAnswers == count($arQuestion["ANSWERS"]) ? "vote-item-vote-last " : "")?><?php 
						?><?=($iCountAnswers%2 == 1 ? "vote-item-vote-odd " : "vote-item-vote-even ")?>">
<?php 
			switch ($arAnswer["FIELD_TYPE"]):
					case 0://radio
?>
						<span class="vote-answer-item vote-answer-item-radio">
							<input type="radio" name="vote_radio_<?=$arAnswer["QUESTION_ID"]?>" <?php 
								?>id="vote_radio_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>" <?php 
								?>value="<?=$arAnswer["ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?> />
							<label for="vote_radio_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>"><?=$arAnswer["MESSAGE"]?></label>
						</span>
<?php 
					break;
					case 1://checkbox?>
						<span class="vote-answer-item vote-answer-item-checkbox">
							<input type="checkbox" name="vote_checkbox_<?=$arAnswer["QUESTION_ID"]?>[]" value="<?=$arAnswer["ID"]?>" <?php 
								?> id="vote_checkbox_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?> />
							<label for="vote_checkbox_<?=$arAnswer["QUESTION_ID"]?>_<?=$arAnswer["ID"]?>"><?=$arAnswer["MESSAGE"]?></label>
						</span>
					<?php break?>

					<?php case 2://dropdown?>
						<span class="vote-answer-item vote-answer-item-dropdown">
							<select name="vote_dropdown_<?=$arAnswer["QUESTION_ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?>>
							<?php foreach ($arAnswer["DROPDOWN"] as $arDropDown):?>
								<option value="<?=$arDropDown["ID"]?>"><?=$arDropDown["MESSAGE"]?></option>
							<?php endforeach?>
							</select>
						</span>
					<?php break?>

					<?php case 3://multiselect?>
						<span class="vote-answer-item vote-answer-item-multiselect">
							<select name="vote_multiselect_<?=$arAnswer["QUESTION_ID"]?>[]" <?=$arAnswer["~FIELD_PARAM"]?> multiple="multiple">
							<?php foreach ($arAnswer["MULTISELECT"] as $arMultiSelect):?>
								<option value="<?=$arMultiSelect["ID"]?>"><?=$arMultiSelect["MESSAGE"]?></option>
							<?php endforeach?>
							</select>
						</span>
					<?php break?>

					<?php case 4://text field?>
						<span class="vote-answer-item vote-answer-item-textfield">
							<label for="vote_field_<?=$arAnswer["ID"]?>"><?=$arAnswer["MESSAGE"]?></label>
							<input type="text" name="vote_field_<?=$arAnswer["ID"]?>" id="vote_field_<?=$arAnswer["ID"]?>" <?php 
								?>value="" size="<?=$arAnswer["FIELD_WIDTH"]?>" <?=$arAnswer["~FIELD_PARAM"]?> /></span>
					<?php break?>

					<?php case 5://memo?>
						<span class="vote-answer-item vote-answer-item-memo">
							<label for="vote_memo_<?=$arAnswer["ID"]?>"><?=$arAnswer["MESSAGE"]?></label>
							<textarea name="vote_memo_<?=$arAnswer["ID"]?>" id="vote_memo_<?=$arAnswer["ID"]?>" <?php 
								?><?=$arAnswer["~FIELD_PARAM"]?> cols="<?=$arAnswer["FIELD_WIDTH"]?>" <?php 
								?>rows="<?=$arAnswer["FIELD_HEIGHT"]?>"></textarea>
						</span>
					<?php break;
				endswitch;
?>
			</li>
<?php 
			endforeach
?>
		</ol>
	</li>
<?php 
		endforeach
?>
</ol>
<?php //region Captcha
if (isset($arResult["CAPTCHA_CODE"])):  ?>
	<div class="vote-item-header">
		<div class="vote-item-title vote-item-question"><?=GetMessage("F_CAPTCHA_TITLE")?></div>
		<div class="vote-clear-float"></div>
	</div>
	<div class="vote-form-captcha">
		<input type="hidden" name="captcha_code" value="<?=$arResult["CAPTCHA_CODE"]?>"/>
		<div class="vote-reply-field-captcha-image">
			<img src="/bitrix/tools/captcha.php?captcha_code=<?=$arResult["CAPTCHA_CODE"]?>" alt="<?=GetMessage("F_CAPTCHA_TITLE")?>" />
		</div>
		<div class="vote-reply-field-captcha-label">
			<label for="captcha_word"><?=GetMessage("F_CAPTCHA_PROMT")?><span class='starrequired'>*</span></label><br />
			<input type="text" size="20" name="captcha_word" autocomplete="off" />
		</div>
	</div>
<?php  endif //endregion?>

<div class="vote-form-box-buttons vote-vote-footer">
	<span class="vote-form-box-button vote-form-box-button-first"><input type="submit" name="vote" value="<?=GetMessage("VOTE_SUBMIT_BUTTON")?>" /></span>
	<span class="vote-form-box-button vote-form-box-button-last">
		<a name="show_result" <?php 
			?>href="<?=$APPLICATION->GetCurPageParam("view_result=Y", array("VOTE_ID","VOTING_OK","VOTE_SUCCESSFULL", "view_result"))?>"><?=GetMessage("VOTE_RESULTS")?></a>
	</span>
</div>
</form>

</div>
