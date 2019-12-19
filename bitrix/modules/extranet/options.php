<?php 
if(!$USER->IsAdmin())
	return;

CModule::IncludeModule('extranet');
$module_id = "extranet";

IncludeModuleLangFile(__FILE__);

$arAllOptions = array(
	array("extranet_public_uf_code", GetMessage("EXTRANET_UF_PUBLIC_CODE"), "UF_PUBLIC", array("text", 20)),
);

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "extranet_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

$arSiteIDReference = array();
$arSiteIDReferenceID = array();
$cntSite = 0;
$rsSite = CSite::GetList(($v1="sort"), ($v2="asc"));
while ($arSite = $rsSite->Fetch())
{
	if ($arSite["ACTIVE"] == "Y")
	{
		$cntSite++;
	}
	$arSiteIDReference[] = "[".$arSite["ID"]."] ".$arSite["NAME"];
	$arSiteIDReferenceID[] = $arSite["ID"];
}

if (
	$REQUEST_METHOD == "POST"
	&& check_bitrix_sessid()
)
{
	if (strlen($_POST["RestoreDefaults"]) > 0)
	{
		COption::RemoveOption($module_id);
	}
	elseif (strlen($Update) > 0)
	{
		for ($i=0; $i<count($arAllOptions); $i++)
		{
			$name = $arAllOptions[$i][0];
			$val = $_POST[$name];
			if (
				$arAllOptions[$i][3][0] == "checkbox"
				&& $val != "Y"
			)
			{
				$val="N";
			}
			COption::SetOptionString($module_id, $name, $val);
			if($_POST[$name."_clear"] == "Y")
			{
				$func=$arAllOptions[$i][4];
				eval($func);
			}
		}
		COption::SetOptionString($module_id, "extranet_group", intval($EXTRANET_USER_GROUP_ID));
		COption::SetOptionString($module_id, "extranet_site", ($cntSite > 1 ? $EXTRANET_SITE_ID : false));
	}
	LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&".$tabControl->ActiveTabParam());
}

$EXTRANET_USER_GROUP_ID = COption::GetOptionString($module_id, "extranet_group");
$EXTRANET_SITE_ID = COption::GetOptionString($module_id, "extranet_site");
$arSiteID = array("REFERENCE" => $arSiteIDReference, "REFERENCE_ID" => $arSiteIDReferenceID);

$tabControl->Begin();
?><form method="POST" action="<?php echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&amp;lang=<?=LANGUAGE_ID?>"><?php 
$tabControl->BeginNextTab();
?>
	<tr>
		<td valign="top"><?php echo GetMessage("EXTRANET_SITE_ID")?></td>
		<td valign="middle"><?php echo SelectBoxFromArray("EXTRANET_SITE_ID", $arSiteID, $EXTRANET_SITE_ID, GetMessage("MAIN_NO"));?></td>
	</tr>
	<?php 
	for($i=0; $i<count($arAllOptions); $i++):
		$Option = $arAllOptions[$i];
		$val = COption::GetOptionString($module_id, $Option[0], $Option[2]);
		$type = $Option[3];
	?>
	<tr>
		<td valign="top" width="50%"><?php if($type[0]=="checkbox")
							echo "<label for=\"".htmlspecialcharsbx($Option[0])."\">".$Option[1]."</label>";
						else
							echo $Option[1];?></td>
		<td valign="top" width="50%"><?php 
		if($type[0]=="checkbox"):
			?><input type="checkbox" name="<?php echo htmlspecialcharsbx($Option[0])?>" id="<?php echo htmlspecialcharsbx($Option[0])?>" value="Y"<?php if($val=="Y")echo" checked";?>><?php 
		elseif($type[0]=="text"):
			?><input type="text" size="<?php echo $type[1]?>" maxlength="255" value="<?php echo htmlspecialcharsbx($val)?>" name="<?php echo htmlspecialcharsbx($Option[0])?>"><?php 
			if (strlen($Option[4])>0) :
				?>&nbsp;<label for="<?php echo htmlspecialcharsbx($Option[0])?>_clear"><?=GetMessage("EXTRANET_CLEAR")?>:</label><input type="checkbox" name="<?php echo htmlspecialcharsbx($Option[0])?>_clear" id="<?php echo htmlspecialcharsbx($Option[0])?>_clear" value="Y"><?php 
			endif;
		elseif($type[0]=="textarea"):
			?><textarea rows="<?php echo $type[1]?>" cols="<?php echo $type[2]?>" name="<?php echo htmlspecialcharsbx($Option[0])?>"><?php echo htmlspecialcharsbx($val)?></textarea><?php 
		endif;
			?></td>
	</tr>
	<?php endfor;?>
	<tr>
		<td valign="top"><?php echo GetMessage("EXTRANET_USER_GROUP")?></td>
		<td valign="middle"><?php echo SelectBox("EXTRANET_USER_GROUP_ID", CGroup::GetDropDownList("and ACTIVE='Y' and ID <> 2"), GetMessage("MAIN_NO"), htmlspecialcharsbx($EXTRANET_USER_GROUP_ID));?></td>
	</tr>
<?php $tabControl->Buttons();?>
<input type="submit" name="Update" value="<?=GetMessage("EXTRANET_SAVE")?>">
<input type="hidden" name="Update" value="Y">
<input type="reset" name="reset" value="<?=GetMessage("EXTRANET_RESET")?>">
<input type="submit" title="<?php echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirm('<?php echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?php echo GetMessage("MAIN_RESTORE_DEFAULTS")?>" name="RestoreDefaults">
<?=bitrix_sessid_post();?>
<?php $tabControl->End();?>
</form>