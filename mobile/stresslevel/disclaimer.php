<?php 
use Bitrix\Main\Page\Asset;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
Asset::getInstance()->addString(Bitrix\MobileApp\Mobile::getInstance()->getViewPort());
?>

<?php $APPLICATION->IncludeComponent(
	"bitrix:intranet.stresslevel",
	".default",
	array(
		"PAGE" => "disclaimer",
	),
	false
);?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>