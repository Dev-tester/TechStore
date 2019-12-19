<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arFields = array(
	'PERSONAL' => array(
		'NAME', 'LAST_NAME', 'SECOND_NAME', 'PERSONAL_PHOTO', 'PERSONAL_GENDER', 'PERSONAL_BIRTHDAY', 'PERSONAL_BIRTHDATE',
		'PERSONAL_PROFESSION', 'PERSONAL_NOTES', 'AUTO_TIME_ZONE', 'TIME_ZONE',
	),
);

if(!empty($arResult["arSocServ"]))
	$arFields['SOCSERV'] = array('SOCSERVICES');

$arFields['CONTACT'] = array(
	'EMAIL', 'PERSONAL_PHONE', 'PERSONAL_MOBILE', 'PERSONAL_WWW', 'PERSONAL_ICQ', 'PERSONAL_FAX', 'PERSONAL_PAGER',
	'PERSONAL_COUNTRY', 'PERSONAL_STREET', 'PERSONAL_MAILBOX', 'PERSONAL_CITY', 'PERSONAL_STATE', 'PERSONAL_ZIP',
);

$arFields['WORK'] = array(
	'WORK_COUNTRY', 'WORK_CITY', 'WORK_COMPANY', 'WORK_DEPARTMENT', 'WORK_PROFILE', 'WORK_WWW', 'WORK_PHONE',
	'WORK_FAX', 'WORK_PAGER', 'WORK_LOGO', 'WORK_POSITION', 'WORK_STATE',
);

$arFields['AUTH'] = array(
	'LOGIN', 'PASSWORD', 'CONFIRM_PASSWORD',
);

if ($arParams['IS_FORUM'] == 'Y')
{
	$arFields['FORUM'] = array(
		'FORUM_SHOW_NAME', 'FORUM_HIDE_FROM_ONLINE', 'FORUM_SUBSC_GET_MY_MESSAGE', 'FORUM_DESCRIPTION', 'FORUM_INTERESTS', 'FORUM_SIGNATURE', 'FORUM_AVATAR'
	);
}

if ($arParams['IS_BLOG'] == 'Y')
{
	$arFields['BLOG'] = array(
		'BLOG_ALIAS', 'BLOG_DESCRIPTION', 'BLOG_INTERESTS', 'BLOG_AVATAR'
	);
}

foreach ($arParams['EDITABLE_FIELDS'] as $FIELD)
{
	$bFound = false;
	if ($arResult['USER_PROP'][$FIELD])
	{
		foreach ($arFields as $FIELD_TYPE => $arTypeFields)
		{
			if (is_array($arParams['USER_PROPERTY_'.$FIELD_TYPE]) && in_array($FIELD, $arParams['USER_PROPERTY_'.$FIELD_TYPE]))
			{
				$arFields[$FIELD_TYPE][] = $FIELD;
				$bFound = true;
				break;
			}
		}
	
		if (!$bFound)
			$arFields['PERSONAL'][] = $FIELD;
	}
}

$GROUP_ACTIVE = false;

foreach ($arFields as $GROUP => $arGroupFields)
{
	$arFields[$GROUP] = array_unique($arGroupFields);
	foreach ($arGroupFields as $fkey => $FIELD)
	{
		if (!in_array($FIELD, $arParams['EDITABLE_FIELDS']))
		{
			unset($arGroupFields[$fkey]);
		}
		elseif(!$GROUP_ACTIVE)
			$GROUP_ACTIVE = $GROUP;
	}
	
	$arFields[$GROUP] = array_unique($arGroupFields);
}

$current_fieldset = $_REQUEST['current_fieldset'] ? $_REQUEST['current_fieldset'] : ($GROUP_ACTIVE ? $GROUP_ACTIVE : 'PERSONAL');

if (!in_array($current_fieldset, array_keys($arFields))) $current_fieldset = 'PERSONAL';

if ($arResult['ERROR_MESSAGE'])
{
?>
<div class="bx-sonet-profile-edit-error">
<?php 
	ShowError($arResult['ERROR_MESSAGE']);
?>
</div>
<?php 
}

?>
<form name="bx_user_profile_form" method="POST" action="<?php echo POST_FORM_ACTION_URI;?>" enctype="multipart/form-data">
<?php echo bitrix_sessid_post()?>
<input type="hidden" name="current_fieldset" value="<?php echo $current_fieldset?>" />
<div class="bx-sonet-profile-edit-layout">
	<ul class="bx-sonet-profile-edit-menu">
