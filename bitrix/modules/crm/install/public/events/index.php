<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(GetMessage("CRM_PAGE_TITLE"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.event.view",
	"",
	Array(
		"ENTITY_ID" => "",
		"EVENT_COUNT" => "20",
		"EVENT_ENTITY_LINK" => "Y"
	)
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>