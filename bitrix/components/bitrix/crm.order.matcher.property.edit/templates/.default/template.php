<?php  if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

/**
 * @var CBitrixComponentTemplate $this
 * @var array $arParams
 * @var array $arResult
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Internals\Input;

function renderInput($input, $name, $property)
{
	$display = isset($input['NO_DISPLAY']) && $input['NO_DISPLAY'] === 'Y' ? ' style="display: none;"' : '';

	switch ($input['TYPE'])
	{
		case 'STRING':
			$input['CLASS'] = 'crm-entity-widget-content-input';
			?>
			<div class="crm-entity-widget-content-block crm-entity-widget-content-block-field-text"<?=$display?>>
				<div class="crm-entity-widget-content-block-title">
					<span class="crm-entity-widget-content-block-title-text">
						<?php 
						echo $input['LABEL'];

						if (isset($input['REQUIRED']) && $input['REQUIRED'] === 'Y')
						{
							?>
							<span style="color: #ff5752">*</span>
							<?php 
						}
						?>
					</span>
				</div>
				<div class="crm-entity-widget-content-block-inner">
					<?=Input\Manager::getEditHtml($name, $input, $property[$name]).$input['RLABEL']?>

					<?php 
					if ($input['DESCRIPTION'])
					{
						?>
						<small><?=$input['DESCRIPTION']?></small>
						<?php 
					}
					?>
				</div>
			</div>
			<?php 
			break;

		case 'NUMBER':
			$input['CLASS'] = 'crm-entity-widget-content-input';
			?>
			<div class="crm-entity-widget-content-block crm-entity-widget-content-block-field-number"<?=$display?>>
				<div class="crm-entity-widget-content-block-title">
					<span class="crm-entity-widget-content-block-title-text">
						<?php 
						echo $input['LABEL'];

						if (isset($input['REQUIRED']) && $input['REQUIRED'] === 'Y')
						{
							?>
							<span style="color: #ff5752">*</span>
							<?php 
						}
						?>
					</span>
				</div>
				<div class="crm-entity-widget-content-block-inner">
					<?=Input\Manager::getEditHtml($name, $input, $property[$name]).$input['RLABEL']?>

					<?php 
					if ($input['DESCRIPTION'])
					{
						?>
						<small><?=$input['DESCRIPTION']?></small>
						<?php 
					}
					?>
				</div>
			</div>
			<?php 
			break;

		case 'Y/N':
			?>
			<div class="crm-entity-widget-content-block"<?=$display?>>
				<div class="crm-entity-widget-content-block-title">
					<span class="crm-entity-widget-content-block-title-text">
						<?php 
						echo $input['LABEL'];

						if (isset($input['REQUIRED']) && $input['REQUIRED'] === 'Y')
						{
							?>
							<span style="color: #ff5752">*</span>
							<?php 
						}
						?>
					</span>
				</div>
				<div class="crm-entity-widget-content-block-inner">
					<?=Input\Manager::getEditHtml($name, $input, $property[$name]).$input['RLABEL']?>

					<?php 
					if ($input['DESCRIPTION'])
					{
						?>
						<small><?=$input['DESCRIPTION']?></small>
						<?php 
					}
					?>
				</div>
			</div>
			<?php 
			break;

		case 'ENUM':
			$input['CLASS'] = 'crm-entity-widget-content-custom-select';
			?>
			<div class="crm-entity-widget-content-block crm-entity-widget-content-block-field-select"<?=$display?>>
				<div class="crm-entity-widget-content-block-title">
					<span class="crm-entity-widget-content-block-title-text">
						<?php 
						echo $input['LABEL'];

						if (isset($input['REQUIRED']) && $input['REQUIRED'] === 'Y')
						{
							?>
							<span style="color: #ff5752">*</span>
							<?php 
						}
						?>
					</span>
				</div>
				<div class="crm-entity-widget-content-block-inner crm-entity-widget-content-block-select">
					<?=Input\Manager::getEditHtml($name, $input, $property[$name]).$input['RLABEL']?>

					<?php 
					if ($input['DESCRIPTION'])
					{
						?>
						<small><?=$input['DESCRIPTION']?></small>
						<?php 
					}
					?>
				</div>
			</div>
			<?php 
			break;

		case 'FILE':
			?>
			<div class="crm-entity-widget-content-block crm-entity-widget-content-block-field-file"<?=$display?>>
				<div class="crm-entity-widget-content-block-title">
					<span class="crm-entity-widget-content-block-title-text">
						<?php 
						echo $input['LABEL'];

						if (isset($input['REQUIRED']) && $input['REQUIRED'] === 'Y')
						{
							?>
							<span style="color: #ff5752">*</span>
							<?php 
						}
						?>
					</span>
				</div>
				<div class="crm-entity-widget-content-block-inner">
					<?=Input\Manager::getEditHtml($name, $input, $property[$name]).$input['RLABEL']?>

					<?php 
					if ($input['DESCRIPTION'])
					{
						?>
						<small><?=$input['DESCRIPTION']?></small>
						<?php 
					}
					?>
				</div>
			</div>
			<?php 
			break;

		case 'DATE':
			$input['CLASS'] = 'crm-entity-widget-content-input';
			?>
			<div class="crm-entity-widget-content-block crm-entity-widget-content-block-field-date"<?=$display?>>
				<div class="crm-entity-widget-content-block-title">
					<span class="crm-entity-widget-content-block-title-text">
						<?php 
						echo $input['LABEL'];

						if (isset($input['REQUIRED']) && $input['REQUIRED'] === 'Y')
						{
							?>
							<span style="color: #ff5752">*</span>
							<?php 
						}
						?>
					</span>
				</div>
				<div class="crm-entity-widget-content-block-inner">
					<?=Input\Manager::getEditHtml($name, $input, $property[$name]).$input['RLABEL']?>

					<?php 
					if ($input['DESCRIPTION'])
					{
						?>
						<small><?=$input['DESCRIPTION']?></small>
						<?php 
					}
					?>
				</div>
			</div>
			<?php 
			break;

		case 'LOCATION':
			?>
			<div class="crm-entity-widget-content-block crm-entity-widget-content-block-field-text"<?=$display?>>
				<div class="crm-entity-widget-content-block-title">
					<span class="crm-entity-widget-content-block-title-text">
						<?php 
						echo $input['LABEL'];

						if (isset($input['REQUIRED']) && $input['REQUIRED'] === 'Y')
						{
							?>
							<span style="color: #ff5752">*</span>
							<?php 
						}
						?>
					</span>
				</div>
				<div class="crm-entity-widget-content-block-inner">
					<?=Input\Manager::getEditHtml($name, $input, $property[$name]).$input['RLABEL']?>

					<?php 
					if ($input['DESCRIPTION'])
					{
						?>
						<small><?=$input['DESCRIPTION']?></small>
						<?php 
					}
					?>
				</div>
			</div>
			<?php 
			break;
	}
}

CUtil::InitJSCore();
$this->addExternalCss('/bitrix/components/bitrix/crm.entity.editor/templates/.default/style.css');

if (\Bitrix\Main\Loader::includeModule('bitrix24'))
{
	CBitrix24::initLicenseInfoPopupJS();
}

\Bitrix\Main\UI\Extension::load('ui.buttons');

$property = &$arResult['PROPERTY'];

$typeIndex = array_search('TYPE', array_keys($arResult['PROPERTY_SETTINGS']));
$leftBlockItems = array_slice($arResult['PROPERTY_SETTINGS'], 0, $typeIndex);
$rightBlockItems = array_slice($arResult['PROPERTY_SETTINGS'], $typeIndex);
?>
<form method="POST" action="<?=$APPLICATION->GetCurPage()?>" name="form1" id="crm-order-prop" enctype="multipart/form-data" class="bx-step-opacity">
	<!-- form-reload-container -->
	<?php 
	if (!empty($arResult['ERRORS']))
	{
		?>
		<div id="bx-crm-error" class="crm-property-edit-top-block">
			<?php 
			foreach ($arResult['ERRORS'] as $error)
			{
				?>
				<div class="crm-entity-widget-content-error-text">
					<?=$error?>
				</div>
				<?php 
			}
			?>
			<script>
				BX.scrollToNode(BX('bx-crm-error'));
			</script>
		</div>
		<?php 
	}
	?>
	<input type="hidden" name="PREVIOUS-TYPE" value="<?=htmlspecialcharsbx($property['TYPE'])?>">
	<?=bitrix_sessid_post()?>

	<table class="crm-table">
		<tr>
			<td class="crm-table-left-column">
				<?php 
				if (!empty($arResult['MATCH_CODE']))
				{
					?>
					<input type="hidden" name="MATCH_CODE" value="<?=$arResult['MATCH_CODE']?>">
					<?php 
				}

				$matched = !empty($arResult['MATCH_SETTINGS']) || $arResult['PROPERTY']['MATCHED'] === 'Y' ? 'Y' : 'N';
				?>
				<input type="hidden" name="MATCHED" value="<?=$matched?>">
				<?php 
				if (!empty($arResult['MATCH_SETTINGS']))
				{
					?>
					<div class="crm-entity-card-container" style="width: 100%">
						<div class="crm-entity-card-container-content">
							<div class="crm-entity-card-widget">
								<div class="crm-entity-card-widget-title">
								<span class="crm-entity-card-widget-title-text">
									<?=Loc::getMessage('MATCH_TITLE')?><span class="crm-orderform-linked"></span>
								</span>
								</div>
								<div class="crm-entity-widget-content">
									<?php 
									foreach ($arResult['MATCH_SETTINGS'] as $name => $input)
									{
										renderInput($input, $name, $property);
									}
									?>
								</div>
							</div>
						</div>
					</div>
					<?php 
				}
				?>
				<div class="crm-entity-card-container" style="width: 100%">
					<div class="crm-entity-card-container-content">
						<div class="crm-entity-card-widget">
							<div class="crm-entity-card-widget-title">
								<span class="crm-entity-card-widget-title-text">
									<?=Loc::getMessage('PROPERTY_TITLE')?>
								</span>
							</div>
							<div class="crm-entity-widget-content">
								<?php 
								foreach ($leftBlockItems as $name => $input)
								{
									renderInput($input, $name, $property);
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</td>
			<td class="crm-table-right-column">
				<div class="crm-entity-stream-container" style="width: 100%; vertical-align: top; margin-bottom: 50px;">
					<div class="crm-entity-card-widget">
						<div class="crm-entity-card-widget-title">
							<span class="crm-entity-card-widget-title-text">
								<?=Loc::getMessage('TYPE_TITLE')?>
							</span>
						</div>
						<div class="crm-entity-widget-content">
							<?php 
							foreach ($rightBlockItems as $name => $input)
							{
								renderInput($input, $name, $property);
							}
							?>
						</div>
					</div>
					<?php 
					if ($property['TYPE'] === 'ENUM')
					{
						$variants = $property['VARIANTS'];
						$variantSettings = array_diff_key($arResult['VARIANT_SETTINGS'], ['DESCRIPTION' => 1]);
						?>
						<div class="crm-entity-card-widget">
							<div class="crm-entity-card-widget-title">
							<span class="crm-entity-card-widget-title-text">
								<?=Loc::getMessage('VARIANT_TITLE')?>
							</span>
							</div>
							<div class="crm-entity-widget-content">
								<div class="crm-entity-widget-content-block">
									<table class="crm-table">
										<tr>
											<?php 
											foreach ($variantSettings as $input)
											{
												?>
												<td>
													<?php 
													if ($input['HIDDEN'] !== 'Y')
													{
														echo $input['LABEL'];
													}
													?>
												</td>
												<?php 
											}

											if ($arResult['CAN_EDIT_VARIANTS'])
											{
												?>
												<td align="center">
													<?=Loc::getMessage('SALE_VARIANTS_DEL')?>
												</td>
												<?php 
											}
											?>
										</tr>
										<?php 
										if ($arResult['CAN_EDIT_VARIANTS'])
										{
											for ($index = 0; $index < 5; $index++)
											{
												$variants[] = [];
											}
										}

										$index = 0;
										foreach ($variants as $variant)
										{
											++$index;
											?>
											<tr>
												<?php 
												foreach ($variantSettings as $name => $input)
												{
													$input['REQUIRED'] = 'N';
													$input['CLASS'] = 'crm-entity-widget-content-input';
													?>
													<td>
														<?=Input\Manager::getEditHtml("VARIANTS[$index][$name]", $input, $variant[$name])?>
													</td>
													<?php 
												}

												if ($arResult['CAN_EDIT_VARIANTS'])
												{
													?>
													<td align="center">
														<input type="checkbox" name="VARIANTS[<?=$index?>][DELETE]" value="Y">
													</td>
													<?php 
												}
												?>
											</tr>
											<?php 
										}
										?>
									</table>
								</div>
							</div>
						</div>
						<?php 
					}
					?>
				</div>
			</td>
		</tr>
	</table>
	<?php 
	if ($arParams['IFRAME'])
	{
		?>
		<div class="crm-footer-container">
			<div class="crm-entity-section-control">
				<a id="CRM_ORDER_PROPERTY_APPLY_BUTTON" class="ui-btn ui-btn-success">
					<?=Loc::getMessage('CRM_ORDER_PROPERTY_EDIT_BUTTON_SAVE')?>
				</a>
				<a id="CRM_ORDER_PROPERTY_CANCEL" class="ui-btn ui-btn-link">
					<?=Loc::getMessage('CRM_ORDER_PROPERTY_EDIT_BUTTON_CANCEL')?>
				</a>
			</div>
		</div>
		<?php 
	}
	else
	{
		?>
		<div>
			<a id="CRM_ORDER_PROPERTY_SUBMIT_BUTTON" class="ui-btn ui-btn-success">
				<?=Loc::getMessage('CRM_ORDER_PROPERTY_EDIT_BUTTON_APPLY')?>
			</a>
		</div>
		<?php 
	}
	?>
	<div style="display: none">
		<?php 
		global $APPLICATION;
		// load styles and scripts
		$APPLICATION->IncludeComponent(
			'bitrix:sale.location.selector.steps',
			'.default',
			array(),
			false
		);
		$APPLICATION->IncludeComponent(
			'bitrix:sale.location.selector.search',
			'.default',
			array(),
			false
		);
		?>
	</div>
	<?php 
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'crm.order.matcher.property.edit');
	?>
	<script>
		// this variable is used for "orderPropertyConfig.reloadAction()"
		var orderPropertyConfig = new BX.Config.Order.Property({
			params: <?=CUtil::PhpToJSObject($arParams)?>,
			signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
			actionRequestUrl: '<?=CUtil::JSEscape($this->getComponent()->getPath())?>/ajax.php'
		});
	</script>
	<!-- form-reload-container -->
</form>