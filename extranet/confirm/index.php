<?php 
define('CONFIRM_PAGE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подтверждение регистрации");
?>
<?php $APPLICATION->IncludeComponent(
	"bitrix:system.auth.initialize",
	"",
	Array(
		"CHECKWORD_VARNAME"=>"checkword",
		"USERID_VARNAME"=>"user_id",
		"AUTH_URL"=>"/extranet/auth.php",
	),
false
);?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>