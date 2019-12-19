<?php 
/** @global CUser $USER */
/** @global CMain $APPLICATION */
use Bitrix\Main,
	Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id = "catalog";

if ($USER->CanDoOperation('catalog_read')) :

	if ($ex = $APPLICATION->GetException())
	{
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
		ShowError($ex->GetString());
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
		die();
	}

	Loc::loadMessages(__FILE__);

	if (Loader::includeModule('iblock')):

		$arIBlockType = array(
			"-" => Loc::getMessage("CAT_1C_CREATE"),
		);
		$rsIBlockType = CIBlockType::GetList(array("sort"=>"asc"), array("ACTIVE"=>"Y"));
		while ($arr=$rsIBlockType->Fetch())
		{
			if($ar=CIBlockType::GetByIDLang($arr["ID"], LANGUAGE_ID))
				$arIBlockType[$arr["ID"]] = "[".$arr["ID"]."] ".$ar["NAME"];
			unset($ar);
		}
		unset($arr, $rsIBlockType);

		$rsSite = CSite::GetList($by="sort", $order="asc", $arFilter=array("ACTIVE" => "Y"));
		$arSites = array(
			"-" => Loc::getMessage("CAT_1C_CURRENT"),
		);
		while ($arSite = $rsSite->GetNext())
			$arSites[$arSite["LID"]] = $arSite["NAME"];
		unset($arSite, $rsSite);

		$arUGroupsEx = Array();
		$dbUGroups = CGroup::GetList($by = "c_sort", $order = "asc");
		while($arUGroups = $dbUGroups -> Fetch())
		{
			$arUGroupsEx[$arUGroups["ID"]] = $arUGroups["NAME"];
		}

		$arAction = array(
			"N" => Loc::getMessage("CAT_1C_NONE"),
			"A" => Loc::getMessage("CAT_1C_DEACTIVATE"),
			"D" => Loc::getMessage("CAT_1C_DELETE"),
		);

		$arBaseOptions = array(
			array("1C_IBLOCK_TYPE", Loc::getMessage("CAT_1C_IBLOCK_TYPE"), "-", array("list", $arIBlockType)),
			array("1C_SITE_LIST", Loc::getMessage("CAT_1C_SITE_LIST"), "-", array("list", $arSites)),
			array("1C_GROUP_PERMISSIONS", Loc::getMessage("CAT_1C_GROUP_PERMISSIONS"), "1", array("mlist", 5, $arUGroupsEx)),
			array("1C_USE_OFFERS", Loc::getMessage("CAT_1C_USE_OFFERS_2"), "N", array("checkbox")),
			array("1C_TRANSLIT_ON_ADD", Loc::getMessage("CAT_1C_TRANSLIT_ON_ADD_2"), "Y", array("checkbox")),
			array("1C_TRANSLIT_ON_UPDATE", Loc::getMessage("CAT_1C_TRANSLIT_ON_UPDATE_2"), "Y", array("checkbox")),
			array("1C_TRANSLIT_REPLACE_CHAR", Loc::getMessage("CAT_1C_TRANSLIT_REPLACE_CHAR"), "_", array("text", 1)),
		);

		$arExtOptions = array(
			array("1C_INTERVAL", Loc::getMessage("CAT_1C_INTERVAL"), "30", array("text", 20)),
			array("1C_FILE_SIZE_LIMIT", Loc::getMessage("CAT_1C_FILE_SIZE_LIMIT"), 200*1024, array("text", 20)),
			array("1C_USE_ZIP", Loc::getMessage("CAT_1C_USE_ZIP"), "Y", array("checkbox")),
			array("1C_USE_CRC", Loc::getMessage("CAT_1C_USE_CRC"), "Y", array("checkbox")),
			array("1C_ELEMENT_ACTION", Loc::getMessage("CAT_1C_ELEMENT_ACTION_2"), "D", array("list", $arAction)),
			array("1C_SECTION_ACTION", Loc::getMessage("CAT_1C_SECTION_ACTION_2"), "D", array("list", $arAction)),
			array("1C_FORCE_OFFERS", Loc::getMessage("CAT_1C_FORCE_OFFERS_2"), "N", array("checkbox")),
			array("1C_USE_IBLOCK_TYPE_ID", Loc::getMessage("CAT_1C_USE_IBLOCK_TYPE_ID"), "N", array("checkbox")),
			array("1C_SKIP_ROOT_SECTION", Loc::getMessage("CAT_1C_SKIP_ROOT_SECTION_2"), "N", array("checkbox")),
			array("1C_DISABLE_CHANGE_PRICE_NAME", Loc::getMessage("CAT_1C_DISABLE_CHANGE_PRICE_NAME"), "N", array("checkbox")),
			array(
				"1C_IBLOCK_CACHE_MODE",
				Loc::getMessage("CAT_1C_IBLOCK_CACHE_MODE"),
				"-",
				array("list", CIBlockCMLImport::getIblockCacheModeList(true))
			),
			array("1C_USE_IBLOCK_PICTURE_SETTINGS", Loc::getMessage("CAT_1C_USE_IBLOCK_PICTURE_SETTINGS"), "N", array("checkbox")),
			array("1C_GENERATE_PREVIEW", Loc::getMessage("CAT_1C_GENERATE_PREVIEW"), "Y", array("checkbox")),
			array("1C_PREVIEW_WIDTH", Loc::getMessage("CAT_1C_PREVIEW_WIDTH"), 100, array("text", 20)),
			array("1C_PREVIEW_HEIGHT", Loc::getMessage("CAT_1C_PREVIEW_HEIGHT"), 100, array("text", 20)),
			array("1C_DETAIL_RESIZE", Loc::getMessage("CAT_1C_DETAIL_RESIZE"), "Y", array("checkbox")),
			array("1C_DETAIL_WIDTH", Loc::getMessage("CAT_1C_DETAIL_WIDTH"), 300, array("text", 20)),
			array("1C_DETAIL_HEIGHT", Loc::getMessage("CAT_1C_DETAIL_HEIGHT"), 300, array("text", 20)),
		);

		$arOptionsDeps = array(
			"catalog_1C_USE_IBLOCK_PICTURE_SETTINGS" => array(
				"catalog_1C_GENERATE_PREVIEW",
				"catalog_1C_PREVIEW_WIDTH",
				"catalog_1C_PREVIEW_HEIGHT",
				"catalog_1C_DETAIL_RESIZE",
				"catalog_1C_DETAIL_WIDTH",
				"catalog_1C_DETAIL_HEIGHT",
			),
		);

		if ($_SERVER['REQUEST_METHOD'] == "POST" && strlen($Update)>0 && $USER->CanDoOperation('edit_php') && check_bitrix_sessid())
		{
			$arDisableOptions = array();
			foreach ($arOptionsDeps as $option => $subOptions)
			{
				if (isset($_REQUEST[$option]) && (string)$_REQUEST[$option] == 'Y')
				{
					$arDisableOptions = (
						empty($arDisableOptions) ?
						array_fill_keys($subOptions, true) :
						array_merge($arDisableOptions, array_fill_keys($subOptions, true))
					);
				}
			}

			foreach ($arBaseOptions as $Option)
			{
				$name = $Option[0];
				$reqName = 'catalog_'.$name;
				if (isset($_REQUEST[$reqName]) && !isset($arDisableOptions[$reqName]))
				{
					$val = $_REQUEST[$reqName];
					if ($Option[3][0] == "checkbox" && $val != "Y")
						$val = "N";
					if ($Option[3][0] == "mlist" && is_array($val))
						$val = implode(",", $val);
					Main\Config\Option::set('catalog', $name, $val, '');
				}
			}

			foreach ($arExtOptions as $Option)
			{
				$name = $Option[0];
				$reqName = 'catalog_'.$name;
				if (isset($_REQUEST[$reqName]) && !isset($arDisableOptions[$reqName]))
				{
					$val = $_REQUEST[$reqName];
					if ($Option[3][0] == "checkbox" && $val != "Y")
						$val = "N";
					if ($Option[3][0] == "mlist")
						$val = implode(",", $val);
					Main\Config\Option::set('catalog', $name, $val, '');
				}
			}

			return;
		}

		$showExtOptions = false;
		foreach($arExtOptions as $Option)
		{
			$val = (string)Main\Config\Option::get('catalog', $Option[0], $Option[2]);
			if ($val != $Option[2])
				$showExtOptions = true;
		}

		foreach($arBaseOptions as $Option)
		{
			$val = (string)Main\Config\Option::get('catalog', $Option[0], $Option[2]);
			$type = $Option[3];
			$strOptionName = htmlspecialcharsbx("catalog_".$Option[0]);
			?>
		<tr>
			<td <?php  echo ('textarea' == $type[0] || 'mlist' == $type[0] ? 'valign="top"' : ''); ?> width="40%"><?php 	if($type[0]=="checkbox")
							echo '<label for="'.$strOptionName.'">'.$Option[1].'</label>';
						else
							echo $Option[1];?>:</td>
			<td width="60%">
					<?php if($type[0]=="checkbox"):?>
						<input type="hidden" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>_N" value="N">
						<input type="checkbox" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>" value="Y"<?php if($val=="Y")echo" checked";?> onclick="Check(this.id);">
					<?php elseif($type[0]=="text"):?>
						<input type="text" size="<?php echo $type[1]?>" maxlength="255" value="<?php echo htmlspecialcharsbx($val)?>" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>">
					<?php elseif($type[0]=="textarea"):?>
						<textarea rows="<?php echo $type[1]?>" cols="<?php echo $type[2]?>" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>"><?php echo htmlspecialcharsbx($val)?></textarea>
					<?php elseif($type[0]=="list"):?>
						<select name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>">
						<?php foreach($type[1] as $key=>$value):?>
							<option value="<?php echo htmlspecialcharsbx($key)?>" <?php if($val==$key) echo "selected"?>><?php echo htmlspecialcharsbx($value)?></option>
						<?php endforeach?>
						</select>
					<?php elseif($type[0]=="mlist"):
						$val = explode(",", $val)?>
						<select multiple name="<?php echo $strOptionName; ?>[]" size="<?php echo $type[1]?>" id="<?php echo $strOptionName; ?>">
						<?php foreach($type[2] as $key=>$value):?>
							<option value="<?php echo htmlspecialcharsbx($key)?>" <?php if(in_array($key, $val)) echo "selected"?>><?php echo htmlspecialcharsbx($value)?></option>
						<?php endforeach?>
						</select>
					<?php endif?>
			</td>
		</tr>
		<?php 
		}
		?>
		<tr class="heading">
			<td id="td_extended_options" colspan="2">
				<?php if ($showExtOptions):?>
					<?php echo Loc::getMessage("CAT_1C_EXTENDED_SETTINGS")?>
				<?php else:?>
					<a class="bx-action-href" href="javascript:showExtOptions()"><?php echo Loc::getMessage("CAT_1C_EXTENDED_SETTINGS")?></a>
				<?php endif;?>
			</td>
		</tr>
		<?php 
		foreach($arExtOptions as $Option)
		{
			$val = (string)Main\Config\Option::get('catalog', $Option[0], $Option[2]);
			$type = $Option[3];
			$strOptionName = htmlspecialcharsbx("catalog_".$Option[0]);
			?>
		<tr id="tr_<?php echo htmlspecialcharsbx($Option[0])?>" <?php if (!$showExtOptions) echo 'style="display:none"'?>>
			<td <?php  echo ('textarea' == $type[0] || 'mlist' == $type[0] ? 'valign="top"' : ''); ?> width="40%"><?php 	if($type[0]=="checkbox")
							echo '<label for="'.$strOptionName.'">'.$Option[1].'</label>';
						else
							echo $Option[1];?>:</td>
			<td width="60%">
					<?php if($type[0]=="checkbox"):?>
						<input type="hidden" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>_N" value="N">
						<input type="checkbox" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>" value="Y"<?php if($val=="Y")echo" checked";?> onclick="Check(this.id);">
					<?php elseif($type[0]=="text"):?>
						<input type="text" size="<?php echo $type[1]?>" maxlength="255" value="<?php echo htmlspecialcharsbx($val)?>" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>">
					<?php elseif($type[0]=="textarea"):?>
						<textarea rows="<?php echo $type[1]?>" cols="<?php echo $type[2]?>" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>"><?php echo htmlspecialcharsbx($val)?></textarea>
					<?php elseif($type[0]=="list"):?>
						<select name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>">
						<?php foreach($type[1] as $key=>$value):?>
							<option value="<?php echo htmlspecialcharsbx($key)?>" <?php if($val==$key) echo "selected"?>><?php echo htmlspecialcharsbx($value)?></option>
						<?php endforeach?>
						</select>
					<?php elseif($type[0]=="mlist"):
						$val = explode(",", $val)?>
						<select multiple name="<?php echo $strOptionName; ?>[]" size="<?php echo $type[1]?>" id="<?php echo $strOptionName; ?>">
						<?php foreach($type[2] as $key=>$value):?>
							<option value="<?php echo htmlspecialcharsbx($key)?>" <?php if(in_array($key, $val)) echo "selected"?>><?php echo htmlspecialcharsbx($value)?></option>
						<?php endforeach?>
						</select>
					<?php endif?>
			</td>
		</tr>
		<?php 
		}
		if (!$USER->CanDoOperation('edit_php'))
		{
			?><tr><td colspan="2"><?php 
				echo BeginNote();
				echo GetMessage('CAT_1C_SETTINGS_SAVE_DENIED');
				echo EndNote();
			?></td></tr><?php 
		}
		?>
	<script type="text/javascript">
	var controls = <?php echo CUtil::PhpToJSObject($arOptionsDeps)?>;
	function Check(checkbox)
	{
		var i, mainCheckbox;
		if (!!controls[checkbox] && BX.type.isArray(controls[checkbox]))
		{
			mainCheckbox = BX(checkbox);
			if (!!mainCheckbox)
			{
				for (i = 0;i < controls[checkbox].length; i++)
				{
					if (!!BX(controls[checkbox][i]))
					{
						BX(controls[checkbox][i]).disabled = mainCheckbox.checked;
					}
				}
			}
		}
	}
	var bExtOptions = <?php echo $showExtOptions? 'true': 'false'?>;
	function showExtOptions()
	{
		if (bExtOptions)
		{
		<?php foreach($arExtOptions as $Option):?>
			BX('<?php echo CUtil::JSEscape('tr_'.$Option[0])?>').style.display = 'none';
		<?php endforeach;?>
		}
		else
		{
		<?php foreach($arExtOptions as $Option):?>
			BX('<?php echo CUtil::JSEscape('tr_'.$Option[0])?>').style.display = 'table-row';
		<?php endforeach;?>
		}
		bExtOptions = !bExtOptions;
		BX.onCustomEvent('onAdminTabsChange');
	}
	BX.ready(function(){
		<?php foreach($arOptionsDeps as $key => $value):?>
			Check('<?php echo CUtil::JSEscape($key)?>');
		<?php endforeach;?>
	});
	</script>
		<?php 

	else:
		CAdminMessage::ShowMessage(Loc::getMessage("CAT_NO_IBLOCK_MOD"));
	endif;

endif;