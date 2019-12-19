<?php 
IncludeModuleLangFile(__FILE__);
?>
<form action="<?php echo $APPLICATION->GetCurPage()?>">
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?php echo LANG?>">
	<input type="hidden" name="id" value="documentgenerator">
	<input type="hidden" name="uninstall" value="Y">
	<input type="hidden" name="step" value="2">

	<?php echo GetMessage("DOCUMENTGENERATOR_UNINSTALL_TITLE")?>
	<div class="adm-info-message-wrap">
		<div class="adm-info-message">
			<div><?php echo GetMessage("DOCUMENTGENERATOR_UNINSTALL_QUESTION")?></div>
		</div>
	</div>
	<p><input type="checkbox" name="savedata" id="savedata" value="Y" checked><label for="savedata"><?php echo GetMessage("MOD_UNINST_SAVE_TABLES")?></label></p>
	<input type="submit" name="inst" value="<?php echo GetMessage("MOD_UNINST_DEL")?>">
</form>