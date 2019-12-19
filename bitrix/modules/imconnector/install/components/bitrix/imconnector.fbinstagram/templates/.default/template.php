<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Localization\Loc;
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
/** $arResult["CONNECTION_STATUS"]; */
/** $arResult["REGISTER_STATUS"]; */
/** $arResult["ERROR_STATUS"]; */
/** $arResult["SAVE_STATUS"]; */

Loc::loadMessages(__FILE__);

if ($arParams['INDIVIDUAL_USE'] != 'Y')
{
	$this->addExternalCss('/bitrix/components/bitrix/imconnector.settings/templates/.default/style.css');
	$this->addExternalJs('/bitrix/components/bitrix/imconnector.settings/templates/.default/script.js');
	\Bitrix\Main\UI\Extension::load("ui.buttons");
	\Bitrix\Main\UI\Extension::load("ui.hint");
	\Bitrix\ImConnector\Connector::initIconCss();
}

$iconCode = \Bitrix\ImConnector\Connector::getIconByConnector($arResult["CONNECTOR"]);
?>
	<form action="<?=$arResult["URL"]["DELETE"]?>" method="post" id="form_delete_<?=$arResult["CONNECTOR"]?>">
		<input type="hidden" name="<?=$arResult["CONNECTOR"]?>_form" value="true">
		<input type="hidden" name="<?=$arResult["CONNECTOR"]?>_del" value="Y">
		<?=bitrix_sessid_post();?>
	</form>
