<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/crm/configs/widget/index.php");
$APPLICATION->SetTitle(GetMessage("CRM_TITLE_WIDGET"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.widget_slot.list",
	"",
	Array(
	),
	false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>