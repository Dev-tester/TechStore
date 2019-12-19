<?php 
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (!\check_bitrix_sessid())
	return;
?>
<form action="<?php echo $APPLICATION->GetCurPage()?>">
<?=\bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?php echo LANGUAGE_ID?>">
	<input type="hidden" name="id" value="imconnector">
	<input type="hidden" name="uninstall" value="Y">
	<input type="hidden" name="step" value="2">
	<?php echo \CAdminMessage::ShowMessage(Loc::getMessage("MOD_UNINST_WARN"))?>
	<p><?php echo Loc::getMessage("MOD_UNINST_SAVE")?></p>
	<p><input type="checkbox" name="savedata" id="savedata" value="Y" checked><label for="savedata"><?php echo Loc::getMessage("MOD_UNINST_SAVE_TABLES")?></label></p>
	<input type="submit" name="" value="<?php echo Loc::getMessage("MOD_UNINST_DEL")?>">
</form>