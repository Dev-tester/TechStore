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
/** @var array $skuTemplate */
/** @var array $templateData */
$this->setFrameMode(true);

if (!empty($arResult['ITEMS']))
{
	$arResult['SKU_PROPS'] = reset($arResult['SKU_PROPS']);
	$skuTemplate = array();
	if (!empty($arResult['SKU_PROPS']))
	{
		foreach ($arResult['SKU_PROPS'] as $arProp)
		{
			$propId = $arProp['ID'];
			$skuTemplate[$propId] = array(
				'SCROLL' => array(
					'START' => '',
					'FINISH' => '',
				),
				'FULL' => array(
					'START' => '',
					'FINISH' => '',
				),
				'ITEMS' => array()
			);
			$templateRow = '';
			if ('TEXT' == $arProp['SHOW_MODE'])
			{
				$skuTemplate[$propId]['SCROLL']['START'] = '<div class="bx_item_detail_size full" id="#ITEM#_prop_'.$propId.'_cont">'.
					'<span class="bx_item_section_name_gray">'.htmlspecialcharsbx($arProp['NAME']).'</span>'.
					'<div class="bx_size_scroller_container"><div class="bx_size"><ul id="#ITEM#_prop_'.$propId.'_list" style="width: #WIDTH#;">';;
				$skuTemplate[$propId]['SCROLL']['FINISH'] = '</ul></div>'.
					'<div class="bx_slide_left" id="#ITEM#_prop_'.$propId.'_left" data-treevalue="'.$propId.'" style=""></div>'.
					'<div class="bx_slide_right" id="#ITEM#_prop_'.$propId.'_right" data-treevalue="'.$propId.'" style=""></div>'.
					'</div></div>';

				$skuTemplate[$propId]['FULL']['START'] = '<div class="bx_item_detail_size" id="#ITEM#_prop_'.$propId.'_cont">'.
					'<span class="bx_item_section_name_gray">'.htmlspecialcharsbx($arProp['NAME']).'</span>'.
					'<div class="bx_size_scroller_container"><div class="bx_size"><ul id="#ITEM#_prop_'.$propId.'_list" style="width: #WIDTH#;">';;
				$skuTemplate[$propId]['FULL']['FINISH'] = '</ul></div>'.
					'<div class="bx_slide_left" id="#ITEM#_prop_'.$propId.'_left" data-treevalue="'.$propId.'" style="display: none;"></div>'.
					'<div class="bx_slide_right" id="#ITEM#_prop_'.$propId.'_right" data-treevalue="'.$propId.'" style="display: none;"></div>'.
					'</div></div>';
				foreach ($arProp['VALUES'] as $value)
				{
					$value['NAME'] = htmlspecialcharsbx($value['NAME']);
					$skuTemplate[$propId]['ITEMS'][$value['ID']] = '<li data-treevalue="'.$propId.'_'.$value['ID'].
						'" data-onevalue="'.$value['ID'].'" style="width: #WIDTH#;" title="'.$value['NAME'].'"><i></i><span class="cnt">'.$value['NAME'].'</span></li>';
				}
				unset($value);
			}
			elseif ('PICT' == $arProp['SHOW_MODE'])
			{
				$skuTemplate[$propId]['SCROLL']['START'] = '<div class="bx_item_detail_scu full" id="#ITEM#_prop_'.$propId.'_cont">'.
					'<span class="bx_item_section_name_gray">'.htmlspecialcharsbx($arProp['NAME']).'</span>'.
					'<div class="bx_scu_scroller_container"><div class="bx_scu"><ul id="#ITEM#_prop_'.$propId.'_list" style="width: #WIDTH#;">';
				$skuTemplate[$propId]['SCROLL']['FINISH'] = '</ul></div>'.
					'<div class="bx_slide_left" id="#ITEM#_prop_'.$propId.'_left" data-treevalue="'.$propId.'" style=""></div>'.
					'<div class="bx_slide_right" id="#ITEM#_prop_'.$propId.'_right" data-treevalue="'.$propId.'" style=""></div>'.
					'</div></div>';

				$skuTemplate[$propId]['FULL']['START'] = '<div class="bx_item_detail_scu" id="#ITEM#_prop_'.$propId.'_cont">'.
					'<span class="bx_item_section_name_gray">'.htmlspecialcharsbx($arProp['NAME']).'</span>'.
					'<div class="bx_scu_scroller_container"><div class="bx_scu"><ul id="#ITEM#_prop_'.$propId.'_list" style="width: #WIDTH#;">';
				$skuTemplate[$propId]['FULL']['FINISH'] = '</ul></div>'.
					'<div class="bx_slide_left" id="#ITEM#_prop_'.$propId.'_left" data-treevalue="'.$propId.'" style="display: none;"></div>'.
					'<div class="bx_slide_right" id="#ITEM#_prop_'.$propId.'_right" data-treevalue="'.$propId.'" style="display: none;"></div>'.
					'</div></div>';
				foreach ($arProp['VALUES'] as $value)
				{
					$value['NAME'] = htmlspecialcharsbx($value['NAME']);
					$skuTemplate[$propId]['ITEMS'][$value['ID']] = '<li data-treevalue="'.$propId.'_'.$value['ID'].
						'" data-onevalue="'.$value['ID'].'" style="width: #WIDTH#; padding-top: #WIDTH#;"><i title="'.$value['NAME'].'"></i>'.
						'<span class="cnt"><span class="cnt_item" style="background-image:url(\''.$value['PICT']['SRC'].'\');" title="'.$value['NAME'].'"></span></span></li>';
				}
				unset($value);
			}
		}
		unset($templateRow, $arProp);
	}
}

