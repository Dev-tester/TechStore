<?php 
//<title>CommerceML</title>
IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/catalog/import_setup_templ.php');

$arSetupErrors = array();

//********************  ACTIONS  **************************************//
if (($ACTION == 'IMPORT_EDIT' || $ACTION == 'IMPORT_COPY') && $STEP == 1)
{
	if (isset($arOldSetupVars['URL_FILE_1C']))
		$URL_FILE_1C = $arOldSetupVars['URL_FILE_1C'];
	if (isset($arOldSetupVars['IBLOCK_TYPE_ID']))
		$IBLOCK_TYPE_ID = $arOldSetupVars['IBLOCK_TYPE_ID'];
	if (isset($arOldSetupVars['outFileAction']))
		$outFileAction = $arOldSetupVars['outFileAction'];
	if (isset($arOldSetupVars['CONVERT_UTF8']))
		$CONVERT_UTF8 = $arOldSetupVars['CONVERT_UTF8'];
	if (isset($arOldSetupVars['SETUP_PROFILE_NAME']))
		$SETUP_PROFILE_NAME = $arOldSetupVars['SETUP_PROFILE_NAME'];
	if (isset($arOldSetupVars['USE_TRANSLIT']))
		$USE_TRANSLIT = $arOldSetupVars['USE_TRANSLIT'];
	if (isset($arOldSetupVars['ADD_TRANSLIT']))
		$ADD_TRANSLIT = $arOldSetupVars['ADD_TRANSLIT'];
	if (isset($arOldSetupVars['keepExistingProperties']))
		$keepExistingProperties = $arOldSetupVars['keepExistingProperties'];
	if (isset($arOldSetupVars['activateFileData']))
		$activateFileData = $arOldSetupVars['activateFileData'];
}
if ($STEP > 1)
{
	if (strlen($URL_FILE_1C) > 0 && file_exists($_SERVER["DOCUMENT_ROOT"].$URL_FILE_1C) && is_file($_SERVER["DOCUMENT_ROOT"].$URL_FILE_1C))
		$DATA_FILE_NAME = $_SERVER["DOCUMENT_ROOT"].$URL_FILE_1C;

	if (strlen($DATA_FILE_NAME) <= 0)
	{
		$arSetupErrors[] = GetMessage("CICML_ERROR_NO_DATAFILE");
	}

	if (strlen($IBLOCK_TYPE_ID) <= 0)
	{
		$arSetupErrors[] = GetMessage("CICML_ERROR_NO_IBLOCKTYPE");
	}

	$USE_TRANSLIT = (isset($USE_TRANSLIT) && 'Y' == $USE_TRANSLIT ? 'Y' : 'N');
	$ADD_TRANSLIT = (isset($ADD_TRANSLIT) && 'Y' == $ADD_TRANSLIT ? 'Y' : 'N');
	$keepExistingProperties = (isset($keepExistingProperties) && 'Y' == $keepExistingProperties ? 'Y' : 'N');
	$activateFileData = (isset($activateFileData) && 'Y' == $activateFileData ? 'Y' : 'N');

	if (!empty($arSetupErrors))
	{
		$STEP = 1;
	}
}
//********************  END ACTIONS  **********************************//

$aMenu = array(
	array(
		"TEXT"=>GetMessage("CATI_ADM_RETURN_TO_LIST"),
		"TITLE"=>GetMessage("CATI_ADM_RETURN_TO_LIST_TITLE"),
		"LINK"=>"/bitrix/admin/cat_import_setup.php?lang=".LANGUAGE_ID,
		"ICON"=>"btn_list",
	)
);

$context = new CAdminContextMenu($aMenu);

$context->Show();

if (!empty($arSetupErrors))
	ShowError(implode('<br />', $arSetupErrors));

$actionParams = "";
if ($adminSidePanelHelper->isSidePanel())
{
	$actionParams = "?IFRAME=Y&IFRAME_TYPE=SIDE_SLIDER";
}
?>
<form method="POST" action="<?php  echo $APPLICATION->GetCurPage().$actionParams; ?>" ENCTYPE="multipart/form-data" name="dataload">
<?php 
$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("CAT_ADM_CML1_IMP_TAB1"), "ICON" => "store", "TITLE" => GetMessage("CAT_ADM_CML1_IMP_TAB1_TITLE")),
	array("DIV" => "edit2", "TAB" => GetMessage("CAT_ADM_CML1_IMP_TAB2"), "ICON" => "store", "TITLE" => GetMessage("CAT_ADM_CML1_IMP_TAB2_TITLE")),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs, false, true);

$tabControl->Begin();

$tabControl->BeginNextTab();

