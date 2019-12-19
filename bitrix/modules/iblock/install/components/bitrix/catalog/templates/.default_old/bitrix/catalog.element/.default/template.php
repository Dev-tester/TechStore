<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
$templateLibrary = array('popup');
$currencyList = '';
if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BASIS_PRICE' => $strMainID.'_basis_price',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'BASKET_ACTIONS' => $strMainID.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);
?><div class="bx_item_detail <?php  echo $templateData['TEMPLATE_CLASS']; ?>" id="<?php  echo $arItemIDs['ID']; ?>">
<?php 
if ('Y' == $arParams['DISPLAY_NAME'])
{
?>
<div class="bx_item_title"><h1><span><?php 
	echo (
		isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
		? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
		: $arResult["NAME"]
	); ?>
</span></h1></div>
<?php 
}
reset($arResult['MORE_PHOTO']);
$arFirstPhoto = current($arResult['MORE_PHOTO']);
?>
	<div class="bx_item_container">
		<div class="bx_lt">
<div class="bx_item_slider" id="<?php  echo $arItemIDs['BIG_SLIDER_ID']; ?>">
	<div class="bx_bigimages" id="<?php  echo $arItemIDs['BIG_IMG_CONT_ID']; ?>">
	<div class="bx_bigimages_imgcontainer">
	<span class="bx_bigimages_aligner"><img id="<?php  echo $arItemIDs['PICT']; ?>" src="<?php  echo $arFirstPhoto['SRC']; ?>" alt="<?php  echo $strAlt; ?>" title="<?php  echo $strTitle; ?>"></span>
