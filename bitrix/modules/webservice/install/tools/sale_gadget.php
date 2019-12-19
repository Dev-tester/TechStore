<?php 
define("NO_KEEP_STATISTIC", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");?>

<?php $APPLICATION->IncludeComponent("bitrix:webservice.sale", ".default", Array());?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>