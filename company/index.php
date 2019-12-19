<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/company/index.php");
$APPLICATION->SetTitle(GetMessage("COMPANY_TITLE"));
?>
<?php 
$componentParams = [
	"PATH_TO_DEPARTMENT" => "/company/structure.php?set_filter_structure=Y&structure_UF_DEPARTMENT=#ID#",
	"LIST_URL" => "/company/",
];

$APPLICATION->IncludeComponent(
	"bitrix:ui.sidepanel.wrapper",
	"",
	array(
		'POPUP_COMPONENT_NAME' => "bitrix:intranet.user.list",
		"POPUP_COMPONENT_TEMPLATE_NAME" => "",
		"POPUP_COMPONENT_PARAMS" => $componentParams,
		"USE_UI_TOOLBAR" => "Y"
	)
);
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
