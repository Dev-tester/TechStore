<?php
require($_SERVER['DOCUMENT_ROOT'] . '/mobile/headers.php');
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
?><div class="crm_wrapper"><?php 
$GLOBALS['APPLICATION']->IncludeComponent(
	'bitrix:mobile.crm.pay_system.list',
	'',
	array('UID' => 'mobile_crm_pay_system_list_#PERSON_TYPE_ID#')
);
?></div><?php 
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
