<?php

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Mail\Helper\LicenseManager;
use Bitrix\Mail\Helper\MessageFolder;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (\CModule::includeModule('bitrix24'))
{
	\CBitrix24::initLicenseInfoPopupJS();
}

\Bitrix\Main\UI\Extension::load('ui.buttons');
\Bitrix\Main\Loader::includeModule('socialnetwork');
\CJsCore::init(array('socnetlogdest', 'popup', 'fx'));
$APPLICATION->setAdditionalCSS('/bitrix/components/bitrix/main.post.form/templates/.default/style.css');

$mailbox = $arParams['MAILBOX'];
$settings = $arParams['SERVICE'];

if ('N' == $_REQUEST['oauth'])
{
	$hiddenOAuth = !empty($settings['oauth']);
	unset($settings['oauth']);
}

$baseUri = \CHTTP::urlDeleteParams(Main\Context::getCurrent()->getRequest()->getRequestUri(), array('oauth'));

if (!empty($mailbox))
{
	if (!is_array($mailbox['OPTIONS']))
	{
		$mailbox['OPTIONS'] = array();
	}

	$mailbox['OPTIONS']['flags'] = is_array($mailbox['OPTIONS']['flags'])
		? array_values($mailbox['OPTIONS']['flags'])
		: array();

	if (!is_array($mailbox['OPTIONS']['imap']))
	{
		$mailbox['OPTIONS']['imap'] = array();
	}

	if (!is_array($mailbox['OPTIONS']['imap']['dirs']))
	{
		$mailbox['OPTIONS']['imap']['dirs'] = array();
	}

	$mailbox['OPTIONS']['imap']['disabled'] = is_array($mailbox['OPTIONS']['imap']['disabled'])
		? array_values($mailbox['OPTIONS']['imap']['disabled'])
		: array();

	$mailbox['OPTIONS']['imap']['ignore'] = is_array($mailbox['OPTIONS']['imap']['ignore'])
		? array_values($mailbox['OPTIONS']['imap']['ignore'])
		: array();

	$mailbox['OPTIONS']['imap'][MessageFolder::INCOME] = is_array($mailbox['OPTIONS']['imap'][MessageFolder::INCOME])
		? array_values($mailbox['OPTIONS']['imap'][MessageFolder::INCOME])
		: array();

	$mailbox['OPTIONS']['imap'][MessageFolder::OUTCOME] = is_array($mailbox['OPTIONS']['imap'][MessageFolder::OUTCOME])
		? array_values($mailbox['OPTIONS']['imap'][MessageFolder::OUTCOME])
		: array();

	$mailbox['OPTIONS']['imap'][MessageFolder::TRASH] = is_array($mailbox['OPTIONS']['imap'][MessageFolder::TRASH])
		? array_values($mailbox['OPTIONS']['imap'][MessageFolder::TRASH])
		: array();

	$mailbox['OPTIONS']['imap'][MessageFolder::SPAM] = is_array($mailbox['OPTIONS']['imap'][MessageFolder::SPAM])
		? array_values($mailbox['OPTIONS']['imap'][MessageFolder::SPAM])
		: array();
}

// @TODO: split by types
$accessList = array();
$accessLast = array();
$accessSelected = array();
foreach ($arParams['ACCESS_LIST'] as $type => $list)
{
	foreach ($list as $id => $item)
	{
		if ('users' == $type)
		{
			$accessList[$id] = $item;
		}

		$accessLast[$id] = $id;
		$accessSelected[$id] = $type;
	}
}

$crmQueueList = array();
$crmQueueLast = array();
$crmQueueSelected = array();
if ($arParams['CRM_AVAILABLE'])
{
	foreach ($arParams['CRM_QUEUE'] as $item)
	{
		$id = sprintf('U%u', $item['ID']);

		$crmQueueList[$id] = array(
			'id'       => $id,
			'entityId' => $item['ID'],
			'name'     => \CUser::formatName(\CSite::getNameFormat(), $item, true),
			'avatar'   => '',
			'desc'     => $item['WORK_POSITION'] ?: $item['PERSONAL_PROFESSION'] ?: '&nbsp;'
		);
		$crmQueueLast[$id] = $id;
		$crmQueueSelected[$id] = 'users';
	}
}

$APPLICATION->includeComponent('bitrix:main.mail.confirm', '', array());

?>

