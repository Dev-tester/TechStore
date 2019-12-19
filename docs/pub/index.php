<?php 
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?php 

	if(\Bitrix\Main\Config\Option::get("main", "site_stopped", "N") === 'Y')
	{
		return;
	}

	if(\Bitrix\Main\Config\Option::get('disk', 'successfully_converted', false) != 'Y')
	{
		$APPLICATION->IncludeComponent("bitrix:webdav.extlinks", ".default", Array());
		return;
	}
?>

<?php $APPLICATION->IncludeComponent("bitrix:disk.external.link", ".default", Array());?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>