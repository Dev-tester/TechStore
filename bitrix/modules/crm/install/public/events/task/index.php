<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(GetMessage("CRM_PAGE_TITLE"));
?><?php $APPLICATION->IncludeComponent(
	"bitrix:crm.activity.task.list",
	"",
	Array(
		"ACTIVITY_TASK_COUNT" => "20",
		"ACTIVITY_ENTITY_LINK" => "Y"
	)
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>