<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<div class="content-form login-form">
<div class="fields">
<?php 
ShowMessage($arParams["~AUTH_RESULT"]);
ShowMessage($arResult['ERROR_MESSAGE']);
?>

<form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">

	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="AUTH" />
	<?php if (strlen($arResult["BACKURL"]) > 0):?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<?php endif?>
	<?php 
	foreach ($arResult["POST"] as $key => $value)
	{
	?>
	<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
	<?php 
	}
	?>
	<div class="field">
		<label class="field-title"><?=GetMessage("AUTH_LOGIN")?></label>
		<div class="form-input"><input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" class="input-field" /></div>
	</div>	
	<div class="field">
		<label class="field-title"><?=GetMessage("AUTH_PASSWORD")?></label>
		<div class="form-input"><input type="password" name="USER_PASSWORD" maxlength="50" class="input-field" />
<?php if($arResult["SECURE_AUTH"]):?>
				<span class="bx-auth-secure" id="bx_auth_secure" title="<?php echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
					<div class="bx-auth-secure-icon"></div>
				</span>
				<noscript>
				<span class="bx-auth-secure" title="<?php echo GetMessage("AUTH_NONSECURE_NOTE")?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
				</noscript>
<script type="text/javascript">
document.getElementById('bx_auth_secure').style.display = 'inline-block';
</script>
<?php endif?>
		</div>
	</div>
	<?php if($arResult["CAPTCHA_CODE"]):?>
		<div class="field">
			<label class="field-title"><?=GetMessage("AUTH_CAPTCHA_PROMT")?></label>
			<div class="form-input"><input type="text" name="captcha_word" maxlength="50" class="input-field" /></div>
			<p style="clear: left;"><input type="hidden" name="captcha_sid" value="<?php echo $arResult["CAPTCHA_CODE"]?>" /><img src="/bitrix/tools/captcha.php?captcha_sid=<?php echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /></p>
		</div>
	<?php endif;?>
	<?php 
	if ($arResult["STORE_PASSWORD"] == "Y")
	{
	?>
		<div class="field field-option">
			<input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" /><label for="USER_REMEMBER">&nbsp;<?=GetMessage("AUTH_REMEMBER_ME")?></label>
		</div>
	<?php 
	}
	?>
	<div class="field field-button">
		<input type="submit" name="Login" value="<?=GetMessage("AUTH_AUTHORIZE")?>" />
	</div>
<?php 
if ($arParams["NOT_SHOW_LINKS"] != "Y")
{
?><noindex>
<div class="field">
<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><b><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></b></a><br />
<?=GetMessage("AUTH_GO")?> <a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_GO_AUTH_FORM")?></a><br />
<?=GetMessage("AUTH_MESS_1")?> <a href="<?=$arResult["AUTH_CHANGE_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_CHANGE_FORM")?></a>
</div>
</noindex><?php 
}
?>
</form>
<script type="text/javascript">
<?php 
if (strlen($arResult["LAST_LOGIN"])>0)
{
?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?php 
}
else
{
?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?php 
}
?>
</script>

</div>

<?php if($arResult["AUTH_SERVICES"]):?>
<?php 
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "", 
	array(
		"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
		"CURRENT_SERVICE"=>$arResult["CURRENT_SERVICE"],
		"AUTH_URL"=>$arResult["AUTH_URL"],
		"POST"=>$arResult["POST"],
	), 
	$component, 
	array("HIDE_ICONS"=>"Y")
);
?>
<?php endif?>

</div>