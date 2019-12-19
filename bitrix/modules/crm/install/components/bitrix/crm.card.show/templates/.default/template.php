<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
\Bitrix\Main\Page\Asset::getInstance()->addJs('/bitrix/js/crm/common.js');
\Bitrix\Main\Page\Asset::getInstance()->addJs('/bitrix/js/crm/progress_control.js');
\Bitrix\Main\Page\Asset::getInstance()->addCss('/bitrix/js/crm/css/crm.css');
\Bitrix\Main\UI\Extension::load("ui.fonts.opensans");
?>

<input type="hidden" value="<?=htmlspecialcharsbx($arResult['ENTITY']['VK_PROFILE'])?>" data-role="crm-card-vk-profile">

<?php if ($arResult['SIMPLE']):?>
	<div class="crm-card-show-main">
		<div class="crm-card-show-user">
			<?php  if(isset($arResult['ENTITY']['PHOTO_URL'])): ?>
				<div class="crm-card-show-user-item" style="background-image: url(<?=$arResult['ENTITY']['PHOTO_URL']?>)"></div>
			<?php else: ?>
				<div class="crm-card-show-user-item"></div>
			<?php endif?>
		</div><!--crm-card-show-user-->
		<div class="crm-card-show-user-name">
			<div class="crm-card-show-user-name-item">
				<?php  if($arResult['ENTITY']['SHOW_URL']): ?>
					<a class="crm-card-show-user-name-link" href="<?=htmlspecialcharsbx($arResult['ENTITY']['SHOW_URL'])?>" target="_blank" data-use-slider="<?= ($arResult['SLIDER_ENABLED'] ? 'Y' : 'N')?>">
						<?=htmlspecialcharsbx($arResult['ENTITY']['FORMATTED_NAME'])?>
					</a>
				<?php  else: ?>
					<span class="crm-card-show-user-name-link">
						<?=htmlspecialcharsbx($arResult['ENTITY']['FORMATTED_NAME'])?>
					</span>
				<?php  endif ?>
			</div>
			<?php if($arResult['ENTITY']['POST']):?>
				<div class="crm-card-show-user-name-desc"><?=htmlspecialcharsbx($arResult['ENTITY']['POST'])?></div>
			<?php endif?>
			<?php if($arResult['ENTITY']['COMPANY_TITLE']):?>
				<div class="crm-card-show-user-name-desc"><?=htmlspecialcharsbx($arResult['ENTITY']['COMPANY_TITLE'])?></div>
			<?php endif?>
		</div><!--crm-card-show-user-name-->
		<?php  if($arResult['ENTITY']['RESPONSIBLE']): ?>
			<div class="crm-card-show-user-responsible">
				<div class="crm-card-show-user-responsible-title"><?= GetMessage('CRM_CARD_RESPONSIBLE')?>:</div>
				<div class="crm-card-show-user-responsible-user">
					<?php  if($arResult['ENTITY']['RESPONSIBLE']['PHOTO'] != ''): ?>
						<div class="crm-card-show-user-responsible-user-icon" style="background-image: url(<?=$arResult['ENTITY']['RESPONSIBLE']['PHOTO']?>)"></div>
					<?php  else: ?>
						<div class="crm-card-show-user-responsible-user-icon"></div>
					<?php  endif ?>
					<div class="crm-card-show-user-responsible-user-info">
						<a class="crm-card-show-user-responsible-user-name" href="<?=$arResult['ENTITY']['RESPONSIBLE']['PROFILE_PATH']?>" target="_blank">
							<?= htmlspecialcharsbx($arResult['ENTITY']['RESPONSIBLE']['NAME'])?>
						</a>
						<div class="crm-card-show-user-responsible-user-info-position">
							<?= htmlspecialcharsbx($arResult['ENTITY']['RESPONSIBLE']['POST'])?>
						</div>
					</div>
				</div>
			</div>
		<?php  endif ?>
		<div class="crm-card-show-user-settings">
			<div class="crm-card-show-user-settings-item"></div>
		</div><!--crm-card-show-user-settings-->
	</div><!--crm-card-show-main-->
