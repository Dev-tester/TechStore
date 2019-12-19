<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

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
		if($arParams["~FOR_SPLIT"] == 'Y' && is_array($arParams["~AUTH_SERVICES"]) && count($arParams["~AUTH_SERVICES"]))
		{
			$servicesNum = 5;

			?>
			<div class="bx-auth-serv-icons" style="display:inline-block">
				<?php 
				$i = 0;
				foreach($arParams["~AUTH_SERVICES"] as $key=>$service)
				{
					$icon = $service["ICON"];
					if($icon == "bitrix24")
					{
						if(LANGUAGE_ID == "ru" || LANGUAGE_ID == "ua")
						{
							$icon .= '-'.LANGUAGE_ID;
						}
					}
					if ($i < $servicesNum)
					{
						if(($arParams["~FOR_SPLIT"] == 'Y') && (is_array($service["FORM_HTML"])))
							$onClickEvent = $service["FORM_HTML"]["ON_CLICK"];
						else
							$onClickEvent = "onclick=\"BxShowAuthService('".$service['ID']."', '".$arParams['SUFFIX']."')\"";
						?><a title="<?=htmlspecialcharsbx($service["NAME"])?>" href="javascript:void(0)" <?=$onClickEvent?> id="bx_auth_href_<?=$arParams["SUFFIX"]?><?=$service["ID"]?>"><span class="bx-auth-serv-icon <?=htmlspecialcharsbx($icon)?>"></span></a><?php 
					}
					else
					{
						if($i == $servicesNum)
						{
						?>
							<span class="login-social-networks-link-more" id="socservMoreButton"><?=GetMessage("socserv_more")?></span>

							<div id="moreSocServPopup" style="display: none; width:40px" class="bx-auth-serv-icons" onclick="BX.PopupWindowManager.getCurrentPopup().close();">
						<?php 
						}
						if(($arParams["~FOR_SPLIT"] == 'Y') && (is_array($service["FORM_HTML"])))
							$onClickEvent = $service["FORM_HTML"]["ON_CLICK"];
						else
							$onClickEvent = "onclick=\"BxShowAuthService('".$service['ID']."', '".$arParams['SUFFIX']."')\"";
						?>
						<a title="<?=htmlspecialcharsbx($service["NAME"])?>" href="javascript:void(0)" <?=$onClickEvent?> id="bx_auth_href_<?=$arParams["SUFFIX"]?><?=$service["ID"]?>">
							<span class="bx-auth-serv-icon <?=htmlspecialcharsbx($icon)?>"></span>
						</a>
						<br/>
					<?php 
					}

					$i++;
				}

				if($i > $servicesNum)
				{
				?>
							</div>
				<?php 
				}
				?>
			</div>
		<?php 
		}
		?>
		<div class="bx-auth" style="margin-bottom:0; margin-top:0">
			<form method="post" name="bx_auth_services<?=$arParams["SUFFIX"]?>" target="_top" action="<?=$arParams["AUTH_URL"]?>">
				<?php if($arParams["~SHOW_TITLES"] != 'N'):?>
					<div class="bx-auth-title"><?=GetMessage("socserv_as_user")?></div>
					<div class="bx-auth-note"><?=GetMessage("socserv_as_user_note")?></div>
				<?php endif;?>
				<?php /*if($arParams["~FOR_SPLIT"] != 'Y'):?>
		<div class="bx-auth-services">
		<?php foreach($arParams["~AUTH_SERVICES"] as $service):?>
			<div>
				<a href="javascript:void(0)" onclick="BxShowAuthService('<?=$service["ID"]?>', '<?=$arParams["SUFFIX"]?>')" id="bx_auth_href_<?=$arParams["SUFFIX"]?><?=$service["ID"]?>">
					<i class="bx-ss-icon <?=htmlspecialcharsbx($service["ICON"])?>"></i><b><?=htmlspecialcharsbx($service["NAME"])?></b>
				</a>
			</div>
		<?php endforeach?>
		</div>
		<?php endif;?>

		<?php if($arParams["~AUTH_LINE"] != 'N'):?>
			<div class="bx-auth-line"></div>
		<?php endif;*/?>
				<div class="bx-auth-service-form" id="bx_auth_serv<?=$arParams["SUFFIX"]?>" style="display:none; margin-top: 12px;margin-left: 20px;">
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
			</form>
		</div>

		<?php if($arParams["POPUP"]):?>
	</div>
</div>
<?php endif?>

