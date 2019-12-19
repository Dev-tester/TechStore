<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/services/telephony/detail.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");

$APPLICATION->SetTitle(GetMessage("VI_PAGE_STAT_DETAIL"));
?>

<?php $APPLICATION->IncludeComponent("bitrix:voximplant.statistic.detail", "", array("LIMIT" => "30"));?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
