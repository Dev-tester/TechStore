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
<div class="catalog-section">
<?php $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "tree", Array(
	"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
	"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
	"SECTION_ID"	=>	"0",
	"COUNT_ELEMENTS"	=>	"Y",
	"TOP_DEPTH"	=>	"2",
	"SECTION_URL"	=>	$arParams["SECTION_URL"],
	"CACHE_TYPE"	=>	"N",
	"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
	"DISPLAY_PANEL"	=>	"N",
	"ADD_SECTIONS_CHAIN"	=>	$arParams["ADD_SECTIONS_CHAIN"],
	"SECTION_USER_FIELDS"	=>	$arParams["SECTION_USER_FIELDS"],
	),
	$component
);?>
</div>
<div class="catalog-section">
<?php if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?php endif;?>
<table cellpadding="8" cellspacing="8" border="0" width="100%">
		<?php foreach($arResult["ITEMS"] as $cell=>$arElement):?>
		<?php 
		$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
		?>
		<?php if($cell%$arParams["LINE_ELEMENT_COUNT"] == 0):?>
		<tr>
		<?php endif;?>

		<td valign="top" width="<?=round(100/$arParams["LINE_ELEMENT_COUNT"])?>%" style="border:1px solid #CCCCCC" id="<?=$this->GetEditAreaId($arElement['ID']);?>">
			<table cellpadding="0" cellspacing="2" border="0">
				<tr>
					<?php if(is_array($arElement["PREVIEW_PICTURE"])):?>
						<td valign="top">
						<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img
								border="0"
								src="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>"
								width="<?=$arElement["PREVIEW_PICTURE"]["WIDTH"]?>"
								height="<?=$arElement["PREVIEW_PICTURE"]["HEIGHT"]?>"
								alt="<?=$arElement["PREVIEW_PICTURE"]["ALT"]?>"
								title="<?=$arElement["PREVIEW_PICTURE"]["TITLE"]?>"
								/></a><br />
						</td>
					<?php elseif(is_array($arElement["DETAIL_PICTURE"])):?>
						<td valign="top">
						<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img
								border="0"
								src="<?=$arElement["DETAIL_PICTURE"]["SRC"]?>"
								width="<?=$arElement["DETAIL_PICTURE"]["WIDTH"]?>"
								height="<?=$arElement["DETAIL_PICTURE"]["HEIGHT"]?>"
								alt="<?=$arElement["DETAIL_PICTURE"]["ALT"]?>"
								title="<?=$arElement["DETAIL_PICTURE"]["TITLE"]?>"
								/></a><br />
						</td>
					<?php endif?>
					<td valign="top"><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><b><?=$arElement["NAME"]?></b></a><br /><br />
						<?php 
						$pub_date = '';
						if ($arElement["ACTIVE_FROM"])
							$pub_date = FormatDate($GLOBALS['DB']->DateFormatToPhp(CSite::GetDateFormat('FULL')), MakeTimeStamp($arElement["ACTIVE_FROM"]));
						elseif ($arElement["DATE_CREATE"])
							$pub_date =  FormatDate($GLOBALS['DB']->DateFormatToPhp(CSite::GetDateFormat('FULL')), MakeTimeStamp($arElement["DATE_CREATE"]));

						if ($pub_date)
							echo '<b>'.GetMessage('PUB_DATE').'</b>&nbsp;'.$pub_date.'<br />';
						?>
						<?php foreach($arElement["DISPLAY_PROPERTIES"] as $pid=>$arProperty):
							echo '<b>'.$arProperty["NAME"].':</b>&nbsp;';

							if(is_array($arProperty["DISPLAY_VALUE"]))
								echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
							else
								echo $arProperty["DISPLAY_VALUE"];
							?><br />
						<?php endforeach?>
						<br />
						<?=$arElement["PREVIEW_TEXT"]?>
					</td>
				</tr>
			</table>

			<?php foreach($arElement["PRICES"] as $code=>$arPrice):?>
				<?php if($arPrice["CAN_ACCESS"]):?>
					<p><?=$arResult["PRICES"][$code]["TITLE"];?>:&nbsp;&nbsp;
					<?php if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
						<s><?=$arPrice["PRINT_VALUE"]?></s> <span class="catalog-price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></span>
					<?php else:?><span class="catalog-price"><?=$arPrice["PRINT_VALUE"]?></span><?php endif;?>
					</p>
				<?php endif;?>
			<?php endforeach;?>
			<?php if(is_array($arElement["PRICE_MATRIX"])):?>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" class="data-table">
				<thead>
				<tr>
					<?php if(count($arElement["PRICE_MATRIX"]["ROWS"]) >= 1 && ($arElement["PRICE_MATRIX"]["ROWS"][0]["QUANTITY_FROM"] > 0 || $arElement["PRICE_MATRIX"]["ROWS"][0]["QUANTITY_TO"] > 0)):?>
						<td valign="top" nowrap><?= GetMessage("CATALOG_QUANTITY") ?></td>
					<?php endif?>
					<?php foreach($arElement["PRICE_MATRIX"]["COLS"] as $typeID => $arType):?>
						<td valign="top" nowrap><?= $arType["NAME_LANG"] ?></td>
					<?php endforeach?>
				</tr>
				</thead>
				<?php foreach ($arElement["PRICE_MATRIX"]["ROWS"] as $ind => $arQuantity):?>
				<tr>
					<?php if(count($arElement["PRICE_MATRIX"]["ROWS"]) > 1 || count($arElement["PRICE_MATRIX"]["ROWS"]) == 1 && ($arElement["PRICE_MATRIX"]["ROWS"][0]["QUANTITY_FROM"] > 0 || $arElement["PRICE_MATRIX"]["ROWS"][0]["QUANTITY_TO"] > 0)):?>
						<th nowrap><?php 
							if (IntVal($arQuantity["QUANTITY_FROM"]) > 0 && IntVal($arQuantity["QUANTITY_TO"]) > 0)
								echo str_replace("#FROM#", $arQuantity["QUANTITY_FROM"], str_replace("#TO#", $arQuantity["QUANTITY_TO"], GetMessage("CATALOG_QUANTITY_FROM_TO")));
							elseif (IntVal($arQuantity["QUANTITY_FROM"]) > 0)
								echo str_replace("#FROM#", $arQuantity["QUANTITY_FROM"], GetMessage("CATALOG_QUANTITY_FROM"));
							elseif (IntVal($arQuantity["QUANTITY_TO"]) > 0)
								echo str_replace("#TO#", $arQuantity["QUANTITY_TO"], GetMessage("CATALOG_QUANTITY_TO"));
						?></th>
					<?php endif?>
					<?php foreach($arElement["PRICE_MATRIX"]["COLS"] as $typeID => $arType):?>
						<td><?php 
							if($arElement["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["DISCOUNT_PRICE"] < $arElement["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"]):?>
								<s><?=FormatCurrency($arElement["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"], $arElement["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"])?></s><span class="catalog-price"><?=FormatCurrency($arElement["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["DISCOUNT_PRICE"], $arElement["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"]);?></span>
							<?php else:?>
								<span class="catalog-price"><?=FormatCurrency($arElement["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"], $arElement["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"]);?></span>
							<?php endif?>&nbsp;
						</td>
					<?php endforeach?>
				</tr>
				<?php endforeach?>
				</table><br />
			<?php endif?>
			<?php if($arParams["DISPLAY_COMPARE"]):?>
				<noindex>
				<a href="<?php echo $arElement["COMPARE_URL"]?>" rel="nofollow"><?php echo GetMessage("CATALOG_COMPARE")?></a>&nbsp;
				</noindex>
			<?php endif?>
			<?php if($arElement["CAN_BUY"]):?>
				<?php if($arParams["USE_PRODUCT_QUANTITY"] || count($arElement["PRODUCT_PROPERTIES"])):?>
					<form action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
					<table border="0" cellspacing="0" cellpadding="2">
					<?php if($arParams["USE_PRODUCT_QUANTITY"]):?>
						<tr valign="top">
							<td><?php echo GetMessage("CT_BCS_QUANTITY")?>:</td>
							<td>
								<input type="text" name="<?php echo $arParams["PRODUCT_QUANTITY_VARIABLE"]?>" value="1" size="5">
							</td>
						</tr>
					<?php endif;?>
					<?php foreach($arElement["PRODUCT_PROPERTIES"] as $pid => $product_property):?>
						<tr valign="top">
							<td><?php echo $arElement["PROPERTIES"][$pid]["NAME"]?>:</td>
							<td>
							<?php if(
								$arElement["PROPERTIES"][$pid]["PROPERTY_TYPE"] == "L"
								&& $arElement["PROPERTIES"][$pid]["LIST_TYPE"] == "C"
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
					<input type="hidden" name="<?php echo $arParams["PRODUCT_ID_VARIABLE"]?>" value="<?php echo $arElement["ID"]?>">
					<input type="submit" name="<?php echo $arParams["ACTION_VARIABLE"]."BUY"?>" value="<?php echo GetMessage("CATALOG_BUY")?>">
					<input type="submit" name="<?php echo $arParams["ACTION_VARIABLE"]."ADD2BASKET"?>" value="<?php echo GetMessage("CATALOG_ADD")?>">
					</form>
				<?php else:?>
					<noindex>
					<a href="<?php echo $arElement["BUY_URL"]?>" rel="nofollow"><?php echo GetMessage("CATALOG_BUY")?></a>&nbsp;<a href="<?php echo $arElement["ADD_URL"]?>" rel="nofollow"><?php echo GetMessage("CATALOG_ADD")?></a>
					</noindex>
				<?php endif?>
			<?php elseif((count($arResult["PRICES"]) > 0) || is_array($arElement["PRICE_MATRIX"])):?>
				<?=GetMessage("CATALOG_NOT_AVAILABLE")?>
				<?php $APPLICATION->IncludeComponent("bitrix:sale.notice.product", ".default", array(
							"NOTIFY_ID" => $arElement['ID'],
							"NOTIFY_URL" => htmlspecialcharsback($arElement["SUBSCRIBE_URL"]),
							"NOTIFY_USE_CAPTHA" => "N"
							),
							$component
						);?>
			<?php endif?>
			&nbsp;
		</td>

		<?php $cell++;
		if($cell%$arParams["LINE_ELEMENT_COUNT"] == 0):?>
			</tr>
		<?php endif?>

		<?php endforeach; // foreach($arResult["ITEMS"] as $arElement):?>

		<?php if($cell%$arParams["LINE_ELEMENT_COUNT"] != 0):?>
			<?php while(($cell++)%$arParams["LINE_ELEMENT_COUNT"] != 0):?>
				<td>&nbsp;</td>
			<?php endwhile;?>
			</tr>
		<?php endif?>

</table>
<?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?php endif;?>
</div>
