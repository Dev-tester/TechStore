<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 

if ($arResult['RESULT']['MORE_ROWS']):
?>
<script type="text/javascript">
BXSPSync(<?=intval($arResult['RESULT']['COUNT'])?>);
</script>
<?php 
else:
?>
<script type="text/javascript">
window.BXSPData.loaded += <?=intval($arResult['RESULT']['COUNT'])?>;
</script>
<?php 
	if ($arResult['QUEUE_EXISTS']):
?>
<script type="text/javascript">
BXSPSyncAdditions('queue');
</script>
<?php 
	elseif ($arResult['LOG_EXISTS']):
?>
<script type="text/javascript">
BXSPSyncAdditions('log');
</script>
<?php 
	else:
?>
<script type="text/javascript">
alert(window.BXSPData.loaded + ' items loaded.');
if (window.BXSPData.loaded > 0)
	BX.reload();
</script>
<?php 
	endif;
endif;
?>