if ($STEP == 1)
{
	?><tr class="heading">
		<td colspan="2"><?php  echo GetMessage("CICML_DATA_IMPORT"); ?></td>
	</tr>
	<tr>
		<td valign="top" width="40%"><?php  echo GetMessage("CICML_F_DATAFILE2"); ?></td>
		<td valign="top" width="60%">
			<input type="text" name="URL_FILE_1C" size="40" value="<?php  echo htmlspecialcharsbx($URL_FILE_1C); ?>">
			<input type="button" value="<?php  echo GetMessage("CICML_F_BUTTON_CHOOSE"); ?>" onclick="cmlBtnSelectClick();"><?php 
CAdminFileDialog::ShowScript(
	array(
		"event" => "cmlBtnSelectClick",
		"arResultDest" => array("FORM_NAME" => "dataload", "FORM_ELEMENT_NAME" => "URL_FILE_1C"),
		"arPath" => array("PATH" => "/upload/catalog", "SITE" => SITE_ID),
		"select" => 'F',// F - file only, D - folder only, DF - files & dirs
		"operation" => 'O',// O - open, S - save
		"showUploadTab" => true,
		"showAddToMenuTab" => false,
		"fileFilter" => 'xml',
		"allowAllFiles" => true,
		"SaveConfig" => true
	)
);
		?></td>
	</tr>
	<tr>
		<td valign="top" width="40%"><?php  echo GetMessage("CICML_F_IBLOCK"); ?></td>
		<td valign="top" width="60%">
			<select name="IBLOCK_TYPE_ID" class="adm-detail-iblock-types"><?php 
				if (!isset($IBLOCK_TYPE_ID))
					$IBLOCK_TYPE_ID = '';
				?><option value="">- <?php  echo GetMessage("CICML_F_IBLOCK_SELECT"); ?> -</option><?php 
				$rsIBlockTypes = CIBlockType::GetList(array('ID' => 'ASC'));
				while ($arIBlockType = $rsIBlockTypes->Fetch())
				{
					if($arIBLang = CIBlockType::GetByIDLang($arIBlockType["ID"], LANGUAGE_ID))
					{
						?><option value="<?php  echo htmlspecialcharsbx($arIBlockType['ID']); ?>"<?php  echo ($arIBlockType['ID'] == $IBLOCK_TYPE_ID ? ' selected' : ''); ?>><?php  echo htmlspecialcharsex($arIBLang["NAME"]); ?> [<?php  echo htmlspecialcharsex($arIBlockType['ID']); ?>]</option><?php 
					}
				}
				?>
			</select>
		</td>
	</tr>
	<tr class="heading">
		<td colspan="2"><?php  echo GetMessage('CATI_ADDIT_SETTINGS'); ?></td>
	</tr>
	<tr>
		<td valign="top" width="40%"><?php  echo GetMessage("CICML_F_OUTFILEACTION"); ?>:</td>
		<td valign="top" width="60%"><?php 
			if (!isset($outFileAction) || (
				$outFileAction != 'D' && $outFileAction != 'H' && $outFileAction != 'F'
			))
			{
				$outFileAction = COption::GetOptionString("catalog", "default_outfile_action");
			}
			?><input type="radio" name="outFileAction" id="outFileAction_D" value="D" <?php if ($outFileAction == "D") echo "checked";?>> <label for="outFileAction_D"><?php echo GetMessage("CICML_OF_DEL") ?></label><br>
			<input type="radio" name="outFileAction" id="outFileAction_H" value="H" <?php if ($outFileAction=="H") echo "checked";?>> <label for="outFileAction_H"><?php echo GetMessage("CICML_OF_DEACT") ?></label><br>
			<input type="radio" name="outFileAction" id="outFileAction_F" value="F" <?php if ($outFileAction=="F") echo "checked";?>> <label for="outFileAction_F"><?php echo GetMessage("CICML_OF_KEEP") ?></label>
		</td>
	</tr>
	<tr>
		<td valign="top" width="40%"><?= GetMessage("CML_S_ACT_DATA") ?>:</td>
		<td valign="top" width="60%"><?php 
			if (!isset($activateFileData) || ('Y' != $activateFileData && 'N' != $activateFileData))
			{
				$activateFileData = 'N';
			}
			?><input type="radio" name="activateFileData" id="activateFileData_Y" value="Y" <?php if ($activateFileData=="Y") echo "checked";?>> <label for="activateFileData_Y"><?= GetMessage("CML_S_YES") ?></label><br>
			<input type="radio" name="activateFileData" id="activateFileData_N" value="N" <?php if ($activateFileData=="N") echo "checked";?>> <label for="activateFileData_N"><?= GetMessage("CML_S_NO") ?></label>
		</td>
	</tr>
	<tr>
		<td valign="top" width="40%"><?php  echo GetMessage("CML_S_KEEP_PRP"); ?>:</td>
		<td valign="top" width="60%">
			<input type="hidden" name="keepExistingProperties" id="keepExistingProperties_N" value="N">
			<input type="checkbox" name="keepExistingProperties" id="keepExistingProperties_Y" value="Y" <?php if (isset($keepExistingProperties) && $keepExistingProperties=="Y") echo "checked";?>>
		</td>
	</tr>
	<tr>
		<td valign="top" width="40%"><?php  echo GetMessage("CICML_CONVERT_UTF8"); ?>:</td>
		<td valign="top" width="60%"><?php 
			if (!isset($CONVERT_UTF8) || ('N' != $CONVERT_UTF8 && 'Y' != $CONVERT_UTF8))
			{
				$CONVERT_UTF8 = 'N';
			}
			?><input type="radio" name="CONVERT_UTF8" id="CONVERT_UTF8_N" value="N" <?php if ($CONVERT_UTF8=="N") echo "checked";?>> <label for="CONVERT_UTF8_N"><?php echo GetMessage("CICML_CONVERT_NO");?></label><br>
			<input type="radio" name="CONVERT_UTF8" id="CONVERT_UTF8_Y" value="Y" <?php if ($CONVERT_UTF8=="Y") echo "checked";?>> <label for="CONVERT_UTF8_Y"><?php echo GetMessage("CICML_CONVERT_YES");?></label>
		</td>
	</tr>
	<tr>
		<td valign="top" width="40%"><?php  echo GetMessage('CATI_USE_CODE_TRANSLIT'); ?>:</td>
		<td valign="top" width="60%">
			<input type="hidden" name="USE_TRANSLIT" id="USE_TRANSLIT_N" value="N">
			<input type="checkbox" name="USE_TRANSLIT" id="USE_TRANSLIT_Y" value="Y" <?php  echo (isset($USE_TRANSLIT) && 'Y' == $USE_TRANSLIT ? ' checked' : ''); ?>>
		</td>
	</tr>
	<tr>
		<td valign="top" width="40%"><?php  echo GetMessage('CATI_ADD_CODE_TRANSLIT'); ?>:</td>
		<td valign="top" width="60%">
			<input type="hidden" name="ADD_TRANSLIT" id="ADD_TRANSLIT_N" value="N">
			<input type="checkbox" name="ADD_TRANSLIT" id="ADD_TRANSLIT_Y" value="Y" <?php  echo (isset($ADD_TRANSLIT) && 'Y' == $ADD_TRANSLIT ? ' checked' : ''); ?>>
		</td>
	</tr>
	<?php if ($ACTION=="IMPORT_SETUP" || $ACTION == 'IMPORT_EDIT' || $ACTION == 'IMPORT_COPY')
	{
		?><tr class="heading">
			<td colspan="2"><?php  echo GetMessage("CICML_SAVE_SCHEME"); ?></td>
		</tr>
		<tr>
			<td valign="top" width="40%"><?php echo GetMessage("CICML_SSCHEME_NAME") ?>:</td>
			<td valign="top" width="60%">
				<input type="text" name="SETUP_PROFILE_NAME" size="40" value="<?php  echo htmlspecialcharsbx($SETUP_PROFILE_NAME); ?>">
			</td>
		</tr><?php 
	}
}

