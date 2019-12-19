<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регулярные платежи");
?><?php $APPLICATION->IncludeComponent(
	"bitrix:sale.personal.subscribe",
	"",
	Array(
		"SEF_MODE" => "N", 
		"PER_PAGE" => "20", 
		"SET_TITLE" => "Y" 
	)
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>