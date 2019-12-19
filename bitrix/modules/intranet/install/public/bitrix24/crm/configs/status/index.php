<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public_bitrix24/crm/configs/status/index.php");
$APPLICATION->SetTitle(GetMessage("TITLE"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.config.status",
	"",
	Array(
	),
false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>