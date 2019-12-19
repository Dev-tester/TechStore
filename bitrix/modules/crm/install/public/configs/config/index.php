<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(GetMessage("CRM_PAGE_TITLE"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.config.configs",
	"",
	Array(
	),
false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>