<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . '/bitrix/components/bitrix/imconnector.settings/templates/.default/template.php');

if (\Bitrix\Main\Loader::includeModule("bitrix24"))
{
	CBitrix24::initLicenseInfoPopupJS();
}

$this->addExternalCss('/bitrix/components/bitrix/imconnector.settings/templates/.default/style.css');
$this->addExternalJs('/bitrix/components/bitrix/imconnector.settings/templates/.default/script.js');
\Bitrix\Main\UI\Extension::load("ui.buttons");
\Bitrix\Main\UI\Extension::load("ui.hint");
\CJSCore::Init("loader");
\Bitrix\ImConnector\Connector::initIconCss();
?>

<?php 
if (empty($arResult['RELOAD']) && empty($arResult['URL_RELOAD']))
{
	if (!empty($arResult['ACTIVE_LINE']))
	{
		?>
		<?php 
		$APPLICATION->IncludeComponent(
			$arResult['COMPONENT'],
			"",
			Array(
				"LINE" => $arResult['ACTIVE_LINE']['ID'],
				"CONNECTOR" => $arResult['ID'],
				"AJAX_MODE" =>  "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"AJAX_OPTION_HISTORY" => "N",
				"AJAX_OPTION_JUMP" => "Y",
				"AJAX_OPTION_STYLE" => "Y",
				"INDIVIDUAL_USE" => "Y"
			)
		); ?>
		<?= $arResult['LANG_JS_SETTING']; ?>
		<?php 
		$status = \Bitrix\ImConnector\Status::getInstance($arResult['ID'], $arResult['ACTIVE_LINE']['ID'])->isStatus();
		\Bitrix\ImConnector\Status::cleanCache($arResult['ID'], $arResult['ACTIVE_LINE']['ID']);
		if ($status || count($arResult['LIST_LINE']) > 1)
		{
			?>
			<div class="imconnector-field-container" id="bx-connector-user-list">
				<div class="imconnector-field-section">
					<div class="imconnector-field-main-title">
						<?= Loc::getMessage('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_CONFIGURE_CHANNEL') ?>
					</div>

					<?php 
					if ($arResult['SHOW_LIST_LINES'])
					{
						?>
						<div class="imconnector-field-box">
							<div class="imconnector-field-box-subtitle">
								<?= Loc::getMessage('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_OPEN_LINE') ?>
							</div>
							<div class="imconnector-field-control-box">
								<?php 
								if (count($arResult['LIST_LINE']) > 0)
								{
									foreach ($arResult['LIST_LINE'] as &$line)
									{
										$line['URL'] = CUtil::JSEscape($line['URL']);
										$line['NAME'] = htmlspecialcharsbx($line['NAME']);
									}
									if (!empty($arResult['PATH_TO_ADD_LINE']))
									{
										$arResult['LIST_LINE'][] = array(
											'ID' => 0,
											'URL' => CUtil::JSEscape($arResult['PATH_TO_CONNECTOR_LINE_ADAPTED']),
											'NAME' => Loc::getMessage('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_CREATE_OPEN_LINE'),
											'NEW' => 'Y',
											'DELIMITER_BEFORE' => true
										);
									}
									?>
									<script>
										BX.ready(function () {
											BX.message({
												IMCONNECTOR_COMPONENT_CONNECTOR_LINE_ACTIVATION_TITLE: '<?=GetMessageJS('IMCONNECTOR_COMPONENT_CONNECTOR_LINE_ACTIVATION_TITLE')?>',
												IMCONNECTOR_COMPONENT_CONNECTOR_LINE_ACTIVATION_DESCRIPTION: '<?=GetMessageJS('IMCONNECTOR_COMPONENT_CONNECTOR_LINE_ACTIVATION_DESCRIPTION')?>',
												IMCONNECTOR_COMPONENT_CONNECTOR_LINE_ACTIVATION_BUTTON_ACTIVE: '<?=GetMessageJS('IMCONNECTOR_COMPONENT_CONNECTOR_LINE_ACTIVATION_BUTTON_ACTIVE')?>',
												IMCONNECTOR_COMPONENT_CONNECTOR_LINE_ACTIVATION_BUTTON_NO: '<?=GetMessageJS('IMCONNECTOR_COMPONENT_CONNECTOR_LINE_ACTIVATION_BUTTON_NO')?>',
											});
											BX.OpenLinesConfigEdit.initConfigMenu({
												element: 'imconnector-lines-list',
												bindElement: 'imconnector-lines-list',
												items: <?=CUtil::PhpToJSObject($arResult['LIST_LINE'])?>,
												iframe: <?=CUtil::PhpToJSObject($arParams['IFRAME'])?>
											});
										});
									</script>
									<div class="imconnector-field-control-input imconnector-field-control-select"
										 id="imconnector-lines-list"><?=$arResult['ACTIVE_LINE']['NAME']?></div>
									<?php 
									if (!empty($arResult['ACTIVE_LINE']['URL_EDIT']))
									{
										?>
										<button onclick="BX.SidePanel.Instance.open(
												'<?= CUtil::JSEscape($arResult['ACTIVE_LINE']['URL_EDIT']) ?>',
												{width: 996, loader: '/bitrix/components/bitrix/imopenlines.lines.edit/templates/.default/images/imopenlines-view.svg', allowChangeHistory: false})"
												class="ui-btn ui-btn-link imopenlines-settings-button">
											<?= Loc::getMessage('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_CONFIGURE') ?>
										</button>
										<?php 
									}
								}
								else
								{
									?>
									<button onclick="BX.OpenLinesConfigEdit.createLineAction('<?= CUtil::JSEscape($arResult['PATH_TO_CONNECTOR_LINE_ADAPTED']) ?>', <?=CUtil::PhpToJSObject($arParams['IFRAME'])?>)"
											class="ui-btn ui-btn-link imopenlines-settings-button">
										<?= Loc::getMessage('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_CREATE_OPEN_LINE') ?>
									</button>
									<?php 
								}
								?>
							</div>
						</div>
						<?php 
					}
					?>

					<?php 
					if ($arResult['CAN_CHANGE_USERS'])
					{
						CUtil::InitJSCore(array("socnetlogdest"));
						?>
						<form name="users-queue" id="user-queue-save">
							<div class="tel-set-destination-container" id="users_for_queue"></div>
							<input type="hidden" name="lineId" value="<?= $arResult['ACTIVE_LINE']['ID'] ?>">
						</form>
						<script type="text/javascript">
							BX.ready(function () {
								BX.message({
									LM_ADD1: '<?=GetMessageJS("IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_LM_ADD1")?>',
									LM_ADD2: '<?=GetMessageJS("IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_LM_ADD2")?>',
									LM_ERROR_BUSINESS: '<?=GetMessageJS("IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_LM_ERROR_BUSINESS")?>',
									'LM_BUSINESS_USERS': '<?=CUtil::JSEscape($arResult['BUSINESS_USERS'])?>',
									'LM_BUSINESS_USERS_ON': '<?=CUtil::JSEscape($arResult['BUSINESS_USERS_LIMIT'])?>',
									'LM_BUSINESS_USERS_TEXT': '<?=GetMessageJS("IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_POPUP_LIMITED_BUSINESS_USERS_TEXT")?>',
									'LM_QUEUE_DESCRIPTION': '<?=GetMessageJS("IMCONNECTOR_COMPONENT_CONNECTOR_QUEUE_DESCRIPTION")?>',
									'LM_QUEUE_TITLE': '<?=GetMessageJS("IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_QUEUE")?>'
								});

								BX.OpenLinesConfigEdit.initDestination(BX('users_for_queue'), 'QUEUE', <?=CUtil::PhpToJSObject($arResult["QUEUE_DESTINATION"])?>);
							});
						</script>
						<?php 
					}
					else
					{
						?>
						<div class="imconnector-field-box imconnector-field-user-box">
							<div class="imconnector-field-box-subtitle">
								<?= Loc::getMessage('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_QUEUE') ?>
								<div class="imconnector-field-box-subtitle-tooltip"
									 data-hint="<?=Loc::getMessage("IMCONNECTOR_COMPONENT_CONNECTOR_QUEUE_DESCRIPTION")?>"></div>
							</div>
							<div class="imconnector-field-user">
								<?php 
								foreach ($arResult["QUEUE_DESTINATION"]["SELECTED"]["USERS"] as $userId)
								{
									$user = $arResult["QUEUE_DESTINATION"]["USERS"]["U" . $userId];
									?>
									<div class="imconnector-field-user-item">
										<div class="imconnector-field-user-icon"
											 <?php 
											 if ($user['avatar'] != '')
											 {
											 	?>
												 style="background-image: url(<?= $user['avatar'] ?>)"
											 	<?php 
											 }
											 ?>>
										</div>
										<div class="imconnector-field-user-info">
											<a href="<?= $user['link'] ?>" target="_top"
											   class="imconnector-field-user-name"><?= $user['name'] ?></a>
											<div class="imconnector-field-user-desc"><?= $user['desc'] ?></div>
										</div>
									</div>
									<?php 
								}
								?>
							</div>
						</div>
						<?php 
					}
					?>

					<?php 
					if (\Bitrix\ImOpenlines\Security\Helper::isSettingsMenuEnabled())
					{
						?>
						<div class="imconnector-field-box imconnector-field-user-box box-rights">
							<div class="imconnector-field-box-subtitle box-rights">
								<?= Loc::getMessage('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_PERMISSIONS') ?>
							</div>
							<a href="javascript:void(0)"
							   onclick="BX.SidePanel.Instance.open('<?=\Bitrix\ImOpenLines\Common::getPublicFolder() . 'permissions.php'?>', {allowChangeHistory: false})"
							   class="bx-destination-add imconnector-field-link-grey">
								<?= Loc::getMessage('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_CONFIGURE') ?>
							</a>
						</div>
						<?php 
					}
					?>
				</div>
			</div>
			<?php 
			if (count($arResult['LIST_LINE']) > 0)
			{
				?>
				<script>
					BX.message({
						IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_POPUP_LIMITED_TITLE: '<?= GetMessageJS('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_POPUP_LIMITED_TITLE') ?>',
						IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_POPUP_LIMITED_TEXT: '<?= GetMessageJS('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_POPUP_LIMITED_TEXT') ?>',
						IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_ERROR_ACTION: '<?= GetMessageJS('IIMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_ERROR_ACTION') ?>',
						IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_CLOSE: '<?= GetMessageJS('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_CLOSE') ?>',
						IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_QUEUE: '<?= GetMessageJS('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_QUEUE')?>'
					});
				</script>
				<?php 
			}

		}
	}
	elseif (empty($arResult['ACTIVE_LINE']) && !empty($arResult['PATH_TO_ADD_LINE']))
	{
		?>
		<div class="imconnector-field-container">
			<div class="imconnector-field-section imconnector-field-section-social">
				<div class="imconnector-field-box">
					<?= Loc::getMessage('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_NO_OPEN_LINE'); ?>
					<a class="imconnector-field-box-link" onclick="BX.OpenLinesConfigEdit.createLineAction('<?= CUtil::JSEscape($arResult['PATH_TO_CONNECTOR_LINE_ADAPTED']) ?>', <?=CUtil::PhpToJSObject($arParams['IFRAME'])?>)" target="_top">
						<?=Loc::getMessage('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_CREATE_OPEN_LINE')?>
					</a>
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
					<?= Loc::getMessage('IMCONNECTOR_COMPONENT_CONNECTOR_SETTINGS_NO_OPEN_LINE_AND_NOT_ADD_OPEN_LINE'); ?>
				</div>
			</div>
		</div>
		<?php 
	}
}
elseif (!empty($arResult['URL_RELOAD']))
{
	?>
	<html>
	<body>
	<script>
		window.reloadAjaxImconnector = function (urlReload)
		{
			parent.window.opener.location.href = urlReload; //parent.window.opener construction is used for both frame and page mode as universal
			parent.window.opener.addPreloader();
			window.close();
		};
		reloadAjaxImconnector(<?=CUtil::PhpToJSObject($arResult['URL_RELOAD'])?>);
	</script>
	</body>
	</html>
	<?php 
}
else
{
	?>
	<html>
	<body>
	<script>
		window.reloadAjaxImconnector = function (urlReload, idReload)
		{
			parent.window.opener.BX.ajax.insertToNode(urlReload, idReload);
			window.close();
		};
		reloadAjaxImconnector(<?=CUtil::PhpToJSObject($arResult['URL_RELOAD'])?>, <?=CUtil::PhpToJSObject('comp_' . $arResult['RELOAD'])?>);
	</script>
	</body>
	</html>
	<?php 
}
?>
