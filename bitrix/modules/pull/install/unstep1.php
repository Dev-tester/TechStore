<?php 
IncludeModuleLangFile(__FILE__);
?>
<form action="<?php echo $APPLICATION->GetCurPage()?>">
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?php echo LANG?>">
	<input type="hidden" name="id" value="pull">
	<input type="hidden" name="uninstall" value="Y">
	<input type="hidden" name="step" value="2">

	<?php 
	CModule::IncludeModule('pull');
	CPullOptions::ClearCheckCache();
	$arDependentModule = Array();
	$ar = CPullOptions::GetDependentModule();
	foreach ($ar as $key => $value)
		$arDependentModule[] = $value['MODULE_ID'];

	if (empty($arDependentModule)):?>
		<?php echo CAdminMessage::ShowMessage(GetMessage("MOD_UNINST_WARN"))?>
	<?php else:?>
		<?php echo CAdminMessage::ShowMessage(GetMessage("PULL_WARNING_MODULE", Array('#BR#' => '<br />', '#MODULE#' => implode(", ", $arDependentModule))))?>
	<?php endif;?>
	<p><?php echo GetMessage("MOD_UNINST_SAVE")?></p>
	<p><input type="checkbox" name="savedata" id="savedata" value="Y" checked><label for="savedata"><?php echo GetMessage("MOD_UNINST_SAVE_TABLES")?></label></p>
	<input type="submit" name="inst" value="<?php echo GetMessage("MOD_UNINST_DEL")?>">
</form>