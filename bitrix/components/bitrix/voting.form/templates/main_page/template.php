<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?=ShowError($arResult["ERROR_MESSAGE"]);?>
<?=ShowNote($arResult["OK_MESSAGE"]);?>

<?php if (!empty($arResult["QUESTIONS"])):?>

<div class="voting-form-box">
	<form action="<?=POST_FORM_ACTION_URI?>" method="post">
	<input type="hidden" name="vote" value="Y">
	<input type="hidden" name="PUBLIC_VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>">
	<input type="hidden" name="VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>">
	<?=bitrix_sessid_post()?>

	<?php foreach ($arResult["QUESTIONS"] as $arQuestion):?>

		<?php if ($arQuestion["IMAGE"] !== false):?>
			<img src="<?=$arQuestion["IMAGE"]["SRC"]?>" width="30" height="30" />
		<?php endif?>

		<b><?=$arQuestion["QUESTION"]?><?php if($arQuestion["REQUIRED"]=="Y"){echo "<span class='starrequired'>*</span>";}?></b><br /><br />

		<?php foreach ($arQuestion["ANSWERS"] as $arAnswer):?>
			<?php 
			switch ($arAnswer["FIELD_TYPE"]):
				case 0://radio
					$value=(isset($_REQUEST['vote_radio_'.$arAnswer["QUESTION_ID"]]) && 
						$_REQUEST['vote_radio_'.$arAnswer["QUESTION_ID"]] == $arAnswer["ID"]) ? 'checked="checked"' : '';
				break;
				case 1://checkbox
					$value=(isset($_REQUEST['vote_checkbox_'.$arAnswer["QUESTION_ID"]]) && 
						array_search($arAnswer["ID"],$_REQUEST['vote_checkbox_'.$arAnswer["QUESTION_ID"]])!==false) ? 'checked="checked"' : '';
				break;
				case 2://select
					$value=(isset($_REQUEST['vote_dropdown_'.$arAnswer["QUESTION_ID"]])) ? $_REQUEST['vote_dropdown_'.$arAnswer["QUESTION_ID"]] : false;
				break;
				case 3://multiselect
					$value=(isset($_REQUEST['vote_multiselect_'.$arAnswer["QUESTION_ID"]])) ? $_REQUEST['vote_multiselect_'.$arAnswer["QUESTION_ID"]] : array();
				break;
				case 4://text field
					$value = isset($_REQUEST['vote_field_'.$arAnswer["ID"]]) ? htmlspecialcharsbx($_REQUEST['vote_field_'.$arAnswer["ID"]]) : '';
				break;
				case 5://memo
					$value = isset($_REQUEST['vote_memo_'.$arAnswer["ID"]]) ?  htmlspecialcharsbx($_REQUEST['vote_memo_'.$arAnswer["ID"]]) : '';
				break;
			endswitch;
			?>
			<?php switch ($arAnswer["FIELD_TYPE"]):
				case 0://radio?>
					<label><input <?=$value?> type="radio" name="vote_radio_<?=$arAnswer["QUESTION_ID"]?>" value="<?=$arAnswer["ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?> />&nbsp;<?=$arAnswer["MESSAGE"]?></label>
					<br />
				<?php break?>

				<?php case 1://checkbox?>
					<label><input <?=$value?> type="checkbox" name="vote_checkbox_<?=$arAnswer["QUESTION_ID"]?>[]" value="<?=$arAnswer["ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?> />&nbsp;<?=$arAnswer["MESSAGE"]?></label>
					<br />
				<?php break?>

				<?php case 2://dropdown?>
					<select name="vote_dropdown_<?=$arAnswer["QUESTION_ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?>>
						<option value=""><?=GetMessage("VOTE_DROPDOWN_SET")?></option>
					<?php foreach ($arAnswer["DROPDOWN"] as $arDropDown):?>
						<option value="<?=$arDropDown["ID"]?>" <?=($arDropDown["ID"] === $value)?'selected="selected"':''?>><?=$arDropDown["MESSAGE"]?></option>
					<?php endforeach?>
					</select><br />
				<?php break?>

				<?php case 3://multiselect?>
					<select name="vote_multiselect_<?=$arAnswer["QUESTION_ID"]?>[]" <?=$arAnswer["~FIELD_PARAM"]?> multiple="multiple">
					<?php foreach ($arAnswer["MULTISELECT"] as $arMultiSelect):?>
						<option value="<?=$arMultiSelect["ID"]?>" <?=(array_search($arMultiSelect["ID"], $value)!==false)?'selected="selected"':''?>><?=$arMultiSelect["MESSAGE"]?></option>
					<?php endforeach?>
					</select><br />
				<?php break?>

				<?php case 4://text field?>
					<label><?php if (strlen(trim($arAnswer["MESSAGE"]))>0):?>
						<?=$arAnswer["MESSAGE"]?><br />
					<?php endif?>
					<input type="text" name="vote_field_<?=$arAnswer["ID"]?>" value="<?=$value?>" size="<?=$arAnswer["FIELD_WIDTH"]?>" <?=$arAnswer["~FIELD_PARAM"]?> /></label>
					<br />
				<?php break?>

				<?php case 5://memo?>
					<label><?php if (strlen(trim($arAnswer["MESSAGE"]))>0):?>
						<?=$arAnswer["MESSAGE"]?><br />
					<?php endif?>
					<textarea name="vote_memo_<?=$arAnswer["ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?> cols="<?=$arAnswer["FIELD_WIDTH"]?>" rows="<?=$arAnswer["FIELD_HEIGHT"]?>"><?=$value?></textarea></label>
					<br />
				<?php break?>

			<?php endswitch?>

		<?php endforeach?>
		<br />
	<?php endforeach?>

	<?php  if (isset($arResult["CAPTCHA_CODE"])):  ?>
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
	<?php  endif // CAPTCHA_CODE ?>

	<input type="submit" name="vote" value="<?=GetMessage("VOTE_SUBMIT_BUTTON")?>">&nbsp;&nbsp;

	</form>

</div>

<?php endif?>
