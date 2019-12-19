<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?><?php $APPLICATION->IncludeComponent(
	"bitrix:webservice.server",
	"",
	Array(
		"WEBSERVICE_NAME" => "bitrix.wsdl.test1", 
		"WEBSERVICE_MODULE" => "webservice", 
		"WEBSERVICE_CLASS" => "CGenericWSDLTestWS"
	)
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>