<?php 
IncludeModuleLangFile(__FILE__);
?>
<form action="<?php echo $APPLICATION->GetCurPage()?>">
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?php echo LANG?>">
	<input type="hidden" name="id" value="faceid">
	<input type="hidden" name="uninstall" value="Y">
	<input type="hidden" name="step" value="2">

	<?php echo GetMessage("FACEID_UNINSTALL_TITLE")?>
	<div class="adm-info-message-wrap">
		<div class="adm-info-message">
			<div><?php echo GetMessage("FACEID_UNINSTALL_QUESTION")?></div>
		</div>
	</div>
	<input type="submit" name="inst" value="<?php echo GetMessage("MOD_UNINST_DEL")?>">
</form>