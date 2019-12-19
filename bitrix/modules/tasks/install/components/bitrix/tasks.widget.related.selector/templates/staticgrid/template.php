<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arParams =& $this->__component->arParams;
?>

<div class="task-list">
	<table class="task-list-table" cellspacing="0" style="width:100%">
		<thead>
			<tr>
				<th></th>
				<?php foreach($arResult['COLUMNS'] as $column):?>
					<th <?=($column['SOURCE'] == 'TITLE'? 'style="width: 35%"' : '')?>>
						<div class="task-head-cell">
							<span class="task-head-cell-title"><?=htmlspecialcharsbx($column['TITLE'])?></span>
						</div>
					</th>
				<?php endforeach?>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($arParams['DATA'] as $item):?>
				<tr class="task-list-item task-depth-0 task-status-accepted">
					<td></td>
					<?php foreach($arResult['COLUMNS'] as $column):?>
						<td>
							<?php if($column['SOURCE'] == 'TITLE'):?>
								<div class="task-title-info">
									<a class="task-title-link<?=($item['STATUS'] == 5? ' task-title-complete' : '')?>"
									   href="<?=htmlspecialcharsbx($item["URL"])?>"><?=htmlspecialcharsbx($item["TITLE"])?></a>
								</div>
							<?php elseif($column['SOURCE'] == 'RESPONSIBLE_ID'):?>
								<a href="<?=htmlspecialcharsbx($item["RESPONSIBLE_URL"])?>" class="task-responsible-link" target="_top"><?=htmlspecialcharsbx($item["RESPONSIBLE_FORMATTED_NAME"])?></a>
							<?php else:?>
								<?=htmlspecialcharsbx($item[$column['SOURCE']])?>
							<?php endif?>
						</td>
					<?php endforeach?>
					<td></td>
				</tr>
			<?php endforeach?>
		</tbody>
	</table>
</div>
