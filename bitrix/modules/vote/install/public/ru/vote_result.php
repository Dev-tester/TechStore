<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->SetTitle("Результаты опроса");
$APPLICATION->AddChainItem("Архив опросов", "vote_list.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");
?>
<?php $APPLICATION->IncludeComponent("bitrix:voting.result", ".default", Array(
	"VOTE_ID"	=> $_REQUEST["VOTE_ID"],
	)
);?>
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");?>
