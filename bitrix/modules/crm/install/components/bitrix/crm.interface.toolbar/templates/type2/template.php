<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)die();
global $APPLICATION;
$APPLICATION->SetAdditionalCSS('/bitrix/js/crm/css/crm.css');
\Bitrix\Main\UI\Extension::load("ui.buttons");
$toolbarID =  $arParams['TOOLBAR_ID'];
$prefix =  $toolbarID.'_';
?><div class="bx-crm-view-menu" id="<?=htmlspecialcharsbx($toolbarID)?>"><?php 

$moreItems = array();
$enableMoreButton = false;
$labelText = '';
$documentButton = null;
foreach($arParams['BUTTONS'] as $k => $item):
	if ($item['LABEL'] === true)
	{
		$labelText = isset($item['TEXT']) ? $item['TEXT'] : '';
		continue;
	}
	if(!$enableMoreButton && isset($item['NEWBAR']) && $item['NEWBAR'] === true):
		$enableMoreButton = true;
		continue;
	endif;

	if($enableMoreButton):
		$moreItems[] = $item;
		continue;
	endif;

	$link = isset($item['LINK']) ? $item['LINK'] : '#';
	$text = isset($item['TEXT']) ? $item['TEXT'] : '';
	$title = isset($item['TITLE']) ? $item['TITLE'] : '';
	$type = isset($item['TYPE']) ? $item['TYPE'] : 'context';
	$code = isset($item['CODE']) ? $item['CODE'] : '';
	$visible = isset($item['VISIBLE']) ? (bool)$item['VISIBLE'] : true;
	$target = isset($item['TARGET']) ? $item['TARGET'] : '';

	$iconBtnClassName = '';
	if (isset($item['ICON']))
	{
		$iconBtnClassName = 'crm-'.$item['ICON'];
	}

	$onclick = isset($item['ONCLICK']) ? $item['ONCLICK'] : '';
	if ($type == 'toolbar-split-left')
	{
		$item_tmp = reset($item['LINKS']);
		?><span class="crm-toolbar-btn-split crm-toolbar-btn-left <?=$iconBtnClassName; ?>"
			<?php  if ($code !== '') { ?> id="<?=htmlspecialcharsbx("{$prefix}{$code}"); ?>"<?php  } ?>
			<?php  if (!$visible) { ?> style="display: none;"<?php  } ?>>
			<span class="crm-toolbar-btn-split-l"
				title="<?=(isset($item_tmp['TITLE']) ? htmlspecialcharsbx($item_tmp['TITLE']) : ''); ?>"
				<?php  if (isset($item_tmp['ONCLICK'])) { ?> onclick="<?=htmlspecialcharsbx($item_tmp['ONCLICK']); ?>; return false;"<?php  } ?>>
				<span class="crm-toolbar-btn-split-bg"><span class="crm-toolbar-btn-icon"></span><?php 
					echo (isset($item_tmp['TEXT']) ? htmlspecialcharsbx($item_tmp['TEXT']) : '');
				?></span>
			</span><span class="crm-toolbar-btn-split-r" onclick="btnMenu_<?=$k; ?>.ShowMenu(this);">
			<span class="crm-toolbar-btn-split-bg"></span></span>
		</span>
		<script>
			var btnMenu_<?=$k; ?> = new PopupMenu('bxBtnMenu_<?=$k; ?>', 1010);
			btnMenu_<?=$k; ?>.SetItems([
				<?php  foreach ($item['LINKS'] as $v) { ?>
				{
					'DEFAULT': <?=(isset($v['DEFAULT']) && $v['DEFAULT'] ? 'true' : 'false'); ?>,
					'DISABLED': <?=(isset($v['DISABLED']) && $v['DISABLED'] ? 'true' : 'false'); ?>,
					'ICONCLASS': "<?=(isset($v['ICONCLASS']) ? htmlspecialcharsbx($v['ICONCLASS']) : ''); ?>",
					'ONCLICK': "<?=(isset($v['ONCLICK']) ? $v['ONCLICK'] : ''); ?>; return false;",
					'TEXT': "<?=(isset($v['TEXT']) ? htmlspecialcharsbx($v['TEXT']) : ''); ?>",
					'TITLE': "<?=(isset($v['TITLE']) ? htmlspecialcharsbx($v['TITLE']) : ''); ?>"
				},
				<?php  } ?>
			]);
		</script><?php 
	}
	else if ($type == 'toolbar-left')
	{
		?><a class="crm-toolbar-btn crm-toolbar-btn-left <?=$iconBtnClassName; ?>"
			<?php  if ($code !== '') { ?> id="<?=htmlspecialcharsbx("{$prefix}{$code}"); ?>"<?php  } ?>
			href="<?=htmlspecialcharsbx($link)?>"
			<?php  if($target !== '') { ?> target="<?=$target?>"<?php  } ?>
			title="<?=htmlspecialcharsbx($title)?>"
			<?php  if ($onclick !== '') { ?> onclick="<?=htmlspecialcharsbx($onclick); ?>; return false;"<?php  } ?>
			<?php  if (!$visible) { ?> style="display: none;"<?php  } ?>>
			<span class="crm-toolbar-btn-icon"></span><span><?=htmlspecialcharsbx($text); ?></span></a><?php 
	}
	else if ($type === 'toolbar-menu' || $type == 'toolbar-menu-left')
	{
		if ($code !== '')
		{
			$menuId = $prefix.$code;
			$lastClass = '';
			if ($type === 'toolbar-menu')
			{
				$lastClass = 'crm-toolbar-menu';
			}
			else if ($type == 'toolbar-menu-left')
			{
				$lastClass = 'crm-toolbar-menu-left';
			}
			$classAttribute = ' class="ui-btn ui-btn-md ui-btn-light-border ui-btn-dropdown '.
				$lastClass.'"';
			$idAttribute = ' id="'.htmlspecialcharsbx($menuId).'"';
			$titleAttribute = '';
			if (is_string($title) && strlen($title) > 0)
			{
				$titleAttribute = ' title="'.htmlspecialcharsbx($title).'"';
			}
			?>
				<button<?=$classAttribute?><?=$idAttribute?><?=$titleAttribute?>><?=$item['TEXT'];?></button>
				<script>
					BX.ready(function()
					{
						BX.bind(BX('<?=CUtil::JSEscape($menuId);?>'), 'click', function()
						{
							BX.PopupMenu.show(
								'<?=CUtil::JSEscape($menuId);?>_menu',
								BX('<?=CUtil::JSEscape($menuId);?>'),
								<?=CUtil::PhpToJSObject($item['ITEMS']);?>,
								{
									offsetLeft: 0,
									offsetTop: 0,
									closeByEsc: true,
									className: '<?=$lastClass?>'
								}
							);
						});
					});
				</script>
			<?php 
			unset($menuId, $lastClass, $classAttribute, $idAttribute, $titleAttribute);
		}
	}
	else if ($type == 'crm-document-button')
	{
		if ($code !== '')
		{
			$documentButtonId = $prefix.$code;
			$classAttribute = ' class="ui-btn ui-btn-md ui-btn-light-border ui-btn-dropdown '.
				'crm-btn-dropdown-document"';
			$idAttribute = ' id="'.htmlspecialcharsbx($documentButtonId).'"';
			$titleAttribute = '';
			if (is_string($title) && strlen($title) > 0)
			{
				$titleAttribute = ' title="'.htmlspecialcharsbx($title).'"';
			}
			?>
			<button<?=$classAttribute?><?=$idAttribute?><?=$titleAttribute?>><?=$item['TEXT'];?></button>
			<script>
				BX.ready(function()
				{
					var button = new BX.DocumentGenerator.Button('<?=htmlspecialcharsbx($documentButtonId);?>', <?=CUtil::PhpToJSObject($item['PARAMS']);?>);
					button.init();
				});
			</script>
			<?php 
			unset($documentButtonId, $classAttribute, $idAttribute, $titleAttribute);
		}
	}
	else if ($type == 'toolbar-conv-scheme')
	{
		$params = isset($item['PARAMS']) ? $item['PARAMS'] : array();

		$typeID = isset($params['TYPE_ID']) ? (int)$params['TYPE_ID'] : 0;
		$schemeName = isset($params['SCHEME_NAME']) ? $params['SCHEME_NAME'] : null;
		$schemeDescr = isset($params['SCHEME_DESCRIPTION']) ? $params['SCHEME_DESCRIPTION'] : null;
		$name = isset($params['NAME']) ? $params['NAME'] : $code;
		$entityID = isset($params['ENTITY_ID']) ? (int)$params['ENTITY_ID'] : 0;
		$entityTypeID = isset($params['ENTITY_TYPE_ID']) ? (int)$params['ENTITY_TYPE_ID'] : CCrmOwnerType::Undefined;
		$isPermitted = isset($params['IS_PERMITTED']) ? (bool)$params['IS_PERMITTED'] : false;
		$lockScript = isset($params['LOCK_SCRIPT']) ? $params['LOCK_SCRIPT'] : '';

		$hintKey = 'enable_'.strtolower($name).'_hint';
		$hint = isset($params['HINT']) ? $params['HINT'] : array();

		$enableHint = !empty($hint);
		if($enableHint)
		{
			$options = CUserOptions::GetOption("crm.interface.toobar", "conv_scheme_selector", array());
			$enableHint = !(isset($options[$hintKey]) && $options[$hintKey] === 'N');
		}

		$iconBtnClassName = $isPermitted ? 'crm-btn-convert' : 'crm-btn-convert crm-btn-convert-blocked';
		$originUrl = $APPLICATION->GetCurPage();

		$containerID = "{$prefix}{$code}";
		$labelID = "{$prefix}{$code}_label";
		$buttonID = "{$prefix}{$code}_button";

		if($isPermitted && $entityTypeID === CCrmOwnerType::Lead)
		{
			Bitrix\Main\Page\Asset::getInstance()->addJs('/bitrix/js/crm/crm.js');
		}
		?>
		<span class="crm-btn-convert-wrap">
			<a class="bx-context-button <?=$iconBtnClassName; ?>"
				id="<?=htmlspecialcharsbx($containerID); ?>"
				href="<?=htmlspecialcharsbx($link)?>"
				<?php  if($target !== '') { ?> target="<?=$target?>"<?php  } ?>
				title="<?=htmlspecialcharsbx($title)?>"
				onclick="return false;"
				<?php  if (!$visible) { ?> style="display: none;"<?php  } ?>>
				<span class="bx-context-button-icon"></span>
				<span>
					<?=htmlspecialcharsbx($text);?>
					<span class="crm-btn-convert-text" id="<?=htmlspecialcharsbx($labelID);?>">
						<?=htmlspecialcharsbx($schemeDescr)?>
					</span>
				</span>
			</a>
			<span class="crm-btn-convert-arrow" id="<?=htmlspecialcharsbx($buttonID);?>"></span><?php 
			?><script type="text/javascript">
				BX.ready(
					function()
					{
						//region Toolbar script
						<?php $selectorID = CUtil::JSEscape($name);?>
						<?php $originUrl = CUtil::JSEscape($originUrl);?>
						<?php if($isPermitted):?>
							<?php if($entityTypeID === CCrmOwnerType::Lead):?>
								BX.CrmLeadConversionSchemeSelector.create(
									"<?=$selectorID?>",
									{
										typeId: <?=$typeID?>,
										entityId: <?=$entityID?>,
										scheme: "<?=$schemeName?>",
										containerId: "<?=$containerID?>",
										labelId: "<?=$labelID?>",
										buttonId: "<?=$buttonID?>",
										originUrl: "<?=$originUrl?>",
										enableHint: <?=CUtil::PhpToJSObject($enableHint)?>,
										hintMessages: <?=CUtil::PhpToJSObject($hint)?>
									}
								);
							<?php elseif($entityTypeID === CCrmOwnerType::Deal):?>
								BX.CrmDealConversionSchemeSelector.create(
									"<?=$selectorID?>",
									{
										entityId: <?=$entityID?>,
										scheme: "<?=$schemeName?>",
										containerId: "<?=$containerID?>",
										labelId: "<?=$labelID?>",
										buttonId: "<?=$buttonID?>",
										originUrl: "<?=$originUrl?>",
										enableHint: <?=CUtil::PhpToJSObject($enableHint)?>,
										hintMessages: <?=CUtil::PhpToJSObject($hint)?>
									}
								);

								BX.addCustomEvent(window,
									"CrmCreateQuoteFromDeal",
									function()
									{
										BX.CrmDealConverter.getCurrent().convert(
											<?=$entityID?>,
											BX.CrmDealConversionScheme.createConfig(BX.CrmDealConversionScheme.quote),
											"<?=$originUrl?>"
										);
									}
								);

								BX.addCustomEvent(window,
									"CrmCreateInvoiceFromDeal",
									function()
									{
										BX.CrmDealConverter.getCurrent().convert(
											<?=$entityID?>,
											BX.CrmDealConversionScheme.createConfig(BX.CrmDealConversionScheme.invoice),
											"<?=$originUrl?>"
										);
									}
								);
							<?php elseif($entityTypeID === CCrmOwnerType::Quote):?>
								BX.CrmQuoteConversionSchemeSelector.create(
									"<?=$selectorID?>",
									{
										entityId: <?=$entityID?>,
										scheme: "<?=$schemeName?>",
										containerId: "<?=$containerID?>",
										labelId: "<?=$labelID?>",
										buttonId: "<?=$buttonID?>",
										originUrl: "<?=$originUrl?>",
										enableHint: <?=CUtil::PhpToJSObject($enableHint)?>,
										hintMessages: <?=CUtil::PhpToJSObject($hint)?>
									}
								);

								BX.addCustomEvent(window,
									"CrmCreateDealFromQuote",
									function()
									{
										BX.CrmQuoteConverter.getCurrent().convert(
											<?=$entityID?>,
											BX.CrmQuoteConversionScheme.createConfig(BX.CrmQuoteConversionScheme.deal),
											"<?=$originUrl?>"
										);
									}
								);

								BX.addCustomEvent(window,
									"CrmCreateInvoiceFromQuote",
									function()
									{
										BX.CrmQuoteConverter.getCurrent().convert(
											<?=$entityID?>,
											BX.CrmQuoteConversionScheme.createConfig(BX.CrmQuoteConversionScheme.invoice),
											"<?=$originUrl?>"
										);
									}
								);
							<?php endif;?>
						<?php elseif($lockScript !== ''):?>
							var showLockInfo = function()
							{
								<?=$lockScript?>
							};
							BX.bind(BX("<?=$containerID?>"), "click", showLockInfo );
							<?php if($entityTypeID === CCrmOwnerType::Deal):?>
								BX.addCustomEvent(window, "CrmCreateQuoteFromDeal", showLockInfo);
								BX.addCustomEvent(window, "CrmCreateInvoiceFromDeal", showLockInfo);
							<?php elseif($entityTypeID === CCrmOwnerType::Quote):?>
								BX.addCustomEvent(window, "CrmCreateDealFromQuote", showLockInfo);
								BX.addCustomEvent(window, "CrmCreateInvoiceFromQuote", showLockInfo);
							<?php endif;?>
						<?php endif;?>
						//endregion
					}
				);
			</script><?php 
		?></span><?php 
	}
	elseif ($type == 'toolbar-activity-planner')
	{
		$params = isset($item['PARAMS']) ? $item['PARAMS'] : array();
		if (isset($params['MENU']) && is_array($params['MENU'])):
		$plannerActionNodeId = $prefix.'_act_pl_act';
		$plannerActionOpenerId = $prefix.'_act_pl_opn';

		?>
		<span class="crm-btn-convert-wrap crm-toolbar-btn-planner-wrap">
			<a class="bx-context-button crm-toolbar-button" title="<?=htmlspecialcharsbx($title)?>">
				<span class="crm-toolbar-btn-planner-icon"></span>
				<span class="crm-toolbar-btn-planner-item"><?=htmlspecialcharsbx($text)?>:</span>
				<span class="crm-toolbar-btn-planner-link" id="<?=htmlspecialcharsbx($plannerActionNodeId)?>" data-action-id="<?=htmlspecialcharsbx($params['DEFAULT_ACTION_ID'])?>"><?=htmlspecialcharsbx($params['DEFAULT_ACTION_TEXT'])?></span>
			</a><!--crm-toolbar-btn-planner-wrap-->
			<span class="crm-btn-convert-arrow crm-toolbar-arrow" id="<?=htmlspecialcharsbx($plannerActionOpenerId)?>"></span>
		</span>
		<script>
			BX.ready(
				function()
				{
					BX.Crm.Activity.PlannerToolbar.setActions(<?=Bitrix\Main\Web\Json::encode($params['MENU'])?>);
					BX.Crm.Activity.PlannerToolbar.bindNodes({
						action: BX('<?=htmlspecialcharsbx($plannerActionNodeId)?>'),
						opener: BX('<?=htmlspecialcharsbx($plannerActionOpenerId)?>')
					});
				}
			);
		</script>
		<?php 
		endif;
	}
	else
	{
		?><a class="ui-btn ui-btn-primary <?=$iconBtnClassName; ?>"
			<?php  if ($code !== '') { ?> id="<?=htmlspecialcharsbx("{$prefix}{$code}"); ?>"<?php  } ?>
			href="<?=htmlspecialcharsbx($link)?>"
			<?php  if($target !== '') { ?> target="<?=$target?>"<?php  } ?>
			title="<?=htmlspecialcharsbx($title)?>"
			<?php  if ($onclick !== '') { ?> onclick="<?=htmlspecialcharsbx($onclick); ?>; return false;"<?php  } ?>
			<?php  if (!$visible) { ?> style="display: none;"<?php  } ?>><?=htmlspecialcharsbx($text); ?></a><?php 
	}

endforeach;
if(!empty($moreItems)):
	?><a class="bx-context-button crm-btn-more" href="#">
		<span class="bx-context-button-icon"></span>
		<span><?=htmlspecialcharsbx(GetMessage('CRM_INTERFACE_TOOLBAR_BTN_MORE'))?></span>
	</a>
	<script type="text/javascript">
		BX.ready(
			function()
			{
				BX.InterfaceToolBar.create(
					"<?=CUtil::JSEscape($toolbarID)?>",
					BX.CrmParamBag.create(
						{
							"containerId": "<?=CUtil::JSEscape($toolbarID)?>",
							"prefix": "<?=CUtil::JSEscape($prefix)?>",
							"moreButtonClassName": "crm-btn-more",
							"items": <?=CUtil::PhpToJSObject($moreItems)?>
						}
					)
				);
			}
		);
	</script>
<?php 
endif;
if ($labelText != ''):
?><div class="crm-toolbar-label2"><span id="<?= $toolbarID.'_label' ?>"><?=htmlspecialcharsbx($labelText)?></span></div><?php 
endif;
?></div>
