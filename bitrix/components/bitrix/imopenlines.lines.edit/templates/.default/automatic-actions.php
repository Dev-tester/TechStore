<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Localization\Loc; ?>

<div class="imopenlines-form-settings-section">
	<div class="imopenlines-form-settings-block">
		<div class="imopenlines-control-checkbox-container">
			<label class="imopenlines-control-checkbox-label">
				<input type="checkbox"
					   id="imol_welcome_message"
					   name="CONFIG[WELCOME_MESSAGE]"
					   value="Y"
					   class="imopenlines-control-checkbox"
					   <?php  if ($arResult['CONFIG']['WELCOME_MESSAGE'] == "Y") { ?>checked<?php  } ?>>
				<?=Loc::getMessage('IMOL_CONFIG_EDIT_WELCOME_MESSAGE_NEW')?>
				<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage("IMOL_CONFIG_EDIT_WELCOME_MESSAGE_NEW_TIP"))?>"></span>
			</label>
		</div>
		<div class="imopenlines-control-container <?php  if ($arResult['CONFIG']['WELCOME_MESSAGE'] != 'Y') { ?>invisible<?php  } ?>" id="imol_action_welcome">
			<div class="imopenlines-control-subtitle"><?=Loc::getMessage("IMOL_CONFIG_EDIT_WELCOME_MESSAGE_NEW_TEXT")?></div>
			<div class="imopenlines-control-inner">
				<textarea type="text" class="imopenlines-control-input imopenlines-control-textarea"
				name="CONFIG[WELCOME_MESSAGE_TEXT]"><?=htmlspecialcharsbx($arResult["CONFIG"]["WELCOME_MESSAGE_TEXT"])?></textarea>
			</div>
		</div>

		<div class="imopenlines-control-container imopenlines-control-select">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage('IMOL_CONFIG_EDIT_NA_TIME_NEW')?>
			</div>
			<div class="imopenlines-control-inner">
				<select class="imopenlines-control-input" name="CONFIG[NO_ANSWER_TIME]">
					<option value="60" <?php if($arResult["CONFIG"]["NO_ANSWER_TIME"] == "60") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_1")?></option>
					<option value="180" <?php if($arResult["CONFIG"]["NO_ANSWER_TIME"] == "180") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_3")?></option>
					<option value="300" <?php if($arResult["CONFIG"]["NO_ANSWER_TIME"] == "300") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_5")?></option>
					<option value="600" <?php if($arResult["CONFIG"]["NO_ANSWER_TIME"] == "600") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_10")?></option>
					<option value="900" <?php if($arResult["CONFIG"]["NO_ANSWER_TIME"] == "900") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_15")?></option>
					<option value="1800" <?php if($arResult["CONFIG"]["NO_ANSWER_TIME"] == "1800") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_30")?></option>

					<option value="3600" <?php if($arResult["CONFIG"]["NO_ANSWER_TIME"] == "3600") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_60")?></option>
					<option value="7200" <?php if($arResult["CONFIG"]["NO_ANSWER_TIME"] == "7200") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_120")?></option>
					<option value="10800" <?php if($arResult["CONFIG"]["NO_ANSWER_TIME"] == "10800") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_180")?></option>
					<option value="21600" <?php if($arResult["CONFIG"]["NO_ANSWER_TIME"] == "21600") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_360")?></option>
					<option value="28800" <?php if($arResult["CONFIG"]["NO_ANSWER_TIME"] == "28800") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_480")?></option>
					<option value="43200" <?php if($arResult["CONFIG"]["NO_ANSWER_TIME"] == "43200") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_720")?></option>
				</select>
			</div>
		</div>

		<div class="imopenlines-control-container imopenlines-control-select">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage('IMOL_CONFIG_NO_ANSWER_RULE')?>
				<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage('IMOL_CONFIG_NO_ANSWER_DESC_NEW'))?>"></span>
			</div>
			<div class="imopenlines-control-inner">
				<select name="CONFIG[NO_ANSWER_RULE]" id="imol_no_answer_rule" class="imopenlines-control-input">
					<?php 
					foreach ($arResult["NO_ANSWER_RULES"] as $value => $name)
					{
						?>
						<option value="<?=$value?>" <?php if($arResult["CONFIG"]["NO_ANSWER_RULE"] == $value) { ?>selected<?php  }?> <?php if($value == 'disabled') { ?>disabled<?php  }?>>
							<?=$name?>
						</option>
						<?php 
					}
					?>
				</select>
			</div>
		</div>

		<div class="imopenlines-control-container imopenlines-control-select" id="imol_no_answer_rule_form_form">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage("IMOL_CONFIG_NO_ANSWER_FORM_ID")?>
				<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage('IMOL_CONFIG_NO_ANSWER_FORM_TEXT'))?>"></span>
			</div>
			<div class="imopenlines-control-inner">
				<select name="CONFIG[NO_ANSWER_FORM_ID]" class="imopenlines-control-input">
					<?php 
					foreach ($arResult["NO_ANSWER_RULES"] as $value => $name)
					{
						?>
						<option value="<?=$value?>" <?php if($arResult["CONFIG"]["NO_ANSWER_RULE"] == $value) { ?>selected<?php  }?> <?php if($value == 'disabled') { ?>disabled<?php  }?>>
							<?=$name?>
						</option>
						<?php 
					}
					?>
				</select>
			</div>
		</div>
		<div class="imopenlines-control-container" id="imol_no_answer_rule_text">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage("IMOL_CONFIG_NO_ANSWER_TEXT")?>
			</div>
			<div class="imopenlines-control-inner">
				<textarea type="text"
						  name="CONFIG[NO_ANSWER_TEXT]"
						  class="imopenlines-control-input imopenlines-control-textarea"><?=htmlspecialcharsbx($arResult["CONFIG"]["NO_ANSWER_TEXT"])?></textarea>
			</div>
		</div>
		<div class="imopenlines-control-container imopenlines-control-select">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage('IMOL_CONFIG_EDIT_CLOSE_ACTION_NEW')?>
			</div>
			<div class="imopenlines-control-inner">
				<select name="CONFIG[CLOSE_RULE]" id="imol_action_close" class="imopenlines-control-input">
					<?php 
					foreach($arResult["CLOSE_RULES"] as $value=>$name)
					{
						?>
						<option value="<?=$value?>"
								<?php if($arResult["CONFIG"]["CLOSE_RULE"] == $value) { ?>selected<?php  }?>
								<?php if($value == 'disabled') { ?>disabled<?php  }?>>
							<?=$name?>
						</option>
						<?php 
					}
					?>
				</select>
			</div>
		</div>
		<div class="imopenlines-control-container ui-control-select" id="imol_action_close_form">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage('IMOL_CONFIG_EDIT_CLOSE_FORM_ID')?>
			</div>
			<div class="imopenlines-control-inner">
				<select class="imopenlines-control-input" name="CONFIG[CLOSE_FORM_ID]"></select>
			</div>
		</div>
		<div class="imopenlines-control-container imopenlines-control-block" id="imol_action_close_text">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage('IMOL_CONFIG_EDIT_CLOSE_TEXT_NEW')?>
			</div>
			<div class="imopenlines-control-inner">
				<textarea class="imopenlines-control-input imopenlines-control-textarea"
						  name="CONFIG[CLOSE_TEXT]"><?=htmlspecialcharsbx($arResult["CONFIG"]["CLOSE_TEXT"])?></textarea>
			</div>
		</div>
		<div class="imopenlines-control-container imopenlines-control-select">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage('IMOL_CONFIG_EDIT_FULL_CLOSE_TIME_NEW')?>
				<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage('IMOL_CONFIG_FULL_CLOSE_TIME_DESC_NEW'))?>"></span>
			</div>
			<div class="imopenlines-control-inner">
				<select name="CONFIG[FULL_CLOSE_TIME]" class="imopenlines-control-input">
					<option value="0" <?php if($arResult["CONFIG"]["FULL_CLOSE_TIME"] == "0") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_FULL_CLOSE_TIME_0")?></option>
					<option value="1" <?php if($arResult["CONFIG"]["FULL_CLOSE_TIME"] == "1") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_FULL_CLOSE_TIME_1")?></option>
					<option value="2" <?php if($arResult["CONFIG"]["FULL_CLOSE_TIME"] == "2") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_FULL_CLOSE_TIME_2")?></option>
					<option value="5" <?php if($arResult["CONFIG"]["FULL_CLOSE_TIME"] == "5") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_FULL_CLOSE_TIME_5")?></option>
					<option value="10" <?php if($arResult["CONFIG"]["FULL_CLOSE_TIME"] == "10" || !isset($arResult["CONFIG"]["FULL_CLOSE_TIME"])) { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_FULL_CLOSE_TIME_10")?></option>
					<option value="30" <?php if($arResult["CONFIG"]["FULL_CLOSE_TIME"] == "30") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_FULL_CLOSE_TIME_30")?></option>
					<option value="60" <?php if($arResult["CONFIG"]["FULL_CLOSE_TIME"] == "60") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_FULL_CLOSE_TIME_60")?></option>
				</select>
			</div>
		</div>
		<div class="imopenlines-control-container imopenlines-control-select" id="imol_queue_time">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage('IMOL_CONFIG_EDIT_AUTO_CLOSE_TIME')?>
			</div>
			<div class="imopenlines-control-inner">
				<select class="imopenlines-control-input" name="CONFIG[AUTO_CLOSE_TIME]">
					<option value="3600" <?php if($arResult["CONFIG"]["AUTO_CLOSE_TIME"] == "3600") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_AUTO_CLOSE_TIME_1_H")?></option>
					<option value="14400" <?php if($arResult["CONFIG"]["AUTO_CLOSE_TIME"] == "14400") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_AUTO_CLOSE_TIME_4_H")?></option>
					<option value="28800" <?php if($arResult["CONFIG"]["AUTO_CLOSE_TIME"] == "28800") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_AUTO_CLOSE_TIME_8_H")?></option>
					<option value="86400" <?php if($arResult["CONFIG"]["AUTO_CLOSE_TIME"] == "86400") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_AUTO_CLOSE_TIME_1_D")?></option>
					<option value="172800" <?php if($arResult["CONFIG"]["AUTO_CLOSE_TIME"] == "172800") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_AUTO_CLOSE_TIME_2_D")?></option>
					<option value="604800" <?php if($arResult["CONFIG"]["AUTO_CLOSE_TIME"] == "604800") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_AUTO_CLOSE_TIME_1_W")?></option>
					<option value="2678400" <?php if($arResult["CONFIG"]["AUTO_CLOSE_TIME"] == "2678400") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_AUTO_CLOSE_TIME_1_M")?></option>
				</select>
			</div>
		</div>
		<div class="imopenlines-control-container imopenlines-control-select">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage('IMOL_CONFIG_EDIT_AUTO_CLOSE_RULE')?>
			</div>
			<div class="imopenlines-control-inner">
				<select name="CONFIG[AUTO_CLOSE_RULE]" id="imol_action_auto_close" class="imopenlines-control-input">
					<?php 
					foreach($arResult["CLOSE_RULES"] as $value=>$name)
					{
						?>
						<option value="<?=$value?>" <?php if($arResult["CONFIG"]["AUTO_CLOSE_RULE"] == $value) { ?>selected<?php  }?> <?php if($value == 'disabled') { ?>disabled<?php  }?>>
							<?=$name?>
						</option>
						<?php 
					}
					?>
				</select>
			</div>
		</div>
		<div class="imopenlines-control-container ui-control-select"
			 id="imol_action_auto_close_form">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage('IMOL_CONFIG_EDIT_AUTO_CLOSE_FORM_ID')?>
			</div>
			<div class="imopenlines-control-inner">
				<select class="imopenlines-control-input" name="CONFIG[AUTO_CLOSE_FORM_ID]"></select>
			</div>
		</div>
		<div class="imopenlines-control-container imopenlines-control-block"
			 id="imol_action_auto_close_text">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage('IMOL_CONFIG_EDIT_AUTO_CLOSE_TEXT_NEW')?>
			</div>
			<div class="imopenlines-control-inner">
				<textarea class="imopenlines-control-input imopenlines-control-textarea"
						  name="CONFIG[AUTO_CLOSE_TEXT]"><?=htmlspecialcharsbx($arResult["CONFIG"]["AUTO_CLOSE_TEXT"])?></textarea>
			</div>
		</div>
	</div>
	<div class="imopenlines-form-settings-block">
		<div class="imopenlines-control-container imopenlines-control-select">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage("IMOL_CONFIG_EDIT_QUICK_ANSWERS_STORAGE")?>
			</div>
			<div class="imopenlines-control-inner">
				<select name="CONFIG[QUICK_ANSWERS_IBLOCK_ID]" class="imopenlines-control-input">
					<?php 
					foreach($arResult['QUICK_ANSWERS_STORAGE_LIST'] as $id => $item)
					{
						?>
						<option value="<?=intval($id);?>"<?php if($id == $arResult['CONFIG']['QUICK_ANSWERS_IBLOCK_ID']){?> selected<?php }?>>
							<?=htmlspecialcharsbx($item['NAME']);?>
						</option>
						<?php 
					}
					?>
				</select>
				<div class="ui-btn ui-btn-light-border" id="imol_quick_answer_manage" data-url="<?=$arResult['QUICK_ANSWERS_MANAGE_URL']?>">
					<?php 
					$code = ($arResult['CONFIG']['QUICK_ANSWERS_IBLOCK_ID'] > 0 ? 'IMOL_CONFIG_QUICK_ANSWERS_CONFIG' : 'IMOL_CONFIG_QUICK_ANSWERS_CREATE');
					echo Loc::getMessage($code);
					?>
				</div>
			</div>
			<div class="imopenlines-control-subtitle imopenlines-control-subtitle-answer">
				<?=Loc::getMessage("IMOL_CONFIG_QUICK_ANSWERS_DESC_NEW")?>
			</div>
		</div>
	</div>
</div>
