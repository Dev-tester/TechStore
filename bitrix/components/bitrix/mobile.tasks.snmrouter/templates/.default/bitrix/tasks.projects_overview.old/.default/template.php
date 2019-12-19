<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$frame = \Bitrix\Main\Page\Frame::getInstance();
$frame->setEnable();
$frame->setUseAppCache();
\Bitrix\Main\Data\AppCacheManifest::getInstance()->addAdditionalParam("page", "tasks.projects");
\Bitrix\Main\Data\AppCacheManifest::getInstance()->addAdditionalParam("MobileAPIVersion", CMobile::getApiVersion());
\Bitrix\Main\Data\AppCacheManifest::getInstance()->addAdditionalParam("MobilePlatform", CMobile::getPlatform());
\Bitrix\Main\Data\AppCacheManifest::getInstance()->addAdditionalParam("version", "v1.3");
\Bitrix\Main\Data\AppCacheManifest::getInstance()->addAdditionalParam("LanguageId", LANGUAGE_ID);
\Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/tasks/logic.js');
\Bitrix\Main\Page\Asset::getInstance()->addJs($templateFolder.'/../../tasks.list.controls/.default/script.js');
if (empty($arResult['PROJECTS']) )
{
	?>
	<div class="mobile-grid mobile-grid-empty" >
		<div class="mobile-grid-stub">
			<div class="mobile-grid-stub-text"><?=GetMessage('TASKS_PROJECTS_OVERVIEW_NO_DATA')?></div>
		</div>
	</div>
	<?php 
}
else
{
$APPLICATION->SetPageProperty('BodyClass', 'tasks-list-controls');
?><?=CJSCore::Init(array("mobile_fastclick"), true);?><?php 
?><div id="bx-task"><?php 
	?><div id="tasks-all-items" class="mobile-task-list">
		<div class="mobile-task-list-title"><?=GetMessage("TASKS_PROJECTS_WITH_MY_MEMBERSHIP")?></div><?php 
	$counters = array();
	foreach($arResult['PROJECTS'] as $groupId => $item)
	{
		?>
		<div class="mobile-grid-field" data-bx-id="taskgroups-group" data-group-id="<?=htmlspecialcharsbx($item["ID"])?>">
			<div class="mobile-grid-field-counter<?php if(!($item['COUNTERS']['EXPIRED'] > 0)):?> hidden<?php endif;?>" data-bx-id="taskgroups-counter" data-counter-id="TOTAL"><?=intval($item['COUNTERS']['EXPIRED'])?></div>
			<div class="mobile-grid-field-item-icon mobile-grid-field-item-icon-<?php if ($item["IMAGE"]):?>group<?php else:?>folder<?php endif;?>"><?php if ($item["IMAGE"]):?><img src="<?=$item["IMAGE"]["SRC"]?>" /><?php endif;?></div>
			<div data-bx-id="taskgroups-group-url" data-url="<?=htmlspecialcharsbx($item['PATHES']['IN_WORK'])?>" class="mobile-grid-field-item"><?=$item['TITLE']?></div>
		</div>
		<?php 
		$counters[$item["ID"]] = array(
			"TOTAL" => array(
				"VALUE" => $item['COUNTERS']['EXPIRED']
			)
		);
	}
	?>
	</div>
	<?php 
	$frame->startDynamicWithID("mobile-tasks-roles");
	?>
<script>
	BX.ready(function(){
		BX.message({PAGE_TITLE : '<?=GetMessageJS("TASKS_PROJECTS")?>'});
		FastClick.attach(BX("bx-task"));
		app.hidePopupLoader();
		var res = new BX.Mobile.Tasks.View.Bitrix.taskgroups({scope: BX('tasks-all-items')}),
			res2 = new BX.Mobile.Tasks.roles({});
		res2.instance('taskgroups', res);
		res2.dynamicActions(<?=CUtil::PhpToJsObject(array(
			'counters' => $counters,
			'userId' => intval($arParams['USER_ID'])
		))?>);
	});
</script>
	<?php 
	$frame->finishDynamicWithID("mobile-tasks-roles", $stub = "", $containerId = null, $useBrowserStorage = true);
	?></div>
</div><?php 
}
