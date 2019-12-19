<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

$saleModulePermissions = $APPLICATION->GetGroupRight("sale");
if ($saleModulePermissions=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

IncludeModuleLangFile(__FILE__);

\Bitrix\Main\Loader::includeModule('sale');

if(!CBXFeatures::IsFeatureEnabled('SaleAffiliate'))
{
	require($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");

	ShowError(GetMessage("SALE_FEATURE_NOT_ALLOW"));

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
	die();
}

ClearVars();

$errorMessage = "";
$bVarsFromForm = false;

$ID = IntVal($ID);
$affiliatePlanType = COption::GetOptionString("sale", "affiliate_plan_type", "N");
$simpleForm = COption::GetOptionString("sale", "lock_catalog", "Y");

if ($REQUEST_METHOD=="POST" && strlen($Update)>0 && $saleModulePermissions>="W" && check_bitrix_sessid())
{
	if (StrLen($SITE_ID) <= 0)
		$errorMessage .= GetMessage("SAPE1_NO_SITE").".<br>";
	if (StrLen($NAME) <= 0)
		$errorMessage .= GetMessage("SAPE1_NO_NAME").".<br>";

	$ACTIVE = (($ACTIVE == "Y") ? "Y" : "N");

	$BASE_RATE = str_replace(",", ".", $BASE_RATE);
	$BASE_RATE = DoubleVal($BASE_RATE);

	$MIN_PAY = str_replace(",", ".", $MIN_PAY);
	$MIN_PAY = DoubleVal($MIN_PAY);

	if ($BASE_RATE_TYPE_CMN == "P")
	{
		$BASE_RATE_TYPE = "P";
		$BASE_RATE_CURRENCY = False;
	}
	else
	{
		$BASE_RATE_TYPE = "F";
		$BASE_RATE_CURRENCY = $BASE_RATE_TYPE_CMN;

		if (StrLen($BASE_RATE_CURRENCY) <= 0)
			$errorMessage .= GetMessage("SAPE1_NO_RATE_CURRENCY").".<br>";
	}

	if ($affiliatePlanType == "N")
	{
		$MIN_PLAN_VALUE = IntVal($MIN_PLAN_VALUE);
	}
	else
	{
		$MIN_PLAN_VALUE = str_replace(",", ".", $MIN_PLAN_VALUE);
		$MIN_PLAN_VALUE = DoubleVal($MIN_PLAN_VALUE);
	}

	$NUM_SECTIONS = IntVal($NUM_SECTIONS);
	if ($NUM_SECTIONS >= 0)
	{
		for ($i = 0; $i <= $NUM_SECTIONS; $i++)
		{
			if (!isset(${"ID_".$i}))
				continue;

			if ($simpleForm == "Y")
				${"MODULE_ID_".$i} = "catalog";

			if (${"MODULE_ID_".$i} == "catalog")
			{
				if (isset(${"SECTION_SELECTOR_LEVEL_".$i}) && is_array(${"SECTION_SELECTOR_LEVEL_".$i}))
				{
					for ($j = 0, $maxCount = count(${"SECTION_SELECTOR_LEVEL_".$i}); $j < $maxCount; $j++)
					{
						if (IntVal(${"SECTION_SELECTOR_LEVEL_".$i}[$j]) > 0)
							${"SECTION_ID_".$i} = IntVal(${"SECTION_SELECTOR_LEVEL_".$i}[$j]);
					}
				}

				${"SECTION_ID_".$i} = IntVal(${"SECTION_ID_".$i});
				if (${"SECTION_ID_".$i} <= 0)
					$errorMessage .= GetMessage("SAPE1_NO_SECTION").".<br>";
			}
			else
			{
				${"MODULE_ID_".$i} = Trim(${"MODULE_ID_".$i});
				if (StrLen(${"MODULE_ID_".$i}) <= 0)
					$errorMessage .= GetMessage("SAPE1_NO_MODULE").".<br>";

				${"SECTION_ID_".$i} = Trim(${"SECTION_ID_".$i});
				if (StrLen(${"SECTION_ID_".$i}) <= 0)
					$errorMessage .= GetMessage("SAPE1_NO_SECTION").".<br>";
			}

			${"RATE_".$i} = str_replace(",", ".", ${"RATE_".$i});
			${"RATE_".$i} = DoubleVal(${"RATE_".$i});

			if (${"RATE_TYPE_CMN_".$i} == "P")
			{
				${"RATE_TYPE_".$i} = "P";
				${"RATE_CURRENCY_".$i} = False;
			}
			else
			{
				${"RATE_TYPE_".$i} = "F";
				${"RATE_CURRENCY_".$i} = ${"RATE_TYPE_CMN_".$i};

				if (StrLen(${"RATE_CURRENCY_".$i}) <= 0)
					$errorMessage .= GetMessage("SAPE1_NO_RATE_CURRENCY").".<br>";
			}
		}
	}


	if (StrLen($errorMessage) <= 0)
	{
		$arFields = array(
			"SITE_ID" => $SITE_ID,
			"NAME" => $NAME,
			"DESCRIPTION" => $DESCRIPTION,
			"ACTIVE" => $ACTIVE,
			"BASE_RATE" => $BASE_RATE,
			"BASE_RATE_TYPE" => $BASE_RATE_TYPE,
			"BASE_RATE_CURRENCY" => $BASE_RATE_CURRENCY,
			"MIN_PAY" => $MIN_PAY,
			"MIN_PLAN_VALUE" => $MIN_PLAN_VALUE
		);

		if ($ID > 0)
		{
			if (!CSaleAffiliatePlan::Update($ID, $arFields))
			{
				if ($ex = $APPLICATION->GetException())
					$errorMessage .= $ex->GetString().".<br>";
				else
					$errorMessage .= GetMessage("SAPE1_ERROR_SAVE").".<br>";
			}
		}
		else
		{
			$ID = CSaleAffiliatePlan::Add($arFields);
			$ID = IntVal($ID);
			if ($ID <= 0)
			{
				if ($ex = $APPLICATION->GetException())
					$errorMessage .= $ex->GetString().".<br>";
				else
					$errorMessage .= GetMessage("SAPE1_ERROR_SAVE").".<br>";
			}
		}
	}

	if (strlen($errorMessage) <= 0)
	{
		$arSectionIDs = array();

		$NUM_SECTIONS = IntVal($NUM_SECTIONS);
		if ($NUM_SECTIONS >= 0)
		{
			for ($i = 0; $i <= $NUM_SECTIONS; $i++)
			{
				if (!isset(${"ID_".$i}))
					continue;

				${"ID_".$i} = IntVal(${"ID_".$i});

				$arFields = array(
					"PLAN_ID" => $ID,
					"MODULE_ID" => ${"MODULE_ID_".$i},
					"SECTION_ID" => ${"SECTION_ID_".$i},
					"RATE" => ${"RATE_".$i},
					"RATE_TYPE" => ${"RATE_TYPE_".$i},
					"RATE_CURRENCY" => ${"RATE_CURRENCY_".$i}
				);
				if (${"ID_".$i} > 0)
				{
					if (!CSaleAffiliatePlanSection::Update(${"ID_".$i}, $arFields))
					{
						if ($ex = $APPLICATION->GetException())
							$errorMessage .= $ex->GetString().".<br>";
						else
							$errorMessage .= GetMessage("SAPE1_ERROR_SAVE_SECTION").".<br>";
					}
				}
				else
				{
					${"ID_".$i} = CSaleAffiliatePlanSection::Add($arFields);
					${"ID_".$i} = IntVal(${"ID_".$i});
					if (${"ID_".$i} <= 0)
					{
						if ($ex = $APPLICATION->GetException())
							$errorMessage .= $ex->GetString().".<br>";
						else
							$errorMessage .= GetMessage("SAPE1_ERROR_SAVE_SECTION").".<br>";
					}
				}
				$arSectionIDs[] = ${"ID_".$i};
			}
		}

		CSaleAffiliatePlanSection::DeleteByPlan($ID, $arSectionIDs);
	}

	if (strlen($errorMessage) <= 0)
	{
		if (strlen($apply) <= 0)
			LocalRedirect("/bitrix/admin/sale_affiliate_plan.php?lang=".LANG.GetFilterParams("filter_", false));
	}
	else
	{
		$bVarsFromForm = true;
	}
}

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sale/prolog.php");

if ($ID > 0)
	$APPLICATION->SetTitle(str_replace("#ID#", $ID, GetMessage("SAPE1_TITLE_UPDATE")));
else
	$APPLICATION->SetTitle(GetMessage("SAPE1_TITLE_ADD"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$dbPlan = CSaleAffiliatePlan::GetList(array(), array("ID" => $ID));
if (!$dbPlan->ExtractFields("str_"))
	$ID = 0;

if ($bVarsFromForm)
	$DB->InitTableVarsForEdit("b_sale_affiliate_plan", "", "str_");
?>

<?php 
$aMenu = array(
		array(
				"TEXT" => GetMessage("SAPE1_LIST"),
				"LINK" => "/bitrix/admin/sale_affiliate_plan.php?lang=".LANG.GetFilterParams("filter_"),
				"ICON" => "btn_list"
			)
	);

if ($ID > 0)
{
	$aMenu[] = array("SEPARATOR" => "Y");

	$aMenu[] = array(
			"TEXT" => GetMessage("SAPE1_ADD"),
			"LINK" => "/bitrix/admin/sale_affiliate_plan_edit.php?lang=".LANG.GetFilterParams("filter_"),
			"ICON" => "btn_new"
		);

	if ($saleModulePermissions >= "W")
	{
		$aMenu[] = array(
				"TEXT" => GetMessage("SAPE1_DELETE"),
				"LINK" => "javascript:if(confirm('".GetMessage("SAPE1_DELETE_CONF")."')) window.location='/bitrix/admin/sale_affiliate_plan.php?ID=".$ID."&action=delete&lang=".LANG."&".bitrix_sessid_get()."#tb';",
				"WARNING" => "Y",
				"ICON" => "btn_delete"
			);
	}
}
$context = new CAdminContextMenu($aMenu);
$context->Show();
?>

<?php if(strlen($errorMessage)>0)
	echo CAdminMessage::ShowMessage(Array("DETAILS"=>$errorMessage, "TYPE"=>"ERROR", "MESSAGE"=>GetMessage("SAPE1_ERROR_SAVE"), "HTML"=>true));?>


<form method="POST" action="<?php echo $APPLICATION->GetCurPage()?>?" name="form1">
<?php echo GetFilterHiddens("filter_");?>
<input type="hidden" name="Update" value="Y">
<input type="hidden" name="lang" value="<?php echo LANG ?>">
<input type="hidden" name="ID" value="<?php echo $ID ?>">
<?=bitrix_sessid_post()?>

<?php 
$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("SAPE1_PLAN"), "ICON" => "sale", "TITLE" => GetMessage("SAPE1_PLAN_PARAM")),
	array("DIV" => "edit2", "TAB" => GetMessage("SAPE1_SECTIONS"), "ICON" => "sale", "TITLE" => GetMessage("SAPE1_SECTIONS_ALT")),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
?>

<?php 
$tabControl->BeginNextTab();
?>

	<?php if ($ID > 0):?>
		<tr>
			<td width="40%">ID:</td>
			<td width="60%"><?=$ID?></td>
		</tr>
		<tr>
			<td width="40%"><?php echo GetMessage("SAPE1_TIMESTAMP_X")?></td>
			<td width="60%"><?=$str_TIMESTAMP_X?></td>
		</tr>
	<?php endif;?>
	<tr class="adm-detail-required-field">
		<td width="40%"><?php echo GetMessage("SAPE1_SITE")?></td>
		<td width="60%">
			<?php echo CSite::SelectBox("SITE_ID", $str_SITE_ID, "", "");?>
		</td>
	</tr>
	<tr class="adm-detail-required-field">
		<td><?php echo GetMessage("SAPE1_NAME")?></td>
		<td>
			<input type="text" name="NAME" size="60" maxlength="250" value="<?= $str_NAME ?>">
		</td>
	</tr>
	<tr>
		<td class="adm-detail-valign-top"><?php echo GetMessage("SAPE1_DESCR")?></td>
		<td>
			<textarea name="DESCRIPTION" rows="5" cols="60"><?= $str_DESCRIPTION ?></textarea>
		</td>
	</tr>
	<tr>
		<td><?php echo GetMessage("SAPE1_ACTIVE")?></td>
		<td>
			<input type="checkbox" name="ACTIVE" value="Y"<?php if ($str_ACTIVE == "Y") echo " checked"?>>
		</td>
	</tr>
	<tr class="adm-detail-required-field">
		<td><?php echo GetMessage("SAPE1_RATE")?></td>
		<td>
			<input type="text" name="BASE_RATE" size="10" maxlength="10" value="<?= roundEx($str_BASE_RATE, SALE_VALUE_PRECISION) ?>">
			<?php 
			if ($bVarsFromForm)
			{
				$str_BASE_RATE_TYPE_CMN = $BASE_RATE_TYPE_CMN;
			}
			else
			{
				if ($str_BASE_RATE_TYPE == "P")
					$str_BASE_RATE_TYPE_CMN = "P";
				else
					$str_BASE_RATE_TYPE_CMN = $str_BASE_RATE_CURRENCY;
			}
			?>
			<select name="BASE_RATE_TYPE_CMN">
				<option value="P"<?= ($str_BASE_RATE_TYPE_CMN == "P") ? " selected" : "" ?>>%</option>
				<?php 
				$arCurrencies = array();
				$dbCurrencyList = CCurrency::GetList(($b = "currency"), ($o = "asc"));
				while ($arCurrency = $dbCurrencyList->Fetch())
					$arCurrencies[$arCurrency["CURRENCY"]] = "[".$arCurrency["CURRENCY"]."]&nbsp;".htmlspecialcharsEx($arCurrency["FULL_NAME"]);

				foreach ($arCurrencies as $key => $value)
				{
					?><option value="<?= $key ?>"<?= ($key == $str_BASE_RATE_TYPE_CMN) ? " selected" : "" ?>><?= $value ?></option><?php 
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td><?= (($affiliatePlanType == "N") ? GetMessage("SAPE1_LIMIT") : GetMessage("SAPE1_LIMIT_HINT")) ?>:</td>
		<td>
			<input type="text" name="MIN_PLAN_VALUE" size="10" maxlength="10" value="<?= IntVal($str_MIN_PLAN_VALUE) ?>">
		</td>
	</tr>
<?php 
$tabControl->BeginNextTab();
?>

	<tr>
		<td colspan="2">
			<script language="JavaScript">
			<!--
			function ShowHideSectionBox(cnt, val)
			{
				var catalogGroupBox = document.getElementById("ID_CATALOG_GROUP_" + cnt);
				var otherGroupBox = document.getElementById("ID_OTHER_GROUP_" + cnt);

				if (val)
				{
					catalogGroupBox.style["display"] = "block";
					otherGroupBox.style["display"] = "none";
				}
				else
				{
					catalogGroupBox.style["display"] = "none";
					otherGroupBox.style["display"] = "block";
				}
			}

			function ModuleChange(cnt)
			{
				var m = eval("document.form1.MODULE_ID_" + cnt);
				if (!m)
					return;

				if (m[m.selectedIndex].value == "catalog")
					ShowHideSectionBox(cnt, true);
				else
					ShowHideSectionBox(cnt, false);
			}


			var itm_id = new Object();
			var itm_name = new Object();

			function ChlistIBlock(cnt, n_id)
			{
				var max_lev = itm_lev;
				var nex = document.form1["SECTION_SELECTOR_LEVEL_" + cnt + "[0]"];
				var iBlock = eval("document.form1.SECTION_IBLOCK_ID_" + cnt);
				var iBlockID = iBlock[iBlock.selectedIndex].value;
				if (itm_id[iBlockID])
				{
					var curlist = itm_id[iBlockID][0];
					if (curlist && curlist.length > 1)
					{
						var curlistname = itm_name[iBlockID][0];
						var nex_length = nex.length;
						while (nex_length > 1)
						{
							nex_length--;
							nex.options[nex_length] = null;
						}
						nex_length = 1;

						for (i = 1; i < curlist.length; i++)
						{
							var newoption = new Option(curlistname[i], curlist[i], false, false);
							nex.options[nex_length] = newoption;
							if (n_id == curlist[i]) nex.selectedIndex = nex_length;
							nex_length++;
						}
						startClear = 1;
					}
					else
					{
						startClear = 0;
					}
				}
				else
				{
					startClear = 0;
				}

				for (i = startClear; i < max_lev; i++)
				{
					nex = document.form1["SECTION_SELECTOR_LEVEL_" + cnt + "["+i+"]"];
					var nex_length = nex.length;
					while (nex_length > 1)
					{
						nex_length--;
						nex.options[nex_length] = null;
					}
				}
			}

			function Chlist(cnt, num, n_id)
			{
				var max_lev = itm_lev;
				var cur = document.form1["SECTION_SELECTOR_LEVEL_" + cnt + "["+num+"]"];
				var nex = document.form1["SECTION_SELECTOR_LEVEL_" + cnt + "["+(parseInt(num)+1)+"]"];
				var iBlock = document.form1["SECTION_IBLOCK_ID_" + cnt];
				var id = cur[cur.selectedIndex].value;
				var iBlockID = iBlock[iBlock.selectedIndex].value;
				var curlist = itm_id[iBlockID][id];
				if (curlist && curlist.length>1)
				{
					var curlistname = itm_name[iBlockID][id];
					var nex_length = nex.length;
					while (nex_length>1)
					{
						nex_length--;
						nex.options[nex_length] = null;
					}
					nex_length = 1;

					for (i = 1; i < curlist.length; i++)
					{
						var newoption = new Option(curlistname[i], curlist[i], false, false);
						nex.options[nex_length] = newoption;
						if (n_id == curlist[i]) nex.selectedIndex = nex_length;
						nex_length++;
					}
				}
				else
					num--;

				for (i = num + 2; i < max_lev; i++)
				{
					nex = document.form1["SECTION_SELECTOR_LEVEL_" + cnt + "["+i+"]"];
					var nex_length = nex.length;
					while (nex_length>1)
					{
						nex_length--;
						nex.options[nex_length] = null;
					}
				}
			}

			function Fnd(cnt, ar)
			{
				var iBlock = document.form1["SECTION_IBLOCK_ID_" + cnt];
				var fst = document.form1["SECTION_SELECTOR_LEVEL_" + cnt + "[0]"];
				var i = 0;

				for (i = 0; i < iBlock.length; i++)
					if (iBlock[i].value == ar[1])
						iBlock.selectedIndex = i;

				if (ar.length > 2)
					ChlistIBlock(cnt, ar[2]);

				for (i = 0; i < ar.length - 3; i++)
					Chlist(cnt, i, ar[i + 3]);

				Chlist(cnt, i);
			}
			//-->
			</script>

			<?php 
			$arIBlockCache = array();
			$arIBlockTypeCache = array();
			$maxLevel = 0;
			$dbIBlockList = CIBlock::GetList(
				array("IBLOCK_TYPE" => "ASC", "NAME" => "ASC"),
				array("ACTIVE" => "Y")
			);
			while ($arIBlock = $dbIBlockList->Fetch())
			{
				$arIBlockCache[] = $arIBlock;
				if (!array_key_exists($arIBlock["IBLOCK_TYPE_ID"], $arIBlockTypeCache))
					if ($arIBlockType = CIBlockType::GetByIDLang($arIBlock["IBLOCK_TYPE_ID"], LANG, true))
						$arIBlockTypeCache[$arIBlock["IBLOCK_TYPE_ID"]] = $arIBlockType["NAME"];

				$arSections = Array();

				$dbSectionTree = CIBlockSection::GetTreeList(
					array("IBLOCK_ID" => $arIBlock["ID"])
				);
				while ($arSectionTree = $dbSectionTree->Fetch())
				{
					if ($maxLevel < $arSectionTree["DEPTH_LEVEL"])
						$maxLevel = $arSectionTree["DEPTH_LEVEL"];

					$arSectionTree["IBLOCK_SECTION_ID"] = IntVal($arSectionTree["IBLOCK_SECTION_ID"]);

					if (!is_array($arSections[$arSectionTree["IBLOCK_SECTION_ID"]]))
						$arSections[$arSectionTree["IBLOCK_SECTION_ID"]] = array();

					$arSections[$arSectionTree["IBLOCK_SECTION_ID"]][] = array(
						"ID" => $arSectionTree["ID"],
						"NAME" => $arSectionTree["NAME"]
					);
				}

				$str1 = "";
				$str2 = "";
				foreach ($arSections as $sectionID => $arSubSection)
				{
					$str1 .= "itm_id['".$arIBlock["ID"]."']['".$sectionID."'] = new Array(0";
					$str2 .= "itm_name['".$arIBlock["ID"]."']['".$sectionID."'] = new Array(''";
					for ($i = 0, $maxCount = count($arSubSection); $i < $maxCount; $i++)
					{
						$str1 .= ", ".$arSubSection[$i]["ID"];
						$str2 .= ", '".CUtil::JSEscape($arSubSection[$i]["NAME"])."'";
					}
					$str1 .= ");\r\n";
					$str2 .= ");\r\n";
				}
				?>
				<script type="text/javascript">
				<!--
				itm_name['<?= $arIBlock["ID"] ?>'] = new Object();
				itm_id['<?= $arIBlock["ID"] ?>'] = new Object();
				<?=$str1;?>
				<?=$str2;?>
				//-->
				</script>
				<?php 
			}
			?>
			<script type="text/javascript">
			<!--
			itm_lev = <?= $maxLevel ?>;
			var aff_cnt = 0;

			function AffAddSectionRow(cnt, id, moduleID, sectionID, rate, rateCmn, ar)
			{
				var oTbl = document.getElementById("SECTIONS_TABLE");
				if (!oTbl)
					return;
				if (!id)
					id = 0;
				else
					aff_cnt++;

				if (!moduleID)
					moduleID = "catalog";
				if (!sectionID)
					sectionID = "";
				if (!rate)
					rate = 0;
				if (!rateCmn)
					rateCmn = "P";

				if (cnt < 0)
				{
					var oCntr = document.getElementById("NUM_SECTIONS");
					var cnt = parseInt(oCntr.value) + 1;
					oCntr.value = cnt;
				}

				var oRow = oTbl.insertRow(-1);
				oRow.id = "SECTION_TABLE_ROW_" + cnt;

				var str = "";

				<?php 
				if ($simpleForm != "Y")
				{
					?>
					var oCell = oRow.insertCell(-1);
					oCell.vAlign = 'top';
					str = '';
					str += '<select name="MODULE_ID_' + cnt + '" id="ID_MODULE_ID_' + cnt + '" OnChange="ModuleChange(' + cnt + ')" style="width:150px;">';
					<?php 
					$dbModuleList = CModule::GetList();
					while ($arModuleList = $dbModuleList->Fetch())
					{
						?>str += '<option value="<?= $arModuleList["ID"] ?>"><?= htmlspecialcharsbx($arModuleList["ID"]) ?></option>';<?php 
					}
					?>
					str += '</select>';

					oCell.innerHTML = str;

					var oModule = document.getElementById("ID_MODULE_ID_" + cnt);
					for (var i = 0; i < oModule.options.length; i++)
					{
						if (oModule.options[i].value == moduleID)
						{
							oModule.selectedIndex = i;
							break;
						}
					}
					<?php 
				}
				?>


				var oCell = oRow.insertCell(-1);
				oCell.vAlign = 'top';
				str = '';
				str += '<input type="hidden" name="ID_' + cnt + '" value="' + id + '">';
				str += '<div id="ID_CATALOG_GROUP_' + cnt + '" style="display: none;">';
				str += '<select name="SECTION_IBLOCK_ID_' + cnt + '" onChange="ChlistIBlock(' + cnt + ')" style="width:300px;">';
				str += '<option value="0"> - </option>';
				<?php 
				foreach ($arIBlockCache as $key => $arIBlock)
				{
					?>str += '<option value="<?= $arIBlock["ID"] ?>"><?= htmlspecialcharsbx("[".$arIBlockTypeCache[$arIBlock["IBLOCK_TYPE_ID"]]."] ".$arIBlock["NAME"]) ?></option>';<?php 
				}
				?>
				str += '</select><br>';
				<?php 
				$initValue = 0;
				for ($i = 0; $i < $maxLevel; $i++)
				{
					?>
					str += '<select name="SECTION_SELECTOR_LEVEL_' + cnt + '[<?= $i ?>]" onChange="Chlist(' + cnt + ', <?= $i ?>)" style="width:300px;">';
					str += '<option value=""><?php echo GetMessage("SAPE1_NO")?></option>';
					str += '</select><br>';
					<?php 
				}
				?>
				str += '</div>';

				str += '<div id="ID_OTHER_GROUP_' + cnt + '" style="display: block;">';
				str += '<input type="text" name="SECTION_ID_' + cnt + '" size="30" value="' + sectionID + '">';
				str += '</div>';

				oCell.innerHTML = str;

				var oCell = oRow.insertCell(-1);
				oCell.vAlign = 'top';
				str = '';
				str += '<input type="text" name="RATE_' + cnt + '" size="10" maxlength="10" value="' + rate + '">';
				str += '<select name="RATE_TYPE_CMN_' + cnt + '" id="ID_RATE_TYPE_CMN_' + cnt + '" style="width:100px;">';
				str += '<option value="P">%</option>';
				<?php 
				foreach ($arCurrencies as $key => $value)
				{
					?>str += '<option value="<?= $key ?>"><?= htmlspecialcharsbx($value) ?></option>';<?php 
				}
				?>
				str += '</select>';

				oCell.innerHTML = str;

				var oType = document.getElementById("ID_RATE_TYPE_CMN_" + cnt);
				for (var i = 0; i < oType.options.length; i++)
				{
					if (oType.options[i].value == rateCmn)
					{
						oType.selectedIndex = i;
						break;
					}
				}

				var oCell = oRow.insertCell(-1);
				oCell.vAlign = 'top';
				str = '';
				str += '<a href="javascript:if(confirm(\'<?php echo GetMessage("SAPE1_DELETE1_CONF")?>\')) AffDeleteSectionRow(' + cnt + ')"><?php echo GetMessage("SAPE1_DELETE1")?></a>';
				oCell.innerHTML = str;

				ChlistIBlock(cnt);

				<?php 
				if ($simpleForm != "Y")
				{
					?>ModuleChange(cnt);<?php 
				}
				else
				{
					?>ShowHideSectionBox(cnt, true);<?php 
				}
				?>

				if (ar && ar.length > 0)
					Fnd(cnt, ar);
				
				if (document.forms.form1.BXAUTOSAVE)
				{
					setTimeout(function() {
						var r = BX.findChildren(oRow, {tag: /^(input|select)$/i}, true);
						if (r && r.length > 0)
						{
							for (var i=0,l=r.length;i<l;i++)
							{
								r[i].form.BXAUTOSAVE.RegisterInput(r[i]);
							}
						}
					}, 10);
				}
			}

			function AffDeleteSectionRow(index)
			{
				var oTbl = document.getElementById("SECTIONS_TABLE");
				ind = -1;
				for (var i = 0; i < oTbl.rows.length; i++)
				{
					if (oTbl.rows[i].id == "SECTION_TABLE_ROW_" + index)
					{
						ind = i;
						break;
					}
				}
				if (ind >= 0)
					oTbl.deleteRow(ind);
			}
			
			BX.ready(function() {
				BX.addCustomEvent(document.forms.form1, 'onAutoSaveRestore', function(ob,data) {
					if (data['MODULE_ID_' + aff_cnt])
					{
						var i = aff_cnt;
						while (data['MODULE_ID_' + i])
						{
							AffAddSectionRow(-1);
							i++;
						}
					}
				});
			})
			//-->
			</script>

			<input type="hidden" name="NUM_SECTIONS" id="NUM_SECTIONS" value="-1">
			<table cellpadding="3" cellspacing="1" border="0" width="100%" class="internal" id="SECTIONS_TABLE">
				<tr class="heading">
					<?php 
					if ($simpleForm != "Y")
					{
						?><td><?php echo GetMessage("SAPE1_MODULE")?></td><?php 
					}
					?>
					<td><?php echo GetMessage("SAPE1_SECTION")?></td>
					<td><?php echo GetMessage("SAPE1_RATE1")?></td>
					<td>&nbsp;</td>
				</tr>
				<?php 
				$cnt = -1;
				if ($bVarsFromForm)
				{
					$NUM_SECTIONS = IntVal($NUM_SECTIONS);
					if ($NUM_SECTIONS > 0)
					{
						for ($i = 0; $i <= $NUM_SECTIONS; $i++)
						{
							if (!isset(${"ID_".$i}))
								continue;

							$cnt++;

							$str = "";
							if (IntVal(${"SECTION_ID_".$i}) > 0)
							{
								$dbSection = CIBlockSection::GetByID(${"SECTION_ID_".$i});
								if ($arSection = $dbSection->Fetch())
								{
									$dbNavChain = CIBlockSection::GetNavChain($arSection["IBLOCK_ID"], ${"SECTION_ID_".$i});
									$str = $arSection["IBLOCK_ID"];
									while ($arNavChain = $dbNavChain->Fetch())
										$str .= ",".$arNavChain["ID"];
								}
							}

							?>
							<script language="JavaScript">
							<!--
							AffAddSectionRow(-1, <?= CUtil::JSEscape(${"ID_".$i}) ?>, '<?= CUtil::JSEscape(${"MODULE_ID_".$i}) ?>', '<?= CUtil::JSEscape(${"SECTION_ID_".$i}) ?>', '<?= CUtil::JSEscape(${"RATE_".$i}) ?>', '<?= CUtil::JSEscape(${"RATE_TYPE_CMN_".$i}) ?>', <?= ((StrLen($str) > 0) ? "new Array(0, ".$str.")" : "new Array()") ?>);
							//-->
							</script>
							<?php 
						}
					}
				}
				else
				{
					$dbPlanSection = CSaleAffiliatePlanSection::GetList(array(), array("PLAN_ID" => $ID));
					while ($arPlanSection = $dbPlanSection->Fetch())
					{
						$cnt++;
						$str_MODULE_ID = $arPlanSection["MODULE_ID"];
						$str_SECTION_ID = $arPlanSection["SECTION_ID"];
						$str_RATE = $arPlanSection["RATE"];
						if ($arPlanSection["RATE_TYPE"] == "P")
							$str_RATE_TYPE_CMN = "P";
						else
							$str_RATE_TYPE_CMN = $arPlanSection["RATE_CURRENCY"];

						$str = "";
						if (IntVal($str_SECTION_ID) > 0)
						{
							$dbSection = CIBlockSection::GetByID($str_SECTION_ID);
							if ($arSection = $dbSection->Fetch())
							{
								$dbNavChain = CIBlockSection::GetNavChain($arSection["IBLOCK_ID"], $str_SECTION_ID);
								$str = $arSection["IBLOCK_ID"];
								while ($arNavChain = $dbNavChain->Fetch())
									$str .= ",".$arNavChain["ID"];
							}
						}
						?>
						<script language="JavaScript">
						<!--
						AffAddSectionRow(-1, <?= $arPlanSection["ID"] ?>, '<?= CUtil::JSEscape($str_MODULE_ID) ?>', '<?= CUtil::JSEscape($str_SECTION_ID) ?>', '<?= CUtil::JSEscape($str_RATE) ?>', '<?= CUtil::JSEscape($str_RATE_TYPE_CMN) ?>', <?= ((StrLen($str) > 0) ? "new Array(0, ".$str.")" : "new Array()") ?>);
						//-->
						</script>
						<?php 
					}
				}
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2"><input type="button" value="<?php echo GetMessage("SAPE1_ADD1")?>" OnClick="AffAddSectionRow(-1);"></td>
	</tr>

<?php 
$tabControl->EndTab();
?>

<?php 
$tabControl->Buttons(
	array(
		"disabled" => ($saleModulePermissions < "W"),
		"back_url" => "/bitrix/admin/sale_affiliate_plan.php?lang=".LANG.GetFilterParams("filter_")
	)
);
?>

<?php 
$tabControl->End();
?>

</form>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>