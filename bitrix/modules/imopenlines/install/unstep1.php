<?php 
IncludeModuleLangFile(__FILE__);
?>
<form action="<?php echo $APPLICATION->GetCurPage()?>">
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?php echo LANG?>">
	<input type="hidden" name="id" value="imopenlines">
	<input type="hidden" name="uninstall" value="Y">
	<input type="hidden" name="step" value="2">
	<p><?php echo GetMessage("MOD_UNINST_SAVE")?></p>
	<p><input type="checkbox" name="savedata" id="savedata" value="Y" checked><label for="savedata"><?php echo GetMessage("MOD_UNINST_SAVE_TABLES")?></label></p>
	<p><?php echo GetMessage("MOD_UNINST_SAVE_EMAIL_NEW")?></p>
	<p><input type="checkbox" name="saveemails" id="saveemails" value="Y" checked><label for="saveemails"><?php echo GetMessage("MOD_UNINST_SAVE_EMAILS_NEW")?></label></p>
	<input type="submit" name="inst" value="<?php echo GetMessage("MOD_UNINST_DEL")?>">
</form>