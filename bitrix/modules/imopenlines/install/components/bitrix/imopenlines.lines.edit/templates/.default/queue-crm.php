<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Localization\Loc;

use \Bitrix\ImOpenLines\Config;

if ($arResult['CAN_EDIT'])
{
	CJSCore::Init(array('avatar_editor'));
	?>
	<script type="text/javascript">
		BX.ready(function(){
			BX.message({
				LM_ADD1 : '<?=GetMessageJS("IMOL_CONFIG_EDIT_LM_ADD1")?>',
				LM_ADD2 : '<?=GetMessageJS("IMOL_CONFIG_EDIT_LM_ADD2")?>',
				LM_ERROR_BUSINESS: '<?=GetMessageJS("IMOL_CONFIG_EDIT_LM_ERROR_BUSINESS_NEW")?>',
				'LM_BUSINESS_USERS': '<?=CUtil::JSEscape($arResult['BUSINESS_USERS'])?>',
				'LM_BUSINESS_USERS_ON': '<?=CUtil::JSEscape($arResult['BUSINESS_USERS_LIMIT'])?>',
				'LM_BUSINESS_USERS_TEXT': "<?=GetMessageJS("IMOL_CONFIG_EDIT_POPUP_LIMITED_BUSINESS_USERS_TEXT_NEW")?>",
				'LM_QUEUE_USER_NAME': '<?=GetMessageJS("IMOL_CONFIG_QUEUE_USER_NAME_PLACEHOLDER")?>',
				'LM_QUEUE_USER_WORK_POSITION': '<?=GetMessageJS("IMOL_CONFIG_QUEUE_USER_WORK_POSITION_PLACEHOLDER")?>',
			});
			BX.OpenLinesConfigEdit.initDestination(
				{
					destInputNode: BX('users_for_queue'),
					userDataInputNode: BX('users_for_queue_data'),
					defaultUserDataInputNode: BX('default_user_data')
				},
				{
					destInputName: 'QUEUE',
					userDataInputName: 'QUEUE_USERS_FIELDS'
				},
				{
					queue: <?=CUtil::PhpToJSObject($arResult["QUEUE_DESTINATION"])?>,
					defaultOperator: <?=CUtil::PhpToJSObject($arResult["CONFIG"]["DEFAULT_OPERATOR_DATA"])?>,
				}
			);
		});
	</script>
	<div style="display: none">
		<script type="text/html" id="dest_input_template">
			<?=getImolDestInputElement()?>
		</script>
		<script type="text/html" id="user_data_input_template">
			<?=getImolUserDataInputElement()?>
		</script>
		<script type="text/html" id="default_user_data_input_template">
			<?=getImolDefaultUserDataInputElement()?>
		</script>
		<script type="text/html" id="user_data_avatar_template">
			<?=getImolUserDataAvatarTemplate()?>
		</script>
		<div id="imol_user_data_avatar_upload" class="imopenlines-user-photo-upload">
			<div class="imopenlines-user-photo-upload-container">
				<div class="imopenlines-user-photo-upload-item-container-title">
					<span><?=Loc::getMessage('IMOL_CONFIG_AVATAR_LOADED')?></span>
				</div>
				<div class="imopenlines-user-photo-upload-item-added-btn-container">
					<span data-imopenlines-user-photo-edit="" class="imopenlines-user-photo-upload-item-added-btn">
						<span class="imopenlines-user-photo-upload-item-added-btn-inner"></span>
						<span class="imopenlines-user-photo-upload-item-added-btn-inner-bg"></span>
					</span>
					<div class="imopenlines-user-photo-upload-item-added-completed-container">
						<div class="imopenlines-user-photo-upload-item-added-completed-slider-container">
							<div data-imopenlines-user-photo-edit-avatars="" class="imopenlines-user-photo-upload-item-added-completed-block">
								<?php /*foreach($arResult['AVATARS'] as $icon):
									echo str_replace(
										array(
											'%file_id%',
											'%path%',
										),
										array(
											htmlspecialcharsbx($icon['ID']),
											htmlspecialcharsbx($icon['PATH'])
										),
										getCrmButtonEditTemplateAvatar()
									);
								endforeach;*/?>
							</div>
						</div>
						<?php /*<div data-imopenlines-user-photo-edit-avatar-prev="" class="imopenlines-user-photo-upload-item-added-completed-block-control-left"></div>
						<div data-imopenlines-user-photo-edit-avatar-next="" class="imopenlines-user-photo-upload-item-added-completed-block-control-right"></div>*/?>
					</div>

					<div style="clear: both;"></div>
				</div>
			</div>

			<div class="imopenlines-user-photo-uploaded-container">
				<div class="imopenlines-user-photo-uploaded-item-container-title">
					<span><?=Loc::getMessage('IMOL_CONFIG_AVATAR_PRESET')?></span>
				</div>
				<?php foreach($arResult['HELLO']['ICONS'] as $icon):?>
					<span data-imopenlines-user-photo-edit-avatar-item=""
						  data-file-id=""
						  data-path="<?=htmlspecialcharsbx($icon['PATH'])?>"
						  class="imopenlines-user-photo-upload-item-container">
						<span style="background-image: url(<?=htmlspecialcharsbx($icon['PATH'])?>)"
							  class="imopenlines-user-photo-upload-item"></span>
						<span class="imopenlines-user-photo-upload-item-selected"></span>
					</span>
				<?php endforeach;?>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
	<?php 
}
?>
<div class="imopenlines-form-settings-section">
	<div class="imopenlines-form-settings-block">
		<div class="imopenlines-form-settings-inner">
			<div class="imopenlines-control-subtitle">
				<?=Loc::getMessage("IMOL_CONFIG_EDIT_RESPONSIBLE_USERS_QUEUE")?>
				<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_DESC"))?>"></span>
			</div>
			<div class="imopenlines-user-list-input" id="users_for_queue">
				<?php 
				if ($arResult["HTML"]["DEST_INPUT_ELEMENTS"] != '')
				{
					echo $arResult["HTML"]["DEST_INPUT_ELEMENTS"];
				}
				?>
			</div>
		</div>
		<div class="imopenlines-form-settings-inner">
			<div class="imopenlines-control-container imopenlines-control-select">
				<div class="imopenlines-control-subtitle">
					<?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TYPE")?>
					<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage('IMOL_CONFIG_EDIT_QUEUE_TYPE_TIP_ALL_NEW'))?>"></span>
				</div>
				<div class="imopenlines-control-inner">
					<select name="CONFIG[QUEUE_TYPE]" id="imol_queue_type" class="imopenlines-control-input">
						<option value="evenly" <?php if($arResult["CONFIG"]["QUEUE_TYPE"] == Config::QUEUE_TYPE_EVENLY) { ?>selected<?php  }?>>
							<?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TYPE_EVENLY")?>
						</option>
						<option value="strictly" <?php if($arResult["CONFIG"]["QUEUE_TYPE"] == Config::QUEUE_TYPE_STRICTLY) { ?>selected<?php  }?>>
							<?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TYPE_STRICTLY")?>
						</option>
						<option value="all" <?php if($arResult["CONFIG"]["QUEUE_TYPE"] == Config::QUEUE_TYPE_ALL) { ?>selected<?php  }?>
								<?php if(!\Bitrix\Imopenlines\Limit::canUseQueueAll()) { ?>disabled<?php  }?>>
							<?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TYPE_ALL")?>
						</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="imopenlines-form-settings-block">
		<div class="imopenlines-form-settings-inner">
			<?php /*<div class="imopenlines-control-checkbox-container">
				<label class="imopenlines-control-checkbox-label">
					<input type="checkbox"
						   name="SHOW_WORKERS_TIME"
						   value="Y"
						   id="imol_workers_time_link"
						   class="imopenlines-control-checkbox"
						   <?php  if($arResult['SHOW_WORKERS_TIME']) { ?>checked<?php  } ?>>
					<?= ($arResult["CONFIG"]["QUEUE_TYPE"] == Config::QUEUE_TYPE_ALL) ? Loc::getMessage('IMOL_CONFIG_EDIT_NA_TIME_NEW') : Loc::getMessage('IMOL_CONFIG_EDIT_QUEUE_TIME')?>
				</label>
			</div>*/?>
			<div class="imopenlines-control-container imopenlines-control-select  <?php  if($arResult["VISIBLE"]["QUEUE_TIME"] == false) { ?>invisible<?php  } ?>" id="imol_workers_time_block">
				<div class="imopenlines-control-subtitle" id="imol_queue_time_title">
					<?=Loc::getMessage('IMOL_CONFIG_EDIT_QUEUE_TIME_NEW')?>
				</div>
				<div class="imopenlines-control-inner">
					<select class="imopenlines-control-input" name="CONFIG[QUEUE_TIME]">
						<option value="60" <?php if($arResult["CONFIG"]["QUEUE_TIME"] == "60") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_1")?></option>
						<option value="180" <?php if($arResult["CONFIG"]["QUEUE_TIME"] == "180") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_3")?></option>
						<option value="300" <?php if($arResult["CONFIG"]["QUEUE_TIME"] == "300") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_5")?></option>
						<option value="600" <?php if($arResult["CONFIG"]["QUEUE_TIME"] == "600") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_10")?></option>
						<option value="900" <?php if($arResult["CONFIG"]["QUEUE_TIME"] == "900") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_15")?></option>
						<option value="1800" <?php if($arResult["CONFIG"]["QUEUE_TIME"] == "1800") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_30")?></option>

						<option value="3600" <?php if($arResult["CONFIG"]["QUEUE_TIME"] == "3600") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_60")?></option>
						<option value="7200" <?php if($arResult["CONFIG"]["QUEUE_TIME"] == "7200") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_120")?></option>
						<option value="10800" <?php if($arResult["CONFIG"]["QUEUE_TIME"] == "10800") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_180")?></option>
						<option value="21600" <?php if($arResult["CONFIG"]["QUEUE_TIME"] == "21600") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_360")?></option>
						<option value="28800" <?php if($arResult["CONFIG"]["QUEUE_TIME"] == "28800") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_480")?></option>
						<option value="43200" <?php if($arResult["CONFIG"]["QUEUE_TIME"] == "43200") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_QUEUE_TIME_720")?></option>
					</select>
				</div>
			</div>
			<div id="imol_check_available_block" class="imopenlines-control-checkbox-container">
				<label class="imopenlines-control-checkbox-label">
					<input id="imol_check_available"
						   type="checkbox"
						   name="CONFIG[CHECK_AVAILABLE]"
						   value="Y"
						   class="imopenlines-control-checkbox"
						   <?php if($arResult["CONFIG"]["CHECK_AVAILABLE"] == "Y") { ?>checked<?php  }?>>
					<?=Loc::getMessage("IMOL_CONFIG_EDIT_CHECK_OPERATOR_AVAILABLE")?>
					<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage('IMOL_CONFIG_EDIT_CHECK_OPERATOR_AVAILABLE_DESC'))?>"></span>
				</label>
			</div>


			<div id="imol_check_online_block" class="imopenlines-control-checkbox-container<?php if ($arResult['VISIBLE']['CHECK_ONLINE_BLOCK'] == false){?> invisible<?php }?>">
				<label class="imopenlines-control-checkbox-label">
					<input id="imol_check_online"
						   type="checkbox"
						   name="CONFIG[CHECK_ONLINE]"
						   value="Y"
						   class="imopenlines-control-checkbox"
						   <?php if($arResult["CONFIG"]["CHECK_ONLINE"] == "Y") { ?>checked<?php  }?>>
					<?=Loc::getMessage("IMOL_CONFIG_EDIT_CHECK_OPERATOR_ONLINE")?>
				</label>
			</div>
			<div id="imol_limitation_max_chat_block" <?php  if ($arResult['VISIBLE']['LIMITATION_MAX_CHAT'] == false) { ?>class="invisible"<?php  } ?>>
				<div class="imopenlines-control-checkbox-container">
					<label class="imopenlines-control-checkbox-label">
						<input id="imol_limitation_max_chat"
							   type="checkbox"
							   name="CONFIG[LIMITATION_MAX_CHAT]"
							   value="Y"
							   class="imopenlines-control-checkbox"
							   <?php if($arResult["CONFIG"]["MAX_CHAT"] > "0") { ?>checked<?php  }?>>
						<?=Loc::getMessage("IMOL_CONFIG_EDIT_LIMITATION_MAX_CHAT_TITLE_NEW")?>
						<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage('IMOL_CONFIG_EDIT_LIMITATION_MAX_CHAT_DESC'))?>"></span>
					</label>
				</div>
				<div <?php if($arResult['VISIBLE']['MAX_CHAT'] == false) {?>class="invisible"<?php }?> id="imol_max_chat">
					<div class="imopenlines-control-container imopenlines-control-select">
						<div class="imopenlines-control-subtitle">
							<?= Loc::getMessage('IMOL_CONFIG_EDIT_TYPE_MAX_CHAT_TITLE_NEW') ?>
							<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage('IMOL_CONFIG_EDIT_TYPE_MAX_CHAT_TIP_NEW'))?>"></span>
						</div>
						<div class="imopenlines-control-inner">
							<select class="imopenlines-control-input" name="CONFIG[TYPE_MAX_CHAT]">
								<option value="answered_new" <?php if($arResult["CONFIG"]["TYPE_MAX_CHAT"] == "answered_new" || empty($arResult["CONFIG"]["TYPE_MAX_CHAT"])) { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_TYPE_MAX_CHAT_OPTION_ANSWERED_NEW")?></option>
								<option value="answered" <?php if($arResult["CONFIG"]["TYPE_MAX_CHAT"] == "answered") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_TYPE_MAX_CHAT_OPTION_ANSWERED")?></option>
								<option value="closed" <?php if($arResult["CONFIG"]["TYPE_MAX_CHAT"] == "closed") { ?>selected<?php  }?>><?=Loc::getMessage("IMOL_CONFIG_EDIT_TYPE_MAX_CHAT_OPTION_CLOSED")?></option>
							</select>
						</div>
					</div>
					<div class="imopenlines-control-container">
						<div class="imopenlines-control-subtitle">
							<?= Loc::getMessage('IMOL_CONFIG_EDIT_MAX_CHAT_TITLE_NEW') ?>
						</div>
						<div class="imopenlines-control-inner width-80">
							<input type="text" name="CONFIG[MAX_CHAT]" class="imopenlines-control-input" value="<?=$arResult["CONFIG"]["MAX_CHAT"]?>">
						</div>
					</div>
				</div>
			</div>
			<?php 
			if(defined('IMOL_FDC'))
			{
				?>
				<div class="imopenlines-form-settings-inner">
					<div class="imopenlines-control-subtitle">
						<?=Loc::getMessage('IMOL_CONFIG_EDIT_LANG_SESSION_PRIORITY')?>
						<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage("IMOL_CONFIG_EDIT_LANG_SESSION_PRIORITY_TIP_NEW"))?>"></span>
					</div>
					<div class="imopenlines-control-inner width-80">
						<input type="number"
							   min="0"
							   max="86400"
							   class="imopenlines-control-input"
							   name="CONFIG[SESSION_PRIORITY]"
							   value="<?=htmlspecialcharsbx($arResult['CONFIG']['SESSION_PRIORITY'])?>">
					</div>
					<div class="imopenlines-control-subtitle"><?=Loc::getMessage('IMOL_CONFIG_EDIT_LANG_SESSION_PRIORITY_2')?></div>
				</div>
				<?php 
			}
			?>
		</div>
	</div>
