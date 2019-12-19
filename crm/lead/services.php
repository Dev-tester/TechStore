<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/crm/lead/services.php");
$APPLICATION->SetTitle(GetMessage("CRM_TITLE"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.lead.webservice",
	"",
	Array(
	),
false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>