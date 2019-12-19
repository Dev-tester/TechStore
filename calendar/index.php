<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/about/calendar.php");
$APPLICATION->SetTitle(GetMessage("ABOUT_TITLE"));
?>

<?php $APPLICATION->IncludeComponent(
	"bitrix:calendar.grid",
	"",
	Array(
		"CALENDAR_TYPE" => "company_calendar",
		"ALLOW_SUPERPOSE" => "Y",
		"ALLOW_RES_MEETING" => "Y"
	)
);?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
