<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?><?php $APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order",
	".default",
	Array(
		"SEF_MODE" => "N", 
		"ORDERS_PER_PAGE" => "20", 
		"SET_TITLE" => "Y" 
	)
);?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
