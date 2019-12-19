<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(GetMessage("CRM_PAGE_TITLE"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.config.external_sale",
	"",
	Array(
	),
false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>