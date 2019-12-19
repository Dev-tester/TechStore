<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

?><?php $APPLICATION->IncludeComponent("bitrix:rest.token", ".default", array(
	),
	false
);?>