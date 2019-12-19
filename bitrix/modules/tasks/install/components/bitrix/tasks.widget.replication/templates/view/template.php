<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Tasks\UI;

Loc::loadMessages(__FILE__);

$helper = $arResult['HELPER'];
$arParams =& $helper->getComponent()->arParams; // make $arParams the same variable as $this->__component->arParams, as it really should be
?>

<?php //$helper->displayFatals();?>
<?php if(!$helper->checkHasFatals()):?>

	<div id="<?=$helper->getScopeId()?>" class="tasks tasks-replication-view <?php if($arParams['REPLICATE']):?>enabled<?php endif?>">

		<?php //$helper->displayWarnings();?>

		<?php if(!$arParams['ENTITY_ID']):?>
			<?=Loc::getMessage("TASKS_SIDEBAR_TEMPLATE_NOT_ACCESSIBLE")?>
		<?php else:?>
			<div class="js-id-replication-detail tasks-replication-detail <?=($arParams['REPLICATE'] ? '' : 'invisible')?>">
				<div class="tasks-replication-detail-inner">
					<?=Loc::getMessage("TASKS_SIDEBAR_TASK_REPEATS")?> <?=UI\Task\Template::makeReplicationPeriodString($arParams['DATA'])?>
					<?php if($arParams['ENABLE_TEMPLATE_LINK']):?>
						<br />(<a href="<?=$arParams["PATH_TO_TEMPLATES_TEMPLATE"]?>" target="_top"><?=Loc::getMessage("TASKS_COMMON_TEMPLATE_LC")?></a>)
					<?php endif?>
				</div>
			</div>
			<?php if($arParams['ENABLE_SYNC']):?>
				<span class="js-id-replication-switch task-dashed-link tasks-replication-view-switch">
					<span class="task-dashed-link-inner tasks-replication-view-enable"><?=Loc::getMessage('TASKS_TWRV_ENABLE_REPLICATION');?></span>
					<span class="task-dashed-link-inner tasks-replication-view-disable"><?=Loc::getMessage('TASKS_TWRV_DISABLE_REPLICATION');?></span>
				</span>
			<?php endif?>

			<?php $helper->initializeExtension();?>

		<?php endif?>

	</div>

<?php endif?>