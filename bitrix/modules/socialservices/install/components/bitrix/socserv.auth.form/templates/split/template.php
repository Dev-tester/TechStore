<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>
<?php 
if($arParams["POPUP"]):
	//only one float div per page
	if(defined("BX_SOCSERV_POPUP"))
		return;
	define("BX_SOCSERV_POPUP", true);
?>
<div style="display:none">
<div id="bx_auth_float" class="bx-auth-float">
<?php endif?>

<?php if(($arParams["~CURRENT_SERVICE"] <> '') && $arParams["~FOR_SPLIT"] != 'Y'):?>
<script type="text/javascript">
BX.ready(function(){BxShowAuthService('<?=CUtil::JSEscape($arParams["~CURRENT_SERVICE"])?>', '<?=$arParams["~SUFFIX"]?>')});
</script>
<?php endif?>
<?php 
if($arParams["~FOR_SPLIT"] == 'Y'):?>
<div class="bx-auth-serv-icons">
<?php foreach($arParams["~AUTH_SERVICES"] as $service):?>
	<?php 
	if(($arParams["~FOR_SPLIT"] == 'Y') && (is_array($service["FORM_HTML"])))
		$onClickEvent = $service["FORM_HTML"]["ON_CLICK"];
	else
		$onClickEvent = "onclick=\"BxShowAuthService('".$service['ID']."', '".$arParams['SUFFIX']."')\"";
	?>
	<a title="<?=htmlspecialcharsbx($service["NAME"])?>" href="javascript:void(0)" <?=$onClickEvent?> id="bx_auth_href_<?=$arParams["SUFFIX"]?><?=$service["ID"]?>"><i class="bx-ss-icon <?=htmlspecialcharsbx($service["ICON"])?>"></i></a><?php endforeach?>
</div>
<?php endif;?>
<div class="bx-auth">
		<div class="bx-auth-service-form" id="bx_auth_serv<?=$arParams["SUFFIX"]?>" style="display:none">
<?php foreach($arParams["~AUTH_SERVICES"] as $service):?>
			<?php if(($arParams["~FOR_SPLIT"] != 'Y') || (!is_array($service["FORM_HTML"]))):?>
			<div id="bx_auth_serv_<?=$arParams["SUFFIX"]?><?=$service["ID"]?>" style="display:none"><?=$service["FORM_HTML"]?></div>
				<?php endif;?>
<?php endforeach?>
		</div>
<?php foreach($arParams["~POST"] as $key => $value):?>
		<?php if(!preg_match("|OPENID_IDENTITY|", $key)):?>
		<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
		<?php endif;?>
<?php endforeach?>
		<input type="hidden" name="auth_service_id" value="" />
</div>

<?php if($arParams["POPUP"]):?>
</div>
</div>
<?php endif?>