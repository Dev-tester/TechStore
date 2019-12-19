<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

echo '<link rel="stylesheet" type="text/css" href="', $this->getFolder(), '/style.css?v13" />';
echo '<script type="text/javascript" src="', $this->getFolder(), '/script.js?v2"></script>';

if (!function_exists('CrmEntityTreeConvertDateTime'))
{
	/*
	 * For optimization format date.
	 */
	function CrmEntityTreeConvertDateTime($datetime, $to_format=false, $from_site=false, $bSearchInSitesOnly=false)
	{
		if (preg_match('/[^\d]+/', $datetime))
		{
			return \ConvertDateTime($datetime, $to_format, $from_site, $bSearchInSitesOnly);
		}
		else
		{
			return \ConvertTimeStamp($datetime);
		}
	}
}



if (!function_exists('CrmEntityTreeDrawActivity'))
{
	/*
	 * Draw activity block for one entity.
	 */
	function CrmEntityTreeDrawActivity($id, $type, $activity, $leadId=null)
	{
		static $label = null;
		static $activityTypes = array();

		if ($label === null)
		{
			$label = Loc::getMessage('CRM_ENTITY_TREE_ACTIVITY');
		}
		if (empty($activityTypes))
		{
			//crm.activity.list/templates/grid
			$activityTypes = array(
				//call
				\CCrmActivityType::Call => array(
					'title' => Loc::getMessage('CRM_ENTITY_TREE_ACTIVITY_CALL'),
					'icon' => 'crm-doc-droplist-item-call'
				),
				\CCrmActivityType::Call . '_' . \CCrmActivityDirection::Incoming => array(
					'title' => Loc::getMessage('CRM_ENTITY_TREE_ACTIVITY_CALL_INCOMING'),
					'icon' => 'crm-doc-droplist-item-call-in'
				),
				\CCrmActivityType::Call . '_' . \CCrmActivityDirection::Outgoing => array(
					'title' => Loc::getMessage('CRM_ENTITY_TREE_ACTIVITY_CALL_OUTGOING'),
					'icon' => 'crm-doc-droplist-item-call-out'
				),
				//email
				\CCrmActivityType::Email => array(
					'title' => Loc::getMessage('CRM_ENTITY_TREE_ACTIVITY_EMAIL'),
					'icon' => 'crm-doc-droplist-item-mail',
				),
				\CCrmActivityType::Email . '_' . \CCrmActivityDirection::Incoming => array(
					'title' => Loc::getMessage('CRM_ENTITY_TREE_ACTIVITY_EMAIL_INCOMING'),
					'icon' => 'crm-doc-droplist-item-mail-in',
				),
				\CCrmActivityType::Email . '_' . \CCrmActivityDirection::Outgoing => array(
					'title' => Loc::getMessage('CRM_ENTITY_TREE_ACTIVITY_EMAIL_OUTGOING'),
					'icon' => 'crm-doc-droplist-item-mail-out',
				),
				//other
				\CCrmActivityType::Meeting => array(
					'title' => Loc::getMessage('CRM_ENTITY_TREE_ACTIVITY_MEETING'),
					'icon' => 'crm-doc-droplist-item-meeting',
				),
				\CCrmActivityType::Task => array(
					'title' => Loc::getMessage('CRM_ENTITY_TREE_ACTIVITY_TASK'),
					'icon' => 'crm-doc-droplist-item-check',
				),
				\CCrmActivityType::Provider => array(
					'title' => '',
					'icon' => 'crm-doc-droplist-item-check',
					'provider' => true
				),
				'default' => array(
					'title' => Loc::getMessage('CRM_ENTITY_TREE_ACTIVITY_DEFAULT'),
					'icon' => 'crm-doc-droplist-item-check'
				)
			);
		}

		if (
			isset($activity[$type]) &&
			isset($activity[$type][$id]) &&
			!empty($activity[$type][$id])
		)
		{
			//add parent lead's activity
			if (
				$leadId &&
				isset($activity['LEAD']) &&
				isset($activity['LEAD'][$leadId]) &&
				!empty($activity['LEAD'][$leadId])
			)
			{
				$activity[$type][$id] += $activity['LEAD'][$leadId];
				ksort($activity[$type][$id]);
				$activity[$type][$id] = array_reverse($activity[$type][$id], true);
			}
			?>
			<div class="crm-doc-drop">
				<div class="crm-doc-drop-link" data-role="drop-link"><?php 
					?><span class="crm-doc-drop-link-border"><?= $label?></span> <?php 
					?><span class="crm-doc-drop-link-number">(<?= count($activity[$type][$id])?>)</span><?php 
				?></div>
				<div class="crm-doc-droplist-wrapper">
					<ul class="crm-doc-droplist">
						<?php foreach ($activity[$type][$id] as $item):
							$visual = $activityTypes['default'];
							if (array_key_exists($item['TYPE_ID'], $activityTypes))
							{
								$provider = isset($activityTypes[$item['TYPE_ID']]['provider']) && $activityTypes[$item['TYPE_ID']]['provider'] === true;
								if ($provider && ($provider = \CCrmActivity::GetActivityProvider($item)) !== null)
								{
									$visual = array(
										'title' => $provider::getTypeName($item['PROVIDER_TYPE_ID'], $item['DIRECTION']),
										'icon' => 'crm-doc-droplist-item-' . strtolower($provider::getId())
									);
								}
								elseif (isset($activityTypes[$item['TYPE_ID'] .'_'. $item['DIRECTION']]))
								{
									$visual = $activityTypes[$item['TYPE_ID'] .'_'. $item['DIRECTION']];
								}
								elseif (isset($activityTypes[$item['TYPE_ID']]))
								{
									$visual = $activityTypes[$item['TYPE_ID']];
								}
							}
							?>
							<li class="crm-doc-droplist-item <?= $visual['icon']?>" title="<?= $visual['title']?>"><?= $item['SUBJECT']?></li>
						<?php endforeach;?>
					</ul>
				</div>
			</div>
			<?php 
		}
	}
}

