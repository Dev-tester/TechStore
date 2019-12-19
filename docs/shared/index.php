<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/docs/shared/index.php");
$APPLICATION->SetTitle(GetMessage("DOCS_TITLE"));
$APPLICATION->AddChainItem($APPLICATION->GetTitle(), "/docs/shared/");
?>
<?php $APPLICATION->IncludeComponent("bitrix:disk.common", ".default", Array(
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/docs/shared",
		"STORAGE_ID" => "14"
	)
);?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>