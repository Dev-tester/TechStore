<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<p class="bx_order_list">
	<?php if(strlen($arResult["ERROR_MESSAGE"])):?>
		<?=ShowError($arResult["ERROR_MESSAGE"]);?>
	<?php else:?>	
		<?php if($arParams["SHOW_ORDER_BASE"]=='Y' || $arParams["SHOW_ORDER_USER"]=='Y' || $arParams["SHOW_ORDER_PARAMS"]=='Y' || $arParams["SHOW_ORDER_BUYER"]=='Y' || $arParams["SHOW_ORDER_DELIVERY"]=='Y' || $arParams["SHOW_ORDER_PAYMENT"]=='Y'):?>
		<table class="bx_order_list_table">
			<thead>
				<tr>
					<td colspan="2">
						<?=GetMessage('SPOD_ORDER')?> <?=GetMessage('SPOD_NUM_SIGN')?><?=$arResult["ACCOUNT_NUMBER"]?>
						<?php if(strlen($arResult["DATE_INSERT_FORMATED"])):?>
							<?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_INSERT_FORMATED"]?>
						<?php endif?>
					</td>
				</tr>
			</thead>
			<tbody>
			<?php if($arParams["SHOW_ORDER_BASE"]=='Y'):?>
				<tr>
					<td>
						<?=GetMessage('SPOD_ORDER_STATUS')?>:
					</td>
					<td>
						<?=htmlspecialcharsbx($arResult["STATUS"]["NAME"])?>
						<?php if(strlen($arResult["DATE_STATUS_FORMATED"])):?>
							(<?=GetMessage("SPOD_FROM")?> <?=$arResult["DATE_STATUS_FORMATED"]?>)
						<?php endif?>
					</td>
				</tr>
				<tr>
					<td>
						<?=GetMessage('SPOD_ORDER_PRICE')?>:
					</td>
					<td>
						<?=$arResult["PRICE_FORMATED"]?>
						<?php if(floatval($arResult["SUM_PAID"])):?>
							(<?=GetMessage('SPOD_ALREADY_PAID')?>:&nbsp;<?=$arResult["SUM_PAID_FORMATED"]?>)
						<?php endif?>
					</td>
				</tr>
				<?php 
				if (!empty($arResult["SUM_REST"]))
				{
					?>
					<tr>
						<td>
							<?=GetMessage('SPOD_ORDER_SUM_REST')?>:
						</td>
						<td>
							<?=$arResult["SUM_REST_FORMATED"]?>
						</td>
					</tr>
					<?php 
				}
				?>
				<?php if($arResult["CANCELED"] == "Y" || $arResult["CAN_CANCEL"] == "Y"):?>
					<tr>
						<td><?=GetMessage('SPOD_ORDER_CANCELED')?>:</td>
						<td>
							<?php if($arResult["CANCELED"] == "Y"):?>
								<?=GetMessage('SPOD_YES')?>
								<?php if(strlen($arResult["DATE_CANCELED_FORMATED"])):?>
									(<?=GetMessage('SPOD_FROM')?> <?=$arResult["DATE_CANCELED_FORMATED"]?>)
								<?php endif?>
							<?php elseif($arResult["CAN_CANCEL"] == "Y"):?>
								<?=GetMessage('SPOD_NO')?>&nbsp;&nbsp;&nbsp;[<a href="<?=$arResult["URL_TO_CANCEL"]?>"><?=GetMessage("SPOD_ORDER_CANCEL")?></a>]
							<?php endif?>
						</td>
					</tr>
				<?php endif?>
				<tr><td><br></td><td></td></tr>
			<?php endif?>
				
			<?php if($arParams["SHOW_ORDER_USER"]=='Y'):?>
				<?php if(intval($arResult["USER_ID"])):?>

					<tr>
						<td colspan="2"><?=GetMessage('SPOD_ACCOUNT_DATA')?></td>
					</tr>
					<?php if(strlen($arResult["USER_NAME"])):?>
						<tr>
							<td><?=GetMessage('SPOD_ACCOUNT')?>:</td>
							<td><?=htmlspecialcharsbx($arResult["USER_NAME"])?></td>
						</tr>
					<?php endif?>
					<tr>
						<td><?=GetMessage('SPOD_LOGIN')?>:</td>
						<td><?=htmlspecialcharsbx($arResult["USER"]["LOGIN"])?></td>
					</tr>
					<tr>
						<td><?=GetMessage('SPOD_EMAIL')?>:</td>
						<td><a href="mailto:<?=htmlspecialcharsbx($arResult["USER"]["EMAIL"])?>"><?=htmlspecialcharsbx($arResult["USER"]["EMAIL"])?></a></td>
					</tr>

					<tr><td><br></td><td></td></tr>

				<?php endif?>
			<?php endif?>

			<?php if($arParams["SHOW_ORDER_PARAMS"]=='Y'):?>
				<tr>
					<td colspan="2"><?=GetMessage('SPOD_ORDER_PROPERTIES')?></td>
				</tr>
				<tr>
					<td><?=GetMessage('SPOD_ORDER_PERS_TYPE')?>:</td>
					<td><?=htmlspecialcharsbx($arResult["PERSON_TYPE"]["NAME"])?></td>
				</tr>
			<?php endif?>
			
			<?php if($arParams["SHOW_ORDER_BUYER"]=='Y'):?>
				<?php foreach($arResult["ORDER_PROPS"] as $prop):?>

					<?php if($prop["SHOW_GROUP_NAME"] == "Y"):?>

						<tr><td><br></td><td></td></tr>
						<tr>
							<td colspan="2"><?=$prop["GROUP_NAME"]?></td>
						</tr>

					<?php endif?>

					<tr>
						<td><?=$prop['NAME']?>:</td>
						<td>

							<?php if($prop["TYPE"] == "Y/N"):?>
								<?=GetMessage('SPOD_'.($prop["VALUE"] == "Y" ? 'YES' : 'NO'))?>
							<?php elseif ($prop["TYPE"] == "FILE"):?>
								<?=$prop["VALUE"]?>
							<?php else:?>
								<?=htmlspecialcharsbx($prop["VALUE"])?>
							<?php endif?>

						</td>
					</tr>

				<?php endforeach?>

				
				<?php if(!empty($arResult["USER_DESCRIPTION"])):?>

					<tr>
						<td><?=GetMessage('SPOD_ORDER_USER_COMMENT')?>:</td>
						<td><?=$arResult["USER_DESCRIPTION"]?></td>
					</tr>

				<?php endif?>

				<tr><td><br></td><td></td></tr>
			<?php endif?>

			<?php if($arParams["SHOW_ORDER_PAYMENT"]=='Y'):?>
				<tr>
					<td colspan="2"><?=GetMessage("SPOD_ORDER_PAYMENT")?></td>
				</tr>
				<tr><td><br></td><td></td></tr>
				<?php 
				foreach ($arResult["PAYMENT"] as $payment)
				{
					$titleParams = [
						"#ACCOUNT_NUMBER#" => htmlspecialcharsbx($payment['ACCOUNT_NUMBER']),
						"#DATE_BILL#" => $payment['DATE_BILL_FORMATTED'],
					];
					?>
					<tr>
						<td colspan="2"><?=GetMessage("SPOD_ORDER_PAYMENT_TITLE", $titleParams)?></td>
					</tr>
					<tr>
						<td><?=GetMessage('SPOD_PAY_SYSTEM')?>:</td>
						<td>
							<?php if(intval($payment["PAY_SYSTEM_ID"])):?>
								<?=htmlspecialcharsbx($payment["PAY_SYSTEM_NAME"])?>
							<?php else:?>
								<?=GetMessage("SPOD_NONE")?>
							<?php endif?>
						</td>
					</tr>
					<tr>
						<td><?=GetMessage('SPOD_ORDER_PAYED')?>:</td>
						<td>
							<?php if($payment["PAID"] == "Y"):?>
								<?=GetMessage('SPOD_YES')?>
								<?php if(strlen($payment["DATE_PAID_FORMATTED"])):?>
									(<?=GetMessage('SPOD_FROM')?> <?=$payment["DATE_PAID_FORMATTED"]?>)
								<?php endif?>
							<?php else:?>
								<?=GetMessage('SPOD_NO')?>
								<?php if($arResult["CAN_REPAY"]=="Y" && $arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y"):?>
									&nbsp;&nbsp;&nbsp;[<a href="<?=$arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]?>" target="_blank"><?=GetMessage("SPOD_REPEAT_PAY")?></a>]
								<?php endif?>
							<?php endif?>
						</td>
					</tr>
					<tr><td><br></td><td></td></tr>
					<?php 
				}
				?>
				<?php 
				foreach ($arResult["SHIPMENT"] as $shipment)
				{
					$titleParams = [
						"#ACCOUNT_NUMBER#" => htmlspecialcharsbx($shipment['ACCOUNT_NUMBER']),
						"#DELIVERY_PRICE#" => $shipment['PRICE_DELIVERY_FORMATTED'],
					];
					?>
					<tr>
						<td colspan="2"><?=GetMessage("SPOD_ORDER_SHIPMENT_TITLE", $titleParams)?></td>
					</tr>
					<tr>
						<td><?=GetMessage("SPOD_ORDER_DELIVERY")?>:</td>
						<td>
							<?php if (intval($shipment["DELIVERY_ID"]) > 0):?>
								<?=strlen($shipment["DELIVERY_NAME"]) ? htmlspecialcharsbx($shipment["DELIVERY_NAME"]) : GetMessage("SPOD_NONE")?>
								<?php if(isset($shipment['STORE_ID']) && !empty($arResult["DELIVERY"]["STORE_LIST"][(int)$shipment['STORE_ID']])):?>

									<?php $store = $arResult["DELIVERY"]["STORE_LIST"][$shipment['STORE_ID']];?>
									<div class="bx_ol_store">
										<div class="bx_old_s_row_title">
											<?=GetMessage('SPOD_TAKE_FROM_STORE')?>: <b><?=htmlspecialcharsbx($store['TITLE'])?></b>

											<?php if(!empty($store['DESCRIPTION'])):?>
												<div class="bx_ild_s_desc">
													<?=htmlspecialcharsbx($store['DESCRIPTION'])?>
												</div>
											<?php endif?>

										</div>

										<?php if(!empty($store['ADDRESS'])):?>
											<div class="bx_old_s_row">
												<b><?=GetMessage('SPOD_STORE_ADDRESS')?></b>: <?=htmlspecialcharsbx($store['ADDRESS'])?>
											</div>
										<?php endif?>

										<?php if(!empty($store['SCHEDULE'])):?>
											<div class="bx_old_s_row">
												<b><?=GetMessage('SPOD_STORE_WORKTIME')?></b>: <?=htmlspecialcharsbx($store['SCHEDULE'])?>
											</div>
										<?php endif?>

										<?php if(!empty($store['PHONE'])):?>
											<div class="bx_old_s_row">
												<b><?=GetMessage('SPOD_STORE_PHONE')?></b>: <?=htmlspecialcharsbx($store['PHONE'])?>
											</div>
										<?php endif?>

										<?php if(!empty($store['EMAIL'])):?>
											<div class="bx_old_s_row">
												<b><?=GetMessage('SPOD_STORE_EMAIL')?></b>: <a href="mailto:<?=htmlspecialcharsbx($store['EMAIL'])?>"><?=htmlspecialcharsbx($store['EMAIL'])?></a>
											</div>
										<?php endif?>
									</div>

								<?php endif?>
							<?php else:?>
								<?=GetMessage("SPOD_NONE")?>
							<?php endif?>
						</td>
					</tr>

					<?php if($shipment["TRACKING_NUMBER"]):?>
						<tr>
							<td><?=GetMessage('SPOD_ORDER_TRACKING_NUMBER')?>:</td>
							<td><?=htmlspecialcharsbx($shipment["TRACKING_NUMBER"])?></td>
						</tr>
					<?php endif?>
					<tr><td><br></td><td></td></tr>
					<?php 
				}
				?>
			<?php endif?>
			</tbody>
		</table>
			
			<?php if($arParams["SHOW_ORDER_BASKET"]=='Y'):?>
				<h3><?=GetMessage('SPOD_ORDER_BASKET')?></h3>
			<?php endif?>
		<?php endif?>

		
		
		<?php if($arParams["SHOW_ORDER_BASKET"]=='Y'):?>
		<table class="bx_order_list_table_order">
			<thead>
				<tr>			
					<?php 
					foreach ($arParams["CUSTOM_SELECT_PROPS"] as $headerId):						
						if($headerId == 'PICTURE' && in_array('NAME', $arParams["CUSTOM_SELECT_PROPS"]))
							continue;
							
						$colspan = "";
						if($headerId == 'NAME' && in_array('PICTURE', $arParams["CUSTOM_SELECT_PROPS"]))
							$colspan = 'colspan="2"';
						
						$headerName = GetMessage('SPOD_'.$headerId);
						if(strlen($headerName)<=0)
						{
							foreach(array_values($arResult['PROPERTY_DESCRIPTION']) as $prop_head_desc):
								if(array_key_exists($headerId, $prop_head_desc))
									$headerName = $prop_head_desc[$headerId]['NAME'];
							endforeach;
						}
						?><td <?=$colspan?>><?=$headerName?></td><?php 
					endforeach;
					?>
				</tr>
			</thead>
			<tbody>
				<?php //echo "<pre>".print_r($arParams['CUSTOM_SELECT_PROPS'], true).print_R($arResult["BASKET"], true)."</pre>"?>
				<?php 
				foreach($arResult["BASKET"] as $prod):
					?><tr><?php 
					
					$hasLink = !empty($prod["DETAIL_PAGE_URL"]);
					$actuallyHasProps = is_array($prod["PROPS"]) && !empty($prod["PROPS"]);
					
					foreach ($arParams["CUSTOM_SELECT_PROPS"] as $headerId):
						
						?><td class="custom"><?php 
						
						if($headerId == "NAME"):
							
							if($hasLink):
								?><a href="<?=$prod["DETAIL_PAGE_URL"]?>" target="_blank"><?php 
							endif;
							?><?=$prod["NAME"]?><?php 
							if($hasLink):
								?></a><?php 
							endif;
							
						elseif($headerId == "PICTURE"):
							
							if($hasLink):
								?><a href="<?=$prod["DETAIL_PAGE_URL"]?>" target="_blank"><?php 
							endif;
							if($prod['PICTURE']['SRC']):
								?><img src="<?=$prod['PICTURE']['SRC']?>" width="<?=$prod['PICTURE']['WIDTH']?>" height="<?=$prod['PICTURE']['HEIGHT']?>" alt="<?=$prod['NAME']?>" /><?php 
							endif;
							if($hasLink):
								?></a><?php 
							endif;
							
						elseif($headerId == "PROPS" && $arResult['HAS_PROPS'] && $actuallyHasProps):
							
							?>
							<table cellspacing="0" class="bx_ol_sku_prop">
								<?php foreach($prod["PROPS"] as $prop):?>
									<tr>
										<td><nobr><?=htmlspecialcharsbx($prop["NAME"])?>:</nobr></td>
										<td style="padding-left: 10px !important"><b><?=htmlspecialcharsbx($prop["VALUE"])?></b></td>
									</tr>
								<?php endforeach?>
							</table>
							<?php 

						elseif($headerId == "QUANTITY"):
						
							?>
							<?=$prod["QUANTITY"]?>
							<?php if(strlen($prod['MEASURE_TEXT'])):?>
								<?=$prod['MEASURE_TEXT']?>
							<?php else:?>
								<?=GetMessage('SPOD_DEFAULT_MEASURE')?>
							<?php endif?>
							<?php 
							
						else:
							$headerId = strtoupper($headerId);
							echo $prod[(strpos($headerId, 'PROPERTY_')===0 ? $headerId."_VALUE" : $headerId)];
						endif;
						
						?></td><?php 
						
					endforeach;
					
					?></tr><?php 
					
				endforeach;
				?>
			</tbody>
		</table>
		<br>
		<?php endif?>

		<?php if($arParams["SHOW_ORDER_SUM"]=='Y'):?>
		<table class="bx_ordercart_order_sum">
			<tbody>

				<?php  ///// WEIGHT ?>
				<?php if(floatval($arResult["ORDER_WEIGHT"])):?>
					<tr>
						<td class="custom_t1"><?=GetMessage('SPOD_TOTAL_WEIGHT')?>:</td>
						<td class="custom_t2"><?=$arResult['ORDER_WEIGHT_FORMATED']?></td>
					</tr>
				<?php endif?>

				<?php  ///// PRICE SUM ?>
				<tr>
					<td class="custom_t1"><?=GetMessage('SPOD_PRODUCT_SUM')?>:</td>
					<td class="custom_t2"><?=$arResult['PRODUCT_SUM_FORMATED']?></td>
				</tr>

				<?php  ///// DELIVERY PRICE: print even equals 2 zero ?>
				<?php if(strlen($arResult["PRICE_DELIVERY_FORMATED"])):?>
					<tr>
						<td class="custom_t1"><?=GetMessage('SPOD_DELIVERY')?>:</td>
						<td class="custom_t2"><?=$arResult["PRICE_DELIVERY_FORMATED"]?></td>
					</tr>
				<?php endif?>

				<?php  ///// TAXES DETAIL ?>
				<?php foreach($arResult["TAX_LIST"] as $tax):?>
					<tr>
						<td class="custom_t1"><?=$tax["TAX_NAME"]?>:</td>
						<td class="custom_t2"><?=$tax["VALUE_MONEY_FORMATED"]?></td>
					</tr>	
				<?php endforeach?>

				<?php  ///// TAX SUM ?>
				<?php if(floatval($arResult["TAX_VALUE"])):?>
					<tr>
						<td class="custom_t1"><?=GetMessage('SPOD_TAX')?>:</td>
						<td class="custom_t2"><?=$arResult["TAX_VALUE_FORMATED"]?></td>
					</tr>
				<?php endif?>

				<?php  ///// DISCOUNT ?>
				<?php if(floatval($arResult["DISCOUNT_VALUE"])):?>
					<tr>
						<td class="custom_t1"><?=GetMessage('SPOD_DISCOUNT')?>:</td>
						<td class="custom_t2"><?=$arResult["DISCOUNT_VALUE_FORMATED"]?></td>
					</tr>
				<?php endif?>

				<tr>
					<td class="custom_t1 fwb"><?=GetMessage('SPOD_SUMMARY')?>:</td>
					<td class="custom_t2 fwb"><?=$arResult["PRICE_FORMATED"]?></td>
				</tr>
			</tbody>
		</table>
		<?php endif?>
	<?php endif?>
</p>