<?php
require($_SERVER['DOCUMENT_ROOT'] . '/mobile/headers.php');
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
?><div class="crm_wrapper"><?php 
$GLOBALS['APPLICATION']->IncludeComponent(
	'bitrix:mobile.crm.product_row.list',
	'',
	array(
		'UID' => 'mobile_crm_product_row_list'
	)
);
?></div><?php 
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
