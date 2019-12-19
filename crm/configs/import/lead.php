<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?php $APPLICATION->IncludeComponent(
	"bitrix:crm.lead.rest", "", Array()
);
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>