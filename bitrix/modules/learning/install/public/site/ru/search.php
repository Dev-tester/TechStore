<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?>

<?php 
$APPLICATION->IncludeComponent("bitrix:learning.search", ".default", array(
	"PAGE_RESULT_COUNT" => "10",
	"DISPLAY_TOP_PAGER" => "Y",
	"DISPLAY_BOTTOM_PAGER" => "Y",
	),
	$component
);
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>