if (!function_exists('CrmEntityTreeDrawItem'))
{
	/*
	 * Draw one item of tree.
	 */
	function CrmEntityTreeDrawItem($item, $params, $result)
	{
		static $lang = array();
		static $counter = 0;
		$counter++;
		if (empty($lang))
		{
			$lang = array(
				'ASSIGNED_BY' => Loc::getMessage('CRM_ENTITY_TREE_ASSIGNED_BY'),
				'QUOTE' => Loc::getMessage('CRM_ENTITY_TREE_QUOTE'),
				'INVOICE' => Loc::getMessage('CRM_ENTITY_TREE_INVOICE'),
				'ORDER' => Loc::getMessage('CRM_ENTITY_TREE_ORDER'),
				'ORDER_PAYMENT' => Loc::getMessage('CRM_ENTITY_TREE_ORDER_PAYMENT'),
				'ORDER_SHIPMENT' => Loc::getMessage('CRM_ENTITY_TREE_ORDER_SHIPMENT'),
				'LEAD' => Loc::getMessage('CRM_ENTITY_TREE_LEAD'),
				'DEAL' => Loc::getMessage('CRM_ENTITY_TREE_DEAL'),
				'DATE_BEGIN' => Loc::getMessage('CRM_ENTITY_TREE_DATE_BEGIN'),
				'DATE_CREATE' => Loc::getMessage('CRM_ENTITY_TREE_DATE_CREATE'),
				'DATE_CLOSE' => Loc::getMessage('CRM_ENTITY_TREE_DATE_CLOSE'),
				'DATE_PAYED' => Loc::getMessage('CRM_ENTITY_TREE_DATE_PAYED'),
				'DATE_BILL' => Loc::getMessage('CRM_ENTITY_TREE_DATE_BILL'),
				'SUM' => Loc::getMessage('CRM_ENTITY_TREE_SUM'),
				'EMAIL' => Loc::getMessage('CRM_ENTITY_TREE_EMAIL'),
				'PHONE' => Loc::getMessage('CRM_ENTITY_TREE_PHONE'),
				'SOURCE' => Loc::getMessage('CRM_ENTITY_TREE_SOURCE')
			);
		}
		$statuses = $params['STATUSES'];
		$codes = $params['TYPES'];
		$selected = $params['ENTITY_TYPE_NAME'] == $item['TREE_TYPE'] && $params['ENTITY_ID'] == $item['ID'];

		echo '<li class="crm-doc-ul-li">';
		switch ($item['TREE_TYPE'])
		{
			case $codes['lead']:
				?>
				<div class="crm-doc-cart<?= $selected ? ' crm-tree-active' : ''?><?= $counter == 1 ? ' crm-doc-cart-top' : ''?>">
					<div class="crm-doc-title"><span class="crm-doc-title-gray"><?= $lang['LEAD']?>:</span>
						<a href="<?= $item['URL']?>" class="crm-tree-link" data-id="<?= $item['ID']?>" data-type="<?= $item['TREE_TYPE']?>"><?= $item['TITLE']?></a>
						<?php if ($item['IS_RETURN_CUSTOMER'] == 'Y'):?>
						<div>
							<?= Loc::getMessage('CRM_ENTITY_TREE_IS_RETURN_CUSTOMER');?>
						</div>
						<?php endif;?>
					</div>
					<div class="crm-doc-info">
						<?php if ($item['STATUS_ID']):
							$name = $statuses['STATUS'][$item['STATUS_ID']]['NAME'];
							$width = isset($statuses['STATUS'][$item['STATUS_ID']]['CHUNK'])
									? round($statuses['STATUS'][$item['STATUS_ID']]['CHUNK'] * 100 / $statuses['STATUS']['__COUNT'], 2)
									: 100;
							$color = $statuses['STATUS'][$item['STATUS_ID']]['COLOR'] != ''
									? $statuses['STATUS'][$item['STATUS_ID']]['COLOR']
									: \Bitrix\Crm\Color\LeadStatusColorScheme::getDefaultColorByStatus($item['STATUS_ID']);
							?>
						<div class="crm-doc-info-progress">
							<div class="crm-doc-info-progressbar">
								<div class="crm-doc-info-progressbar-indikator" style="background-color: <?= $color?>; width: <?= $width?>%"></div>
							</div>
							<div class="crm-doc-info-text"><?= $name?></div>
						</div>
						<?php endif;?>
						<?php if ($item['ASSIGNED_BY_ID'] > 0):?>
						<div class="crm-doc-info-responsible">
							<div class="crm-doc-info-text"><?= $lang['ASSIGNED_BY']?>:
								<?php 
								echo CCrmViewHelper::PrepareUserBaloonHtml(
											array(
												'PREFIX' => 'LEAD_'.$item['ID'].'_'.$item['ASSIGNED_BY_ID'],
												'USER_ID' => $item['ASSIGNED_BY_ID'],
												'USER_NAME'=> $item['ASSIGNED_BY_FORMATTED_NAME'],
												'USER_PROFILE_URL' => $item['ASSIGNED_BY_URL']
									)
								)
								?>
							</div>
						</div>
						<?php endif;?>
						<div class="crm-doc-info-param">
							<table class="crm-doc-table">
								<tbody>
								<?php if ($item['SOURCE_ID']):?>
								<tr>
									<td><?= $lang['SOURCE']?>:</td>
									<td><?= $statuses['SOURCE'][$item['SOURCE_ID']]['NAME']?></td>
								</tr>
								<?php endif;?>
								<tr>
									<td><?= $lang['DATE_CREATE']?>:</td>
									<td><?= CrmEntityTreeConvertDateTime($item['DATE_CREATE'], FORMAT_DATE)?></td>
								</tr>
								</tbody>
							</table>
						</div>
						<?php CrmEntityTreeDrawActivity($item['ID'], $item['TREE_TYPE'], $result['ACTIVITY']);?>
					</div>
				</div>
				<?php 
				break;
			case $codes['company']:
			case $codes['contact']:
				?>
				<div class="crm-doc-cart<?= $selected ? ' crm-tree-active' : ''?><?= $counter == 1 ? ' crm-doc-cart-top' : ''?>">
					<div class="crm-doc-cart-user">
						<div class="crm-doc-cart-<?= $item['TREE_TYPE'] == $codes['company'] ? 'company' : 'user'?>-avatar"<?php 
															?><?php if ($item['TREE_TYPE'] == $codes['company'] && $item['LOGO']){?> style="background-image: url('<?= $item['LOGO_FILE']['src']?>'); background-position: center;"<?php }?><?php 
															?><?php if ($item['TREE_TYPE'] == $codes['contact'] && $item['PHOTO']){?> style="background-image: url('<?= $item['PHOTO_FILE']['src']?>'); background-position: center;"<?php }?><?php 
															?>></div>
						<div class="crm-doc-cart-user-info">
							<a href="<?= $item['URL']?>" class="crm-doc-cart-user-name crm-tree-link" data-id="<?= $item['ID']?>" data-type="<?= $item['TREE_TYPE']?>"><?php 
								if ($item['TREE_TYPE'] == $codes['company'])
								{
									echo $item['TITLE'];
								}
								elseif ($item['TREE_TYPE'] == $codes['contact'])
								{
									echo $item['LAST_NAME'], ' ', $item['NAME'], ' ', $item['SECOND_NAME'];
								}
							?></a>
							<?php if ($item['TREE_TYPE'] == $codes['contact'] && $item['COMPANY_TITLE']):?>
							<div class="crm-doc-cart-user-company"><?= $item['COMPANY_TITLE']?></div>
							<?php endif;?>
							<?php if ($item['TREE_TYPE'] == $codes['company'] && $item['COMPANY_TYPE']):?>
							<div class="crm-doc-cart-user-company"><?= $statuses['COMPANY_TYPE'][$item['COMPANY_TYPE']]['NAME']?></div>
							<?php endif;?>
							<?php CrmEntityTreeDrawActivity($item['ID'], $item['TREE_TYPE'], $result['ACTIVITY'], $item['LEAD_ID']);?>
						</div>
					</div>
					<?php if (isset($item['FM_VALUES'])):?>
					<div class="crm-doc-cart-contact">
						<table>
						<?php if (isset($item['FM_VALUES']['EMAIL'])):?>
							<tr>
								<td><?php if ($p == 0){?><?= $lang['EMAIL']?>: <?php }?></td>
								<td>
									<?php foreach ($item['FM_VALUES']['EMAIL'] as $p => $val):?>
										<a href="mailto:<?= $val?>" class="crm-doc-gray crm-doc-bold crm-doc-clear crm-doc-cart-contact-item-email"><?= $val?></a>
									<?php endforeach;?>
								</td>
							</tr>
						<?php endif;?>
						<?php if (isset($item['FM_VALUES']['PHONE'])):?>
							<tr>
								<td><?= $lang['PHONE']?>: </td>
								<td>
									<?php foreach ($item['FM_VALUES']['PHONE'] as $p => $val):
										$formatCU = \CCrmCallToUrl::PrepareLinkAttributes($val, array(
											'ENTITY_TYPE' => $item['TREE_TYPE'],
											'ENTITY_ID' => $item['ID']
										));
										?>
									<a href="<?= htmlspecialcharsbx($formatCU['HREF'])?>"<?php 
										?><?php if ($formatCU['ONCLICK'] != ''){?> onclick="<?= htmlspecialcharsbx($formatCU['ONCLICK'])?>"<?php }?><?php 
										?> class="crm-doc-gray crm-doc-bold crm-doc-clear crm-doc-cart-contact-item-phone"><?php 
											?><?= $val?><?php 
										?></a>
									<?php endforeach;?>
								</td>
							</tr>
						<?php endif;?>
						</table>
					</div>
					<?php endif;?>
					<div class="crm-doc-cart-create">
						<?= $lang['DATE_CREATE']?>: <span class="crm-doc-gray"><?= CrmEntityTreeConvertDateTime($item['DATE_CREATE'], FORMAT_DATE)?></span>
					</div>
				</div>
				<?php 
				break;
			case $codes['deal']:
				?>
				<div class="crm-doc-cart<?= $selected ? ' crm-tree-active' : ''?><?= $counter == 1 ? ' crm-doc-cart-top' : ''?>">
					<div class="crm-doc-cart-info">
						<a href="<?= $item['URL']?>" class="crm-doc-cart-title crm-doc-cart-title-deal crm-tree-link" data-id="<?= $item['ID']?>" data-type="<?= $item['TREE_TYPE']?>"><?php 
							?><span class="crm-doc-gray"><?= $lang['DEAL']?>:</span> <?php 
							?><?= $item['TITLE']?><?php 
						?></a>
						<?php if ($item['ASSIGNED_BY_ID'] > 0):?>
						<div class="crm-doc-info-text"><?= $lang['ASSIGNED_BY']?>:
							<?php 
							echo CCrmViewHelper::PrepareUserBaloonHtml(
										array(
											'PREFIX' => 'DEAL_'.$item['ID'].'_'.$item['ASSIGNED_BY_ID'],
											'USER_ID' => $item['ASSIGNED_BY_ID'],
											'USER_NAME'=> $item['ASSIGNED_BY_FORMATTED_NAME'],
											'USER_PROFILE_URL' => $item['ASSIGNED_BY_URL']
								)
							)
							?>
						</div>
						<?php endif;?>
						<?php CrmEntityTreeDrawActivity($item['ID'], $item['TREE_TYPE'], $result['ACTIVITY'], $item['LEAD_ID']);?>
					</div>
					<div class="crm-doc-cart-param">
						<div class="crm-doc-info-progress">
							<?php if ($item['STAGE_ID']):
								$statusGroup = $item['CATEGORY_ID'] > 0 ? $statuses['DEAL_STAGE_' . $item['CATEGORY_ID']] : $statuses['DEAL_STAGE'];
								$name = $statusGroup[$item['STAGE_ID']]['NAME'];
								$width = isset($statusGroup[$item['STAGE_ID']]['CHUNK'])
										? round($statusGroup[$item['STAGE_ID']]['CHUNK'] * 100 / $statusGroup['__COUNT'], 2)
										: 100;
								$color = $statusGroup[$item['STAGE_ID']]['COLOR'] != ''
										? $statusGroup[$item['STAGE_ID']]['COLOR']
										: \Bitrix\Crm\Color\DealStageColorScheme::getDefaultColorByStage($item['STAGE_ID']);
								?>
							<div class="crm-doc-info-progressbar">
								<div class="crm-doc-info-progressbar-indikator" style="background-color: <?= $color?>; width: <?= $width?>%"></div>
							</div>
							<div class="crm-doc-info-text"><?= $name?></div>
							<?php endif;?>
							<table class="crm-doc-info-table">
								<tr>
									<td><?= $lang['DATE_BEGIN']?>:</td>
									<td><?= CrmEntityTreeConvertDateTime($item['BEGINDATE'] ? $item['BEGINDATE'] : $item['DATE_CREATE'], FORMAT_DATE)?></td>
								</tr>
								<?php if ($item['CLOSEDATE']):?>
								<tr>
									<td><?= $lang['DATE_CLOSE']?>:</td>
									<td><?= CrmEntityTreeConvertDateTime($item['CLOSEDATE'], FORMAT_DATE)?></td>
								</tr>
								<?php endif;?>
								<?php if ($item['OPPORTUNITY'] > 0):?>
								<tr>
									<td><?= $lang['SUM']?>:</td>
									<td><?= $item['OPPORTUNITY_FORMATTED']?></td>
								</tr>
								<?php endif;?>
							</table>
						</div>
					</div>
				</div>
				<?php 
				break;
			case $codes['quote']:
				?>
				<div class="crm-doc-cart<?= $selected ? ' crm-tree-active' : ''?><?= $counter == 1 ? ' crm-doc-cart-top' : ''?>">
					<div class="crm-doc-cart-info">
						<a href="<?= $item['URL']?>" target="_top" class="crm-doc-cart-title crm-doc-cart-title-sentence crm-tree-link" data-id="<?= $item['ID']?>" data-type="<?= $item['TREE_TYPE']?>"><?php 
							?><span class="crm-doc-gray"><?= $lang['QUOTE']?>:</span> <?php 
							?><?= $item['TITLE']?><?php 
						?></a>
						<?php if ($item['ASSIGNED_BY_ID'] > 0):?>
						<div class="crm-doc-info-text"><?= $lang['ASSIGNED_BY']?>:
						<?php 
						echo CCrmViewHelper::PrepareUserBaloonHtml(
									array(
										'PREFIX' => 'QUOTE_'.$item['ID'].'_'.$item['ASSIGNED_BY_ID'],
										'USER_ID' => $item['ASSIGNED_BY_ID'],
										'USER_NAME'=> $item['ASSIGNED_BY_FORMATTED_NAME'],
										'USER_PROFILE_URL' => $item['ASSIGNED_BY_URL']
							)
						)
						?>
						</div>
						<?php endif;?>
					</div>
					<div class="crm-doc-cart-param">
						<div class="crm-doc-info-progress">
							<?php if ($item['STATUS_ID']):
								$name = $statuses['QUOTE_STATUS'][$item['STATUS_ID']]['NAME'];
								$width = isset($statuses['QUOTE_STATUS'][$item['STATUS_ID']]['CHUNK'])
										? round($statuses['QUOTE_STATUS'][$item['STATUS_ID']]['CHUNK'] * 100 / $statuses['QUOTE_STATUS']['__COUNT'], 2)
										: 100;
								$color = $statuses['QUOTE_STATUS'][$item['STATUS_ID']]['COLOR'] != ''
										? $statuses['QUOTE_STATUS'][$item['STATUS_ID']]['COLOR']
										: \Bitrix\Crm\Color\QuoteStatusColorScheme::getDefaultColorByStatus($item['STATUS_ID']);
								?>
							<div class="crm-doc-info-progressbar">
								<div class="crm-doc-info-progressbar-indikator" style="background-color: <?= $color?>; width: <?= $width?>%"></div>
							</div>
							<div class="crm-doc-info-text"><?= $name?></div>
							<?php endif;?>
							<table class="crm-doc-info-table">
								<col class="crm-doc-info-table-1">
								<col class="crm-doc-info-table-2">
								<tr>
									<td><?= $lang['DATE_BILL']?>:</td>
									<td><?= CrmEntityTreeConvertDateTime($item['BEGINDATE'] ? $item['BEGINDATE'] : $item['DATE_CREATE'], FORMAT_DATE)?></td>
								</tr>
								<?php if ($item['CLOSEDATE']):?>
								<tr>
									<td><?= $lang['DATE_CLOSE']?>:</td>
									<td><?= CrmEntityTreeConvertDateTime($item['CLOSEDATE'], FORMAT_DATE)?></td>
								</tr>
								<?php endif;?>
							</table>
						</div>
					</div>
				</div>
				<?php 
				break;
			case $codes['order']:
			case $codes['order_payment']:
			case $codes['order_shipment']:
			case $codes['invoice']:
				?>
				<div class="crm-doc-cart<?= $selected ? ' crm-tree-active' : ''?><?= $counter == 1 ? ' crm-doc-cart-top' : ''?>">
					<div class="crm-doc-cart-info">
						<a href="<?= $item['URL']?>" target="_top" class="crm-doc-cart-title crm-doc-cart-title-invoice crm-tree-link" data-id="<?= $item['ID']?>" data-type="<?= $item['TREE_TYPE']?>"><?php 
							?><span class="crm-doc-gray"><?= $lang[strtoupper($item['TREE_TYPE'])]?><?= $item['ACCOUNT_NUMBER']?>:</span> <?php 
							?><?= strlen($item['ORDER_TOPIC']) > 0 ? $item['ORDER_TOPIC'] : Loc::getMessage('CRM_ENTITY_TREE_UNTITLED')?><?php 
						?></a>
						<?php if ($item['RESPONSIBLE_ID'] > 0):?>
						<div class="crm-doc-info-text"><?= $lang['ASSIGNED_BY']?>:
						<?php 
						echo CCrmViewHelper::PrepareUserBaloonHtml(
									array(
										'PREFIX' => 'INVOICE_'.$item['ID'].'_'.$item['RESPONSIBLE_ID'],
										'USER_ID' => $item['RESPONSIBLE_ID'],
										'USER_NAME'=> $item['RESPONSIBLE_FORMATTED_NAME'],
										'USER_PROFILE_URL' => $item['RESPONSIBLE_URL']
							)
						)
						?>
						</div>
						<?php endif;?>
					</div>
					<div class="crm-doc-cart-param">
						<div class="crm-doc-info-progress">
							<?php if ($item['STATUS_ID']):
								$typeStatusName = $item['TREE_TYPE'].'_STATUS';
								$name = $statuses[$typeStatusName][$item['STATUS_ID']]['NAME'];
								$width = isset($statuses[$typeStatusName][$item['STATUS_ID']]['CHUNK'])
										? round($statuses[$typeStatusName][$item['STATUS_ID']]['CHUNK'] * 100 / $statuses[$typeStatusName]['__COUNT'], 2)
										: 100;
								if ($statuses[$typeStatusName][$item['STATUS_ID']]['COLOR'] != '')
								{
									$color = $statuses[$typeStatusName][$item['STATUS_ID']]['COLOR'];
								}
								else
								{
									$defaultSemantic = null;
									switch ($item['TREE_TYPE'])
									{
										case $codes['invoice']:
											$defaultSemantic = \CCrmInvoice::GetSemanticID($item['STATUS_ID']);
											break;
										case $codes['order']:
											$defaultSemantic = \Bitrix\Crm\Order\OrderStatus::getSemanticID($item['STATUS_ID']);
											break;
										case $codes['order_shipment']:
											$defaultSemantic = \Bitrix\Crm\Order\DeliveryStatus::getSemanticID($item['STATUS_ID']);
											break;
									}
									$color = \Bitrix\Crm\Color\PhaseColorScheme::getDefaultColorBySemantics($defaultSemantic);
								}
								?>
							<div class="crm-doc-info-progressbar">
								<div class="crm-doc-info-progressbar-indikator" style="background-color: <?= $color?>; width: <?= $width?>%"></div>
							</div>
							<div class="crm-doc-info-text"><?= $name?></div>
							<?php endif;?>
							<table class="crm-doc-info-table">
								<tr>
									<td><?= $lang['DATE_BILL']?>:</td>
									<td><?= CrmEntityTreeConvertDateTime($item['DATE_BILL'] ? $item['DATE_BILL'] : $item['DATE_INSERT_FORMAT'], FORMAT_DATE)?></td>
								</tr>
								<?php if ($item['DATE_PAY_BEFORE']):?>
								<tr>
									<td><?= $lang['DATE_PAYED']?>:</td>
									<td><?= CrmEntityTreeConvertDateTime($item['DATE_PAY_BEFORE'], FORMAT_DATE)?></td>
								</tr>
								<?php endif;?>
								<tr>
									<td><?= $lang['SUM']?>:</td>
									<td><?= $item['PRICE_FORMATTED']?></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<?php 
				break;
		}
		echo '</li>';
	}
}