<?php 
if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
{
	if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS']))
	{
		if (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF'])
		{
?>
	<div class="bx_stick_disc right bottom" id="<?php  echo $arItemIDs['DISCOUNT_PICT_ID'] ?>"><?php  echo -$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>%</div>
<?php 
		}
	}
	else
	{
?>
	<div class="bx_stick_disc right bottom" id="<?php  echo $arItemIDs['DISCOUNT_PICT_ID'] ?>" style="display: none;"></div>
<?php 
	}
}
if ($arResult['LABEL'])
{
?>
	<div class="bx_stick average left top" id="<?php  echo $arItemIDs['STICKER_ID'] ?>" title="<?php  echo $arResult['LABEL_VALUE']; ?>"><?php  echo $arResult['LABEL_VALUE']; ?></div>
<?php 
}
?>
	</div>
	</div>
<?php 
if ($arResult['SHOW_SLIDER'])
{
	if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS']))
	{
		if (5 < $arResult['MORE_PHOTO_COUNT'])
		{
			$strClass = 'bx_slider_conteiner full';
			$strOneWidth = (100/$arResult['MORE_PHOTO_COUNT']).'%';
			$strWidth = (20*$arResult['MORE_PHOTO_COUNT']).'%';
			$strSlideStyle = '';
		}
		else
		{
			$strClass = 'bx_slider_conteiner';
			$strOneWidth = '20%';
			$strWidth = '100%';
			$strSlideStyle = 'display: none;';
		}
?>
	<div class="<?php  echo $strClass; ?>" id="<?php  echo $arItemIDs['SLIDER_CONT_ID']; ?>">
	<div class="bx_slider_scroller_container">
	<div class="bx_slide">
	<ul style="width: <?php  echo $strWidth; ?>;" id="<?php  echo $arItemIDs['SLIDER_LIST']; ?>">
<?php 
		foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto)
		{
?>
	<li data-value="<?php  echo $arOnePhoto['ID']; ?>" style="width: <?php  echo $strOneWidth; ?>; padding-top: <?php  echo $strOneWidth; ?>;"><span class="cnt"><span class="cnt_item" style="background-image:url('<?php  echo $arOnePhoto['SRC']; ?>');"></span></span></li>
<?php 
		}
		unset($arOnePhoto);
?>
	</ul>
	</div>
	<div class="bx_slide_left" id="<?php  echo $arItemIDs['SLIDER_LEFT']; ?>" style="<?php  echo $strSlideStyle; ?>"></div>
	<div class="bx_slide_right" id="<?php  echo $arItemIDs['SLIDER_RIGHT']; ?>" style="<?php  echo $strSlideStyle; ?>"></div>
	</div>
	</div>
<?php 
	}
	else
	{
		foreach ($arResult['OFFERS'] as $key => $arOneOffer)
		{
			if (!isset($arOneOffer['MORE_PHOTO_COUNT']) || 0 >= $arOneOffer['MORE_PHOTO_COUNT'])
				continue;
			$strVisible = ($key == $arResult['OFFERS_SELECTED'] ? '' : 'none');
			if (5 < $arOneOffer['MORE_PHOTO_COUNT'])
			{
				$strClass = 'bx_slider_conteiner full';
				$strOneWidth = (100/$arOneOffer['MORE_PHOTO_COUNT']).'%';
				$strWidth = (20*$arOneOffer['MORE_PHOTO_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_slider_conteiner';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<?php  echo $strClass; ?>" id="<?php  echo $arItemIDs['SLIDER_CONT_OF_ID'].$arOneOffer['ID']; ?>" style="display: <?php  echo $strVisible; ?>;">
	<div class="bx_slider_scroller_container">
	<div class="bx_slide">
	<ul style="width: <?php  echo $strWidth; ?>;" id="<?php  echo $arItemIDs['SLIDER_LIST_OF_ID'].$arOneOffer['ID']; ?>">
<?php 
			foreach ($arOneOffer['MORE_PHOTO'] as &$arOnePhoto)
			{
?>
	<li data-value="<?php  echo $arOneOffer['ID'].'_'.$arOnePhoto['ID']; ?>" style="width: <?php  echo $strOneWidth; ?>; padding-top: <?php  echo $strOneWidth; ?>"><span class="cnt"><span class="cnt_item" style="background-image:url('<?php  echo $arOnePhoto['SRC']; ?>');"></span></span></li>
<?php 
			}
			unset($arOnePhoto);
?>
	</ul>
	</div>
	<div class="bx_slide_left" id="<?php  echo $arItemIDs['SLIDER_LEFT_OF_ID'].$arOneOffer['ID'] ?>" style="<?php  echo $strSlideStyle; ?>" data-value="<?php  echo $arOneOffer['ID']; ?>"></div>
	<div class="bx_slide_right" id="<?php  echo $arItemIDs['SLIDER_RIGHT_OF_ID'].$arOneOffer['ID'] ?>" style="<?php  echo $strSlideStyle; ?>" data-value="<?php  echo $arOneOffer['ID']; ?>"></div>
	</div>
	</div>
<?php 
		}
	}
}
?>
</div>
		</div>
		<div class="bx_rt">
<?php 
$useBrands = ('Y' == $arParams['BRAND_USE']);
$useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);
if ($useBrands || $useVoteRating)
{
?>
	<div class="bx_optionblock">
<?php 
	if ($useVoteRating)
	{
		?><?php $APPLICATION->IncludeComponent(
			"bitrix:iblock.vote",
			"stars",
			array(
				"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
				"IBLOCK_ID" => $arParams['IBLOCK_ID'],
				"ELEMENT_ID" => $arResult['ID'],
				"ELEMENT_CODE" => "",
				"MAX_VOTE" => "5",
				"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
				"SET_STATUS_404" => "N",
				"DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
				"CACHE_TYPE" => $arParams['CACHE_TYPE'],
				"CACHE_TIME" => $arParams['CACHE_TIME']
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);?><?php 
	}
	if ($useBrands)
	{
		?><?php $APPLICATION->IncludeComponent("bitrix:catalog.brandblock", ".default", array(
			"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
			"IBLOCK_ID" => $arParams['IBLOCK_ID'],
			"ELEMENT_ID" => $arResult['ID'],
			"ELEMENT_CODE" => "",
			"PROP_CODE" => $arParams['BRAND_PROP_CODE'],
			"CACHE_TYPE" => $arParams['CACHE_TYPE'],
			"CACHE_TIME" => $arParams['CACHE_TIME'],
			"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
			"WIDTH" => "",
			"HEIGHT" => ""
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);?><?php 
	}
?>
	</div>
<?php 
}
unset($useVoteRating, $useBrands);
?>
<div class="item_price">
<?php 
$minPrice = (isset($arResult['RATIO_PRICE']) ? $arResult['RATIO_PRICE'] : $arResult['MIN_PRICE']);
$boolDiscountShow = (0 < $minPrice['DISCOUNT_DIFF']);
?>
	<div class="item_old_price" id="<?php  echo $arItemIDs['OLD_PRICE']; ?>" style="display: <?php  echo ($boolDiscountShow ? '' : 'none'); ?>"><?php  echo ($boolDiscountShow ? $minPrice['PRINT_VALUE'] : ''); ?></div>
	<div class="item_current_price" id="<?php  echo $arItemIDs['PRICE']; ?>"><?php  echo $minPrice['PRINT_DISCOUNT_VALUE']; ?></div>
	<div class="item_economy_price" id="<?php  echo $arItemIDs['DISCOUNT_PRICE']; ?>" style="display: <?php  echo ($boolDiscountShow ? '' : 'none'); ?>"><?php  echo ($boolDiscountShow ? GetMessage('CT_BCE_CATALOG_ECONOMY_INFO', array('#ECONOMY#' => $minPrice['PRINT_DISCOUNT_DIFF'])) : ''); ?></div>
</div>
<?php 
unset($minPrice);
if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
{
?>
<div class="item_info_section">
<?php 
	if (!empty($arResult['DISPLAY_PROPERTIES']))
	{
?>
	<dl>
<?php 
		foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp)
		{
?>
		<dt><?php  echo $arOneProp['NAME']; ?></dt><dd><?php 
			echo (
				is_array($arOneProp['DISPLAY_VALUE'])
				? implode(' / ', $arOneProp['DISPLAY_VALUE'])
				: $arOneProp['DISPLAY_VALUE']
			); ?></dd><?php 
		}
		unset($arOneProp);
?>
	</dl>
<?php 
	}
	if ($arResult['SHOW_OFFERS_PROPS'])
	{
?>
	<dl id="<?php  echo $arItemIDs['DISPLAY_PROP_DIV'] ?>" style="display: none;"></dl>
<?php 
	}
?>
</div>
<?php 
}
if ('' != $arResult['PREVIEW_TEXT'])
{
	if (
		'S' == $arParams['DISPLAY_PREVIEW_TEXT_MODE']
		|| ('E' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'] && '' == $arResult['DETAIL_TEXT'])
	)
	{
?>
<div class="item_info_section">
<?php 
		echo ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>');
?>
</div>
<?php 
	}
}
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP']))
{
	$arSkuProps = array();
?>
<div class="item_info_section" style="padding-right:150px;" id="<?php  echo $arItemIDs['PROP_DIV']; ?>">
<?php 
	foreach ($arResult['SKU_PROPS'] as &$arProp)
	{
		if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']]))
			continue;
		$arSkuProps[] = array(
			'ID' => $arProp['ID'],
			'SHOW_MODE' => $arProp['SHOW_MODE'],
			'VALUES_COUNT' => $arProp['VALUES_COUNT']
		);
		if ('TEXT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_size full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_size';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<?php  echo $strClass; ?>" id="<?php  echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
		<span class="bx_item_section_name_gray"><?php  echo htmlspecialcharsex($arProp['NAME']); ?></span>
		<div class="bx_size_scroller_container"><div class="bx_size">
			<ul id="<?php  echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <?php  echo $strWidth; ?>;margin-left:0%;">
<?php 
			foreach ($arProp['VALUES'] as $arOneValue)
			{
				$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
?>
<li data-treevalue="<?php  echo $arProp['ID'].'_'.$arOneValue['ID']; ?>" data-onevalue="<?php  echo $arOneValue['ID']; ?>" style="width: <?php  echo $strOneWidth; ?>; display: none;">
<i title="<?php  echo $arOneValue['NAME']; ?>"></i><span class="cnt" title="<?php  echo $arOneValue['NAME']; ?>"><?php  echo $arOneValue['NAME']; ?></span></li>
<?php 
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<?php  echo $strSlideStyle; ?>" id="<?php  echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<?php  echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<?php  echo $strSlideStyle; ?>" id="<?php  echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<?php  echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?php 
		}
		elseif ('PICT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_scu full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_scu';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<?php  echo $strClass; ?>" id="<?php  echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
		<span class="bx_item_section_name_gray"><?php  echo htmlspecialcharsex($arProp['NAME']); ?></span>
		<div class="bx_scu_scroller_container"><div class="bx_scu">
			<ul id="<?php  echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <?php  echo $strWidth; ?>;margin-left:0%;">
<?php 
			foreach ($arProp['VALUES'] as $arOneValue)
			{
				$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
?>
<li data-treevalue="<?php  echo $arProp['ID'].'_'.$arOneValue['ID'] ?>" data-onevalue="<?php  echo $arOneValue['ID']; ?>" style="width: <?php  echo $strOneWidth; ?>; padding-top: <?php  echo $strOneWidth; ?>; display: none;" >
<i title="<?php  echo $arOneValue['NAME']; ?>"></i>
<span class="cnt"><span class="cnt_item" style="background-image:url('<?php  echo $arOneValue['PICT']['SRC']; ?>');" title="<?php  echo $arOneValue['NAME']; ?>"></span></span></li>
<?php 
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<?php  echo $strSlideStyle; ?>" id="<?php  echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<?php  echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<?php  echo $strSlideStyle; ?>" id="<?php  echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<?php  echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?php 
		}
	}
	unset($arProp);
?>
</div>
<?php 
}
?>
<div class="item_info_section">
<?php 
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	$canBuy = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CAN_BUY'];
}
else
{
	$canBuy = $arResult['CAN_BUY'];
}
$buyBtnMessage = ($arParams['MESS_BTN_BUY'] != '' ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));
$addToBasketBtnMessage = ($arParams['MESS_BTN_ADD_TO_BASKET'] != '' ? $arParams['MESS_BTN_ADD_TO_BASKET'] : GetMessage('CT_BCE_CATALOG_ADD'));
$notAvailableMessage = ($arParams['MESS_NOT_AVAILABLE'] != '' ? $arParams['MESS_NOT_AVAILABLE'] : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE'));
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);

$showSubscribeBtn = false;
$compareBtnMessage = ($arParams['MESS_BTN_COMPARE'] != '' ? $arParams['MESS_BTN_COMPARE'] : GetMessage('CT_BCE_CATALOG_COMPARE'));

if ($arParams['USE_PRODUCT_QUANTITY'] == 'Y')
{
	if ($arParams['SHOW_BASIS_PRICE'] == 'Y')
	{
		$basisPriceInfo = array(
			'#PRICE#' => $arResult['MIN_BASIS_PRICE']['PRINT_DISCOUNT_VALUE'],
			'#MEASURE#' => (isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : '')
		);
?>
		<p id="<?php  echo $arItemIDs['BASIS_PRICE']; ?>" class="item_section_name_gray"><?php  echo GetMessage('CT_BCE_CATALOG_MESS_BASIS_PRICE', $basisPriceInfo); ?></p>
<?php 
	}
?>
	<span class="item_section_name_gray"><?php  echo GetMessage('CATALOG_QUANTITY'); ?></span>
	<div class="item_buttons vam">
		<span class="item_buttons_counter_block">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb" id="<?php  echo $arItemIDs['QUANTITY_DOWN']; ?>">-</a>
			<input id="<?php  echo $arItemIDs['QUANTITY']; ?>" type="text" class="tac transparent_input" value="<?php  echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
					? 1
					: $arResult['CATALOG_MEASURE_RATIO']
				); ?>">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb" id="<?php  echo $arItemIDs['QUANTITY_UP']; ?>">+</a>
			<span class="bx_cnt_desc" id="<?php  echo $arItemIDs['QUANTITY_MEASURE']; ?>"><?php  echo (isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : ''); ?></span>
		</span>
		<span class="item_buttons_counter_block" id="<?php  echo $arItemIDs['BASKET_ACTIONS']; ?>" style="display: <?php  echo ($canBuy ? '' : 'none'); ?>;">
<?php 
	if ($showBuyBtn)
	{
?>
			<a href="javascript:void(0);" class="bx_big bx_bt_button bx_cart" id="<?php  echo $arItemIDs['BUY_LINK']; ?>"><span></span><?php  echo $buyBtnMessage; ?></a>
<?php 
	}
	if ($showAddBtn)
	{
?>
			<a href="javascript:void(0);" class="bx_big bx_bt_button bx_cart" id="<?php  echo $arItemIDs['ADD_BASKET_LINK']; ?>"><span></span><?php  echo $addToBasketBtnMessage; ?></a>
<?php 
	}
?>
		</span>
		<span id="<?php  echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_notavailable" style="display: <?php  echo (!$canBuy ? '' : 'none'); ?>;"><?php  echo $notAvailableMessage; ?></span>
<?php 
	if ($arParams['DISPLAY_COMPARE'] || $showSubscribeBtn)
	{
?>
		<span class="item_buttons_counter_block">
<?php 
		if ($arParams['DISPLAY_COMPARE'])
		{
?>
			<a href="javascript:void(0);" class="bx_big bx_bt_button_type_2 bx_cart" id="<?php  echo $arItemIDs['COMPARE_LINK']; ?>"><?php  echo $compareBtnMessage; ?></a>
<?php 
		}
		if ($showSubscribeBtn)
		{

		}
?>
		</span>
<?php 
	}
?>
	</div>
<?php 
	if ('Y' == $arParams['SHOW_MAX_QUANTITY'])
	{
		if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
		{
?>
	<p id="<?php  echo $arItemIDs['QUANTITY_LIMIT']; ?>" style="display: none;"><?php  echo GetMessage('OSTATOK'); ?>: <span></span></p>
<?php 
		}
		else
		{
			if ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO'])
			{
?>
	<p id="<?php  echo $arItemIDs['QUANTITY_LIMIT']; ?>"><?php  echo GetMessage('OSTATOK'); ?>: <span><?php  echo $arResult['CATALOG_QUANTITY']; ?></span></p>
<?php 
			}
		}
	}
}
else
{
?>
	<div class="item_buttons vam">
		<span class="item_buttons_counter_block" id="<?php  echo $arItemIDs['BASKET_ACTIONS']; ?>" style="display: <?php  echo ($canBuy ? '' : 'none'); ?>;">
<?php 
	if ($showBuyBtn)
	{
?>
			<a href="javascript:void(0);" class="bx_big bx_bt_button bx_cart" id="<?php  echo $arItemIDs['BUY_LINK']; ?>"><span></span><?php  echo $buyBtnMessage; ?></a>
<?php 
	}
	if ($showAddBtn)
	{
?>
		<a href="javascript:void(0);" class="bx_big bx_bt_button bx_cart" id="<?php  echo $arItemIDs['ADD_BASKET_LINK']; ?>"><span></span><?php  echo $addToBasketBtnMessage; ?></a>
<?php 
	}
?>
		</span>
		<span id="<?php  echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_notavailable" style="display: <?php  echo (!$canBuy ? '' : 'none'); ?>;"><?php  echo $notAvailableMessage; ?></span>
<?php 
	if ($arParams['DISPLAY_COMPARE'] || $showSubscribeBtn)
	{
		?>
		<span class="item_buttons_counter_block">
	<?php 
	if ($arParams['DISPLAY_COMPARE'])
	{
		?>
		<a href="javascript:void(0);" class="bx_big bx_bt_button_type_2 bx_cart" id="<?php  echo $arItemIDs['COMPARE_LINK']; ?>"><?php  echo $compareBtnMessage; ?></a>
	<?php 
	}
	if ($showSubscribeBtn)
	{

	}
?>
		</span>
<?php 
	}
?>
	</div>
<?php 
}
unset($showAddBtn, $showBuyBtn);
?>
</div>
			<div class="clb"></div>
		</div>

		<div class="bx_md">
<div class="item_info_section">
<?php 
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	if ($arResult['OFFER_GROUP'])
	{
		foreach ($arResult['OFFER_GROUP_VALUES'] as $offerID)
		{
?>
	<span id="<?php  echo $arItemIDs['OFFER_GROUP'].$offerID; ?>" style="display: none;">
<?php $APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
	".default",
	array(
		"IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
		"ELEMENT_ID" => $offerID,
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
		"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"]
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?><?php 
?>
	</span>
<?php 
		}
	}
}
else
{
	if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP'])
	{
?><?php $APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
	".default",
	array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_ID" => $arResult["ID"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
		"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"]
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?><?php 
	}
}
?>
</div>
		</div>
		<div class="bx_rb">
<div class="item_info_section">
<?php 
if ('' != $arResult['DETAIL_TEXT'])
{
?>
	<div class="bx_item_description">
		<div class="bx_item_section_name_gray" style="border-bottom: 1px solid #f2f2f2;"><?php  echo GetMessage('FULL_DESCRIPTION'); ?></div>
<?php 
	if ('html' == $arResult['DETAIL_TEXT_TYPE'])
	{
		echo $arResult['DETAIL_TEXT'];
	}
	else
	{
		?><p><?php  echo $arResult['DETAIL_TEXT']; ?></p><?php 
	}
?>
	</div>
<?php 
}
?>
</div>
		</div>
		<div class="bx_lb">
<div class="tac ovh">
</div>
<div class="tab-section-container">
<?php 
if ('Y' == $arParams['USE_COMMENTS'])
{
?>
<?php $APPLICATION->IncludeComponent(
	"bitrix:catalog.comments",
	"",
	array(
		"ELEMENT_ID" => $arResult['ID'],
		"ELEMENT_CODE" => "",
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"SHOW_DEACTIVATED" => $arParams['SHOW_DEACTIVATED'],
		"URL_TO_COMMENT" => "",
		"WIDTH" => "",
		"COMMENTS_COUNT" => "5",
		"BLOG_USE" => $arParams['BLOG_USE'],
		"FB_USE" => $arParams['FB_USE'],
		"FB_APP_ID" => $arParams['FB_APP_ID'],
		"VK_USE" => $arParams['VK_USE'],
		"VK_API_ID" => $arParams['VK_API_ID'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
		"BLOG_TITLE" => "",
		"BLOG_URL" => $arParams['BLOG_URL'],
		"PATH_TO_SMILE" => "",
		"EMAIL_NOTIFY" => $arParams['BLOG_EMAIL_NOTIFY'],
		"AJAX_POST" => "Y",
		"SHOW_SPAM" => "Y",
		"SHOW_RATING" => "N",
		"FB_TITLE" => "",
		"FB_USER_ADMIN_ID" => "",
		"FB_COLORSCHEME" => "light",
		"FB_ORDER_BY" => "reverse_time",
		"VK_TITLE" => "",
		"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME']
	),
	$component,
	array("HIDE_ICONS" => "Y")
);?>
<?php 
}
?>
</div>
		</div>
			<div style="clear: both;"></div>
	</div>
	<div class="clb"></div>
</div><?php 
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	foreach ($arResult['JS_OFFERS'] as &$arOneJS)
	{
		if ($arOneJS['PRICE']['DISCOUNT_VALUE'] != $arOneJS['PRICE']['VALUE'])
		{
			$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'];
			$arOneJS['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'];
		}
		$strProps = '';
		if ($arResult['SHOW_OFFERS_PROPS'])
		{
			if (!empty($arOneJS['DISPLAY_PROPERTIES']))
			{
				foreach ($arOneJS['DISPLAY_PROPERTIES'] as $arOneProp)
				{
					$strProps .= '<dt>'.$arOneProp['NAME'].'</dt><dd>'.(
						is_array($arOneProp['VALUE'])
						? implode(' / ', $arOneProp['VALUE'])
						: $arOneProp['VALUE']
					).'</dd>';
				}
			}
		}
		$arOneJS['DISPLAY_PROPERTIES'] = $strProps;
	}
	if (isset($arOneJS))
		unset($arOneJS);
	$arJSParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => true,
			'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
			'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP' => $arResult['OFFER_GROUP'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
			'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y')
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'DEFAULT_PICTURE' => array(
			'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
			'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
		),
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'NAME' => $arResult['~NAME']
		),
		'BASKET' => array(
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		),
		'OFFERS' => $arResult['JS_OFFERS'],
		'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
		'TREE_PROPS' => $arSkuProps
	);
	if ($arParams['DISPLAY_COMPARE'])
	{
		$arJSParams['COMPARE'] = array(
			'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
			'COMPARE_PATH' => $arParams['COMPARE_PATH']
		);
	}
}
else
{
	$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
	if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
	{
?>
<div id="<?php  echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
<?php 
		if (!empty($arResult['PRODUCT_PROPERTIES_FILL']))
		{
			foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
			{
?>
	<input type="hidden" name="<?php  echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?php  echo $propID; ?>]" value="<?php  echo htmlspecialcharsbx($propInfo['ID']); ?>">
<?php 
				if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
					unset($arResult['PRODUCT_PROPERTIES'][$propID]);
			}
		}
		$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
		if (!$emptyProductProperties)
		{
?>
	<table>
<?php 
			foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo)
			{
?>
	<tr><td><?php  echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
	<td>
<?php 
				if(
					'L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE']
					&& 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']
				)
				{
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?><label><input type="radio" name="<?php  echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?php  echo $propID; ?>]" value="<?php  echo $valueID; ?>" <?php  echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><?php  echo $value; ?></label><br><?php 
					}
				}
				else
				{
					?><select name="<?php  echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?php  echo $propID; ?>]"><?php 
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?><option value="<?php  echo $valueID; ?>" <?php  echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><?php  echo $value; ?></option><?php 
					}
					?></select><?php 
				}
