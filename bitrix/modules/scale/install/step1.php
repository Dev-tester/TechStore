<?php 
/** @global CMain $APPLICATION */
IncludeModuleLangFile(__FILE__);
?>
<p><?php echo GetMessage("SCALE_INSTALL"); ?></p>
<form action="<?php echo $APPLICATION->GetCurPage(); ?>" name="form1">
<?php echo bitrix_sessid_post(); ?>
<input type="hidden" name="lang" value="<?php echo LANG ?>">
<input type="hidden" name="id" value="scale">
<input type="hidden" name="install" value="Y">
<input type="hidden" name="step" value="2">
<input type="submit" name="inst" value="<?php echo GetMessage("MOD_INSTALL"); ?>">
</form>