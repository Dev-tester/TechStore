<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

//_print_r($arParams);
//_print_r($arResult);
?>

<div id="bx-component-scope-<?=$arResult['TEMPLATE_DATA']['ID']?>">

	<div class="popup-tags-window" id="task-tags-content" style="display: block;">
		<div class="popup-tags-create-new">
			<div class="popup-tags-textbox"><input data-bx-id="tag-item-set-pre-form-item-name" autocomplete="off" type="text"></div>
			<div data-bx-id="tag-item-set-pre-item-add" title="<?=Loc::getMessage('TASKS_COMPONENT_TASK_TAG_SELECTOR_TEMPLATE_ADD_NEW')?>" class="popup-tags-add-button"></div>
		</div>
		<div class="popup-window-hr"><i></i></div>
		<div class="popup-tags-content-wrapper">
			<div class="popup-tags-content" style="height: auto; overflow-y: visible;">
				<table cellspacing="0">
					<tbody>
					<tr>
						<td data-bx-id="tag-item-set-pre-left-list" class="popup-tags-left-top-cell">
							<script data-bx-id="tag-item-set-pre-item" type="text/html">
								<div class="popup-tags-item popup-tags-item-default-mode">
									<input data-bx-id="tag-item-set-pre-item-btn-toggle" data-item-value="{{VALUE}}" class="popup-tags-item-checkbox" type="checkbox" id="popup-tags-item-{{ID}}" {{CHECKED_ATTR}}>
									<label for="popup-tags-item-{{ID}}">{{DISPLAY}}</label>
								</div>
							</script>
						</td>
						<td data-bx-id="tag-item-set-pre-right-list" class="popup-tags-right-top-cell">
						</td>
					</tr>

					<tr>
						<td colspan="2" class="popup-tags-middle-cell" style="height: 19px; visibility: visible;">
							<div class="popup-window-hr"><i></i></div>
						</td>
					</tr>

					</tbody>
				</table>

				<?php if(is_array($arResult['~USER_TAGS']) && !empty($arResult['~USER_TAGS'])):?>

					<?php $arResult['~USER_TAGS'] = array_unique($arResult['~USER_TAGS']);?>

					<table data-bx-id="tag-item-set-pre-static" cellspacing="0">
						<tbody>

							<tr>

								<?php $i = 0;?>
								<?php $first = true;?>
								<?php foreach($arResult['~USER_TAGS'] as $tag):?>

									<?php $newRow = $i && !($i % 2);?>

									<?php if($newRow):?>
										<?php $first = false;?>
										</tr><tr>
									<?php endif?>

									<?php $tId = md5($tag);?>
									<td class="popup-tags-left-bottom-cell">
										<div class="popup-tags-item popup-tags-item-default-mode<?php if($first):?> popup-tags-item-first<?php endif?>">
											<input data-bx-id="tag-item-set-pre-item-btn-toggle" data-item-value="<?=htmlspecialcharsbx($tag)?>" class="popup-tags-item-checkbox" type="checkbox" id="popup-tags-item-<?=$tId?>">
											<label for="popup-tags-item-<?=$tId?>"><?=htmlspecialcharsbx($tag)?></label>
										</div>
									</td>

									<?php $i++;?>
								<?php endforeach?>

							</tr>

						</tbody>
					</table>

				<?php endif?>
			</div>
		</div>
	</div>
</div>

<script>
	new BX.Tasks.Component.TaskTagsSelector(<?=CUtil::PhpToJSObject(array(
		'id' => $arResult['TEMPLATE_DATA']['ID']
	), false, false, true)?>);
</script>