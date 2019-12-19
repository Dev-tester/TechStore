<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/services/telephony/groups.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");

$APPLICATION->SetTitle(GetMessage("VI_PAGE_GROUPS_TITLE"));
?>

<?php $APPLICATION->IncludeComponent("bitrix:voximplant.queue.list", "", array());?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
