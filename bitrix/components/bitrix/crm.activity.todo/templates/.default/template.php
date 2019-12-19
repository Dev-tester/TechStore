<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if ($arParams['IS_AJAX'] == 'Y')
{
	echo '<link rel="stylesheet" type="text/css" href="', $this->getFolder(), '/style.css?5" />';
	echo '<script type="text/javascript" src="', $this->getFolder(), '/script.js?v13"></script>';
}
?>

<script type="text/javascript">
	BX.message({
		CRM_ACTIVITY_TODO_VIEW_TITLE: '<?= CUtil::JSEscape(Loc::getMessage('CRM_ACTIVITY_TODO_VIEW_TITLE'));?>',
		CRM_ACTIVITY_TODO_CLOSE: '<?= CUtil::JSEscape(Loc::getMessage('CRM_ACTIVITY_TODO_CLOSE'));?>'
	});
</script>

<div id="crm-activity-todo-items" class="crm-activity-todo-items">
<?php foreach ($arResult['ITEMS'] as $item):
	if ($item['DETAIL_EXIST'])
	{
		$uriView = new \Bitrix\Main\Web\Uri('/bitrix/components/bitrix/crm.activity.planner/slider.php');
		$uriView->addParams(array(
			'site_id' => SITE_ID,
			'sessid' => bitrix_sessid_get(),
			'ajax_action' => 'ACTIVITY_VIEW',
			'activity_id' => $item['ID']
		));
	}
	?>
<div class="crm-activity-todo-item<?= $item['COMPLETED']=='Y' ? ' crm-activity-todo-item-completed' : ''?>"<?php 
	?> data-id="<?= $item['ID']?>"<?php 
	?> data-ownerid="<?= $item['OWNER_ID']?>"<?php 
	?> data-ownertypeid="<?= $item['OWNER_TYPE_ID']?>"<?php 
	?> data-deadlined="<?= $item['DEADLINED']?>"<?php 
	?> data-associatedid="<?= isset($item['ASSOCIATED_ENTITY_ID']) ? $item['ASSOCIATED_ENTITY_ID'] : 0?>"<?php 
	?> data-icon="<?= $item['ICON']?>">
		<div class="crm-activity-todo-item-left">
			<input type="checkbox" id="check<?= $item['ID']?>" value="1" class="crm-activity-todo-check"<?= $item['COMPLETED']=='Y' ? ' checked="checked" disabled="disabled"' : ''?> />
		</div>
		<label class="crm-activity-todo-item-middle" for="check<?= $item['ID']?>">
			<?php if (isset($item['DEADLINE']) && $item['DEADLINE'] != ''):?>
			<div class="crm-activity-todo-date<?= $item['HIGH']=='Y' ? ' crm-activity-todo-date-alert' : ''?>"<?= $item['DEADLINED'] ? ' style="color: red"' : ''?> <?php 
				?>title="<?= Loc::getMessage('CRM_ACTIVITY_TODO_DEADLINE')?><?= $item['HIGH']=='Y' ? ' '.Loc::getMessage('CRM_ACTIVITY_TODO_HOT') : ''?>">
				<?= $item['DEADLINE']?>
			</div>
			<?php elseif (isset($item['START_TIME']) && $item['START_TIME'] != ''):?>
			<div class="crm-activity-todo-date<?= $item['HIGH']=='Y' ? ' crm-activity-todo-date-alert' : ''?>">
				<?= $item['START_TIME']?>
			</div>
			<?php endif;?>
			<?php if ($item['DETAIL_EXIST']):?>
				<a href="<?= $uriView->getUri();?>" data-id="<?= $item['ID']?>" class="crm-activity-todo-link">
					<span class="crm-activity-todo-link-txt"><?= $item['SUBJECT']?></span>
					<span class="crm-activity-todo-link-num"><?= $item['DEADLINED'] ? ' <span>1</span>' : ''?><?php  ?></span>
				</a>
			<?php else:?>
				<span data-id="<?= $item['ID']?>" class="crm-activity-todo-link"><?= $item['SUBJECT']?></span>
			<?php endif;?>
			<?php if (!empty($item['CONTACTS'])):?>
			<div class="crm-activity-todo-info">
				<?= Loc::getMessage('CRM_ACTIVITY_TODO_CONTACT')?>:
				<?php foreach ($item['CONTACTS'] as $contact):?>
					<a href="<?= $contact['URL']?>"><?= $contact['TITLE']?></a>
				<?php endforeach;?>
			</div>
			<?php endif;?>
		</label>
		<?php if ($item['ICON'] == 'no'):?>
		<div class="crm-activity-todo-item-right-nopadding">
			<div class="crm-activity-todo-event crm-activity-todo-event-no">
			</div>
		</div>
		<?php else:?>
		<div class="crm-activity-todo-item-right-nopadding<?php if (!empty($item['CONTACTS'])):?> crm-activity-todo-item-right<?php endif;?>">
			<div class="crm-activity-todo-event crm-activity-todo-event-<?= $item['ICON']?>" title="<?= $item['PROVIDER_TITLE']!='' ? $item['PROVIDER_TITLE'] : $item['TYPE_NAME']?>">
			<?php if (!empty($item['PROVIDER_ANCHOR'])):?>
				<?= $item['PROVIDER_TITLE']!='' ? $item['PROVIDER_TITLE'] : $item['TYPE_NAME']?>
				<?php if (isset($item['PROVIDER_ANCHOR']['HTML']) && !empty($item['PROVIDER_ANCHOR']['HTML'])):?>
					<br/>
					<?= $item['PROVIDER_ANCHOR']['HTML']?>
				<?php elseif (false && isset($item['PROVIDER_ANCHOR']['TEXT']) && !empty($item['PROVIDER_ANCHOR']['URL'])):?>
					<a href="<?= $item['PROVIDER_ANCHOR']['URL']?>"><?= $item['PROVIDER_ANCHOR']['TEXT']?></a>
				<?php endif;?>
			<?php else:?>
				<?= $item['PROVIDER_TITLE']!='' ? $item['PROVIDER_TITLE'] : $item['TYPE_NAME']?>
			<?php endif;?>
			</div>
		</div>
		<?php endif;?>
</div>
<?php endforeach;?>
</div>

<script type="text/javascript">
	BX.CrmActivityTodo.create({
		container: 'crm-activity-todo-items'
	});
</script>