<?php 
if (empty($arResult['PAGE']) && $arResult['ACTIVE_STATUS'])
{
	if ($arResult['STATUS'])
	{
		?>
		<div class="imconnector-field-container">
			<div class="imconnector-field-section imconnector-field-section-social">
				<div class="imconnector-field-box">
					<div class="connector-icon ui-icon ui-icon-service-<?=$iconCode?>"><i></i></div>
				</div>
				<div class="imconnector-field-box">
					<div class="imconnector-field-main-subtitle">
						<?=Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_CONNECTED')?>
					</div>
					<div class="imconnector-field-box-content">
						<?=Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_CHANGE_ANY_TIME')?>
					</div>
					<div class="ui-btn-container">
						<a href="<?=$arResult["URL"]["SIMPLE_FORM_EDIT"]?>" class="ui-btn ui-btn-primary show-preloader-button">
							<?=Loc::getMessage('IMCONNECTOR_COMPONENT_SETTINGS_CHANGE_SETTING')?>
						</a>
						<button class="ui-btn ui-btn-light-border"
								onclick="popupShow(<?=CUtil::PhpToJSObject($arResult["CONNECTOR"])?>)">
							<?=Loc::getMessage('IMCONNECTOR_COMPONENT_SETTINGS_DISABLE')?>
						</button>
					</div>
				</div>
			</div>
		</div>
		<?php include 'messages.php'?>
		<div class="imconnector-field-container">
			<div class="imconnector-field-section">
				<div class="imconnector-field-main-title">
					<?=Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_INFO')?>
				</div>
				<div class="imconnector-field-box">
					<?php 
					if (!empty($arResult["FORM"]["USER"]["INFO"]["URL"]))
					{
						?>
						<div class="imconnector-field-box-entity-row">
							<div class="imconnector-field-box-subtitle">
								<?=Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_USER')?>
							</div>
							<a href="<?=$arResult["FORM"]["USER"]["INFO"]["URL"]?>"
							   target="_blank"
							   class="imconnector-field-box-entity-link">
								<?=$arResult["FORM"]["USER"]["INFO"]["NAME"]?>
							</a>
							<span class="imconnector-field-box-entity-icon-copy-to-clipboard"
								  data-text="<?=CUtil::JSEscape($arResult["FORM"]["USER"]["INFO"]["URL"])?>"></span>
						</div>
						<?php 
					}
					?>
					<?php 
					if (!empty($arResult["FORM"]["PAGE"]["URL"]))
					{
						?>
						<div class="imconnector-field-box-entity-row">
							<div class="imconnector-field-box-subtitle">
								<?=Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_CONNECTED_PAGE')?>
							</div>
							<?php if(empty($arResult["FORM"]["PAGE"]["INSTAGRAM"]["URL"])):?>
							<span class="imconnector-field-box-entity-link">
							<?php else:?>
							<a href="<?= $arResult["FORM"]["PAGE"]["INSTAGRAM"]["URL"] ?>"
								target="_blank"
							   class="imconnector-field-box-entity-link">
							<?php endif;?>
								<?= $arResult["FORM"]["PAGE"]["INSTAGRAM"]["NAME"] ?> <?php if(!empty($arResult["FORM"]["PAGE"]["INSTAGRAM"]["MEDIA_COUNT"])):?> (<?=$arResult["FORM"]["PAGE"]["INSTAGRAM"]["MEDIA_COUNT"];?> <?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_MEDIA') ?>)<?php endif;?>
							<?php if(empty($arResult["FORM"]["PAGE"]["INSTAGRAM"]["URL"])):?>
							</span>
							<?php else:?>
							</a>
							<?php endif;?>
						</div>
						<div class="imconnector-field-box-entity-row">
							<div class="imconnector-field-box-subtitle"><?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_PREFIX_NAMING_PAGE') ?></div>

							<a href="<?=$arResult["FORM"]["PAGE"]["URL"]?>"
							   target="_blank"
							   class="imconnector-field-box-entity-link">
								<?=$arResult["FORM"]["PAGE"]["NAME"]?>
							</a>
							<?php if(empty($arResult["FORM"]["PAGE"]["INSTAGRAM"]["URL"])):?>
								<span class="imconnector-field-box-entity-icon-copy-to-clipboard"
									  data-text="<?=CUtil::JSEscape($arResult["FORM"]["PAGE"]["INSTAGRAM"]["URL"])?>"></span>
							<?php endif;?>
						</div>
						<?php 
					}
					?>
				</div>
			</div>
		</div>
		<?php 
	}
	else
	{
		?>
		<div class="imconnector-field-container">
			<div class="imconnector-field-section imconnector-field-section-social">
				<div class="imconnector-field-box">
					<div class="connector-icon ui-icon ui-icon-service-<?=$iconCode?>"><i></i></div>
				</div>
				<div class="imconnector-field-box">
					<div class="imconnector-field-main-subtitle">
						<?=$arResult['NAME']?>
					</div>
					<div class="imconnector-field-box-content">
						<?=Loc::getMessage('IMCONNECTOR_COMPONENT_SETTINGS_SETTING_IS_NOT_COMPLETED')?>
					</div>
					<div class="ui-btn-container">
						<a href="<?=$arResult["URL"]["SIMPLE_FORM_EDIT"]?>"
						   class="ui-btn ui-btn-primary show-preloader-button">
							<?=Loc::getMessage('IMCONNECTOR_COMPONENT_SETTINGS_CONTINUE_WITH_THE_SETUP')?>
						</a>
						<button class="ui-btn ui-btn-light-border"
								onclick="popupShow(<?=CUtil::PhpToJSObject($arResult["CONNECTOR"])?>)">
							<?=Loc::getMessage('IMCONNECTOR_COMPONENT_SETTINGS_DISABLE')?>
						</button>
					</div>
				</div>
			</div>
		</div>
		<?php include 'messages.php'?>
		<div class="imconnector-field-container">
			<div class="imconnector-field-section">
				<?php include 'connection-help.php';?>
			</div>
		</div>
		<?php 
	}
}
else
{
	if (empty($arResult['FORM']['USER']['INFO'])) //start case with clear connections
	{
		?>
		<div class="imconnector-field-container">
			<div class="imconnector-field-section imconnector-field-section-social">
				<div class="imconnector-field-box">
					<div class="connector-icon ui-icon ui-icon-service-<?=$iconCode?>"><i></i></div>
				</div>
				<div class="imconnector-field-box">
					<div class="imconnector-field-main-subtitle">
						<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_TITLE') ?>
					</div>
					<div class="imconnector-field-box-content">
						<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_DESCRIPTION') ?>
					</div>
				</div>
			</div>
		</div>
		<?php include 'messages.php'?>
		<?php 
		if ($arResult['ACTIVE_STATUS']) //case before auth to fb
		{
			?>
			<div class="imconnector-field-container">
				<div class="imconnector-field-section">
					<div class="imconnector-field-main-title">
						<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_AUTHORIZATION') ?>
					</div>
					<div class="imconnector-field-box">
						<div class="imconnector-field-box-content">
							<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_LOG_IN_UNDER_AN_ADMINISTRATOR_ACCOUNT_PAGE') ?>
						</div>
					</div>
					<?php 
					if ($arResult['FORM']['USER']['URI'] != '')
					{
						?>
						<div class="imconnector-field-social-connector">
							<div class="connector-icon ui-icon ui-icon-service-<?=$iconCode?> imconnector-field-social-connector-icon"><i></i></div>
							<div class="ui-btn ui-btn-light-border"
								 onclick="BX.util.popup('<?= $arResult['FORM']['USER']['URI'] ?>', 700, 525)">
								<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_AUTHORIZE') ?>
							</div>
						</div>
						<?php 
					}
					?>
				</div>
			</div>
			<?php 
		}
		else
		{    //case before start connecting to fb
			?>
			<div class="imconnector-field-container">
				<div class="imconnector-field-section">
					<div class="imconnector-field-main-title">
						<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_LOG_IN_UNDER_AN_ADMINISTRATOR_ACCOUNT_PAGE') ?>
					</div>
					<div class="imconnector-field-box">
						<div class="imconnector-field-box-content">
							<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_INDEX_DESCRIPTION') ?>
						</div>
					</div>
					<div class="imconnector-field-social-connector">
						<div class="connector-icon ui-icon ui-icon-service-<?=$iconCode?> imconnector-field-social-connector-icon"><i></i></div>
						<form action="<?= $arResult["URL"]["SIMPLE_FORM"] ?>" method="post">
							<input type="hidden" name="<?= $arResult["CONNECTOR"] ?>_form" value="true">
							<?= bitrix_sessid_post(); ?>
							<button class="ui-btn ui-btn-light-border"
									name="<?= $arResult["CONNECTOR"] ?>_active"
									type="submit"
									value="<?= Loc::getMessage('IMCONNECTOR_COMPONENT_SETTINGS_TO_CONNECT') ?>">
								<?= Loc::getMessage('IMCONNECTOR_COMPONENT_SETTINGS_TO_CONNECT') ?>
							</button>
						</form>
					</div>
				</div>
			</div>
			<?php 
		}
		?>
		<div class="imconnector-field-container">
			<div class="imconnector-field-section">
				<?php include 'connection-help.php';?>
			</div>
		</div>
		<?php 
	}
	else
	{
		?>
		<div class="imconnector-field-container">
			<div class="imconnector-field-section imconnector-field-section-social">
				<div class="imconnector-field-box">
					<div class="connector-icon ui-icon ui-icon-service-<?=$iconCode?>"><i></i></div>
				</div>
				<div class="imconnector-field-box">
					<div class="imconnector-field-main-subtitle">
						<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_CONNECTED') ?>
					</div>
					<div class="imconnector-field-social-card">
						<div class="imconnector-field-social-card-info">
							<div class="imconnector-field-social-icon"></div>
							<?php if(empty($arResult['FORM']['USER']['INFO']['URL'])):?>
								<span class="imconnector-field-social-name">
							<?php else:?>
								<a href="<?= $arResult['FORM']['USER']['INFO']['URL'] ?>"
									target="_blank"
									 class="imconnector-field-social-name">
							<?php endif;?>
								<?= $arResult['FORM']['USER']['INFO']['NAME'] ?>
							<?php if(empty($arResult['FORM']['USER']['INFO']['URL'])):?>
								</span>
							<?php else:?>
								</a>
							<?php endif;?>
						</div>
						<div class="ui-btn ui-btn-sm ui-btn-light-border imconnector-field-social-card-button"
							 onclick="popupShow(<?= CUtil::PhpToJSObject($arResult["CONNECTOR"]) ?>)">
							<?= Loc::getMessage('IMCONNECTOR_COMPONENT_SETTINGS_DISABLE') ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include 'messages.php'?>
		<?php 
		if (empty($arResult['FORM']['PAGES']))  //case user haven't got any groups.
		{
			?>
			<div class="imconnector-field-container">
				<div class="imconnector-field-section">
					<div class="imconnector-field-main-title">
						<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_CONNECT_FACEBOOK_PAGE') ?>
					</div>
					<div class="imconnector-field-box">
						<div class="imconnector-field-box-content">
							<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_THERE_IS_NO_PAGE_WHERE_THE_ADMINISTRATOR') ?>
						</div>
					</div>
					<div class="imconnector-field-social-connector">
						<div class="connector-icon ui-icon ui-icon-service-<?=$iconCode?> imconnector-field-social-connector-icon"><i></i></div>
						<a href="https://www.facebook.com/pages/create/"
						   class="ui-btn ui-btn-light-border"
						   target="_blank">
							<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_TO_CREATE_A_PAGE') ?>
						</a>
					</div>
				</div>
			</div>
			<div class="imconnector-field-container">
				<div class="imconnector-field-section">
					<?php include 'connection-help.php';?>
				</div>
			</div>
			<?php 
		}
		else
		{
			if (empty($arResult['FORM']['PAGE'])) //case user haven't choose active group yet
			{
				?>
				<div class="imconnector-field-container">
					<div class="imconnector-field-section imconnector-field-section-social-list">
						<div class="imconnector-field-main-title">
							<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_SELECT_THE_PAGE') ?>
						</div>
						<div class="imconnector-field-social-list">
							<?php 
							foreach ($arResult['FORM']['PAGES'] as $page)
							{
								if (empty($page['ACTIVE']))
								{
									?>
									<div class="imconnector-field-social-list-item">
										<div class="imconnector-field-social-list-inner">
											<div class="imconnector-field-social-icon imconnector-field-social-list-icon"<?php if(!empty($page["INFO"]["INSTAGRAM"]["PROFILE_PICTURE_URL"])):?> style='background: url("<?=$page["INFO"]["INSTAGRAM"]["PROFILE_PICTURE_URL"]?>"); background-size: cover'<?php endif;?>></div>
											<div class="imconnector-field-social-list-info">
											<?php if(empty($page["INFO"]["INSTAGRAM"]["URL"])):?>
											<span class="imconnector-field-social-name">
											<?php else:?>
											<a href="<?= $page["INFO"]["INSTAGRAM"]["URL"] ?>"
												target="_blank"
												class="imconnector-field-social-name">
											<?php endif;?>
												<?= $page["INFO"]["INSTAGRAM"]["NAME"] ?> <?php if(!empty($page["INFO"]["INSTAGRAM"]["MEDIA_COUNT"])):?> (<?=$page["INFO"]["INSTAGRAM"]["MEDIA_COUNT"];?> <?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_MEDIA') ?>)<?php endif;?>
											<?php if(empty($page["INFO"]["INSTAGRAM"]["URL"])):?>
												</span>
											<?php else:?>
												</a>
											<?php endif;?>

											<span class="imconnector-field-social-name imconnector-field-social-name-text"><?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_PREFIX_NAMING_PAGE') ?></span>

											<?php if(empty($page["INFO"]["URL"])):?>
												<span class="imconnector-field-social-name">
											<?php else:?>
												<a href="<?= $page["INFO"]["URL"] ?>"
													target="_blank"
													class="imconnector-field-social-name">
											<?php endif;?>
											<?= $page["INFO"]["NAME"] ?>
											<?php if(empty($page["INFO"]["URL"])):?>
												</span>
											<?php else:?>
												</a>
											<?php endif;?>
											</div>
										</div>
										<form action="<?= $arResult["URL"]["SIMPLE_FORM"] ?>" method="post">
											<input type="hidden" name="<?= $arResult["CONNECTOR"] ?>_form" value="true">
											<input type="hidden" name="page_id" value="<?= $page["INFO"]["ID"] ?>">
											<?= bitrix_sessid_post(); ?>
											<button type="submit"
													name="<?= $arResult["CONNECTOR"] ?>_authorization_page"
													class="ui-btn ui-btn-sm ui-btn-light-border"
													value="<?= Loc::getMessage('IMCONNECTOR_COMPONENT_SETTINGS_TO_CONNECT') ?>">
												<?= Loc::getMessage('IMCONNECTOR_COMPONENT_SETTINGS_TO_CONNECT') ?>
											</button>
										</form>
									</div>
									<?php 
								}
							}
							?>
						</div>
					</div>
				</div>
				<div class="imconnector-field-container">
					<div class="imconnector-field-section">
						<?php include 'connection-help.php';?>
					</div>
				</div>
				<?php 
			}
			else
			{
				?>
				<div class="imconnector-field-container">
					<div class="imconnector-field-section">
						<div class="imconnector-field-main-title imconnector-field-main-title-no-border">
							<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_CONNECTED_PAGE') ?>
						</div>

						<div class="imconnector-field-social-card">
							<div class="imconnector-field-social-card-info">
								<div<?php if(!empty($arResult['FORM']['PAGE']["INSTAGRAM"]["PROFILE_PICTURE_URL"])):?> class="imconnector-field-social-icon imconnector-field-social-list-icon" style='background: url("<?=$arResult['FORM']['PAGE']["INSTAGRAM"]["PROFILE_PICTURE_URL"]?>"); background-size: cover'<?php else:?> class="connector-icon ui-icon ui-icon-service-<?=$iconCode?> imconnector-field-social-icon"<?php endif;?>><i></i></div>

								<div class="imconnector-field-social-list-info">
									<?php if(empty($arResult['FORM']['PAGE']["INSTAGRAM"]["URL"])):?>
									<span class="imconnector-field-social-name">
									<?php else:?>
									<a href="<?= $arResult['FORM']['PAGE']["INSTAGRAM"]["URL"] ?>"
										target="_blank"
										class="imconnector-field-social-name">
									<?php endif;?>
									<?= $arResult['FORM']['PAGE']["INSTAGRAM"]["NAME"] ?> <?php if(!empty($arResult['FORM']['PAGE']["INSTAGRAM"]["MEDIA_COUNT"])):?> (<?=$arResult['FORM']['PAGE']["INSTAGRAM"]["MEDIA_COUNT"];?> <?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_MEDIA') ?>)<?php endif;?>
									<?php if(empty($arResult['FORM']['PAGE']["INSTAGRAM"]["URL"])):?>
									</span>
									<?php else:?>
									</a>
									<?php endif;?>

									<span class="imconnector-field-social-name imconnector-field-social-name-text"><?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_PREFIX_NAMING_PAGE') ?></span>

									<?php if(empty($arResult['FORM']['PAGE']['URL'])):?>
									<span class="imconnector-field-social-name">
									<?php else:?>
									<a href="<?= $arResult['FORM']['PAGE']['URL'] ?>"
										target="_blank"
										class="imconnector-field-social-name">
									<?php endif;?>
									<?= $arResult['FORM']['PAGE']['NAME'] ?>
									<?php if(empty($arResult['FORM']['PAGE']['URL'])):?>
									</span>
									<?php else:?>
									</a>
									<?php endif;?>
								</div>

							</div>
							<form action="<?= $arResult["URL"]["SIMPLE_FORM"] ?>" method="post">
								<input type="hidden" name="<?= $arResult["CONNECTOR"] ?>_form" value="true">
								<input type="hidden" name="page_id" value="<?= $arResult["FORM"]["PAGE"]["ID"] ?>">
								<?= bitrix_sessid_post(); ?>
								<button class="ui-btn ui-btn-sm ui-btn-light-border imconnector-field-social-card-button"
										name="<?= $arResult["CONNECTOR"] ?>_del_page"
										type="submit"
										value="<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_DEL_REFERENCE') ?>">
									<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_DEL_REFERENCE') ?>
								</button>
							</form>
						</div>

						<?php 
						if (count($arResult['FORM']['PAGES']) > 1)
						{
							?>
							<div class="imconnector-field-dropdown-button" id="toggle-list">
								<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_OTHER_PAGES') ?>
							</div>

							<div class="imconnector-field-box imconnector-field-social-list-modifier imconnector-field-box-hidden"
								 id="hidden-list">
								<div class="imconnector-field-main-title">
									<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_OTHER_PAGES') ?>
								</div>
								<div class="imconnector-field-social-list">
									<?php 
									foreach ($arResult['FORM']['PAGES'] as $page)
									{
										if (empty($page['ACTIVE']))
										{
											?>
											<div class="imconnector-field-social-list-item">
												<div class="imconnector-field-social-list-inner">
													<div class="imconnector-field-social-icon imconnector-field-social-list-icon"<?php if(!empty($page["INFO"]["INSTAGRAM"]["PROFILE_PICTURE_URL"])):?> style='background: url("<?=$page["INFO"]["INSTAGRAM"]["PROFILE_PICTURE_URL"]?>"); background-size: cover'<?php endif;?>></div>

													<div class="imconnector-field-social-list-info">
														<?php if(empty($page["INFO"]["INSTAGRAM"]["URL"])):?>
														<span class="imconnector-field-social-name">
														<?php else:?>
														<a href="<?= $page["INFO"]["INSTAGRAM"]["URL"] ?>"
															target="_blank"
															class="imconnector-field-social-name">
														<?php endif;?>
														<?= $page["INFO"]["INSTAGRAM"]["NAME"] ?> <?php if(!empty($page["INFO"]["INSTAGRAM"]["MEDIA_COUNT"])):?> (<?=$page["INFO"]["INSTAGRAM"]["MEDIA_COUNT"];?> <?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_MEDIA') ?>)<?php endif;?>
														<?php if(empty($page["INFO"]["INSTAGRAM"]["URL"])):?>
														</span>
														<?php else:?>
														</a>
														<?php endif;?>

														<span class="imconnector-field-social-name imconnector-field-social-name-text"><?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_PREFIX_NAMING_PAGE') ?></span>

														<?php if(empty($page["INFO"]["URL"])):?>
														<span class="imconnector-field-social-name">
														<?php else:?>
														<a href="<?= $page["INFO"]["URL"] ?>"
															target="_blank"
															class="imconnector-field-social-name">
														<?php endif;?>
														<?= $page["INFO"]["NAME"] ?>
														<?php if(empty($page["INFO"]["URL"])):?>
														</span>
														<?php else:?>
														</a>
														<?php endif;?>
													</div>

												</div>
												<form action="<?= $arResult["URL"]["SIMPLE_FORM_EDIT"] ?>" method="post">
													<input type="hidden" name="<?= $arResult["CONNECTOR"] ?>_form"
														   value="true">
													<input type="hidden" name="page_id"
														   value="<?= $page["INFO"]["ID"] ?>">
													<?= bitrix_sessid_post(); ?>
													<button type="submit"
															name="<?= $arResult["CONNECTOR"] ?>_authorization_page"
															class="ui-btn ui-btn-sm ui-btn-light-border"
															value="<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_CHANGE_PAGE') ?>">
														<?= Loc::getMessage('IMCONNECTOR_COMPONENT_FBINSTAGRAM_CHANGE_PAGE') ?>
													</button>
												</form>
											</div>
											<?php 
										}
									}
									?>
								</div>
							</div>
							<?php 
						}
						?>
					</div>
				</div>
				<?php 
			}
		}
	}
}
?>