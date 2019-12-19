<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Landing\Manager;
use \Bitrix\Main\Page\Asset;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\ModuleManager;
\Bitrix\Main\UI\Extension::load("ui.fonts.opensans");
Loc::loadMessages(__FILE__);

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();

// some errors
if ($arResult['ERRORS'])
{
	foreach ($arResult['ERRORS'] as $code => $error)
	{
		echo '<p style="color: red;">' . $error . '</p>';
	}
}

// show message for license renew if need
if (empty($arResult['DEMO']) && !isset($arResult['ERRORS']['ACCESS_DENIED']))
{
	if (ModuleManager::isModuleInstalled('bitrix24'))
	{
		\showError(Loc::getMessage('LANDING_TPL_EMPTY_REPO_SERVICE'));
	}
	else
	{
		if (Manager::licenseIsValid())
		{
			\showError(Loc::getMessage('LANDING_TPL_EMPTY_REPO_SERVICE'));
		}
		else
		{
			$link = Manager::isB24()
					? 'https://www.bitrix24.ru/prices/self-hosted.php'
					: 'https://www.1c-bitrix.ru/buy/cms.php#tab-updates-link';
			?>
			<div class="landing-license-wrapper">
				<div class="landing-license-inner">
					<div class="landing-license-icon-container">
						<div class="landing-license-icon"></div>
					</div>
					<div class="landing-license-info">
						<span class="landing-license-info-text"><?= Loc::getMessage('LANDING_TPL_EMPTY_REPO_EXPIRED');?></span>
						<div class="landing-license-info-btn">
							<?= Loc::getMessage('LANDING_TPL_EMPTY_REPO_EXPIRED_LINK', array(
								'#LINK1#' => '<a href="' . $link . '" target="_blank" class="landing-license-info-link">',
								'#LINK2#' => '</a>'
							));?>
						</div>
					</div>
				</div>
			</div>
			<?php 
		}
	}
}

// exit on fatal
if ($arResult['FATAL'])
{
	return;
}

// title
$bodyClass = $APPLICATION->GetPageProperty('BodyClass');
$APPLICATION->SetPageProperty(
	'BodyClass',
	($bodyClass ? $bodyClass.' ' : '') . 'no-all-paddings no-background'
);
\Bitrix\Landing\Manager::setPageTitle(
	Loc::getMessage('LANDING_TPL_TITLE')
);

// additional assets
\CJSCore::Init(array('popup', 'action_dialog', 'loader', 'sidepanel'));
Asset::getInstance()->addCSS('/bitrix/components/bitrix/landing.sites/templates/.default/style.css');
Asset::getInstance()->addJS('/bitrix/components/bitrix/landing.sites/templates/.default/script.js');
?>

<div class="grid-tile-wrap" id="grid-tile-wrap">
	<div class="grid-tile-inner" id="grid-tile-inner">

