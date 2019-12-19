<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Bitrix24\Feature;
?>
<?php CJSCore::Init(array("access"));?>

<?php if(isset($_GET['success'])): ?>
	<div class="content-edit-form-notice-successfully">
		<span class="content-edit-form-notice-text"><span class="content-edit-form-notice-icon"></span><?=GetMessage('CONFIG_SAVE_SUCCESSFULLY')?></span>
	</div>
<?php endif;?>
<div class="content-edit-form-notice-error" <?php if (!$arResult["ERROR"]):?>style="display: none;"<?php endif?> id="config_error_block">
	<span class="content-edit-form-notice-text"><span class="content-edit-form-notice-icon"></span><?=$arResult["ERROR"]?></span>
</div>

<form name="configPostForm" id="configPostForm" method="POST" action="<?=POST_FORM_ACTION_URI?>" enctype="multipart/form-data">
	<input type="hidden" name="save_settings" value="true" >
	<?=bitrix_sessid_post();?>

	<table id="content-edit-form-config" class="content-edit-form" cellspacing="0" cellpadding="0">
		<tr>
			<td class="content-edit-form-header content-edit-form-header-first" colspan="3" >
				<div class="content-edit-form-header-wrap content-edit-form-header-wrap-blue"><?=GetMessage('CONFIG_HEADER_SETTINGS')?></div>
			</td>
		</tr>

		<?php if ($arResult["IS_BITRIX24"]):?>
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_COMPANY_NAME')?></td>
			<td class="content-edit-form-field-input"><input type="text" name="logo_name" value="<?=htmlspecialcharsbx(COption::GetOptionString("main", "site_name", ""));?>"  class="content-edit-form-field-input-text"/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<?php endif?>

		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_COMPANY_TITLE_NAME')?></td>
			<td class="content-edit-form-field-input"><input type="text" name="site_title" value="<?=htmlspecialcharsbx(COption::GetOptionString("bitrix24", "site_title", ""));?>"  class="content-edit-form-field-input-text"/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>

		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('config_rating_label_likeY')?></td>
			<td class="content-edit-form-field-input"><input type="text" name="rating_text_like_y" value="<?=htmlspecialcharsbx(COption::GetOptionString("main", "rating_text_like_y", ""));?>"  class="content-edit-form-field-input-text"/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<!--
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('config_rating_label_likeN')?></td>
			<td class="content-edit-form-field-input"><input type="text" name="rating_text_like_n" value="<?=htmlspecialcharsbx(COption::GetOptionString("main", "rating_text_like_n", ""));?>"  class="content-edit-form-field-input-text"/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		-->

		<?php if ($arResult["IS_BITRIX24"]):?>
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_EMAIL_FROM')?></td>
			<td class="content-edit-form-field-input"><input type="text" name="email_from" value="<?=htmlspecialcharsbx(COption::GetOptionString("main", "email_from", ""));?>"  class="content-edit-form-field-input-text"/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<?php endif?>

		<?php if (
			$arResult["IS_BITRIX24"] && \Bitrix\Bitrix24\Feature::isFeatureEnabled("remove_logo24")
			|| !$arResult["IS_BITRIX24"]
		):
			$logo24show = COption::GetOptionString("bitrix24", "logo24show", "Y");
		?>
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="logo24"><?=GetMessage('CONFIG_LOGO_24')?></label></td>
			<td class="content-edit-form-field-input"><input type="checkbox" id="logo24" name="logo24" <?php if ($logo24show == "" || $logo24show == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<?php endif?>


		<tr data-field-id="congig_date">
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_DATE_FORMAT')?></td>
			<td class="content-edit-form-field-input">
				<select name="date_format">
					<?php foreach($arResult["DATE_FORMATS"] as $format):?>
					<option value="<?=$format?>" <?php if ($format == $arResult["CUR_DATE_FORMAT"]) echo "selected"?>><?=$format?></option>
					<?php endforeach?>
				</select>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>

		<tr data-field-id="config_time">
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_TIME_FORMAT')?></td>
			<td class="content-edit-form-field-input">
				<input type="radio" id="12_format" name="time_format" value="H:MI:SS T" <?php if ($arResult["CUR_TIME_FORMAT"] == "H:MI:SS TT" || $arResult["CUR_TIME_FORMAT"] == "H:MI TT" || $arResult["CUR_TIME_FORMAT"] == "H:MI:SS T" || $arResult["CUR_TIME_FORMAT"] == "H:MI T") echo "checked"?>>
				<label for="12_format"><?=GetMessage("CONFIG_TIME_FORMAT_12")?></label>
				<br/>
				<input type="radio" id="24_format" name="time_format" value="HH:MI:SS" <?php if ($arResult["CUR_TIME_FORMAT"] == "HH:MI:SS") echo "checked"?>>
				<label for="24_format"><?=GetMessage("CONFIG_TIME_FORMAT_24")?></label>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>

		<tr data-field-id="config_time">
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_NAME_FORMAT')?></td>
			<td class="content-edit-form-field-input">
				<select name="" onchange="if(this.value != 'other'){this.form.FORMAT_NAME.value = this.value;this.form.FORMAT_NAME.style.display='none';} else {this.form.FORMAT_NAME.style.display='block';}">
					<?php 
					$formatExists = false;
					foreach ($arResult["NAME_FORMATS"] as $template => $value)
					{
						if ($template == $arResult["CUR_NAME_FORMAT"])
							$formatExists = true;

						echo '<option value="'.$template.'"'.($template == $arResult["CUR_NAME_FORMAT"] ? ' selected' : '').'>'.htmlspecialcharsex($value).'</option>'."\n";
					}
					?>
					<option value="other" <?=($formatExists ? '' : "selected")?>><?php echo GetMessage("CONFIG_CULTURE_OTHER")?></option>
				</select>

				<input type="text" style="margin-top: 10px;<?=($formatExists ? 'display:none' : '')?>" name="FORMAT_NAME"  value="<?=htmlspecialcharsbx($arResult["CUR_NAME_FORMAT"])?>" class="content-edit-form-field-input-text" />
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>

		<tr data-field-id="config_week_start">
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_WEEK_START')?></td>
			<td class="content-edit-form-field-input">
				<select name="WEEK_START">
					<?php 
					for ($i = 0; $i < 7; $i++)
					{
						echo '<option value="'.$i.'"'.($i == $arResult["WEEK_START"] ? ' selected="selected"' : '').'>'.GetMessage('DAY_OF_WEEK_' .$i).'</option>';
					}
					?>
				</select>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>

		<tr data-field-id="config_time">
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_WORK_TIME')?></td>
			<td class="content-edit-form-field-input">
				<select name="work_time_start">
					<?php foreach($arResult["WORKTIME_LIST"] as $key => $val):?>
						<option value="<?= $key?>" <?php  if ($arResult["CALENDAT_SET"]['work_time_start'] == $key) echo ' selected="selected" ';?>><?= $val?></option>
					<?php endforeach;?>
				</select>
				-
				<select name="work_time_end">
					<?php foreach($arResult["WORKTIME_LIST"] as $key => $val):?>
						<option value="<?= $key?>" <?php  if ($arResult["CALENDAT_SET"]['work_time_end'] == $key) echo ' selected="selected" ';?>><?= $val?></option>
					<?php endforeach;?>
				</select>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<tr data-field-id="config_time">
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_WEEK_HOLIDAYS')?></td>
			<td class="content-edit-form-field-input">
				<select size="7" multiple=true id="cal_week_holidays" name="week_holidays[]">
					<?php foreach($arResult["WEEK_DAYS"] as $day):?>
						<option value="<?= $day?>" <?php if (in_array($day, $arResult["CALENDAT_SET"]['week_holidays']))echo ' selected="selected"';?>><?= GetMessage('CAL_OPTION_FIRSTDAY_'.$day)?></option>
					<?php endforeach;?>
				</select>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<tr data-field-id="config_time">
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_YEAR_HOLIDAYS')?></td>
			<td class="content-edit-form-field-input">
				<input name="year_holidays" type="text" value="<?= htmlspecialcharsbx($arResult["CALENDAT_SET"]['year_holidays'])?>" id="cal_year_holidays" size="60" class="content-edit-form-field-input-text"/>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_PHONE_NUMBER_DEFAULT_COUNTRY')?></td>
			<td class="content-edit-form-field-input">
				<select name="phone_number_default_country">
					<?php foreach($arResult["COUNTRIES"] as $key => $val):?>
						<option value="<?= $key?>" <?php  if ($arResult["PHONE_NUMBER_DEFAULT_COUNTRY"] == $key) echo ' selected="selected" ';?>><?= $val?></option>
					<?php endforeach;?>
				</select>
			</td>
		</tr>

		<?php if ($arResult["IS_BITRIX24"]):?>
	<!-- Organization type-->
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_ORGANIZATION')?></td>
			<td class="content-edit-form-field-input">
				<input type="radio" id="organisation" name="organization" value="" <?php if ($arResult["ORGANIZATION_TYPE"] == "") echo "checked"?>>
				<label for="organization"><?=GetMessage("CONFIG_ORGANIZATION_DEF")?></label>
				<br/>
				<input type="radio" id="organization_public" name="organization" value="public_organization" <?php if ($arResult["ORGANIZATION_TYPE"] == "public_organization") echo "checked"?>>
				<label for="organization_public"><?=GetMessage("CONFIG_ORGANIZATION_PUBLIC")?></label>
				<?php if (in_array(LANGUAGE_ID, array("ru", "ua"))):?>
					<br/>
					<input type="radio" id="organization_gov" name="organization" value="gov_organization" <?php if ($arResult["ORGANIZATION_TYPE"] == "gov_organization") echo "checked"?>>
					<label for="organization_gov"><?=GetMessage("CONFIG_ORGANIZATION_GOV")?></label>
				<?php endif?>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<?php endif?>

	<!-- show fired employees -->
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="show_fired_employees"><?=GetMessage('CONFIG_SHOW_FIRED_EMPLOYEES')?></label></td>
			<td class="content-edit-form-field-input"><input type="checkbox" name="show_fired_employees" id="show_fired_employees" <?php if (COption::GetOptionString("bitrix24", "show_fired_employees", "Y") == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>

	<!-- webdav/disk-->
		<tr data-field-id="congig_date">
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_DISK_VIEWER_SERVICE')?></td>
			<td class="content-edit-form-field-input">
				<select name="default_viewer_service">
					<?php foreach($arResult["DISK_VIEWER_SERVICE"] as $code => $name):?>
						<option value="<?=$code?>" <?php if ($code == $arResult["DISK_VIEWER_SERVICE_DEFAULT"]) echo "selected"?>><?=$name?></option>
					<?php endforeach?>
				</select>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>

		<?php if ($arResult["IS_DISK_CONVERTED"]):?>
			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="disk_allow_edit_object_in_uf"><?=GetMessage('CONFIG_DISK_ALLOW_EDIT_OBJECT_IN_UF')?></label></td>
				<td class="content-edit-form-field-input"><input type="checkbox" name="disk_allow_edit_object_in_uf" id="disk_allow_edit_object_in_uf" <?php if (COption::GetOptionString("disk", "disk_allow_edit_object_in_uf", "Y") == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
				<td class="content-edit-form-field-error"></td>
			</tr>
			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="disk_allow_autoconnect_shared_objects"><?=GetMessage('CONFIG_WEBDAV_ALLOW_AUTOCONNECT_SHARE_GROUP_FOLDER')?></label></td>
				<td class="content-edit-form-field-input"><input type="checkbox" name="disk_allow_autoconnect_shared_objects" id="disk_allow_autoconnect_shared_objects" <?php if (COption::GetOptionString("disk", "disk_allow_autoconnect_shared_objects", "N") == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
				<td class="content-edit-form-field-error"></td>
			</tr>
		<?php else:?>
			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="webdav_global"><?=GetMessage('CONFIG_WEBDAV_SERVICES_GLOBAL')?></label></td>
				<td class="content-edit-form-field-input"><input type="checkbox" name="webdav_global" id="webdav_global" <?php if (COption::GetOptionString("webdav", "webdav_allow_ext_doc_services_global", "N") == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
				<td class="content-edit-form-field-error"></td>
			</tr>
			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="webdav_local"><?=GetMessage('CONFIG_WEBDAV_SERVICES_LOCAL')?></label></td>
				<td class="content-edit-form-field-input"><input type="checkbox" name="webdav_local" id="webdav_local" <?php if (COption::GetOptionString("webdav", "webdav_allow_ext_doc_services_local", "N") == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
				<td class="content-edit-form-field-error"></td>
			</tr>
			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="webdav_autoconnect_share_group_folder"><?=GetMessage('CONFIG_WEBDAV_ALLOW_AUTOCONNECT_SHARE_GROUP_FOLDER')?></label></td>
				<td class="content-edit-form-field-input"><input type="checkbox" name="webdav_autoconnect_share_group_folder" id="webdav_autoconnect_share_group_folder" <?php if (COption::GetOptionString("webdav", "webdav_allow_autoconnect_share_group_folder", "Y") == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
				<td class="content-edit-form-field-error"></td>
			</tr>
		<?php endif?>

		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left">
				<label for="disk_version_limit_per_file"><?=GetMessage('CONFIG_DISK_VERSION_LIMIT_PER_FILE')?></label>
				<?php if ($arResult["IS_BITRIX24"] && !Feature::isFeatureEnabled("disk_version_limit_per_file")):?>
					<?php 
					CBitrix24::initLicenseInfoPopupJS("disk_version_limit_per_file");
					?>
					<img src="<?=$this->GetFolder();?>/images/lock.png" data-role="config-disk-version-limit-per-file" style="position: relative;bottom: -1px; margin-left: 5px;"/>
					<script>
						BX.ready(function(){
							var lock4 = document.querySelector("[data-role='config-disk-version-limit-per-file']");
							if (lock4)
							{
								BX.bind(lock4, "click", function(){
									B24.licenseInfoPopup.show('disk_version_limit_per_file', '<?=GetMessageJS("CONFIG_DISK_LIMIT_LOCK_POPUP_TITLE")?>',
										'<?=GetMessageJS("CONFIG_DISK_LIMIT_HISTORY_LOCK_POPUP_TEXT")?>');
								});
							}
						});
					</script>
				<?php endif?>

			</td>
			<td class="content-edit-form-field-input">
				<select name="disk_version_limit_per_file" <?php if ($arResult["IS_BITRIX24"] && !Feature::isFeatureEnabled("disk_version_limit_per_file")) echo "disabled";?>>
					<?php foreach($arResult["DISK_LIMIT_PER_FILE"] as $code => $name):?>
						<option value="<?=$code?>" <?php if ($code == $arResult["DISK_LIMIT_PER_FILE_SELECTED"]) echo "selected"?>><?=$name?></option>
					<?php endforeach?>
				</select>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>

		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left">
				<label for="disk_allow_use_external_link"><?=GetMessage('CONFIG_DISK_ALLOW_USE_EXTERNAL_LINK')?></label>
				<?php if ($arResult["IS_BITRIX24"] && !Feature::isFeatureEnabled("disk_switch_external_link")):?>
					<?php 
					CBitrix24::initLicenseInfoPopupJS("disk_switch_external_link");
					?>
					<img src="<?=$this->GetFolder();?>/images/lock.png" data-role="config-lock-disk-external-link" style="position: relative;bottom: -1px; margin-left: 5px;"/>
					<script>
						BX.ready(function(){
							var lock1 = document.querySelector("[data-role='config-lock-disk-external-link']");
							if (lock1)
							{
								BX.bind(lock1, "click", function(){
									B24.licenseInfoPopup.show('disk_switch_external_link', '<?=GetMessageJS("CONFIG_DISK_LOCK_POPUP_TITLE")?>',
										'<?=GetMessageJS("CONFIG_DISK_LOCK_POPUP_TEXT")?>');
								});
							}
						});
					</script>
				<?php endif?>
			</td>
			<td class="content-edit-form-field-input">
				<input type="checkbox"
					<?php if ($arResult["IS_BITRIX24"] && !Feature::isFeatureEnabled("disk_switch_external_link")) echo "disabled";?>
					id="disk_allow_use_external_link"
					name="disk_allow_use_external_link"
					<?php if (
						COption::GetOptionString("disk", "disk_allow_use_external_link", "Y") == "Y"
						&& (!$arResult["IS_BITRIX24"] || $arResult["IS_BITRIX24"] && Feature::isFeatureEnabled("disk_manual_external_link"))
					):?>
						checked
					<?php endif?>
					class="content-edit-form-field-input-selector"
				/>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>

		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left">
				<label for="disk_object_lock_enabled"><?=GetMessage('CONFIG_DISK_OBJECT_LOCK_ENABLED')?></label>
				<?php if ($arResult["IS_BITRIX24"] && !Feature::isFeatureEnabled("disk_object_lock_enabled")):?>
					<?php 
					CBitrix24::initLicenseInfoPopupJS("disk_object_lock_enabled");
					?>
					<img src="<?=$this->GetFolder();?>/images/lock.png" data-role="config-lock-disk-object-lock" style="position: relative;bottom: -1px; margin-left: 5px;"/>
					<script>
						BX.ready(function(){
							var lock2 = document.querySelector("[data-role='config-lock-disk-object-lock']");
							if (lock2)
							{
								BX.bind(lock2, "click", function(){
									B24.licenseInfoPopup.show('disk_object_lock_enabled', '<?=GetMessageJS("CONFIG_DISK_LOCK_POPUP_TITLE")?>',
										'<?=GetMessageJS("CONFIG_DISK_LOCK_POPUP_TEXT")?>');
								});
							}
						});
					</script>
				<?php endif?>
			</td>
			<td class="content-edit-form-field-input">
				<input type="checkbox" <?php if ($arResult["IS_BITRIX24"] && !Feature::isFeatureEnabled("disk_object_lock_enabled")) echo "disabled";?> id="disk_object_lock_enabled" name="disk_object_lock_enabled" <?php if (COption::GetOptionString("disk", "disk_object_lock_enabled", "N") == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>

		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left">
				<label for="disk_allow_use_extended_fulltext"><?=GetMessage('CONFIG_DISK_ALLOW_USE_EXTENDED_FULLTEXT')?></label>
				<?php if ($arResult["IS_BITRIX24"] && !Feature::isFeatureEnabled("disk_allow_use_extended_fulltext")):?>
					<?php 
					CBitrix24::initLicenseInfoPopupJS("disk_allow_use_extended_fulltext");
					?>
					<img src="<?=$this->GetFolder();?>/images/lock.png" data-role="config-lock-disk-allow-use-extended-fulltext" style="position: relative;bottom: -1px; margin-left: 5px;"/>
					<script>
						BX.ready(function(){
							var lockDiskFullText = document.querySelector("[data-role='config-lock-disk-allow-use-extended-fulltext']");
							if (BX.type.isDomNode(lockDiskFullText))
							{
								BX.bind(lockDiskFullText, "click", function(){
									B24.licenseInfoPopup.show('disk_allow_use_extended_fulltext', '<?=GetMessageJS("CONFIG_DISK_LOCK_POPUP_TITLE")?>',
										'<?=GetMessageJS("CONFIG_DISK_LOCK_EXTENDED_FULLTEXT_POPUP_TEXT")?>', false);
								});
							}
						});
					</script>
				<?php endif?>
			</td>
			<td class="content-edit-form-field-input">
				<input
					type="checkbox"
					<?php if ($arResult["IS_BITRIX24"] && !Feature::isFeatureEnabled("disk_allow_use_extended_fulltext")) echo "disabled";?>
					id="disk_allow_use_extended_fulltext"
					name="disk_allow_use_extended_fulltext"
					<?php if (COption::GetOptionString("disk", "disk_allow_use_extended_fulltext", "N") == "Y"):?>checked<?php endif?>
					class="content-edit-form-field-input-selector"
					<?php if (
						$arResult["IS_BITRIX24"]
						&& Feature::isFeatureEnabled("disk_allow_use_extended_fulltext")
						&& COption::GetOptionString("disk", "disk_allow_use_extended_fulltext", "N") != "Y"
					):?>
					onclick="BX.Bitrix24.Configs.Functions.showDiskExtendedFullTextInfo(event, this);"
					<?php endif?>
				/>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>

		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="allow_livefeed_toall"><?=GetMessage('CONFIG_ALLOW_TOALL')?></label></td>
			<td class="content-edit-form-field-input">
				<input type="checkbox" id="allow_livefeed_toall" name="allow_livefeed_toall" <?php if (COption::GetOptionString("socialnetwork", "allow_livefeed_toall", "Y") == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>

	<!-- live feed right-->
		<tr id="RIGHTS_all" style="display: <?=(COption::GetOptionString("socialnetwork", "allow_livefeed_toall", "Y") == "Y" ? "table-row" : "none")?>;">
			<td class="content-edit-form-field-name content-edit-form-field-name-left">&nbsp;</td>
			<td class="content-edit-form-field-input">
				<?php 
				$APPLICATION->IncludeComponent(
					"bitrix:main.user.selector",
					"",
					[
						"ID" => "livefeed_toall_rights",
						"INPUT_NAME" => 'livefeed_toall_rights[]',
						"LIST" => $arResult['arToAllRights'],
						"USE_SYMBOLIC_ID" => "Y",
						"API_VERSION" => 3,
						"SELECTOR_OPTIONS" => array(
							'contextCode' => 'U',
							'context' => "livefeed_toall_rights",
							'departmentSelectDisable' => 'N',
							'userSearchArea' => 'I',
							'enableAll' => 'Y',
							'departmentFlatEnable' => 'Y'
						)
					]
				);
				?>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<tr id="DEFAULT_all" style="display: <?=(COption::GetOptionString("socialnetwork", "allow_livefeed_toall", "Y") == "Y" ? "table-row" : "none")?>;">
			<td class="content-edit-form-field-name content-edit-form-field-name-left">
				<label for="default_livefeed_toall"><?=GetMessage('CONFIG_DEFAULT_TOALL')?></label>
			</td>
			<td class="content-edit-form-field-input"><input type="checkbox" id="default_livefeed_toall" name="default_livefeed_toall" <?php if (COption::GetOptionString("socialnetwork", "default_livefeed_toall", "Y") == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>

	<!-- im general chat right-->
		<?php 
			$imAllow = COption::GetOptionString("im", "allow_send_to_general_chat_all");

		?>
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="allow_general_chat_toall"><?=GetMessage('CONFIG_IM_CHAT_RIGHTS')?></label></td>
			<td class="content-edit-form-field-input">
				<input type="checkbox" id="allow_general_chat_toall" name="allow_general_chat_toall" <?php if ($imAllow == "Y" || $imAllow == "N" && !empty($arResult['arChatToAllRights'])):?>checked<?php endif?> class="content-edit-form-field-input-selector"/>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<tr id="chat_rights_all" style="display: <?=($imAllow == "Y" || $imAllow == "N" && !empty($arResult['arChatToAllRights']) ? "table-row" : "none")?>;">
			<td class="content-edit-form-field-name content-edit-form-field-name-left">&nbsp;</td>
			<td class="content-edit-form-field-input">
				<?php 

				$APPLICATION->IncludeComponent(
					"bitrix:main.user.selector",
					"",
					[
						"ID" => "imchat_toall_rights",
						"INPUT_NAME" => 'imchat_toall_rights[]',
						"LIST" => $arResult['arChatToAllRights'],
						"USE_SYMBOLIC_ID" => "Y",
						"API_VERSION" => 3,
						"SELECTOR_OPTIONS" => array(
							'contextCode' => 'U',
							'context' => "imchat_toall_rights",
							'departmentSelectDisable' => 'N',
							'userSearchArea' => 'I',
							'enableAll' => 'Y',
							'departmentFlatEnable' => 'Y'
						)
					]
				);
				?>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>

		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="general_chat_message_join"><?=GetMessage('CONFIG_IM_GENERSL_CHAT_MESSAGE_JOIN')?></label></td>
			<td class="content-edit-form-field-input"><input type="checkbox" name="general_chat_message_join" id="general_chat_message_join" <?php if (COption::GetOptionString("im", "general_chat_message_join")):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="general_chat_message_leave"><?=GetMessage('CONFIG_IM_GENERSL_CHAT_MESSAGE_LEAVE')?></label></td>
			<td class="content-edit-form-field-input"><input type="checkbox" name="general_chat_message_leave" id="general_chat_message_leave" <?php if (COption::GetOptionString("im", "general_chat_message_leave")):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>

		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="url_preview_enable"><?=GetMessage('CONFIG_URL_PREVIEW_ENABLE')?></label></td>
			<td class="content-edit-form-field-input"><input type="checkbox" name="url_preview_enable" id="url_preview_enable" <?php if (COption::GetOptionString("main", "url_preview_enable", "N") == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>
<?php 
$mpUserAllowInstall = count($arResult['MP_ALLOW_USER_INSTALL']) > 0;
?>
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="mp_allow_user_install"><?=GetMessage('CONFIG_MP_ALLOW_USER_INSTALL1')?></label></td>
			<td class="content-edit-form-field-input">
				<input type="hidden" id="mp_allow_user_install_changed" name="mp_allow_user_install_changed" value="N" /><input type="hidden" name="mp_allow_user_install" value="N" /><input type="checkbox" name="mp_allow_user_install" id="mp_allow_user_install" value="Y" <?=$mpUserAllowInstall ? ' checked="checked"' : ''?> class="content-edit-form-field-input-selector" onclick="BX('mp_allow_user_install_changed').value = 'Y'" />
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>
<?php
		if($arResult['MP_ALLOW_USER_INSTALL_EXTENDED'])
		{
?>
		<tr id="mp_user_install" <?php if(!$mpUserAllowInstall) echo ' style="display: none;"'?> >
			<td class="content-edit-form-field-name content-edit-form-field-name-left">&nbsp;</td>
			<td class="content-edit-form-field-input"><?php 
				$APPLICATION->IncludeComponent(
					"bitrix:main.user.selector",
					"",
					[
						"ID" => "mp_user_install_rights",
						"INPUT_NAME" => 'mp_user_install_rights[]',
						"LIST" => $arResult['MP_ALLOW_USER_INSTALL'],
						"USE_SYMBOLIC_ID" => "Y",
						"API_VERSION" => 3,
						"SELECTOR_OPTIONS" => array(
							'contextCode' => 'U',
							'context' => "mp_user_install_rights",
							'departmentSelectDisable' => 'N',
							'userSearchArea' => 'I',
							'enableAll' => 'Y',
							'departmentFlatEnable' => 'Y'
						)
					]
				);
			?>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>
<?php 
		}

		if ($arResult["IS_BITRIX24"])
		{
		?>
			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="configs_allow_register"><?=GetMessage('CONFIG_ALLOW_SELF_REGISTER')?></label></td>
				<td class="content-edit-form-field-input"><input type="checkbox" name="allow_register" id="configs_allow_register" <?php if ($arResult["ALLOW_SELF_REGISTER"] == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
				<td class="content-edit-form-field-error"></td>
			</tr>

			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="configs_allow_invite_users"><?=GetMessage('CONFIG_ALLOW_INVITE_USERS')?></label></td>
				<td class="content-edit-form-field-input"><input type="checkbox" name="allow_invite_users" value="Y" id="configs_allow_invite_users" <?php if ($arResult["ALLOW_INVITE_USERS"] == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
				<td class="content-edit-form-field-error"></td>
			</tr>

			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="configs_allow_new_user_lf"><?=GetMessage('CONFIG_ALLOW_NEW_USER_LF')?></label></td>
				<td class="content-edit-form-field-input"><input type="checkbox" name="allow_new_user_lf" value="Y" id="configs_allow_new_user_lf" <?php if ($arResult["ALLOW_NEW_USER_LF"] == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
				<td class="content-edit-form-field-error"></td>
			</tr>
			<?php 
			if (in_array($arResult["LICENSE_PREFIX"], array("ru", "ua", "kz", "by")))
			{
			?>
			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left">
					<label for="network_avaiable"><?=GetMessage('CONFIG_NETWORK_AVAILABLE')?></label>
					<?php 
					$disabled = $arResult['ALLOW_NETWORK_CHANGE'] !== 'Y';
					if ($arResult["IS_BITRIX24"] && !$arResult['CREATOR_CONFIRMED']):
						?>
					<img src="<?=$this->GetFolder();?>/images/lock.png"
						 style="position: relative;bottom: -1px; margin-left: 5px;"
						 onmouseover="BX.hint(this, '<?=htmlspecialcharsbx(CUtil::JSEscape(GetMessage('CONFIG_NETWORK_AVAILABLE_NOT_CONFIRMED')))?>')"/>
					<?php 
					elseif ($arResult['ALLOW_NETWORK_CHANGE'] === 'N'):
					CBitrix24::initLicenseInfoPopupJS("network_available");
					?>
					<img src="<?=$this->GetFolder();?>/images/lock.png" data-role="config-lock-network-available"
						 style="position: relative;bottom: -1px; margin-left: 5px;"/>
						<script>
							BX.ready(function () {
								var lock3 = document.querySelector("[data-role='config-lock-network-available']");
								if (lock3)
								{
									BX.bind(lock3, "click", function () {
										B24.licenseInfoPopup.show('network-available', '<?=GetMessageJS("CONFIG_NETWORK_AVAILABLE_TITLE")?>',
											'<?=GetMessageJS("CONFIG_NETWORK_AVAILABLE_TEXT_NEW", array("#PRICE#" => $arResult["PROJECT_PRICE"]))?>', false);
									});
								}
							});
						</script>
					<?php 
					endif; ?>
				</td>
				<td class="content-edit-form-field-input">
					<input type="checkbox"
						<?php if ($disabled) echo "disabled"; ?>
						name="network_avaiable" value="Y" id="network_avaiable"
						<?php if ($arResult["NETWORK_AVAILABLE"] == "Y"): ?>checked<?php endif?>
						class="content-edit-form-field-input-selector"
					/>
				</td>
				<td class="content-edit-form-field-error"></td>
			</tr>
		<?php 
			}
		}
		?>

		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="show_year_for_female"><?=GetMessage('CONFIG_SHOW_YEAR_FOR_FEMALE')?></label></td>
			<td class="content-edit-form-field-input"><input type="checkbox" name="show_year_for_female" value="N" id="show_year_for_female" <?php if ($arResult["SHOW_YEAR_FOR_FEMALE"] == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<?php 

		if (
			!$arResult["IS_BITRIX24"]
			|| \Bitrix\Bitrix24\Release::isAvailable('stresslevel')
		)
		{
			?>
			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left"><label for="stresslevel_available"><?=GetMessage('CONFIG_STRESSLEVEL_AVAILABLE')?></label></td>
				<td class="content-edit-form-field-input"><input type="checkbox" name="stresslevel_available" value="Y" id="stresslevel_available" <?php if ($arResult["STRESSLEVEL_AVAILABLE"] == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"/></td>
				<td class="content-edit-form-field-error"></td>
			</tr>
			<?php 
		}
		?>

	<!-- GDPR for Europe-->
		<?php 
		if ($arResult["IS_BITRIX24"])
		{
		?>
			<tr>
				<td class="content-edit-form-header" colspan="3">
					<div class="content-edit-form-header-wrap content-edit-form-header-wrap-blue"><?=GetMessage('CONFIG_HEADER_GDRP')?></div>
				</td>
			</tr>
			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left" colspan="3"><?=GetMessage('CONFIG_GDRP_TITLE1')?></td>
			</tr>
			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left">
					<?=GetMessage('CONFIG_GDRP_LABEL1')?>
					<?php if ($arResult["LICENSE_PREFIX"] == "ru"):?>
						<?=GetMessage("CONFIG_GDRP_LABEL11")?>
					<?php endif?>
				</td>
				<td class="content-edit-form-field-input"><input type="checkbox" name="gdpr_email_info" <?php if (COption::GetOptionString("bitrix24", "gdpr_email_info", "Y") == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"></td>
				<td class="content-edit-form-field-error"></td>
			</tr>
			<tr>
				<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_GDRP_LABEL2')?></td>
				<td class="content-edit-form-field-input"><input type="checkbox" name="gdpr_email_training" <?php if (COption::GetOptionString("bitrix24", "gdpr_email_training", "Y") == "Y"):?>checked<?php endif?> class="content-edit-form-field-input-selector"></td>
				<td class="content-edit-form-field-error"></td>
			</tr>
			<?php 
			if (!in_array($arResult["LICENSE_PREFIX"], array("ru", "ua", "kz", "by")))
			{
			?>
				<tr>
					<td class="content-edit-form-field-name content-edit-form-field-name-left" style="padding-top: 27px"><?=GetMessage('CONFIG_GDRP_LABEL3')?></td>
					<td class="content-edit-form-field-input" style="padding-top: 27px"><input
							type="checkbox"
							name="gdpr_data_processing"
							onchange="BX.Bitrix24.Configs.Functions.onGdprChange(this);"
							<?php if (COption::GetOptionString("bitrix24", "gdpr_data_processing", "") == "Y"):?>checked<?php endif?>
							class="content-edit-form-field-input-selector">
					</td>
					<td class="content-edit-form-field-error"></td>
				</tr>

				<?php 
				$isGdprDataShown = COption::GetOptionString("bitrix24", "gdpr_data_processing", "") == "Y";
				?>
				<tr data-role="gdpr-data" <?php if (!$isGdprDataShown):?>style="visibility:collapse"<?php endif?>>
					<td class="content-edit-form-field-name content-edit-form-field-name-left" colspan="3"><?=GetMessage('CONFIG_GDRP_TITLE2')?></td>
				</tr>
				<tr data-role="gdpr-data" <?php if (!$isGdprDataShown):?>style="visibility:collapse"<?php endif?>>
					<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_GDRP_LABEL4')?></td>
					<td class="content-edit-form-field-input"><input type="text" name="gdpr_legal_name" value="<?=htmlspecialcharsbx(isset($_POST["gdpr_legal_name"]) ? $_POST["gdpr_legal_name"] : COption::GetOptionString("bitrix24", "gdpr_legal_name", ""))?>" class="content-edit-form-field-input-text" size="60"></td>
					<td class="content-edit-form-field-error"></td>
				</tr>
				<tr data-role="gdpr-data" <?php if (!$isGdprDataShown):?>style="visibility:collapse"<?php endif?>>
					<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_GDRP_LABEL5')?></td>
					<td class="content-edit-form-field-input"><input type="text" name="gdpr_contact_name" value="<?=htmlspecialcharsbx(isset($_POST["gdpr_contact_name"]) ? $_POST["gdpr_contact_name"] : COption::GetOptionString("bitrix24", "gdpr_contact_name", ""))?>" class="content-edit-form-field-input-text" size="60"></td>
					<td class="content-edit-form-field-error"></td>
				</tr>
				<tr data-role="gdpr-data" <?php if (!$isGdprDataShown):?>style="visibility:collapse"<?php endif?>>
					<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_GDRP_LABEL6')?></td>
					<td class="content-edit-form-field-input"><input type="text" name="gdpr_title" value="<?=htmlspecialcharsbx(isset($_POST["gdpr_title"]) ? $_POST["gdpr_title"] : COption::GetOptionString("bitrix24", "gdpr_title", ""))?>" class="content-edit-form-field-input-text" size="60"></td>
					<td class="content-edit-form-field-error"></td>
				</tr>
				<tr data-role="gdpr-data" <?php if (!$isGdprDataShown):?>style="visibility:collapse"<?php endif?>>
					<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_GDRP_LABEL7')?></td>
					<td class="content-edit-form-field-input">
						<?php $APPLICATION->IncludeComponent(
							"bitrix:main.calendar",
							"",
							array(
								"SHOW_INPUT"=>"Y",
								"INPUT_NAME"=> "gdpr_date",
								"INPUT_VALUE"=> htmlspecialcharsbx(isset($_POST["gdpr_date"]) ? $_POST["gdpr_date"] : COption::GetOptionString("bitrix24", "gdpr_date", "")),
								"INPUT_ADDITIONAL_ATTR"=>'class="content-edit-form-field-input-text" style="width: 100px;"',
								"SHOW_TIME" =>  'N',
							),
							$component,
							array("HIDE_ICONS"=>true)
						);?>
					</td>
					<td class="content-edit-form-field-error"></td>
				</tr>
				<tr data-role="gdpr-data" <?php if (!$isGdprDataShown):?>style="visibility:collapse"<?php endif?>>
					<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_GDRP_LABEL8')?></td>
					<td class="content-edit-form-field-input"><input type="text" name="gdpr_notification_email" value="<?=htmlspecialcharsbx(isset($_POST["gdpr_notification_email"]) ? $_POST["gdpr_notification_email"] : COption::GetOptionString("bitrix24", "gdpr_notification_email", ""))?>" class="content-edit-form-field-input-text" size="60"></td>
					<td class="content-edit-form-field-error"></td>
				</tr>

				<?php \CJSCore::init("sidepanel");?>
				<tr>
					<td colspan="3">
						<div class="config_notify_message" style="margin: 10px 20px 10px 20px">
							<?=GetMessage("CONFIG_GDRP_TITLE3")?><br/>
							<a href="javascript:void(0)" onclick="BX.SidePanel.Instance.open('/marketplace/detail/integrations24.gdprstaff/');"><?=GetMessage("CONFIG_GDRP_APP1")?></a>
							<br/>
							<a href="javascript:void(0)" onclick="BX.SidePanel.Instance.open('/marketplace/detail/integrations24.gdpr/');"><?=GetMessage("CONFIG_GDRP_APP2")?></a>
						</div>
					</td>
				</tr>
		<?php 
			}
		}
		?>
	<!-- //GDPR for Europe-->

	<!-- secutity -->
		<tr>
			<td class="content-edit-form-header" colspan="3" >
				<div class="content-edit-form-header-wrap content-edit-form-header-wrap-blue"><?=GetMessage('CONFIG_HEADER_SECUTIRY')?></div>
			</td>
		</tr>
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_OTP_SECURITY2')?></td>
			<td class="content-edit-form-field-input"><input type="checkbox" <?php if (!$arResult["SECURITY_IS_USER_OTP_ACTIVE"] && !$arResult["SECURITY_OTP"]):?> onclick="BX.Bitrix24.Configs.Functions.adminOtpIsRequiredInfo(this);return false;"<?php endif?> onchange="BX.Bitrix24.Configs.Functions.otpSwitchOffInfo(this);" name="security_otp"  class="content-edit-form-field-input-selector" <?php if ($arResult["SECURITY_OTP"]):?>checked<?php endif?>/></td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"><?=GetMessage('CONFIG_OTP_SECURITY_DAYS')?></td>
			<td class="content-edit-form-field-input">
				<select id="security_otp_days" name="security_otp_days">
					<?php for($i=5; $i<=10; $i++):?>
						<option value="<?=$i?>" <?php if ($arResult["SECURITY_OTP_DAYS"] == $i) echo 'selected="selected"';?>><?=FormatDate("ddiff", time()-60*60*24*$i)?></option>
					<?php endfor;?>
				</select>
			</td>
			<td class="content-edit-form-field-error"></td>
		</tr>
		<tr>
			<td colspan="3">
				<div class="config_notify_message" style="margin: 10px 20px 10px 20px">
					<?=GetMessage("CONFIG_OTP_SECURITY_INFO")?>
					<a href="javascript:void(0)" onclick="BX.nextSibling(this).style.display='block'; BX.remove(this)"><?=GetMessage("CONFIG_MORE")?></a>
					<span style="display: none">
						<?=GetMessage("CONFIG_OTP_SECURITY_INFO_1")?>
						<?=!$arResult["IS_BITRIX24"] ? GetMessage("CONFIG_OTP_SECURITY_INFO_2") : "";?>
						<?=GetMessage("CONFIG_OTP_SECURITY_INFO_3")?>
					</span>
				</div>
			</td>
		</tr>

	<?php 
	if ($arResult["IS_BITRIX24"])
	{
	?>
	<!-- features -->
		<tr>
			<td class="content-edit-form-header " colspan="3" >
				<div class="content-edit-form-header-wrap content-edit-form-header-wrap-blue"><?=GetMessage('CONFIG_FEATURES_TITLE')?></div>
			</td>
		</tr>
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"></td>
			<td class="content-edit-form-field-input" colspan="2">

				<input type="checkbox" name="feature_crm" id="feature_crm" <?php if (IsModuleInstalled("crm")) echo "checked"?>/>
				<label for="feature_crm"><?=GetMessage("CONFIG_FEATURES_CRM")?></label><br/>

				<input type="checkbox" name="feature_extranet" id="feature_extranet" <?php if (IsModuleInstalled("extranet")) echo "checked"?>/>
				<label for="feature_extranet"><?=GetMessage("CONFIG_FEATURES_EXTRANET")?></label><br/>

				<?php if (Feature::isFeatureEnabled("timeman")):?>
					<input type="checkbox" name="feature_timeman" id="feature_timeman" <?php if (IsModuleInstalled("timeman")) echo "checked"?>/>
					<label for="feature_timeman"><?=GetMessage("CONFIG_FEATURES_TIMEMAN")?></label><br/>
				<?php endif?>
				<?php if (Feature::isFeatureEnabled("meeting")):?>
					<input type="checkbox" name="feature_meeting" id="feature_meeting" <?php if (IsModuleInstalled("meeting")) echo "checked"?>/>
					<label for="feature_meeting"><?=GetMessage("CONFIG_FEATURES_MEETING")?></label><br/>
				<?php endif?>
				<?php if (Feature::isFeatureEnabled("lists")):?>
					<input type="checkbox" name="feature_lists" id="feature_lists" <?php if (IsModuleInstalled("lists")) echo "checked"?>/>
					<label for="feature_lists"><?=GetMessage("CONFIG_FEATURES_LISTS")?></label><br/>
				<?php endif?>
			</td>
		</tr>
	<!--ip -->
		<?php 
		if (Feature::isFeatureEnabled("ip_access_rights"))
		{
			$arCurIpRights = $arResult["IP_RIGHTS"];
			if (!is_array($arCurIpRights))
				$arCurIpRights = array();
			$access = new CAccess();
			$arNames = $access->GetNames(array_keys($arCurIpRights));
			?>
			<tr>
				<td class="content-edit-form-header " colspan="3" >
					<div class="content-edit-form-header-wrap content-edit-form-header-wrap-blue"><?=GetMessage('CONFIG_IP_TITLE')?></div>
				</td>
			</tr>
			<?php 
			foreach($arCurIpRights as $right => $arIps)
			{
			?>
				<tr data-bx-right="<?=$right?>">
					<td class="content-edit-form-field-name">
						<?=(!empty($arNames[$right]["provider"]) ? $arNames[$right]["provider"].": " : "").$arNames[$right]["name"]?>
					</td>
					<td class="content-edit-form-field-input" colspan="2">
						<?php foreach($arIps as $ip):?>
							<div>
								<input name="ip_access_rights_<?=$right?>[]" value="<?=$ip?>" size="30"/>
								<a href="javascript:void(0);" onclick="B24ConfigsIpObj.DeleteIpAccessRow(this);" class="access-delete" title="<?=GetMessage("CONFIG_TOALL_DEL")?>"></a>
							</div>
						<?php endforeach?>
						<div>
							<input name="ip_access_rights_<?=$right?>[]" size="30" onclick="B24ConfigsIpObj.addInputForIp(this)"/>
							<a href="javascript:void(0);" onclick="B24ConfigsIpObj.DeleteIpAccessRow(this);" class="access-delete" title="<?=GetMessage("CONFIG_TOALL_DEL")?>"></a>
						</div>
					</td>
				</tr>
			<?php 
			}
			?>
			<tr id="ip_add_right_button">
				<td class="content-edit-form-field-name content-edit-form-field-name-left"></td>
				<td class="content-edit-form-field-input" colspan="2">
					<a href="javascript:void(0)" class="bx-action-href" onclick="B24ConfigsIpObj.ShowIpAccessPopup(B24ConfigsIpObj.arCurIpRights);"><?=GetMessage("CONFIG_TOALL_RIGHTS_ADD")?></a>
				</td>
			</tr>
		<?php 
		}
		?>
		<?php if (LANGUAGE_ID == "ru"):?>
		<!-- marketplace -->
		<tr>
			<td class="content-edit-form-header " colspan="3" >
				<div class="content-edit-form-header-wrap content-edit-form-header-wrap-blue"><?=GetMessage('CONFIG_MARKETPLACE_TITLE')?></div>
			</td>
		</tr>
		<tr>
			<td class="content-edit-form-field-name content-edit-form-field-name-left"></td>
			<td class="content-edit-form-field-input" colspan="2">
				<a href="/marketplace/category/migration/"><?=GetMessage("CONFIG_MARKETPLACE_MORE")?></a>
			</td>
		</tr>
		<?php endif?>
	<?php 
	}


	if($arResult['ALLOW_DOMAIN_CHANGE'])
	{
	?>
		<tr>
			<td class="content-edit-form-header " colspan="3">
				<div class="content-edit-form-header-wrap content-edit-form-header-wrap-blue"><?=GetMessage('CONFIG_NAME_CHANGE_SECTION')?></div>
			</td>
		</tr>
		<tr>
			<td class="content-edit-form-field-name" style="width:370px; padding-right:30px"><?=GetMessage('CONFIG_NAME_CHANGE_FIELD')?></td>
			<td class="content-edit-form-field-input" colspan="2">
				<a href="javascript:void(0)" onclick="BX.Bitrix24.renamePortal()"><?=GetMessage('CONFIG_NAME_CHANGE_ACTION')?></a>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div class="config_notify_message" style="margin: 10px 20px 10px 20px">
					<?=GetMessage("CONFIG_NAME_CHANGE_INFO")?>
				</div>
			</td>
		</tr>
	<?php 
	}

	if($arResult['SHOW_GOOGLE_API_KEY_FIELD'])
	{
	?>
		<tr>
			<td class="content-edit-form-header " colspan="3">
				<div class="content-edit-form-header-wrap content-edit-form-header-wrap-blue"><?=GetMessage('CONFIG_NAME_GOOGLE_API_KEY')?></div>
			</td>
		</tr>
		<tr>
			<td class="content-edit-form-field-name" style="width:370px; padding-right:30px"><?=GetMessage('CONFIG_NAME_GOOGLE_API_KEY_FIELD')?></td>
			<td class="content-edit-form-field-input" colspan="2">
				<a name="google_api_key"></a>
				<input class="content-edit-form-field-input-text" name="google_api_key" value="<?=\Bitrix\Main\Text\HtmlFilter::encode($arResult['GOOGLE_API_KEY'])?>">
			</td>
		</tr>
<?php 
		if(strlen($arResult['GOOGLE_API_KEY_HOST']) > 0 && strlen($arResult['GOOGLE_API_KEY']) > 0):
?>
			<tr>
				<td colspan="3">
					<div class="config_notify_message" style="margin: 10px 20px 10px 20px">
						<?=GetMessage("CONFIG_NAME_GOOGLE_API_HOST_HINT", array(
							'#domain#' => \Bitrix\Main\Text\HtmlFilter::encode($arResult['GOOGLE_API_KEY_HOST'])
						))?>
					</div>
				</td>
			</tr>
<?php 
		else:
?>
			<tr>
				<td colspan="3">
					<div class="config_notify_message" style="margin: 10px 20px 10px 20px">
						<?=GetMessage("CONFIG_NAME_GOOGLE_API_KEY_HINT")?>
					</div>
				</td>
			</tr>
<?php 
		endif;
	}
	?>
	</table>
</form>

<br/><br/>
<table class="content-edit-form" cellspacing="0" cellpadding="0">
	<tr>
		<td class="content-edit-form-buttons" style="border-top: 1px #eaeae1 solid; text-align:center">
			<span onclick="BX.Bitrix24.Configs.Functions.submitForm(this)" class="webform-button webform-button-create">
				<?=GetMessage("CONFIG_SAVE")?>
			</span>
		</td>
	</tr>
</table>
<br/><br/><br/><br/>
<!-- logo -->
<?php 
if ($arResult["IS_BITRIX24"] && Feature::isFeatureEnabled("set_logo") || !$arResult["IS_BITRIX24"])
{
	$clientLogoID = COption::GetOptionInt("bitrix24", "client_logo", "");
	$clientLogoRetinaID = COption::GetOptionInt("bitrix24", "client_logo_retina", "");
?>
<table id="content-edit-form-config" class="content-edit-form" cellspacing="0" cellpadding="0">
	<tr>
		<td class="content-edit-form-header" colspan="3" >
			<div class="content-edit-form-header-wrap content-edit-form-header-wrap"><?=GetMessage('CONFIG_CLIENT_LOGO')?></div>
		</td>
	</tr>
	<tr>
		<td colspan="3" >
			<div class="content-edit-form-notice-error" style="display: none;" id="config_logo_error_block">
				<span class="content-edit-form-notice-text"><span class="content-edit-form-notice-icon"></span></span>
			</div>
		</td>
	</tr>
	<tr>
		<td class="content-edit-form-field-name" style="width:370px; padding-right:30px"><?=GetMessage('CONFIG_CLIENT_LOGO')?></td>
		<td class="content-edit-form-field-input" colspan="2">
			<?=GetMessage('CONFIG_CLIENT_LOGO_DESCR')?>
			<form name="configLogoPostForm" id="configLogoPostForm" method="POST" action="<?=POST_FORM_ACTION_URI?>" enctype="multipart/form-data">
				<?=bitrix_sessid_post();?>
				<div>
					<label for="clientLogo" class="config-webform-field-upload" style="margin-top:10px;vertical-align: middle;" onmouseup="BX.removeClass(this,'content-edit-form-button-press')" onmousedown="BX.addClass(this, 'content-edit-form-button-press')">
						<span class=""><span class="content-edit-form-button-left"></span><span class="content-edit-form-button-text"><?=GetMessage('CONFIG_ADD_LOGO_BUTTON')?></span><span class="content-edit-form-button-right"></span></span>
						<input type="file" name="client_logo" id="clientLogo" value=""/>
					</label>
					<br/><br/>
					<div id="configWaitLogo" style="display:none;"><img src="<?=$this->GetFolder();?>/images/wait.gif"/></div>

					<div id="configBlockLogo" class="config-webform-logo-img" <?php if (!$clientLogoID):?>style="display:none"<?php endif?>>
						<img id="configImgLogo" src="<?php if ($clientLogoID) echo CFile::GetPath($clientLogoID)?>" />
					</div>

					<a href="javascript:void(0)" id="configDeleteLogo" class="config_logo_delete_link"  <?php if (!$clientLogoID):?>style="display:none"<?php endif?>>
						<?=GetMessage("CONFIG_ADD_LOGO_DELETE")?>
					</a>
				</div>
			</form>
		</td>
	</tr>

	<tr>
		<td class="content-edit-form-field-name" style="width:370px; padding-right:30px"><?=GetMessage('CONFIG_CLIENT_LOGO_RETINA')?></td>
		<td class="content-edit-form-field-input" colspan="2">
			<?=GetMessage('CONFIG_CLIENT_LOGO_DESC_RETINA')?>
			<form name="configLogoRetinaPostForm" id="configLogoRetinaPostForm" method="POST" action="<?=POST_FORM_ACTION_URI?>" enctype="multipart/form-data">
				<?=bitrix_sessid_post();?>

				<!-- retina -->
				<div style="margin-top:20px">
					<label for="clientLogoretina" class="config-webform-field-upload" style="margin-top:10px;vertical-align: middle;" onmouseup="BX.removeClass(this,'content-edit-form-button-press')" onmousedown="BX.addClass(this, 'content-edit-form-button-press')">
						<span class=""><span class="content-edit-form-button-left"></span><span class="content-edit-form-button-text"><?=GetMessage('CONFIG_ADD_LOGO_BUTTON')?></span><span class="content-edit-form-button-right"></span></span>
						<input type="file" name="client_logo_retina" id="clientLogoretina" value=""/>
					</label>
					<br/><br/>
					<div id="configWaitLogoretina" style="display:none;"><img src="<?=$this->GetFolder();?>/images/wait.gif"/></div>

					<div id="configBlockLogoretina" class="config-webform-logo-img" <?php if (!$clientLogoRetinaID):?>style="display:none"<?php endif?>>
						<img id="configImgLogoretina" src="<?php if ($clientLogoRetinaID) echo CFile::GetPath($clientLogoRetinaID)?>" />
					</div>

					<a href="javascript:void(0)" id="configDeleteLogoretina" class="config_logo_retina_delete_link"  <?php if (!$clientLogoRetinaID):?>style="display:none"<?php endif?>>
						<?=GetMessage("CONFIG_ADD_LOGO_DELETE")?>
					</a>
				</div>
			</form>
		</td>
	</tr>
</table>
<?php 
}
?>

<?php 
if (isset($_GET["otp"]))
{
?>
	<form id="bitrix24-otp-tell-about-form" style="display: none" action="/bitrix/urlrewrite.php?SEF_APPLICATION_CUR_PAGE_URL=<?=str_replace("%23", "#", urlencode($arParams["CONFIG_PATH_TO_POST"]))?>" method="POST">
		<div style="padding: 4px">
			<?=bitrix_sessid_post()?>
			<input type="hidden" name="POST_TITLE" value="<?=GetMessage("CONFIG_OTP_IMPORTANT_TITLE")?>">
			<textarea name="POST_MESSAGE" style="display: none"><?=GetMessage("CONFIG_OTP_IMPORTANT_TEXT")?></textarea>
			<input type="hidden" name="changePostFormTab" value="important">
			<div style="margin-bottom: 10px; font-size: 14px">
				<?=GetMessage("CONFIG_OTP_POPUP_TEXT")?>
			</div>
		</div>
	</form>

	<script>
		BX.ready(function(){
			var popup = BX.PopupWindowManager.create("bitrix24OtpImportant", null, {
				autoHide: true,
				offsetLeft: 0,
				offsetTop: 0,
				overlay : true,
				draggable: { restrict:true },
				closeByEsc: true,
				closeIcon: true,
				titleBar: "<?=GetMessageJS("CONFIG_OTP_POPUP_TITLE")?>",
				content: BX("bitrix24-otp-tell-about-form"),
				buttons: [
					new BX.PopupWindowButton({
						text: "<?=GetMessage("CONFIG_OTP_POPUP_SHARE")?>",
						className: "popup-window-button-accept",
						events: {
							click: function() {
								BX.submit(BX("bitrix24-otp-tell-about-form"), 'dummy')
							}
						}
					}),
					new BX.PopupWindowButtonLink({
						text: "<?=GetMessage("CONFIG_OTP_POPUP_CLOSE")?>",
						events: {
							click: function() {
								this.popupWindow.close();
							}
						}
					})
				]
			});

			popup.show();
		});
	</script>
<?php 
}
?>

<script>
	BX.message({
		SLToAllDel: '<?=CUtil::JSEscape(GetMessage("CONFIG_TOALL_DEL"))?>',
		LogoDeleteConfirm: '<?=GetMessageJS("CONFIG_ADD_LOGO_DELETE_CONFIRM")?>',
		CONFIG_OTP_SECURITY_SWITCH_OFF_INFO: '<?=GetMessageJS("CONFIG_OTP_SECURITY_SWITCH_OFF_INFO")?>',
		CONFIG_OTP_ADMIN_IS_REQUIRED_INFO: '<?=GetMessageJS("CONFIG_OTP_ADMIN_IS_REQUIRED_INFO")?>',
		CONFIG_DISK_EXTENDED_FULLTEXT_INFO: "<?=GetMessageJS("CONFIG_DISK_EXTENDED_FULLTEXT_INFO")?>"
	});

	var B24ConfigsLogo = new BX.Bitrix24.Configs.LogoClass("<?=CUtil::JSEscape(POST_FORM_ACTION_URI)?>");

	BX.ready(function(){
		BX.Bitrix24.Configs.Functions.init();
	});

	<?php if ($arResult["IS_BITRIX24"] && Feature::isFeatureEnabled("ip_access_rights")):?>
	var B24ConfigsIpObj = new BX.Bitrix24.Configs.IpSettingsClass(<?=CUtil::PhpToJSObject(array_keys($arCurIpRights))?>);
	<?php endif?>
	<?php if ($arResult['SHOW_RENAME_POPUP']):?>
		BX.ready(function(){
			if (typeof BX.Bitrix24.renamePortal != 'undefined')
				BX.Bitrix24.renamePortal();
		});
	<?php endif?>
</script>