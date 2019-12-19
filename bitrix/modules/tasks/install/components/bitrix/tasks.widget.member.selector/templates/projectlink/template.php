<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$helper = $arResult['HELPER'];
?>

<?php //$helper->displayFatals();?>
<?php if(!$helper->checkHasFatals()):?>

	<?php //$helper->displayWarnings();?>

	<?php $group = $arResult['DATA'];?>
	<?php $empty = empty($group);?>
	<?php $readOnly = $arParams['READ_ONLY'];?>

	<?php if(!$empty || !$readOnly):?>

		<span id="<?=$helper->getScopeId()?>">

			<span class="js-id-ms-plink-item task-group-field <?=($empty ? 'invisible' : '')?>"><?php 
				?><span class="task-group-field-inner"><?php 
					?><a href="<?=$group['URL']?>" class="js-id-ms-plink-item-link task-group-field-label" target="_top"><?=htmlspecialcharsbx($group['DISPLAY'])?></a><?php 
					?><?php if(!$readOnly):?><span class="js-id-ms-plink-deselect task-group-field-title-del"></span><?php endif?><?php 
				?></span><?php 
			?></span>
			<?php /*<span class="task-detail-group-loader"><?=Loc::getMessage("TASKS_COMMON_LOADING")?></span>*/?>

			<?php if(!$readOnly):?>
				<span class="js-id-ms-plink-open-form task-dashed-link task-group-select <?=($empty ? '' : 'invisible')?>"><span class="task-dashed-link-inner"><?=Loc::getMessage("TASKS_COMMON_ADD")?></span></span>
			<?php endif?>

		</span>

		<?php if(!$readOnly):?>
			<?php $helper->initializeExtension();?>
		<?php endif?>

	<?php endif?>

<?php endif?>