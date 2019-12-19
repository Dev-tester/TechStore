<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");?>

<?php $APPLICATION->IncludeComponent(
	"bitrix:learning.student.profile",
	"",
	Array(
		"TRANSCRIPT_DETAIL_TEMPLATE" => "certification/?TRANSCRIPT_ID=#TRANSCRIPT_ID#", 
		"SET_TITLE" => "Y" 
	)
);?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>