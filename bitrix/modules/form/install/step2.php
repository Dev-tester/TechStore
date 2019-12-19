<?php if(!check_bitrix_sessid()) return;?>
<?php 
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/form/install/install.php");

if(is_array($errors) && count($errors)>0):
	foreach($errors as $val)
		$alErrors .= $val."<br>";
	echo CAdminMessage::ShowMessage(Array("TYPE"=>"ERROR", "MESSAGE" =>GetMessage("MOD_INST_ERR"), "DETAILS"=>$alErrors, "HTML"=>true));
else:
	echo CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));
endif;
/*
if (strlen($public_dir)>0) :
?>
<p><?=GetMessage("MOD_DEMO_DIR")?></p>
<table border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td align="center"><p><b><?=GetMessage("MOD_DEMO_SITE")?></b></p></td>
		<td align="center"><p><b><?=GetMessage("MOD_DEMO_LINK")?></b></p></td>
	</tr>
	<?php 
	$sites = CSite::GetList($by, $order, Array("ACTIVE"=>"Y"));
	while($site = $sites->Fetch())
	{
		?>
		<tr>
			<td width="0%"><p>[<?=$site["ID"]?>] <?=$site["NAME"]?></p></td>
			<td width="0%"><p><a href="<?php if(strlen($site["SERVER_NAME"])>0) echo "http://".$site["SERVER_NAME"];?><?=$site["DIR"].$public_dir?>/result_list.php?WEB_FORM_NAME=ANKETA"><?=$site["DIR"].$public_dir?>/result_list.php?WEB_FORM_NAME=ANKETA</a></p></td>
		</tr>
		<?php 
	}
	?>
</table>
<?php 
endif;
*/
?>
<form action="<?php echo $APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?php echo LANG?>">
	<input type="submit" name="" value="<?php echo GetMessage("MOD_BACK")?>">
</form>