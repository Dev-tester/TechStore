<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="catalog-element">
	<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<?php if(is_array($arResult["PREVIEW_PICTURE"]) && is_array($arResult["DETAIL_PICTURE"])):?>
				<img
					border="0"
					src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>"
					width="<?=$arResult["PREVIEW_PICTURE"]["WIDTH"]?>"
					height="<?=$arResult["PREVIEW_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arResult["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$arResult["PREVIEW_PICTURE"]["TITLE"]?>"
					id="image_<?=$arResult["PREVIEW_PICTURE"]["ID"]?>"
					style="display:block;cursor:pointer;cursor: hand;"
					OnClick="document.getElementById('image_<?=$arResult["PREVIEW_PICTURE"]["ID"]?>').style.display='none';document.getElementById('image_<?=$arResult["DETAIL_PICTURE"]["ID"]?>').style.display='block'"
					/>
				<img
					border="0"
					src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
					width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
					height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
					title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
					id="image_<?=$arResult["DETAIL_PICTURE"]["ID"]?>"
					style="display:none;cursor:pointer;cursor: hand;"
					OnClick="document.getElementById('image_<?=$arResult["DETAIL_PICTURE"]["ID"]?>').style.display='none';document.getElementById('image_<?=$arResult["PREVIEW_PICTURE"]["ID"]?>').style.display='block'"
					/>
			<?php elseif(is_array($arResult["DETAIL_PICTURE"])):?>
				<img
					border="0"
					src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
					width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
					height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
					title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
					/>
			<?php elseif(is_array($arResult["PREVIEW_PICTURE"])):?>
				<img
					border="0"
					src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>"
					width="<?=$arResult["PREVIEW_PICTURE"]["WIDTH"]?>"
					height="<?=$arResult["PREVIEW_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arResult["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$arResult["PREVIEW_PICTURE"]["TITLE"]?>"
					/>
			<?php endif?>
			<td width="100%" valign="top">
						<?php 
						$pub_date = '';
						if ($arResult["ACTIVE_FROM"])
							$pub_date = $arResult["ACTIVE_FROM"];
						elseif ($arResult["DATE_CREATE"])
							$pub_date = $arResult["DATE_CREATE"];

						if ($pub_date)
							echo '<b>'.GetMessage('PUB_DATE').'</b>&nbsp;'.$pub_date.'<br />';
						?>
				<?php foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
					<b><?=$arProperty["NAME"]?>:</b>&nbsp;<?php 
					if(is_array($arProperty["DISPLAY_VALUE"])):
						echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
					elseif($pid=="MANUAL"):
						?><a href="<?=$arProperty["VALUE"]?>"><?=GetMessage("CATALOG_DOWNLOAD")?></a><?php 
					else:
						echo $arProperty["DISPLAY_VALUE"];?>
					<?php endif?><br />
				<?php endforeach?>
			</td>
		</tr>
	</table>
		<?php foreach($arResult["PRICES"] as $code=>$arPrice):?>
			<?php if($arPrice["CAN_ACCESS"]):?>
				<p><?=$arResult["CAT_PRICES"][$code]["TITLE"];?>&nbsp;
				<?php if($arParams["PRICE_VAT_SHOW_VALUE"] && ($arPrice["VATRATE_VALUE"] > 0)):?>
					<?php if($arParams["PRICE_VAT_INCLUDE"]):?>
						(<?php echo GetMessage("CATALOG_PRICE_VAT")?>)
					<?php else:?>
						(<?php echo GetMessage("CATALOG_PRICE_NOVAT")?>)
					<?php endif?>
				<?php endif;?>:&nbsp;
				<?php if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
					<s><?=$arPrice["PRINT_VALUE"]?></s> <span class="catalog-price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></span>
					<?php if($arParams["PRICE_VAT_SHOW_VALUE"]):?><br />
						<?=GetMessage("CATALOG_VAT")?>:&nbsp;&nbsp;<span class="catalog-vat catalog-price"><?=$arPrice["DISCOUNT_VATRATE_VALUE"] > 0 ? $arPrice["PRINT_DISCOUNT_VATRATE_VALUE"] : GetMessage("CATALOG_NO_VAT")?></span>
					<?php endif;?>
				<?php else:?>
					<span class="catalog-price"><?=$arPrice["PRINT_VALUE"]?></span>
					<?php if($arParams["PRICE_VAT_SHOW_VALUE"]):?><br />
						<?=GetMessage("CATALOG_VAT")?>:&nbsp;&nbsp;<span class="catalog-vat catalog-price"><?=$arPrice["VATRATE_VALUE"] > 0 ? $arPrice["PRINT_VATRATE_VALUE"] : GetMessage("CATALOG_NO_VAT")?></span>
					<?php endif;?>
				<?php endif?>
				</p>
			<?php endif;?>
		<?php endforeach;?>
		<?php if(is_array($arResult["PRICE_MATRIX"])):?>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="data-table">
			<thead>
			<tr>
				<?php if(count($arResult["PRICE_MATRIX"]["ROWS"]) >= 1 && ($arResult["PRICE_MATRIX"]["ROWS"][0]["QUANTITY_FROM"] > 0 || $arResult["PRICE_MATRIX"]["ROWS"][0]["QUANTITY_TO"] > 0)):?>
					<td><?= GetMessage("CATALOG_QUANTITY") ?></td>
				<?php endif;?>
				<?php foreach($arResult["PRICE_MATRIX"]["COLS"] as $typeID => $arType):?>
					<td><?= $arType["NAME_LANG"] ?></td>
				<?php endforeach?>
			</tr>
			</thead>
			<?php foreach ($arResult["PRICE_MATRIX"]["ROWS"] as $ind => $arQuantity):?>
			<tr>
				<?php if(count($arResult["PRICE_MATRIX"]["ROWS"]) > 1 || count($arResult["PRICE_MATRIX"]["ROWS"]) == 1 && ($arResult["PRICE_MATRIX"]["ROWS"][0]["QUANTITY_FROM"] > 0 || $arResult["PRICE_MATRIX"]["ROWS"][0]["QUANTITY_TO"] > 0)):?>
					<th nowrap>
						<?php if(IntVal($arQuantity["QUANTITY_FROM"]) > 0 && IntVal($arQuantity["QUANTITY_TO"]) > 0)
							echo str_replace("#FROM#", $arQuantity["QUANTITY_FROM"], str_replace("#TO#", $arQuantity["QUANTITY_TO"], GetMessage("CATALOG_QUANTITY_FROM_TO")));
						elseif(IntVal($arQuantity["QUANTITY_FROM"]) > 0)
							echo str_replace("#FROM#", $arQuantity["QUANTITY_FROM"], GetMessage("CATALOG_QUANTITY_FROM"));
						elseif(IntVal($arQuantity["QUANTITY_TO"]) > 0)
							echo str_replace("#TO#", $arQuantity["QUANTITY_TO"], GetMessage("CATALOG_QUANTITY_TO"));
						?>
					</th>
				<?php endif;?>
				<?php foreach($arResult["PRICE_MATRIX"]["COLS"] as $typeID => $arType):?>
					<td>
						<?php if($arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["DISCOUNT_PRICE"] < $arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"])
							echo '<s>'.FormatCurrency($arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"], $arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"]).'</s> <span class="catalog-price">'.FormatCurrency($arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["DISCOUNT_PRICE"], $arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"])."</span>";
						else
							echo '<span class="catalog-price">'.FormatCurrency($arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"], $arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"])."</span>";
						?>
					</td>
				<?php endforeach?>
			</tr>
			<?php endforeach?>
			</table>
			<?php if($arParams["PRICE_VAT_SHOW_VALUE"]):?>
				<?php if($arParams["PRICE_VAT_INCLUDE"]):?>
					<small><?=GetMessage('CATALOG_VAT_INCLUDED')?></small>
				<?php else:?>
					<small><?=GetMessage('CATALOG_VAT_NOT_INCLUDED')?></small>
				<?php endif?>
			<?php endif;?><br />
		<?php endif?>
		<?php if($arResult["CAN_BUY"]):?>
			<?php if($arParams["USE_PRODUCT_QUANTITY"] || count($arResult["PRODUCT_PROPERTIES"])):?>
				<form action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
				<table border="0" cellspacing="0" cellpadding="2">
				<?php if($arParams["USE_PRODUCT_QUANTITY"]):?>
					<tr valign="top">
						<td><?php echo GetMessage("CT_BCE_QUANTITY")?>:</td>
						<td>
							<input type="text" name="<?php echo $arParams["PRODUCT_QUANTITY_VARIABLE"]?>" value="1" size="5">
						</td>
					</tr>
				<?php endif;?>
				<?php foreach($arResult["PRODUCT_PROPERTIES"] as $pid => $product_property):?>
					<tr valign="top">
						<td><?php echo $arResult["PROPERTIES"][$pid]["NAME"]?>:</td>
						<td>
						<?php if(
							$arResult["PROPERTIES"][$pid]["PROPERTY_TYPE"] == "L"
							&& $arResult["PROPERTIES"][$pid]["LIST_TYPE"] == "C"
						):?>
							<?php foreach($product_property["VALUES"] as $k => $v):?>
								<label><input type="radio" name="<?php echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?php echo $pid?>]" value="<?php echo $k?>" <?php if($k == $product_property["SELECTED"]) echo '"checked"'?>><?php echo $v?></label><br>
							<?php endforeach;?>
						<?php else:?>
							<select name="<?php echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?php echo $pid?>]">
								<?php foreach($product_property["VALUES"] as $k => $v):?>
									<option value="<?php echo $k?>" <?php if($k == $product_property["SELECTED"]) echo '"selected"'?>><?php echo $v?></option>
								<?php endforeach;?>
							</select>
						<?php endif;?>
						</td>
					</tr>
				<?php endforeach;?>
				</table>
				<input type="hidden" name="<?php echo $arParams["ACTION_VARIABLE"]?>" value="BUY">
				<input type="hidden" name="<?php echo $arParams["PRODUCT_ID_VARIABLE"]?>" value="<?php echo $arResult["ID"]?>">
				<input type="submit" name="<?php echo $arParams["ACTION_VARIABLE"]."BUY"?>" value="<?php echo GetMessage("CATALOG_BUY")?>">
				<input type="submit" name="<?php echo $arParams["ACTION_VARIABLE"]."ADD2BASKET"?>" value="<?php echo GetMessage("CATALOG_ADD_TO_BASKET")?>">
				</form>
			<?php else:?>
				<noindex>
				<a href="<?php echo $arResult["BUY_URL"]?>" rel="nofollow"><?php echo GetMessage("CATALOG_BUY")?></a>
				&nbsp;<a href="<?php echo $arResult["ADD_URL"]?>" rel="nofollow"><?php echo GetMessage("CATALOG_ADD_TO_BASKET")?></a>
				</noindex>
			<?php endif;?>
		<?php elseif((count($arResult["PRICES"]) > 0) || is_array($arResult["PRICE_MATRIX"])):?>
			<?=GetMessage("CATALOG_NOT_AVAILABLE")?>
			<?php $APPLICATION->IncludeComponent("bitrix:sale.notice.product", ".default", array(
				"NOTIFY_ID" => $arResult['ID'],
				"NOTIFY_URL" => htmlspecialcharsback($arResult["SUBSCRIBE_URL"]),
				"NOTIFY_USE_CAPTHA" => "N"
				),
				$component
			);?>
		<?php endif?>
		<br />
	<?php if($arResult["PREVIEW_TEXT"]):?>
		<br /><?=$arResult["PREVIEW_TEXT"]?><br />
	<?php elseif($arResult["DETAIL_TEXT"]):?>
		<br /><?=$arResult["DETAIL_TEXT"]?><br />
	<?php endif;?>
	<?php if(count($arResult["LINKED_ELEMENTS"])>0):?>
		<br /><b><?=$arResult["LINKED_ELEMENTS"][0]["IBLOCK_NAME"]?>:</b>
		<ul>
	<?php foreach($arResult["LINKED_ELEMENTS"] as $arElement):?>
		<li><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a></li>
	<?php endforeach;?>
		</ul>
	<?php endif?>
	<?php 
	// additional photos
	$LINE_ELEMENT_COUNT = 2; // number of elements in a row
	if(count($arResult["MORE_PHOTO"])>0):?>
		<a name="more_photo"></a>
		<?php foreach($arResult["MORE_PHOTO"] as $PHOTO):?>
			<img border="0" src="<?=$PHOTO["SRC"]?>" width="<?=$PHOTO["WIDTH"]?>" height="<?=$PHOTO["HEIGHT"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" /><br />
		<?php endforeach?>
	<?php endif?>
	<?php if(is_array($arResult["SECTION"])):?>
		<br /><a href="<?=$arResult["SECTION"]["SECTION_PAGE_URL"]?>"><?=GetMessage("CATALOG_BACK")?></a>
	<?php endif?>
</div>
