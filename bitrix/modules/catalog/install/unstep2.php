<?php if(!check_bitrix_sessid()) return;?>
<?php 
global $errors;

if($errors === false):
	echo CAdminMessage::ShowNote(GetMessage("MOD_UNINST_OK"));
else:
	$alErrors .= implode('<br>', $errors);
	echo CAdminMessage::ShowMessage(Array("TYPE"=>"ERROR", "MESSAGE" =>GetMessage("MOD_UNINST_ERR"), "DETAILS"=>$alErrors, "HTML"=>true));
endif;
?>
<form action="<?php echo $APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?php echo LANGUAGE_ID;?>">
	<input type="submit" name="" value="<?php echo GetMessage("MOD_BACK")?>">
<form>