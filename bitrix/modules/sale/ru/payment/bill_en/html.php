<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?php 
$ORDER_ID = IntVal($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"]);
if (!is_array($arOrder))
	$arOrder = CSaleOrder::GetByID($ORDER_ID);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Счет</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET?>">
<style>
	table { border-collapse: collapse; }
	table.it td { border: 1pt solid #000000; padding: 0pt 3pt; }
	table.inv td, table.sign td { padding: 0pt; }
	table.sign td { vertical-align: top; }
</style>
</head>

<?php 

$pageWidth  = 595.28;
$pageHeight = 841.89;

$background = '#ffffff';
if (CSalePaySystemAction::GetParamValue('BACKGROUND'))
{
	$path = CSalePaySystemAction::GetParamValue('BACKGROUND');
	if (intval($path) > 0)
	{
		if ($arFile = CFile::GetFileArray($path))
			$path = $arFile['SRC'];
	}

	$backgroundStyle = CSalePaySystemAction::GetParamValue('BACKGROUND_STYLE');
	if (!in_array($backgroundStyle, array('none', 'tile', 'stretch')))
		$backgroundStyle = 'none';

	if ($path)
	{
		switch ($backgroundStyle)
		{
			case 'none':
				$background = "url('" . $path . "') 0 0 no-repeat";
				break;
			case 'tile':
				$background = "url('" . $path . "') 0 0 repeat";
				break;
			case 'stretch':
				$background = sprintf(
					"url('%s') 0 0 repeat-y; background-size: %.02fpt %.02fpt",
					$path, $pageWidth, $pageHeight
				);
				break;
		}
	}
}

$margin = array(
	'top' => intval(CSalePaySystemAction::GetParamValue('MARGIN_TOP') ?: 15) * 72/25.4,
	'right' => intval(CSalePaySystemAction::GetParamValue('MARGIN_RIGHT') ?: 15) * 72/25.4,
	'bottom' => intval(CSalePaySystemAction::GetParamValue('MARGIN_BOTTOM') ?: 15) * 72/25.4,
	'left' => intval(CSalePaySystemAction::GetParamValue('MARGIN_LEFT') ?: 20) * 72/25.4
);

$width = $pageWidth - $margin['left'] - $margin['right'];

?>

<body
	style="margin: 0pt; padding: <?=join('pt ', $margin); ?>pt; width: <?=$width; ?>pt; background: <?=$background; ?>"
	<?php  if ($_REQUEST['PRINT'] == 'Y') { ?>
	onload="setTimeout(window.print, 0);"
	<?php  } ?>
>

<?=CFile::ShowImage(
	CSalePaySystemAction::GetParamValue("PATH_TO_LOGO"),
	0, 0,
	'style="float: left; padding-right: 5pt; "'
); ?>

<div style="float: left; ">
	<b><?=CSalePaySystemAction::GetParamValue("SELLER_NAME"); ?></b><br>
	<?php  if (CSalePaySystemAction::GetParamValue("SELLER_ADDRESS")) { ?>
	<b><?=CSalePaySystemAction::GetParamValue("SELLER_ADDRESS"); ?></b><br>
	<?php  } ?>
	<?php  if (CSalePaySystemAction::GetParamValue("SELLER_PHONE")) { ?>
	<b><?=sprintf("Tel.: %s", CSalePaySystemAction::GetParamValue("SELLER_PHONE")); ?></b><br>
	<?php  } ?>
	<br>
</div>
<div style="clear: both; height: 5pt; "></div>

<br>

<div style="text-align: center; font-size: 2em"><b>Invoice</b></div>

<br>
<br>

<table width="100%">
	<tr>
		<?php  if (CSalePaySystemAction::GetParamValue("BUYER_NAME")) { ?>
		<td>
			<b>To</b><br>
			<?=CSalePaySystemAction::GetParamValue("BUYER_NAME"); ?><br>
			<?php  if (CSalePaySystemAction::GetParamValue("BUYER_ADDRESS")) { ?>
			<?=CSalePaySystemAction::GetParamValue("BUYER_ADDRESS"); ?>
			<?php  } ?>
		</td>
		<?php  } ?>
		<td align="right">
			<table class="inv">
				<tr align="right">
					<td><b>Invoice #&nbsp;</b></td>
					<td><?=htmlspecialcharsbx($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ACCOUNT_NUMBER"]); ?></td>
				</tr>
				<tr align="right">
					<td><b>Issue Date:&nbsp;</b></td>
					<td><?=CSalePaySystemAction::GetParamValue("DATE_INSERT"); ?></td>
				</tr>
				<?php  if (CSalePaySystemAction::GetParamValue("DATE_PAY_BEFORE")) { ?>
				<tr align="right">
					<td><b>Due Date:&nbsp;</b></td>
					<td><?=ConvertDateTime(CSalePaySystemAction::GetParamValue("DATE_PAY_BEFORE"), FORMAT_DATE); ?></td>
				</tr>
				<?php  } ?>
			</table>
		</td>
	</tr>
</table>

<br>
<br>
<br>

<?php 

$dbBasket = CSaleBasket::GetList(
	array("NAME" => "ASC"),
	array("ORDER_ID" => $ORDER_ID)
);
if ($arBasket = $dbBasket->Fetch())
{
	$arCells = array();
	$arProps = array();

	$n = 0;
	$sum = 0.00;
	$vat = 0;
	$vats = array();
	do
	{
		// props in busket product
		$arProdProps = array();
		$dbBasketProps = CSaleBasket::GetPropsList(
			array("SORT" => "ASC", "ID" => "DESC"),
			array(
				"BASKET_ID" => $arBasket["ID"],
				"!CODE" => array("CATALOG.XML_ID", "PRODUCT.XML_ID")
			),
			false,
			false,
			array("ID", "BASKET_ID", "NAME", "VALUE", "CODE", "SORT")
		);
		while ($arBasketProps = $dbBasketProps->GetNext())
		{
			if (!empty($arBasketProps) && $arBasketProps["VALUE"] != "")
				$arProdProps[] = $arBasketProps;
		}
		$arBasket["PROPS"] = $arProdProps;

		// @TODO: replace with real vatless price
		$arBasket["VATLESS_PRICE"] = roundEx($arBasket["PRICE"] / (1 + $arBasket["VAT_RATE"]), SALE_VALUE_PRECISION);

		$arCells[++$n] = array(
			1 => $n,
			htmlspecialcharsbx($arBasket["NAME"]),
			roundEx($arBasket["QUANTITY"], SALE_VALUE_PRECISION),
			'pcs',
			SaleFormatCurrency($arBasket["VATLESS_PRICE"], $arBasket["CURRENCY"], true),
			roundEx($arBasket["VAT_RATE"]*100, SALE_VALUE_PRECISION) . "%",
			SaleFormatCurrency(
				$arBasket["VATLESS_PRICE"] * $arBasket["QUANTITY"],
				$arBasket["CURRENCY"],
				true
			)
		);

		$arProps[$n] = array();
		foreach ($arBasket["PROPS"] as $vv)
			$arProps[$n][] = htmlspecialcharsbx(sprintf("%s: %s", $vv["NAME"], $vv["VALUE"]));

		$sum += doubleval($arBasket["VATLESS_PRICE"] * $arBasket["QUANTITY"]);
		$vat = max($vat, $arBasket["VAT_RATE"]);
		if ($arBasket["VAT_RATE"] > 0)
		{
			if (!isset($vats[$arBasket["VAT_RATE"]]))
				$vats[$arBasket["VAT_RATE"]] = 0;
			$vats[$arBasket["VAT_RATE"]] += ($arBasket["PRICE"] - $arBasket["VATLESS_PRICE"]) * $arBasket["QUANTITY"];
		}
	}
	while ($arBasket = $dbBasket->Fetch());

	if (DoubleVal($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["PRICE_DELIVERY"]) > 0)
	{
		$arDelivery_tmp = CSaleDelivery::GetByID($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["DELIVERY_ID"]);

		$sDeliveryItem = "Shipping";
		if (strlen($arDelivery_tmp["NAME"]) > 0)
			$sDeliveryItem .= sprintf(" (%s)", $arDelivery_tmp["NAME"]);
		$arCells[++$n] = array(
			1 => $n,
			htmlspecialcharsbx($sDeliveryItem),
			1,
			'',
			SaleFormatCurrency(
				$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["PRICE_DELIVERY"] / (1 + $vat),
				$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["CURRENCY"],
				true
			),
			roundEx($vat*100, SALE_VALUE_PRECISION) . "%",
			SaleFormatCurrency(
				$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["PRICE_DELIVERY"] / (1 + $vat),
				$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["CURRENCY"],
				true
			)
		);

		$sum += roundEx(
			doubleval($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["PRICE_DELIVERY"] / (1 + $vat)),
			SALE_VALUE_PRECISION
		);

		if ($vat > 0)
			$vats[$vat] += roundEx(
				$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["PRICE_DELIVERY"] * $vat / (1 + $vat),
				SALE_VALUE_PRECISION
			);
	}

	$items = $n;

	if ($sum < $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["PRICE"])
	{
		$arCells[++$n] = array(
			1 => null,
			null,
			null,
			null,
			null,
			"Subtotal:",
			SaleFormatCurrency($sum, $GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["CURRENCY"], true)
		);
	}

	if (!empty($vats))
	{
		// @TODO: remove on real vatless price implemented
		$delta = intval(roundEx(
			$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["PRICE"] - $sum - array_sum($vats),
			SALE_VALUE_PRECISION
		) * pow(10, SALE_VALUE_PRECISION));
		if ($delta)
		{
			$vatRates = array_keys($vats);
			rsort($vatRates);

			while (abs($delta) > 0)
			{
				foreach ($vatRates as $vatRate)
				{
					$vats[$vatRate] += abs($delta)/$delta / pow(10, SALE_VALUE_PRECISION);
					$delta -= abs($delta)/$delta;

					if ($delta == 0)
						break 2;
				}
			}
		}

		foreach ($vats as $vatRate => $vatSum)
		{
			$arCells[++$n] = array(
				1 => null,
				null,
				null,
				null,
				null,
				sprintf(
					"Sales Tax (%s%%):",
					roundEx($vatRate * 100, SALE_VALUE_PRECISION)
				),
				SaleFormatCurrency(
					$vatSum,
					$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["CURRENCY"],
					true
				)
			);
		}
	}
	else
	{
		$dbTaxList = CSaleOrderTax::GetList(
			array("APPLY_ORDER" => "ASC"),
			array("ORDER_ID" => $ORDER_ID)
		);

		while ($arTaxList = $dbTaxList->Fetch())
		{
			$arCells[++$n] = array(
				1 => null,
				null,
				null,
				null,
				null,
				htmlspecialcharsbx(sprintf(
					"%s%s%s:",
					($arTaxList["IS_IN_PRICE"] == "Y") ? "Included " : "",
					$arTaxList["TAX_NAME"],
					sprintf(' (%s%%)', roundEx($arTaxList["VALUE"],SALE_VALUE_PRECISION))
				)),
				SaleFormatCurrency(
					$arTaxList["VALUE_MONEY"],
					$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["CURRENCY"],
					true
				)
			);
		}
	}

	if (DoubleVal($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["SUM_PAID"]) > 0)
	{
		$arCells[++$n] = array(
			1 => null,
			null,
			null,
			null,
			null,
			"Payment made:",
			SaleFormatCurrency(
				$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["SUM_PAID"],
				$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["CURRENCY"],
				true
			)
		);
	}

	if (DoubleVal($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["DISCOUNT_VALUE"]) > 0)
	{
		$arCells[++$n] = array(
			1 => null,
			null,
			null,
			null,
			null,
			"Discount:",
			SaleFormatCurrency(
				$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["DISCOUNT_VALUE"],
				$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["CURRENCY"],
				true
			)
		);
	}

	$arCells[++$n] = array(
		1 => null,
		null,
		null,
		null,
		null,
		"Total:",
		SaleFormatCurrency(
			$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["SHOULD_PAY"],
			$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["CURRENCY"],
			true
		)
	);
}

?>
<table class="it" width="100%">
	<tr>
		<td><nobr>#</nobr></td>
		<td><nobr>Item / Description</nobr></td>
		<td><nobr>Qty</nobr></td>
		<td><nobr>Units</nobr></td>
		<td><nobr>Unit Price</nobr></td>
		<?php  if ($vat > 0) { ?>
		<td><nobr>Tax</nobr></td>
		<?php  } ?>
		<td><nobr>Total</nobr></td>
	</tr>
<?php 

$rowsCnt = count($arCells);
for ($n = 1; $n <= $rowsCnt; $n++)
{
	$accumulated = 0;

?>
	<tr valign="top">
		<?php  if (!is_null($arCells[$n][1])) { ?>
		<td align="center"><?=$arCells[$n][1]; ?></td>
		<?php  } else {
			$accumulated++;
		} ?>
		<?php  if (!is_null($arCells[$n][2])) { ?>
		<td align="left"
			<?php  if ($accumulated) {
				?> style="border-width: 0pt 1pt 0pt 0pt" colspan="<?=($accumulated+1); ?>"<?php  $accumulated = 0;
			} ?>>
			<?=$arCells[$n][2]; ?>
			<?php  if (isset($arProps[$n]) && is_array($arProps[$n])) { ?>
			<?php  foreach ($arProps[$n] as $property) { ?>
			<br>
			<small><?=$property; ?></small>
			<?php  } ?>
			<?php  } ?>
		</td>
		<?php  } else {
			$accumulated++;
		} ?>
		<?php  for ($i = 3; $i <= 7; $i++) { ?>
			<?php  if (!is_null($arCells[$n][$i])) { ?>
				<?php  if ($i != 6 || $vat > 0 || is_null($arCells[$n][2])) { ?>
				<td align="right"
					<?php  if ($accumulated) { ?>
					style="border-width: 0pt 1pt 0pt 0pt"
					colspan="<?=(($i == 6 && $vat <= 0) ? $accumulated : $accumulated+1); ?>"
					<?php  $accumulated = 0; } ?>>
					<nobr><?=$arCells[$n][$i]; ?></nobr>
				</td>
				<?php  }
			} else {
				$accumulated++;
			}
		} ?>
	</tr>
<?php 

}

?>
</table>
<br>
<br>
<br>
<br>

<?php  if (CSalePaySystemAction::GetParamValue("COMMENT1") || CSalePaySystemAction::GetParamValue("COMMENT2")) { ?>
<b>Terms & Conditions</b>
<br>
	<?php  if (CSalePaySystemAction::GetParamValue("COMMENT1")) { ?>
	<?=nl2br(HTMLToTxt(preg_replace(
		array('#</div>\s*<div[^>]*>#i', '#</?div>#i'), array('<br>', '<br>'),
		htmlspecialcharsback(CSalePaySystemAction::GetParamValue("COMMENT1"))
	), '', array(), 0)); ?>
	<br>
	<br>
	<?php  } ?>
	<?php  if (CSalePaySystemAction::GetParamValue("COMMENT2")) { ?>
	<?=nl2br(HTMLToTxt(preg_replace(
		array('#</div>\s*<div[^>]*>#i', '#</?div>#i'), array('<br>', '<br>'),
		htmlspecialcharsback(CSalePaySystemAction::GetParamValue("COMMENT2"))
	), '', array(), 0)); ?>
	<br>
	<br>
	<?php  } ?>
<?php  } ?>

<br>
<br>
<br>

<?php  $bankAccNo = CSalePaySystemAction::GetParamValue("SELLER_BANK_ACCNO"); ?>
<?php  $bankRouteNo = CSalePaySystemAction::GetParamValue("SELLER_BANK_ROUTENO"); ?>
<?php  $bankSwift = CSalePaySystemAction::GetParamValue("SELLER_BANK_SWIFT"); ?>

<table class="sign" style="width: 100%; ">
	<tr>
		<td style="width: 50%; ">

		<?php  if ($bankAccNo && $bankRouteNo && $bankSwift) { ?>

			<b>Bank Details</b>
			<br>

			<?php  if (CSalePaySystemAction::GetParamValue("SELLER_NAME")) { ?>
				Account Name: <?=CSalePaySystemAction::GetParamValue("SELLER_NAME"); ?>
				<br>
			<?php  } ?>

			Account #: <?=$bankAccNo; ?>
			<br>

			<?php  $bank = CSalePaySystemAction::GetParamValue("SELLER_BANK"); ?>
			<?php  $bankAddr = CSalePaySystemAction::GetParamValue("SELLER_BANK_ADDR"); ?>
			<?php  $bankPhone = CSalePaySystemAction::GetParamValue("SELLER_BANK_PHONE"); ?>

			<?php  if ($bank || $bankAddr || $bankPhone) { ?>
				Bank Name and Address: <?php  if ($bank) { ?><?=$bank; ?><?php  } ?>
				<br>

				<?php  if ($bankAddr) { ?>
					<?=$bankAddr; ?>
					<br>
				<?php  } ?>

				<?php  if ($bankPhone) { ?>
					<?=$bankPhone; ?>
					<br>
				<?php  } ?>
			<?php  } ?>

			Bank's routing number: <?=$bankRouteNo; ?>
			<br>

			Bank SWIFT: <?=$bankSwift; ?>
			<br>
		<?php  } ?>

		</td>
		<td style="width: 50%; ">

			<div style="position: relative; "><?=CFile::ShowImage(
				CSalePaySystemAction::GetParamValue("PATH_TO_STAMP"),
				0, 0,
				'style="position: absolute; left: 30pt; "'
			); ?></div>

			<table style="width: 100%; position: relative; ">
				<colgroup>
					<col width="0">
					<col width="100%">
				</colgroup>
				<?php  if (CSalePaySystemAction::GetParamValue("SELLER_DIR") || CSalePaySystemAction::GetParamValue("SELLER_DIR_SIGN")) { ?>
				<?php  if (CSalePaySystemAction::GetParamValue("SELLER_DIR")) { ?>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan="2"><?=CSalePaySystemAction::GetParamValue("SELLER_DIR"); ?></td>
				</tr>
				<?php  } ?>
				<?php  if (CSalePaySystemAction::GetParamValue("SELLER_DIR_SIGN")) { ?>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><nobr>The Director</nobr></td>
					<td style="border-bottom: 1pt solid #000000; text-align: center; ">
						<span style="position: relative; ">&nbsp;<?=CFile::ShowImage(
							CSalePaySystemAction::GetParamValue("SELLER_DIR_SIGN"),
							200, 50,
							'style="position: absolute; margin-left: -75pt; bottom: 0pt; "'
						); ?></span>
					</td>
				</tr>
				<?php  } ?>
				<?php  } ?>
				<?php  if (CSalePaySystemAction::GetParamValue("SELLER_ACC") || CSalePaySystemAction::GetParamValue("SELLER_ACC_SIGN")) { ?>
				<?php  if (CSalePaySystemAction::GetParamValue("SELLER_ACC")) { ?>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan="2"><?=CSalePaySystemAction::GetParamValue("SELLER_ACC"); ?></td>
				</tr>
				<?php  } ?>
				<?php  if (CSalePaySystemAction::GetParamValue("SELLER_ACC_SIGN")) { ?>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><nobr>The Accountant</nobr></td>
					<td style="border-bottom: 1pt solid #000000; text-align: center; ">
						<span style="position: relative; ">&nbsp;<?=CFile::ShowImage(
							CSalePaySystemAction::GetParamValue("SELLER_ACC_SIGN"),
							200, 50,
							'style="position: absolute; margin-left: -75pt; bottom: 0pt; "'
						); ?></span>
					</td>
				</tr>
				<?php  } ?>
				<?php  } ?>
			</table>

		</td>
	</tr>
</table>

</body>
</html>