$intRowsCount = count($arResult['ITEMS']);
$strRand = $this->randString();
$strContID = 'cat_top_cont_'.$strRand;
?><div id="<?php  echo $strContID; ?>" class="bx_catalog_tile_home_type_2 col<?php  echo $arParams['LINE_ELEMENT_COUNT']; ?> <?php  echo $templateData['TEMPLATE_CLASS']; ?>">
	<div class="bx_catalog_tile_section">
		<?php 
		$boolFirst = true;
		$arRowIDs = array();
		foreach ($arResult['ITEMS'] as $keyRow => $arOneRow)
		{
			$strRowID = 'cat-top-'.$keyRow.'_'.$strRand;
			$arRowIDs[] = $strRowID;
			?>
			<div id="<?php  echo $strRowID; ?>" class="bx_catalog_tile_slide <?php  echo ($boolFirst ? 'active' : 'notactive'); ?>">
				<?php 
				foreach ($arOneRow as $keyItem => $arItem)
				{
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
					$strMainID = $this->GetEditAreaId($arItem['ID']);

					$arItemIDs = array(
						'ID' => $strMainID,
						'PICT' => $strMainID.'_pict',
						'SECOND_PICT' => $strMainID.'_secondpict',
						'MAIN_PROPS' => $strMainID.'_main_props',

						'QUANTITY' => $strMainID.'_quantity',
						'QUANTITY_DOWN' => $strMainID.'_quant_down',
						'QUANTITY_UP' => $strMainID.'_quant_up',
						'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
						'BUY_LINK' => $strMainID.'_buy_link',
						'BASKET_ACTIONS' => $strMainID.'_basket_actions',
						'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
						'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
						'COMPARE_LINK' => $strMainID.'_compare_link',

						'PRICE' => $strMainID.'_price',
						'DSC_PERC' => $strMainID.'_dsc_perc',
						'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',

						'PROP_DIV' => $strMainID.'_sku_tree',
						'PROP' => $strMainID.'_prop_',
						'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
						'BASKET_PROP_DIV' => $strMainID.'_basket_prop'
					);

					$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
					$productTitle = (
					isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
						? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
						: $arItem['NAME']
					);
					$imgTitle = (
					isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
						? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
						: $arItem['NAME']
					);

					$minPrice = false;
					if (isset($arItem['MIN_PRICE']) || isset($arItem['RATIO_PRICE']))
						$minPrice = (isset($arItem['RATIO_PRICE']) ? $arItem['RATIO_PRICE'] : $arItem['MIN_PRICE']);
					?>
					<div class="<?php  echo ($arItem['SECOND_PICT'] ? 'bx_catalog_item double' : 'bx_catalog_item'); ?>"><div class="bx_catalog_item_container" id="<?php  echo $strMainID; ?>">
							<a id="<?php  echo $arItemIDs['PICT']; ?>" href="<?php  echo $arItem['DETAIL_PAGE_URL']; ?>" class="bx_catalog_item_images" style="background-image: url('<?php  echo $arItem['PREVIEW_PICTURE']['SRC']; ?>')" title="<?php  echo $imgTitle; ?>">
								<?php 
								if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
								{
									?>
									<div id="<?php  echo $arItemIDs['DSC_PERC']; ?>" class="bx_stick_disc right bottom" style="display:<?php  echo (0 < $minPrice['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">-<?php  echo $minPrice['DISCOUNT_DIFF_PERCENT']; ?>%</div>
									<?php 
								}
								if ($arItem['LABEL'])
								{
									?>
									<div class="bx_stick average left top" title="<?php  echo $arItem['LABEL_VALUE']; ?>"><?php  echo $arItem['LABEL_VALUE']; ?></div>
									<?php 
								}
								?>
							</a>
							<?php 
							if ($arItem['SECOND_PICT'])
							{
								?>
								<a id="<?php  echo $arItemIDs['SECOND_PICT']; ?>" href="<?php  echo $arItem['DETAIL_PAGE_URL']; ?>" class="bx_catalog_item_images_double" style="background-image: url('<?php  echo (
								!empty($arItem['PREVIEW_PICTURE_SECOND'])
									? $arItem['PREVIEW_PICTURE_SECOND']['SRC']
									: $arItem['PREVIEW_PICTURE']['SRC']
								); ?>')" title="<?php  echo $imgTitle; ?>">
									<?php 
									if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
									{
										?>
										<div id="<?php  echo $arItemIDs['SECOND_DSC_PERC']; ?>" class="bx_stick_disc right bottom" style="display:<?php  echo (0 < $minPrice['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">-<?php  echo $minPrice['DISCOUNT_DIFF_PERCENT']; ?>%</div>
										<?php 
									}
									if ($arItem['LABEL'])
									{
										?>
										<div class="bx_stick average left top" title="<?php  echo $arItem['LABEL_VALUE']; ?>"><?php  echo $arItem['LABEL_VALUE']; ?></div>
										<?php 
									}
									?>
								</a>
								<?php 
							}
							?>
							<div class="bx_catalog_item_title"><a href="<?php  echo $arItem['DETAIL_PAGE_URL']; ?>" title="<?php  echo $productTitle; ?>"><?php  echo $productTitle; ?></a></div>
							<div class="bx_catalog_item_price"><div id="<?php  echo $arItemIDs['PRICE']; ?>" class="bx_price">
									<?php 
									if (!empty($minPrice))
									{
										if ('N' == $arParams['PRODUCT_DISPLAY_MODE'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
										{
											echo GetMessage(
												'CT_BCT_TPL_MESS_PRICE_SIMPLE_MODE',
												array(
													'#PRICE#' => $minPrice['PRINT_DISCOUNT_VALUE'],
													'#MEASURE#' => GetMessage(
														'CT_BCT_TPL_MESS_MEASURE_SIMPLE_MODE',
														array(
															'#VALUE#' => $minPrice['CATALOG_MEASURE_RATIO'],
															'#UNIT#' => $minPrice['CATALOG_MEASURE_NAME']
														)
													)
												)
											);
										}
										else
										{
											echo $minPrice['PRINT_DISCOUNT_VALUE'];
										}
										if ('Y' == $arParams['SHOW_OLD_PRICE'] && $minPrice['DISCOUNT_VALUE'] < $minPrice['VALUE'])
										{
											?> <span><?php  echo $minPrice['PRINT_VALUE']; ?></span><?php 
										}
									}
									unset($minPrice);
									?>
								</div></div>
							<?php 
							$showSubscribeBtn = false;
							$compareBtnMessage = ($arParams['MESS_BTN_COMPARE'] != '' ? $arParams['MESS_BTN_COMPARE'] : GetMessage('CT_BCT_TPL_MESS_BTN_COMPARE'));
							if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS']))
							{
								?>
								<div class="bx_catalog_item_controls">
									<?php 
									if ($arItem['CAN_BUY'])
									{
										if ('Y' == $arParams['USE_PRODUCT_QUANTITY'])
										{
											?>
											<div class="bx_catalog_item_controls_blockone"><div style="display: inline-block;position: relative;">
													<a id="<?php  echo $arItemIDs['QUANTITY_DOWN']; ?>" href="javascript:void(0)" class="bx_bt_button_type_2 bx_small" rel="nofollow">-</a>
													<input type="text" class="bx_col_input" id="<?php  echo $arItemIDs['QUANTITY']; ?>" name="<?php  echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?php  echo $arItem['CATALOG_MEASURE_RATIO']; ?>">
													<a id="<?php  echo $arItemIDs['QUANTITY_UP']; ?>" href="javascript:void(0)" class="bx_bt_button_type_2 bx_small" rel="nofollow">+</a>
													<span id="<?php  echo $arItemIDs['QUANTITY_MEASURE']; ?>"><?php  echo $arItem['CATALOG_MEASURE_NAME']; ?></span>
												</div></div>
											<?php 
										}
										?>
										<div id="<?php  echo $arItemIDs['BASKET_ACTIONS']; ?>" class="bx_catalog_item_controls_blocktwo">
											<a id="<?php  echo $arItemIDs['BUY_LINK']; ?>" class="bx_bt_button bx_medium" href="javascript:void(0)" rel="nofollow">
												<?php 
												if ($arParams['ADD_TO_BASKET_ACTION'] == 'BUY')
												{
													echo ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCT_TPL_MESS_BTN_BUY'));
												}
												else
												{
													echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? $arParams['MESS_BTN_ADD_TO_BASKET'] : GetMessage('CT_BCT_TPL_MESS_BTN_ADD_TO_BASKET'));
												}
												?>
											</a>
										</div>
										<?php 
										if ($arParams['DISPLAY_COMPARE'])
										{
											?>
											<div class="bx_catalog_item_controls_blocktwo">
												<a id="<?php  echo $arItemIDs['COMPARE_LINK']; ?>" class="bx_bt_button_type_2 bx_medium" href="javascript:void(0)"><?php  echo $compareBtnMessage; ?></a>
											</div>
											<?php 
										}
									}
									else
									{
										?>
										<div id="<?php  echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_catalog_item_controls_blockone"><span class="bx_notavailable">
<?php 
echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessage('CT_BCT_TPL_MESS_PRODUCT_NOT_AVAILABLE'));
?>
			</span></div>
										<?php 
										if ($arParams['DISPLAY_COMPARE'] || $showSubscribeBtn)
										{
											?>
											<div class="bx_catalog_item_controls_blocktwo"><?php 
											if ($arParams['DISPLAY_COMPARE'])
											{
												?><a id="<?php  echo $arItemIDs['COMPARE_LINK']; ?>" class="bx_bt_button_type_2 bx_medium" href="javascript:void(0)"><?php  echo $compareBtnMessage; ?></a><?php 
											}
											if ($showSubscribeBtn)
											{
												?>
												<a id="<?php  echo $arItemIDs['SUBSCRIBE_LINK']; ?>" class="bx_bt_button_type_2 bx_medium" href="javascript:void(0)"><?php 
												echo ('' != $arParams['MESS_BTN_SUBSCRIBE'] ? $arParams['MESS_BTN_SUBSCRIBE'] : GetMessage('CT_BCT_TPL_MESS_BTN_SUBSCRIBE'));
												?></a><?php 
											}
											?>
											</div><?php 
										}
									}
									?>
									<div style="clear: both;"></div>
								</div>
							<?php 
							if (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']))
							{
							?>
								<div class="bx_catalog_item_articul">
									<?php 
									foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp)
									{
										?><br><strong><?php  echo $arOneProp['NAME']; ?></strong> <?php 
										echo (
										is_array($arOneProp['DISPLAY_VALUE'])
											? implode('<br>', $arOneProp['DISPLAY_VALUE'])
											: $arOneProp['DISPLAY_VALUE']
										);
									}
									?>
								</div>
							<?php 
							}
							$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
							if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
							{
							?>
								<div id="<?php  echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
									<?php 
									if (!empty($arItem['PRODUCT_PROPERTIES_FILL']))
									{
										foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
										{
											?>
											<input type="hidden" name="<?php  echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<?php  echo $propID; ?>]" value="<?php  echo htmlspecialcharsbx($propInfo['ID']); ?>"><?php 
											if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
												unset($arItem['PRODUCT_PROPERTIES'][$propID]);
										}
									}
									$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
									if (!$emptyProductProperties)
									{
										?>
										<table>
											<?php 
											foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo)
											{
												?>
												<tr><td><?php  echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
													<td>
														<?php 
														if(
															'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']
															&& 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']
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
							$arJSParams = array(
								'PRODUCT_TYPE' => $arItem['PRODUCT']['TYPE'],
								'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
								'SHOW_ADD_BASKET_BTN' => false,
								'SHOW_BUY_BTN' => true,
								'SHOW_ABSENT' => true,
								'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
								'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
								'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
								'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
								'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
								'PRODUCT' => array(
									'ID' => $arItem['ID'],
									'NAME' => $productTitle,
									'PICT' => ('Y' == $arItem['SECOND_PICT'] ? $arItem['PREVIEW_PICTURE_SECOND'] : $arItem['PREVIEW_PICTURE']),
									'CAN_BUY' => $arItem["CAN_BUY"],
									'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
									'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
									'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
									'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
									'BASIS_PRICE' => $arItem['MIN_BASIS_PRICE']
								),
								'VISUAL' => array(
									'ID' => $arItemIDs['ID'],
									'PICT_ID' => ('Y' == $arItem['SECOND_PICT'] ? $arItemIDs['SECOND_PICT'] : $arItemIDs['PICT']),
									'QUANTITY_ID' => $arItemIDs['QUANTITY'],
									'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
									'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
									'PRICE_ID' => $arItemIDs['PRICE'],
									'BUY_ID' => $arItemIDs['BUY_LINK'],
									'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
									'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
									'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
									'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK']
								),
								'BASKET' => array(
									'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
									'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
									'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
									'EMPTY_PROPS' => $emptyProductProperties,
									'BASKET_URL' => $arParams['~BASKET_URL'],
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
							?>
								<script type="text/javascript">
							var <?php  echo $strObName; ?> = new JCCatalogTopSlider(<?php  echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
								</script>
							<?php 
							}
							else
							{
							if ('Y' == $arParams['PRODUCT_DISPLAY_MODE'])
							{
							$canBuy = $arItem['JS_OFFERS'][$arItem['OFFERS_SELECTED']]['CAN_BUY'];
							?>
								<div class="bx_catalog_item_controls no_touch">
									<?php 
									if ('Y' == $arParams['USE_PRODUCT_QUANTITY'])
									{
										?>
										<div class="bx_catalog_item_controls_blockone">
											<a id="<?php  echo $arItemIDs['QUANTITY_DOWN']; ?>" href="javascript:void(0)" class="bx_bt_button_type_2 bx_small" rel="nofollow">-</a>
											<input type="text" class="bx_col_input" id="<?php  echo $arItemIDs['QUANTITY']; ?>" name="<?php  echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?php  echo $arItem['CATALOG_MEASURE_RATIO']; ?>">
											<a id="<?php  echo $arItemIDs['QUANTITY_UP']; ?>" href="javascript:void(0)" class="bx_bt_button_type_2 bx_small" rel="nofollow">+</a>
											<span id="<?php  echo $arItemIDs['QUANTITY_MEASURE']; ?>"></span>
										</div>
										<?php 
									}
									?>
									<div id="<?php  echo $arItemIDs['NOT_AVAILABLE_MESS']; ?>" class="bx_catalog_item_controls_blockone" style="display: <?php  echo ($canBuy ? 'none' : ''); ?>;"><span class="bx_notavailable">
<?php 
echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessage('CT_BCT_TPL_MESS_PRODUCT_NOT_AVAILABLE'));
?>
			</span></div>
									<div id="<?php  echo $arItemIDs['BASKET_ACTIONS']; ?>" class="bx_catalog_item_controls_blocktwo" style="display: <?php  echo ($canBuy ? '' : 'none'); ?>;">
										<a id="<?php  echo $arItemIDs['BUY_LINK']; ?>" class="bx_bt_button bx_medium" href="javascript:void(0)" rel="nofollow"><?php 
											if ($arParams['ADD_TO_BASKET_ACTION'] == 'BUY')
											{
												echo ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCT_TPL_MESS_BTN_BUY'));
											}
											else
											{
												echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? $arParams['MESS_BTN_ADD_TO_BASKET'] : GetMessage('CT_BCT_TPL_MESS_BTN_ADD_TO_BASKET'));
											}
											?></a>
									</div>
									<?php 
									if ($arParams['DISPLAY_COMPARE'])
									{
										?>
										<div class="bx_catalog_item_controls_blocktwo">
										<a id="<?php  echo $arItemIDs['COMPARE_LINK']; ?>" class="bx_bt_button_type_2 bx_medium" href="javascript:void(0)"><?php  echo $compareBtnMessage; ?></a>
										</div><?php 
									}
									?>
									<div style="clear: both;"></div>
								</div>
								<?php 
								unset($canBuy);
							}
							else
							{
								?>
								<div class="bx_catalog_item_controls no_touch">
									<a class="bx_bt_button_type_2 bx_medium" href="<?php  echo $arItem['DETAIL_PAGE_URL']; ?>"><?php 
										echo ('' != $arParams['MESS_BTN_DETAIL'] ? $arParams['MESS_BTN_DETAIL'] : GetMessage('CT_BCT_TPL_MESS_BTN_DETAIL'));
										?></a>
								</div>
							<?php 
							}
							?>
								<div class="bx_catalog_item_controls touch">
									<a class="bx_bt_button_type_2 bx_medium" href="<?php  echo $arItem['DETAIL_PAGE_URL']; ?>"><?php 
										echo ('' != $arParams['MESS_BTN_DETAIL'] ? $arParams['MESS_BTN_DETAIL'] : GetMessage('CT_BCT_TPL_MESS_BTN_DETAIL'));
										?></a>
								</div>
							<?php 
							$boolShowOfferProps = ('Y' == $arParams['PRODUCT_DISPLAY_MODE'] && $arItem['OFFERS_PROPS_DISPLAY']);
							$boolShowProductProps = (isset($arItem['DISPLAY_PROPERTIES']) && !empty($arItem['DISPLAY_PROPERTIES']));
							if ($boolShowProductProps || $boolShowOfferProps)
							{
							?>
								<div class="bx_catalog_item_articul">
									<?php 
									if ($boolShowProductProps)
									{
										foreach ($arItem['DISPLAY_PROPERTIES'] as $arOneProp)
										{
											?><br><strong><?php  echo $arOneProp['NAME']; ?></strong> <?php 
											echo (
											is_array($arOneProp['DISPLAY_VALUE'])
												? implode(' / ', $arOneProp['DISPLAY_VALUE'])
												: $arOneProp['DISPLAY_VALUE']
											);
										}
									}
									if ($boolShowOfferProps)
									{
										?>
										<span id="<?php  echo $arItemIDs['DISPLAY_PROP_DIV']; ?>" style="display: none;"></span>
										<?php 
									}
									?>
								</div>
							<?php 
							}
							if ('Y' == $arParams['PRODUCT_DISPLAY_MODE'])
							{
							if (!empty($arItem['OFFERS_PROP']))
							{
							$arSkuProps = array();
							?>
								<div class="bx_catalog_item_scu" id="<?php  echo $arItemIDs['PROP_DIV']; ?>">
									<?php 
									foreach ($skuTemplate as $propId => $propTemplate)
									{
										if (!isset($arItem['SKU_TREE_VALUES'][$propId]))
											continue;
										$valueCount = count($arItem['SKU_TREE_VALUES'][$propId]);
										if ($valueCount > 5)
										{
											$fullWidth = ($valueCount*20).'%';
											$itemWidth = (100/$valueCount).'%';
											$rowTemplate = $propTemplate['SCROLL'];
										}
										else
										{
											$fullWidth = '100%';
											$itemWidth = '20%';
											$rowTemplate = $propTemplate['FULL'];
										}
										unset($valueCount);
										echo '<div>', str_replace(array('#ITEM#_prop_', '#WIDTH#'), array($arItemIDs['PROP'], $fullWidth), $rowTemplate['START']);
										foreach ($propTemplate['ITEMS'] as $value => $valueItem)
										{
											if (!isset($arItem['SKU_TREE_VALUES'][$propId][$value]))
												continue;
											echo str_replace(array('#ITEM#_prop_', '#WIDTH#'), array($arItemIDs['PROP'], $itemWidth), $valueItem);
										}
										unset($value, $valueItem);
										echo str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $rowTemplate['FINISH']), '</div>';
									}
									unset($propId, $propTemplate);
									foreach ($arResult['SKU_PROPS'] as $arOneProp)
									{
										if (!isset($arItem['OFFERS_PROP'][$arOneProp['CODE']]))
											continue;
										$arSkuProps[] = array(
											'ID' => $arOneProp['ID'],
											'SHOW_MODE' => $arOneProp['SHOW_MODE'],
											'VALUES_COUNT' => $arOneProp['VALUES_COUNT']
										);
									}
									foreach ($arItem['JS_OFFERS'] as &$arOneJs)
									{
										if (0 < $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'])
										{
											$arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] = '-'.$arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'].'%';
											$arOneJs['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = '-'.$arOneJs['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'].'%';
										}
									}
									unset($arOneJs);
									?>
								</div>
							<?php 
							if ($arItem['OFFERS_PROPS_DISPLAY'])
							{
								foreach ($arItem['JS_OFFERS'] as $keyOffer => $arJSOffer)
								{
									$strProps = '';
									if (!empty($arJSOffer['DISPLAY_PROPERTIES']))
									{
										foreach ($arJSOffer['DISPLAY_PROPERTIES'] as $arOneProp)
										{
											$strProps .= '<br>'.$arOneProp['NAME'].' <strong>'.(
												is_array($arOneProp['VALUE'])
													? implode(' / ', $arOneProp['VALUE'])
													: $arOneProp['VALUE']
												).'</strong>';
										}
									}
									$arItem['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
								}
							}
							$arJSParams = array(
								'PRODUCT_TYPE' => $arItem['PRODUCT']['TYPE'],
								'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
								'SHOW_ADD_BASKET_BTN' => false,
								'SHOW_BUY_BTN' => true,
								'SHOW_ABSENT' => true,
								'SHOW_SKU_PROPS' => $arItem['OFFERS_PROPS_DISPLAY'],
								'SECOND_PICT' => $arItem['SECOND_PICT'],
								'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
								'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
								'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
								'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
								'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
								'DEFAULT_PICTURE' => array(
									'PICTURE' => $arItem['PRODUCT_PREVIEW'],
									'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
								),
								'VISUAL' => array(
									'ID' => $arItemIDs['ID'],
									'PICT_ID' => $arItemIDs['PICT'],
									'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
									'QUANTITY_ID' => $arItemIDs['QUANTITY'],
									'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
									'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
									'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
									'PRICE_ID' => $arItemIDs['PRICE'],
									'TREE_ID' => $arItemIDs['PROP_DIV'],
									'TREE_ITEM_ID' => $arItemIDs['PROP'],
									'BUY_ID' => $arItemIDs['BUY_LINK'],
									'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
									'DSC_PERC' => $arItemIDs['DSC_PERC'],
									'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
									'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
									'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
									'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
									'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK']
								),
								'BASKET' => array(
									'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
									'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
									'SKU_PROPS' => $arItem['OFFERS_PROP_CODES'],
									'BASKET_URL' => $arParams['~BASKET_URL'],
									'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
									'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
								),
								'PRODUCT' => array(
									'ID' => $arItem['ID'],
									'NAME' => $productTitle
								),
								'OFFERS' => $arItem['JS_OFFERS'],
								'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
								'TREE_PROPS' => $arSkuProps
							);
							if ($arParams['DISPLAY_COMPARE'])
							{
								$arJSParams['COMPARE'] = array(
									'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
									'COMPARE_PATH' => $arParams['COMPARE_PATH']
								);
							}
							?>
								<script type="text/javascript">
							var <?php  echo $strObName; ?> = new JCCatalogTopSlider(<?php  echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
								</script>
							<?php 
							}
							}
							else
							{
							$arJSParams = array(
								'PRODUCT_TYPE' => $arItem['PRODUCT']['TYPE'],
								'SHOW_QUANTITY' => false,
								'SHOW_ADD_BASKET_BTN' => false,
								'SHOW_BUY_BTN' => false,
								'SHOW_ABSENT' => false,
								'SHOW_SKU_PROPS' => false,
								'SECOND_PICT' => $arItem['SECOND_PICT'],
								'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
								'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
								'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
								'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
								'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
								'DEFAULT_PICTURE' => array(
									'PICTURE' => $arItem['PRODUCT_PREVIEW'],
									'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
								),
								'VISUAL' => array(
									'ID' => $arItemIDs['ID'],
									'PICT_ID' => $arItemIDs['PICT'],
									'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
									'QUANTITY_ID' => $arItemIDs['QUANTITY'],
									'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
									'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
									'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
									'PRICE_ID' => $arItemIDs['PRICE'],
									'TREE_ID' => $arItemIDs['PROP_DIV'],
									'TREE_ITEM_ID' => $arItemIDs['PROP'],
									'BUY_ID' => $arItemIDs['BUY_LINK'],
									'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
									'DSC_PERC' => $arItemIDs['DSC_PERC'],
									'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
									'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
									'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
									'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
									'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK']
								),
								'BASKET' => array(
									'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
									'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
									'SKU_PROPS' => $arItem['OFFERS_PROP_CODES'],
									'BASKET_URL' => $arParams['~BASKET_URL'],
									'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
									'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
								),
								'PRODUCT' => array(
									'ID' => $arItem['ID'],
									'NAME' => $productTitle
								),
								'OFFERS' => array(),
								'OFFER_SELECTED' => 0,
								'TREE_PROPS' => array()
							);
							if ($arParams['DISPLAY_COMPARE'])
							{
								$arJSParams['COMPARE'] = array(
									'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
									'COMPARE_PATH' => $arParams['COMPARE_PATH']
								);
							}
							?>
								<script type="text/javascript">
							var <?php  echo $strObName; ?> = new JCCatalogTopSlider(<?php  echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
								</script>
								<?php 
							}
							}
							?>
						</div></div>
					<?php 
				}
				?>
				<div style="clear: both;"></div>
			</div>
			<?php 
			$boolFirst = false;
		}
		?>
	</div>
	<?php 
	if (1 < $intRowsCount)
	{
		$arJSParams = array(
			'cont' => $strContID,
			'left' => array(
				'id' => $strContID.'_left_arr',
				'className' => 'bx_catalog_tile_slider_arrow_left'
			),
			'right' => array(
				'id' => $strContID.'_right_arr',
				'className' => 'bx_catalog_tile_slider_arrow_right'
			),
			'rows' => $arRowIDs,
			'rotate' => (0 < $arParams['ROTATE_TIMER']),
			'rotateTimer' => $arParams['ROTATE_TIMER']
		);
		if ('Y' == $arParams['SHOW_PAGINATION'])
		{
			$arJSParams['pagination'] = array(
				'id' => $strContID.'_pagination',
				'className' => 'bx_catalog_tile_slider_pagination'
			);
		}
		?>
		<script type="text/javascript">
			var ob<?php  echo $strContID; ?> = new JCCatalogTopSliderList(<?php  echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
		</script>
		<?php 
	}
	?>
</div>