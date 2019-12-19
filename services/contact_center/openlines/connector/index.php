<?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");?>

<?php $APPLICATION->IncludeComponent("bitrix:intranet.popup.provider",
								 "",
								 array(
									 "COMPONENT_NAME" => "bitrix:imconnector.connector.settings",
									 "COMPONENT_TEMPLATE" => ""));?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
