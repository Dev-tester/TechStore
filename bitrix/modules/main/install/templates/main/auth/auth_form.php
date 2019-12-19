<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?php 
// Authorization form (for prolog)
IncludeTemplateLangFile(__FILE__); //include of language file
$store_password = COption::GetOptionString("main", "store_password", "Y");
if (!$USER->IsAuthorized()):
if (defined("AUTH_404"))
{
	$page = SITE_DIR."auth.php";
	$str = "<input type='hidden' name='backurl' value='".$GLOBALS["APPLICATION"]->GetCurPage()."'>";
}
else $page = $GLOBALS["APPLICATION"]->GetCurPage();
?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
	<form method="post" target="_top" action="<?php echo $page.(($s=DeleteParam(array("logout", "login"))) == ""? "?login=yes":"?$s&login=yes");?>">
		<?=$str?>
		<?php 
		foreach ($GLOBALS["HTTP_POST_VARS"] as $vname=>$vvalue) :
			if ($vname=="USER_LOGIN") continue;
			?><input type="hidden" name="<?php echo htmlspecialcharsbx($vname)?>" value="<?php echo htmlspecialcharsbx($vvalue)?>"><?php 
		endforeach;
		?>
		<input type="hidden" name="AUTH_FORM" value="Y">
		<input type="hidden" name="TYPE" value="AUTH">

		<tr valign="middle">
			<td align="center" colspan="2"><input type="text" name="USER_LOGIN" maxlength="50" size="15" value="<?php echo htmlspecialcharsbx(${COption::GetOptionString("main", "cookie_name", "BITRIX_SM")."_LOGIN"})?>"  class="inputtext"></td>
		</tr>
		<tr>
			<td align="center" colspan="2"><input type="password" name="USER_PASSWORD" maxlength="50" size="15" class="inputtext"></td>
		</tr>
		<tr>
			<td align="center" colspan="2"><input type="submit" name="Login" value="<?php echo GetMessage("AUTH_LOGIN_BUTTON");?>" class="inputbutton"></td>
		</tr>
		<?php if ($store_password=="Y") :?>
		<tr>
			<td align="center" width="0%" valign="top"><input type="checkbox" name="USER_REMEMBER" value="Y"></td>
			<td width="100%"><font class="smalltext"><?=GetMessage("AUTH_REMEMBER_ME")?></font></td>
		</tr>
		<?php endif;?>
	</form>
		<tr>
			<td width="0%"><font class="smalltext">&nbsp;</font></td>
			<td align="left" width="100%"><a href="<?php echo SITE_DIR."auth.php?forgot_password=yes"; ?>" class="smalltext"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a><br></td>
		</tr>
		<?php if(COption::GetOptionString("main", "new_user_registration", "N")=="Y"):?>
		<tr>
			<td width="0%"><font class="smalltext">&nbsp;</font></td>
			<td align="left" width="100%"><a href="<?php echo SITE_DIR."auth.php?register=yes"; ?>" class="smalltext"><?=GetMessage("AUTH_REGISTER")?></a><br></td>
		</tr>
		<?php endif?>
	</table><br>
<?php else:?>
<form action="?logout=yes<?php echo htmlspecialcharsbx(($s=DeleteParam(array("logout", "login"))) == ""? "":"&".$s);?>">
<div align="center" class="smalltext" style="padding-bottom: 2 px;"><?php echo htmlspecialcharsbx($USER->GetFullName())?><br>[<?php echo htmlspecialcharsbx($USER->GetLogin())?>]<br>
<?php foreach ($_GET as $vname=>$vvalue):?>
<input type="hidden" name="<?php echo htmlspecialcharsbx($vname)?>" value="<?php echo htmlspecialcharsbx($vvalue)?>">
<?php endforeach;?>
<input type="hidden" name="logout" value="yes"><input type="submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" class="inputbutton" style="margin-top: 4px;">
</div>
</form>
	<?php 
endif;
?>