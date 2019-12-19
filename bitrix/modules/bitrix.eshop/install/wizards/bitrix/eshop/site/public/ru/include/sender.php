<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="mb-5">
	<h3>РАССЫЛКА</h3>
	<?php $APPLICATION->IncludeComponent("bitrix:sender.subscribe", "", array(
		"SET_TITLE" => "N"
	));?>
</div>