<div class="mail-connect mail-connect-slider">
	<form id="mail_connect_form" method="POST"
		action="/bitrix/services/main/ajax.php?c=<?=rawurlencode($this->getComponent()->getName()) ?>&action=save&mode=class">

		<?=bitrix_sessid_post() ?>
		<input type="hidden" name="fields[site_id]" value="<?=SITE_ID ?>">
		<input type="hidden" name="fields[service_id]" value="<?=$settings['id'] ?>">
		<?php  if (!empty($mailbox)): ?>
			<input type="hidden" name="fields[mailbox_id]" value="<?=$mailbox['ID'] ?>">
			<input type="hidden" name="fields[pass_placeholder]" value="<?=htmlspecialcharsbx($arParams['PASSWORD_PLACEHOLDER']) ?>">
		<?php  endif ?>

		<?php  if (empty($settings['oauth'])): ?>
			<div class="mail-connect-section-block">
				<div class="mail-connect-img-block">
					<?php  if ($settings['icon']): ?>
						<img class="mail-connect-img" src="<?=$settings['icon'] ?>" alt="<?=htmlspecialcharsbx($settings['name']) ?>">
					<?php  else: ?>
						<span class="mail-connect-text <?php  if (strlen($settings['name']) > 10): ?> mail-connect-text-small"<?php  endif ?>">
							&nbsp;<?=htmlspecialcharsbx($settings['name']) ?>&nbsp;
						</span>
					<?php  endif ?>
				</div>
			</div>
		<?php  endif ?>

		<?php  if (!empty($mailbox)): ?>
			<div class="mail-connect-section-block">
				<div class="mail-connect-mailbox-block">
					<div class="mail-connect-mailbox-name"><?=htmlspecialcharsbx($mailbox['EMAIL'] ?: sprintf('#%u', $mailbox['ID'])) ?></div>
					<?php  if ($arResult['LAST_MAIL_CHECK_DATE'] > 0): ?>
						<div class="mail-connect-last-sync-wrapper">
							<span class="mail-connect-last-sync-title">
								<?=Loc::getMessage(
									'MAIL_CLIENT_CONFIG_LAST_MAIL_CHECK_TITLE',
									array(
										'#TIME_AGO#' => formatDate(
											array('s' => 'sago', 'i' => 'iago', 'H' => 'Hago', 'd' => 'dago', 'm' => 'mago', 'Y' => 'Yago'),
											(int) $arResult['LAST_MAIL_CHECK_DATE']
										)
									)
								) ?>
							</span>
							<?php  $isSuccessSyncStatus = $arResult['LAST_MAIL_CHECK_STATUS']; ?>
							<span class="mail-connect-last-sync-status mail-connect-last-sync-<?= $isSuccessSyncStatus ? 'success' : 'error'; ?>">
								<?= Loc::getMessage('MAIL_CLIENT_CONFIG_LAST_MAIL_CHECK_' . ($isSuccessSyncStatus ? 'SUCCESS' : 'ERROR')); ?>
							</span>
						</div>
					<?php  endif ?>
				</div>
			</div>
		<?php  endif ?>

		<div class="mail-connect-section-block">
			<?php  if (!empty($settings['oauth'])): ?>
				<input type="hidden" name="fields[email]" value="<?=htmlspecialcharsbx($mailbox['EMAIL']) ?>">
				<input type="hidden" name="fields[oauth_uid]" value="<?=htmlspecialcharsbx($settings['oauth']->getStoredUid()) ?>">
				<input type="hidden" id="mail_connect_mb_oauth_url_field"
					value="<?=htmlspecialcharsbx($settings['oauth']->getUrl()) ?>">
				<input type="hidden" name="fields[oauth_mode]" id="mail_connect_mb_oauth_field"
					value="<?=(empty($settings['oauth_user']) ? 'N' : 'S') ?>">
				<div class="mail-connect-inner">
					<div class="mail-connect-img-block">
						<?php  if ($settings['icon']): ?>
							<img class="mail-connect-img" src="<?=$settings['icon'] ?>" alt="<?=htmlspecialcharsbx($settings['name']) ?>">
						<?php  else: ?>
							<span class="mail-connect-text <?php  if (strlen($settings['name']) > 10): ?> mail-connect-text-small"<?php  endif ?>">
								&nbsp;<?=htmlspecialcharsbx($settings['name']) ?>&nbsp;
							</span>
						<?php  endif ?>
					</div>
					<button class="ui-btn ui-btn-primary" id="mail_connect_mb_oauth_btn" type="button"
						<?php  if (!empty($settings['oauth_user'])): ?> style="display: none; "<?php  endif ?>><?=Loc::getMessage('MAIL_CLIENT_CONFIG_OAUTH_CONNECT') ?></button>
					<div class="mail-connect-email-block" id="mail_connect_mb_oauth_status"
						<?php  if (empty($settings['oauth_user'])): ?> style="display: none; "<?php  endif ?>>
						<div class="mail-connect-email-inner">
							<span class="mail-connect-email-img" id="mail_connect_mb_oauth_status_image"></span>
							<a class="mail-connect-email-text" id="mail_connect_mb_oauth_status_email">
								<?php  if (!empty($settings['oauth_user'])) echo htmlspecialcharsbx($settings['oauth_user']['email']); ?>
							</a>
						</div>
						<button class="ui-btn ui-btn-md ui-btn-link ui-btn-no-caps mail-connect-email-btn-disable"
							type="button" id="mail_connect_mb_oauth_cancel_btn"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_OAUTH_DISCONNECT') ?></button>
					</div>
				</div>
				<a href="<?=htmlspecialcharsbx(\CHTTP::urlAddParams($baseUri, array('oauth' => 'N'))) ?>"
					data-slider-ignore-autobinding="true" style="display: none; ">password mode</a>
			<?php  else: ?>
				<div class="mail-connect-form-inner">
					<?php  if (empty($mailbox['EMAIL'])): ?>
						<div class="mail-connect-form-item">
							<label class="mail-connect-form-label" for="mail_connect_mb_email_field"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_MAILBOX_EMAIL') ?></label>
							<input class="mail-connect-form-input" type="text" placeholder="info@example.com" 
								name="fields[email]" id="mail_connect_mb_email_field">
							<div class="mail-connect-form-error"></div>
						</div>
					<?php else:?>
						<input type="hidden" name="fields[email]" value="<?=htmlspecialcharsbx($mailbox['EMAIL']) ?>">
					<?php  endif ?>
					<?php  if (empty($settings['server'])): ?>
						<div class="mail-connect-form-item">
							<label class="mail-connect-form-label" for="mail_connect_mb_server_imap_field"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_SERVER') ?></label>
							<input class="mail-connect-form-input" type="text" placeholder="imap.example.com"
								name="fields[server_imap]" id="mail_connect_mb_server_imap_field"
								<?php  if (!empty($mailbox)): ?> value="<?=htmlspecialcharsbx($mailbox['SERVER']) ?>" <?php  endif ?>>
							<div class="mail-connect-form-error"></div>
						</div>
						<div class="mail-connect-form-item">
							<label class="mail-connect-form-label" for="mail_connect_mb_port_imap_field"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_PORT') ?></label>
							<div class="mail-connect-form-item-inner">
								<input class="mail-connect-form-input" type="text" placeholder="993"
									name="fields[port_imap]" id="mail_connect_mb_port_imap_field"
									<?php  if (!empty($mailbox)): ?> value="<?=htmlspecialcharsbx($mailbox['PORT']) ?>" <?php  endif ?>>
								<div class="mail-connect-option-email">
									<input class="mail-connect-form-input mail-connect-form-input-check" type="checkbox"
										name="fields[ssl_imap]" id="mail_connect_mb_ssl_imap_field"
										<?php  if (!empty($mailbox) && in_array($mailbox['USE_TLS'], array('Y', 'S'))): ?> value="<?=$mailbox['USE_TLS'] ?>" <?php  else: ?> value="Y" <?php  endif ?>
										<?php  if (empty($mailbox) || in_array($mailbox['USE_TLS'], array('Y', 'S'))): ?> checked <?php  endif ?>>
									<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_ssl_imap_field"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_SSL') ?></label>
								</div>
							</div>
							<div class="mail-connect-form-error"></div>
						</div>
					<?php  endif ?>
					<div class="mail-connect-form-item">
						<label class="mail-connect-form-label" for="mail_connect_mb_login_imap_field"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_LOGIN') ?></label>
						<input class="mail-connect-form-input" type="text"
							name="fields[login_imap]" id="mail_connect_mb_login_imap_field"
							onchange="this['__filled'] = this.value.length > 0; "
							<?php  if (!empty($mailbox)): ?> value="<?=htmlspecialcharsbx($mailbox['LOGIN']) ?>" disabled <?php  endif ?>>
						<div class="mail-connect-form-error"></div>
					</div>
					<div class="mail-connect-form-item">
						<label class="mail-connect-form-label" for="mail_connect_mb_pass_imap_field"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_PASS') ?></label>
						<input class="mail-connect-form-input" type="password" name="fields[pass_imap]" id="mail_connect_mb_pass_imap_field"
							<?php  if (!empty($mailbox['PASSWORD'])): ?>
								data-placeholder="<?=htmlspecialcharsbx($arParams['PASSWORD_PLACEHOLDER']) ?>"
								onfocus="if (this.value == this.getAttribute('data-placeholder')) this.value = ''; "
								onblur="if ('' == this.value) this.value = this.getAttribute('data-placeholder'); "
								value="<?=htmlspecialcharsbx($arParams['PASSWORD_PLACEHOLDER']) ?>"
							<?php  endif ?>>
						<div class="mail-connect-form-error"></div>
					</div>
				</div>
				<?php  if (!empty($hiddenOAuth)): ?>
					<a href="<?=htmlspecialcharsbx(\CHTTP::urlAddParams($baseUri, array('oauth' => 'Y'))) ?>"
						data-slider-ignore-autobinding="true" style="display: none; ">oauth mode</a>
				<?php  endif ?>
			<?php  endif ?>
		</div>

		<?php  $maxAgeLimit = LicenseManager::getSyncOldLimit(); ?>
		<?php  if (empty($mailbox)): ?>
			<div class="mail-connect-section-block">
				<div class="mail-connect-form-inner">
					<input type="checkbox" class="mail-connect-form-input mail-connect-form-input-check" name="fields[mail_connect_import_messages]" value="Y" id="mail_connect_mb_import_messages" checked>
					<?php  list($label1, $label2) = explode('#AGE#', Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_AGE'), 2); ?>
					<label class="mail_connect_mb_import_messages_label" for="mail_connect_mb_import_messages"><?=$label1 ?></label>
					<?php  $maxAgeDefault = $maxAgeLimit > 0 && $maxAgeLimit < 7 ? 1 : 7; ?>
					<label class="mail-set-singleselect mail-set-singleselect-line" data-checked="mail_connect_mb_max_age_field_<?=$maxAgeDefault ?>">
						<input id="mail_connect_mb_max_age_field_0" type="radio" name="fields[msg_max_age]" value="0">
						<label for="mail_connect_mb_max_age_field_0"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_AGE_2_<?=$maxAgeDefault ?>') ?></label>
						<div class="mail-set-singleselect-wrapper">
							<?php  foreach ($maxAgeDefault < 7 ? array(1, 7, 30, 60, 90) : array(7, 30, 60, 90) as $value): ?>
								<?php  $disabled = $maxAgeLimit > 0 && $value > $maxAgeLimit; ?>
								<input type="radio" name="fields[msg_max_age]" value="<?=$value ?>"
									id="mail_connect_mb_max_age_field_<?=$value ?>"
									<?php  if ($maxAgeDefault == $value): ?> checked<?php  endif ?>
									<?php  if ($disabled): ?> disabled<?php  endif ?>>
								<label for="mail_connect_mb_max_age_field_<?=$value ?>"
									<?php  if ($disabled): ?>
										class="mail-set-singleselect-option-disabled"
										onclick="showLicenseInfoPopup('age'); "
									<?php  endif ?>><?=Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_AGE_2_' . $value) ?></label>
							<?php  endforeach ?>
							<?php  if ($maxAgeLimit <= 0): ?>
								<input type="radio" name="fields[msg_max_age]" value="-1" id="mail_connect_mb_max_age_field_i">
								<label for="mail_connect_mb_max_age_field_i"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_AGE_2_I') ?></label>
							<?php  endif ?>
						</div>
					</label>
					<?=$label2 ?>
				</div>
			</div>
		<?php  else: ?>
			<div class="mail-connect-section-block">
				<a class="mail-connect-dashed-switch" href="#" id="mail_connect_mb_imap_dirs_link"
					><?=Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_DIRS_LINK') ?></a>
			</div>
		<?php  endif ?>

		<div class="mail-connect-section-block">
			<span class="mail-connect-dashed-switch"
				onclick="this.style.display = 'none'; BX('mail_connect_mb_ext_params').style.display = ''; "
				><?=Loc::getMessage('MAIL_CLIENT_CONFIG_EXT_SWITCH') ?></span>
			<div id="mail_connect_mb_ext_params" style="display: none; ">
				<div class="mail-connect-form-item">
					<label class="mail-connect-form-label" for="mail_connect_mb_name_field"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_MAILBOX_NAME') ?></label>
					<input class="mail-connect-form-input" type="text"
						name="fields[name]" id="mail_connect_mb_name_field"
						onchange="this['__filled'] = this.value.length > 0; "
						<?php  if (!empty($mailbox)): ?> value="<?=htmlspecialcharsbx($mailbox['NAME']) ?>" <?php  endif ?>>
				</div>
				<div class="mail-connect-form-item">
					<label class="mail-connect-form-label" for="mail_connect_mb_sender_field"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_MAILBOX_USERNAME') ?></label>
					<input class="mail-connect-form-input" type="text" name="fields[sender]" id="mail_connect_mb_sender_field"
						<?php  if (!empty($mailbox)): ?> value="<?=htmlspecialcharsbx($mailbox['USERNAME']) ?>" <?php  endif ?>>
				</div>
				<?php  if (empty($settings['link'])): ?>
					<div class="mail-connect-form-item">
						<label class="mail-connect-form-label" for="mail_connect_mb_link_field"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_MAILBOX_LINK') ?></label>
						<input class="mail-connect-form-input" type="text" name="fields[link]" id="mail_connect_mb_link_field"
							<?php  if (!empty($mailbox)): ?> value="<?=htmlspecialcharsbx($mailbox['LINK']) ?>" <?php  endif ?>>
						<div class="mail-connect-form-error"></div>
					</div>
				<?php  endif ?>
			</div>
		</div>

		<?php  if (!empty($arParams['IS_SMTP_AVAILABLE'])): ?>
			<?php  $hasSmtpFields = empty($settings['smtp']['server']) || !$settings['smtp']['login'] || !$settings['smtp']['password'] || !empty($settings['oauth']); ?>
			<div class="mail-connect-section-block">
				<div class="mail-connect-title-block">
					<div class="mail-connect-title"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_SMTP') ?></div>
				</div>
				<div class="<?php  if (empty($mailbox['__smtp']) || !$hasSmtpFields): ?>mail-connect-form-hidden-block<?php  endif ?>">
					<?php  if (empty($mailbox['__smtp']) || !$hasSmtpFields): ?>
						<div class="mail-connect-option-email">
							<input class="mail-connect-form-input mail-connect-form-input-check" type="checkbox"
								name="fields[use_smtp]" value="1" id="mail_connect_mb_server_smtp_switch"
								<?php  if (empty($mailbox)): ?> checked <?php  endif ?>
								<?php  if (!empty($mailbox['__smtp'])): ?> checked disabled <?php  endif ?>
								onchange="BX('mail_connect_mb_server_smtp_form').style.display = this.checked ? '' : 'none'; ">
							<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_server_smtp_switch"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_SMTP_ACTIVE') ?></label>
						</div>
					<?php  endif ?>
					<div class="mail-connect-form-inner" id="mail_connect_mb_server_smtp_form"
						<?php  if (!empty($mailbox) && empty($mailbox['__smtp'])): ?> style="display: none; " <?php  endif ?>>
						<?php  if ($hasSmtpFields): ?>
							<div class="mail-connect-warning-block">
								<div class="mail-connect-warning-text"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_SMTP_WARNING') ?></div>
							</div>
						<?php  endif ?>
						<?php  if (empty($settings['smtp']['server'])): ?>
							<div class="mail-connect-form-item">
								<label class="mail-connect-form-label" for="mail_connect_mb_server_smtp_field"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_SMTP_SERVER') ?></label>
								<div class="mail-connect-form-item-server-port">
									<input class="mail-connect-form-input mail-connect-form-input-server" type="text" placeholder="smtp.example.com"
										name="fields[server_smtp]" id="mail_connect_mb_server_smtp_field"
										<?php  if (!empty($mailbox['__smtp'])): ?> value="<?=htmlspecialcharsbx($mailbox['__smtp']['server']) ?>" <?php  endif ?>>
									<input class="mail-connect-form-input mail-connect-form-input-port" type="text"
										name="fields[port_smtp]" id="mail_connect_mb_port_smtp_field"
										<?php  if (!empty($mailbox['__smtp'])): ?> value="<?=htmlspecialcharsbx($mailbox['__smtp']['port']) ?>" <?php  endif ?>>
								</div>
								<div class="mail-connect-form-error"></div>
							</div>
						<?php  endif ?>
						<?php  if (!$settings['smtp']['login']): ?>
							<div class="mail-connect-form-item">
								<label class="mail-connect-form-label" for="mail_connect_mb_login_smtp_field"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_SMTP_LOGIN') ?></label>
								<input class="mail-connect-form-input" type="text"
									name="fields[login_smtp]" id="mail_connect_mb_login_smtp_field"
									onchange="this['__filled'] = this.value.length > 0; "
									<?php  if (!empty($mailbox['__smtp'])): ?> value="<?=htmlspecialcharsbx($mailbox['__smtp']['login']) ?>" <?php  endif ?>>
								<div class="mail-connect-form-error"></div>
							</div>
						<?php  endif ?>
						<?php  if (!$settings['smtp']['password'] || !empty($settings['oauth'])): ?>
							<div class="mail-connect-form-item">
								<label class="mail-connect-form-label" for="mail_connect_mb_pass_smtp_field"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_SMTP_PASS') ?></label>
								<input class="mail-connect-form-input" type="password"
									name="fields[pass_smtp]" id="mail_connect_mb_pass_smtp_field"
									onchange="this['__filled'] = this.value.length > 0; "
									<?php  if (!empty($mailbox['__smtp'])): ?>
										data-placeholder="<?=htmlspecialcharsbx($arParams['PASSWORD_PLACEHOLDER']) ?>"
										onfocus="if (this.value == this.getAttribute('data-placeholder')) this.value = ''; "
										onblur="if ('' == this.value) this.value = this.getAttribute('data-placeholder'); "
										value="<?=htmlspecialcharsbx($arParams['PASSWORD_PLACEHOLDER']) ?>"
									<?php  endif ?>>
								<div class="mail-connect-form-error"></div>
							</div>
						<?php  endif ?>
					</div>
				</div>
			</div>
		<?php  endif ?>

		<?php  if ($arParams['CRM_AVAILABLE']): ?>
			<div class="mail-connect-section-block">
				<div class="mail-connect-title-block">
					<div class="mail-connect-title"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_CRM') ?></div>
				</div>
				<div class="mail-connect-form-hidden-block">
					<div class="mail-connect-option-email">
						<input class="mail-connect-form-input mail-connect-form-input-check" type="checkbox"
							name="fields[use_crm]" value="Y" id="mail_connect_mb_crm_switch"
							onchange="BX('mail_connect_mb_crm_form').style.display = this.checked ? '' : 'none'; "
							<?php  if (empty($mailbox) || !empty($mailbox['__crm'])): ?> checked <?php  endif ?>>
						<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_crm_switch"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_ACTIVE') ?></label>
					</div>
					<div class="mail-connect-form-inner" id="mail_connect_mb_crm_form"
						<?php  if (!empty($mailbox) && empty($mailbox['__crm'])): ?> style="display: none; " <?php  endif ?>>
						<?php  if (empty($mailbox)): ?>
							<div class="mail-connect-option-email mail-connect-form-check-hidden">
								<?php  list($label1, $label2) = explode('#AGE#', Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_AGE'), 2); ?>
								<input class="mail-connect-form-input mail-connect-form-input-check" type="checkbox"
									name="fields[crm_sync_old]" value="Y" id="mail_connect_mb_crm_sync_old"
									<?php  if (empty($mailbox)): ?> checked <?php  endif ?>>
								<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_crm_sync_old">
									<?=$label1 ?>
								</label>
								<label class="mail-set-singleselect mail-set-singleselect-line" data-checked="mail_connect_mb_crm_max_age_field_7">
									<input id="mail_connect_mb_crm_max_age_field_0" type="radio" name="fields[crm_max_age]" value="0">
									<label for="mail_connect_mb_crm_max_age_field_0"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_AGE_2_7') ?></label>
									<div class="mail-set-singleselect-wrapper">
										<?php  foreach (array(7, 30) as $value): ?>
											<input type="radio" name="fields[crm_max_age]" value="<?=$value ?>"
												id="mail_connect_mb_crm_max_age_field_<?=$value ?>"
												<?php  if (7 == $value): ?> checked <?php  endif ?>>
											<label for="mail_connect_mb_crm_max_age_field_<?=$value ?>"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_AGE_2_' . $value) ?></label>
										<?php  endforeach ?>
										<input type="radio" name="fields[crm_max_age]" value="-1" id="mail_connect_mb_crm_max_age_field_i">
										<label for="mail_connect_mb_crm_max_age_field_i"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_AGE_2_I') ?></label>
									</div>
								</label>
								<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_crm_sync_old">
									<?=$label2 ?>
								</label>
							</div>
						<?php  endif ?>
						<div class="mail-connect-option-email mail-connect-form-check-hidden">
							<input class="mail-connect-form-input mail-connect-form-input-check" type="checkbox"
								name="fields[crm_public]" value="Y" id="mail_connect_mb_crm_public"
								<?php  if (!empty($mailbox) && in_array('crm_public_bind', $mailbox['OPTIONS']['flags'])): ?> checked <?php  endif ?>>
							<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_crm_public">
								<?=Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_PUBLIC') ?>
							</label>
						</div>
						<div class="mail-connect-option-email mail-connect-form-check-hidden">
							<?php  list($label1, $label2) = explode('#ENTITY#', Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_NEW_ENTITY_IN'), 2); ?>
							<input class="mail-connect-form-input mail-connect-form-input-check" type="checkbox"
								name="fields[crm_allow_entity_in]" value="Y" id="mail_connect_mb_crm_allow_entity_in"
								<?php  if (empty($mailbox) || !array_intersect(array('crm_deny_new_lead', 'crm_deny_entity_in'), $mailbox['OPTIONS']['flags'])): ?> checked <?php  endif ?>>
							<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_crm_allow_entity_in">
								<?=$label1 ?>
							</label>
							<label class="mail-set-singleselect mail-set-singleselect-line" data-checked="mail_connect_mb_crm_entity_in_<?=htmlspecialcharsbx($arParams['DEFAULT_NEW_ENTITY_IN']) ?>">
								<input id="mail_connect_mb_crm_entity_in_0" type="radio" name="fields[crm_entity_in]" value="0">
								<label for="mail_connect_mb_crm_entity_in_0"><?=htmlspecialcharsbx($arParams['NEW_ENTITY_LIST'][$arParams['DEFAULT_NEW_ENTITY_IN']]) ?></label>
								<div class="mail-set-singleselect-wrapper">
									<?php  foreach ($arParams['NEW_ENTITY_LIST'] as $value => $title): ?>
										<input type="radio" name="fields[crm_entity_in]" value="<?=htmlspecialcharsbx($value) ?>"
											id="mail_connect_mb_crm_entity_in_<?=htmlspecialcharsbx($value) ?>"
											<?php  if ($value == $arParams['DEFAULT_NEW_ENTITY_IN']): ?> checked <?php  endif ?>>
										<label for="mail_connect_mb_crm_entity_in_<?=htmlspecialcharsbx($value) ?>"><?=htmlspecialcharsbx($title) ?></label>
									<?php  endforeach ?>
								</div>
							</label>
							<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_crm_allow_entity_in">
								<?=$label2 ?>
							</label>
						</div>
						<div class="mail-connect-option-email mail-connect-form-check-hidden">
							<?php  list($label1, $label2) = explode('#ENTITY#', Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_NEW_ENTITY_OUT'), 2); ?>
							<input class="mail-connect-form-input mail-connect-form-input-check" type="checkbox"
								name="fields[crm_allow_entity_out]" value="Y" id="mail_connect_mb_crm_allow_entity_out"
								<?php  if (empty($mailbox) || !array_intersect(array('crm_deny_new_lead', 'crm_deny_entity_out'), $mailbox['OPTIONS']['flags'])): ?> checked <?php  endif ?>>
							<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_crm_allow_entity_out">
								<?=$label1 ?>
							</label>
							<label class="mail-set-singleselect mail-set-singleselect-line" data-checked="mail_connect_mb_crm_entity_out_<?=htmlspecialcharsbx($arParams['DEFAULT_NEW_ENTITY_OUT']) ?>">
								<input id="mail_connect_mb_crm_entity_out_0" type="radio" name="fields[crm_entity_out]" value="0">
								<label for="mail_connect_mb_crm_entity_out_0"><?=htmlspecialcharsbx($arParams['NEW_ENTITY_LIST'][$arParams['DEFAULT_NEW_ENTITY_IN']]) ?></label>
								<div class="mail-set-singleselect-wrapper">
									<?php  foreach ($arParams['NEW_ENTITY_LIST'] as $value => $title): ?>
										<input type="radio" name="fields[crm_entity_out]" value="<?=htmlspecialcharsbx($value) ?>"
											id="mail_connect_mb_crm_entity_out_<?=htmlspecialcharsbx($value) ?>"
											<?php  if ($value == $arParams['DEFAULT_NEW_ENTITY_OUT']): ?> checked <?php  endif ?>>
										<label for="mail_connect_mb_crm_entity_out_<?=htmlspecialcharsbx($value) ?>"><?=htmlspecialcharsbx($title) ?></label>
									<?php  endforeach ?>
								</div>
							</label>
							<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_crm_allow_entity_out">
								<?=$label2 ?>
							</label>
						</div>
						<div class="mail-connect-option-email mail-connect-form-check-hidden">
							<input class="mail-connect-form-input mail-connect-form-input-check" type="checkbox"
								name="fields[crm_vcf]" value="Y" id="mail_connect_mb_crm_vcf"
								<?php  if (empty($mailbox) || !in_array('crm_deny_new_contact', $mailbox['OPTIONS']['flags'])): ?> checked <?php  endif ?>>
							<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_crm_vcf">
								<?=Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_VCF') ?>
							</label>
						</div>
						<div class="mail-connect-option-email mail-connect-form-check-hidden">
							<?php  list($label1, $label2) = explode('#SOURCE#', Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_LEAD_SOURCE'), 2); ?>
							<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_crm_lead_source">
								<?=$label1 ?>
							</label>
							<label class="mail-set-singleselect mail-set-singleselect-line" data-checked="mail_connect_mb_crm_lead_source_<?=htmlspecialcharsbx($arParams['DEFAULT_LEAD_SOURCE']) ?>">
								<input id="mail_connect_mb_crm_lead_source_0" type="radio" name="fields[crm_lead_source]" value="0">
								<label for="mail_connect_mb_crm_lead_source_0"><?=htmlspecialcharsbx($arParams['LEAD_SOURCE_LIST'][$arParams['DEFAULT_LEAD_SOURCE']]) ?></label>
								<div class="mail-set-singleselect-wrapper">
									<?php  foreach ($arParams['LEAD_SOURCE_LIST'] as $value => $title): ?>
										<input type="radio" name="fields[crm_lead_source]" value="<?=htmlspecialcharsbx($value) ?>"
											id="mail_connect_mb_crm_lead_source_<?=htmlspecialcharsbx($value) ?>"
											<?php  if ($value == $arParams['DEFAULT_LEAD_SOURCE']): ?> checked <?php  endif ?>>
										<label for="mail_connect_mb_crm_lead_source_<?=htmlspecialcharsbx($value) ?>"><?=htmlspecialcharsbx($title) ?></label>
									<?php  endforeach ?>
								</div>
							</label>
							<label class="mail-connect-form-label mail-connect-form-label-check" for="mail_connect_mb_crm_lead_source">
								<?=$label2 ?>
							</label>
						</div>
						<div class="mail-connect-option-email mail-connect-form-check-hidden">
							<?php  list($label1, $label2) = explode('#LIST#', Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_NEW_LEAD_ALLWAYS'), 2); ?>
							<label class="mail-connect-form-label mail-connect-form-label-check">
								<?=$label1 ?>
							</label>
							<span class="mail-set-textarea-show <?php  if (!empty($arParams['NEW_LEAD_FOR'])): ?> mail-set-textarea-show-open<?php  endif ?>"
								id="mail_connect_mb_crm_new_lead_for_link"
								><?=Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_NEW_LEAD_ALLWAYS_LIST') ?></span>
							<label class="mail-connect-form-label mail-connect-form-label-check">
								<?=$label2 ?>
							</label>
						</div>
						<div class="mail-connect-form-textarea-block" id="mail_connect_mb_crm_new_lead_for"
							<?php  if (empty($arParams['NEW_LEAD_FOR'])): ?> style="display: none; " <?php  endif ?>>
							<textarea class="mail-connect-form-textarea" name="fields[crm_new_lead_for]"
								placeholder="<?=Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_NEW_LEAD_FOR_PROMPT') ?>"><?php 
								echo join(', ', (array) $arParams['NEW_LEAD_FOR']);
							?></textarea>
						</div>
						<div class="mail-connect-option-email mail-connect-form-check-hidden">
							<label class="mail-connect-form-label mail-connect-form-label-check">
								<?=Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_QUEUE') ?>
							</label>
							<div class="mail-connect-access-user-block" id="mail_connect_mb_crm_queue_container">
								<span id="mail_connect_mb_crm_queue_item"></span>
								<span class="feed-add-destination-input-box" id="mail_connect_mb_crm_queue_input_box" style="display: none; ">
									<input type="text" class="feed-add-destination-inp" id="mail_connect_mb_crm_queue_input">
								</span>
								<a href="javascript:void(0)" class="mail-connect-access-user-add" id="mail_connect_mb_crm_queue_tag"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_QUEUE_ADD') ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php  endif ?>

		<div class="mail-connect-section-block">
			<div class="mail-connect-title-block">
				<div class="mail-connect-title"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_ACCESS') ?></div>
			</div>
			<div class="mail-connect-notice-block">
				<div class="mail-connect-notice-text">
					<?=Loc::getMessage('MAIL_CLIENT_CONFIG_ACCESS_HINT') ?>
					<!--span class="mail-connect-notice-more"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_ACCESS_MORE') ?></span-->
				</div>
			</div>
			<div class="mail-connect-access-user-block" id="mail_connect_mb_access_container">
				<span id="mail_connect_mb_access_item"></span>
				<span class="feed-add-destination-input-box" id="mail_connect_mb_access_input_box" style="display: none; ">
					<input type="text" class="feed-add-destination-inp" id="mail_connect_mb_access_input">
				</span>
				<a href="javascript:void(0)" class="mail-connect-access-user-add" id="mail_connect_mb_access_tag"
					data-forbidden-to-share="<?= CUtil::JSEscape($arResult['FORBIDDEN_TO_SHARE_MAILBOX']); ?>">
					<?=Loc::getMessage('MAIL_CLIENT_CONFIG_ACCESS_ADD') ?>
				</a>
				<?php  if ($arResult['FORBIDDEN_TO_SHARE_MAILBOX']): ?>
					<span class="mail-connect-lock-icon"></span>
				<?php  endif ?>
			</div>
		</div>

		<div class="mail-connect-footer mail-connect-footer-fixed">
			<div class="main-connect-form-error" id="mail_connect_form_error"></div>
			<div class="mail-connect-footer-container">
				<button class="ui-btn ui-btn-md ui-btn-success ui-btn-success mail-connect-btn-connect"
					type="submit" id="mail_connect_save_btn"><?=Loc::getMessage(empty($mailbox) ? 'MAIL_CLIENT_CONFIG_BTN_CONNECT' : 'MAIL_CLIENT_CONFIG_BTN_SAVE') ?></button>
				<?php  if (!empty($mailbox)): ?>
					<button class="ui-btn ui-btn-md ui-btn ui-btn-danger mail-connect-btn-disconnect"
						type="button" id="mail_connect_disconnect_btn"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_BTN_DISCONNECT') ?></button>
				<?php  endif ?>
				<button class="ui-btn ui-btn-md ui-btn-link mail-connect-btn-cancel"
					type="reset" id="mail_connect_cancel_btn"><?=Loc::getMessage('MAIL_CLIENT_CONFIG_BTN_CANCEL') ?></button>
			</div>
		</div>

	</form>

</div>

<script type="text/javascript">

	if (window === window.top)
	{
		BX.ready(function ()
		{
			var footerPanel = BX.findChildByClassName(BX('mail_connect_form'), 'mail-connect-footer', true);
			footerPanel && document.body.appendChild(footerPanel);
		});
	}
	else
	{
		top.BX.loadCSS('/bitrix/components/bitrix/mail.client.sidepanel/templates/.default/style.css');
		top.BX.loadCSS('/bitrix/components/bitrix/mail.client.config/templates/.default/style.css');
	}

	BX.message({
		'MAIL_CLIENT_CONFIG_IMAP_DIRS_TITLE': '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_DIRS_TITLE')) ?>',
		'MAIL_CLIENT_CONFIG_IMAP_DIRS_SYNC': '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_DIRS_SYNC')) ?>',
		'MAIL_CLIENT_CONFIG_IMAP_DIRS_FOR': '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_DIRS_FOR')) ?>',
		'MAIL_CLIENT_CONFIG_IMAP_DIRS_OUTCOME': '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_DIRS_OUTCOME')) ?>',
		'MAIL_CLIENT_CONFIG_IMAP_DIRS_TRASH': '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_DIRS_TRASH')) ?>',
		'MAIL_CLIENT_CONFIG_IMAP_DIRS_SPAM': '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_DIRS_SPAM')) ?>',
		'MAIL_CLIENT_CONFIG_IMAP_DIRS_EMPTY_DEFAULT': '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_DIRS_EMPTY_DEFAULT')) ?>',
		'MAIL_CLIENT_CONFIG_IMAP_DIRS_BTN_SAVE': '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_DIRS_BTN_SAVE')) ?>',
		'MAIL_CLIENT_CONFIG_IMAP_DIRS_BTN_CANCEL': '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_DIRS_BTN_CANCEL')) ?>',
		'MAIL_MAILBOX_LICENSE_SHARED_LIMIT_BODY': '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_MAILBOX_LICENSE_SHARED_LIMIT_BODY', array('#LIMIT#' => LicenseManager::getSharedMailboxesLimit()))) ?>',
		'MAIL_MAILBOX_LICENSE_SHARED_LIMIT_TITLE': '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_MAILBOX_LICENSE_SHARED_LIMIT_TITLE')) ?>'
	});

	BX.SocNetLogDestination.init({
		name: 'mail_connect_mb_access_selector',
		searchInput: BX('mail_connect_mb_access_input'),
		departmentSelectDisable: false,
		extranetUser:  false,
		allowAddSocNetGroup: false,
		bindMainPopup: {
			node: BX('mail_connect_mb_access_container'),
			offsetTop: '5px',
			offsetLeft: '15px'
		},
		bindSearchPopup: {
			node: BX('mail_connect_mb_access_container'),
			offsetTop: '5px',
			offsetLeft: '15px'
		},
		callback: {
			select: function(item, type, search, undeleted)
			{
				BX.SocNetLogDestination.BXfpSelectCallback({
					item: item,
					type: type,
					varName: 'fields[access]',
					bUndeleted: undeleted,
					containerInput: BX('mail_connect_mb_access_item'),
					valueInput: BX('mail_connect_mb_access_input'),
					formName: 'mail_connect_mb_access_selector',
					tagInputName: 'mail_connect_mb_access_tag',
					tagLink1: '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_ACCESS_ADD')) ?>',
					tagLink2: '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_ACCESS_ADD')) ?>'
				});
			},
			unSelect: BX.delegate(BX.SocNetLogDestination.BXfpUnSelectCallback, {
				formName: 'mail_connect_mb_access_selector',
				inputContainerName: 'mail_connect_mb_access_item',
				inputName: 'mail_connect_mb_access_input',
				tagInputName: 'mail_connect_mb_access_tag',
				tagLink1: '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_ACCESS_ADD')) ?>',
				tagLink2: '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_ACCESS_ADD')) ?>',
				undeleteClassName: 'feed-add-post-destination-undelete'
			}),
			openDialog: BX.delegate(BX.SocNetLogDestination.BXfpOpenDialogCallback, {
				inputBoxName: 'mail_connect_mb_access_input_box',
				inputName: 'mail_connect_mb_access_input',
				tagInputName: 'mail_connect_mb_access_tag'
			}),
			closeDialog: BX.delegate(BX.SocNetLogDestination.BXfpCloseDialogCallback, {
				inputBoxName: 'mail_connect_mb_access_input_box',
				inputName: 'mail_connect_mb_access_input',
				tagInputName: 'mail_connect_mb_access_tag'
			}),
			openSearch: BX.delegate(BX.SocNetLogDestination.BXfpOpenDialogCallback, {
				inputBoxName: 'mail_connect_mb_access_input_box',
				inputName: 'mail_connect_mb_access_input',
				tagInputName: 'mail_connect_mb_access_tag'
			})
		},
		items: {
			users: <?=CUtil::phpToJSObject($accessList) ?>,
			groups: {},
			sonetgroups: {},
			department: <?=CUtil::phpToJSObject($arParams['COMPANY_STRUCTURE']['department']) ?>,
			departmentRelation: <?=CUtil::phpToJSObject($arParams['COMPANY_STRUCTURE']['department_relation']) ?>
		},
		itemsLast: {
			users: <?=CUtil::phpToJSObject($accessLast) ?>,
			sonetgroups: {},
			department: <?=CUtil::phpToJSObject($accessLast) ?>,
			groups: {}
		},
		itemsSelected: <?=CUtil::phpToJSObject($accessSelected) ?>,
		itemsSelectedUndeleted: <?=\CUtil::phpToJsObject(array(sprintf('U%u', empty($mailbox) ? $USER->getId() : $mailbox['USER_ID']))) ?>,
		destSort: {}
	});

	BX.bind(
		BX('mail_connect_mb_access_input'),
		'keydown',
		BX.delegate(
			BX.SocNetLogDestination.BXfpSearchBefore,
			{
				formName: 'mail_connect_mb_access_selector',
				inputName: 'mail_connect_mb_access_input'
			}
		)
	);
	BX.bind(
		BX('mail_connect_mb_access_input'),
		'keyup',
		BX.delegate(
			BX.SocNetLogDestination.BXfpSearch,
			{
				formName: 'mail_connect_mb_access_selector',
				inputName: 'mail_connect_mb_access_input',
				tagInputName: 'mail_connect_mb_access_tag'
			}
		)
	);
	BX.bind(
		BX('mail_connect_mb_access_input'),
		'paste',
		BX.delegate(
			BX.SocNetLogDestination.BXfpSearchBefore,
			{
				formName: 'mail_connect_mb_access_selector',
				inputName: 'mail_connect_mb_access_input'
			}
		)
	);
	BX.bind(
		BX('mail_connect_mb_access_input'),
		'paste',
		BX.defer(
			BX.SocNetLogDestination.BXfpSearch,
			{
				formName: 'mail_connect_mb_access_selector',
				inputName: 'mail_connect_mb_access_input',
				tagInputName: 'mail_connect_mb_access_tag',
				onPasteEvent: true
			}
		)
	);

	var openAccessSelector = function (e)
	{
		e.preventDefault();
		e.stopPropagation();

		var limitInfo = BX('mail_connect_mb_access_tag').dataset;
		if (limitInfo && limitInfo.forbiddenToShare == 1)
		{
			B24.licenseInfoPopup.show(
				'mail-shared-mailbox-limit',
				BX.message('MAIL_MAILBOX_LICENSE_SHARED_LIMIT_TITLE'),
				BX.message('MAIL_MAILBOX_LICENSE_SHARED_LIMIT_BODY')
			);
			return;
		}

		BX.SocNetLogDestination.openDialog('mail_connect_mb_access_selector');
	};

	BX.bind(
		BX('mail_connect_mb_access_tag'),
		'click',
		openAccessSelector
	);
	BX.bind(
		BX('mail_connect_mb_access_container'),
		'click',
		openAccessSelector
	);

	BX.SocNetLogDestination.init({
		name: 'mail_connect_mb_crm_queue_selector',
		searchInput: BX('mail_connect_mb_crm_queue_input'),
		departmentSelectDisable: true,
		extranetUser:  false,
		allowAddSocNetGroup: false,
		bindMainPopup: {
			node: BX('mail_connect_mb_crm_queue_container'),
			offsetTop: '5px',
			offsetLeft: '15px'
		},
		bindSearchPopup: {
			node: BX('mail_connect_mb_crm_queue_container'),
			offsetTop: '5px',
			offsetLeft: '15px'
		},
		callback: {
			select: function(item, type, search)
			{
				BX.SocNetLogDestination.BXfpSelectCallback({
					item: item,
					type: type,
					varName: 'fields[crm_queue]',
					bUndeleted: false,
					containerInput: BX('mail_connect_mb_crm_queue_item'),
					valueInput: BX('mail_connect_mb_crm_queue_input'),
					formName: 'mail_connect_mb_crm_queue_selector',
					tagInputName: 'mail_connect_mb_crm_queue_tag',
					tagLink1: '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_QUEUE_ADD')) ?>',
					tagLink2: '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_QUEUE_ADD')) ?>'
				});
			},
			unSelect: BX.delegate(BX.SocNetLogDestination.BXfpUnSelectCallback, {
				formName: 'mail_connect_mb_crm_queue_selector',
				inputContainerName: 'mail_connect_mb_crm_queue_item',
				inputName: 'mail_connect_mb_crm_queue_input',
				tagInputName: 'mail_connect_mb_crm_queue_tag',
				tagLink1: '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_QUEUE_ADD')) ?>',
				tagLink2: '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_CRM_QUEUE_ADD')) ?>'
			}),
			openDialog: BX.delegate(BX.SocNetLogDestination.BXfpOpenDialogCallback, {
				inputBoxName: 'mail_connect_mb_crm_queue_input_box',
				inputName: 'mail_connect_mb_crm_queue_input',
				tagInputName: 'mail_connect_mb_crm_queue_tag'
			}),
			closeDialog: BX.delegate(BX.SocNetLogDestination.BXfpCloseDialogCallback, {
				inputBoxName: 'mail_connect_mb_crm_queue_input_box',
				inputName: 'mail_connect_mb_crm_queue_input',
				tagInputName: 'mail_connect_mb_crm_queue_tag'
			}),
			openSearch: BX.delegate(BX.SocNetLogDestination.BXfpOpenDialogCallback, {
				inputBoxName: 'mail_connect_mb_crm_queue_input_box',
				inputName: 'mail_connect_mb_crm_queue_input',
				tagInputName: 'mail_connect_mb_crm_queue_tag'
			})
		},
		items: {
			users: <?=CUtil::phpToJSObject($crmQueueList) ?>,
			groups: {},
			sonetgroups: {},
			department: <?=CUtil::phpToJSObject($arParams['COMPANY_STRUCTURE']['department']) ?>,
			departmentRelation: <?=CUtil::phpToJSObject($arParams['COMPANY_STRUCTURE']['department_relation']) ?>
		},
		itemsLast: {
			users: <?=CUtil::phpToJSObject($crmQueueLast) ?>,
			sonetgroups: {},
			department: {},
			groups: {}
		},
		itemsSelected: <?=CUtil::phpToJSObject($crmQueueSelected) ?>,
		destSort: {}
	});

	BX.bind(
		BX('mail_connect_mb_crm_queue_input'),
		'keydown',
		BX.delegate(
			BX.SocNetLogDestination.BXfpSearchBefore,
			{
				formName: 'mail_connect_mb_crm_queue_selector',
				inputName: 'mail_connect_mb_crm_queue_input'
			}
		)
	);
	BX.bind(
		BX('mail_connect_mb_crm_queue_input'),
		'keyup',
		BX.delegate(
			BX.SocNetLogDestination.BXfpSearch,
			{
				formName: 'mail_connect_mb_crm_queue_selector',
				inputName: 'mail_connect_mb_crm_queue_input',
				tagInputName: 'mail_connect_mb_crm_queue_tag'
			}
		)
	);
	BX.bind(
		BX('mail_connect_mb_crm_queue_input'),
		'paste',
		BX.delegate(
			BX.SocNetLogDestination.BXfpSearchBefore,
			{
				formName: 'mail_connect_mb_crm_queue_selector',
				inputName: 'mail_connect_mb_crm_queue_input'
			}
		)
	);
	BX.bind(
		BX('mail_connect_mb_crm_queue_input'),
		'paste',
		BX.defer(
			BX.SocNetLogDestination.BXfpSearch,
			{
				formName: 'mail_connect_mb_crm_queue_selector',
				inputName: 'mail_connect_mb_crm_queue_input',
				tagInputName: 'mail_connect_mb_crm_queue_tag',
				onPasteEvent: true
			}
		)
	);

	BX.bind(
		BX('mail_connect_mb_crm_queue_tag'),
		'click',
		function (e)
		{
			BX.SocNetLogDestination.openDialog('mail_connect_mb_crm_queue_selector');
			BX.PreventDefault(e);
		}
	);
	BX.bind(
		BX('mail_connect_mb_crm_queue_container'),
		'click',
		function (e)
		{
			BX.SocNetLogDestination.openDialog('mail_connect_mb_crm_queue_selector');
			BX.PreventDefault(e);
		}
	);

	(function()
	{
		var singleselect = function(input)
		{
			var options = BX.findChildren(input, {tag: 'input', attr: {type: 'radio'}}, true);
			for (var i in options)
			{
				BX.bind(options[i], 'change', function()
				{
					if (this.checked)
					{
						if (this.value == 0)
						{
							var input1 = BX(input.getAttribute('data-checked'));
							if (input1)
							{
								var label0 = BX.findNextSibling(this, {tag: 'label', attr: {'for': this.id}});
								var label1 = BX.findNextSibling(input1, {tag: 'label', attr: {'for': input1.id}});
								if (label0 && label1)
									BX.adjust(label0, {text: label1.innerHTML});
							}
						}
						else
						{
							input.setAttribute('data-checked', this.id);
						}
					}
				});
			}

			BX.bind(input, 'click', function(event)
			{
				event = event || window.event;
				event.skip_singleselect = input;
			});

			BX.bind(document, 'click', function(event)
			{
				event = event || window.event;
				if (event.skip_singleselect !== input)
					BX(input.getAttribute('data-checked')).checked = true;
			});
		};

		var selectInputs = BX.findChildrenByClassName(document, 'mail-set-singleselect', true);
		for (var i in selectInputs)
			singleselect(selectInputs[i]);

		BX.bind(
			BX('mail_connect_mb_crm_new_lead_for_link'),
			'click',
			function (e)
			{
				var textarea = BX('mail_connect_mb_crm_new_lead_for');
				var hide = textarea.offsetHeight > 0;

				textarea.style.display = hide ? 'none' : '';
				BX[hide?'removeClass':'addClass'](this, 'mail-set-textarea-show-open');
			}
		);

	})();

	(function()
	{
		var form = BX('mail_connect_form');

		var oauthHandler = function(uid, url, user, init)
		{
			if (uid != form.elements['fields[oauth_uid]'].value)
			{
				return;
			}

			if (user.image && user.image.length > 0)
			{
				BX.adjust(
					BX('mail_connect_mb_oauth_status_image'),
					{
						style: {
							backgroundImage: 'url("' + encodeURI(user.image) + '")',
							backgroundSize: 'cover'
						}
					}
				);
			}
			else
			{
				var initials = '';
				if (user.first_name && user.first_name.length > 0)
				{
					initials += user.first_name.substr(0, 1);
				}
				if (user.last_name && user.last_name.length > 0)
				{
					initials += user.last_name.substr(0, 1);
				}
				if (!(initials.length > 0) && user.full_name && user.full_name.length > 0)
				{
					initials += user.full_name.substr(0, 1);
				}
				if (!(initials.length > 0))
				{
					initials += user.email.substr(0, 1);
				}

				initials = initials.toUpperCase();

				// @TODO: initials -> color
				var color = Math.round(160 + Math.random() * (Math.pow(2, 24) - 320)).toString(16);
				color = '#' + '0'.repeat(6 - color.length) + color;

				BX.adjust(
					BX('mail_connect_mb_oauth_status_image'),
					{
						text: initials,
						style: {
							background: color
						}
					}
				);
			}

			BX.adjust(BX('mail_connect_mb_oauth_status_email'), { text: user.email });
			BX('mail_connect_mb_oauth_url_field').value = url;
			BX('mail_connect_mb_oauth_field').value = init ? 'S' : 'Y';

			var emailField = form.elements['fields[email]'];
			if (emailField)
			{
				emailField.value = user.email;
			}

			var nameField = BX('mail_connect_mb_name_field');
			if (!(nameField.value.length > 0) || !nameField['__filled'])
			{
				nameField.value = user.email;
			}

			BX('mail_connect_mb_oauth_btn').style.display = 'none';
			BX('mail_connect_mb_oauth_status').style.display = '';

			if (oauthHandler['__submit'])
			{
				oauthHandler['__submit'] = false;

				submitForm();
			}
		};

		BX.addCustomEvent('OnMailOAuthBCompleted', oauthHandler);

		BX.bind(
			BX('mail_connect_mb_oauth_btn'),
			'click',
			function (e)
			{
				BX.util.popup(BX('mail_connect_mb_oauth_url_field').value, 500, 600);

				e.preventDefault();
			}
		);

		var cancelHandler = function (e)
		{
			BX('mail_connect_mb_oauth_field').value = 'N';

			if (!form.elements['fields[mailbox_id]'])
			{
				var nameField = BX('mail_connect_mb_name_field');
				if (!nameField['__filled'])
				{
					nameField.value = '';
				}
			}

			BX('mail_connect_mb_oauth_status').style.display = 'none';
			BX('mail_connect_mb_oauth_btn').style.display = '';

			e.preventDefault();
		};

		BX.bind(BX('mail_connect_mb_oauth_cancel_btn'), 'click', cancelHandler);

		for (var i = 0; i < form.elements.length; i++)
		{
			if (form.elements[i].name && form.elements[i].type.match(/^text|password$/i))
			{
				if ('fields[email]' == form.elements[i].name)
				{
					BX.bind(
						form.elements[i],
						'bxchange',
						function ()
						{
							var nameField = BX('mail_connect_mb_name_field');
							if (!(nameField.value.length > 0) || !nameField['__filled'])
							{
								nameField.value = this.value;
							}

							var loginField = BX('mail_connect_mb_login_imap_field');
							if (loginField && (!(loginField.value.length > 0) || !loginField['__filled']))
							{
								loginField.value = this.value;
							}

							var loginSmtpField = BX('mail_connect_mb_login_smtp_field');
							if (loginSmtpField && (!(loginSmtpField.value.length > 0) || !loginSmtpField['__filled']))
							{
								loginSmtpField.value = this.value;
							}
						}
					);
				}

				if ('fields[login_imap]' == form.elements[i].name)
				{
					BX.bind(
						form.elements[i],
						'bxchange',
						function ()
						{
							var loginSmtpField = BX('mail_connect_mb_login_smtp_field');
							if (loginSmtpField && (!(loginSmtpField.value.length > 0) || loginSmtpField['__filled'] !== true))
							{
								loginSmtpField.value = this.value;
								loginSmtpField['__filled'] = 1;
							}
						}
					);
				}

				if ('fields[pass_imap]' == form.elements[i].name)
				{
					BX.bind(
						form.elements[i],
						'bxchange',
						function ()
						{
							var passSmtpField = BX('mail_connect_mb_pass_smtp_field');
							if (passSmtpField && (!(passSmtpField.value.length > 0) || !passSmtpField['__filled']))
							{
								passSmtpField.value = this.value;
							}
						}
					);
				}

				BX.bind(
					form.elements[i],
					'bxchange',
					BX.defer(
						function ()
						{
							if (this.value != this['__last_value'])
							{
								var fieldContainer = BX.findParent(
									this,
									{
										class: 'mail-connect-form-item'
									},
									form
								);

								BX.removeClass(fieldContainer, 'mail-connect-form-item-confirmed');
								BX.removeClass(fieldContainer, 'mail-connect-form-item-error');
							}
						},
						form.elements[i]
					)
				);
			}
		}

		var fieldError = function (field, error, text)
		{
			field['__last_value'] = field.value;

			var fieldContainer = BX.findParent(
				field,
				{
					class: 'mail-connect-form-item'
				},
				form
			);

			if (error)
			{
				BX.removeClass(fieldContainer, 'mail-connect-form-item-confirmed');
				BX.addClass(fieldContainer, 'mail-connect-form-item-error');
				BX.adjust(
					BX.findChildByClassName(fieldContainer, 'mail-connect-form-error', true),
					{
						text: text
					}
				);
			}
			else
			{
				BX.removeClass(fieldContainer, 'mail-connect-form-item-error');
				//BX.addClass(fieldContainer, 'mail-connect-form-item-confirmed');
			}

			return !error;
		};

		var checkForm = function ()
		{
			if (BX('mail_connect_mb_oauth_field'))
			{
				if ('N' == BX('mail_connect_mb_oauth_field').value)
				{
					oauthHandler['__submit'] = true;

					BX.util.popup(BX('mail_connect_mb_oauth_url_field').value, 500, 600);

					return false;
				}
			}

			var result = true;

			var emailField = form.elements['fields[email]'];
			if (emailField)
			{
				if (emailField.value.length > 0)
				{
					var atom = "[=a-z0-9_+~'!$&*^`|#%/?{}-]";
					var pattern = new RegExp('^\\s*'+atom+'+(\\.'+atom+'+)*@([a-z0-9-]+\\.)+[a-z0-9-]{2,20}\\s*$', 'i');

					result *= fieldError(
						emailField,
						!emailField.value.match(pattern),
						'<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_EMAIL_BAD')) ?>'
					);
				}
				else
				{
					result *= fieldError(emailField, true, '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_EMAIL_EMPTY')) ?>');
				}
			}

			var serverField = form.elements['fields[server_imap]'];
			if (serverField)
			{
				if (serverField.value.length > 0)
				{
					result *= fieldError(
						serverField,
						!serverField.value.match(/^\s*((http|https|ssl|tls|imap):\/\/)?([a-z0-9](-*[a-z0-9])*\.?)+\s*$/i),
						'<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_SERVER_BAD')) ?>'
					);
				}
				else
				{
					result *= fieldError(serverField, true, '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_SERVER_EMPTY')) ?>');
				}
			}

			var portField = form.elements['fields[port_imap]'];
			if (portField)
			{
				result *= fieldError(
					portField,
					!(portField.value.match(/^\s*[0-9]+\s*$/) && portField.value > 0 && portField.value <= 65535),
					'<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_PORT_BAD')) ?>'
				);
			}

			var linkField = form.elements['fields[link]'];
			if (linkField)
			{
				if (linkField.value.length > 0)
				{
					result *= fieldError(
						linkField,
						!linkField.value.match(/^\s*(https?:\/\/)?([a-z0-9](-*[a-z0-9])*\.?)+(:[0-9]+)?\/?/i),
						'<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_LINK_BAD')) ?>'
					);
				}
			}

			var loginField = form.elements['fields[login_imap]'];
			if (loginField && !loginField.disabled)
			{
				result *= fieldError(
					loginField,
					!(loginField.value.length > 0),
					'<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_LOGIN_EMPTY')) ?>'
				);
			}

			var passwordField = form.elements['fields[pass_imap]'];
			if (passwordField && !passwordField.hasAttribute('data-placeholder'))
			{
				result *= fieldError(
					passwordField,
					!(passwordField.value.length > 0),
					'<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_PASS_EMPTY')) ?>'
				);
			}

			var smtpSwitch = form.elements['fields[use_smtp]'];
			if (smtpSwitch && smtpSwitch.checked)
			{
				var serverSmtpField = form.elements['fields[server_smtp]'];
				var serverError = false;
				if (serverSmtpField)
				{
					if (serverSmtpField.value.length > 0)
					{
						result *= fieldError(
							serverSmtpField,
							serverError = !serverSmtpField.value.match(/^\s*((http|https|ssl|tls|smtp):\/\/)?([a-z0-9](-*[a-z0-9])*\.?)+\s*$/i),
							'<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_SERVER_BAD')) ?>'
						);
					}
					else
					{
						result *= fieldError(serverSmtpField, serverError = true, '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_SERVER_EMPTY')) ?>');
					}
				}

				var portSmtpField = form.elements['fields[port_smtp]'];
				if (portSmtpField && !serverError)
				{
					result *= fieldError(
						portSmtpField,
						!(portSmtpField.value.match(/^\s*[0-9]+\s*$/) && portSmtpField.value > 0 && portSmtpField.value <= 65535),
						'<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_PORT_BAD')) ?>'
					);
				}

				var loginSmtpField = form.elements['fields[login_smtp]'];
				if (loginSmtpField && !loginSmtpField.disabled)
				{
					result *= fieldError(
						loginSmtpField,
						!(loginSmtpField.value.length > 0),
						'<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_LOGIN_EMPTY')) ?>'
					);
				}

				var passwordSmtpField = form.elements['fields[pass_smtp]'];
				if (passwordSmtpField && !form.elements['fields[mailbox_id]'])
				{
					result *= fieldError(
						passwordSmtpField,
						!(passwordSmtpField.value.length > 0),
						'<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_PASS_EMPTY')) ?>'
					);
				}
			}

			return result;
		};

		var closeForm = function (id)
		{
			id = id > 0 ? id : <?=intval($mailbox['ID']) ?>;

			var slider = top.BX.SidePanel.Instance.getSliderByWindow(window);
			if (slider)
			{
				slider.setCacheable(false);
				slider.close();
			}
			else
			{
				if (id > 0)
				{
					window.location.href = BX.util.add_url_param(
						'<?=\CUtil::jsEscape($arParams['PATH_TO_MAIL_MSG_LIST']) ?>'.replace('#id#', id),
						{ 'strict': 'N' }
					);
				}
				else
				{
					window.location.href = '<?=\CUtil::jsEscape($arParams['PATH_TO_MAIL_HOME']) ?>';
				}
			}
		};

		var submitForm = function (e)
		{
			if (e && e.preventDefault)
			{
				e.preventDefault();
			}

			var button = BX('mail_connect_save_btn');

			if (button.disabled)
			{
				return false;
			}

			button.disabled = false;

			BX.hide(BX('mail_connect_form_error'));

			if (!checkForm())
			{
				return false;
			}

			BX.addClass(button, 'ui-btn-wait');
			button.disabled = true;

			var formField = function (name)
			{
				return form.elements['fields[' + name + ']'] || {};
			}

			BX.ajax.submitAjax(
				form,
				{
					url: BX.util.add_url_param(
						form.getAttribute('action'),
						{
							is_new: '<?=(empty($mailbox) ? 'Y' : 'N') ?>',
							use_crm: formField('use_crm').checked ? 'Y' : 'N',
							use_smtp: formField('use_smtp').checked ? 'Y' : 'N',
							msg_age: formField('mail_connect_import_messages').checked ? formField('msg_max_age').value : 0,
							crm_age: formField('use_crm').checked && formField('crm_sync_old').checked ? formField('crm_max_age').value : 0,
							mail_serv: '<?=\CUtil::jsEscape($settings['name']) ?>'
						}
					),
					method: 'POST',
					data: form.__extData,
					dataType: 'json',
					onsuccess: function(json)
					{
						if ('success' != json.status)
						{
							button.disabled = false;
							BX.removeClass(button, 'ui-btn-wait');

							var errorText = '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_FORM_ERROR')) ?>';
							if (json.errors && json.errors.length > 0)
							{
								if (json.errors.length == 1 && 'MAIL_CLIENT_CONFIG_SMTP_CONFIRM' == json.errors[0].message)
								{
									BXMainMailConfirm.showForm(
										submitForm, // @TODO: skip if edit
										{
											mode: 'confirm',
											data: {
												email: form.elements['fields[email]'].value
											}
										}
									);

									return;
								}

								errorText = json.errors.map(
									function (item)
									{
										var result = item.message;

										if (item.customData)
										{
											result += ' (' +
												'<a href="#" onclick="BX.hide(this); BX.show(BX.findNextSibling(this, {class: \'main-connect-form-error-ext\'}), \'inline\'); return false; "><?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_CONFIG_IMAP_ERR_EXT')) ?></a>' +
												'<span class="main-connect-form-error-ext">' + item.customData + '</span>' +
											')';
										}

										return result;
									}
								).join('<br>');
							}

							BX('mail_connect_form_error').innerHTML = errorText;
							BX.show(BX('mail_connect_form_error'));
						}
						else
						{
							if (json.data && json.data.id > 0)
							{
								top.BX.SidePanel.Instance.postMessage(
									window,
									'mail-mailbox-config-success',
									{
										id: json.data.id,
										changed: {
											imap_dirs: form.__extData && form.__extData.imap_dirs
										}
									}
								);
							}

							closeForm(json.data ? json.data.id : 0);
						}
					},
					onfailure: function(json)
					{
						button.disabled = false;
						BX.removeClass(button, 'ui-btn-wait');

						BX('mail_connect_form_error').innerHTML = '<?=\CUtil::jsEscape(Loc::getMessage('MAIL_CLIENT_AJAX_ERROR')) ?>';
						BX.show(BX('mail_connect_form_error'));
					}
				}
			);
		};

		BX.bind(form, 'submit', submitForm);
		BX.bind(BX('mail_connect_save_btn'), 'click', submitForm);

		var nameField = BX('mail_connect_mb_name_field');
		if (nameField && nameField.value.length > 0)
		{
			nameField['__filled'] = true;
		}

		var loginField = BX('mail_connect_mb_login_imap_field');
		if (loginField && loginField.value.length > 0)
		{
			loginField['__filled'] = true;
		}

		var loginSmtpField = BX('mail_connect_mb_login_smtp_field');
		if (loginSmtpField && loginSmtpField.value.length > 0)
		{
			loginSmtpField['__filled'] = true;
		}

		var passSmtpField = BX('mail_connect_mb_pass_smtp_field');
		if (passSmtpField && passSmtpField.value.length > 0)
		{
			passSmtpField['__filled'] = true;
		}

		BX.bind(
			BX('mail_connect_cancel_btn'),
			'click',
			function (e)
			{
				closeForm();
			}
		);

		<?php  if (!empty($mailbox)): ?>

		var deletePopup = false;
		BX.bind(
			BX('mail_connect_disconnect_btn'),
			'click',
			function (e)
			{
				var button = BX('mail_connect_disconnect_btn');

				if (button.disabled)
				{
					return false;
				}

				BX.addClass(button, 'ui-btn-wait');
				button.disabled = true;

				if (deletePopup === false)
				{
					deletePopup = new BX.PopupWindow('delete-mailbox-confirm', null, {
						closeIcon: true,
						closeByEsc: true,
						overlay: true,
						lightShadow: true,
						titleBar: '<?=\CUtil::jsEscape(getMessage('MAIL_MAILBOX_REMOVE_CONFIRM')) ?>',
						content: '<?=\CUtil::jsEscape(getMessage('MAIL_MAILBOX_REMOVE_CONFIRM_TEXT')) ?>',
						buttons: [
							new BX.PopupWindowButton({
								className: 'popup-window-button-decline',
								text: '<?=\CUtil::jsEscape(getMessage('MAIL_CLIENT_CONFIG_BTN_DISCONNECT')) ?>',
								events: {
									click: function()
									{
										this.popupWindow.close();

										var pr = BX.ajax.runComponentAction(
											'bitrix:mail.client.config',
											'delete',
											{
												mode: 'class',
												data: {
													id: form.elements['fields[mailbox_id]'].value
												}
											}
										);

										pr.then(
											function (json)
											{
												top.BX.SidePanel.Instance.postMessage(
													window,
													'mail-mailbox-config-delete',
													{
														id: form.elements['fields[mailbox_id]'].value
													}
												);

												closeForm();
											},
											function (json)
											{
												button.disabled = false;
												BX.removeClass(button, 'ui-btn-wait');
											}
										);
									}
								}
							}),
							new BX.PopupWindowButtonLink({
								text: '<?=CUtil::jsEscape(getMessage('MAIL_CLIENT_CONFIG_BTN_CANCEL')) ?>',
								className: 'popup-window-button-link',
								events: {
									click: function()
									{
										this.popupWindow.close();

										button.disabled = false;
										BX.removeClass(button, 'ui-btn-wait');
									}
								}
							})
						]
					});
				}

				deletePopup.show();
			}
		);

		var mailboxData = <?=\Bitrix\Main\Web\Json::encode(array(
			'ID'       => $mailbox['ID'],
			'EMAIL'    => $mailbox['EMAIL'],
			'NAME'     => $mailbox['NAME'],
			'USERNAME' => $mailbox['USERNAME'],
			'SERVER'   => $mailbox['SERVER'],
			'PORT'     => $mailbox['PORT'],
			'USE_TLS'  => $mailbox['USE_TLS'],
			'LOGIN'    => $mailbox['LOGIN'],
			'LINK'     => $mailbox['LINK'],
			'OPTIONS'  => array(
				'flags' => $mailbox['OPTIONS']['flags'],
				'imap'  => $mailbox['OPTIONS']['imap'],
			),
		)) ?>;

		<?php  $imapDirs = array_map(
			function ($dirName) use ($mailbox)
			{
				return Bitrix\Mail\Helper\MessageFolder::getFormattedPath($dirName, $mailbox['OPTIONS']);
			},
			(array) $mailbox['OPTIONS']['imap']['dirs']
		) ?>

		// this is to preserve dirs order
		mailboxData.OPTIONS.imap.dirs = <?=json_encode(array_combine(
			$imapDirsList = Main\Text\Encoding::convertEncoding(array_keys($imapDirs), SITE_CHARSET, 'UTF-8'),
			Main\Text\Encoding::convertEncoding(array_values($imapDirs), SITE_CHARSET, 'UTF-8')
		)) ?>;

		mailboxData.OPTIONS.imap.dirsList = <?=json_encode($imapDirsList) ?>;

		BXMailMailbox.init(mailboxData);

		var applyDirs = function (data)
		{
			form.__extData = data;
		};

		BX.bind(
			BX('mail_connect_mb_imap_dirs_link'),
			'click',
			function (e)
			{
				e.preventDefault();

				BXMailMailbox.setupDirs(applyDirs);
			}
		);

		if ('#mail-cfg-dirs' == window.location.hash)
		{
			window.location.hash = '#mail-cfg-dummy';
			BXMailMailbox.setupDirs(applyDirs);
		}

		<?php  endif ?>

		<?php  if (!empty($settings['oauth']) && !empty($settings['oauth_user'])): ?>

		BX.onCustomEvent(
			'OnMailOAuthBCompleted',
			[
				'<?=\CUtil::jsEscape($settings['oauth']->getStoredUid()) ?>',
				'<?=\CUtil::jsEscape($settings['oauth']->getUrl()) ?>',
				<?=\Bitrix\Main\Web\Json::encode($settings['oauth_user']) ?>,
				true
			]
		);

		<?php  endif ?>

	})();

	<?php 

	function get_plural_messages($prefix)
	{
		global $MESS;

		$result = array();

		$k = 0;
		while ($form = getMessage($prefix.'PLURAL_'.++$k))
			$result[] = $form;

		return $result;
	}

	// http://localization-guide.readthedocs.org/en/latest/l10n/pluralforms.html
	function plural_form($n, $forms)
	{
		switch (LANGUAGE_ID)
		{
			case 'ru':
			case 'ua':
				$p = $n%10 == 1 && $n%100 != 11 ? 0 : ($n%10 >= 2 && $n%10 <= 4 && ($n%100 < 10 || $n%100 >= 20) ? 1 : 2);
				break;
			case 'en':
			case 'de':
			case 'es':
				$p = $n == 1 ? 0 : 1;
				break;
		}

		return isset($forms[$p]) ? $forms[$p] : end($forms);
	}

	?>

	function showLicenseInfoPopup(id)
	{
		var titles = {
			'age': '<?=CUtil::jsEscape(getMessage('MAIL_MAILBOX_LICENSE_AGE_LIMIT_TITLE')) ?>'
		};

		var descrs = {
			'age': '<?=CUtil::jsEscape(getMessage(
				'MAIL_MAILBOX_LICENSE_AGE_LIMIT_DESCR',
				array(
					'#LIMIT#' => $maxAgeLimit,
					'#DAYS#' => plural_form($maxAgeLimit, get_plural_messages('MAIL_MAILBOX_DAYS_')),
				)
			)) ?>'
		};

		B24.licenseInfoPopup.show(
			'mail_setup_'+id,
			titles[id],
			descrs[id]
		);
	}

</script>