?>
	</td></tr>
<?php 
			}
?>
	</table>
<?php 
		}
?>
</div>
<?php 
	}
	if ($arResult['MIN_PRICE']['DISCOUNT_VALUE'] != $arResult['MIN_PRICE']['VALUE'])
	{
		$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'];
		$arResult['MIN_BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arResult['MIN_BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'];
	}
	$arJSParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => (isset($arResult['MIN_PRICE']) && !empty($arResult['MIN_PRICE']) && is_array($arResult['MIN_PRICE'])),
			'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
			'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
			'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y')
		),
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'PICT' => $arFirstPhoto,
			'NAME' => $arResult['~NAME'],
			'SUBSCRIPTION' => true,
			'PRICE' => $arResult['MIN_PRICE'],
			'BASIS_PRICE' => $arResult['MIN_BASIS_PRICE'],
			'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
			'SLIDER' => $arResult['MORE_PHOTO'],
			'CAN_BUY' => $arResult['CAN_BUY'],
			'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
			'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
			'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
		),
		'BASKET' => array(
			'ADD_PROPS' => ($arParams['ADD_PROPERTIES_TO_BASKET'] == 'Y'),
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'EMPTY_PROPS' => $emptyProductProperties,
			'BASKET_URL' => $arParams['BASKET_URL'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		)
	);
	if ($arParams['DISPLAY_COMPARE'])
	{
		$arJSParams['COMPARE'] = array(
			'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
			'COMPARE_PATH' => $arParams['COMPARE_PATH']
		);
	}
	unset($emptyProductProperties);
}
?>
<script type="text/javascript">
var <?php  echo $strObName; ?> = new JCCatalogElement(<?php  echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
BX.message({
	ECONOMY_INFO_MESSAGE: '<?php  echo GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO'); ?>',
	BASIS_PRICE_MESSAGE: '<?php  echo GetMessageJS('CT_BCE_CATALOG_MESS_BASIS_PRICE') ?>',
	TITLE_ERROR: '<?php  echo GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR') ?>',
	TITLE_BASKET_PROPS: '<?php  echo GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS') ?>',
	BASKET_UNKNOWN_ERROR: '<?php  echo GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
	BTN_SEND_PROPS: '<?php  echo GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS'); ?>',
	BTN_MESSAGE_BASKET_REDIRECT: '<?php  echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT') ?>',
	BTN_MESSAGE_CLOSE: '<?php  echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE'); ?>',
	BTN_MESSAGE_CLOSE_POPUP: '<?php  echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP'); ?>',
	TITLE_SUCCESSFUL: '<?php  echo GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK'); ?>',
	COMPARE_MESSAGE_OK: '<?php  echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK') ?>',
	COMPARE_UNKNOWN_ERROR: '<?php  echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR') ?>',
	COMPARE_TITLE: '<?php  echo GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE') ?>',
	BTN_MESSAGE_COMPARE_REDIRECT: '<?php  echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT') ?>',
	SITE_ID: '<?php  echo SITE_ID; ?>'
});
</script>