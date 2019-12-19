<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
$arAuthServices = $arPost = array();
if(is_array($arParams["~AUTH_SERVICES"]))
{
	$arAuthServices = $arParams["~AUTH_SERVICES"];
}
if(is_array($arParams["~POST"]))
{
	$arPost = $arParams["~POST"];
}
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
	<?php foreach($arAuthServices as $service):?>
	<?php 
	if(($arParams["~FOR_SPLIT"] == 'Y') && (is_array($service["FORM_HTML"])))
		$onClickEvent = $service["FORM_HTML"]["ON_CLICK"];
	else
		$onClickEvent = "onclick=\"BxShowAuthService('".$service['ID']."', '".$arParams['SUFFIX']."')\"";
	?>
	<a title="<?=htmlspecialcharsbx($service["NAME"])?>" href="javascript:void(0)" <?=$onClickEvent?> id="bx_auth_href_<?=$arParams["SUFFIX"]?><?=$service["ID"]?>"><i class="bx-ss-icon <?=htmlspecialcharsbx($service["ICON"])?>"></i></a>
	<?php endforeach?>
</div>
<?php endif;?>
<div class="bx-auth">
	<form method="post" name="bx_auth_services<?=$arParams["SUFFIX"]?>" target="_top" action="<?=$arParams["AUTH_URL"]?>">
		<?php if($arParams["~SHOW_TITLES"] != 'N'):?>
			<div class="bx-auth-title"><?=GetMessage("socserv_as_user")?></div>
			<div class="bx-auth-note"><?=GetMessage("socserv_as_user_note")?></div>
		<?php endif;?>
		<?php if($arParams["~FOR_SPLIT"] != 'Y'):?>
			<div class="bx-auth-services">
				<?php foreach($arAuthServices as $service):?>
					<div><a href="javascript:void(0)" onclick="BxShowAuthService('<?=$service["ID"]?>', '<?=$arParams["SUFFIX"]?>')" id="bx_auth_href_<?=$arParams["SUFFIX"]?><?=$service["ID"]?>"><i class="bx-ss-icon <?=htmlspecialcharsbx($service["ICON"])?>"></i><b><?=htmlspecialcharsbx($service["NAME"])?></b></a></div>
				<?php endforeach?>
			</div>
		<?php endif;?>
		<?php if($arParams["~AUTH_LINE"] != 'N'):?>
			<div class="bx-auth-line"></div>
		<?php endif;?>
		<div class="bx-auth-service-form" id="bx_auth_serv<?=$arParams["SUFFIX"]?>" style="display:none">
			<?php foreach($arAuthServices as $service):?>
				<?php if(($arParams["~FOR_SPLIT"] != 'Y') || (!is_array($service["FORM_HTML"]))):?>
					<div id="bx_auth_serv_<?=$arParams["SUFFIX"]?><?=$service["ID"]?>" style="display:none"><?=$service["FORM_HTML"]?></div>
				<?php endif;?>
			<?php endforeach?>
		</div>
		<?php foreach($arPost as $key => $value):?>
			<?php if(!preg_match("|OPENID_IDENTITY|", $key)):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?php endif;?>
		<?php endforeach?>
		<input type="hidden" name="auth_service_id" value="" />
	</form>
</div>

<?php if($arParams["POPUP"]):?>
</div>
</div>
<?php endif?>