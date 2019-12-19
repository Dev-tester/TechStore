<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Results");
?><?php $APPLICATION->IncludeComponent("bitrix:voting.result", "with_description", Array(
	"VOTE_ID"	=>	$_REQUEST["VOTE_ID"]
	)
);?><?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>