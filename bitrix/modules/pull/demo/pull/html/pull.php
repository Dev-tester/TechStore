<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Push & Pull");
?>

<?php 
$APPLICATION->IncludeComponent("yourcompanyprefix:pull.test", '');
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>