<?php else:?>
	<div id="crm-card-detail-container" class="crm-card-show-detail crm-card-custom-scroll">
		<div class="crm-card-show-detail-header">
			<div class="crm-card-show-detail-header-user">
				<div id="crm-card-user-photo" class="crm-card-show-detail-header-user-image">
					<?php  if(isset($arResult['ENTITY']['PHOTO_URL'])): ?>
						<div class="crm-card-show-detail-header-user-image-item" style="background-image: url(<?=$arResult['ENTITY']['PHOTO_URL']?>)"></div>
					<?php  else: ?>
						<div class="crm-card-show-detail-header-user-image-item"></div>
					<?php  endif ?>
				</div>
				<div class="crm-card-show-detail-header-user-info">
					<a href="<?=htmlspecialcharsbx($arResult['ENTITY']['SHOW_URL'])?>" target="_blank" data-use-slider="<?= ($arResult['SLIDER_ENABLED'] ? 'Y' : 'N')?>">
						<div class="crm-card-show-detail-header-user-name">
							<?=htmlspecialcharsbx($arResult['ENTITY']['FORMATTED_NAME'])?>
						</div>
					</a>
					<?php if($arResult['ENTITY']['POST']):?>
						<div class="crm-card-show-detail-header-user-item"><?=htmlspecialcharsbx($arResult['ENTITY']['POST'])?></div>
					<?php endif?>
					<?php if($arResult['ENTITY']['COMPANY_TITLE']):?>
						<div class="crm-card-show-detail-header-user-item"><?=htmlspecialcharsbx($arResult['ENTITY']['COMPANY_TITLE'])?></div>
					<?php endif?>
				</div>
			</div><!--crm-card-show-detail-header-user-->
			<div class="crm-card-show-detail-header-user-status">
				<div class="crm-card-show-detail-header-user-status-item"><?php /*=GetMessage('CRM_CARD_CONSTANT_CLIENT')*/?></div>
			</div><!--crm-card-show-detail-header-user-status-->
		</div><!--crm-card-show-detail-header-->
		<div class="crm-card-show-detail-info">
			<div class="crm-card-show-detail-info-inner">
				<div id="crm-card-extended-info" class="crm-card-show-detail-info-content">
					<?php  if(is_array($arResult['ENTITY']['ACTIVITIES']) && count($arResult['ENTITY']['ACTIVITIES']) > 0): ?>
						<div class="crm-card-show-detail-info-wrap">
							<div class="crm-card-show-detail-info-title crm-card-show-title-main">
								<div class="crm-card-show-detail-info-title-item">
									<a href="<?=htmlspecialcharsbx($arResult['ENTITY']['ACTIVITY_LIST_URL'])?>" target="_blank">
										<?=GetMessage('CRM_CARD_ACTIVITIES')?>
									</a>
								</div>
							</div>
							<?php  foreach ($arResult['ENTITY']['ACTIVITIES'] as $activity): ?>
								<div class="crm-card-show-detail-info-block">
									<div class="crm-card-show-detail-info-name">
										<div class="crm-card-show-detail-info-name-item">
											<a href="<?=htmlspecialcharsbx($activity['SHOW_URL'])?>" target="_blank" data-use-slider="Y">
												<?=htmlspecialcharsbx($activity['SUBJECT'])?>
											</a>
										</div>
									</div>
									<div class="crm-card-show-detail-info-desc">
										<div class="crm-card-show-detail-info-desc-item"><?=htmlspecialcharsbx($activity['DEADLINE'])?></div>
									</div>
								</div><!--crm-card-show-detail-info-block-->
							<?php  endforeach ?>
						</div>
					<?php  endif ?>

					<?php  if(is_array($arResult['ENTITY']['DEALS']) && count($arResult['ENTITY']['DEALS']) > 0): ?>
						<div class="crm-card-show-detail-info-wrap">
							<div class="crm-card-show-detail-info-title crm-card-show-title-main">
								<div class="crm-card-show-detail-info-title-item">
									<a href="<?=htmlspecialcharsbx($arResult['ENTITY']['DEAL_LIST_URL'])?>" target="_blank"><?=GetMessage('CRM_CARD_DEALS')?></a>
								</div>
							</div>
							<?php  foreach ($arResult['ENTITY']['DEALS'] as $deal): ?>
								<div class="crm-card-show-detail-info-main-inner">
									<div class="crm-card-show-detail-info-main-content">
										<div class="crm-card-show-detail-info-block">
											<div class="crm-card-show-detail-info-name">
												<div class="crm-card-show-detail-info-name-item">
													<a href="<?=htmlspecialcharsbx($deal['SHOW_URL'])?>" target="_blank" data-use-slider="<?= ($arResult['SLIDER_ENABLED'] ? 'Y' : 'N')?>">
														<?=htmlspecialcharsbx($deal['TITLE'])?>
													</a>
												</div>
											</div>
											<div class="crm-card-show-detail-info-desc">
												<div class="crm-card-show-detail-info-desc-item"><?=$deal['FORMATTED_OPPORTUNITY']?></div>
											</div>
										</div>
									</div><!--crm-card-show-detail-info-main-content-->
									<div class="crm-card-show-detail-info-main-status">
										<?= CCrmViewHelper::RenderDealStageControl(
											array(
												'ENTITY_ID' => $deal['ID'],
												'CURRENT_ID' => $deal['STAGE_ID'],
												'CATEGORY_ID' => $deal['CATEGORY_ID'],
												'READ_ONLY' => true
											)) ?>
									</div><!--crm-card-show-detail-info-main-status-->
								</div><!--crm-card-show-detail-info-main-inner-->
							<?php  endforeach ?>
						</div>
					<?php  endif ?>

					<?php  if(is_array($arResult['ENTITY']['INVOICES']) && count($arResult['ENTITY']['INVOICES']) > 0): ?>
						<div class="crm-card-show-detail-info-wrap">
							<div class="crm-card-show-detail-info-title crm-card-show-title-main">
								<div class="crm-card-show-detail-info-title-item">
									<a href="<?=htmlspecialcharsbx($arResult['ENTITY']['INVOICE_LIST_URL'])?>" target="_blank">
										<?=GetMessage('CRM_CARD_INVOICES')?>
									</a>
								</div>
							</div>
							<?php  foreach ($arResult['ENTITY']['INVOICES'] as $invoice): ?>
								<div class="crm-card-show-detail-info-main-inner">
									<div class="crm-card-show-detail-info-main-content">
										<div class="crm-card-show-detail-info-block">
											<div class="crm-card-show-detail-info-name">
												<div class="crm-card-show-detail-info-name-item">
													<a href="<?=htmlspecialcharsbx($invoice['SHOW_URL'])?>" target="_blank" data-use-slider="<?= ($arResult['SLIDER_ENABLED'] ? 'Y' : 'N')?>">
														<?=htmlspecialcharsbx($invoice['ORDER_TOPIC']).' '.GetMessage('CRM_CARD_INVOICE_DATE_FROM').' '.htmlspecialcharsbx($invoice['DATE_BILL'])?>
													</a>
												</div>
											</div>
											<div class="crm-card-show-detail-info-desc">
												<div class="crm-card-show-detail-info-desc-item"><?=htmlspecialcharsbx($invoice['PRICE_FORMATTED'])?></div>
											</div>
										</div>
									</div><!--crm-card-show-detail-info-main-content-->
									<div class="crm-card-show-detail-info-main-status">
										<?= CCrmViewHelper::RenderInvoiceStatusControl(
											array(
												'ENTITY_ID' => $invoice['ID'],
												'CURRENT_ID' => $invoice['STATUS_ID'],
												'READ_ONLY' => true
											)) ?>
									</div><!--crm-card-show-detail-info-main-status-->
								</div><!--crm-card-show-detail-info-main-inner-->
							<?php  endforeach ?>
						</div>
					<?php  endif ?>

					<?php  if($arResult['ENTITY']['RESPONSIBLE']): ?>
						<div class="crm-card-show-detail-info-wrap">
							<div class="crm-card-show-user-responsible crm-card-show-user-responsible-detail-info">
								<div class="crm-card-show-user-responsible-title"><?= GetMessage('CRM_CARD_RESPONSIBLE')?>:</div>
								<div class="crm-card-show-user-responsible-user">
									<?php  if($arResult['ENTITY']['RESPONSIBLE']['PHOTO'] != ''): ?>
										<div class="crm-card-show-user-responsible-user-icon" style="background-image: url(<?=$arResult['ENTITY']['RESPONSIBLE']['PHOTO']?>)"></div>
									<?php  else: ?>
										<div class="crm-card-show-user-responsible-user-icon"></div>
									<?php  endif ?>
									<div class="crm-card-show-user-responsible-user-info">
										<a class="crm-card-show-user-responsible-user-name" href="<?=$arResult['ENTITY']['RESPONSIBLE']['PROFILE_PATH']?>" target="_blank">
											<?= htmlspecialcharsbx($arResult['ENTITY']['RESPONSIBLE']['NAME'])?>
										</a>
										<div class="crm-card-show-user-responsible-user-info-position">
											<?= htmlspecialcharsbx($arResult['ENTITY']['RESPONSIBLE']['POST'])?>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php  endif ?>

				</div><!--crm-card-show-detail-info-content-->
			</div><!--crm-card-show-detail-info-inner-->
		</div><!--crm-card-show-detail-info-->
	</div><!--crm-card-show-detail-->
	<script>
		BX.ready(function()
		{
			var extendedNode = BX('crm-card-extended-info');
			if(extendedNode)
			{
				if(extendedNode.clientHeight == 304)
				{
					BX.addClass(BX('crm-card-detail-container'), 'crm-card-show-detail-compact');
				}
				var photoNode = BX('crm-card-user-photo');
				if(photoNode)
				{
					photoNode.style.width = photoNode.clientHeight.toString() + 'px';
				}
			}
		})
	</script>
<?php endif?>


