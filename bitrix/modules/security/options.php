<?php 
$module_id = "security";
CModule::IncludeModule($module_id);

/**
 * @global CUser $USER
 * @global CMain $APPLICATION
 **/
$canRead = $USER->CanDoOperation('security_module_settings_read');
$canWrite = $USER->CanDoOperation('security_module_settings_write');
if($canRead || $canWrite) :

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$arSyslogFacilities = array(
	"reference_id" => array_keys(CSecurityEvent::getSyslogFacilities()),
	"reference" => CSecurityEvent::getSyslogFacilities()
);
$arSyslogPriorities = array(
	"reference_id" => array_keys(CSecurityEvent::getSyslogPriorities()),
	"reference" => CSecurityEvent::getSyslogPriorities(),
);

$arAllOptions = array(
	array("", GetMessage("SEC_OPTIONS_IPCHECK"), array("heading")),
	array("ipcheck_allow_self_block", GetMessage("SEC_OPTIONS_IPCHECK_ALLOW_SELF_BLOCK"), array("checkbox")),
	array("ipcheck_disable_file", GetMessage("SEC_OPTIONS_IPCHECK_DISABLE_FILE"), array("text", 45)),
	array("", GetMessage("SEC_OPTIONS_EVENTS"), array("heading")),
	array("security_event_format", GetMessage("SEC_OPTIONS_EVENT_FORMAT"), array("text", 60), 1),
	array("security_event_userinfo_format", GetMessage("SEC_OPTIONS_EVENT_USERINFO_FORMAT"), array("text", 60), 2),
	array("security_event_db_active", GetMessage("SEC_OPTIONS_EVENT_DB_ACTIVE"), array("checkbox")),
	array("security_event_syslog_active", GetMessage("SEC_OPTIONS_EVENT_SYSLOG_ACTIVE"), array("checkbox")),
	array("security_event_syslog_facility", GetMessage("SEC_OPTIONS_EVENT_SYSLOG_FACILITY"), array("selectbox", $arSyslogFacilities)),
	array("security_event_syslog_priority", GetMessage("SEC_OPTIONS_EVENT_SYSLOG_PRIORITY"), array("selectbox", $arSyslogPriorities)),
	array("security_event_file_active", GetMessage("SEC_OPTIONS_EVENT_FILE_ACTIVE"), array("checkbox")),
	array("security_event_file_path", GetMessage("SEC_OPTIONS_EVENT_FILE_PATH"), array("text", 45), 3),
);

$aTabs = array(
	array(
		"DIV" => "edit1",
		"TAB" => GetMessage("MAIN_TAB_SET"),
		"ICON" => "security_settings",
		"TITLE" => GetMessage("MAIN_TAB_TITLE_SET"),
	),
);
if ($USER->IsAdmin())
{
	$aTabs[] = array(
		"DIV" => "edit2",
		"TAB" => GetMessage("MAIN_TAB_RIGHTS"),
		"ICON" => "security_settings",
		"TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS"),
	);
}
$tabControl = new CAdminTabControl("tabControl", $aTabs);

if($_SERVER["REQUEST_METHOD"]=="POST" && $_REQUEST["Update"].$_REQUEST["Apply"].$_REQUEST["RestoreDefaults"] != "" && $canWrite && check_bitrix_sessid())
{

	if($_REQUEST["RestoreDefaults"] != "")
	{
		COption::RemoveOption($module_id);
	}
	else
	{
		foreach($arAllOptions as $arOption)
		{
			$name = $arOption[0];
			$val = trim($_REQUEST[$name], " \t\n\r");

			$type = $arOption[2][0];
			if ($type === 'heading')
				continue;

			if($type === 'checkbox' && $val != 'Y')
				$val = 'N';

			COption::SetOptionString($module_id, $name, $val, $arOption[1]);
		}
	}

	if ($USER->IsAdmin())
	{
		ob_start();
		$Update = $_REQUEST["Update"].$_REQUEST["Apply"];
		require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights2.php");
		ob_end_clean();
	}

	if($_REQUEST["back_url_settings"] != "")
	{
		if($_REQUEST["Update"] != "")
			LocalRedirect($_REQUEST["back_url_settings"]);

		$returnUrl = $_GET["return_url"]? urlencode($_GET["return_url"]): "";
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".$returnUrl."&".$tabControl->ActiveTabParam());
	}
	else
	{
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&".$tabControl->ActiveTabParam());
	}
}

