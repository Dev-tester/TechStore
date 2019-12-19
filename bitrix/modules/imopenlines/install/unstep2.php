<?php 
if(!check_bitrix_sessid()) return;
IncludeModuleLangFile(__FILE__);
global $errors;

echo CAdminMessage::ShowNote(GetMessage("MOD_UNINST_OK"));
?>
<br>
<form action="<?php echo $APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?php echo LANG?>">
	<input type="submit" name="" value="<?php echo GetMessage("MOD_BACK")?>">
</form>