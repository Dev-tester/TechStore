<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Localization\Loc;

// init
Loc::loadMessages(__FILE__);
\CJSCore::init(array('sidepanel', 'action_dialog', 'loader'));
\Bitrix\Main\UI\Extension::load('ui.buttons');
\Bitrix\Main\UI\Extension::load('ui.buttons.icons');

if ($arResult['FATAL'])
{
	return;
}

// some vars
$uriAjax = new \Bitrix\Main\Web\Uri($arResult['CUR_URI']);
$uriAjax->addParams(array(
	'IS_AJAX' => 'Y',
	$arResult['NAVIGATION_ID'] => $arResult['CURRENT_PAGE']
));
$uriAjax->deleteParams([
	LandingBaseComponent::NAVIGATION_ID
]);
if (defined('SITE_TEMPLATE_ID'))
{
	$isBitrix24Template = SITE_TEMPLATE_ID === 'bitrix24';
}

// title
$bodyClass = $APPLICATION->GetPageProperty('BodyClass');
$APPLICATION->SetPageProperty('BodyClass', ($bodyClass ? $bodyClass.' ' : '') . 'pagetitle-toolbar-field-view');
?>

<?php
if ($isBitrix24Template)
{
	$this->SetViewTarget('inside_pagetitle');
}
?>

<?php if (!$isBitrix24Template):?>
<div class="tasks-interface-filter-container">
<?php endif;?>

	<div class="pagetitle-container<?php if (!$isBitrix24Template) {?> pagetitle-container-light<?php }?> pagetitle-flexible-space">
		<?php $APPLICATION->IncludeComponent(
			'bitrix:main.ui.filter',
			'',
			array(
				'FILTER_ID' => $arParams['FILTER_ID'],
				'GRID_ID' => $arParams['FILTER_ID'],
				'FILTER' => $arResult['FILTER'],
				'FILTER_PRESETS' => $arResult['FILTER_PRESETS'],
				'ENABLE_LABEL' => true,
				'ENABLE_LIVE_SEARCH' => true,
				'RESET_TO_DEFAULT_MODE' => true
			),
			$this->__component,
			array('HIDE_ICONS' => true)
		);?>
		<script type="text/javascript">
			var landingAjaxPath = '<?= \CUtil::jsEscape($uriAjax->getUri());?>';
			var landingFilterId = '<?= \CUtil::jsEscape($arParams['FILTER_ID']);?>';
		</script>
	</div>

	<div class="landing-filter-buttons-container">

		<span class="ui-btn ui-btn-light-border ui-btn-themes landing-recycle-bin-btn" id="landing-recycle-bin">
			<?= Loc::getMessage('LANDING_TPL_RECYCLE_BIN');?>
		</span>

		<?php if ($arParams['SETTING_LINK']):
			// for compatibility
			if (!is_array($arParams['SETTING_LINK']))
			{
				$arParams['SETTING_LINK'] = [[
					'TITLE' => '',
					'LINK' => $arParams['SETTING_LINK']
				]];
			}
			?>
			<script type="text/javascript">
				var landingSettingsButtons = [
					<?php 
					$bFirst = true;
					foreach ($arParams['SETTING_LINK'] as $link):?>
						<?php if (isset($link['LINK']) && isset($link['TITLE'])):?>
						<?= !$bFirst ? ',' : '';?>{
							href: '<?= \CUtil::JSEscape($link['LINK']);?>',
							text: '<?= \CUtil::JSEscape($link['TITLE']);?>'
						}
						<?php 
						$bFirst = false;
						endif;?>
					<?php endforeach;?>
				];
			</script>
			<a class="ui-btn ui-btn-light-border ui-btn-themes ui-btn-icon-setting" id="landing-menu-settings" href="#"></a>
		<?php endif;?>

		<?php if ($arParams['FOLDER_SITE_ID']):?>
		<a class="ui-btn ui-btn-light-border ui-btn-icon-add-folder ui-btn-themes landing-filter-buttons-add-folder" <?php 
			?>id="landing-create-folder" <?php 
			?>data-action="<?= \CUtil::jsEscape(Loc::getMessage('LANDING_TPL_CREATE_FOLDER_ACTION'));?>" <?php 
			?>data-siteId="<?= $arParams['FOLDER_SITE_ID'];?>" <?php 
			?>href="javascript:void(0);" <?php 
			?>title="<?= Loc::getMessage('LANDING_TPL_CREATE_FOLDER');?>"></a>
		<?php else:?>
		&nbsp;&nbsp;
		<?php endif;?>

		<?php 
		if (isset($arParams['BUTTONS']) && is_array($arParams['BUTTONS']))
		{
			if (count($arParams['BUTTONS']) == 1)
			{
				$buuton = array_shift($arParams['BUTTONS']);
				if (isset($buuton['LINK']) && isset($buuton['TITLE']))
				{
				?>
				<div class="pagetitle-container pagetitle-align-right-container">
					<a href="<?= \htmlspecialcharsbx($buuton['LINK']);?>" id="landing-create-element" class="ui-btn ui-btn-md ui-btn-primary ui-btn-icon-add landing-filter-action-link">
						<?= \htmlspecialcharsbx($buuton['TITLE']);?>
					</a>
				</div>
				<?php 
				}
			}
			else
			{
				$buuton = array_shift($arParams['BUTTONS']);
				?>
				<?php if (isset($buuton['LINK']) && isset($buuton['TITLE'])):?>
				<div class="pagetitle-container pagetitle-align-right-container" id="landing-menu-actions">
					<a href="<?= \htmlspecialcharsbx($buuton['LINK']);?>" id="landing-create-element" <?php 
						?>class="ui-btn ui-btn-md ui-btn-primary ui-btn-icon-add landing-filter-action-link ui-btn-dropdown">
						<?= \htmlspecialcharsbx($buuton['TITLE']);?>
					</a>
				</div>
				<script type="text/javascript">
					var landingCreateButtons = [
						<?php foreach ($arParams['BUTTONS'] as $buuton):?>
							<?php if (isset($buuton['LINK']) && isset($buuton['TITLE'])):?>
							{
								href: '<?= \CUtil::JSEscape($buuton['LINK']);?>',
								text: '<?= \CUtil::JSEscape($buuton['TITLE']);?>'
							},
							<?php endif;?>
						<?php endforeach;?>
						null
					];
				</script>
				<?php endif;?>
				<?php 
			}
		}
		?>
	</div>


<?php if (!$isBitrix24Template):?>
</div>
<?php endif;?>

<?php
if ($isBitrix24Template)
{
	$this->EndViewTarget();
}
?>