<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (isset($messageservice_installer_errors) && is_array($messageservice_installer_errors) && (count($messageservice_installer_errors) > 0))
{
	$errors = "";
	foreach ($messageservice_installer_errors as $e)
		$errors .= htmlspecialcharsbx($e)."<br>";
	echo CAdminMessage::ShowMessage(Array("TYPE"=>"ERROR", "MESSAGE" =>GetMessage("MOD_UNINST_ERR"), "DETAILS"=>$errors, "HTML"=>true));
	?>
	<form action="<?php echo $APPLICATION->GetCurPage()?>">
		<input type="hidden" name="lang" value="<?php echo LANG?>">
		<input type="submit" name="" value="<?php echo GetMessage("MOD_BACK")?>">
	</form>
	<?php 
}
else
{
	?>
	<form action="<?php echo $APPLICATION->GetCurPage()?>">
		<?=bitrix_sessid_post()?>
		<input type="hidden" name="lang" value="<?php echo LANGUAGE_ID?>">
		<input type="hidden" name="id" value="messageservice">
		<input type="hidden" name="uninstall" value="Y">
		<input type="hidden" name="step" value="2">
		<?php echo CAdminMessage::ShowMessage(GetMessage("MOD_UNINST_WARN"))?>
		<p><?php echo GetMessage("MOD_UNINST_SAVE")?></p>
		<p><input type="checkbox" name="savedata" id="savedata" value="Y" checked><label for="savedata"><?php echo GetMessage("MOD_UNINST_SAVE_TABLES")?></label></p>
		<input type="submit" name="inst" value="<?php echo GetMessage("MOD_UNINST_DEL")?>">
	</form>
	<?php 
}
?>