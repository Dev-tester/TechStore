<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Настройки пользователя");
?><?php $APPLICATION->IncludeComponent("bitrix:main.profile", ".default", Array(
	"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
	),
	false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>