<?php 
foreach ($arFields as $GROUP_ID => $arGroupFields):
	if (is_array($arGroupFields) && count($arGroupFields) > 0):
?>
		<li id="bx_sonet_switcher_<?php echo $GROUP_ID?>"<?php echo $GROUP_ID == $current_fieldset ? ' class="bx-sonet-switcher-current"' : ''?>><div><a href="javascript: void(0)" onclick="switchFieldSet('<?php echo $GROUP_ID?>'); this.blur();"><?php echo GetMessage('SOCNET_SUPE_TPL_GROUP_'.$GROUP_ID)?></a></div></li>
<?php 
		${'bShowGroup_'.$GROUP_ID} = true;
	else:
		${'bShowGroup_'.$GROUP_ID} = false;
	endif;
endforeach;
?>
	</ul>
	<div class="bx-sonet-profile-edit-layers">
<?php 
foreach ($arFields as $GROUP_ID => $arGroupFields):
	if (${'bShowGroup_'.$GROUP_ID}):
?>
		<div id="bx_sonet_fieldset_<?php echo $GROUP_ID?>" class="bx-sonet-profile-fieldset" style="display: <?php echo $GROUP_ID == $current_fieldset ? 'block' : 'none'?>">
			<table class="bx-sonet-profile-fieldset-table">
<?php 
		foreach ($arGroupFields as $FIELD):

			if(($FIELD == 'AUTO_TIME_ZONE' || $FIELD == 'TIME_ZONE') && $arResult["TIME_ZONE_ENABLED"] <> true)
				continue;

			$value = $arResult['User'][$FIELD];
