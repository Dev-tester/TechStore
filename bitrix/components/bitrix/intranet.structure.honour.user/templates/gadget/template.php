<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (is_array($arResult['ENTRIES']) && count($arResult['ENTRIES']) > 0):
	?><div class="bx-user-honour-layout"><?php 
	foreach ($arResult['ENTRIES'] as $arEntry):
		$ts_start = MakeTimeStamp($arEntry['DATE_ACTIVE_FROM']);
		$ts_finish = MakeTimeStamp($arEntry['DATE_ACTIVE_TO']);
		$ts_now = time();
		
		$bNow = $ts_now >= $ts_start && $ts_now <= $ts_finish;
		?><div class="bx-user-honour-entry<?php echo $bNow ? ' bx-user-honour-now' : ''?>">
			<span class="bx-user-honour-entry-date"><?php echo $arEntry['DATE_ACTIVE_FROM']?></span>
			<span class="bx-user-honour-entry-title"><?php echo $arEntry['TITLE']?></span>
		</div><?php 
	endforeach;
	?></div><?php 
else:
	echo GetMessage('SONET_HONOUR_USER_NOT_FOUND');	
endif;
?>