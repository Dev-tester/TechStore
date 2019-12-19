<?php 
define("ADMIN_MODULE_NAME", "messageservice");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin.php");

\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);

$APPLICATION->SetTitle(\Bitrix\Main\Localization\Loc::getMessage("MESSAGESERVICE_SENDER_SMS_TITLE"));
$APPLICATION->SetAdditionalCSS('/bitrix/css/main/grid/webform-button.css');

$isSlider = isset($_REQUEST['IFRAME_TYPE']) && $_REQUEST['IFRAME_TYPE'] === 'SIDE_SLIDER';

if ($isSlider)
{
	$APPLICATION->RestartBuffer();
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<?php $APPLICATION->ShowHead(); ?>
	</head>
	<body>
	<?php 
}

?>
<div style="background: white">
<?php 
/** @var \CAllMain $APPLICATION */
$APPLICATION->IncludeComponent("bitrix:messageservice.config.sender.sms", "", [
	'SENDER_ID' => isset($_REQUEST['sender_id']) ? $_REQUEST['sender_id'] : null
]);
?>
</div>
<?php 

if ($isSlider)
{
	?></body></html><?php 
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");