<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?php 
$ORDER_ID = IntVal($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"]);
?>
<form method=post action=http://www.kreditpilot.com/servlets/com.kreditpilot.server.FirstStep target="_blank">
	<input type=hidden name=BillNumber value="<?php echo $ORDER_ID?>">
	<p>Вы хотите оплатить через систему <strong>www.kreditpilot.ru</strong>.</p>
	<p>Cчет № <?php echo $ORDER_ID." от ".CSalePaySystemAction::GetParamValue("DATE_INSERT")?></p>
	<input type=hidden name=BillDescription value="Order &nbsp;<?php echo $ORDER_ID?>&nbsp">
	<input type=hidden name=BillSum value="<?php echo CSalePaySystemAction::GetParamValue("SHOULD_PAY")?>">
	<p>Сумма к оплате по счету: <?php echo SaleFormatCurrency(CSalePaySystemAction::GetParamValue("SHOULD_PAY"), CSalePaySystemAction::GetParamValue("CURRENCY"))?></p>
	<input type=hidden name=BillShopId value="<?php echo CSalePaySystemAction::GetParamValue("SHOP_ID")?>">
	<input type=hidden name=BillDate value="<?php echo CSalePaySystemAction::GetParamValue("DATE_INSERT")?>">
	<input type=hidden name=BillCurrency value="<?php echo (CSalePaySystemAction::GetParamValue("CURRENCY") == "RUR"? "руб.":"")?>">
	<input type=submit name=sub value="Оплатить" class="btn btn-primary">
</form>
