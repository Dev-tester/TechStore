<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/crm/configs/config/index.php");
$APPLICATION->SetTitle(GetMessage("CRM_TITLE"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.config.configs",
	"",
	Array(
	),
false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>