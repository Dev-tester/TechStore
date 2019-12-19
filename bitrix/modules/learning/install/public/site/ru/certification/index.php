<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");?>

<?php $APPLICATION->IncludeComponent(
	"bitrix:learning.student.transcript",
	"",
	Array(
		"TRANSCRIPT_ID" => $_REQUEST["TRANSCRIPT_ID"], 
		"SET_TITLE" => "Y" 
	)
);?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>