<?php 
foreach ($arResult['DEMO'] as $item):
	// skip site group items
	if (
		isset($item['DATA']['site_group_item']) &&
		$item['DATA']['site_group_item'] == 'Y'
	)
	{
		continue;
	}
	
	$tpl = (
		(
			defined('SMN_SITE_ID') ||
			!$arParams['SITE_ID']
		)
		&&
		isset($item['DATA']['items'][0])
	)
		? $item['DATA']['items'][0]
		: $item['ID'];

	if (!isset($item['EXTERNAL_URL']))
	{
		$uriSelect = new \Bitrix\Main\Web\Uri($arResult['CUR_URI']);
		$uriSelect->addParams([
			'tpl' => $tpl
		]);
		$uriSelect->deleteParams([
			'select'
		]);
		$previewUrl = $uriSelect->getUri();
	}
	else if (isset($item['EXTERNAL_URL']['href']))
	{
		$previewUrl = $item['EXTERNAL_URL']['href'];
	}
	else
	{
		$previewUrl = '';
	}
	?>
	<?php if ($item['AVAILABLE']):?>
	<span data-href="<?= $previewUrl;?>" id="landing-demo-<?= \htmlspecialcharsbx($tpl);?>" <?php 
		?>class="landing-template-pseudo-link landing-item landing-item-hover<?= $arResult['LIMIT_REACHED'] ? ' landing-item-payment' : '';?>" <?php 
		?><?php if (isset($item['EXTERNAL_URL']['width'])){?>data-slider-width="<?= (int)$item['EXTERNAL_URL']['width'];?>"<?php }?>>
	<?php else:?>
	<span class="landing-item landing-item-hover landing-item-unactive">
	<?php endif;?>
		<span class="landing-item-inner">
			<div class="landing-title">
				<div class="landing-title-wrap">
					<div class="landing-title-overflow"><?= \htmlspecialcharsbx($item['TITLE'])?></div>
				</div>
			</div>
			<?php if (trim($item['DESCRIPTION'])):?>
				<span class="landing-item-cover landing-item-cover-short">
					<?php if ($item['PREVIEW']):?>
						<img class="landing-item-cover-img"
							 src="<?= \htmlspecialcharsbx($item['PREVIEW'])?>"
							 srcset="<?= \htmlspecialcharsbx($item['PREVIEW2X'] ? $item['PREVIEW2X'] : $item['PREVIEW'])?> 2x,
									<?= \htmlspecialcharsbx($item['PREVIEW3X'] ? $item['PREVIEW3X'] : $item['PREVIEW'])?> 3x">
					<?php endif;?>
				</span>
				<span class="landing-item-description">
					<span class="landing-item-desc-inner">
						<span class="landing-item-desc-overflow">
							<span class="landing-item-desc-height">
								<?= \htmlspecialcharsbx($item['DESCRIPTION'])?>
							</span>
						</span>
						<span class="landing-item-desc-open"></span>
					</span>
				</span>
			<?php else:?>
				<span class="landing-item-cover">
					<?php if ($item['PREVIEW']):?>
						<img class="landing-item-cover-img"
							 src="<?= \htmlspecialcharsbx($item['PREVIEW'])?>"
							 srcset="<?= \htmlspecialcharsbx($item['PREVIEW2X'] ? $item['PREVIEW2X'] : $item['PREVIEW'])?> 2x,
									<?= \htmlspecialcharsbx($item['PREVIEW3X'] ? $item['PREVIEW3X'] : $item['PREVIEW'])?> 3x">
					<?php endif;?>
				</span>
			<?php endif?>
		</span>
	<?php if (!$item['AVAILABLE']):?>
	</span>
	<?php else:?>
	</span>
	<?php endif;?>
<?php endforeach;?>

	</div>
</div>

<?php if ($arResult['NAVIGATION']->getPageCount() > 1):?>
	<div class="<?= (defined('ADMIN_SECTION') && ADMIN_SECTION === true) ? '' : 'landing-navigation';?>">
		<?php $APPLICATION->IncludeComponent(
			'bitrix:main.pagenavigation',
			'',//grid
			array(
				'NAV_OBJECT' => $arResult['NAVIGATION'],
				'SEF_MODE' => 'N',
				'BASE_LINK' => $arResult['CUR_URI'] .
							   ((defined('ADMIN_SECTION') && ADMIN_SECTION === true) ? '&slider' : '')//@tmp bug #105866
			),
			false
		);?>
	</div>
<?php endif;?>

<a class="landing-license-banner" href="javascript:void(0)" onclick="BX.SidePanel.Instance.open('<?= SITE_DIR;?>marketplace/?placement=site_templates');">
	<div class="landing-license-banner-icon">
		<div class="landing-license-banner-icon-arrow"></div>
	</div>
	<div class="landing-license-banner-title">
		<?= Loc::getMessage('LANDING_TPL_LOAD_APP_TEMPLATE');?>
	</div>
</a>

<script type="text/javascript">
	BX.ready(function ()
	{
		<?php if ($arResult['LIMIT_REACHED']):?>
		if (typeof BX.Landing.PaymentAlert !== 'undefined')
		{
			BX.Landing.PaymentAlert({
				nodes: BX('grid-tile-wrap').querySelectorAll('.landing-item-payment'),
				title: '<?= \CUtil::jsEscape(Loc::getMessage('LANDING_TPL_LIMIT_REACHED_TITLE'));?>',
				message: '<?= ($arParams['SITE_ID'] > 0)
					? \CUtil::jsEscape(Loc::getMessage('LANDING_TPL_PAGE_LIMIT_REACHED_TEXT'))
					: \CUtil::jsEscape(Loc::getMessage('LANDING_TPL_SITE_LIMIT_REACHED_TEXT'));
					?>'
			});
		}
		<?php endif;?>

		<?php if ($select = $request->get('select')):?>
		BX.fireEvent(
			BX('landing-demo-<?= \CUtil::JSEscape($select);?>'),
			'click'
		);
		<?php endif;?>
	})
</script>