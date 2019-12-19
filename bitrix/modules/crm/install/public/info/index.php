<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(GetMessage("CRM_PAGE_TITLE"));
 
echo GetMessage("CRM_PAGE_CONTENT");

?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>