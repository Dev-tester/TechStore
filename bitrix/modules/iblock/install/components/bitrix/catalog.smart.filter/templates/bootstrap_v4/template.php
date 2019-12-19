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
$templateData = array(
	'TEMPLATE_CLASS' => 'bx-'.$arParams['TEMPLATE_THEME']
);

if (isset($templateData['TEMPLATE_THEME']))
{
	$this->addExternalCss($templateData['TEMPLATE_THEME']);
}

?>
<div class="smart-filter mb-4 <?=$templateData["TEMPLATE_CLASS"]?> <?php if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL") echo "smart-filter-horizontal"?>">
	<div class="smart-filter-section">

		<div class="smart-filter-title"><?php echo GetMessage("CT_BCSF_FILTER_TITLE")?></div>

		<form name="<?php echo $arResult["FILTER_NAME"]."_form"?>" action="<?php echo $arResult["FORM_ACTION"]?>" method="get" class="smart-filter-form">

			<?php foreach($arResult["HIDDEN"] as $arItem):?>
				<input type="hidden" name="<?php echo $arItem["CONTROL_NAME"]?>" id="<?php echo $arItem["CONTROL_ID"]?>" value="<?php echo $arItem["HTML_VALUE"]?>" />
			<?php endforeach;?>

			<div class="row">
				<?php foreach($arResult["ITEMS"] as $key=>$arItem)//prices
				{
					$key = $arItem["ENCODED_ID"];
					if(isset($arItem["PRICE"])):
						if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
							continue;

						$step_num = 4;
						$step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / $step_num;
						$prices = array();
						if (Bitrix\Main\Loader::includeModule("currency"))
						{
							for ($i = 0; $i < $step_num; $i++)
							{
								$prices[$i] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MIN"]["VALUE"] + $step*$i, $arItem["VALUES"]["MIN"]["CURRENCY"], false);
							}
							$prices[$step_num] = CCurrencyLang::CurrencyFormat($arItem["VALUES"]["MAX"]["VALUE"], $arItem["VALUES"]["MAX"]["CURRENCY"], false);
						}
						else
						{
							$precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
							for ($i = 0; $i < $step_num; $i++)
							{
								$prices[$i] = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step*$i, $precision, ".", "");
							}
							$prices[$step_num] = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
						}
						?>

						<div class="<?php if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL"):?>col-sm-6 col-md-4<?php else:?>col-12<?php endif?> mb-2 smart-filter-parameters-box bx-active">
							<span class="smart-filter-container-modef"></span>

							<div class="smart-filter-parameters-box-title" onclick="smartFilter.hideFilterProps(this)">
								<span class="smart-filter-parameters-box-title-text"><?=$arItem["NAME"]?></span>
								<span data-role="prop_angle" class="smart-filter-angle smart-filter-angle-up">
									<span  class="smart-filter-angles"></span>
								</span>
							</div>

							<div class="smart-filter-block" data-role="bx_filter_block">
								<div class="smart-filter-parameters-box-container">
									<div class="smart-filter-input-group-number">
										<div class="d-flex justify-content-between">
											<div class="form-group" style="width: calc(50% - 10px);">
												<div class="smart-filter-input-container">
													<input
														class="min-price form-control form-control-sm"
														type="number"
														name="<?php echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
														id="<?php echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
														value="<?php echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
														size="5"
														placeholder="<?=GetMessage("CT_BCSF_FILTER_FROM")?>"
														onkeyup="smartFilter.keyup(this)"
													/>
												</div>
											</div>

											<div class="form-group" style="width: calc(50% - 10px);">
												<div class="smart-filter-input-container">
													<input
														class="max-price form-control form-control-sm"
														type="number"
														name="<?php echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
														id="<?php echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
														value="<?php echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
														size="5"
														placeholder="<?=GetMessage("CT_BCSF_FILTER_TO")?>"
														onkeyup="smartFilter.keyup(this)"
													/>
												</div>
											</div>
										</div>

										<div class="smart-filter-slider-track-container">
											<div class="smart-filter-slider-track" id="drag_track_<?=$key?>">
												<?php for($i = 0; $i <= $step_num; $i++):?>
												<div class="smart-filter-slider-ruler p<?=$i+1?>"><span><?=$prices[$i]?></span></div>
												<?php endfor;?>
												<div class="smart-filter-slider-price-bar-vd" style="left: 0;right: 0;" id="colorUnavailableActive_<?=$key?>"></div>
												<div class="smart-filter-slider-price-bar-vn" style="left: 0;right: 0;" id="colorAvailableInactive_<?=$key?>"></div>
												<div class="smart-filter-slider-price-bar-v"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>
												<div class="smart-filter-slider-range" id="drag_tracker_<?=$key?>"  style="left: 0; right: 0;">
													<a class="smart-filter-slider-handle left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
													<a class="smart-filter-slider-handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php 

						$arJsParams = array(
							"leftSlider" => 'left_slider_'.$key,
							"rightSlider" => 'right_slider_'.$key,
							"tracker" => "drag_tracker_".$key,
							"trackerWrap" => "drag_track_".$key,
							"minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
							"maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
							"minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
							"maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
							"curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
							"curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
							"fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
							"fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
							"precision" => $precision,
							"colorUnavailableActive" => 'colorUnavailableActive_'.$key,
							"colorAvailableActive" => 'colorAvailableActive_'.$key,
							"colorAvailableInactive" => 'colorAvailableInactive_'.$key,
						);
						?>
						<script type="text/javascript">
							BX.ready(function(){
								window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
							});
						</script>
					<?php endif;
				}

				//not prices
				foreach($arResult["ITEMS"] as $key=>$arItem)
				{
					if (empty($arItem["VALUES"]) || isset($arItem["PRICE"]))
						continue;

					if ($arItem["DISPLAY_TYPE"] == "A" && ( $arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0))
						continue;
					?>

					<div class="<?php if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL"):?>col-sm-6 col-md-4<?php else:?>col-lg-12<?php endif?> mb-2 smart-filter-parameters-box <?php if ($arItem["DISPLAY_EXPANDED"]== "Y"):?>bx-active<?php endif?>">
						<span class="smart-filter-container-modef"></span>

						<div class="smart-filter-parameters-box-title" onclick="smartFilter.hideFilterProps(this)">

							<span class="smart-filter-parameters-box-title-text"><?=$arItem["NAME"]?></span>

							<span data-role="prop_angle" class="smart-filter-angle smart-filter-angle-<?php if ($arItem["DISPLAY_EXPANDED"]== "Y"):?>up<?php else:?>down<?php endif?>">
								<span  class="smart-filter-angles"></span>
							</span>

							<?php if ($arItem["FILTER_HINT"] <> ""):?>
								<span class="smart-filter-hint">
									<span class="smart-filter-hint-icon">?</span>
									<span class="smart-filter-hint-popup">
										<span class="smart-filter-hint-popup-angle"></span>
										<span class="smart-filter-hint-popup-content">

										</span>	<?=$arItem["FILTER_HINT"]?></span>
								</span>
							<?php endif?>
						</div>

						<div class="smart-filter-block" data-role="bx_filter_block">
							<div class="smart-filter-parameters-box-container">
							<?php 
							$arCur = current($arItem["VALUES"]);
							switch ($arItem["DISPLAY_TYPE"])
							{
								//region NUMBERS_WITH_SLIDER +
								case "A":
								?>
									<div class="smart-filter-input-group-number">
										<div class="d-flex justify-content-between">

											<div class="form-group" style="width: calc(50% - 10px);">
												<div class="smart-filter-input-container">
													<input class="min-price form-control form-control-sm"
														type="number"
														name="<?php echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
														id="<?php echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
														value="<?php echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
														size="5"
														placeholder="<?=GetMessage("CT_BCSF_FILTER_FROM")?>"
														onkeyup="smartFilter.keyup(this)"
													/>
												</div>
											</div>

											<div class="form-group" style="width: calc(50% - 10px);">
												<div class="smart-filter-input-container">
													<input
														class="max-price form-control form-control-sm"
														type="number"
														name="<?php echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
														id="<?php echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
														value="<?php echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
														size="5"
														placeholder="<?=GetMessage("CT_BCSF_FILTER_TO")?>"
														onkeyup="smartFilter.keyup(this)"
													/>
												</div>
											</div>

										</div>

										<div class="smart-filter-slider-track-container">
											<div class="smart-filter-slider-track" id="drag_track_<?=$key?>">
												<?php 
													$precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
													$step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 4;
													$value1 = number_format($arItem["VALUES"]["MIN"]["VALUE"], $precision, ".", "");
													$value2 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step, $precision, ".", "");
													$value3 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 2, $precision, ".", "");
													$value4 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 3, $precision, ".", "");
													$value5 = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
												?>
												<div class="smart-filter-slider-ruler p1"><span><?=$value1?></span></div>
												<div class="smart-filter-slider-ruler p2"><span><?=$value2?></span></div>
												<div class="smart-filter-slider-ruler p3"><span><?=$value3?></span></div>
												<div class="smart-filter-slider-ruler p4"><span><?=$value4?></span></div>
												<div class="smart-filter-slider-ruler p5"><span><?=$value5?></span></div>

												<div class="smart-filter-slider-price-bar-vd" style="left: 0;right: 0;" id="colorUnavailableActive_<?=$key?>"></div>
												<div class="smart-filter-slider-price-bar-vn" style="left: 0;right: 0;" id="colorAvailableInactive_<?=$key?>"></div>
												<div class="smart-filter-slider-price-bar-v"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>
												<div class="smart-filter-slider-range" 	id="drag_tracker_<?=$key?>"  style="left: 0;right: 0;">
													<a class="smart-filter-slider-handle left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
													<a class="smart-filter-slider-handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>
												</div>
											</div>
										</div>
									</div>

									<?php 
										$arJsParams = array(
										"leftSlider" => 'left_slider_'.$key,
										"rightSlider" => 'right_slider_'.$key,
										"tracker" => "drag_tracker_".$key,
										"trackerWrap" => "drag_track_".$key,
										"minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
										"maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
										"minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
										"maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
										"curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
										"curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
										"fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
										"fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
										"precision" => $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0,
										"colorUnavailableActive" => 'colorUnavailableActive_'.$key,
										"colorAvailableActive" => 'colorAvailableActive_'.$key,
										"colorAvailableInactive" => 'colorAvailableInactive_'.$key,
									);
									?>
										<script type="text/javascript">
											BX.ready(function(){
												window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
											});
										</script>
									<?php 

								break;

								//endregion

								//region NUMBERS +
								case "B":
								?>
									<div class="smart-filter-input-group-number">
										<div class="d-flex justify-content-between">
											<div class="form-group" style="width: calc(50% - 10px);">
												<div class="smart-filter-input-container">
													<input
														class="min-price form-control form-control-sm"
														type="number"
														name="<?php echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
														id="<?php echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
														value="<?php echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
														size="5"
														placeholder="<?=GetMessage("CT_BCSF_FILTER_FROM")?>"
														onkeyup="smartFilter.keyup(this)"
														/>
												</div>
											</div>

											<div class="form-group" style="width: calc(50% - 10px);">
											<div class="smart-filter-input-container">
												<input
													class="max-price form-control form-control-sm"
													type="number"
													name="<?php echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
													id="<?php echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
													value="<?php echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
													size="5"
													placeholder="<?=GetMessage("CT_BCSF_FILTER_TO")?>"
													onkeyup="smartFilter.keyup(this)"
													/>
											</div>
										</div>
										</div>
									</div>
								<?php 
								break;
								//endregion

								//region CHECKBOXES_WITH_PICTURES +
								case "G":
								?>
									<div class="smart-filter-input-group-checkbox-pictures">
										<?php foreach ($arItem["VALUES"] as $val => $ar):?>
											<input
												style="display: none"
												type="checkbox"
												name="<?=$ar["CONTROL_NAME"]?>"
												id="<?=$ar["CONTROL_ID"]?>"
												value="<?=$ar["HTML_VALUE"]?>"
												<?php  echo $ar["CHECKED"]? 'checked="checked"': '' ?>
											/>
											<?php 
												$class = "";
												if ($ar["CHECKED"])
													$class.= " bx-active";
												if ($ar["DISABLED"])
													$class.= " disabled";
											?>
											<label for="<?=$ar["CONTROL_ID"]?>"
												   data-role="label_<?=$ar["CONTROL_ID"]?>"
												   class="smart-filter-checkbox-label<?=$class?>"
												   onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
												<span class="smart-filter-checkbox-btn bx-color-sl">
													<?php if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
														<span class="smart-filter-checkbox-btn-image" style="background-image: url('<?=$ar["FILE"]["SRC"]?>');"></span>
													<?php endif?>
												</span>
											</label>
										<?php endforeach?>
										<div style="clear: both;"></div>
									</div>
								<?php 
								break;
								//endregion

								//region CHECKBOXES_WITH_PICTURES_AND_LABELS +
								case "H":
								?>
									<div class="smart-filter-input-group-checkbox-pictures-text">
										<?php foreach ($arItem["VALUES"] as $val => $ar):?>
										<input
											style="display: none"
											type="checkbox"
											name="<?=$ar["CONTROL_NAME"]?>"
											id="<?=$ar["CONTROL_ID"]?>"
											value="<?=$ar["HTML_VALUE"]?>"
											<?php  echo $ar["CHECKED"]? 'checked="checked"': '' ?>
										/>
										<?php 
											$class = "";
											if ($ar["CHECKED"])
												$class.= " bx-active";
											if ($ar["DISABLED"])
												$class.= " disabled";
										?>
										<label for="<?=$ar["CONTROL_ID"]?>"
											   data-role="label_<?=$ar["CONTROL_ID"]?>"
											   class="smart-filter-checkbox-label<?=$class?>"
											   onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
											<span class="smart-filter-checkbox-btn">
												<?php if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
													<span class="smart-filter-checkbox-btn-image" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
												<?php endif?>
											</span>
											<span class="smart-filter-checkbox-text" title="<?=$ar["VALUE"];?>">
												<?=$ar["VALUE"];
												if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
													?> (<span data-role="count_<?=$ar["CONTROL_ID"]?>"><?php  echo $ar["ELEMENT_COUNT"]; ?></span>)<?php 
												endif;?>
											</span>
										</label>
									<?php endforeach?>
									</div>
								<?php 
								break;
								//endregion

								//region DROPDOWN +
								case "P":
								?>
									<?php  $checkedItemExist = false; ?>
									<div class="smart-filter-input-group-dropdown">
										<div class="smart-filter-dropdown-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
											<div class="smart-filter-dropdown-text" data-role="currentOption">
												<?php foreach ($arItem["VALUES"] as $val => $ar)
												{
													if ($ar["CHECKED"])
													{
														echo $ar["VALUE"];
														$checkedItemExist = true;
													}
												}
												if (!$checkedItemExist)
												{
													echo GetMessage("CT_BCSF_FILTER_ALL");
												}
												?>
											</div>
											<div class="smart-filter-dropdown-arrow"></div>
											<input
												style="display: none"
												type="radio"
												name="<?=$arCur["CONTROL_NAME_ALT"]?>"
												id="<?php  echo "all_".$arCur["CONTROL_ID"] ?>"
												value=""
											/>
											<?php foreach ($arItem["VALUES"] as $val => $ar):?>
												<input
													style="display: none"
													type="radio"
													name="<?=$ar["CONTROL_NAME_ALT"]?>"
													id="<?=$ar["CONTROL_ID"]?>"
													value="<?php  echo $ar["HTML_VALUE_ALT"] ?>"
													<?php  echo $ar["CHECKED"]? 'checked="checked"': '' ?>
												/>
											<?php endforeach?>

											<div class="smart-filter-dropdown-popup" data-role="dropdownContent" style="display: none;">
												<ul>
													<li>
														<label for="<?="all_".$arCur["CONTROL_ID"]?>"
															   class="smart-filter-dropdown-label"
															   data-role="label_<?="all_".$arCur["CONTROL_ID"]?>"
															   onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
															<?=GetMessage("CT_BCSF_FILTER_ALL"); ?>
														</label>
													</li>
													<?php foreach ($arItem["VALUES"] as $val => $ar):
														$class = "";
														if ($ar["CHECKED"])
															$class.= " selected";
														if ($ar["DISABLED"])
															$class.= " disabled";
													?>
														<li>
															<label for="<?=$ar["CONTROL_ID"]?>"
																   class="smart-filter-dropdown-label<?=$class?>"
																   data-role="label_<?=$ar["CONTROL_ID"]?>"
																   onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')">
																<?=$ar["VALUE"]?>
															</label>
														</li>
													<?php endforeach?>
												</ul>
											</div>
										</div>
									</div>
								<?php 
								break;
								//endregion

								//region DROPDOWN_WITH_PICTURES_AND_LABELS
								case "R":
									?>
										<div class="smart-filter-input-group-dropdown">
											<div class="smart-filter-dropdown-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
												<div class="smart-filter-input-group-dropdown-flex" data-role="currentOption">
													<?php 
													$checkedItemExist = false;
													foreach ($arItem["VALUES"] as $val => $ar):
														if ($ar["CHECKED"])
														{
														?>
															<?php if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
																<span class="smart-filter-checkbox-btn-image" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
															<?php endif?>
															<span class="smart-filter-dropdown-text"><?=$ar["VALUE"]?></span>
														<?php 
															$checkedItemExist = true;
														}
													endforeach;
													if (!$checkedItemExist)
													{
														?>
															<span class="smart-filter-checkbox-btn-image all"></span>
															<span class="smart-filter-dropdown-text"><?=GetMessage("CT_BCSF_FILTER_ALL");?></span>
														<?php 
													}
													?>
												</div>

												<div class="smart-filter-dropdown-arrow"></div>

												<input
													style="display: none"
													type="radio"
													name="<?=$arCur["CONTROL_NAME_ALT"]?>"
													id="<?php  echo "all_".$arCur["CONTROL_ID"] ?>"
													value=""
												/>
												<?php foreach ($arItem["VALUES"] as $val => $ar):?>
													<input
														style="display: none"
														type="radio"
														name="<?=$ar["CONTROL_NAME_ALT"]?>"
														id="<?=$ar["CONTROL_ID"]?>"
														value="<?=$ar["HTML_VALUE_ALT"]?>"
														<?php  echo $ar["CHECKED"]? 'checked="checked"': '' ?>
													/>
												<?php endforeach?>

												<div class="smart-filter-dropdown-popup" data-role="dropdownContent" style="display: none">
													<ul>
														<li style="border-bottom: 1px solid #e5e5e5;padding-bottom: 5px;margin-bottom: 5px;">
															<label for="<?="all_".$arCur["CONTROL_ID"]?>"
																   class="smart-filter-param-label"
																   data-role="label_<?="all_".$arCur["CONTROL_ID"]?>"
																   onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
																<span class="smart-filter-checkbox-btn-image all"></span>
																<span class="smart-filter-dropdown-text"><?=GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
															</label>
														</li>
													<?php 
													foreach ($arItem["VALUES"] as $val => $ar):
														$class = "";
														if ($ar["CHECKED"])
															$class.= " selected";
														if ($ar["DISABLED"])
															$class.= " disabled";
													?>
														<li>
															<label for="<?=$ar["CONTROL_ID"]?>"
																   data-role="label_<?=$ar["CONTROL_ID"]?>"
																   class="smart-filter-param-label<?=$class?>"
																   onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')">
																<?php if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
																	<span class="smart-filter-checkbox-btn-image" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
																<?php endif?>
																<span class="smart-filter-dropdown-text"><?=$ar["VALUE"]?></span>
															</label>
														</li>
													<?php endforeach?>
													</ul>
												</div>
											</div>
										</div>
									<?php 
									break;
								//endregion

								//region RADIO_BUTTONS
								case "K":
									?>
									<div class="col">
										<div class="radio">
											<label class="smart-filter-param-label" for="<?php  echo "all_".$arCur["CONTROL_ID"] ?>">
												<span class="smart-filter-input-checkbox">
													<input
														type="radio"
														value=""
														name="<?php  echo $arCur["CONTROL_NAME_ALT"] ?>"
														id="<?php  echo "all_".$arCur["CONTROL_ID"] ?>"
														onclick="smartFilter.click(this)"
													/>
													<span class="smart-filter-param-text"><?php  echo GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
												</span>
											</label>
										</div>
										<?php foreach($arItem["VALUES"] as $val => $ar):?>
											<div class="radio">
												<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="smart-filter-param-label" for="<?php  echo $ar["CONTROL_ID"] ?>">
													<span class="smart-filter-input-checkbox <?php  echo $ar["DISABLED"] ? 'disabled': '' ?>">
														<input
															type="radio"
															value="<?php  echo $ar["HTML_VALUE_ALT"] ?>"
															name="<?php  echo $ar["CONTROL_NAME_ALT"] ?>"
															id="<?php  echo $ar["CONTROL_ID"] ?>"
															<?php  echo $ar["CHECKED"]? 'checked="checked"': '' ?>
															onclick="smartFilter.click(this)"
														/>
														<span class="smart-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?php 
														if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
															?>&nbsp;(<span data-role="count_<?=$ar["CONTROL_ID"]?>"><?php  echo $ar["ELEMENT_COUNT"]; ?></span>)<?php 
														endif;?></span>
													</span>
												</label>
											</div>
										<?php endforeach;?>
									</div>
									<div class="w-100"></div>
									<?php 
									break;

								//endregion

								//region CALENDAR
								case "U":
									?>
									<div class="col">
										<div class=""><div class="smart-filter-input-container smart-filter-calendar-container">
											<?php $APPLICATION->IncludeComponent(
												'bitrix:main.calendar',
												'',
												array(
													'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
													'SHOW_INPUT' => 'Y',
													'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
													'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
													'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
													'SHOW_TIME' => 'N',
													'HIDE_TIMEBAR' => 'Y',
												),
												null,
												array('HIDE_ICONS' => 'Y')
											);?>
										</div></div>
										<div class=""><div class="smart-filter-input-container smart-filter-calendar-container">
											<?php $APPLICATION->IncludeComponent(
												'bitrix:main.calendar',
												'',
												array(
													'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
													'SHOW_INPUT' => 'Y',
													'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
													'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
													'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
													'SHOW_TIME' => 'N',
													'HIDE_TIMEBAR' => 'Y',
												),
												null,
												array('HIDE_ICONS' => 'Y')
											);?>
										</div></div>
									</div>
									<div class="w-100"></div>
									<?php 
									break;
								//endregion

								//region CHECKBOXES +
								default:
									?>
								<div class="smart-filter-input-group-checkbox-list">
										<?php foreach($arItem["VALUES"] as $val => $ar):?>
											<div class="form-group form-check mb-1">
												<input
													type="checkbox"
													value="<?php  echo $ar["HTML_VALUE"] ?>"
													name="<?php  echo $ar["CONTROL_NAME"] ?>"
													id="<?php  echo $ar["CONTROL_ID"] ?>"
													class="form-check-input"
													<?php  echo $ar["CHECKED"]? 'checked="checked"': '' ?>
													<?php  echo $ar["DISABLED"] ? 'disabled': '' ?>
													onclick="smartFilter.click(this)"
												/>
												<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="smart-filter-checkbox-text form-check-label" for="<?php  echo $ar["CONTROL_ID"] ?>">
													<?=$ar["VALUE"];
													if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
														?>&nbsp;(<span data-role="count_<?=$ar["CONTROL_ID"]?>"><?php  echo $ar["ELEMENT_COUNT"]; ?></span>)<?php 
													endif;?>
												</label>
											</div>
										<?php endforeach;?>
								</div>
							<?php 
								//endregion
							}
							?>
							</div>
						</div>
					</div>
				<?php 
				}
				?>
			</div><!--//row-->

			<div class="row">
				<div class="col smart-filter-button-box">
					<div class="smart-filter-block">
						<div class="smart-filter-parameters-box-container">
							<input
								class="btn btn-primary"
								type="submit"
								id="set_filter"
								name="set_filter"
								value="<?=GetMessage("CT_BCSF_SET_FILTER")?>"
							/>
							<input
								class="btn btn-link"
								type="submit"
								id="del_filter"
								name="del_filter"
								value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>"
							/>
							<div class="smart-filter-popup-result <?php if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL") echo $arParams["POPUP_POSITION"]?>" id="modef" <?php if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?> style="display: inline-block;">
								<?php echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
								<span class="arrow"></span>
								<br/>
								<a href="<?php echo $arResult["FILTER_URL"]?>" target=""><?php echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>

	</div>
</div>

<script type="text/javascript">
	var smartFilter = new JCSmartFilter('<?php echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>