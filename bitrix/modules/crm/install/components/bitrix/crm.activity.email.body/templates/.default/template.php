<?php  if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?php  $activity = $arParams['ACTIVITY']; ?>

<div class="crm-task-list-mail-item-inner-header <?php  if ($arParams['LOADED_FROM_LOG'] == 'Y'): ?> crm-task-list-mail-item-inner-header-clickable crm-task-list-mail-item-open<?php  endif ?>">
	<?php  if ($arParams['LOADED_FROM_LOG'] == 'Y'): ?>
		<span class="crm-task-list-mail-item-date crm-activity-email-details-hide" style="margin-top: -10px; margin-right: -7px; "></span>
	<?php  endif ?>
	<span class="crm-task-list-mail-item-inner-user"
		<?php  if (!empty($activity['ITEM_IMAGE'])): ?>style="background: url('<?=$activity['ITEM_IMAGE'] ?>'); background-size: 40px 40px; "<?php  endif ?>>
	</span>
	<span class="crm-task-list-mail-item-inner-user-container">
		<span class="crm-task-list-mail-item-inner-user-info">
			<span class="crm-task-list-mail-item-inner-user-title"><?=$activity['ITEM_FROM_TITLE'] ?></span>
			<?php  if (!empty($activity['ITEM_FROM_EMAIL'])): ?>
				<span class="crm-task-list-mail-item-inner-user-mail"><?=$activity['ITEM_FROM_EMAIL'] ?></span>
			<?php  endif ?>
			<div class="crm-task-list-mail-item-inner-send">
				<span class="crm-task-list-mail-item-inner-send-item"><?=getMessage('CRM_ACT_EMAIL_RCPT') ?>:</span>
				<?php  foreach ($activity['ITEM_TO'] as $item): ?>
					<span class="crm-task-list-mail-item-inner-send-user"
						<?php  if (!empty($item['IMAGE'])): ?>style="background: url('<?=$item['IMAGE'] ?>'); background-size: 23px 23px; "<?php  endif ?>>
						</span>
					<span class="crm-task-list-mail-item-inner-send-mail"><?=$item['TITLE'] ?></span>
				<?php  endforeach ?>
			</div>
		</span>
	</span>
</div>
<div class="crm-task-list-mail-item-control-block"></div>
<div class="crm-task-list-mail-item-inner-body"><?=$arParams['~ACTIVITY']['DESCRIPTION_HTML'] ?></div>
