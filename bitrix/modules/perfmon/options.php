<?php 
$module_id = "perfmon";
$RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($RIGHT >= "R") :

	IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
	IncludeModuleLangFile(__FILE__);

	$arAllOptions = array(
		array("max_display_url", GetMessage("PERFMON_OPTIONS_MAX_DISPLAY_URL"), array("text", 6)),
		array("warning_log", GetMessage("PERFMON_OPTIONS_WARNING_LOG"), array("checkbox")),
		array("cache_log", GetMessage("PERFMON_OPTIONS_CACHE_LOG"), array("checkbox")),
		array("large_cache_log", GetMessage("PERFMON_OPTIONS_LARGE_CACHE_LOG"), array("checkbox"), GetMessage("PERFMON_OPTIONS_LARGE_CACHE_NOTE")),
		array("large_cache_size", GetMessage("PERFMON_OPTIONS_LARGE_CACHE_SIZE"), array("text", 6)),
		array("sql_log", GetMessage("PERFMON_OPTIONS_SQL_LOG"), array("checkbox")),
		array("sql_backtrace", GetMessage("PERFMON_OPTIONS_SQL_BACKTRACE"), array("checkbox")),
		array("slow_sql_log", GetMessage("PERFMON_OPTIONS_SLOW_SQL_LOG"), array("checkbox"), GetMessage("PERFMON_OPTIONS_SLOW_SQL_NOTE")),
		array("slow_sql_time", GetMessage("PERFMON_OPTIONS_SLOW_SQL_TIME"), array("text", 6)),
	);

	$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "perfmon_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
		array("DIV" => "edit2", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "perfmon_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
	);
	$tabControl = new CAdminTabControl("tabControl", $aTabs);

	CModule::IncludeModule($module_id);

	if ($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && $RIGHT == "W" && check_bitrix_sessid())
	{
		require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/perfmon/prolog.php");

		if ($_REQUEST["clear_data"] === "y")
		{
			CPerfomanceComponent::Clear();
			CPerfomanceSQL::Clear();
			CPerfomanceHit::Clear();
			CPerfomanceError::Clear();
			CPerfomanceCache::Clear();
		}

		if (array_key_exists("ACTIVE", $_REQUEST))
		{
			$ACTIVE = intval($_REQUEST["ACTIVE"]);
			CPerfomanceKeeper::SetActive($ACTIVE > 0, time() + $ACTIVE);
		}

		if (strlen($RestoreDefaults) > 0)
		{
			COption::RemoveOption("perfmon");
		}
		else
		{
			foreach ($arAllOptions as $arOption)
			{
				$name = $arOption[0];
				$val = $_REQUEST[$name];
				if ($arOption[2][0] == "checkbox" && $val != "Y")
					$val = "N";
				COption::SetOptionString("perfmon", $name, $val, $arOption[1]);
			}
		}

		ob_start();
		$Update = $Update.$Apply;
		require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
		ob_end_clean();

		if (strlen($_REQUEST["back_url_settings"]) > 0)
		{
			if ((strlen($Apply) > 0) || (strlen($RestoreDefaults) > 0))
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
	<form method="post" action="<?php  echo $APPLICATION->GetCurPage() ?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
		<?php 
		$tabControl->Begin();
		$tabControl->BeginNextTab();
		$arNotes = array();
		foreach ($arAllOptions as $arOption):
			$val = COption::GetOptionString("perfmon", $arOption[0]);
			$type = $arOption[2];
			if (isset($arOption[3]))
				$arNotes[] = $arOption[3];
			?>
			<tr>
				<td width="40%" nowrap <?php  if ($type[0] == "textarea")
					echo 'class="adm-detail-valign-top"' ?>>
					<?php  if (isset($arOption[3])): ?>
						<span class="required"><sup><?php  echo count($arNotes) ?></sup></span>
					<?php  endif; ?>
					<label for="<?php  echo htmlspecialcharsbx($arOption[0]) ?>"><?php  echo $arOption[1] ?>
						:</label>
				<td width="60%">
					<?php  if ($type[0] == "checkbox"): ?>
						<input
							type="checkbox"
							name="<?php  echo htmlspecialcharsbx($arOption[0]) ?>"
							id="<?php  echo htmlspecialcharsbx($arOption[0]) ?>"
							value="Y"<?php  if ($val == "Y") echo " checked"; ?>>
					<?php  elseif ($type[0] == "text"): ?>
						<input
							type="text"
							size="<?php  echo $type[1] ?>"
							maxlength="255"
							value="<?php  echo htmlspecialcharsbx($val) ?>"
							name="<?php  echo htmlspecialcharsbx($arOption[0]) ?>"
							id="<?php  echo htmlspecialcharsbx($arOption[0]) ?>">
						<?php  if ($arOption[0] == "slow_sql_time")
							echo GetMessage("PERFMON_OPTIONS_SLOW_SQL_TIME_SEC") ?>
						<?php  if ($arOption[0] == "large_cache_size")
							echo GetMessage("PERFMON_OPTIONS_LARGE_CACHE_SIZE_KB") ?>
					<?php 
					elseif ($type[0] == "textarea"): ?>
						<textarea
							rows="<?php  echo $type[1] ?>"
							cols="<?php  echo $type[2] ?>"
							name="<?php  echo htmlspecialcharsbx($arOption[0]) ?>"
							id="<?php  echo htmlspecialcharsbx($arOption[0]) ?>"
						><?php  echo htmlspecialcharsbx($val) ?></textarea>
					<?php endif ?>
				</td>
			</tr>
		<?php  endforeach ?>
		<?php  $ACTIVE = CPerfomanceKeeper::IsActive(); ?>
		<tr>
			<td valign="top" width="50%">
				<?php  echo GetMessage("PERFMON_OPT_ACTIVE") ?>:
			</td>
			<td valign="middle" width="50%">
				<?php  if ($ACTIVE): ?>
					<?php  echo GetMessage("PERFMON_OPT_ACTIVE_Y") ?>
				<?php  else: ?>
				<?php  echo GetMessage("PERFMON_OPT_ACTIVE_N") ?>
			</td>
			<?php  endif; ?>
		</tr>
		<?php  if ($ACTIVE): ?>
			<tr>
				<td valign="top" width="50%">
					<?php  echo GetMessage("PERFMON_OPT_ACTIVE_TO") ?>:
				</td>
				<td valign="top" width="50%">
					<?php 
					$interval = COption::GetOptionInt("perfmon", "end_time") - time();
					$hours = intval($interval / 3600);
					$interval -= $hours * 3600;
					$minutes = intval($interval / 60);
					$interval -= $minutes * 60;
					$seconds = intval($interval);
					echo GetMessage("PERFMON_OPT_MINUTES", array("#HOURS#" => $hours, "#MINUTES#" => $minutes, "#SECONDS#" => $seconds));
					?>
				</td>
			</tr>
			<tr>
				<td valign="top" width="50%">
					<label for="ACTIVE"><?php  echo GetMessage("PERFMON_OPT_SET_IN_ACTIVE") ?></label>:
				</td>
				<td valign="top" width="50%">
					<input type="checkbox" name="ACTIVE" value="0" id="ACTIVE_CKBOX">
				</td>
			</tr>
		<?php  else: ?>
			<tr>
				<td valign="top" width="50%">
					<?php  echo GetMessage("PERFMON_OPT_SET_ACTIVE") ?>:
				</td>
				<td valign="top" width="50%">
					<select name="ACTIVE" id="ACTIVE_LIST">
						<option value="0"><?php  echo GetMessage("PERFMON_OPT_INTERVAL_NO") ?></option>
						<option value="60"><?php  echo GetMessage("PERFMON_OPT_INTERVAL_60_SEC") ?></option>
						<option value="300"><?php  echo GetMessage("PERFMON_OPT_INTERVAL_300_SEC") ?></option>
						<option value="600"><?php  echo GetMessage("PERFMON_OPT_INTERVAL_600_SEC") ?></option>
						<option value="1800"><?php  echo GetMessage("PERFMON_OPT_INTERVAL_1800_SEC") ?></option>
						<option value="3600"><?php  echo GetMessage("PERFMON_OPT_INTERVAL_3600_SEC") ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<td valign="top" width="50%">
					<label for="clear_data"><?php  echo GetMessage("PERFMON_OPT_CLEAR_DATA") ?></label>
				</td>
				<td valign="top" width="50%">
					<input type="checkbox" name="clear_data" id="clear_data" value="y">
				</td>
			</tr>
		<?php endif; ?>
		<?php  $tabControl->BeginNextTab(); ?>
		<?php  require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php"); ?>
		<?php  $tabControl->Buttons(); ?>
		<input <?php  if ($RIGHT < "W")
			echo "disabled" ?> type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>"
				title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
		<input <?php  if ($RIGHT < "W")
			echo "disabled" ?> type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>"
				title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
		<?php  if (strlen($_REQUEST["back_url_settings"]) > 0): ?>
			<input
				<?php  if ($RIGHT < "W") echo "disabled" ?>
				type="button"
				name="Cancel"
				value="<?=GetMessage("MAIN_OPT_CANCEL")?>"
				title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>"
				onclick="window.location='<?php  echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"])) ?>'"
			>
			<input
				type="hidden"
				name="back_url_settings"
				value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>"
			>
		<?php  endif ?>
		<input
			type="submit"
			name="RestoreDefaults"
			title="<?php  echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS") ?>"
			onclick="return confirm('<?php  echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING")) ?>')"
			value="<?php  echo GetMessage("MAIN_RESTORE_DEFAULTS") ?>"
		>
		<?=bitrix_sessid_post();?>
		<?php  $tabControl->End(); ?>
	</form>
	<script>
		function slow_sql_log_check()
		{
			var activeCheckbox = BX('ACTIVE_LIST');
			if (activeCheckbox)
			{
				jsSelectUtils.deleteAllOptions(activeCheckbox);
				jsSelectUtils.addNewOption(activeCheckbox, '0', '<?php echo GetMessageJS("PERFMON_OPT_INTERVAL_NO")?>');
				if (
					(!BX('sql_log').checked || BX('sql_log').checked && BX('slow_sql_log').checked)
					&& (!BX('cache_log').checked || BX('cache_log').checked && BX('large_cache_log').checked)
				)
				{
					jsSelectUtils.addNewOption(activeCheckbox, '3600', '<?php echo GetMessageJS("PERFMON_OPT_INTERVAL_3600_SEC")?>');
					jsSelectUtils.addNewOption(activeCheckbox, '14400', '<?php echo GetMessageJS("PERFMON_OPT_INTERVAL_4_HOURS")?>');
					jsSelectUtils.addNewOption(activeCheckbox, '28800', '<?php echo GetMessageJS("PERFMON_OPT_INTERVAL_8_HOURS")?>');
					jsSelectUtils.addNewOption(activeCheckbox, '86400', '<?php echo GetMessageJS("PERFMON_OPT_INTERVAL_24_HOURS")?>');
					jsSelectUtils.addNewOption(activeCheckbox, '604800', '<?php echo GetMessageJS("PERFMON_OPT_INTERVAL_7_DAYS")?>');
				}
				else
				{
					jsSelectUtils.addNewOption(activeCheckbox, '60', '<?php echo GetMessageJS("PERFMON_OPT_INTERVAL_60_SEC")?>');
					jsSelectUtils.addNewOption(activeCheckbox, '300', '<?php echo GetMessageJS("PERFMON_OPT_INTERVAL_300_SEC")?>');
					jsSelectUtils.addNewOption(activeCheckbox, '600', '<?php echo GetMessageJS("PERFMON_OPT_INTERVAL_600_SEC")?>');
					jsSelectUtils.addNewOption(activeCheckbox, '1800', '<?php echo GetMessageJS("PERFMON_OPT_INTERVAL_1800_SEC")?>');
					jsSelectUtils.addNewOption(activeCheckbox, '3600', '<?php echo GetMessageJS("PERFMON_OPT_INTERVAL_3600_SEC")?>');
				}
			}
		}
		BX.ready(function ()
		{
			BX.bind(BX('sql_log'), 'click', slow_sql_log_check);
			BX.bind(BX('slow_sql_log'), 'click', slow_sql_log_check);
			BX.bind(BX('cache_log'), 'click', slow_sql_log_check);
			BX.bind(BX('large_cache_log'), 'click', slow_sql_log_check);
			slow_sql_log_check();
		});
	</script>
	<?php 
	if (!empty($arNotes))
	{
		echo BeginNote();
		foreach ($arNotes as $i => $str)
		{
			?><span class="required"><sup><?php  echo $i + 1 ?></sup></span><?php  echo $str ?><br><?php 
		}
		echo EndNote();
	}
	?>
<?php  endif; ?>