$message = CSecurityIPRule::CheckAntiFile(true);
if($message)
	echo $message->Show();

$availableMessagePlaceholders = CSecurityEventMessageFormatter::getAvailableMessagePlaceholders();
$availableUserInfoPlaceholders = CSecurityEventMessageFormatter::getAvailableUserInfoPlaceholders();
?>
<form method="post" action="<?php echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
<?php 
$tabControl->Begin();
$tabControl->BeginNextTab();

	foreach($arAllOptions as $arOption):
	$type = $arOption[2];
	$note = $arOption[3]?: null;?>
	<?php if($type[0] == "heading"):?>
	<tr class="heading">
		<td colspan="2"><b><?php echo $arOption[1]?></b></td>
	</tr>
	<?php else:?>
	<?php $val = COption::GetOptionString($module_id, $arOption[0]);?>
	<tr>
		<td width="40%">
			<label for="<?=htmlspecialcharsbx($arOption[0])?>"><?=$arOption[1]?>
			<?php  if($note !== null):?>
				<span class="required"><sup><?=$note?></sup></span>
			<?php endif;?>
			:</label>

		</td>
		<td width="60%">
			<?php if($type[0] == "checkbox"):?>
				<input type="checkbox" name="<?php echo htmlspecialcharsbx($arOption[0])?>" id="<?php echo htmlspecialcharsbx($arOption[0])?>" value="Y"<?php if($val=="Y")echo" checked";?>>
			<?php elseif($type[0] == "text"):?>
				<input type="text" size="<?php echo $type[1]?>" maxlength="255" value="<?php echo htmlspecialcharsbx($val)?>" name="<?php echo htmlspecialcharsbx($arOption[0])?>" id="<?php echo htmlspecialcharsbx($arOption[0])?>">
			<?php elseif($type[0] == "textarea"):?>
				<textarea rows="<?php echo $type[1]?>" cols="<?php echo $type[2]?>" name="<?php echo htmlspecialcharsbx($arOption[0])?>" id="<?php echo htmlspecialcharsbx($arOption[0])?>"><?php echo htmlspecialcharsbx($val)?></textarea>
			<?php elseif($type[0] == "selectbox"):
				echo SelectBoxFromArray($arOption[0], $type[1], $val);
			endif?>
		</td>
	</tr>
	<?php endif;?>
	<?php endforeach?>
	<tr>
		<td colspan="2">
			<?=BeginNote();?>
			<p>
				<span class="required"><sup>1</sup></span>
				<?=GetMessage("SEC_OPTIONS_EVENT_MESSAGE_PLACEHOLDERS")?>:
				<?php foreach($availableMessagePlaceholders as $placeholder):?>
				<div style="margin-left: 20px;"><?=$placeholder?> - <?=getMessage("SEC_OPTIONS_EVENT_MESSAGE_PLACEHOLDER_".str_replace("#", "", $placeholder))?></div>
				<?php endforeach?>
			</p>
			<p>
				<span class="required"><sup>2</sup></span>
				<?=GetMessage("SEC_OPTIONS_EVENT_USERINFO_PLACEHOLDERS")?>:
				<?php foreach($availableUserInfoPlaceholders as $placeholder):?>
				<div style="margin-left: 20px;"><?=$placeholder?> - <?=getMessage("SEC_OPTIONS_EVENT_USERINFO_PLACEHOLDER_".str_replace("#", "", $placeholder))?></div>
				<?php endforeach?>
			</p>
			<p>
				<span class="required"><sup>3</sup></span>
				<?=GetMessage("SEC_OPTIONS_ABSOLUTE_PATH_NOTE")?>
			</p>
			<?=EndNote(); ?>
		</td>
	</tr>
<?php 
if ($USER->IsAdmin())
{
	$tabControl->BeginNextTab();
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights2.php");
}

$tabControl->Buttons();?>
	<input <?php if(!$canWrite) echo "disabled" ?> type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>">
	<input <?php if(!$canWrite) echo "disabled" ?> type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
	<?php if($_REQUEST["back_url_settings"] != "" ):?>
		<input <?php if(!$canWrite) echo "disabled" ?> type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?php echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
		<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
	<?php endif?>
	<input <?php if(!$canWrite) echo "disabled" ?> type="submit" name="RestoreDefaults" title="<?php echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" onclick="return confirm('<?php echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?php echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
	<?=bitrix_sessid_post();?>
<?php $tabControl->End();?>
</form>
<?php endif;?>