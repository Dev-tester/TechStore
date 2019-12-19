<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Tasks\CheckList\Template\TemplateCheckListFacade;
use Bitrix\Tasks\Integration;
use Bitrix\Tasks\Util\Result;
use Bitrix\Tasks\Util\User;
use Bitrix\Tasks\Util\UserField;

Loc::loadMessages(__FILE__);

$helper = $arResult['HELPER'];
$arParams =& $helper->getComponent()->arParams;

/** @var \Bitrix\Tasks\Item\Task\Template $template */
$template = $arResult['ITEM'];

$toList = str_replace("#user_id#", $arParams["USER_ID"], $arParams["PATH_TO_USER_TASKS_TEMPLATES"]);
?>

<?php if($arParams["ENABLE_MENU_TOOLBAR"]):?>

	<?php
    if(!$_REQUEST['IFRAME']) {
        $APPLICATION->IncludeComponent(
            'bitrix:tasks.interface.topmenu',
            '',
            array(
                'USER_ID' => $arParams['USER_ID'],

                'GROUP_ID' => $arParams['GROUP_ID'],
                'SECTION_URL_PREFIX' => '',

                'PATH_TO_GROUP_TASKS' => $arParams['PATH_TO_GROUP_TASKS'],
                'PATH_TO_GROUP_TASKS_TASK' => $arParams['PATH_TO_GROUP_TASKS_TASK'],
                'PATH_TO_GROUP_TASKS_VIEW' => $arParams['PATH_TO_GROUP_TASKS_VIEW'],
                'PATH_TO_GROUP_TASKS_REPORT' => $arParams['PATH_TO_GROUP_TASKS_REPORT'],

                'PATH_TO_USER_TASKS' => $arParams['PATH_TO_USER_TASKS'],
                'PATH_TO_USER_TASKS_TASK' => $arParams['PATH_TO_USER_TASKS_TASK'],
                'PATH_TO_USER_TASKS_VIEW' => $arParams['PATH_TO_USER_TASKS_VIEW'],
                'PATH_TO_USER_TASKS_REPORT' => $arParams['PATH_TO_USER_TASKS_REPORT'],
                'PATH_TO_USER_TASKS_TEMPLATES' => $arParams['PATH_TO_USER_TASKS_TEMPLATES'],
                'PATH_TO_USER_TASKS_PROJECTS_OVERVIEW' => $arParams['PATH_TO_USER_TASKS_PROJECTS_OVERVIEW'],

                'PATH_TO_CONPANY_DEPARTMENT' => $arParams['PATH_TO_CONPANY_DEPARTMENT'],

                'MARK_TEMPLATES' => 'Y',
                'MARK_ACTIVE_ROLE' => 'N'
            ),
            $component,
            array('HIDE_ICONS' => true)
        );
    }?>

	<?php $this->SetViewTarget("pagetitle", 100);?>
	<div class="task-list-toolbar">
		<div class="task-list-toolbar-actions">
            <?php if (!$_REQUEST['IFRAME'])
            {
            	?><a href="<?=htmlspecialcharsbx($toList)?>" class="task-list-back"><?=Loc::getMessage('TASKS_TASK_TEMPLATE_COMPONENT_TEMPLATE_TO_LIST')?></a><?php
            }

			$APPLICATION->IncludeComponent(
				'bitrix:ui.feedback.form',
				'',
				$arResult['DATA']['FEEDBACK_FORM_PARAMETERS']
			);
            ?>
			<button class="ui-btn ui-btn-light-border ui-btn-themes ui-btn-icon-setting webform-cogwheel" id="templateViewPopupMenuOptions"></button>
			<?php if(!$helper->checkHasFatals()):?>
				<a class="webform-small-button webform-small-button-blue bx24-top-toolbar-add" href="<?=htmlspecialcharsbx($arParams["PATH_TO_TASKS_TEMPLATE_CREATE_SUB"])?>"><span class="webform-small-button-left"></span><span class="webform-small-button-icon"></span><span class="webform-small-button-text"><?=Loc::getMessage('TASKS_TASK_TEMPLATE_COMPONENT_TEMPLATE_ADD_SUBTEMPLATE')?></span><span class="webform-small-button-right"></span></a>
			<?php endif?>
		</div>
	</div>
	<?php $this->EndViewTarget();?>

