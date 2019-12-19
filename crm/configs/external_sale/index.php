<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/crm/configs/external_sale/index.php");
$APPLICATION->SetTitle(GetMessage("CRM_TITLE"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.config.external_sale",
	"",
	Array(
	),
false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>