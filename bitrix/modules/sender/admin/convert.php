<?php 
define("ADMIN_MODULE_NAME", "sender");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

use \Bitrix\Sender\Internals\CounterCalculation;

if(!\Bitrix\Main\Loader::includeModule("sender"))
	ShowError(\Bitrix\Main\Localization\Loc::getMessage("MAIN_MODULE_NOT_INSTALLED"));

IncludeModuleLangFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("sender");
if($POST_RIGHT <= "R")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

if($REQUEST_METHOD == "POST" && $POST_RIGHT=="W" && check_bitrix_sessid())
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");

	CounterCalculation::$maxExecutionTime = intval($max_execution_time);
	if($do_not_run == 'Y' || CounterCalculation::update())
	{
		$messageProgress = GetMessage("sender_convert_status_run");
		?><script>started = true; MoveProgress();</script><?php 
	}
	else
	{
		CAdminNotify::DeleteByTag('sender_counters_16');

		$messageProgress = GetMessage("sender_convert_status_done");
		?><script>EndConvert();</script><?php 
	}

	$completedPercents = CounterCalculation::getCompletedPercent();
	$currentStep = $completedPercents['CURRENT'];
	$stepsCount = $completedPercents['ALL'];

	$message = array(
		"MESSAGE" => GetMessage("sender_convert_status_title"),
		"DETAILS" => $messageProgress . '#PROGRESS_BAR#',
		"HTML"=>true,
		"TYPE"=>"PROGRESS",
		"PROGRESS_TOTAL" => $stepsCount,
		"PROGRESS_VALUE" => $currentStep,
		"BUTTONS" => array()
	);

	$adminMessage = new CAdminMessage($message);
	echo $adminMessage->show();

	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
}


$APPLICATION->SetTitle(GetMessage("sender_convert_title"));
$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("sender_convert_tab_convert_name"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("sender_convert_tab_convert_title")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<script type="text/javascript">
	var stop = false;
	var started = false;
	function StartConvert()
	{
		stop = false;
		disableButton('start_button', true);
		disableButton('stop_button', false);
		MoveProgress();
	}
	function StopConvert()
	{
		stop = true;
		disableButton('start_button', false);
		disableButton('stop_button', true);
	}
	function EndConvert()
	{
		stop = true;
		disableButton('start_button', true);
		disableButton('stop_button', true);
	}
	function disableButton(id, cond)
	{
		if(document.getElementById(id))
			document.getElementById(id).disabled = cond;
	}
	function MoveProgress()
	{
		if(stop)
			return;

		var data = {};
		data['max_execution_time'] = document.getElementById('max_execution_time').value;
		data['do_not_run'] = started ? 'N' : 'Y';

		var url = '/bitrix/admin/sender_convert.php?lang=<?php echo LANGUAGE_ID?>&<?php echo bitrix_sessid_get()?>';
		ShowWaitWindow();

		BX.ajax.post(
			url,
			data,
			function(result){
				CloseWaitWindow();
				document.getElementById('progress_message').innerHTML = result;

				if(!stop)
					disableButton('start_button', true);
				else
					disableButton('stop_button', true);
			}
		);
	}
</script>
<div id="progress_message"></div>
<form method="POST" Action="<?php echo $APPLICATION->GetCurPage()?>" name="post_form">
<?php 
$tabControl->Begin();
?>
<?php 
$tabControl->BeginNextTab();
?>
	<?php 
	$max_execution_time = intval(COption::GetOptionString("sender", "interval", 10));
	if($max_execution_time <= 0)
	{
		$max_execution_time = '';
	}
	?>
	<tr>
		<td width="40%"><?=GetMessage("sender_convert_form_time_name")?></td>
		<td>
			<input type="text" name="max_execution_time" id="max_execution_time" size="3" value="<?php echo $max_execution_time;?>">
			<?=GetMessage("sender_convert_form_time_desc")?>
		</td>
	</tr>

	<?php echo bitrix_sessid_post();?>
<?php 
$tabControl->Buttons();
?>
	<input type="button" id="start_button" value="<?=GetMessage("sender_convert_form_button_start")?>" onclick="StartConvert();">
	<input type="button" id="stop_button" value="<?=GetMessage("sender_convert_form_button_stop")?>" onclick="StopConvert();" disabled>
<?php 
$tabControl->End();
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>