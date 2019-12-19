<?php 
use \Bitrix\Main\Localization\Loc;

/**
 * @var array $arResult
 * @var CMain $APPLICATION
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
\Bitrix\Main\Page\Asset::getInstance()->addCss('/bitrix/css/main/table/style.css');
?>

<div id="imopenlines-correction-permissions-edit">
<form method="POST" action="<?=$arResult['ACTION_URI']?>">
	<?php echo bitrix_sessid_post();?>
	<table class="table-blue-wrapper">
		<tr>
			<td>
				<table class="table-blue">
					<tr>
						<td class="table-blue-td-name" style="width: 25%"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_THAT_NOT_SHOWN')?></td>
						<td class="table-blue-td-select" style="width: 15%"></td>
						<td class="table-blue-td-name" style="width: 10%">
							<input type="submit" name="sessions_that_not_shown" class="webform-small-button webform-small-button-accept" value="<?=Loc::getMessage('IMOL_CORRECTION_RUN')?>">
						</td>
						<td class="table-blue-td-name" style="width: 50%">
							<?php if(isset($arResult['SESSIONS_THAT_NOT_SHOWN']) && empty($arResult['SESSIONS_THAT_NOT_SHOWN'])):?>
								<?=Loc::getMessage('IMOL_CORRECTION_NO_SESSIONS_FOUND')?>
							<?php elseif(isset($arResult['SESSIONS_THAT_NOT_SHOWN']) && !empty($arResult['SESSIONS_THAT_NOT_SHOWN'])):?>
								<?=Loc::getMessage('IMOL_CORRECTION_SESSIONS')?>:<br>
							<?php foreach ($arResult['SESSIONS_THAT_NOT_SHOWN'] as $key => $value):?>
									<?php if($key>0):?>, <?php endif;?><nobr><a href="?IM_HISTORY=imol|<?=$value?>" onclick="BXIM.openHistory('imol|<?=$value?>'); return false;"><?=$value?></a></nobr>
							<?php endforeach;?>
							<?php endif;?>
						</td>
					</tr>
				</table>

				<table class="table-blue">
					<tr>
						<td class="table-blue-td-name" style="width: 25%"><?=Loc::getMessage('IMOL_CORRECTION_STATUS_CLOSED_SESSIONS')?></td>
						<td class="table-blue-td-select" style="width: 15%">
							<label><input type="checkbox" name="status_closed_sessions_correction" value="Y"<?php if($arResult['STATUS_CLOSED_SESSIONS_CORRECTION']):?> checked="checked"<?php endif;?>><?=Loc::getMessage('IMOL_CORRECTION_STATUS_RUN_CORRECTION')?></label>
						</td>
						<td class="table-blue-td-name" style="width: 10%">
							<input type="submit" name="status_closed_sessions" class="webform-small-button webform-small-button-accept" value="<?=Loc::getMessage('IMOL_CORRECTION_RUN')?>">
						</td>
						<td class="table-blue-td-name" style="width: 50%">
							<?php if(isset($arResult['STATUS_CLOSED_SESSIONS']) && empty($arResult['STATUS_CLOSED_SESSIONS'])):?>
								<?=Loc::getMessage('IMOL_CORRECTION_NO_SESSIONS_FOUND')?>
							<?php elseif(isset($arResult['STATUS_CLOSED_SESSIONS']) && !empty($arResult['STATUS_CLOSED_SESSIONS'])):?>
								<?php if($arResult['STATUS_CLOSED_SESSIONS_CORRECTION']):?>
									<?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_NOT_RIGHT_STATUS_CORRECTION')?>:<br>
								<?php else:?>
									<?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_NOT_RIGHT_STATUS')?>:<br>
								<?php endif;?>

								<?php foreach ($arResult['STATUS_CLOSED_SESSIONS'] as $key => $value):?>
									<?php if($key>0):?>, <?php endif;?><nobr><a href="?IM_HISTORY=imol|<?=$value?>" onclick="BXIM.openHistory('imol|<?=$value?>'); return false;"><?=$value?></a></nobr>
								<?php endforeach;?>
							<?php endif;?>
						</td>
					</tr>
				</table>

				<table class="table-blue">
					<tr>
						<td class="table-blue-td-name" style="width: 25%"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_NO_AUTO_CLOSE_DATE')?></td>
						<td class="table-blue-td-select" style="width: 15%">
							<label><input type="checkbox" name="correct_sessions_data_close_correction" value="Y"<?php if($arResult['CORRECT_SESSIONS_DATA_CLOSE_CORRECTION']):?> checked="checked"<?php endif;?>><?=Loc::getMessage('IMOL_CORRECTION_STATUS_RUN_CORRECTION')?></label><br><br>
							<label>
								<?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_TITLE')?>:
								<select name="correct_sessions_data_close_select">
									<option<?php if(!empty($arResult['CORRECT_SESSIONS_DATA_CLOSE_SELECT'])):?> selected<?php endif;?> value="0"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_0')?></option>
									<option<?php if($arResult['CORRECT_SESSIONS_DATA_CLOSE_SELECT'] == 30):?> selected<?php endif;?> value="30"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_30')?></option>
									<option<?php if($arResult['CORRECT_SESSIONS_DATA_CLOSE_SELECT'] == 40):?> selected<?php endif;?> value="40"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_40')?></option>
									<option<?php if($arResult['CORRECT_SESSIONS_DATA_CLOSE_SELECT'] == 50):?> selected<?php endif;?> value="50"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_50')?></option>
									<option<?php if($arResult['CORRECT_SESSIONS_DATA_CLOSE_SELECT'] == 60):?> selected<?php endif;?> value="60"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_60')?></option>
									<option<?php if($arResult['CORRECT_SESSIONS_DATA_CLOSE_SELECT'] == 70):?> selected<?php endif;?> value="70"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_70')?></option>
									<option<?php if($arResult['CORRECT_SESSIONS_DATA_CLOSE_SELECT'] == 80):?> selected<?php endif;?> value="80"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_80')?></option>
									<option<?php if($arResult['CORRECT_SESSIONS_DATA_CLOSE_SELECT'] == 90):?> selected<?php endif;?> value="90"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_90')?></option>
									<option<?php if($arResult['CORRECT_SESSIONS_DATA_CLOSE_SELECT'] == 100):?> selected<?php endif;?> value="100"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_100')?></option>
								</select>
							</label>
						</td>
						<td class="table-blue-td-name" style="width: 10%">
							<input type="submit" name="correct_sessions_data_close" class="webform-small-button webform-small-button-accept" value="<?=Loc::getMessage('IMOL_CORRECTION_RUN')?>">
						</td>
						<td class="table-blue-td-name" style="width: 50%">
							<?php if(isset($arResult['CORRECT_SESSIONS_DATA_CLOSE']) && empty($arResult['CORRECT_SESSIONS_DATA_CLOSE']['CLOSE']) && empty($arResult['CORRECT_SESSIONS_DATA_CLOSE']['UPDATE'])):?>
								<?=Loc::getMessage('IMOL_CORRECTION_NO_SESSIONS_FOUND')?>
							<?php elseif(isset($arResult['CORRECT_SESSIONS_DATA_CLOSE']) && !empty($arResult['CORRECT_SESSIONS_DATA_CLOSE'])):?>

								<?php if(!empty($arResult['CORRECT_SESSIONS_DATA_CLOSE']['CLOSE'])):?>
									<?=Loc::getMessage('CORRECT_SESSIONS_CLOSE')?>:<br>
									<?php foreach ($arResult['CORRECT_SESSIONS_DATA_CLOSE']['CLOSE'] as $key => $value):?>
										<?php if($key>0):?>, <?php endif;?><nobr><a href="?IM_HISTORY=imol|<?=$value?>" onclick="BXIM.openHistory('imol|<?=$value?>'); return false;"><?=$value?></a></nobr>
									<?php endforeach;?>
								<?php endif;?>

								<?php if(!empty($arResult['CORRECT_SESSIONS_DATA_CLOSE']['UPDATE'])):?>
									<?=Loc::getMessage('CORRECT_SESSIONS_UPDATE')?>:<br>
									<?php foreach ($arResult['CORRECT_SESSIONS_DATA_CLOSE']['UPDATE'] as $key => $value):?>
										<?php if($key>0):?>, <?php endif;?><nobr><a href="?IM_HISTORY=imol|<?=$value?>" onclick="BXIM.openHistory('imol|<?=$value?>'); return false;"><?=$value?></a></nobr>
									<?php endforeach;?>
								<?php endif;?>
							<?php endif;?>
						</td>
					</tr>
				</table>

				<table class="table-blue">
					<tr>
						<td class="table-blue-td-name" style="width: 25%"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_BROKEN_CONSISTENCY_DATABASE')?></td>
						<td class="table-blue-td-select" style="width: 15%">
							<label><input type="checkbox" name="repair_broken_sessions_correction" value="Y"<?php if($arResult['REPAIR_BROKEN_SESSIONS_CORRECTION']):?> checked="checked"<?php endif;?>><?=Loc::getMessage('IMOL_CORRECTION_STATUS_RUN_CORRECTION')?></label><br><br>
							<label>
								<?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_TITLE')?>:
								<select name="repair_broken_sessions_correction_select">
									<option<?php if(!empty($arResult['REPAIR_BROKEN_SESSIONS_CORRECTION_SELECT'])):?> selected<?php endif;?> value="0"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_0')?></option>
									<option<?php if($arResult['REPAIR_BROKEN_SESSIONS_CORRECTION_SELECT'] == 30):?> selected<?php endif;?> value="30"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_30')?></option>
									<option<?php if($arResult['REPAIR_BROKEN_SESSIONS_CORRECTION_SELECT'] == 40):?> selected<?php endif;?> value="40"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_40')?></option>
									<option<?php if($arResult['REPAIR_BROKEN_SESSIONS_CORRECTION_SELECT'] == 50):?> selected<?php endif;?> value="50"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_50')?></option>
									<option<?php if($arResult['REPAIR_BROKEN_SESSIONS_CORRECTION_SELECT'] == 60):?> selected<?php endif;?> value="60"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_60')?></option>
									<option<?php if($arResult['REPAIR_BROKEN_SESSIONS_CORRECTION_SELECT'] == 70):?> selected<?php endif;?> value="70"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_70')?></option>
									<option<?php if($arResult['REPAIR_BROKEN_SESSIONS_CORRECTION_SELECT'] == 80):?> selected<?php endif;?> value="80"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_80')?></option>
									<option<?php if($arResult['REPAIR_BROKEN_SESSIONS_CORRECTION_SELECT'] == 90):?> selected<?php endif;?> value="90"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_90')?></option>
									<option<?php if($arResult['REPAIR_BROKEN_SESSIONS_CORRECTION_SELECT'] == 100):?> selected<?php endif;?> value="100"><?=Loc::getMessage('IMOL_CORRECTION_SESSIONS_SELECT_DAY_100')?></option>
								</select>
							</label>
						</td>
						<td class="table-blue-td-name" style="width: 10%">
							<input type="submit" name="repair_broken_sessions" class="webform-small-button webform-small-button-accept" value="<?=Loc::getMessage('IMOL_CORRECTION_RUN')?>">
						</td>
						<td class="table-blue-td-name" style="width: 50%">
							<?php if(isset($arResult['REPAIR_BROKEN_SESSIONS']) && empty($arResult['REPAIR_BROKEN_SESSIONS']['CLOSE']) && empty($arResult['REPAIR_BROKEN_SESSIONS']['UPDATE'])):?>
								<?=Loc::getMessage('IMOL_CORRECTION_NO_SESSIONS_FOUND')?>
							<?php elseif(isset($arResult['REPAIR_BROKEN_SESSIONS']) && !empty($arResult['REPAIR_BROKEN_SESSIONS'])):?>

								<?php if(!empty($arResult['REPAIR_BROKEN_SESSIONS']['CLOSE'])):?>
									<?=Loc::getMessage('CORRECT_SESSIONS_CLOSE')?>:<br>
									<?php foreach ($arResult['REPAIR_BROKEN_SESSIONS']['CLOSE'] as $key => $value):?>
										<?php if($key>0):?>, <?php endif;?><nobr><a href="?IM_HISTORY=imol|<?=$value?>" onclick="BXIM.openHistory('imol|<?=$value?>'); return false;"><?=$value?></a></nobr>
									<?php endforeach;?>
								<?php endif;?>

								<?php if(!empty($arResult['REPAIR_BROKEN_SESSIONS']['UPDATE'])):?>
									<?=Loc::getMessage('CORRECT_SESSIONS_UPDATE')?>:<br>
									<?php foreach ($arResult['REPAIR_BROKEN_SESSIONS']['UPDATE'] as $key => $value):?>
										<?php if($key>0):?>, <?php endif;?><nobr><a href="?IM_HISTORY=imol|<?=$value?>" onclick="BXIM.openHistory('imol|<?=$value?>'); return false;"><?=$value?></a></nobr>
									<?php endforeach;?>
								<?php endif;?>
							<?php endif;?>
						</td>
					</tr>
				</table>

			</td>
		</tr>
	</table>
	<input type="submit" name="correction_run_all" class="webform-small-button webform-small-button-accept" value="<?=Loc::getMessage('IMOL_CORRECTION_RUN_ALL')?>">
</form>
</div>