$tabControl->EndTab();

$tabControl->BeginNextTab();

if ($STEP == 2)
{
	$FINITE = true;
}

$tabControl->EndTab();

$tabControl->Buttons();

?>
<?php  echo bitrix_sessid_post(); ?>
<?php 
if ($ACTION == 'IMPORT_EDIT' || $ACTION == 'IMPORT_COPY')
{
	?><input type="hidden" name="PROFILE_ID" value="<?php  echo intval($PROFILE_ID); ?>"><?php 
}

if ($STEP < 2)
{
	?><input type="hidden" name="STEP" value="<?php echo intval($STEP) + 1;?>">
	<input type="hidden" name="lang" value="<?php echo LANGUAGE_ID; ?>">
	<input type="hidden" name="ACT_FILE" value="<?php echo htmlspecialcharsbx($_REQUEST["ACT_FILE"]) ?>">
	<input type="hidden" name="ACTION" value="<?php echo htmlspecialcharsbx($ACTION) ?>">
	<input type="hidden" name="SETUP_FIELDS_LIST" value="URL_FILE_1C,IBLOCK_TYPE_ID,USE_TRANSLIT,ADD_TRANSLIT,outFileAction,CONVERT_UTF8,keepExistingProperties,activateFileData">
	<input type="submit" value="<?php echo (($ACTION=="IMPORT")?GetMessage("CICML_NEXT_STEP_F"):GetMessage("CICML_SAVE"))." &gt;&gt;" ?>" name="submit_btn"><?php 
}
$tabControl->End();

?></form>
<script type="text/javascript">
<?php if ($STEP < 2):?>
tabControl.SelectTab("edit1");
tabControl.DisableTab("edit2");
<?php elseif ($STEP == 2):?>
tabControl.SelectTab("edit2");
tabControl.DisableTab("edit1");
<?php endif;?>
</script>