<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public_bitrix24/telephony/editivr.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");

$APPLICATION->SetTitle(GetMessage("VI_PAGE_EDIT_IVR_TITLE_2"));
?>

<?php $APPLICATION->IncludeComponent("bitrix:ui.sidepanel.wrapper",
	"",
	array(
		"POPUP_COMPONENT_NAME" => "bitrix:voximplant.ivr.edit",
		"POPUP_COMPONENT_TEMPLATE_NAME" => "",
		"USE_PADDING" => false
	)
);?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
