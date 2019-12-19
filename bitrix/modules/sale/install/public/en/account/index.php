<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Account");
?><?php $APPLICATION->IncludeComponent(
	"bitrix:sale.personal.account",
	"",
	Array(
		"SET_TITLE" => "Y" 
	)
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>