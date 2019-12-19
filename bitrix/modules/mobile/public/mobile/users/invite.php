<?php 
require($_SERVER["DOCUMENT_ROOT"]."/mobile/headers.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?php $APPLICATION->IncludeComponent("bitrix:mobile.invite.user", "",
	array(),
	false,
	Array("HIDE_ICONS" => "Y")
);?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php")?>