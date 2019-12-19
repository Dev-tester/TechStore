<?php 
define("ADMIN_MODULE_NAME", "security");

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

CModule::IncludeModule('security');
IncludeModuleLangFile(__FILE__);

/**
 * @global CUser $USER
 * @global CMain $APPLICATION
 **/

$canRead = $USER->CanDoOperation('security_antivirus_settings_read');
$canWrite = $USER->CanDoOperation('security_antivirus_settings_write');
if(!$canRead && !$canWrite)
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$rsSecurityWhiteList = CSecurityAntiVirus::GetWhiteList();
if($rsSecurityWhiteList->Fetch())
	$bSecurityWhiteList = true;
else
	$bSecurityWhiteList = false;

$aTabs = array(
	array(
		"DIV" => "main",
		"TAB" => GetMessage("SEC_ANTIVIRUS_MAIN_TAB"),
		"ICON"=>"main_user_edit",
		"TITLE"=>GetMessage("SEC_ANTIVIRUS_MAIN_TAB_TITLE"),
	),
	array(
		"DIV" => "params",
		"TAB" => GetMessage("SEC_ANTIVIRUS_PARAMETERS_TAB"),
		"ICON"=>"main_user_edit",
		"TITLE"=>GetMessage("SEC_ANTIVIRUS_PARAMETERS_TAB_TITLE"),
	),
	array(
		"DIV" => "exceptions",
		"TAB" => $bSecurityWhiteList? GetMessage("SEC_ANTIVIRUS_WHITE_LIST_SET_TAB"): GetMessage("SEC_ANTIVIRUS_WHITE_LIST_TAB"),
		"ICON"=>"main_user_edit",
		"TITLE"=>GetMessage("SEC_ANTIVIRUS_WHITE_LIST_TAB_TITLE"),
	),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs, true, true);

$bVarsFromForm = false;
$returnUrl = $_GET["return_url"]? "&return_url=".urlencode($_GET["return_url"]): "";

if(
	$_SERVER["REQUEST_METHOD"] == "POST"
	&& $_REQUEST["save"].$_REQUEST["apply"].$_REQUEST["antivirus_b"] !=""
	&& $canWrite
	&& check_bitrix_sessid()
)
{

	if($_REQUEST["antivirus_b"]!="")
		CSecurityAntiVirus::SetActive($_POST["antivirus_active"]==="Y");

	$antivirus_timeout = intval($_POST["antivirus_timeout"]);
	if($antivirus_timeout <= 0)
		$antivirus_timeout = 1;
	COption::SetOptionInt("security", "antivirus_timeout", $antivirus_timeout);

	if($_POST["antivirus_action"]==="notify_only")
		COption::SetOptionString("security", "antivirus_action", "notify_only");
	else
		COption::SetOptionString("security", "antivirus_action", "replace");

	CSecurityAntiVirus::UpdateWhiteList($_POST["WHITE_LIST"]);

	if($_REQUEST["save"] != "" && $_GET["return_url"]!="")
		LocalRedirect($_GET["return_url"]);
	else
		LocalRedirect("/bitrix/admin/security_antivirus.php?lang=".LANGUAGE_ID.$returnUrl."&".$tabControl->ActiveTabParam());
}

$messageDetails = "";
if (CSecurityAntiVirus::IsActive())
{
	$messageType = "OK";
	$messageText = GetMessage("SEC_ANTIVIRUS_ON");
	if($bSecurityWhiteList || COption::GetOptionString("security", "antivirus_action") == "notify_only")
		$messageDetails = "<span style=\"font-style: italic;\">".GetMessage("SEC_ANTIVIRUS_WARNING")."</span>";
}
else
{
	$messageType = "ERROR";
	$messageText = GetMessage("SEC_ANTIVIRUS_OFF");
}