<?php endif?>

<?php $helper->displayFatals();?>
<?php if(!$helper->checkHasFatals()):?>

	<?php 
	$diskUfCode = Integration\Disk\UserField::getMainSysUFCode();
	$templateData = $arResult['TEMPLATE_DATA'];
	$templateEData = $templateData['TEMPLATE'];
	$canUpdate = $template->canUpdate();
	$canDelete = $template->canDelete();
	$userFields = $arResult['TEMPLATE_DATA']['USER_FIELDS'];
	$matchWorkTime = $template['MATCH_WORK_TIME'] == 'Y';
	?>

	<div id="<?=$helper->getScopeId()?>" class="task-detail tasks">

		<?php $helper->displayWarnings();?>

		<div class="js-id-task-template-view-file-area task-detail-info">
			<div class="task-detail-header">
				<?php if($canUpdate):?>
					<div class="js-id-task-template-view-importance-switch task-info-panel-important <?php if($template["PRIORITY"] != CTasks::PRIORITY_HIGH):?>no<?php endif?> mutable" data-priority="<?=intval($template["PRIORITY"])?>">
						<span class="if-no"><?=Loc::getMessage("TASKS_TASK_COMPONENT_TEMPLATE_MAKE_IMPORTANT")?></span>
						<span class="if-not-no"><?=Loc::getMessage("TASKS_IMPORTANT_TASK")?></span>
					</div>
				<?php elseif($template["PRIORITY"] == CTasks::PRIORITY_HIGH):?>
					<div class="task-info-panel-important">
						<span class="if-not-no"><?=Loc::getMessage("TASKS_IMPORTANT_TASK")?></span>
					</div>
				<?php endif?>
				<div class="task-detail-subtitle-status">
					<?=Loc::getMessage('TASKS_TTV_SUB_TITLE', array('#ID#' => $template->getId()))?>
				</div>
			</div>
			<div class="task-detail-content">
				<?php 
				$checkListItems = $templateData['SE_CHECKLIST'];

				if (strlen($template["DESCRIPTION"])):
					$extraDesc = $canUpdate || !empty($checkListItems)
						|| (isset($userFields[$diskUfCode]) && !UserField::isValueEmpty($userFields[$diskUfCode]["VALUE"]))
					?>
					<div class="task-detail-description<?php if (!$extraDesc):?> task-detail-description-only<?php endif?>"
					     id="task-detail-description"><?=$template["DESCRIPTION"]?></div>
				<?php  endif ?>

				<?php if ($canUpdate || !empty($checkListItems)):?>
					<div class="task-detail-checklist">
						<?php $APPLICATION->IncludeComponent(
							'bitrix:tasks.widget.checklist.new',
							'',
							array(
								'ENTITY_ID' => $template->getId(),
								'ENTITY_TYPE' => 'TEMPLATE',
								'DATA' => $checkListItems,
								'PATH_TO_USER_PROFILE' => $arParams['PATH_TO_USER_PROFILE'],
								'CONVERTED' => $arResult['CHECKLIST_CONVERTED'],
								'CAN_ADD_ACCOMPLICE' => $canUpdate,
							),
							null,
							array("HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => "Y")
						);?>
					</div>
				<?php endif?>

				<?php // files\pictures ?>
				<?php if (isset($userFields[$diskUfCode]) && !Bitrix\Tasks\Util\UserField::isValueEmpty($userFields[$diskUfCode]["VALUE"])):?>
					<div class="task-detail-files">
						<?php UserField\UI::showView($userFields[$diskUfCode], array(
							"PUBLIC_MODE" => $arParams["PUBLIC_MODE"],
							"ENABLE_AUTO_BINDING_VIEWER" => false // file viewer cannot work in the iframe (see logic.js)
						));?>
					</div>
				<?php endif?>

				<?php  if (!$arParams["PUBLIC_MODE"]):?>
					<div class="task-detail-extra">

						<?php if($canUpdate || $template['GROUP_ID']):?>
							<div class="task-detail-group">
								<span class="task-detail-group-label"><?=Loc::getMessage("TASKS_TTDP_PROJECT_TASK_IN")?>:</span>

								<?php $APPLICATION->IncludeComponent(
									'bitrix:tasks.widget.member.selector',
									'projectlink',
									array(
										'TYPES' => array('PROJECT'),
										'DATA' => $arResult['TEMPLATE_DATA']['TEMPLATE']['SE_PROJECT'],
										'READ_ONLY' => !$canUpdate,
										'ENTITY_ID' => $template->getId(),
										'ENTITY_ROUTE' => 'task.template',
									),
									$helper->getComponent(),
									array("HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => "Y")
								);?>

							</div>
						<?php endif?>

						<?php  if (!empty($arResult['TEMPLATE_DATA']['TEMPLATE']['SE_PARENTITEM'])):?>
							<?php $parentItem = $arResult['TEMPLATE_DATA']['TEMPLATE']['SE_PARENTITEM'][0];?>
							<div class="task-detail-supertask"><?php 
								?><span class="task-detail-supertask-label"><?=Loc::getMessage($parentItem['ENTITY_TYPE'] == 'T' ? 'TASKS_PARENT_TASK' : 'TASKS_PARENT_TEMPLATE')?>:</span><?php 
								?><span class="task-detail-supertask-name"><a href="<?=$parentItem["URL"]?>"
							                                                  class="task-detail-group-link"><?=htmlspecialcharsbx($parentItem["TITLE"])?></a></span>
							</div>
						<?php  endif ?>
					</div>
				<?php  endif ?>

				<?php if(count($arResult['TEMPLATE_DATA']['USER_FIELDS_TO_SHOW'])):?>
					<div class="task-detail-properties">
						<table cellspacing="0" class="task-detail-properties-layout">
							<?php 
							foreach ($arResult['TEMPLATE_DATA']['USER_FIELDS_TO_SHOW'] as $userField)
							{
								$title = (string) $userField["EDIT_FORM_LABEL"] != '' ? $userField["EDIT_FORM_LABEL"] : $userField["FIELD_NAME"];
								?>
								<tr>
								<td class="task-detail-property-name"><?=htmlspecialcharsbx($title)?></td>
								<td class="task-detail-property-value">
									<?php UserField\UI::showView($userField, array(
										"PUBLIC_MODE" => $arParams["PUBLIC_MODE"],
										"ENABLE_AUTO_BINDING_VIEWER" => true,
									));?>
								</td>
								</tr><?php 
							}
							?>
						</table>
					</div>
				<?php endif?>

			</div>
		</div>

		<div class="task-detail-buttons">

			<?php $APPLICATION->IncludeComponent(
				"bitrix:tasks.widget.buttons",
				"",
				array(
					'TEMPLATE_CONTROLLER_ID' => $helper->getId().'-buttons',
					'SCHEME' => array(
						array(
							'CODE' => 'CREATE_BY',
							'GROUP' => 'MORE',
							'TITLE' => Loc::getMessage('TASKS_TEMPLATE_CREATE_TASK'),
							'TYPE' => 'link',
							'URL' => $arParams['PATH_TO_TASKS_TEMPLATE_CREATE_TASK'],
							'ACTIVE' => $template['TPARAM_TYPE'] != 1,
							'MENU_CLASS' => 'menu-popup-item-create',
						),
						array(
							'CODE' => 'CREATE_SUB',
							'GROUP' => 'MORE',
							'TITLE' => Loc::getMessage('TASKS_TEMPLATE_CREATE_SUB'),
							'TYPE' => 'link',
							'URL' => $arParams['PATH_TO_TASKS_TEMPLATE_CREATE_SUB'],
							'MENU_CLASS' => 'menu-popup-item-create',
						),
						array(
							'CODE' => 'COPY',
							'GROUP' => 'MORE',
							'TITLE' => Loc::getMessage('TASKS_TEMPLATE_COPY'),
							'TYPE' => 'link',
							'URL' => $arParams['PATH_TO_TASKS_TEMPLATE_COPY'],
							'MENU_CLASS' => 'menu-popup-item-copy',
						),
						array(
							'CODE' => 'DELETE',
							'GROUP' => 'MORE',
							'TITLE' => Loc::getMessage('TASKS_COMMON_DELETE'),
							'ACTIVE' => $canDelete,
							'MENU_CLASS' => 'menu-popup-item-delete',
						),
						array(
							'CODE' => 'UPDATE',
							'ACTIVE' => $canUpdate,
							'TITLE' => Loc::getMessage('TASKS_COMMON_EDIT'),
							'TYPE' => 'link',
							'URL' => $arParams['PATH_TO_TASKS_TEMPLATE_EDIT'],
                            'KEEP_SLIDER'=>true
						)
					)
				),
				$helper->getComponent(),
				array("HIDE_ICONS" => "Y")
			);?>

		</div>

		<?php if($arResult['TEMPLATE_DATA']['HAVE_SUB_TEMPLATES']):?>
			<div>
				<div class="task-detail-list">
					<div class="task-detail-list-title">
						<?=Loc::getMessage("TASKS_TASK_SUBTASKS")?>
					</div>
					<?php 
						$pathParams = array();
						if(is_array($arParams))
						{
							foreach($arParams as $param => $value)
							{
								if(strpos($param, 'PATH_') == 0)
									$pathParams[$param] = $value;
							}
						}

						$APPLICATION->IncludeComponent(
							'bitrix:tasks.templates.list',
							'',
							array_merge($pathParams, array(
								'HIDE_MENU'        => 'Y',
								'HIDE_FILTER'      => 'Y',
								'BASE_TEMPLATE_ID' => $template->getId(),
								'SET_TITLE'        => 'N',
							)), null, array("HIDE_ICONS" => "Y")
						);
					?>
				</div>
			</div>
		<?php endif?>

		<?php //related tasks?>
		<?php  if (count($templateEData["SE_RELATEDTASK"])):?>
			<div class="task-detail-list tasks-related-static-grid">
				<div class="task-detail-list-title"><?=Loc::getMessage('TASKS_TASK_LINKED_TASKS')?></div>
				<?php $APPLICATION->IncludeComponent(
					'bitrix:tasks.widget.related.selector',
					'staticgrid',
					array(
						'DATA' => $templateEData["SE_RELATEDTASK"],
						'USERS' => $arResult['DATA']['USER'],
						'TYPES' => array('TASK'),
						'PATH_TO_TASKS_TASK' => $arParams['PATH_TO_TASKS_TASK_WO_GROUP'],
						'PATH_TO_USER_PROFILE' => $arParams['PATH_TO_USER_PROFILE'],
						'NAME_TEMPLATE' => $arParams['NAME_TEMPLATE'],
					),
					$helper->getComponent(),
					array("HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => "Y")
				);?>
			</div>
		<?php  endif ?>

		<?php ob_start();?>
		<div class="task-detail-comments">
			<div class="task-comments-and-log">
				<div class="task-comments-log-switcher">
					<span class="task-switcher task-switcher-selected">
						<span class="task-switcher-text">
							<span class="task-switcher-text-inner">
								<?=Loc::getMessage('TASKS_CTT_SYS_LOG')?>
							</span>
						</span>
					</span>
				</div>

				<div class="task-switcher-block" style="display: block">

					<?php $logResult = $APPLICATION->IncludeComponent(
						'bitrix:tasks.syslog',
						'',
						array(
							'ENTITY_TYPE' => 1,
							'ENTITY_ID' => $template->getId(),
							'PAGE_SIZE' => 7,
						),
						$helper->getComponent(),
						array("HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => "Y")
					);?>

				</div>
			</div>
		</div>
		<?php $html = ob_get_clean();?>

		<?php 
		if(Result::isA($logResult))
		{
			$resultData = $logResult->getData();
			if($resultData['COUNT'])
			{
				print($html);
			}
		}
		?>

		<div class="task-footer-wrap" id="footerWrap">
			<?php $APPLICATION->IncludeComponent('bitrix:ui.button.panel', '', [
				'BUTTONS' => [
					[
						'TYPE' => 'save',
						'ID' => 'saveButton',
					],
					[
						'TYPE' => 'custom',
						'LAYOUT' => '<a class="ui-btn ui-btn-link" id="cancelButton">'.Loc::getMessage("TASKS_TTV_CANCEL_BUTTON_TEXT").'</a>',
					],
				],
			]);?>
		</div>

	</div>

	<?php 
	////////////////////////////////////////////////////////
	//// SIDEBAR

	$this->SetViewTarget("sidebar", 100);
	?>

		<div class="task-detail-sidebar">

			<div class="task-detail-sidebar-content">

				<?php if($template["DEADLINE_AFTER"]
					|| $template["START_DATE_PLAN_AFTER"]
					|| $template["END_DATE_PLAN_AFTER"]
					|| ($template["ALLOW_TIME_TRACKING"] === "Y" && $template["TIME_ESTIMATE"] > 0)
				):?>

					<div class="task-detail-sidebar-status">
						<span id="task-detail-status-name" class="task-detail-sidebar-status-text"><?=Loc::getMessage('TASKS_TTDP_DATES');?></span>
					</div>

					<?php if($template["DEADLINE_AFTER"]):?>
						<div class="task-detail-sidebar-item task-detail-sidebar-item-deadline">
							<div class="task-detail-sidebar-item-title"><?=Loc::getMessage("TASKS_FIELD_DEADLINE_AFTER")?>:</div>
							<div class="task-detail-sidebar-item-value"><?=$helper->formatDateAfter($matchWorkTime, $template["DEADLINE_AFTER"])?></div>
						</div>
					<?php endif?>

					<?php if($template["START_DATE_PLAN_AFTER"]):?>
						<div class="task-detail-sidebar-item">
							<div class="task-detail-sidebar-item-title"><?=Loc::getMessage("TASKS_FIELD_START_DATE_PLAN_AFTER")?>:</div>
							<div class="task-detail-sidebar-item-value"><?=$helper->formatDateAfter($matchWorkTime, $template["START_DATE_PLAN_AFTER"])?></div>
						</div>
					<?php endif?>

					<?php if($template["END_DATE_PLAN_AFTER"]):?>
						<div class="task-detail-sidebar-item">
							<div class="task-detail-sidebar-item-title"><?=Loc::getMessage("TASKS_FIELD_END_DATE_PLAN_AFTER")?>:</div>
							<div class="task-detail-sidebar-item-value"><?=$helper->formatDateAfter($matchWorkTime, $template["END_DATE_PLAN_AFTER"])?></div>
						</div>
					<?php endif?>

					<?php if($template["ALLOW_TIME_TRACKING"] === "Y" && $template["TIME_ESTIMATE"] > 0):?>
						<div class="task-detail-sidebar-item">
							<div class="task-detail-sidebar-item-title"><?=Loc::getMessage("TASKS_FIELD_TIME_ESTIMATE")?>:</div>
							<div class="task-detail-sidebar-item-value" id="task-detail-estimate-time-<?=$template["ID"]?>">
								<?=\Bitrix\Tasks\UI::formatTimeAmount($template["TIME_ESTIMATE"]);?>
							</div>
						</div>
					<?php endif?>

				<?php endif?>

				<?php $APPLICATION->IncludeComponent(
					'bitrix:tasks.widget.member.selector',
					'view',
					array(
						'DATA' => $arResult['TEMPLATE_DATA']['TEMPLATE']['SE_ORIGINATOR'],
						'READ_ONLY' => true,
						'ROLE' => 'ORIGINATOR',
						'MAX' => 1,
						'TITLE' => Loc::getMessage('TASKS_TTDP_TEMPLATE_USER_VIEW_ORIGINATOR'),
						'HIDE_IF_EMPTY' => 'N',
					),
					$helper->getComponent(),
					array("HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => "Y")
				);?>

				<?php $APPLICATION->IncludeComponent(
					'bitrix:tasks.widget.member.selector',
					'view',
					array(
						'DATA' => $arResult['TEMPLATE_DATA']['TEMPLATE']['SE_RESPONSIBLE'],
						'READ_ONLY' => !$canUpdate || $template['TPARAM_TYPE'] == 1 /*for new user*/,
						'ROLE' => 'RESPONSIBLES',
						'MIN' => 1,
						'ENABLE_SYNC' => true,
						'ENTITY_ID' => $template->getId(),
						'ENTITY_ROUTE' => 'task.template',
						'TITLE' => Loc::getMessage('TASKS_TTDP_TEMPLATE_USER_VIEW_RESPONSIBLE'),
						'HIDE_IF_EMPTY' => 'N',
					),
					$helper->getComponent(),
					array("HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => "Y")
				);?>

				<?php $APPLICATION->IncludeComponent(
					'bitrix:tasks.widget.member.selector',
					'view',
					array(
						'DATA' => $arResult['TEMPLATE_DATA']['TEMPLATE']['SE_ACCOMPLICE'],
						'READ_ONLY' => !$canUpdate,
						'ROLE' => 'ACCOMPLICES',
						'ENABLE_SYNC' => true,
						'ENTITY_ID' => $template->getId(),
						'ENTITY_ROUTE' => 'task.template',
						'TITLE' => Loc::getMessage('TASKS_TTDP_TEMPLATE_USER_VIEW_ACCOMPLICES'),
						'HIDE_IF_EMPTY' => !$canUpdate,
					),
					$helper->getComponent(),
					array("HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => "Y")
				);?>

				<?php $APPLICATION->IncludeComponent(
					'bitrix:tasks.widget.member.selector',
					'view',
					array(
						'DATA' => $arResult['TEMPLATE_DATA']['TEMPLATE']['SE_AUDITOR'],
						'READ_ONLY' => !$canUpdate,
						'ROLE' => 'AUDITORS',
						'ENABLE_SYNC' => true,
						'ENTITY_ID' => $template->getId(),
						'ENTITY_ROUTE' => 'task.template',
						'TITLE' => Loc::getMessage('TASKS_TTDP_TEMPLATE_USER_VIEW_AUDITORS'),
						'USER' => $arResult['DATA']['USER'][User::getId()],
						'HIDE_IF_EMPTY' => !$canUpdate,
					),
					$helper->getComponent(),
					array("HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => "Y")
				);?>

				<?php //replication?>
				<?php if(
					!$arParams["PUBLIC_MODE"] &&
					$template['TPARAM_TYPE'] != 1 &&
					!$template['BASE_TEMPLATE_ID'] &&

					!(!$canUpdate && $template['REPLICATE'] == 'N')
				):?>

					<div class="task-detail-sidebar-info-title"><?=Loc::getMessage("TASKS_SIDEBAR_REGULAR_TASK")?></div>
					<div class="task-detail-sidebar-info">
						<?php $APPLICATION->IncludeComponent(
							'bitrix:tasks.widget.replication',
							'view',
							array(
								'DATA' => $template['REPLICATE_PARAMS'],
								'COMPANY_WORKTIME' => $arResult['AUX_DATA']['COMPANY_WORKTIME'],
								'REPLICATE' => $template["REPLICATE"],
								'ENABLE_SYNC' => $canUpdate,
								'ENTITY_ID' => $template->getId(),
								'ENABLE_TEMPLATE_LINK' => 'N',
								'TEMPLATE_CREATED_BY' => $template['CREATED_BY'],
							),
							$helper->getComponent(),
							array("HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => "Y")
						);?>
					</div>

				<?php endif?>

				<?php //tags?>
				<?php if(!$arParams["PUBLIC_MODE"]):?>

					<?php $tags = $template['SE_TAG']?>
					<?php if($canUpdate || count($tags)):?>

						<?php $tagString = \Bitrix\Tasks\UI\Task\Tag::formatTagString($tags);?>

						<div class="task-detail-sidebar-info-title"><?=Loc::getMessage("TASKS_TASK_TAGS")?></div>
						<div class="task-detail-sidebar-info">
							<div class="task-detail-sidebar-info-tag"><?php 
								if ($canUpdate)
								{
									$APPLICATION->IncludeComponent(
										"bitrix:tasks.tags.selector",
										".default",
										array(
											"NAME" => "TAGS",
											"VALUE" => $tagString,
										),
										null,
										array("HIDE_ICONS" => "Y")
									);
								}
								else
								{
									print(htmlspecialcharsbx($tagString));
								}
								?>
							</div>
						</div>
					<?php endif?>

				<?php endif?>

				<?php if(!$arParams["PUBLIC_MODE"] && $template['TPARAM_TYPE'] == 1):?>

					<div class="task-detail-sidebar-info task-detail-sidebar-info-type-new-hint">
						<?=Loc::getMessage('TASKS_TTV_TYPE_FOR_NEW_USER_HINT');?>
					</div>

				<?php endif?>

			</div>
		</div>

	<?php 
	$this->EndViewTarget();
	?>

	<?php $helper->initializeExtension();?>

<?php endif?>
</div>
