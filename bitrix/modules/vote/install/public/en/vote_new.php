<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Vote");
$APPLICATION->AddChainItem("Votes", "vote_list.php");
?>
<?php 
$VOTE_ID = $_REQUEST["VOTE_ID"]; 
?>
<?php $APPLICATION->IncludeComponent("bitrix:voting.form", ".default", Array(
	"VOTE_ID"	=>	$_REQUEST["VOTE_ID"],
	"VOTE_RESULT_TEMPLATE"	=>	"vote_result.php?VOTE_ID=#VOTE_ID#"
	)
);?>
<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
