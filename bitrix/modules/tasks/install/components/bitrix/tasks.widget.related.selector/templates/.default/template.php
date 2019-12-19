<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Tasks\UI\Task\Tag;

Loc::loadMessages(__FILE__);

$helper = $arResult['HELPER'];
?>

<?php $helper->displayFatals();?>
<?php if(!$helper->checkHasFatals()):?>

	<div id="<?=$helper->getScopeId()?>" class="tasks task-form-field <?=$arParams['DISPLAY']?> <?=($arParams['READ_ONLY'] ? 'readonly' : '')?>" <?php if($arParams['MAX_WIDTH'] > 0):?>style="max-width: <?=$arParams['MAX_WIDTH']?>px"<?php endif?>>

		<?php $helper->displayWarnings();?>

		<span class="js-id-task-sel-items tasks-h-invisible">
		    <script type="text/html" data-bx-id="task-sel-item">
			    <?php ob_start();?>
			    <span class="js-id-task-sel-item js-id-task-sel-item-{{VALUE}} task-form-field-item {{ITEM_SET_INVISIBLE}}" data-item-value="{{VALUE}}">
					<a class="task-form-field-item-text" href="{{URL}}" target="_blank" class="task-options-destination-text">
						{{DISPLAY}}
					</a>
					<span class="js-id-task-sel-item-delete task-form-field-item-delete" title="<?=Loc::getMessage('TASKS_COMMON_CANCEL_SELECT')?>"></span>

				    <?php if(!$arResult['JS_DATA']['inputSpecial']):?>
				        <input type="hidden" name="<?=htmlspecialcharsbx($arParams["INPUT_PREFIX"])?>[{{VALUE}}]" value="{{ID}}" />
					<?php endif?>
			    </span>
			    <?php $template = trim(ob_get_flush());?>
		    </script>
			<?php 
			foreach($arParams['DATA'] as $item)
			{
				print($helper->fillTemplate($template, $item));
			}
			?></span>

	    <span class="task-form-field-controls">
		    <?php if($arParams['MAX'] == 1 && $arParams['MIN'] == 1): // single and required?>
			    <a href="javascript:void(0);" class="js-id-task-sel-open-form task-form-field-link">
				    <?=Loc::getMessage('TASKS_COMMON_CHANGE')?>
			    </a>
		    <?php else:?>
			    <?php $add = $arParams['MAX'] > 1;?>
			    <a href="javascript:void(0);" class="js-id-task-sel-open-form task-form-field-when-filled task-form-field-link <?php if($add):?>add<?php endif?>">
				    <?=Loc::getMessage($add ? 'TASKS_COMMON_ADD_MORE' : 'TASKS_COMMON_CHANGE')?>
			    </a>
			    <a href="javascript:void(0);" class="js-id-task-sel-open-form task-form-field-when-empty task-form-field-link add">
				    <?=Loc::getMessage('TASKS_COMMON_ADD')?>
			    </a>
		    <?php endif?>
	    </span>
		<div class="js-id-task-sel-picker-content-t js-id-task-sel-is-picker-content-t hidden-soft">
		</div>
		<div class="js-id-task-sel-picker-content-tt js-id-task-sel-is-picker-content-tt hidden-soft">
		</div>

		<?php if($arResult['JS_DATA']['inputSpecial']):?>
			<input
				class="js-id-task-sel-is-sole-input"
				type="hidden"
				name="<?=htmlspecialcharsbx($arParams["INPUT_PREFIX"])?><?=htmlspecialcharsbx($arParams['TASK'] ? $arParams['SOLE_INPUT_TASK_POSTFIX'] : $arParams['SOLE_INPUT_TASK_POSTFIX'])?>"
				value="<?=intval($arResult['TEMPLATE_DATA']['IDS'][0])?>"
			/>
		<?php else:?>
			<?php // in case of all items removed, the field should be sent anyway?>
			<input type="hidden" name="<?=htmlspecialcharsbx($arParams["INPUT_PREFIX"])?>[]" value="" />
		<?php endif?>
	</div>

	<?php $helper->initializeExtension();?>

<?php endif?>