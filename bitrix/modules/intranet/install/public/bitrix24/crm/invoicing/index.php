<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public_bitrix24/crm/invoicing/index.php");
$APPLICATION->SetTitle(GetMessage("TITLE"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.invoice.invoicing",
	".default",
	Array()
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>