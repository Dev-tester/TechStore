<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/services/telephony/users.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");

$APPLICATION->SetTitle(GetMessage("VI_PAGE_USERS_TITLE"));
?>

<?php $APPLICATION->IncludeComponent("bitrix:voximplant.numbers", "", array());?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