</div>
<div class="imopenlines-form-settings-section">
	<div class="imopenlines-form-settings-block">
		<div class="imopenlines-form-settings-inner">
			<div class="imopenlines-control-container imopenlines-control-select">
				<div class="imopenlines-control-subtitle">
					<?=Loc::getMessage("IMOL_CONFIG_EDIT_OPERATOR_DATA")?>
					<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage('IMOL_CONFIG_EDIT_OPERATOR_DATA_TIP'))?>"></span>
				</div>
				<div class="imopenlines-control-inner">
					<select name="CONFIG[OPERATOR_DATA]" id="imol_operator_data" class="imopenlines-control-input">
						<option value="profile" <?php if($arResult["CONFIG"]["OPERATOR_DATA"] == "profile") { ?>selected<?php  }?>>
							<?=Loc::getMessage("IMOL_CONFIG_EDIT_OPERATOR_DATA_PROFILE")?>
						</option>
						<option value="queue" <?php if($arResult["CONFIG"]["OPERATOR_DATA"] == "queue") { ?>selected<?php  }?>>
							<?=Loc::getMessage("IMOL_CONFIG_EDIT_OPERATOR_DATA_QUEUE")?>
						</option>
						<option value="hide" <?php if($arResult["CONFIG"]["OPERATOR_DATA"] == "hide") { ?>selected<?php  }?>>
							<?=Loc::getMessage("IMOL_CONFIG_EDIT_OPERATOR_DATA_HIDE")?>
						</option>
					</select>
				</div>
			</div>

			<div id="users_for_queue_data" <?php if($arResult["CONFIG"]["OPERATOR_DATA"] != "queue") { ?>class="invisible"<?php  }?>>
				<?php 
				if ($arResult["HTML"]["USER_DATA_INPUT_ELEMENTS"] != '')
				{
					echo $arResult["HTML"]["USER_DATA_INPUT_ELEMENTS"];
				}
				?>
			</div>
			<div class="imopenlines-form-settings-user <?php if($arResult["CONFIG"]["OPERATOR_DATA"] != "hide") { ?>invisible<?php  }?>" id="default_user_data" data-id="default-user">
				<div class="imopenlines-form-settings-user-info">
					<span class="imopenlines-form-settings-user-name"><?=Loc::getMessage("IMOL_CONFIG_EDIT_DEFAULT_OPERATOR_DATA_TITLE")?></span>
					<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage("IMOL_CONFIG_EDIT_DEFAULT_OPERATOR_DATA_TIP"))?>"></span>
				</div>
				<?php 
				if ($arResult["HTML"]["DEFAULT_OPERATOR_DATA"] != '')
				{
					echo $arResult["HTML"]["DEFAULT_OPERATOR_DATA"];
				}
				?>
			</div>
		</div>
	</div>
	<div class="imopenlines-form-settings-block">
		<div class="imopenlines-form-settings-inner">
			<div class="imopenlines-control-checkbox-container">
				<label class="imopenlines-control-checkbox-label">
					<input type="checkbox"
						   name="CONFIG[CRM]"
						   value="Y"
						   id="imol_crm_checkbox"
						   class="imopenlines-control-checkbox"
						<?php  if ($arResult['CONFIG']['CRM'] == 'Y'  && $arResult['IS_CRM_INSTALLED'] == 'Y') { ?>
							checked
						<?php  } elseif($arResult['IS_CRM_INSTALLED'] != 'Y') { ?>
							disabled
						<?php  } ?>>
					<?=Loc::getMessage('IMOL_CONFIG_EDIT_CRM')?>
				</label>
			</div>
			<?php 
			if ($arResult['IS_CRM_INSTALLED'] != 'Y')
			{
				?>
				<div class="imopenlines-control-checkbox-container">
					<label class="imopenlines-control-checkbox-label">
						<?= Loc::getMessage('IMOL_CONFIG_EDIT_CRM_DISABLED') ?>
					</label>
				</div>
				<?php 
			}
			?>
			<div id="imol_crm_block" <?php if (!($arResult['CONFIG']['CRM'] == 'Y'  && $arResult['IS_CRM_INSTALLED'] == 'Y')){?>class="invisible"<?php }?>>
				<div class="imopenlines-control-container imopenlines-control-select">
					<div class="imopenlines-control-subtitle">
						<?=Loc::getMessage('IMOL_CONFIG_EDIT_CRM_CREATE')?>
						<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage('IMOL_CONFIG_EDIT_CRM_CREATE_LEAD_DESC'))?>"></span>
					</div>
					<div class="imopenlines-control-inner">
						<select name="CONFIG[CRM_CREATE]" id="imol_crm_create" class="imopenlines-control-input">
							<option value="none" <?php if($arResult["CONFIG"]["CRM_CREATE"] == "none") { ?>selected<?php  }?>>
								<?=Loc::getMessage("IMOL_CONFIG_EDIT_CRM_CREATE_IN_CHAT")?>
							</option>
							<option value="lead" <?php if($arResult["CONFIG"]["CRM_CREATE"] == "lead") { ?>selected<?php  }?>>
								<?=Loc::getMessage("IMOL_CONFIG_EDIT_CRM_CREATE_LEAD")?>
							</option>
						</select>
					</div>
				</div>
				<div class="imopenlines-control-container imopenlines-control-select" id="imol_crm_source">
					<div class="imopenlines-control-subtitle">
						<?=Loc::getMessage("IMOL_CONFIG_EDIT_CRM_SOURCE")?>
						<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage("IMOL_CONFIG_EDIT_CRM_SOURCE_TIP_NEW"))?>"></span>
					</div>
					<div class="imopenlines-control-inner">
						<select name="CONFIG[CRM_SOURCE]" id="imol_crm_source_select" class="imopenlines-control-input">
							<?php
							foreach ($arResult['CRM_SOURCES'] as $value => $name)
							{
								?>
								<option value="<?=$value?>" <?php if($arResult['CONFIG']['CRM_SOURCE'] === (string)$value) { ?>selected<?php  }?> >
									<?=htmlspecialcharsbx($name)?>
								</option>
								<?php 
							}
							?>
						</select>
					</div>
				</div>
				<div class="imopenlines-control-checkbox-container">
					<label class="imopenlines-control-checkbox-label">
						<input type="checkbox"
							   class="imopenlines-control-checkbox"
							   name="CONFIG[CRM_FORWARD]"
							   id="imol_crm_forward"
							   value="Y"
							   <?php if($arResult["CONFIG"]["CRM_FORWARD"] == "Y") { ?>checked<?php  }?>>
						<?=Loc::getMessage("IMOL_CONFIG_EDIT_CRM_FORWARD_NEW")?>
						<span data-hint="<?=htmlspecialcharsbx(Loc::getMessage("IMOL_CONFIG_EDIT_CRM_FORWARD_TIP"))?>"></span>
					</label>
				</div>
				<div class="imopenlines-control-checkbox-container <?=($arResult["CONFIG"]["CRM_CREATE"] != 'none' ? '' : 'invisible')?>"
					 id="imol_crm_source_rule">
					<label class="imopenlines-control-checkbox-label">
						<input type="checkbox"
							   class="imopenlines-control-checkbox"
							   name="CONFIG[CRM_TRANSFER_CHANGE]"
							   value="Y"
							   <?php if($arResult["CONFIG"]["CRM_TRANSFER_CHANGE"] == "Y") { ?>checked<?php  }?>  >
						<?=Loc::getMessage("IMOL_CONFIG_EDIT_CRM_TRANSFER_CHANGE")?>
					</label>
				</div>
			</div>
		</div>
	</div>
</div>
