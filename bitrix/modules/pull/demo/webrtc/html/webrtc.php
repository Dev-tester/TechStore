<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("WebRTC demo");
?>

<?php 
$APPLICATION->IncludeComponent("yourcompanyprefix:pull.webrtc", '');
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>