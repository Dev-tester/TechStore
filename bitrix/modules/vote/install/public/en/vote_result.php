<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Vote results");
$APPLICATION->AddChainItem("Votes", "vote_list.php");
?>
<?php $APPLICATION->IncludeComponent("bitrix:voting.result", ".default", Array(
	"VOTE_ID"	=> $_REQUEST["VOTE_ID"],
	)
);?>
<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
