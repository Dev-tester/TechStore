<?php 
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var array $arParams */
/** @var CBitrixComponent $component */
/** @global CMain $APPLICATION */

/** @var \Bitrix\Crm\Activity\Provider\Base $provider */
$provider = $arResult['PROVIDER'];
/** @var array $activity */
$activity = $arResult['ACTIVITY'];
$options = array(
	'title' => $provider::getTypeName($activity['PROVIDER_TYPE_ID'], $activity['DIRECTION']),
	'important' => $activity['PRIORITY'] == CCrmActivityPriority::High,
	'isEditable' => !empty($arResult['IS_EDITABLE'])
);
$optionsJson = \Bitrix\Main\Web\Json::encode($options);
?>
<div class="crm-task-list-wrapper" data-role="options" data-options="<?=htmlspecialcharsbx($optionsJson)?>">
	<div class="crm-task-list-container">
		<div class="crm-task-list-header crm-task-list-header-image <?=$arResult['TYPE_ICON']?>">
			<div class="crm-task-list-header-item"><?=htmlspecialcharsbx($activity['SUBJECT'] ? $activity['SUBJECT'] : $provider::getTypeName($activity['PROVIDER_TYPE_ID'], $activity['DIRECTION']))?></div>
			<div class="crm-task-list-header-description">
				<span class="crm-task-list-header-description-item"><?=GetMessage('CRM_ACTIVITY_PLANNER_VIEW_DATE_AND_TIME')?>:</span>
				<span class="crm-task-list-header-description-date"><?=CCrmComponentHelper::TrimDateTimeString($activity['START_TIME'])?><?php if ($activity['END_TIME'] && $activity['START_TIME'] != $activity['END_TIME']):?> - <?=CCrmComponentHelper::TrimDateTimeString($activity['END_TIME'])?><?php endif?></span>
			</div>
		</div><!--crm-task-list-header-->
		<div class="crm-task-list-inner">
			<?=$provider::renderView($activity)?>
		</div><!--crm-task-list-inner-->
		<?php if ($arResult['DOC_BINDINGS']):?>
		<div class="crm-task-list-docs">
			<div class="crm-task-list-docs-item"><?=GetMessage('CRM_ACTIVITY_PLANNER_VIEW_DOCUMENTS')?>:</div>
			<?php foreach ($arResult['DOC_BINDINGS'] as $doc):?>
			<div class="crm-task-list-docs-link">
				<a <?php if($doc['URL']):?>href="<?=htmlspecialcharsbx($doc['URL'])?>"<?php endif;?> class="crm-task-list-docs-link-item" target="_blank"><?=htmlspecialcharsbx($doc['DOC_NAME'])?> - <?=htmlspecialcharsbx($doc['CAPTION'])?></a>
			</div>
			<?php endforeach;?>
		</div><!--crm-task-list-docs-->
		<?php endif?>
		<?php if (!empty($arResult['COMMUNICATIONS'])):?>
		<div class="crm-task-list-person">
			<div class="crm-task-list-person-item"><?=GetMessage('CRM_ACTIVITY_PLANNER_RECEIVER')?>:</div>
			<div class="crm-task-list-person-container">
				<div class="crm-task-list-person-slides" data-role="com-slider-slides">
					<?php foreach($arResult['COMMUNICATIONS'] as $index => $communication):?>
					<div class="crm-task-list-person-inner">
						<span class="crm-task-list-person-user-image" <?php if ($communication['IMAGE_URL']):?> style="background: url('<?=htmlspecialcharsbx($communication['IMAGE_URL'])?>')"<?php endif;?>></span>
						<span class="crm-task-list-person-user-info">
							<a <?php if ($communication['VIEW_URL']):?>href="<?=htmlspecialcharsbx($communication['VIEW_URL'])?>"<?php endif;?> class="crm-task-list-person-info-name"><?=htmlspecialcharsbx($communication['TITLE'])?></a>
							<div class="crm-task-list-person-info-description"><?=htmlspecialcharsbx($communication['DESCRIPTION'])?></div>
							<div class="crm-task-list-person-info-contacts">
								<?php if (!empty($communication['FM']['PHONE'])):
									reset($communication['FM']['PHONE']);
									$fm = current($communication['FM']['PHONE']);
									$entityType = 'CRM_'.strtoupper(CCrmOwnerType::ResolveName($communication['ENTITY_TYPE_ID']));
									$entityID = $communication['ENTITY_ID'];
								?>
								<div class="crm-task-list-person-info-phone-block">
									<span class="crm-task-list-person-info-phone"><?=GetMessage('CRM_ACTIVITY_PLANNER_TEL')?>:</span>
									<?php  $link = \CCrmCallToUrl::PrepareLinkAttributes($fm['VALUE'], array(
										'ENTITY_TYPE' => $entityType,
										'ENTITY_ID' => $entityID,
										'SRC_ACTIVITY_ID' => $activity['ID']
									));?>
									<span class="crm-task-list-person-info-phone-item">
										<a href="<?=htmlspecialcharsbx($link['HREF'])?>" onclick="<?=htmlspecialcharsbx($link['ONCLICK'])?>">
											<?=htmlspecialcharsbx($fm['VALUE'])?>
										</a>
									</span>
									<?php  if(CCrmSipHelper::checkPhoneNumber($fm['VALUE'])):?>
									<span class="crm-task-list-person-info-phone-icon">
										<!--<span class="crm-task-list-person-info-phone-icon-item"></span>-->
										<span class="crm-task-list-person-info-phone-icon-border"></span>
										<span class="crm-task-list-person-info-phone-icon-element"
											onclick="if(typeof(window['BXIM']) === 'undefined') { window.alert('<?=GetMessageJS('CRM_SIP_NO_SUPPORTED')?>'); return; } BX.CrmSipManager.startCall({ number:'<?=CUtil::JSEscape($fm['VALUE'])?>', enableInfoLoading: true }, { ENTITY_TYPE: '<?=CUtil::JSEscape($entityType)?>', ENTITY_ID: '<?=CUtil::JSEscape($entityID)?>', SRC_ACTIVITY_ID: '<?=CUtil::JSEscape($activity['ID'])?>'}, true, this);"></span>
									</span>
									<?php endif?>
								</div>
								<?php endif?>
								<?php if (!empty($communication['FM']['EMAIL'])):
									reset($communication['FM']['EMAIL']);
									$fm = current($communication['FM']['EMAIL']);
								?>
								<div class="crm-task-list-person-info-mail-block">
									<span class="crm-task-list-person-info-mail"><?=GetMessage('CRM_ACTIVITY_PLANNER_EMAIL')?>:</span>
									<span class="crm-task-list-person-info-phone-item">
										<a title="<?=htmlspecialcharsbx($fm['VALUE'])?>" href="mailto:<?=htmlspecialcharsbx($fm['VALUE'])?>">
											<?=htmlspecialcharsbx($fm['VALUE'])?>
										</a>
									</span>
								</div>
								<?php endif?>
							</div>
						</span>
					</div><!--crm-task-list-person-inner-->
					<?php endforeach?>
				</div><!--crm-task-list-person-slides-->
				<div class="crm-task-list-person-slide">
					<span class="crm-task-list-person-slide-left" data-role="com-slider-left"></span>
					<span class="crm-task-list-person-slide-item" data-role="com-slider-nav" data-current="1" data-cnt="<?=count($arResult['COMMUNICATIONS'])?>">1 / <?=count($arResult['COMMUNICATIONS'])?></span>
					<span class="crm-task-list-person-slide-right" data-role="com-slider-right"></span>
				</div>
			</div><!--crm-task-list-person-container-->
		</div><!--crm-task-list-person-->
		<?php endif?>
		<div class="crm-task-list-extra">
				<span class="crm-task-list-extra-button">
					<span class="crm-task-list-extra-button-item" data-role="additional-switcher"><?=GetMessage('CRM_ACTIVITY_PLANNER_ADDITIONAL')?></span>
					<span class="crm-task-list-extra-button-element"></span>
				</span>
				<span class="crm-task-list-extra-item">
					<label class="crm-task-list-extra-item-element">
						<input type="checkbox" class="crm-task-list-extra-checkbox" data-role="field-completed"<?php if ($activity['COMPLETED'] == 'Y'):?> checked<?php endif?>>
						<span class="crm-task-list-extra-text"><?=GetMessage('CRM_ACTIVITY_PLANNER_CHECK_COMPLETED_2')?></span>
					</label>
				</span>
		</div><!--crm-task-list-extra-->

		<div class="crm-task-list-extra-inner" data-role="additional-fields">
			<div class="crm-task-list-extra-inner-container">
				<?php if (!empty($arResult['FILES_LIST'])):?>
				<div class="crm-task-list-receiver">
					<div class="crm-task-list-receiver-item"><?=GetMessage('CRM_ACTIVITY_PLANNER_FILES')?>:</div><!--crm-task-list-receiver-name-->
					<div class="crm-task-list-options-item-open-inner">
						<div class="bx-crm-dialog-view-activity-files">
							<?php foreach ($arResult['FILES_LIST'] as $index => $file):?>
							<div class="bx-crm-dialog-view-activity-file">
								<span class="bx-crm-dialog-view-activity-file-num"><?=($index + 1)?></span>
								<a class="bx-crm-dialog-view-activity-file-text" target="_blank" href="<?=htmlspecialcharsbx($file['viewURL'])?>"><?=htmlspecialcharsbx($file['fileName'])?></a>
							</div>
							<?php endforeach;?>
						</div>
					</div>
				</div><!--crm-task-list-receiver-->
				<?php endif?>
				<?php if (!empty($arResult['RESPONSIBLE_NAME'])):?>
				<div class="crm-task-list-receiver">
					<div class="crm-task-list-receiver-item"><?=GetMessage('CRM_ACTIVITY_PLANNER_RESPONSIBLE_USER')?>:</div><!--crm-task-list-receiver-name-->
					<div class="crm-task-list-options-item-open-inner">
						<span class="crm-task-list-options-destination-wrap">
							<span class="crm-task-list-inline-selector-item">
								<span class="crm-task-list-options-destination">
									<a href="<?=htmlspecialcharsbx($arResult['RESPONSIBLE_URL'])?>" target="_blank" class="crm-task-list-options-destination-text"><?=htmlspecialcharsbx($arResult['RESPONSIBLE_NAME'])?></a>
								</span>
							</span>
						</span>
					</div>
				</div><!--crm-task-list-receiver-->
				<?php endif?>
			</div>
		</div>
	</div><!--crm-task-list-container-->
</div><!--crm-task-list-wrapper-->