<form action="<?php echo $APPLICATION->GetCurPage()?>">
<?=bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?php echo LANGUAGE_ID?>">
	<input type="hidden" name="id" value="socialnetwork">
	<input type="hidden" name="uninstall" value="Y">
	<input type="hidden" name="step" value="2">
	<?php echo CAdminMessage::ShowMessage(GetMessage("MOD_UNINST_WARN"))?>
	<p><?php echo GetMessage("MOD_UNINST_SAVE")?></p>
	<p><input type="checkbox" name="savedata" id="savedata" value="Y" checked><label for="savedata"><?php echo GetMessage("MOD_UNINST_SAVE_TABLES")?></label></p>
	<p><input type="checkbox" name="deleteemails" id="deleteemails" value="Y" checked><label for="deleteemails"><?php echo GetMessage("SONETP_DELETE_EMAIL")?></label></p>
	<input type="submit" name="inst" value="<?php echo GetMessage("MOD_UNINST_DEL")?>">
</form>