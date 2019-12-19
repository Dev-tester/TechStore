<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!is_array($arResult['ENTRIES']) || count($arResult['ENTRIES']) <= 0):
	echo GetMessage('INTR_IAU_TPL_NULL');
else:
?>
<div class="bx-user-absence-layout">
<?php 
	foreach ($arResult['ENTRIES'] as $arEntry):
		$ts_start = MakeTimeStamp($arEntry['DATE_ACTIVE_FROM']);
		$ts_finish = MakeTimeStamp($arEntry['DATE_ACTIVE_TO']);
		$ts_now = time();

		$bNow = $ts_now >= $ts_start && $ts_now <= $ts_finish;
?>
	<div class="bx-user-absence-entry<?php echo $bNow ? ' bx-user-absence-now' : ''?>">
		<span class="bx-user-absence-entry-title"><?php echo htmlspecialcharsbx($arEntry['TITLE'])?></span>
		<span class="bx-user-absence-entry-date"><?php echo GetMessage('INTR_IAU_TPL'.($bNow ? '_TO' : '_FROM'))?> <?php echo $arEntry['DATE_ACTIVE'.($bNow ? '_TO' : '_FROM')]?></span>
	</div>
<?php 
	endforeach;
?>
</div>
<?php 
endif;
?>