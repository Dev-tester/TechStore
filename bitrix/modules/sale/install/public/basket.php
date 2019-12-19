<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?><?php $APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket",
	"",
	Array(
		"PATH_TO_ORDER" => "order.php", 
		"HIDE_COUPON" => "N", 
		"COLUMNS_LIST" => Array("NAME","PRICE","TYPE","QUANTITY","DELETE","DELAY","WEIGHT"), 
		"SET_TITLE" => "Y" 
	)
);?> <?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>