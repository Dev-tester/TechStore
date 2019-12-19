<?php 
use Bitrix\Main\Page\Frame;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$statusName = "";
$statusClass = "";
if ($arResult["START_INFO"]["STATE"] == "OPENED")
{
	$statusName = GetMessage("TM_STATUS_WORK");
	$statusClass = "";
}
elseif ($arResult["START_INFO"]["STATE"] == "CLOSED")
{
	if ($arResult["START_INFO"]["CAN_OPEN"] == "REOPEN" || !$arResult["START_INFO"]["CAN_OPEN"])
	{
		$statusName = GetMessage("TM_STATUS_COMPLETED");
		$statusClass = "timeman-completed";
	}
	else
	{
		$statusName = GetMessage("TM_STATUS_START");
		$statusClass = "timeman-start";
	}
}
elseif ($arResult["START_INFO"]["STATE"] == "PAUSED")
{
	$statusName = GetMessage("TM_STATUS_PAUSED");
	$statusClass = "timeman-paused";
}
elseif ($arResult["START_INFO"]["STATE"] == "EXPIRED")
{
	$statusName = "";
	$statusClass = "timeman-expired";
}

$bInfoRow = $arResult["START_INFO"]['PLANNER']["EVENT_TIME"] != '' || $arResult["START_INFO"]['PLANNER']["TASKS_COUNT"] > 0;
$bTaskTimeRow = isset($arResult["START_INFO"]['PLANNER']['TASKS_TIMER']) && is_array($arResult["START_INFO"]['PLANNER']['TASKS_TIMER']) && $arResult["START_INFO"]['PLANNER']['TASKS_TIMER']['TIMER_STARTED_AT'] > 0;

if($bTaskTimeRow)
{
	$ts = intval($arResult['START_INFO']['PLANNER']['TASK_ON_TIMER']['TIME_SPENT_IN_LOGS']);

	if ($arResult['START_INFO']['PLANNER']['TASKS_TIMER']['TIMER_STARTED_AT'] > 0)
		$ts += (time() - $arResult['START_INFO']['PLANNER']['TASKS_TIMER']['TIMER_STARTED_AT']);

	$taskTime = sprintf("%02d:%02d:%02d", floor($ts/3600), floor($ts/60) % 60, $ts%60);

	if($arResult['START_INFO']['PLANNER']['TASK_ON_TIMER']['TIME_ESTIMATE'] > 0)
	{
		$ts = $arResult['START_INFO']['PLANNER']['TASK_ON_TIMER']['TIME_ESTIMATE'];
		$taskTime .= " / " . sprintf("%02d:%02d", floor($ts/3600), floor($ts/60) % 60);
	}
}

$isCompositeMode = defined("USE_HTML_STATIC_CACHE");
?>
<div class="timeman-container timeman-container-<?=LANGUAGE_ID?><?=(IsAmPmMode() ? " am-pm-mode" : "")?>" id="timeman-container">
	<div class="timeman-wrap"><?php 
		?><span id="timeman-block" class="timeman-block <?=($isCompositeMode ? "" : $statusClass)?>"><?php 
			?><span class="bx-time" id="timeman-timer"></span><?php 
			?><span class="timeman-right-side" id="timeman-right"><?php 
				$frame = $this->createFrame("timeman-right", false)->begin("");
				?><span class="timeman-info" id="timeman-info"<?php if(!$bInfoRow):?> style="display:none"<?php endif?>><?php 
					?><span class="timeman-event" id="timeman-event"<?php if($arResult["START_INFO"]['PLANNER']["EVENT_TIME"] == ''):?> style="display:none"<?php endif?>><?=$arResult["START_INFO"]['PLANNER']["EVENT_TIME"]?></span><?php 
					?><span class="timeman-tasks" id="timeman-tasks"<?php if($arResult["START_INFO"]['PLANNER']["TASKS_COUNT"] <= 0):?> style="display:none"<?php endif?>><?=$arResult["START_INFO"]['PLANNER']["TASKS_COUNT"]?></span><?php 
				?></span><?php 
				?><span class="timeman-task-time" id="timeman-task-time"<?php if(!$bTaskTimeRow):?> style="display:none"<?php endif?>><i></i><span id="timeman-task-timer"><?=$taskTime?></span></span><?php 
				?><span class="timeman-beginning-but" id="timeman-status-block"<?php if($bTaskTimeRow&&$bInfoRow):?> style="display:none"<?php endif?>><i></i><span id="timeman-status"><?=$statusName?></span></span>
				<script type="text/javascript">
				<?php if (!Frame::isAjaxRequest()):?>
					BX.addCustomEvent(window, "onScriptsLoaded", function() {
				<?php endif?>
						BX.message({
							"TM_STATUS_OPENED" : "<?=GetMessageJS("TM_STATUS_WORK")?>",
							"TM_STATUS_CLOSED" : "<?=GetMessageJS("TM_STATUS_START")?>",
							"TM_STATUS_PAUSED" : "<?=GetMessageJS("TM_STATUS_PAUSED")?>",
							"TM_STATUS_COMPLETED" : "<?=GetMessageJS("TM_STATUS_COMPLETED")?>",
							"TM_STATUS_EXPIRED" : "<?=GetMessageJS("TM_STATUS_EXPIRED")?>"
						});

						B24.Timemanager.init(<?=CUtil::PhpToJsObject($arResult["WORK_REPORT"]);?>);

						BX.timeman("bx_tm", <?=CUtil::PhpToJsObject($arResult["START_INFO"]);?>, "<?=SITE_ID?>");

				<?php if (!Frame::isAjaxRequest()):?>
					});
				<?php else:?>
					BX.addClass(BX("timeman-block"), "<?=$statusClass?>");
				<?php endif?>
				</script>
				<?php $frame->end()?>
			</span><?php 
			?><span class="timeman-not-closed-block"><?php 
				?><span class="timeman-not-cl-icon"></span><?php 
				?><span class="timeman-not-cl-text"><?=GetMessage("TM_STATUS_EXPIRED")?></span><?php 
			?></span><?php 
			?><span class="timeman-background" id="timeman-background"></span>
		</span>
	</div>
</div>