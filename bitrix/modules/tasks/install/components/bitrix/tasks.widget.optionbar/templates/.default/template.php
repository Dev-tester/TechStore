<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$helper = $arResult['HELPER'];
$arParams =& $helper->getComponent()->arParams; // make $arParams the same variable as $this->__component->arParams, as it really should be
?>

<?php //$helper->displayFatals();?>
<?php if(!$helper->checkHasFatals()):?>

	<div id="<?=$helper->getScopeId()?>" class="tasks">

		<?php foreach($arParams['OPTIONS'] as $option):?>
			<?php $optionJs = ToLower(str_replace('_', '-', $option['CODE']));?>
			<div class="task-options-field">
				<div class="task-options-field-inner">
					<label
						class="
								task-field-label
								js-id-hint-help
								js-id-wg-optbar-flag-label-<?=htmlspecialcharsbx($optionJs)?>
							"
						data-hint-enabled="<?=htmlspecialcharsbx($option['HINT_ENABLED'])?>"
						data-hint-text="<?=$option['HINT_TEXT']?>"
					>
						<?php if($option['HELP_TEXT'] != ''):?>
							<span class="js-id-hint-help task-options-help tasks-icon-help tasks-help-cursor"><?=$option['HELP_TEXT']?></span>
						<?php endif?>
						<input
							data-target="<?=htmlspecialcharsbx($optionJs)?>"
							data-flag-name="<?=htmlspecialcharsbx($option['CODE'])?>"
							data-yes-value="<?=htmlspecialcharsbx($option['YES_VALUE'])?>"
							data-no-value="<?=htmlspecialcharsbx($option['NO_VALUE'])?>"
							<?=($option['YES_VALUE'] == $option['VALUE'] ? 'checked' : '')?>
							<?=($option['DISABLED'] ? 'disabled' : '')?>
							class="
									js-id-wg-optbar-flag
									js-id-wg-optbar-flag-<?=htmlspecialcharsbx($optionJs)?>
									<?=htmlspecialcharsbx($option['FLAG_CLASS'])?>
									task-field-checkbox
								"
							type="checkbox"><?=htmlspecialcharsbx($option['TEXT'])?>
					</label>
					<input
						class="js-id-wg-optbar-<?=htmlspecialcharsbx($optionJs)?>"
						type="hidden"
						name="<?=htmlspecialcharsbx($arParams['INPUT_PREFIX'])?>[<?=htmlspecialcharsbx($option['CODE'])?>]"
						value="<?=htmlspecialcharsbx($option['VALUE'])?>"

						<?=($option['DISABLED'] ? 'disabled' : '')?>
					/>

					<?php if($option['LINK']):?>
                        <a href="<?=htmlspecialcharsbx($option['LINK']['URL'])?>" target="_blank"><?=htmlspecialcharsbx($option['LINK']['TEXT'])?></a>
					<?php endif?>
					<?php if($option['LINKS']):?>
                        <?php foreach($option['LINKS'] as $link):?>
                            <a href="<?=htmlspecialcharsbx($link['URL'])?>" target="_blank"><?=htmlspecialcharsbx($link['TEXT'])?></a>
                        <?php endforeach?>
					<?php endif?>
					<?php if($option['FIELDS']):?>
                    <div id="js-id-wg-optbar-fields" class="js-id-wg-optbar-fields">
                        <?php foreach($option['FIELDS'] as $field):
                            if(!isset($field['ID']))
                            {
	                            $field['ID'] = 'field-'.randString(5);
                            }
                        ?>
                            <div id="<?=$field['ID']?>" class="js-id-wg-optbar-field js-id-wg-optbar-field-<?=$field['TYPE']?>">
                                <?php include(__DIR__.'/'.strtolower($field['TYPE']).'-field.php')?>
                            </div>
                        <?php endforeach?>
                    </div>
					<?php endif?>

				</div>
			</div>
		<?php endforeach?>

	</div>

	<?php $helper->initializeExtension();?>

<?php endif?>