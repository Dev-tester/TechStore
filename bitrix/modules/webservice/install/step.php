<?php if(!check_bitrix_sessid()) return;?>
<?php 
echo CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));
?>
<br>
<?php echo BeginNote();?>
	<font class="text">
	<a href="/bitrix/components/bitrix/webservice.statistic/distr/BitrixStat.gadget"><?= GetMessage("WS_GADGET_LINK") ?></a><br><br>
	<?= GetMessage("WS_GADGET_DESCR") ?>
	</font>
<?php echo EndNote();?>

<form action="<?php echo $APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?php echo LANG?>">
	<input type="submit" name="" value="<?php echo GetMessage("MOD_BACK")?>">
<form>
