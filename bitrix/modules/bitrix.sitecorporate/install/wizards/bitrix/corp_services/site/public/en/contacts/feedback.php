<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Feedback");
?>
<p><?php $APPLICATION->IncludeComponent("bitrix:main.feedback", "template", array(
	"USE_CAPTCHA" => "Y",
	"OK_TEXT" => "Your request has been sent.",
	"EMAIL_TO" => "",
	"REQUIRED_FIELDS" => array(
		0 => "NAME",
		1 => "EMAIL",
		2 => "MESSAGE",
	),
	"EVENT_MESSAGE_ID" => array(
	)
	),
	false
);?></p>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>