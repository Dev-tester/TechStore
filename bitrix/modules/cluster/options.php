<?php 
$module_id = "cluster";
$RIGHT = $APPLICATION->GetGroupRight($module_id);
if($RIGHT >= "R") :

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$arAllOptions = Array(
	array("max_slave_delay", GetMessage("CLUSTER_OPTIONS_MAX_SLAVE_DELAY")." ", array("text", 6)),
);

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => $module_id."_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

CModule::IncludeModule($module_id);

if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && $RIGHT=="W" && check_bitrix_sessid())
{
	if(strlen($RestoreDefaults)>0)
	{
		COption::RemoveOption($module_id);
	}
	else
	{
		foreach($arAllOptions as $arOption)
		{
			$name=$arOption[0];
			$val=$_REQUEST[$name];
			if($arOption[2][0]=="checkbox" && $val!="Y")
				$val="N";
			COption::SetOptionString($module_id, $name, $val, $arOption[1]);
		}
	}

	if(strlen($_REQUEST["back_url_settings"]) > 0)
	{
		if((strlen($Apply) > 0) || (strlen($RestoreDefaults) > 0))
			LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
		else
			LocalRedirect($_REQUEST["back_url_settings"]);
	}
	else
	{
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&".$tabControl->ActiveTabParam());
	}
}

?>
<form method="post" action="<?php echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
<?php 
$tabControl->Begin();
$tabControl->BeginNextTab();

	foreach($arAllOptions as $arOption):
		$val = COption::GetOptionString($module_id, $arOption[0]);
		$type = $arOption[2];
	?>
	<tr>
		<td width="40%" nowrap <?php if($type[0]=="textarea") echo 'class="adm-detail-valign-top"'?>>
			<label for="<?php echo htmlspecialcharsbx($arOption[0])?>"><?php echo $arOption[1]?>:</label>
		<td width="60%">
			<?php if($type[0]=="checkbox"):?>
				<input type="checkbox" name="<?php echo htmlspecialcharsbx($arOption[0])?>" id="<?php echo htmlspecialcharsbx($arOption[0])?>" value="Y"<?php if($val=="Y")echo" checked";?>>
			<?php elseif($type[0]=="text"):?>
				<input type="text" size="<?php echo $type[1]?>" maxlength="255" value="<?php echo htmlspecialcharsbx($val)?>" name="<?php echo htmlspecialcharsbx($arOption[0])?>" id="<?php echo htmlspecialcharsbx($arOption[0])?>">
			<?php elseif($type[0]=="textarea"):?>
				<textarea rows="<?php echo $type[1]?>" cols="<?php echo $type[2]?>" name="<?php echo htmlspecialcharsbx($arOption[0])?>" id="<?php echo htmlspecialcharsbx($arOption[0])?>"><?php echo htmlspecialcharsbx($val)?></textarea>
			<?php endif?>
		</td>
	</tr>
	<?php endforeach?>
<?php $tabControl->Buttons();?>
	<input <?php if ($RIGHT<"W") echo "disabled" ?> type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
	<input <?php if ($RIGHT<"W") echo "disabled" ?> type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
	<?php if(strlen($_REQUEST["back_url_settings"])>0):?>
		<input <?php if ($RIGHT<"W") echo "disabled" ?> type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?php echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
		<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
	<?php endif?>
	<input type="submit" name="RestoreDefaults" title="<?php echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="confirm('<?php echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?php echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
	<?=bitrix_sessid_post();?>
<?php $tabControl->End();?>
</form>
<?php endif;?>
