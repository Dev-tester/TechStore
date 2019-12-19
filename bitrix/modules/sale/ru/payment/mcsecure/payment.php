<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?php 
$ORDER_ID = IntVal($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"]);
?>
<p>Вы хотите оплатить по кредитной карте через процессинговый центр платежной системы <strong>ИМПЭКСБанка</strong>.</p>
<p>Cчёт № <?php echo $ORDER_ID." от ".CSalePaySystemAction::GetParamValue("DATE_INSERT")?></p>
<p>Сумма к оплате по счету: <strong><?php echo SaleFormatCurrency(CSalePaySystemAction::GetParamValue("SHOULD_PAY"), CSalePaySystemAction::GetParamValue("CURRENCY"))?></strong></p>

<!-- START IMPEXBANK SMART-CARD PAY FORM -->
<form method=post action="https://e-commerce.impexbank.ru/vsmc3ds/3dsproxy_init.jsp" target="_blank" class="mb-3">
	<input type="hidden" name="AcquirerBin" value="<?php echo CSalePaySystemAction::GetParamValue("AcquirerBin")?>">
	<input type="hidden" name="PurchaseAmt" value="<?php echo CSalePaySystemAction::GetParamValue("SHOULD_PAY")?>">
	<input type="hidden" name="PurchaseDesc" value="<?php echo $ORDER_ID?>">
	<input type="hidden" name="CountryCode" value="643">
	<input type="hidden" name="CurrencyCode" value="810">
	<input type="hidden" name="MerchantName" value="<?php echo CSalePaySystemAction::GetParamValue("MerchantName")?>">
	<input type="hidden" name="MerchantURL" value="<?php echo CSalePaySystemAction::GetParamValue("MerchantURL")?>">
	<input type="hidden" name="MerchantCity" value="<?php echo CSalePaySystemAction::GetParamValue("MerchantCity")?>">
	<input type="hidden" name="MerchantID" value="<?php echo CSalePaySystemAction::GetParamValue("MerchantID")?>">
	<input type="submit" value="Оплатить" class="btn btn-primary">
</form>
<!-- END IMPEXBANK SMART-CARD PAY FORM -->

<div class="alert alert-warning" role="alert">
	<p class="mb-1"><strong>Обратите внимание!</strong></p>
	<p class="mb-1">Все финансовые операции осуществляются в процессинговом центре платежной системы ИМПЭКСБанка.</p>
	<p class="mb-0">Все данные, необходимые для осуществления платежа, гарантированно защищены платежной системой ИМПЭКСБанка.</p>
</div>