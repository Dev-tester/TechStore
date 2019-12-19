<?php
require($_SERVER['DOCUMENT_ROOT'] . '/mobile/headers.php');
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');

?><div class="crm_wrapper"><?php 
$GLOBALS['APPLICATION']->IncludeComponent(
	'bitrix:mobile.crm.progress_bar.list',
	'',
	array('UID' => 'mobile_crm_progress_bar_list_#ENTITY_TYPE#')
);
?></div><?php 
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
