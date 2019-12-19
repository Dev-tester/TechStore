<?php 
use Bitrix\Main\Localization\Loc;

if(!$USER->IsAdmin())
	return;

if (!\Bitrix\Main\Loader::includeModule('messageservice'))
{
	return;
}

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");
Loc::loadMessages(__FILE__);

include_once(__DIR__.'/default_option.php');
$arDefaultValues['default'] = $messageservice_default_option;

$arAllOptions = array(
	//array("smsru_partner", GetMessage("MESSAGESERVICE_SMSRU_PARTNER"), $messageservice_default_option['smsru_partner'], array("text", 50)),
	//array("smsru_secret_key", GetMessage("MESSAGESERVICE_SMSRU_SECRET_KEY"), $messageservice_default_option['smsru_secret_key'], array("text", 50)),
	array("clean_up_period", GetMessage("MESSAGESERVICE_CLEAN_UP_PERIOD"), "14", array("text", 3)),
	array("queue_limit", GetMessage("MESSAGESERVICE_QUEUE_LIMIT"), "5", array("text", 3)),
);

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "ib_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

if($_SERVER["REQUEST_METHOD"]=="POST" && ($_POST['Update'] || $_POST['Apply'] || $_POST['RestoreDefaults'])>0 && check_bitrix_sessid())
{
	if(strlen($_POST['RestoreDefaults'])>0)
	{
		$arDefValues = $arDefaultValues['default'];
		foreach($arDefValues as $key=>$value)
		{
			COption::RemoveOption("messageservice", $key);
		}
	}
	else
	{
		foreach($arAllOptions as $arOption)
		{
			$name=$arOption[0];
			$val=$_REQUEST[$name];
			if($arOption[3][0]=="checkbox" && $val!="Y")
				$val="N";
			COption::SetOptionString("messageservice", $name, $val, $arOption[1]);
		}
	}
	if(strlen($_POST['Update'])>0 && strlen($_REQUEST["back_url_settings"])>0)
		LocalRedirect($_REQUEST["back_url_settings"]);
	else
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
}


$tabControl->Begin();
?>
<form method="post" action="<?php echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?php echo LANGUAGE_ID?>">
	<?php $tabControl->BeginNextTab();?>
	<?php 
	foreach($arAllOptions as $arOption):
		$val = COption::GetOptionString("messageservice", $arOption[0], $arOption[2]);
		$type = $arOption[3];
		?>
		<tr>
			<td width="40%" nowrap <?php if($type[0]=="textarea") echo 'class="adm-detail-valign-top"'?>>
				<label for="<?php echo htmlspecialcharsbx($arOption[0])?>"><?php echo $arOption[1]?>:</label>
			<td width="60%">
				<?php if($type[0]=="checkbox"):?>
					<input type="checkbox" id="<?php echo htmlspecialcharsbx($arOption[0])?>" name="<?php echo htmlspecialcharsbx($arOption[0])?>" value="Y"<?php if($val=="Y")echo" checked";?>>
				<?php elseif($type[0]=="text"):?>
					<input type="text" size="<?php echo $type[1]?>" maxlength="255" value="<?php echo htmlspecialcharsbx($val)?>" name="<?php echo htmlspecialcharsbx($arOption[0])?>">
				<?php elseif($type[0]=="textarea"):?>
					<textarea rows="<?php echo $type[1]?>" cols="<?php echo $type[2]?>" name="<?php echo htmlspecialcharsbx($arOption[0])?>"><?php echo htmlspecialcharsbx($val)?></textarea>
				<?php elseif($type[0]=="selectbox"):?>
					<select name="<?php echo htmlspecialcharsbx($arOption[0])?>">
						<?php 
						foreach ($type[1] as $key => $value)
						{
							?><option value="<?= $key ?>"<?= ($key == $val) ? " selected" : "" ?>><?= $value ?></option><?php 
						}
						?>
					</select>
				<?php endif?>
			</td>
		</tr>
	<?php endforeach?>

	<tr>
		<td width="40%" nowrap>
			<label><?=GetMessage("MESSAGESERVICE_SMS_SENDER_LINK")?>:</label>
		<td width="60%" valign="top">
			<ul>
			<?php 
			foreach (Bitrix\MessageService\Sender\SmsManager::getSenders() as $sender):
				if (!$sender->isConfigurable())
				{
					continue;
				}
				/** @var \Bitrix\MessageService\Sender\BaseConfigurable $sender */
			?>
			<li><a href="<?=htmlspecialcharsbx($sender->getManageUrl())?>"><?=htmlspecialcharsbx($sender->getName())?></a></li>
			<?php endforeach;?>
			</ul>
		</td>
	</tr>
	<tr>
		<td width="40%" nowrap>
			<label><?=GetMessage("MESSAGESERVICE_SMS_SENDER_LIMITS")?>:</label>
		<td width="60%" valign="top">
			<a href="messageservice_sender_limits.php"><?=GetMessage("MESSAGESERVICE_TUNE_LINK")?></a></li>
		</td>
	</tr>

	<?php $tabControl->Buttons();?>
	<input type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
	<input type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
	<?php if(strlen($_REQUEST["back_url_settings"])>0):?>
		<input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?php echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
		<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
	<?php endif?>
	<input type="submit" name="RestoreDefaults" title="<?php echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirm('<?php echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?php echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
	<?=bitrix_sessid_post();?>
	<?php $tabControl->End();?>
</form>