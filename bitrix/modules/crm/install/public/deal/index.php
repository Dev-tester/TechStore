<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(GetMessage("CRM_PAGE_TITLE"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.deal",
	"",
	Array(
		"SEF_MODE" => "Y",
		"ELEMENT_ID" => $_REQUEST["deal_id"],
		"SEF_FOLDER" => "#SITE_DIR#crm/deal/",	
		"SEF_URL_TEMPLATES" => Array(
			"index" => "index.php",
			"list" => "list/",
			"edit" => "edit/#deal_id#/",
			"show" => "show/#deal_id#/"
		),
		"VARIABLE_ALIASES" => Array(
			"index" => Array(),
			"list" => Array(),
			"edit" => Array(),
			"show" => Array()
		)
	)
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>