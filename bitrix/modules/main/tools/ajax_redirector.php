<?php 
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");?>
<?php 
$hash = $_GET['hash'];

if ($url = CAjax::decodeURI($hash))
{
	LocalRedirect($url);
}
else
{
	LocalRedirect('/');
}
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>