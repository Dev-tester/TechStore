<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

$bodyClasses = 'pagetitle-toolbar-field-view no-hidden no-background no-all-paddings';
$APPLICATION->setPageProperty('BodyClass', trim(sprintf('%s %s', $bodyClass, $bodyClasses)));

\Bitrix\Main\UI\Extension::load(array("ui.alerts"));

if (!$arResult['SLIDER'])
{
	$this->setViewTarget("inside_pagetitle", 10);
}
?>

<div class="pagetitle-container pagetitle-flexible-space">
	<?php 
	$APPLICATION->IncludeComponent(
		'bitrix:main.ui.filter',
		'',
		array(
			'FILTER_ID'				=> $arResult["FILTER"]["FILTER_ID"],
			'FILTER'				=> $arResult['FILTER']['FILTER'],
			'FILTER_PRESETS'		=> $arResult['FILTER']['FILTER_PRESETS'],
			'DISABLE_SEARCH'		=> true,
			'ENABLE_LABEL'			=> true,
			'RESET_TO_DEFAULT_MODE'	=> true,
		),
		$component
	);
	?>
</div>

<?php 
if (!$arResult['SLIDER'])
{
	$this->endViewTarget();
}
?>
<div class="rest-mp-installed" id="mp-installed-block">
	<?php 
	if ($arResult["AJAX_MODE"])
	{
		$APPLICATION->RestartBuffer();
	}

	if (is_array($arResult["ITEMS"]) && !empty($arResult["ITEMS"]))
	{
		foreach ($arResult["ITEMS"] as $app)
		{
			$appUrl = str_replace(
				array("#app#"),
				array(urlencode($app['CODE'])),
				$arParams['DETAIL_URL_TPL']
			);
			$arParamsApp = array(
				"CODE"    => $app["CODE"],
				"VERSION" => $app["VER"],
				"url"     => $appUrl,
			);
			$itemName = $app["NAME"] ? $app["NAME"] : $app["MENU_NAME"];
			?>
			<div class="rest-mp-installed-item">
				<?php //additional info
				if ($app["ACTIVE"] == "N" && $app["PUBLIC"] == "N")
				{
					?>
					<div class="ui-alert ui-alert-xs ui-alert-warning">
						<span class="ui-alert-message"><?=GetMessage("MARKETPLACE_APP_INSTALL_PARTNER")?></span>
					</div>
					<?php 
				}
				elseif ($app["ACTIVE"] == "Y" && is_array($app['APP_STATUS']) && $app['APP_STATUS']['PAYMENT_NOTIFY'] == 'Y')
				{
					if ($arResult['ADMIN'])
					{
						$app['APP_STATUS']['MESSAGE_SUFFIX'] .= '_A';
					}
					?>
					<div class="ui-alert ui-alert-xs ui-alert-warning">
						<span class="ui-alert-message">
							<?=GetMessage('PAYMENT_MESSAGE'.$app['APP_STATUS']['MESSAGE_SUFFIX'], $app['APP_STATUS']['MESSAGE_REPLACE']);?>
						</span>
					</div>
					<?php 
				}
				?>

				<div class="rest-mp-installed-item-img" style="background-size: cover; <?php if ($app["ICON"]):?>background-image: url('<?=$app["ICON"]?>')<?php endif;?>"></div>

				<div class="rest-mp-installed-item-content">
					<div class="rest-mp-installed-item-content-title"><a href="<?=$appUrl;?>"><?=htmlspecialcharsbx($itemName)?></a></div>
					<div class="rest-mp-installed-item-content-developer">
						<?php if (strlen($app["PARTNER_URL"]) > 0):?>
							<a href="<?=htmlspecialcharsbx($app["PARTNER_URL"])?>" target="_blank"><?=htmlspecialcharsbx($app["PARTNER_NAME"])?></a>
						<?php else:?>
							<?=htmlspecialcharsbx($app["PARTNER_NAME"])?>
						<?php endif?>
					</div>
				</div>

				<div class="rest-mp-installed-item-param">

						<?php 
						if ($app["PUBLIC"] == "Y" && is_array($app["PRICE"]) && !empty($app["PRICE"]) && $app["CAN_INSTALL"])
						{
						?>
							<div class="rest-mp-installed-item-param-content">
							<?php foreach ($app['BUY'] as $key => $price):?>
								<div class="rest-mp-installed-item-param-content-item">
									<input name="rest-mp-installed-price-<?=$app["CODE"]?>" type="radio" <?php if ($key == 0):?>checked="checked"<?php endif?> class="rest-mp-installed-item-param-checkbox" id="rest-mp-installed-price-<?=$app["CODE"]."-".$key?>">
									<label for="rest-mp-installed-price-<?=$app["CODE"]."-".$key?>" class="rest-mp-installed-item-param-label"><?=$price["TEXT"]?></label>
								</div>
								<script>
									BX.ready(function () {
										BX.bind(BX("rest-mp-installed-price-<?=$app["CODE"]."-".$key?>"), "change", BX.proxy(function () {
											if (BX("rest-mp-installed-price-<?=$app["CODE"]."-".$key?>").checked)
											{
												BX("rest-mp-installed-buy-<?=$app["CODE"]?>").href = this.link;
											}
										}, {link: "<?=$price["LINK"]?>"}));
									});
								</script>
							<?php endforeach;?>
							</div>
						<?php 
						}
						?>

						<div class="rest-mp-installed-item-param-buttons">
							<?php 
							if ($app["PUBLIC"] == "Y" && $app["CAN_INSTALL"]) //available in catalog
							{
								?>
								<?php if (is_array($app["PRICE"]) && !empty($app["PRICE"])):?>
									<a class="ui-btn ui-btn-sm ui-btn-primary ui-btn-round" id="rest-mp-installed-buy-<?=$app["CODE"]?>" href="<?=$app['BUY'][0]["LINK"]?>">
										<?=($app["STATUS"] == "P" && $app["DATE_FINISH"]) ? GetMessage("MARKETPLACE_APP_PROLONG") : GetMessage("MARKETPLACE_APP_BUY")?>
									</a>
								<?php endif;?>

								<?php 
								if ($app["ACTIVE"] == "N")
								{
									if (is_array($app["PRICE"]) && !empty($app["PRICE"]) && $app["PUBLIC"] == "Y")
									{
										if ($app["STATUS"] == "P")
										{
											?>
											<button class="ui-btn ui-btn-sm ui-btn-primary ui-btn-round" onclick="BX.rest.Marketplace.install(<?php echo CUtil::PhpToJSObject($arParamsApp) ?>);">
												<?=GetMessage("MARKETPLACE_INSTALL_BUTTON")?>
											</button>
											<?php 
										}
										else
										{
											if ($app["DEMO"] == "D"):?>
												<button class="ui-btn ui-btn-sm ui-btn-primary ui-btn-round" onclick="BX.rest.Marketplace.install(<?php echo CUtil::PhpToJSObject($arParamsApp) ?>);">
													<?=GetMessage("MARKETPLACE_APP_DEMO")?>
												</button>
											<?php  elseif ($app["DEMO"] == "T" && ($app["IS_TRIALED"] == "N" || MakeTimeStamp($app["DATE_FINISH"]) > time())):?>
												<button class="ui-btn ui-btn-sm ui-btn-primary ui-btn-round" onclick="BX.rest.Marketplace.install(<?php echo CUtil::PhpToJSObject($arParamsApp) ?>);">
													<?=GetMessage("MARKETPLACE_APP_TRIAL")?>
												</button>
											<?php endif;
										}
									}
									else
									{
										?>
										<button class="ui-btn ui-btn-sm ui-btn-primary ui-btn-round" onclick="BX.rest.Marketplace.install(<?=CUtil::PhpToJSObject($arParamsApp)?>);">
											<?=GetMessage("MARKETPLACE_INSTALL_BUTTON")?>
										</button>
										<?php 
									}
								}
								elseif (isset($app["UPDATES_AVAILABLE"]) && $app["UPDATES_AVAILABLE"] == "Y")
								{
									?>
									<button class="ui-btn ui-btn-sm ui-btn-primary ui-btn-round" onclick="BX.rest.Marketplace.install(<?=CUtil::PhpToJSObject($arParamsApp) ?>);">
										<?=GetMessage("MARKETPLACE_UPDATE_BUTTON")?>
									</button>
									<?php 
								}
							}
							?>

							<?php if ($app["ACTIVE"] == "Y" && $arResult['ADMIN']):?>
								<button class="ui-btn ui-btn-sm ui-btn-light-border ui-btn-round" onclick="BX.rest.Marketplace.uninstallConfirm('<?=CUtil::JSEscape($app["CODE"])?>')">
									<?=GetMessage("MARKETPLACE_DELETE_BUTTON")?>
								</button>

								<button class="ui-btn ui-btn-sm ui-btn-link ui-btn-round"
										onclick="BX.rest.Marketplace.setRights('<?=CUtil::JSEscape($app["ID"])?>');">
									<?=GetMessage("MARKETPLACE_RIGHTS")?>
								</button>
							<?php endif;?>
						</div>
				</div>

			</div>
		<?php 
		}//--foreach
		?>

		<script>
			BX.rest.Marketplace.bindPageAnchors({allowChangeHistory: true});
		</script>
	<?php 
	}
	else
	{
		echo GetMessage("MARKETPLACE_BUYS_EMPTY");
	}
	?>

	<?php 
	if ($arResult["AJAX_MODE"])
	{
		CMain::FinalActions();
		die();
	}
	?>
</div>

<?php 
$jsParams = array(
	"ajaxPath" => POST_FORM_ACTION_URI,
	"filterId" => isset($arResult["FILTER"]["FILTER_ID"]) ? $arResult["FILTER"]["FILTER_ID"] : ""
);
?>
<script>
	BX.ready(function () {
		BX.Rest.Markeplace.Installed.init(<?=CUtil::PhpToJSObject($jsParams)?>);
		BX.Rest.Markeplace.Installed.initEvents();
	});
</script>