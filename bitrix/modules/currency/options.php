<?php 
/** @global CMain $APPLICATION */
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Localization\Loc;

use Bitrix\Currency;

$module_id = 'currency';
$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);
if ($moduleAccessLevel >= 'R')
{
	Loader::includeModule('currency');
	Loc::loadMessages(__FILE__);

	$aTabs = array(
		array("DIV" => "edit0", "TAB" => Loc::getMessage("CURRENCY_SETTINGS"), "ICON" => "currency_settings", "TITLE" => Loc::getMessage("CURRENCY_SETTINGS_TITLE")),
		array("DIV" => "edit1", "TAB" => Loc::getMessage("CO_TAB_RIGHTS"), "ICON" => "currency_settings", "TITLE" => Loc::getMessage("CO_TAB_RIGHTS_TITLE")),
	);
	$tabControl = new CAdminTabControl("currencyTabControl", $aTabs, true, true);

	$systemTabs = array(
		array('DIV' => 'proc_edit0', 'TAB' => Loc::getMessage('CURRENCY_BASE_RATE'), 'ICON' => '', 'TITLE' => Loc::getMessage('CURRENCY_BASE_RATE_TITLE')),
		array('DIV' => 'proc_edit1', 'TAB' => Loc::getMessage('CURRENCY_AGENTS'), 'ICON' => '', 'TITLE' => Loc::getMessage('CURRENCY_AGENTS_TITLE')),
	);
	$systemTabControl = new CAdminTabControl("currencyProcTabControl", $systemTabs, true, true);

	if (
		$_SERVER['REQUEST_METHOD'] == "GET"
		&& !empty($_GET['RestoreDefaults'])
		&& $moduleAccessLevel == "W"
		&& check_bitrix_sessid()
	)
	{
		COption::RemoveOption("currency");
		$v1="id";
		$v2="asc";
		$z = CGroup::GetList($v1, $v2, array("ACTIVE" => "Y", "ADMIN" => "N"));
		while($zr = $z->Fetch())
			$APPLICATION->DelGroupRight($module_id, array($zr["ID"]));

		LocalRedirect($APPLICATION->GetCurPage().'?lang='.LANGUAGE_ID.'&mid='.$module_id);
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && $moduleAccessLevel == "W" && check_bitrix_sessid())
	{
		if (isset($_POST['Update']) && $_POST['Update'] === 'Y')
		{
			$newBaseCurrency = '';
			if (isset($_POST['BASE_CURRENCY']))
				$newBaseCurrency = (string)$_POST['BASE_CURRENCY'];
			if ($newBaseCurrency != '')
				$res = CCurrency::SetBaseCurrency($newBaseCurrency);

			ob_start();
			require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/admin/group_rights.php');
			ob_end_clean();

			LocalRedirect($APPLICATION->GetCurPage().'?lang='.LANGUAGE_ID.'&mid='.$module_id.'&'.$tabControl->ActiveTabParam());
		}
		if (isset($_POST['procedures']) && $_POST['procedures'] === 'Y' && isset($_POST['action']) && $_POST['action'] == 'recalc')
		{
			Currency\CurrencyManager::updateBaseRates();
			LocalRedirect($APPLICATION->GetCurPage().'?lang='.LANGUAGE_ID.'&mid='.$module_id.'&'.$systemTabControl->ActiveTabParam());
		}
		if (isset($_POST['agents']) && $_POST['agents'] == 'Y' && isset($_POST['action']) && !empty($_POST['action']))
		{
			$action = (string)$_POST['action'];
			switch ($action)
			{
				case 'activate':
				case 'deactivate':
					$agentIterator = CAgent::GetList(
						array(),
						array('MODULE_ID' => 'currency','=NAME' => '\Bitrix\Currency\CurrencyManager::currencyBaseRateAgent();')
					);
					if ($currencyAgent = $agentIterator->Fetch())
					{
						$active = ($action == 'activate' ? 'Y' : 'N');
						CAgent::Update($currencyAgent['ID'], array('ACTIVE' => $active));
					}
					break;
				case 'create':
					$checkDate = DateTime::createFromTimestamp(strtotime('tomorrow 00:01:00'));;
					CAgent::AddAgent('\Bitrix\Currency\CurrencyManager::currencyBaseRateAgent();', 'currency', 'Y', 86400, '', 'Y', $checkDate->toString(), 100, false, true);
					break;
			}
			LocalRedirect($APPLICATION->GetCurPage().'?lang='.LANGUAGE_ID.'&mid='.$module_id.'&'.$systemTabControl->ActiveTabParam());
		}
	}

	$baseCurrency = Currency\CurrencyManager::getBaseCurrency();

	$tabControl->Begin();
	?>
	<form method="POST" action="<?php echo $APPLICATION->GetCurPage()?>?lang=<?php echo LANGUAGE_ID?>&mid=<?=$module_id?>" name="currency_settings">
	<?php  echo bitrix_sessid_post();

	$tabControl->BeginNextTab();
	?><tr>
	<td width="40%"><?php  echo Loc::getMessage('BASE_CURRENCY'); ?></td>
	<td width="60%"><select name="BASE_CURRENCY"><?php 
	$currencyList = Currency\CurrencyManager::getCurrencyList();
	if (!empty($currencyList))
	{
		foreach ($currencyList as $currency => $title)
		{
			?><option value="<?php  echo $currency; ?>"<?php  echo ($currency == $baseCurrency ? ' selected' : ''); ?>><?php 
				echo htmlspecialcharsex($title);
			?></option><?php 
		}
		unset($title, $currency);
	}
	unset($currencyList);
	?></select></td>
	</tr>
	<?php 
	$tabControl->BeginNextTab();

	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");

	$tabControl->Buttons();?>
<script type="text/javascript">
function RestoreDefaults()
{
	if (confirm('<?php  echo CUtil::JSEscape(Loc::getMessage("CUR_OPTIONS_BTN_HINT_RESTORE_DEFAULT_WARNING")); ?>'))
		window.location = "<?php echo $APPLICATION->GetCurPage()?>?lang=<?php  echo LANGUAGE_ID; ?>&mid=<?php  echo $module_id; ?>&RestoreDefaults=Y&<?=bitrix_sessid_get()?>";
}
</script>
	<input type="submit"<?=($moduleAccessLevel < 'W' ? ' disabled' : ''); ?> name="Update" value="<?=Loc::getMessage('CUR_OPTIONS_BTN_SAVE')?>" class="adm-btn-save" title="<?=Loc::getMessage('CUR_OPTIONS_BTN_SAVE_TITLE'); ?>">
	<input type="hidden" name="Update" value="Y">
	<input type="reset" name="reset" value="<?=Loc::getMessage('CUR_OPTIONS_BTN_RESET')?>" title="<?=Loc::getMessage('CUR_OPTIONS_BTN_RESET_TITLE'); ?>">
	<input type="button"<?=($moduleAccessLevel < 'W' ? ' disabled' : ''); ?> title="<?=Loc::getMessage("CUR_OPTIONS_BTN_HINT_RESTORE_DEFAULT")?>" onclick="RestoreDefaults();" value="<?=Loc::getMessage('CUR_OPTIONS_BTN_RESTORE_DEFAULT'); ?>">
	</form>
	<?php $tabControl->End();

	?><h2><?php  echo Loc::getMessage('CURRENCY_PROCEDURES'); ?></h2><?php 
	$systemTabControl->Begin();
	$systemTabControl->BeginNextTab();
	?><form method="POST" action="<?php echo $APPLICATION->GetCurPage();?>?lang=<?php echo LANGUAGE_ID?>&mid=<?=$module_id?>" name="currency_procedures"><?php 
	echo bitrix_sessid_post();
	?>
	<input type="hidden" name="action" value="recalc">
	<input type="submit" <?php if ($moduleAccessLevel<"W" || $baseCurrency === '') echo "disabled" ?> name="recalc" value="<?php echo Loc::getMessage('CUR_PROCEDURES_BTN_RECALC');?>">
	<input type="hidden" name="procedures" value="Y">
	</form><?php 
	$systemTabControl->BeginNextTab();
	?><form method="POST" action="<?php echo $APPLICATION->GetCurPage();?>?lang=<?php echo LANGUAGE_ID?>&mid=<?=$module_id?>" name="currency_agents"><?php 
	echo bitrix_sessid_post();
	?><h4><?php  echo Loc::getMessage('CURRENCY_BASE_RATE_AGENT'); ?></h4><?php 
	$currencyAgent = false;
	$agentIterator = CAgent::GetList(
		array(),
		array('MODULE_ID' => 'currency','=NAME' => '\Bitrix\Currency\CurrencyManager::currencyBaseRateAgent();')
	);
	if ($agentIterator)
		$currencyAgent = $agentIterator->Fetch();
	if (!empty($currencyAgent))
	{
		$currencyAgent['LAST_EXEC'] = (string)$currencyAgent['LAST_EXEC'];
		$currencyAgent['NEXT_EXEC'] = (string)$currencyAgent['NEXT_EXEC'];
		?><b><?php  echo Loc::getMessage('CURRENCY_BASE_RATE_AGENT_ACTIVE'); ?>:</b>&nbsp;<?php 
			echo ($currencyAgent['ACTIVE'] == 'Y' ? Loc::getMessage('CURRENCY_AGENTS_ACTIVE_YES') : Loc::getMessage('CURRENCY_AGENTS_ACTIVE_NO'));
		?><br><?php 
		if ($currencyAgent['LAST_EXEC'])
		{
			?><b><?php  echo Loc::getMessage('CURRENCY_AGENTS_LAST_EXEC'); ?>:</b>&nbsp;<?php  echo $currencyAgent['LAST_EXEC']; ?><br>
			<?php  if ($currencyAgent['ACTIVE'] == 'Y')
			{
				?><b><?php  echo Loc::getMessage('CURRENCY_AGENTS_NEXT_EXEC');?>:</b>&nbsp;<?php  echo $currencyAgent['NEXT_EXEC']; ?><br>
			<?php 
			}
		}
		elseif ($currencyAgent['ACTIVE'] == 'Y')
		{
			?><b><?php  echo Loc::getMessage('CURRENCY_AGENTS_PLANNED_NEXT_EXEC') ?>:</b>&nbsp;<?php  echo $currencyAgent['NEXT_EXEC']; ?><br>
			<?php 
		}
		if ($currencyAgent['ACTIVE'] != 'Y')
		{
			?><br><input type="hidden" name="action" value="activate">
			<input type="submit" name="activate" value="<?php  echo Loc::getMessage('CURRENCY_AGENTS_ACTIVATE'); ?>"><?php 
		}
		else
		{
			?><br><input type="hidden" name="action" value="deactivate">
			<input type="submit" name="deactivate" value="<?php  echo Loc::getMessage('CURRENCY_AGENTS_DEACTIVATE'); ?>"><?php 
		}
	}
	else
	{
		?><b><?php  echo Loc::getMessage('CURRENCY_BASE_RATE_AGENT_ABSENT'); ?></b><br><br>
		<input type="hidden" name="action" value="create">
		<input type="submit" name="startagent" value="<?php  echo Loc::getMessage('CURRENCY_AGENTS_CREATE_AGENT'); ?>">
		<?php 
	}

	?><input type="hidden" name="agents" value="Y">
	</form><?php 
	$systemTabControl->End();
}