$warningMessage = "";
if(!defined("BX_SECURITY_AV_STARTED"))
{
	if(preg_match("/cgi/i", php_sapi_name()))
		$warningMessage = GetMessage("SEC_ANTIVIRUS_PREBODY_NOTFOUND_CGI", array("#PATH#" => $_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/security/tools/start.php"));
	else
		$warningMessage = GetMessage("SEC_ANTIVIRUS_PREBODY_NOTFOUND", array("#PATH#" => $_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/security/tools/start.php"));
}

$APPLICATION->SetTitle(GetMessage("SEC_ANTIVIRUS_TITLE"));

CUtil::InitJSCore();
$APPLICATION->AddHeadScript('/bitrix/js/security/admin/interface.js');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

CAdminMessage::ShowMessage(array(
			"MESSAGE" => $messageText,
			"TYPE" => $messageType,
			"DETAILS" => $messageDetails,
			"HTML" => true
		));
?>
<form method="POST" action="security_antivirus.php?lang=<?=LANGUAGE_ID?><?=htmlspecialcharsbx($returnUrl)?>" enctype="multipart/form-data" name="editform">
<?php 
$tabControl->Begin();
?>
<?php 
$tabControl->BeginNextTab();
?>
<?php if(CSecurityAntiVirus::IsActive()):?>
	<tr>
		<td colspan="2" align="left">
			<input type="hidden" name="antivirus_active" value="N">
			<input type="submit" name="antivirus_b" value="<?php echo GetMessage("SEC_ANTIVIRUS_BUTTON_OFF")?>"<?php if(!$canWrite) echo " disabled"?>>
		</td>
	</tr>
<?php else:?>
	<tr>
		<td colspan="2" align="left">
			<input type="hidden" name="antivirus_active" value="Y">
			<input type="submit" name="antivirus_b" value="<?php echo GetMessage("SEC_ANTIVIRUS_BUTTON_ON")?>"<?php if(!$canWrite) echo " disabled"?> class="adm-btn-save">
		</td>
	</tr>
<?php endif?>
<?php if(strlen($warningMessage) > 0):?>
	<tr>
		<td colspan="2" align="left">
			<?php 
				CAdminMessage::ShowMessage(array(
					"TYPE"=>"ERROR",
					"DETAILS"=>$warningMessage,
					"HTML"=>true
				));
			?>
		</td>
	</tr>
<?php endif;?>
<tr>
	<td colspan="2">
		<?php echo BeginNote();?>
		<?php echo GetMessage("SEC_ANTIVIRUS_NOTE")?>
		<p><i><?php echo GetMessage("SEC_ANTIVIRUS_LEVEL")?></i></p>
		<?php echo EndNote(); ?>
	</td>
</tr>
<?php 
$tabControl->BeginNextTab();
?>
<tr>
	<td class="adm-detail-valign-top" width="40%"><?php echo GetMessage("SEC_ANTIVIRUS_ACTION")?>:</td>
	<td width="60%">
		<label><input type="radio" name="antivirus_action" value="replace" <?php if(COption::GetOptionString("security", "antivirus_action") != "notify_only") echo "checked";?>><?php echo GetMessage("SEC_ANTIVIRUS_ACTION_REPLACE")?></span></label><br>
		<label><input type="radio" name="antivirus_action" value="notify_only" <?php if(COption::GetOptionString("security", "antivirus_action") == "notify_only") echo "checked";?>><?php echo GetMessage("SEC_ANTIVIRUS_ACTION_NOTIFY_ONLY")?></label><br>
	</td>
</tr>
<tr>
	<td><label for="antivirus_timeout"><?php echo GetMessage("SEC_ANTIVIRUS_TIMEOUT")?></label>:</td>
	<td>
		<input type="text" size="4" name="antivirus_timeout" value="<?php echo COption::GetOptionInt("security", "antivirus_timeout")?>">
	</td>
</tr>
<?php 
$tabControl->BeginNextTab();
$arWhiteList = array();
if($bVarsFromForm)
{
	if(is_array($_POST["WHITE_LIST"]))
		foreach($_POST["WHITE_LIST"] as $i => $v)
			$arWhiteList[] = htmlspecialcharsbx($v);
}
else
{
	$rs = CSecurityAntiVirus::GetWhiteList();
	while($ar = $rs->Fetch())
		$arWhiteList[] = htmlspecialcharsbx($ar["WHITE_SUBSTR"]);
}
?>
<tr>
	<td class="adm-detail-valign-top" width="40%" style="padding-top:12px;"><?php echo GetMessage("SEC_ANTIVIRUS_WHITE_LIST")?></td>
	<td width="60%">
	<table cellpadding="0" cellspacing="0" border="0" width="100%" id="tb_WHITE_LIST">
		<?php foreach($arWhiteList as $i => $white_substr):?>
			<tr><td nowrap style="padding-bottom: 3px;">
				<input type="text" size="45" name="WHITE_LIST[<?php echo $i?>]" value="<?php echo $white_substr?>">
			</td></tr>
		<?php endforeach;?>
		<?php if(!$bVarsFromForm):?>
			<tr class="security-addable-row"><td nowrap style="padding-bottom: 3px;">
				<input type="text" size="45" name="WHITE_LIST[n0]" value="">
			</td></tr>
		<?php endif;?>
			<tr><td>
				<br><input type="button" id="add-button" value="<?php echo GetMessage("SEC_ANTIVIRUS_ADD")?>">
			</td></tr>
		</table>
	</td>
</tr>
<script id="security-interface-settings" type="application/json">
	{
		"addableRows": [{
			"tableId": "tb_WHITE_LIST",
			"buttonId": "add-button"
		}]
	}
</script>
<?php 
$tabControl->Buttons(
	array(
		"disabled"=>(!$canWrite),
		"back_url"=>$_GET["return_url"]? $_GET["return_url"]: "security_iprule_list.php?lang=".LANG,
	)
);
?>
<?php echo bitrix_sessid_post();?>
<input type="hidden" name="lang" value="<?php echo LANG?>">
<?php 
$tabControl->End();
?>
</form>
<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>