?>
				<tr>
					<td class="bx-sonet-profile-fieldcaption"><?php 
					if ($arResult['USER_PROPERTY_ALL'][$FIELD]['MANDATORY'] == "Y"):
						?><span class="required-field">*</span><?php 
					endif;
					
					if ($FIELD == "PASSWORD" && intval($arResult["PASSWORD_MIN_LENGTH"]) > 0)
						echo str_replace(array('#MIN_LENGTH#'), $arResult["PASSWORD_MIN_LENGTH"], GetMessage('ISL_PASSWORD_1'));
					else
						echo $arResult['USER_PROP'][$FIELD] ? $arResult['USER_PROP'][$FIELD] : GetMessage('ISL_'.$FIELD)?>:
					</td>				
					<td class="bx-sonet-profile-field"><?php 
			switch ($FIELD)
			{
				case 'PERSONAL_GENDER':
					?><select name="<?php echo $FIELD?>">
						<option value=""></option>
						<option value="M"<?php echo $value == 'M' ? ' selected="selected"' : ''?>><?php echo GetMessage('ISL_PERSONAL_GENDER_MALE')?></option>
						<option value="F"<?php echo $value == 'F' ? ' selected="selected"' : ''?>><?php echo GetMessage('ISL_PERSONAL_GENDER_FEMALE')?></option>
					</select><?php 
					break;
				
				case 'PERSONAL_COUNTRY':
				case 'WORK_COUNTRY':
					echo SelectBoxFromArray($FIELD, GetCountryArray(), $value, GetMessage("ISL_COUNTRY_EMPTY"));
					break;

				case 'SOCSERVICES':
					if(!empty($arResult["arSocServ"])):
						?>
						<div class="bx-sonet-profile-field-socserv">
							<?php 
							$APPLICATION->IncludeComponent("bitrix:socserv.auth.split", "twitpost", array(
									"SHOW_PROFILES" => "Y",
									"ALLOW_DELETE" => "Y",
									"USER_ID" => $arParams['ID'],
								),
								false
							);
							?>
						</div>
						<?php 
					endif;
					break;

				case 'PERSONAL_PHOTO':
				case 'WORK_LOGO':
				case 'FORUM_AVATAR':
				case 'BLOG_AVATAR':
					if ($arResult['User'][$FIELD.'_IMG']):?><div class="bx-sonet-profile-field-photo"><?php echo $arResult['User'][$FIELD.'_IMG']?></div>
					<input type="checkbox" name="<?php echo $FIELD?>_del" id="<?php echo $FIELD?>_del" value="Y" /><label for="<?php echo $FIELD;?>_del"><?php echo GetMessage('ISL_'.$FIELD.'_del')?></label><br /><?php endif; ?>
					<input type="file" name="<?php echo $FIELD?>" />
					<?php 
					break;
			
				case 'PERSONAL_BIRTHDAY':
					$APPLICATION->IncludeComponent(
						'bitrix:main.calendar', 
						'.default', 
						array(
							'SHOW_INPUT' => 'Y',
							'FORM_NAME' => 'bx_user_profile_form',
							'INPUT_NAME' => $FIELD,
							'INPUT_VALUE' => $value,
							'SHOW_TIME' => 'N',
						), 
						null, 
						array('HIDE_ICONS' => 'Y')
					);
					break;
			
				case 'PASSWORD': 
				case 'CONFIRM_PASSWORD': 
					?><input type="password" name="<?php echo $FIELD?>" autocomplete="off" /><?php 
					break;
			
				case 'FORUM_SHOW_NAME':
				case 'FORUM_HIDE_FROM_ONLINE':
				case 'FORUM_SUBSC_GROUP_MESSAGE':
				case 'FORUM_SUBSC_GET_MY_MESSAGE':
					?><input type="checkbox" name="<?php echo $FIELD?>" value="Y"<?php echo $value == 'Y' ? ' checked="checked"' : ''?> /><?php 
					break;
				
				case 'FORUM_INTERESTS':
				case 'FORUM_SIGNATURE':
				case 'BLOG_INTERESTS':
					?><textarea name="<?php echo $FIELD?>"><?php echo $value;?></textarea><?php 
					break;
				
				case 'AUTO_TIME_ZONE':
					?><select name="AUTO_TIME_ZONE" onchange="this.form.TIME_ZONE.disabled=(this.value != 'N')">
						<option value=""><?php echo GetMessage("soc_profile_time_zones_auto_def")?></option>
						<option value="Y"<?=($value == "Y"? ' SELECTED="SELECTED"' : '')?>><?php echo GetMessage("soc_profile_time_zones_auto_yes")?></option>
						<option value="N"<?=($value == "N"? ' SELECTED="SELECTED"' : '')?>><?php echo GetMessage("soc_profile_time_zones_auto_no")?></option>
					</select><?php 
					break;
				case 'TIME_ZONE':
					?><select name="TIME_ZONE"<?php if($arResult["User"]["AUTO_TIME_ZONE"] <> "N") echo ' disabled="disabled"'?>>
					<?php foreach($arResult["TIME_ZONE_LIST"] as $tz=>$tz_name):?>
						<option value="<?=htmlspecialcharsbx($tz)?>"<?=($value == $tz? ' SELECTED="SELECTED"' : '')?>><?=htmlspecialcharsbx($tz_name)?></option>
					<?php endforeach?>
					</select><?php 
					break;
				default: 
					if (substr($FIELD, 0, 3) == 'UF_'):
						$APPLICATION->IncludeComponent(
							'bitrix:system.field.edit',
							$arResult['USER_PROPERTY_ALL'][$FIELD]['USER_TYPE_ID'],
							array(
								'arUserField' => $arResult['USER_PROPERTY_ALL'][$FIELD],
								'form_name' => 'bx_user_profile_form',
							),
							null,
							array('HIDE_ICONS' => 'Y')
						);
					else:
						?><input type="text" name="<?php echo $FIELD?>" value="<?php echo $value?>" /><?php  
					endif;
					break;
			}
?></td>
				</tr>
<?php 
		endforeach;
?>
			</table>
		</div>
<?php 
	endif;
endforeach;

if (substr($_REQUEST['backurl'],0,1) != "/")
	$_REQUEST['backurl'] = "/".$_REQUEST['backurl'];
?>
	</div>
	<div class="bx-sonet-profile-edit-buttons">
		<input type="submit" name="submit" value="<?php echo GetMessage('SOCNET_SUPE_TPL_SUBMIT')?>" />
		<input type="button" name="cancel" value="<?php echo GetMessage('SOCNET_SUPE_TPL_CANCEL')?>" onclick="location.href = '<?php echo htmlspecialcharsbx(CUtil::addslashes(
			$_REQUEST['backurl'] 
			? $_REQUEST['backurl'] 
			: CComponentEngine::MakePathFromTemplate($arParams["PATH_TO_USER"], array("user_id" => $arParams["ID"]))
		))?>'" />
	</div>
</div>
</form>