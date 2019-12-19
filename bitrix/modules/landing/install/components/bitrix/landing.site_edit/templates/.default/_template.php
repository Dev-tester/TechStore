<?php
namespace Bitrix\Landing\Components\LandingEdit;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if ($arResult['ERRORS'])
{
	?><div class="landing-message-label error"><?= implode("\n", $arResult['ERRORS'])?></div><?php 
}

if ($arResult['FATAL'])
{
	return;
}



$row = $arResult['SITE'];
$hooks = $arResult['HOOKS'];

if ($row['ID']['CURRENT'])
{
	$APPLICATION->setTitle(Loc::getMessage('LANDING_TPL_TITLE_EDIT'));
}
else
{
	$APPLICATION->setTitle(Loc::getMessage('LANDING_TPL_TITLE_ADD'));
}


include 'template_class.php';

$template = new Template($arResult);
$domains = $arResult['DOMAINS'];
?>

<form action="<?= POST_FORM_ACTION_URI?>" method="post">
	<input type="hidden" name="fields[SAVE_FORM]" value="Y" />
	<?= bitrix_sessid_post()?>

	<div class="landing-info-panel">
		<div class="landing-info-panel-title">
			<input type="text" name="fields[TITLE]" value="<?= $row['TITLE']['CURRENT']?>" placeholder="<?= $row['TITLE']['TITLE']?>">
		</div>
	</div>

	<div class="landing-options landing-options-main">
		<div class="landing-options-item-destination-wrap">
			<div>
				<div class="landing-options-item landing-options-item-destination">
					<span class="landing-options-item-param"><?= $row['CODE']['TITLE']?></span>
					<div class="landing-options-item-inner">
						<?php if (\Bitrix\Main\Loader::includeModule('bitrix24')):?>
						<input type="hidden" name="fields[CODE]" value="<?= $row['CODE']['CURRENT']?>" >
						<input type="text" name="fields[DOMAIN_ID]" <?php 
							?>value="<?= isset($domains[$row['DOMAIN_ID']['CURRENT']]['DOMAIN']) ? $domains[$row['DOMAIN_ID']['CURRENT']]['DOMAIN'] : $row['DOMAIN_ID']['CURRENT']?>" <?php 
							?>class="landing-options-input landing-options-input-small">
						<?php else:?>
						<select name="fields[DOMAIN_ID]" class="landing-options-input">
							<?php foreach ($arResult['DOMAINS'] as $item):?>
							<option value="<?= $item['ID']?>"<?php if ($item['ID'] == $row['DOMAIN_ID']['CURRENT']){?> selected="selected"<?php }?>>
								<?= \htmlspecialcharsbx($item['DOMAIN'])?>
							</option>
							<?php endforeach;?>
						</select>
						<input type="text" name="fields[CODE]" value="<?= $row['CODE']['CURRENT']?>" class="landing-options-input landing-options-input-small">
						<?php endif;?>
					</div>
				</div>
				<div class="landing-options-item landing-options-item-destination">
					<span class="landing-options-item-param"><?= $row['ACTIVE']['TITLE']?></span>
					<div class="landing-options-item-inner">
						<div style="display: none;">
							<input type="checkbox" name="fields[ACTIVE]" id="action-public-checkbox" value="Y"<?php if ($row['ACTIVE']['CURRENT'] == 'Y') {?> checked="checked"<?php }?>>
						</div>
						<span class="landing-options-public-status landing-options-public-status-<?= $row['ACTIVE']['CURRENT'] == 'Y' ? 'active' : 'unactive'?>" <?php 
							?>id="action-public-status" <?php 
							?>data-retitle="<?= Loc::getMessage('LANDING_TPL_PUBLIC_MESS_' . ($row['ACTIVE']['CURRENT'] == 'Y' ? 'N' : 'Y'))?>">
							<?= Loc::getMessage('LANDING_TPL_PUBLIC_MESS_' . $row['ACTIVE']['CURRENT'])?>
						</span>
						<button class="landing-options-button" id="action-public" data-retitle="<?= Loc::getMessage('LANDING_TPL_PUBLIC_' . $row['ACTIVE']['CURRENT'])?>">
							<?= Loc::getMessage('LANDING_TPL_PUBLIC_' . ($row['ACTIVE']['CURRENT'] == 'Y' ? 'N' : 'Y'))?>
						</button>
					</div>
				</div>
				<?php $template->showHookBlock('B24BUTTON');?>
			</div>
		</div>
	</div>

	<div id="action-additional" class="landing-additional-block" data-block="action-additional-block">
		<div class="landing-additional-alt">
			<div class="landing-additional-alt-more">
				<?= Loc::getMessage('LANDING_TPL_ADDITIONAL');?>
			</div>
			<div class="landing-additional-alt-promo">
				<span class="landing-additional-alt-promo-text"><?= Loc::getMessage('LANDING_TPL_ADDITIONAL_FAVICON');?></span>
				<span class="landing-additional-alt-promo-text"><?= Loc::getMessage('LANDING_TPL_ADDITIONAL_METRIKA');?></span>
				<span class="landing-additional-alt-promo-text"><?= Loc::getMessage('LANDING_TPL_ADDITIONAL_BG');?></span>
				<span class="landing-additional-alt-promo-text"><?= Loc::getMessage('LANDING_TPL_ADDITIONAL_MAPS');?></span>
				<span class="landing-additional-alt-promo-text"><?= Loc::getMessage('LANDING_TPL_ADDITIONAL_PAGES');?></span>
				<span class="landing-additional-alt-promo-text"><?= Loc::getMessage('LANDING_TPL_ADDITIONAL_SEO');?></span>
				<span class="landing-additional-alt-promo-text"><?= Loc::getMessage('LANDING_TPL_ADDITIONAL_HTMLCSS');?></span>
			</div>
		</div>
	</div>

	<div id="action-additional-block" class="landing-options landing-options-additional" style="display: none;">
		<div class="landing-options-item-destination-wrap">
			<div>
				<div class="landing-options-item-destination-group">
					<?php $template->showHookBlock('YACOUNTER');?>
					<?php $template->showHookBlock('GACOUNTER');?>
				</div>
				<div class="landing-options-item-destination-group">
					<?php $template->showHookBlock('BACKGROUND', array('group' => true));?>
				</div>
				<div class="landing-options-item-destination-group">
					<?php $template->showHookBlock('PADDING');?>
				</div>
				<div class="landing-options-item-destination-group">
					<?php $template->showHookBlock('GMAP');?>
				</div>
				<div class="landing-options-item-destination-group">
					<?php if (!empty($arResult['LANDINGS'])):?>
					<div class="landing-options-item landing-options-item-destination">
						<span class="landing-options-item-param"><?= Loc::getMessage('LANDING_TPL_PAGE_SELECT')?></span>
						<div class="landing-options-item-inner">
							<span class="landing-option-fn"><?= $row['LANDING_ID_INDEX']['TITLE']?></span>
							<select name="fields[LANDING_ID_INDEX]" class="landing-options-input">
								<option></option>
								<?php foreach ($arResult['LANDINGS'] as $item):?>
								<option value="<?= $item['ID']?>"<?php if ($item['ID'] == $row['LANDING_ID_INDEX']['CURRENT']){?> selected="selected"<?php }?>>
									<?= \htmlspecialcharsbx($item['TITLE'])?>
								</option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="landing-options-item landing-options-item-destination">
						<span class="landing-options-item-param">&nbsp;</span>
						<div class="landing-options-item-inner">
							<span class="landing-option-fn"><?= $row['LANDING_ID_404']['TITLE']?></span>
							<select name="fields[LANDING_ID_404]" class="landing-options-input">
								<option></option>
								<?php foreach ($arResult['LANDINGS'] as $item):?>
								<option value="<?= $item['ID']?>"<?php if ($item['ID'] == $row['LANDING_ID_404']['CURRENT']){?> selected="selected"<?php }?>>
									<?= \htmlspecialcharsbx($item['TITLE'])?>
								</option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<?php else:?>
					<div class="landing-options-item landing-options-item-destination">
						<span class="landing-options-item-param"><?= Loc::getMessage('LANDING_TPL_PAGE_SELECT')?></span>
						<div class="landing-options-item-inner">
							<span class="landing-field-label-title">
								<?= Loc::getMessage('LANDING_TPL_PAGE_SELECT_EMPTY')?>
							</span>
						</div>
					</div>
					<?php endif;?>
				</div>
				<div class="landing-options-item-destination-group">
					<?php $template->showHookBlock('METAROBOTS');?>
				</div>
				<div class="landing-options-item-destination-group">
					<?php $template->showHookBlock('THEME');?>
				</div>
				<div class="landing-options-item-destination-group">
					<?php $template->showHookBlock('UP');?>
				</div>
				<?php if (isset($hooks['HEADBLOCK']) || isset($hooks['CUSTOMCSS'])):?>
					<div class="landing-options-item landing-options-item-destination">
						<span class="landing-options-item-param"><?= Loc::getMessage('LANDING_TPL_FIELD_HTMLCSS')?></span>
						<div class="landing-options-item-inner">
							<?php $template->showHookBlock('HEADBLOCK', array('wrapper' => false));?>
							<?php $template->showHookBlock('CUSTOMCSS', array('wrapper' => false));?>
						</div>
					</div>
				<?php endif;?>
			</div>
		</div>
	</div>

	<div class="landing-edit-footer-fixed pinable-block">
		<div class="landing-form-footer-container">
			<button class="webform-small-button webform-small-button-accept">
				<span class="webform-small-button-text">
					<?= Loc::getMessage('LANDING_TPL_BUTTON_' . ($row['ID']['CURRENT'] ? 'SAVE' : 'ADD'))?>
				</span>
			</button>
			<a class="landing-button-link" id="action-close" href="<?= $arParams['PAGE_URL_SITES']?>">
				<?= Loc::getMessage('LANDING_TPL_BUTTON_CANCEL')?>
			</a>
		</div>
	</div>

</form>

<script type="text/javascript">
	BX.ready(function(){
		new BX.Landing.EditComponent({
		});
	});
</script>