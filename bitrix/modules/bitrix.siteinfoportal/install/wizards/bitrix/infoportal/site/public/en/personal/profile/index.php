<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Personal Information");
?><?php $APPLICATION->IncludeComponent("bitrix:main.profile", ".default", Array(
	"SET_TITLE" => "Y",	
	),
	false
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>