if (!function_exists('CrmEntityTreeDrawRecur'))
{
	/*
	 * Draw tree recursive.
	 */
	function CrmEntityTreeDrawRecur($entities, $params, $result)
	{
		echo '<ul class="crm-doc-ul">';
		foreach ($entities as $type => $entity)
		{
			foreach ($entity as $id => $entityItem)
			{
				CrmEntityTreeDrawItem($entityItem, $params, $result);
				if (isset($entityItem['SUB_ENTITY']) && !empty($entityItem['SUB_ENTITY']))
				{
					CrmEntityTreeDrawRecur($entityItem['SUB_ENTITY'], $params, $result);
				}
			}
			//echo '<a href="javascript:void(0);" class="crm-entity-more" data-page="', $params[$entity['TREE_TYPE'] . '_PAGE'], '" data-block="', $type, '">???</a>';
		}
		echo '</ul>';
	}
}
?>



<div class="crm-doc">
	<div class="crm-doc-three">
	<?php 
	//parent with base element
	foreach ($arResult['BASE'] as $item)
	{
		echo '<ul class="crm-doc-ul">';
		CrmEntityTreeDrawItem($item, $arParams, $arResult);
	}
	CrmEntityTreeDrawRecur($arResult['TREE'], $arParams, $arResult);
	echo str_repeat('</ul>', count($arResult['BASE']));
	?>
	</div>
</div>

<script>
	BX.ready(function ()
	{
		var dropLink = document.querySelectorAll('[data-role="drop-link"]');
		for(var i = 0; i <= dropLink.length; i++) {
			BX.bind(dropLink[i], 'click', function ()
			{
				var getNextEl = this.nextElementSibling;
				var getNextElheight = getNextEl.offsetHeight;
				var getNextInner = getNextEl.firstElementChild.offsetHeight;
				if(getNextElheight > 0){
					getNextEl.style.height = "0px";
					this.parentNode.classList.remove('crm-doc-drop-open');
				} else {
					getNextEl.style.height = getNextInner + "px";
					this.parentNode.classList.add('crm-doc-drop-open');
				}
			})
		}
	});
</script>
