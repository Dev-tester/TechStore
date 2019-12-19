<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php 
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Location;

Loc::loadMessages(__FILE__);

if ($arParams["UI_FILTER"])
{
	$arParams["USE_POPUP"] = true;
}

?>

<?php if(!empty($arResult['ERRORS']['FATAL'])):?>

	<?php foreach($arResult['ERRORS']['FATAL'] as $error):?>
		<?php ShowError($error)?>
	<?php endforeach?>

<?php else:?>

	<?php CJSCore::Init();?>
	<?php $GLOBALS['APPLICATION']->AddHeadScript('/bitrix/js/sale/core_ui_widget.js')?>
	<?php $GLOBALS['APPLICATION']->AddHeadScript('/bitrix/js/sale/core_ui_etc.js')?>
	<?php $GLOBALS['APPLICATION']->AddHeadScript('/bitrix/js/sale/core_ui_autocomplete.js');?>

	<div id="sls-<?=$arResult['RANDOM_TAG']?>" class="bx-sls <?php if(strlen($arResult['MODE_CLASSES'])):?> <?=$arResult['MODE_CLASSES']?><?php endif?>">

		<?php if(is_array($arResult['DEFAULT_LOCATIONS']) && !empty($arResult['DEFAULT_LOCATIONS'])):?>

			<div class="bx-ui-sls-quick-locations quick-locations">

				<?php foreach($arResult['DEFAULT_LOCATIONS'] as $lid => $loc):?>
					<a href="javascript:void(0)" data-id="<?=intval($loc['ID'])?>" class="quick-location-tag"><?=htmlspecialcharsbx($loc['NAME'])?></a>
				<?php endforeach?>

			</div>

		<?php endif?>

		<?php  $dropDownBlock = $arParams["UI_FILTER"] ? "dropdown-block-ui" : "dropdown-block"; ?>
		<div class="<?=$dropDownBlock?> bx-ui-sls-input-block">

			<span class="dropdown-icon"></span>
			<input type="text" autocomplete="off" name="<?=$arParams['INPUT_NAME']?>" value="<?=$arResult['VALUE']?>" class="dropdown-field" placeholder="<?=Loc::getMessage('SALE_SLS_INPUT_SOME')?> ..." />

			<div class="dropdown-fade2white"></div>
			<div class="bx-ui-sls-loader"></div>
			<div class="bx-ui-sls-clear" title="<?=Loc::getMessage('SALE_SLS_CLEAR_SELECTION')?>"></div>
			<div class="bx-ui-sls-pane"></div>

		</div>

		<script type="text/html" data-template-id="bx-ui-sls-error">
			<div class="bx-ui-sls-error">
				<div></div>
				{{message}}
			</div>
		</script>

		<script type="text/html" data-template-id="bx-ui-sls-dropdown-item">
			<div class="dropdown-item bx-ui-sls-variant">
				<span class="dropdown-item-text">{{display_wrapped}}</span>
				<?php if($arResult['ADMIN_MODE']):?>
					[{{id}}]
				<?php endif?>
			</div>
		</script>

		<div class="bx-ui-sls-error-message">
			<?php if(!$arParams['SUPPRESS_ERRORS']):?>
				<?php if(!empty($arResult['ERRORS']['NONFATAL'])):?>

					<?php foreach($arResult['ERRORS']['NONFATAL'] as $error):?>
						<?php ShowError($error)?>
					<?php endforeach?>

				<?php endif?>
			<?php endif?>
		</div>

	</div>

	<script>

		if (!window.BX && top.BX)
			window.BX = top.BX;

		<?php if(strlen($arParams['JS_CONTROL_DEFERRED_INIT'])):?>
			if(typeof window.BX.locationsDeferred == 'undefined') window.BX.locationsDeferred = {};
			window.BX.locationsDeferred['<?=$arParams['JS_CONTROL_DEFERRED_INIT']?>'] = function(){
		<?php endif?>

			<?php if(strlen($arParams['JS_CONTROL_GLOBAL_ID'])):?>
				if(typeof window.BX.locationSelectors == 'undefined') window.BX.locationSelectors = {};
				window.BX.locationSelectors['<?=$arParams['JS_CONTROL_GLOBAL_ID']?>'] = 
			<?php endif?>

			new BX.Sale.component.location.selector.search(<?=CUtil::PhpToJSObject(array(

				// common
				'scope' => 'sls-'.$arResult['RANDOM_TAG'],
				'source' => $this->__component->getPath().'/get.php',
				'query' => array(
					'FILTER' => array(
						'EXCLUDE_ID' => intval($arParams['EXCLUDE_SUBTREE']),
						'SITE_ID' => $arParams['FILTER_BY_SITE'] && !empty($arParams['FILTER_SITE_ID']) ? $arParams['FILTER_SITE_ID'] : ''
					),
					'BEHAVIOUR' => array(
						'SEARCH_BY_PRIMARY' => $arParams['SEARCH_BY_PRIMARY'] ? '1' : '0',
						'LANGUAGE_ID' => LANGUAGE_ID
					),
				),

				'selectedItem' => !empty($arResult['LOCATION']) ? $arResult['LOCATION']['VALUE'] : false,
				'knownItems' => $arResult['KNOWN_ITEMS'],
				'provideLinkBy' => $arParams['PROVIDE_LINK_BY'],

				'messages' => array(
					'nothingFound' => Loc::getMessage('SALE_SLS_NOTHING_FOUND'),
					'error' => Loc::getMessage('SALE_SLS_ERROR_OCCURED'),
				),

				// "js logic"-related part
				'callback' => $arParams['JS_CALLBACK'],
				'useSpawn' => $arParams['USE_JS_SPAWN'] == 'Y',
				'usePopup' => ($arParams["USE_POPUP"] ? true : false),
				'initializeByGlobalEvent' => $arParams['INITIALIZE_BY_GLOBAL_EVENT'],
				'globalEventScope' => $arParams['GLOBAL_EVENT_SCOPE'],

				// specific
				'pathNames' => $arResult['PATH_NAMES'], // deprecated
				'types' => $arResult['TYPES'],

			), false, false, true)?>);

		<?php if(strlen($arParams['JS_CONTROL_DEFERRED_INIT'])):?>
			};
		<?php endif?>

	</script>

<?php endif?>