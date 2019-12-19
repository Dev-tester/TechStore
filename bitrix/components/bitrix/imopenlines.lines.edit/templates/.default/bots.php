<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Localization\Loc;
\CJSCore::Init(array('marketplace'));
?>
<div class="imopenlines-form-settings-section">
	<div class="imopenlines-form-settings-title imopenlines-form-settings-title-other">
		<?=Loc::getMessage('IMOL_CONFIG_EDIT_BOT_SETTINGS')?>
		<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage("IMOL_CONFIG_EDIT_BOT_JOIN_TIP_NEW"))?>"></span>
	</div>
	<div class="imopenlines-control-container">
		<div class="imopenlines-control-checkbox-container">
			<label class="imopenlines-control-checkbox-label">
				<input id="imol_welcome_bot"
					   type="checkbox"
					   class="imopenlines-control-checkbox"
					   name="CONFIG[WELCOME_BOT_ENABLE]"
					   value="Y"
					   <?php if($arResult["CONFIG"]["WELCOME_BOT_ENABLE"] == "Y") { ?>checked<?php  }?>>
				<?=Loc::getMessage("IMOL_CONFIG_EDIT_BOT_JOIN_NEW")?>
			</label>
		</div>
		<div id="imol_welcome_bot_block" <?php  if($arResult["CONFIG"]["WELCOME_BOT_ENABLE"] != "Y") {?>class="invisible"<?php }?>>
			<div class="imopenlines-control-container imopenlines-control-select">
				<div class="imopenlines-control-subtitle">
					<?=Loc::getMessage("IMOL_CONFIG_EDIT_BOT_ID")?>
				</div>
				<div class="imopenlines-control-inner">
					<select name="CONFIG[WELCOME_BOT_ID]" id="WELCOME_BOT_ID" class="imopenlines-control-input">
						<?php 
						foreach ($arResult['BOT_LIST'] as $value => $name)
						{
							?>
							<option value="<?=$value?>" <?php if($arResult["CONFIG"]["WELCOME_BOT_ID"] == $value) { ?>selected<?php  }?> ><?=htmlspecialcharsbx($name)?></option>
							<?php 
						}
						?>
					</select>
				</div>
			</div>
			<div class="imopenlines-control-container imopenlines-control-select">
				<div class="imopenlines-control-subtitle">
					<?=Loc::getMessage("IMOL_CONFIG_EDIT_WELCOME_BOT_JOIN")?>
				</div>
				<div class="imopenlines-control-inner">
					<select name="CONFIG[WELCOME_BOT_JOIN]" class="imopenlines-control-input">
						<option value="first"
								<?php if($arResult["CONFIG"]["WELCOME_BOT_JOIN"] == "first") { ?>selected<?php  }?>>
							<?=Loc::getMessage("IMOL_CONFIG_EDIT_WELCOME_BOT_JOIN_FIRST")?>
						</option>
						<option value="always"
								<?php if($arResult["CONFIG"]["WELCOME_BOT_JOIN"] == "always") { ?>selected<?php  }?>>
							<?=Loc::getMessage("IMOL_CONFIG_EDIT_WELCOME_BOT_JOIN_ALWAYS")?>
						</option>
					</select>
				</div>
			</div>
			<div class="imopenlines-control-container imopenlines-control-select">
				<div class="imopenlines-control-subtitle">
					<?=Loc::getMessage("IMOL_CONFIG_EDIT_BOT_TIME_NEW")?>
					<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage("IMOL_CONFIG_EDIT_BOT_TIME_TIP"))?>"></span>
				</div>
				<div class="imopenlines-control-inner">
					<select name="CONFIG[WELCOME_BOT_TIME]" class="imopenlines-control-input">
						<option value="60" <?php if($arResult["CONFIG"]["WELCOME_BOT_TIME"] == "60") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_1")?></option>
						<option value="180" <?php if($arResult["CONFIG"]["WELCOME_BOT_TIME"] == "180") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_3")?></option>
						<option value="300" <?php if($arResult["CONFIG"]["WELCOME_BOT_TIME"] == "300") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_5")?></option>
						<option value="600" <?php if($arResult["CONFIG"]["WELCOME_BOT_TIME"] == "600") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_10")?></option>
						<option value="900" <?php if($arResult["CONFIG"]["WELCOME_BOT_TIME"] == "900") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_15")?></option>
						<option value="1800" <?php if($arResult["CONFIG"]["WELCOME_BOT_TIME"] == "1800") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_30")?></option>
						<option value="0" <?php if($arResult["CONFIG"]["WELCOME_BOT_TIME"] == "0") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_0")?></option>
					</select>
				</div>
			</div>
			<div class="imopenlines-control-container imopenlines-control-select">
				<div class="imopenlines-control-subtitle">
					<?=Loc::getMessage("IMOL_CONFIG_EDIT_WELCOME_BOT_LEFT")?>
				</div>
				<div class="imopenlines-control-inner">
					<select name="CONFIG[WELCOME_BOT_LEFT]" class="imopenlines-control-input">
						<option value="queue" <?php if($arResult["CONFIG"]["WELCOME_BOT_LEFT"] == "queue") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_WELCOME_BOT_LEFT_QUEUE_NEW")?></option>
						<option value="close" <?php if($arResult["CONFIG"]["WELCOME_BOT_LEFT"] == "close") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_WELCOME_BOT_LEFT_CLOSE")?></option>
					</select>
				</div>
			</div>
		</div>
		<?php 
		if($arResult['CAN_INSTALL_APPLICATIONS'])
		{
			?>
			<div class="imopenlines-control-checkbox-container">
				<a href="#" class="imopenlines-control-link" id="imopenlines-bot-link">
					<?=Loc::getMessage('IMOL_CONFIG_ADD_BOT')?>
				</a>
			</div>
			<?php 
		}
		?>
	</div>
</div>

<script type="text/javascript">
	BX.ready(function () {
		BX.bind(BX('imol_welcome_bot'), 'change', function(e){
			<?php 
			if(empty($arResult['BOT_LIST']))
			{
			?>
			BX('imol_welcome_bot').checked = false;
			alert('<?=GetMessageJS("IMOL_CONFIG_EDIT_BOT_EMPTY_NEW")?>');
			<?php 
			}
			else
			{
			?>
			BX.OpenLinesConfigEdit.toggleBotBlock('imol_welcome_bot_block');
			<?php 
			}
			?>
		});
		<?php 
		if($arResult['CAN_INSTALL_APPLICATIONS'])
		{
			?>
			BX.bind(
				BX('imopenlines-bot-link'),
				'click',
				BX.OpenLinesConfigEdit.botButtonAction
			);
			<?php 
		}
		?>
	});
</script>
