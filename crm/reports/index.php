<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/crm/reports/index.php");
$APPLICATION->SetTitle(GetMessage("CRM_TITLE"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.deal.funnel",
	"",
	Array(
	),
false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>