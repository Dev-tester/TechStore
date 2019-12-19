<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?><?php $APPLICATION->IncludeComponent(
	"bitrix:rest.hook",
	".default", 
	array(
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => SITE_DIR."marketplace/hook/",
		"COMPONENT_TEMPLATE" => ".default",
		"SEF_URL_TEMPLATES" => array(
			"list" => "",
			"event_list" => "event/",
			"event_edit" => "event/#id#/",
			"ap_list" => "ap/",
			"ap_edit" => "ap/#id#/",
		)
	),
	false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>