<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?><?php $APPLICATION->IncludeComponent(
	"bitrix:webservice.checkauth",
	"",
	Array(
	)
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>