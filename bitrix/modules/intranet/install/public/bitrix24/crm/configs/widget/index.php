<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public_bitrix24/crm/configs/widget/index.php");
$APPLICATION->SetTitle(GetMessage("TITLE"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.widget_slot.list",
	"",
	Array(
	),
false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>