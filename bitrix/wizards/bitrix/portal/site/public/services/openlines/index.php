<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/services/openlines/index.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");

$APPLICATION->SetTitle(GetMessage("OL_PAGE_STATISTICS_TITLE_SHORT"));
?>
<?php //LocalRedirect('/services/openlines/list/');?>
<?php $APPLICATION->IncludeComponent("bitrix:imopenlines.reportboard", "", array());?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
