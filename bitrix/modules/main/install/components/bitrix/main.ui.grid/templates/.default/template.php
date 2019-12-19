<?php 

/**
 * @var $arParams
 * @var $arResult
 */

use \Bitrix\Main\Text;
use \Bitrix\Main\Grid;
use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Extension;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

Extension::load([
	'popup',
	'ui',
	'resize_observer',
	'loader',
	'ui.actionpanel',
	'ui.fonts.opensans',
	'dnd',
]);

global $APPLICATION;
$bodyClass = $APPLICATION->GetPageProperty("BodyClass");
$APPLICATION->SetPageProperty("BodyClass", ($bodyClass ? $bodyClass." " : "")."grid-mode");

if ($arParams['FLEXIBLE_LAYOUT'])
{
	$bodyClass = $APPLICATION->getPageProperty('BodyClass', false);
	$APPLICATION->setPageProperty('BodyClass', trim(sprintf('%s %s', $bodyClass, 'flexible-layout')));
}

$additionalColumnsCount = 1;

if ($arParams["SHOW_ROW_CHECKBOXES"])
{
	$additionalColumnsCount += 1;
}

if ($arParams["SHOW_GRID_SETTINGS_MENU"] || $arParams["SHOW_ROW_ACTIONS_MENU"])
{
	$additionalColumnsCount += 1;
}

if ($arParams["ALLOW_ROWS_SORT"])
{
	$additionalColumnsCount += 1;
}

$stickedColumnsCount = 0;

foreach ($arResult["COLUMNS"] as $header)
{
	if ($header["sticked"] === true)
	{
		$stickedColumnsCount += 1;
	}
}

$displayedCount = count(
	array_filter(
		$arParams["ROWS"],
		function($val)
		{
			return $val["not_count"] !== true;
		}
	)
);

?>

<div class="main-grid<?=$arResult["IS_AJAX"] ? " main-grid-load-animation" : ""?><?=!$arParams["ALLOW_HORIZONTAL_SCROLL"] ? " main-grid-full" : ""?><?=$arParams["ALLOW_ROWS_SORT"] ? " main-grid-rows-sort-enable" : ""?>" id="<?=$arParams["GRID_ID"]?>" data-ajaxid="<?=$arParams["AJAX_ID"]?>"<?=$arResult['IS_AJAX'] ? " style=\"display: none;\"" : ""?>><?php 
	?><form name="form_<?=$arParams["GRID_ID"]?>" action="<?=POST_FORM_ACTION_URI; ?>" method="POST"><?php 
		?><?=bitrix_sessid_post() ?><?php 
		?><div class="main-grid-settings-window"><?php 
			?><div class="main-grid-settings-window-select-links"><?php 
				?><span class="main-grid-settings-window-select-link main-grid-settings-window-select-all"><?=Loc::getMessage("interface_grid_settings_select_all_columns")?></span><?php 
				?><span class="main-grid-settings-window-select-link main-grid-settings-window-unselect-all"><?=Loc::getMessage("interface_grid_settings_unselect_all_columns")?></span><?php 
			?></div><?php 
			?><div class="main-grid-settings-window-list"><?php 
				foreach ($arResult["COLUMNS_ALL"] as $key => $column) : ?><?php 
					?><div data-name="<?=Text\HtmlFilter::encode($column["id"])?>" class="main-grid-settings-window-list-item <?=$arParams["ALLOW_STICKED_COLUMNS"] && $column["sticked"] && array_key_exists($column["id"], $arResult["COLUMNS"]) ? "main-grid-settings-window-list-item-sticked" : ""?>" data-sticked-default="<?=$column["sticked_default"]?>"><?php 
						?><input id="<?=Text\HtmlFilter::encode($column["id"])?>-checkbox" type="checkbox" class="main-grid-settings-window-list-item-checkbox" <?=array_key_exists($column["id"], $arResult["COLUMNS"]) ? " checked" : ""?>><?php 
						?><label for="<?=Text\HtmlFilter::encode($column["id"])?>-checkbox" class="main-grid-settings-window-list-item-label"><?=htmlspecialcharsbx(htmlspecialcharsback($column["name"]))?></label><?php 
						?><span class="main-grid-settings-window-list-item-edit-button<?=!$arParams["ALLOW_STICKED_COLUMNS"] ? " main-grid-reset-right" : ""?>"></span><?php 
						if ($arParams["ALLOW_STICKED_COLUMNS"]) :
							?><span class="main-grid-settings-window-list-item-sticky-button"></span><?php 
						endif;
					?></div><?php 
				endforeach;
			?></div><?php 
			?><div class="popup-window-buttons"><?php 
				?><span class="main-grid-settings-window-buttons-wrapper"><?php 
					?><span class="main-grid-settings-window-actions-item-button main-grid-settings-window-actions-item-reset" id="<?=$arParams["GRID_ID"]?>-grid-settings-reset-button"><?=Loc::getMessage("interface_grid_restore_to_default")?></span><?php 
					if ($USER->CanDoOperation("edit_other_settings")) :
					?><span class="main-grid-settings-window-actions-item-button main-grid-settings-window-for-all">
						<input name="grid-settings-window-for-all" type="checkbox" id="<?=$arParams["GRID_ID"]?>-main-grid-settings-window-for-all-checkbox" class="main-grid-settings-window-for-all-checkbox">
						<label for="<?=$arParams["GRID_ID"]?>-main-grid-settings-window-for-all-checkbox" class="main-grid-settings-window-for-all-label"><?=Loc::getMessage("interface_grid_settings_for_all_label")?></label><?php 
					?></span><?php 
					endif;
				?></span><?php 
				?><span class="main-grid-settings-window-actions-item-button webform-small-button" id="<?=$arParams["GRID_ID"]?>-grid-settings-apply-button"><?=Loc::getMessage("interface_grid_apply_settings")?></span><?php 
				?><span class="main-grid-settings-window-actions-item-button webform-small-button webform-small-button-transparent" id="<?=$arParams["GRID_ID"]?>-grid-settings-cancel-button"><?=Loc::getMessage("interface_grid_cancel_settings")?></span><?php 
			?></div><?php 
		?></div><?php 
		?><div class="main-grid-wrapper<?=!$arParams["ALLOW_HORIZONTAL_SCROLL"] ? " main-grid-full" : "" ?>"><?php 
			?><div class="<?=$arParams["ALLOW_HORIZONTAL_SCROLL"] ? "main-grid-fade" : "" ?>"><?php 
				if ($arParams["ALLOW_HORIZONTAL_SCROLL"]) : ?><?php 
					?><div class="main-grid-fade-shadow-left"></div><?php 
					?><div class="main-grid-fade-shadow-right"></div><?php 
					?><div class="main-grid-ear main-grid-ear-left"></div><?php 
					?><div class="main-grid-ear main-grid-ear-right"></div><?php 
				endif; ?><?php 
				?><div class="main-grid-loader-container"></div><?php 
				?><div class="main-grid-container<?=$arParams["ALLOW_STICKED_COLUMNS"] && $arResult["HAS_STICKED_COLUMNS"] ? " main-grid-with-sticked" : ""?>"><?php 
					?><table class="main-grid-table" id="<?=$arParams["GRID_ID"]?>_table"><?php 
						if (!$arResult['BX_isGridAjax']): ?><?php 
							?><thead class="main-grid-header" data-relative="<?=$arParams["GRID_ID"]?>"><?php 
								?><tr class="main-grid-row-head"><?php 
									if ($arParams["ALLOW_ROWS_SORT"]) :
									?><th class="main-grid-cell-head main-grid-cell-static main-grid-cell-drag<?=$arParams["ALLOW_STICKED_COLUMNS"] && $arResult["HAS_STICKED_COLUMNS"] ? " main-grid-sticked-column" : ""?>"><?php 
										?><span class="main-grid-cell-head-container">&nbsp;</span><?php 
									?></th><?php 
									endif;
									if ($arParams["SHOW_ROW_CHECKBOXES"]): ?><?php 
										?><th class="main-grid-cell-head main-grid-cell-static main-grid-cell-checkbox<?=$arParams["ALLOW_STICKED_COLUMNS"] && $arResult["HAS_STICKED_COLUMNS"] ? " main-grid-sticked-column" : ""?>"><?php 
											if ($arParams["SHOW_CHECK_ALL_CHECKBOXES"]): ?><?php 
												?><span class="main-grid-cell-head-container"><?php 
													?><span class="main-grid-checkbox-container main-grid-head-checkbox-container"><?php 
														?><input class="main-grid-checkbox main-grid-row-checkbox main-grid-check-all" id="<?=$arParams["GRID_ID"]?>_check_all" type="checkbox" title="<?=getMessage('interface_grid_check_all') ?>"<?php  if (!$arResult['ALLOW_EDIT']): ?> disabled<?php  endif ?>><?php 
														?><label class="main-grid-checkbox" for="<?=$arParams["GRID_ID"]?>_check_all"></label><?php 
												?></span><?php 
											?></span><?php 
										endif; ?><?php 
										?></th><?php 
									endif ?><?php 
									if ($arParams["SHOW_GRID_SETTINGS_MENU"] || $arParams["SHOW_ROW_ACTIONS_MENU"]) : ?><?php 
										?><th class="main-grid-cell-head main-grid-cell-static main-grid-cell-action<?=$arParams["ALLOW_STICKED_COLUMNS"] && $arResult["HAS_STICKED_COLUMNS"] ? " main-grid-sticked-column" : ""?>"><?php 
											if ($arParams["SHOW_GRID_SETTINGS_MENU"]) : ?><?php 
												?><span class="main-grid-interface-settings-icon"></span><?php 
											endif; ?><?php 
										?></th><?php 
									endif; ?><?php 
									foreach ($arResult['COLUMNS'] as $id => $header) : ?><?php 
									$isHidden = !array_key_exists($id, $arResult['COLUMNS']); ?><?php 
										?><th class="main-grid-cell-head <?=$header["class"]?> <?=$arParams["ALLOW_COLUMNS_SORT"] ? " main-grid-draggable" : ""?> <?=$arParams["ALLOW_STICKED_COLUMNS"] && $header["sticked"] ? "main-grid-sticked-column" : ""?>" data-edit="(<?=Text\HtmlFilter::encode(CUtil::PhpToJSObject($header["editable"]))?>)" data-name="<?=Text\HtmlFilter::encode($id)?>" data-sort-url="<?=$header["sort_url"]?>" data-sort-by="<?=$header["sort"]?>" data-sort-order="<?=$header["next_sort_order"]?>" <?=(isset($header['title']) && $header['title'] != '' ? 'title="'.Text\HtmlFilter::encode($header['title']).'"' : '');?> <?php  if($header["width"] <> ''): ?> style="width: <?=$header["width"]?>px"<?php  endif ?>><?php 
											?><span class="main-grid-cell-head-container" <?php  if($header["width"] <> ''): ?>style="width: <?=$header["width"]?>px"<?php  endif ?>><?php 
												?><span class="main-grid-head-title<?=$arParams['DISABLE_HEADERS_TRANSFORM'] ? " main-grid-head-title-without-transform" : ""?>"><?=Text\HtmlFilter::encode($header["showname"] ? $header["name"] : ""); ?></span><?php 
												if ($arParams["ALLOW_COLUMNS_RESIZE"] && $header["resizeable"] !== false) : ?><?php 
													?><span class="main-grid-resize-button" onclick="event.stopPropagation(); " title=""></span><?php 
												endif; ?><?php 
												if ($header["sort"] && $arParams["ALLOW_SORT"]) : ?><?php 
													?><span class="main-grid-control-sort main-grid-control-sort-<?=$header["sort_state"] ? $header["sort_state"] : "hover-".$header["order"]?>"></span><?php 
												endif;
											?></span><?php 
										?></th><?php 
									endforeach ?><?php 
									?><th class="main-grid-cell-head main-grid-cell-static main-grid-special-empty"></th><?php 
								?></tr><?php 
							?></thead><?php 
						endif ?><?php 
							?><tbody><?php 
							if (empty($arParams['ROWS'])): ?><?php 
								?><tr class="main-grid-row main-grid-row-empty main-grid-row-body"><?php 
									?><td class="main-grid-cell main-grid-cell-center" colspan="<?=count($arParams['COLUMNS']) + $additionalColumnsCount + $stickedColumnsCount?>"><?php 
										if (!isset($_REQUEST["apply_filter"])) :
											?><div class="main-grid-empty-block"><?php 
												?><div class="main-grid-empty-inner"><?php 
													?><div class="main-grid-empty-image"></div><?php 
													?><div class="main-grid-empty-text"><?=getMessage('interface_grid_no_data') ?></div><?php 
												?></div><?php 
											?></div><?php 
										else :
											?><div class="main-grid-empty-block"><?php 
												?><div class="main-grid-empty-inner"><?php 
													?><div class="main-grid-empty-image"></div><?php 
													?><div class="main-grid-empty-text"><?=getMessage('interface_grid_filter_no_data') ?></div><?php 
												?></div><?php 
											?></div><?php 
										endif; ?><?php 
									?></td><?php 
								?></tr><?php 
							else:
								foreach($arParams['ROWS'] as $key => $arRow):
									$rowClasses = isset($arRow['columnClasses']) && is_array($arRow['columnClasses'])
										? $arRow['columnClasses'] : array();
									$collapseRow = false;
								if (!empty($arRow["custom"])) :
									$lastCollapseGroup = $arRow["expand"] === false ? $arRow["group_id"] : null;
									?><tr class="main-grid-row main-grid-row-body main-grid-row-custom<?=$arRow["not_count"] ? " main-grid-not-count" : ""?><?=$arRow["draggable"] === false ? " main-grid-row-drag-disabled" : ""?><?=$arRow["expand"] ? " main-grid-row-expand" : ""?>"<?=$arRow["attrs_string"]?> data-id="<?=$arRow["id"]?>"><?php 
										?><td colspan="<?=count($arResult["COLUMNS"]) + $additionalColumnsCount?>" class="main-grid-cell main-grid-cell-center"><?php 
											if ($arParams["ENABLE_COLLAPSIBLE_ROWS"] && $arRow["has_child"] == true) :
												?><span class="main-grid-plus-button"></span><?php 
											endif;
											?><div class="main-grid-cell-content"><?=$arRow["custom"]?></div><?php 
										?></td><?php 
									?></tr><?php 
								elseif (!empty($arParams["ROW_LAYOUT"])) :
									$data_id = $arRow["id"];
									$actions = Text\HtmlFilter::encode(CUtil::PhpToJSObject($arRow["actions"]));
									$sDefAction = $arRow["default_action"];
									$depth = $arRow["depth"] > 0 ? 20*$arRow["depth"] : 0;
									$collapseRow = ($arParams["ENABLE_COLLAPSIBLE_ROWS"] && isset($arRow["parent_group_id"]) && $lastCollapseGroup === $arRow["parent_group_id"]);?>

									<tr class="main-grid-row main-grid-row-body<?=$arRow["not_count"] ? " main-grid-not-count" : ""?><?=$arRow["expand"] ? " main-grid-row-expand" : ""?><?=$arRow["draggable"] === false ? " main-grid-row-drag-disabled" : ""?><?=$collapseRow ? " main-grid-hide" : ""?>" data-child-loaded="<?=$arRow["expand"]?"true":"false"?>" data-depth="<?=htmlspecialcharsbx($arRow["depth"])?>" data-id="<?=$data_id ?>"<?=$arParams["ENABLE_COLLAPSIBLE_ROWS"] ? " data-parent-id=\"".htmlspecialcharsbx($arRow["parent_id"])."\"" : ""?> <?php if(!empty($sDefAction["js"])):?> data-default-action="<?=Text\HtmlFilter::encode($sDefAction["js"])?>" title="<?=GetMessage("interface_grid_dblclick")?><?=$sDefAction["title"]?>"<?php endif;?><?=$arRow["attrs_string"]?>>
										<?php  if ($arParams["ALLOW_ROWS_SORT"] && $arRow["draggable"] !== false) : ?>
											<th class="main-grid-cell main-grid-cell-drag" rowspan="<?=count($arParams["ROW_LAYOUT"])?>">
												<span class="main-grid-cell-content">&nbsp;</span>
											</th>
										<?php  endif; ?>
										<?php  if ($arParams["SHOW_ROW_CHECKBOXES"]): ?>
											<td class="main-grid-cell main-grid-cell-checkbox" rowspan="<?=count($arParams["ROW_LAYOUT"])?>">
												<span class="main-grid-cell-content">
													<input type="checkbox" class="main-grid-row-checkbox main-grid-checkbox" name="ID[]" value="<?=$data_id ?>" <?php  if ($arRow['editable'] !== false): ?> title="<?=getMessage('interface_grid_check') ?>" id="checkbox_<?=$arParams["GRID_ID"]?>_<?=$data_id ?>"<?php  endif ?> <?php  if (!$arResult['ALLOW_EDIT'] || $arRow['editable'] === false): ?> data-disabled="1" disabled<?php  endif ?>>
													<label class="main-grid-checkbox" for="checkbox_<?=$arParams["GRID_ID"]?>_<?=$data_id ?>"></label>
												</span>
											</td>
										<?php  endif ?>
										<?php  if ($arParams["SHOW_ROW_ACTIONS_MENU"] || $arParams["SHOW_GRID_SETTINGS_MENU"]) : ?>
											<td class="main-grid-cell main-grid-cell-action" rowspan="<?=count($arParams["ROW_LAYOUT"])?>">
												<?php  if (!empty($arRow["actions"]) && $arParams["SHOW_ROW_ACTIONS_MENU"]) : ?>
													<span class="main-grid-cell-content">
														<a href="#" class="main-grid-row-action-button" data-actions="<?=$actions?>"></a>
													</span>
												<?php  endif; ?>
											</td>
										<?php  endif; ?>

								<?php 
									foreach ($arParams["ROW_LAYOUT"] as $rowIndex => $rowLayout) :
										foreach ($rowLayout as $rowLayoutCellIndex => $rowLayoutCell) :
											$showedColumns[] = $rowLayoutCell["column"];
										endforeach;
									endforeach;

									$showedColumns = array_unique($showedColumns);

									$showedColumnsFromLayout = array();

									foreach ($arParams["ROW_LAYOUT"] as $rowIndex => $rowLayout) :
										foreach ($rowLayout as $rowLayoutCellIndex => $rowLayoutCell) :
											if (array_key_exists($rowLayoutCell["column"], $arResult["COLUMNS"]) && !isset($rowLayoutCell["rowspan"]))
											{
												$showedColumnsFromLayout[] = $rowLayoutCell["column"];
											}
										endforeach;
									endforeach;

								?>

								<?php  foreach ($arParams["ROW_LAYOUT"] as $rowIndex => $rowLayout) : ?>
									<?php  if ($rowIndex > 0) : ?>
										<tr class="main-grid-row main-grid-row-body<?=$arRow["not_count"] ? " main-grid-not-count" : ""?><?=$arRow["expand"] ? " main-grid-row-expand" : ""?><?=$arRow["draggable"] === false ? " main-grid-row-drag-disabled" : ""?><?=$collapseRow ? " main-grid-hide" : ""?>" data-child-loaded="<?=$arRow["expand"]?"true":"false"?>" data-depth="<?=htmlspecialcharsbx($arRow["depth"])?>" data-bind="<?=$data_id ?>"<?=$arParams["ENABLE_COLLAPSIBLE_ROWS"] ? " data-parent-id=\"".htmlspecialcharsbx($arRow["parent_id"])."\"" : ""?> <?php if(!empty($sDefAction["js"])):?> data-default-action="<?=Text\HtmlFilter::encode($sDefAction["js"])?>" title="<?=GetMessage("interface_grid_dblclick")?><?=$sDefAction["title"]?>"<?php endif;?><?=$arRow["attrs_string"]?>>
									<?php  endif; ?>
										<?php  foreach ($rowLayout as $rowLayoutCellIndex => $rowLayoutCell) :
											$header = $arResult["COLUMNS"][$rowLayoutCell["column"]];
											$preventDefault = $header["prevent_default"] ? "true" : "false";

											if (!(is_array($arRow["editable"]) && $arRow["editable"][$header["id"]] === false) && is_array($header["editable"]) && $arRow["editable"] !== false && is_array($arRow["data"]))
											{
												$header["editable"]["VALUE"] = $arRow["data"][$header["id"]];
											}
											else
											{
												$header["editable"] = false;
											}

											$className = "main-grid-cell";
											if($header['align'])
											{
												$className .= " main-grid-cell-{$header['align']}";
											}
											if(isset($rowClasses[$id]))
											{
												$className .= " {$rowClasses[$id]}";
											}

											if (count($arParams["ROW_LAYOUT"]) > 1 && $rowIndex < (count($arParams["ROW_LAYOUT"])-1) && !isset($rowLayoutCell["rowspan"]))
											{
												$className .= " main-grid-cell-no-border";
											}

											$isShift = $arParams["ENABLE_COLLAPSIBLE_ROWS"] && $arResult["HEADERS"][$header["id"]]["shift"] == true;
											$isWithButton = $arParams["ENABLE_COLLAPSIBLE_ROWS"] && $arRow["has_child"] == true && $isShift;

											$colspan = 0;

											if (isset($rowLayoutCell["colspan"]))
											{
												$colspan = min($rowLayoutCell["colspan"], count($showedColumnsFromLayout));
											}
										?>
											<?php  if (isset($rowLayoutCell["data"]) || array_key_exists($rowLayoutCell["column"], $arResult["COLUMNS"])) : ?>
												<td class="<?=$className?>"<?=$isShift ? " style=\"padding-left: ".($depth)."px\" data-shift=\"true\"" : ""?><?=$rowLayoutCell["rowspan"] ? " rowspan=\"".$rowLayoutCell["rowspan"]."\"" : ""?><?=$rowLayoutCell["colspan"] ? " colspan=\"".$colspan."\"" : ""?>>
													<span class="main-grid-cell-content" data-prevent-default="<?=$preventDefault?>">
														<?php  if ($isWithButton) : ?>
															<span class="main-grid-plus-button"></span>
														<?php  endif; ?>
														<?php 
															if (isset($rowLayoutCell["column"]) && isset($arRow["columns"][$rowLayoutCell["column"]]))
															{
																echo $arRow["columns"][$rowLayoutCell["column"]];
															}
															else if (isset($rowLayoutCell["data"]) && isset($arRow["data"][$rowLayoutCell["data"]]))
															{
																echo $arRow["data"][$rowLayoutCell["data"]];
															}
														?>
													</span>
												</td>
											<?php  endif; ?>
										<?php  endforeach; ?>

										<?php  if ($rowIndex === 0) : ?>
											<?php  foreach ($arResult['COLUMNS'] as $id => $header) : ?>
												<?php  if (!in_array($header["id"], $showedColumns)) : ?>
													<?php 
													$preventDefault = $header["prevent_default"] ? "true" : "false";
													$showedColumns[] = $rowLayoutCell["column"];

													if (!(is_array($arRow["editable"]) && $arRow["editable"][$header["id"]] === false) && is_array($header["editable"]) && $arRow["editable"] !== false && is_array($arRow["data"]))
													{
														$header["editable"]["VALUE"] = $arRow["data"][$header["id"]];
													}
													else
													{
														$header["editable"] = false;
													}

													$className = "main-grid-cell";
													if($header['align'])
													{
														$className .= " main-grid-cell-{$header['align']}";
													}
													if(isset($rowClasses[$id]))
													{
														$className .= " {$rowClasses[$id]}";
													}

													if (count($arParams["ROW_LAYOUT"]) > 1 && $rowIndex < (count($arParams["ROW_LAYOUT"])-1) && !isset($rowLayoutCell["rowspan"]))
													{
														$className .= " main-grid-cell-no-border";
													}

													$isShift = $arParams["ENABLE_COLLAPSIBLE_ROWS"] && $arResult["HEADERS"][$header["id"]]["shift"] == true;
													$isWithButton = $arParams["ENABLE_COLLAPSIBLE_ROWS"] && $arRow["has_child"] == true && $isShift;
													?>
													<td class="<?=$className?>"<?=$isShift ? " style=\"padding-left: ".($depth)."px\" data-shift=\"true\"" : ""?> rowspan="<?=count($arParams["ROW_LAYOUT"])?>">
														<span class="main-grid-cell-content" data-prevent-default="<?=$preventDefault?>">
															<?php  if ($isWithButton) : ?>
																<span class="main-grid-plus-button"></span>
															<?php  endif; ?>
															<?php 
																if (isset($arRow["columns"][$header["id"]]))
																{
																	echo $arRow["columns"][$header["id"]];
																}
																else if (isset($arRow["data"][$header["id"]]))
																{
																	echo $arRow["data"][$header["id"]];
																}
															?>
														</span>
													</td>

												<?php  endif; ?>
											<?php  endforeach; ?>
											<td class="main-grid-cell" rowspan="<?=count($arParams["ROW_LAYOUT"])?>"></td>
										<?php  endif; ?>
									</tr>
								<?php  endforeach; ?>

								<?php 
								else :
									$data_id = $arRow["id"];
									$actions = Text\HtmlFilter::encode(CUtil::PhpToJSObject($arRow["actions"]));
									$sDefAction = $arRow["default_action"];
									$depth = $arRow["depth"] > 0 ? 20*$arRow["depth"] : 0;
									$collapseRow = ($arParams["ENABLE_COLLAPSIBLE_ROWS"] && isset($arRow["parent_group_id"]) && $lastCollapseGroup === $arRow["parent_group_id"]);
								?><tr class="main-grid-row main-grid-row-body<?=$arRow["not_count"] ? " main-grid-not-count" : ""?><?=$arRow["expand"] ? " main-grid-row-expand" : ""?><?=$arRow["draggable"] === false ? " main-grid-row-drag-disabled" : ""?><?=$collapseRow ? " main-grid-hide" : ""?>" data-child-loaded="<?=$arRow["expand"]?"true":"false"?>" data-depth="<?=htmlspecialcharsbx($arRow["depth"])?>" data-id="<?=$data_id ?>"<?=$arParams["ENABLE_COLLAPSIBLE_ROWS"] ? " data-parent-id=\"".htmlspecialcharsbx($arRow["parent_id"])."\"" : ""?> <?php if(!empty($sDefAction["js"])):?> data-default-action="<?=Text\HtmlFilter::encode($sDefAction["js"])?>" title="<?=GetMessage("interface_grid_dblclick")?><?=$sDefAction["title"]?>"<?php endif;?><?=$arRow["attrs_string"]?>><?php 
									if ($arParams["ALLOW_ROWS_SORT"] && $arRow["draggable"] !== false) :
									?><th class="main-grid-cell main-grid-cell-drag"><?php 
										?><span class="main-grid-cell-content">&nbsp;</span><?php 
									?></th><?php 
									endif;
									if ($arParams["SHOW_ROW_CHECKBOXES"]): ?><?php 
										?><td class="main-grid-cell main-grid-cell-checkbox"><?php 
											?><span class="main-grid-cell-content"><?php 
												?><input type="checkbox" class="main-grid-row-checkbox main-grid-checkbox" name="ID[]" value="<?=$data_id ?>" <?php  if ($arRow['editable'] !== false): ?> title="<?=getMessage('interface_grid_check') ?>" id="checkbox_<?=$arParams["GRID_ID"]?>_<?=$data_id ?>"<?php  endif ?> <?php  if (!$arResult['ALLOW_EDIT'] || $arRow['editable'] === false): ?> data-disabled="1" disabled<?php  endif ?>><?php 
												?><label class="main-grid-checkbox" for="checkbox_<?=$arParams["GRID_ID"]?>_<?=$data_id ?>"></label><?php 
											?></span><?php 
										?></td><?php 
									endif ?><?php 
										if ($arParams["SHOW_ROW_ACTIONS_MENU"] || $arParams["SHOW_GRID_SETTINGS_MENU"]) :
											?><td class="main-grid-cell main-grid-cell-action"><?php 
												if (!empty($arRow["actions"]) && $arParams["SHOW_ROW_ACTIONS_MENU"]) : ?><?php 
													?><span class="main-grid-cell-content"><?php 
														?><a href="#" class="main-grid-row-action-button" data-actions="<?=$actions?>"></a><?php 
													?></span><?php 
												endif
											?></td><?php 

										endif; ?><?php 
											foreach ($arResult['COLUMNS'] as $id => $header):
												$preventDefault = $header["prevent_default"] ? "true" : "false";

												if (!(is_array($arRow["editable"]) && $arRow["editable"][$header["id"]] === false) && is_array($header["editable"]) && $arRow["editable"] !== false && is_array($arRow["data"]))
												{
													$header["editable"]["VALUE"] = $arRow["data"][$header["id"]];
												}
												else
												{
													$header["editable"] = false;
												}

												$className = "main-grid-cell";
												if($header['align'])
												{
													$className .= " main-grid-cell-{$header['align']}";
												}
												if(isset($rowClasses[$id]))
												{
													$className .= " {$rowClasses[$id]}";
												}

												$isShift = $arParams["ENABLE_COLLAPSIBLE_ROWS"] && $arResult["HEADERS"][$header["id"]]["shift"] == true;
												$isWithButton = $arParams["ENABLE_COLLAPSIBLE_ROWS"] && $arRow["has_child"] == true && $isShift;
												$editable = (!isset($arRow["editableColumns"][$id]) || (isset($arRow["editableColumns"][$id]) && $arRow["editableColumns"][$id] === true)) ? "true" : "false";
											?><td class="<?=$className?>"<?=$isShift ? " style=\"padding-left: ".($depth)."px\" data-shift=\"true\"" : ""?> data-editable="<?=$editable?>"><?php 
												?><span class="main-grid-cell-content" data-prevent-default="<?=$preventDefault?>"><?php 
													if ($isWithButton) :
													?><span class="main-grid-plus-button"></span><?php 
													endif;
													if($header["type"] == "checkbox" && ($arRow["columns"][$header["id"]] == 'Y' || $arRow["columns"][$header["id"]] == 'N'))
													{
														echo ($arRow["columns"][$header["id"]] == 'Y'? GetMessage("interface_grid_yes"):GetMessage("interface_grid_no"));
													}
													else
													{
														echo $arRow["columns"][$header["id"]];
													}
												?></span><?php 
											?></td><?php 
										endforeach ?><?php 
									?><td class="main-grid-cell"></td><?php 
								?></tr><?php 
							endif; ?>
						<?php  endforeach ?><?php 
						if (!empty($arResult['AGGREGATE'])): ?><?php 
						?><tr class="main-grid-row-foot main-grid-aggr-row" id="datarow_<?=$arParams["GRID_ID"]?>_bxaggr"><?php 
							if ($arResult['ALLOW_GROUP_ACTIONS']): ?><td class="main-grid-cell-foot"></td><?php  endif ?><?php 
								if ($arParams['ALLOW_ROW_ACTIONS']): ?><td class="main-grid-cell-foot"></td><?php  endif ?><?php 
									foreach ($arResult['COLUMNS'] as $id => $header): ?><?php 
										$isHidden = !array_key_exists($id, $arResult['COLUMNS']);
											?><td class="main-grid-cell-foot <?php  if ($header['align']) echo 'main-grid-cell-', $header['align']; ?>" <?php  if ($isHidden): ?> style="display: none; "<?php  endif ?>><?php 
													?><span class="main-grid-cell-content main-grid-cell-text-line"><?php 
														if (!$isHidden && !empty($arResult['AGGREGATE'][$id])): ?><?php 
															foreach ($arResult['AGGREGATE'][$id] as $item): ?><?php 
																?><?=$item; ?><br><?php 
															endforeach; ?><?php 
														endif; ?><?php 
													?></span><?php 
											?></td><?php 
									endforeach; ?><?php 
								?><td class="main-grid-cell-foot"></td><?php 
							?></tr><?php 
						endif ?><?php 
					endif ?><?php 
				?></tbody><?php 
			?></table><?php 
		?></div><?php 
	?></div><?php 
?></div><?php 
	?><div class="main-grid-bottom-panels" id="<?=$arParams["GRID_ID"]?>_bottom_panels"><?php 
		?><div class="main-grid-nav-panel"><?php 
			?><div class="main-grid-more" id="<?=$arParams["GRID_ID"]?>_nav_more"><?php 
				?><a href="<?=$arResult["NEXT_PAGE_URL"]?>" class="main-grid-more-btn" data-slider-ignore-autobinding="true" <?php  if (!$arResult["SHOW_MORE_BUTTON"] || !$arParams["SHOW_MORE_BUTTON"] || !count($arResult["ROWS"])): ?>style="display: none; "<?php  endif ?>><?php 
					?><span class="main-grid-more-text"><?=getMessage('interface_grid_nav_more') ?></span><?php 
					?><span class="main-grid-more-load-text"><?=getMessage('interface_grid_load') ?></span><?php 
					?><span class="main-grid-more-icon"></span><?php 
				?></a><?php 
			?></div><?php 
		if ($arParams["SHOW_NAVIGATION_PANEL"]) : ?><?php 
			?><div class="main-grid-panel-wrap"><?php 
				?><table class="main-grid-panel-table"><?php 
					?><tr><?php 
						if ($arParams["SHOW_SELECTED_COUNTER"]) : ?><?php 
							?><td class="main-grid-panel-cell main-grid-panel-counter main-grid-cell-left"><?php 
								?><div class="main-grid-panel-content main-grid-panel-counter-for-selected"><?php 
									?><span class="main-grid-panel-content-title"><?=getMessage('interface_grid_checked') ?></span>&nbsp;<?php 
									?><span class="main-grid-panel-content-text"><?php 
										?><span class="main-grid-counter-selected">0</span><?php 
										?>&nbsp;/&nbsp;<?php 
										?><span class="main-grid-counter-displayed"><?=$displayedCount?></span><?php 
									?></span><?php 
								?></div><?php 
								?><div class="main-grid-panel-content main-grid-panel-counter-for-all"><?php 
									?><span class="main-grid-panel-content-text"><?=Loc::getMessage("interface_grid_all_selected")?></span><?php 
								?></div><?php 
							?></td><?php 
							endif; ?><?php 
							if ($arParams["SHOW_TOTAL_COUNTER"] && (isset($arResult["TOTAL_ROWS_COUNT"]) || !empty($arParams["TOTAL_ROWS_COUNT_HTML"]))) : ?><?php 
							?><td class="main-grid-panel-total main-grid-panel-cell main-grid-cell-left"><?php 
								?><div class="main-grid-panel-content"><?php 
									if (empty($arParams["TOTAL_ROWS_COUNT_HTML"])) : ?><?php 
										?><span class="main-grid-panel-content-title"><?=GetMessage("interface_grid_total")?>:</span><?php 
										?>&nbsp;<span class="main-grid-panel-content-text"><?=count($arResult["ROWS"]) ? $arResult["TOTAL_ROWS_COUNT"] : 0?></span><?php 
									else : ?><?php 
										?><?=Text\HtmlConverter::getHtmlConverter()->decode($arParams["TOTAL_ROWS_COUNT_HTML"])?><?php 
									endif; ?><?php 
								?></div><?php 
							?></td><?php 
						endif; ?><?php 
						?><td class="main-grid-panel-cell main-grid-panel-cell-pagination main-grid-cell-left"><?php 
							if ($arParams["SHOW_PAGINATION"]) : ?><?php 
								?><?=Bitrix\Main\Text\Converter::getHtmlConverter()->decode($arResult["NAV_STRING"]);?><?php 
							endif; ?><?php 
						?></td><?php 
						?><td class="main-grid-panel-cell main-grid-panel-limit main-grid-cell-right"><?php 
							if ($arParams["SHOW_PAGESIZE"] && is_array($arParams["PAGE_SIZES"]) && count($arParams["PAGE_SIZES"]) > 0) :
									$pageSize = $arResult['OPTIONS']['views'][$arResult['OPTIONS']['current_view']]['page_size'] ?: $arParams["DEFAULT_PAGE_SIZE"]; ?><?php 
								?><span class="main-grid-panel-content"><?php 
									?><span class="main-grid-panel-content-title"><?=getMessage('interface_grid_page_size') ?></span> <?php 
										?><span class="main-dropdown main-grid-popup-control main-grid-panel-select-pagesize" id="<?=$arParams["GRID_ID"]?>_grid_page_size" data-value="<?=$pageSize;?>" data-items="<?=$arResult["PAGE_SIZES_JSON"]?>">
											<span class="main-dropdown-inner"> <?=$pageSize; ?></span><?php 
										?></span><?php 
									?></span><?php 
								endif; ?><?php 
							?></td><?php 
						?></tr><?php 
					?></table><?php 
				?></div><?php 
			endif; ?><?php 
		?></div>
		<?php  if ($arParams["SHOW_ACTION_PANEL"] && isset($arParams["ACTION_PANEL"]) && !empty($arParams["ACTION_PANEL"]) && is_array($arParams["ACTION_PANEL"]["GROUPS"])) : ?><?php 
			?><div class="main-grid-action-panel main-grid-disable"><?php 
				?><div class="main-grid-control-panel-wrap"><?php 
					?><table class="main-grid-control-panel-table"><?php 
						?><tr class="main-grid-control-panel-row"><?php 
							foreach ($arParams["ACTION_PANEL"]["GROUPS"] as $groupKey => $group) : ?><?php 
								?><td class="main-grid-control-panel-cell<?=$group["CLASS"] ? " ".$group["CLASS"] : "" ?>"><?php 
									foreach ($group["ITEMS"] as $itemKey => $item) : ?><?php 
										if ($item["TYPE"] === "CHECKBOX") :
											?><span class="main-grid-panel-control-container<?=$item["DISABLED"] ? " main-grid-disable" : "";?>" id="<?=Text\HtmlFilter::encode($item["ID"])?>"><?php 
												if ($item["NAME"] === Grid\Panel\DefaultValue::FOR_ALL_CHECKBOX_NAME) : ?><?php 
													?><span class="main-grid-checkbox-container main-grid-control-panel-checkbox-container"><?php 
															?><input class="main-grid-panel-checkbox main-grid-checkbox main-grid-panel-control <?=$item["CLASS"]?>" id="<?=Text\HtmlFilter::encode($item["ID"])?><?=$arParams["GRID_ID"]?>" name="<?=Text\HtmlFilter::encode($item["NAME"])?><?=$arParams["GRID_ID"]?>" type="checkbox" value="<?=Text\HtmlFilter::encode($item["VALUE"])?>" title="<?=Text\HtmlFilter::encode($item["TITLE"])?>" data-onchange="<?=Text\HtmlFilter::encode(CUtil::PhpToJSObject($item["ONCHANGE"]))?>"<?=$item["CHECKED"] ? " checked" : ""?>> <?php 
															?> <label class="main-grid-checkbox" for="<?=Text\HtmlFilter::encode($item["ID"])?><?=$arParams["GRID_ID"]?>"></label><?php 
													?></span><?php 
													?><span class="main-grid-control-panel-content-title"><?php 
														?> <label for="<?=Text\HtmlFilter::encode($item["ID"])?><?=$arParams["GRID_ID"]?>" title="<?=Loc::getMessage("interface_grid_for_all")?>"><?=Loc::getMessage("interface_grid_for_all_box")?></label><?php 
													?></span><?php 
												else : ?><?php 
													?><span class="main-grid-checkbox-container main-grid-control-panel-checkbox-container"><?php 
														?><input class="main-grid-panel-checkbox main-grid-checkbox main-grid-panel-control" id="<?=Text\HtmlFilter::encode($item["ID"])?>_control" name="<?=Text\HtmlFilter::encode($item["NAME"])?>" type="checkbox" value="<?=Text\HtmlFilter::encode($item["VALUE"])?>" title="<?=Text\HtmlFilter::encode($item["TITLE"])?>" data-onchange="<?=Text\HtmlFilter::encode(CUtil::PhpToJSObject($item["ONCHANGE"]))?>"<?=$item["CHECKED"] ? " checked" : ""?>><?php 
														?> <label class="main-grid-checkbox" for="<?=Text\HtmlFilter::encode($item["ID"])?>_control"></label><?php 
													?></span><?php 
													?><span class="main-grid-control-panel-content-title"><?php 
														?> <label for="<?=Text\HtmlFilter::encode($item["ID"])?>_control" title="<?=Text\HtmlFilter::encode($item["TITLE"])?>"><?=Text\HtmlFilter::encode($item["LABEL"])?></label><?php 
													?></span><?php 
												endif;
											?></span><?php 
										endif; ?><?php 
										if ($item["TYPE"] === "DROPDOWN") :
											?><span class="main-grid-panel-control-container<?=$item["DISABLED"] ? " main-grid-disable" : "";?>" id="<?=Text\HtmlFilter::encode($item["ID"])?>"><?php 
												?><span class="main-dropdown main-grid-panel-control" data-popup-position="fixed" id="<?=Text\HtmlFilter::encode($item["ID"])?>_control" data-name="<?=Text\HtmlFilter::encode($item["NAME"])?>" data-value="<?=Text\HtmlFilter::encode(CUtil::PhpToJSObject($item["ITEMS"][0]["VALUE"]))?>" data-items="<?=Text\HtmlFilter::encode(CUtil::PhpToJSObject($item["ITEMS"]))?>"><?php 
													?><span class="main-dropdown-inner"><?=$item["ITEMS"][0]["NAME"]?></span><?php 
												?></span><?php 
											?></span><?php 
										endif; ?><?php 
										if ($item["TYPE"] === "CUSTOM") : ?><?php 
											?><span class="main-grid-panel-control-container<?=$item["DISABLED"] ? " main-grid-disable" : "";?>" id="<?=Text\HtmlFilter::encode($item["ID"])?>">
												<div class="main-grid-panel-custom">
													<?=$item["VALUE"]?>
												</div>
											</span><?php 
										endif; ?><?php 
										if ($item["TYPE"] === "TEXT") : ?><?php 
										?><span class="main-grid-panel-control-container<?=$item["DISABLED"] ? " main-grid-disable" : "";?>" id="<?=Text\HtmlFilter::encode($item["ID"])?>"><?php 
											if ($item["LABEL"]) : ?><?php 
												?><label for="<?=Text\HtmlFilter::encode($item["ID"])?>_control"><?=Text\HtmlFilter::encode($item["LABEL"])?></label><?php 
											endif;
											?> <input type="text" class="main-grid-control-panel-input-text main-grid-panel-control" name="<?=Text\HtmlFilter::encode($item["NAME"])?>" id="<?=Text\HtmlFilter::encode($item["ID"])?>_control" placeholder="<?=Text\HtmlFilter::encode($item["PLACEHOLDER"])?>" value="<?=Text\HtmlFilter::encode($item["VALUE"])?>" title="<?=Text\HtmlFilter::encode($item["TITLE"])?>"><?php 
										?></span><?php 
										endif; ?><?php 
										if ($item["TYPE"] === "BUTTON") : ?><?php 
										?><span class="main-grid-panel-control-container<?=$item["DISABLED"] ? " main-grid-disable" : "";?>" id="<?=Text\HtmlFilter::encode($item["ID"])?>"><?php 
											?><span class="main-grid-buttons <?=Text\HtmlFilter::encode($item["CLASS"])?>" data-name="<?=Text\HtmlFilter::encode($item["NAME"])?>" data-value="<?=Text\HtmlFilter::encode($item["VALUE"])?>" id="<?=Text\HtmlFilter::encode($item["ID"])?>_control" data-onchange="<?=Text\HtmlFilter::encode(CUtil::PhpToJSObject($item["ONCHANGE"]))?>" title="<?=Text\HtmlFilter::encode($item["TITLE"])?>"><?php 
												?><?=$item["TEXT"]
											?></span><?php 
										?></span><?php 
										endif; ?><?php 
										if ($item["TYPE"] === "LINK") :
											?><span class="main-grid-panel-control-container<?=$item["DISABLED"] ? " main-grid-disable" : "";?>" id="<?=Text\HtmlFilter::encode($item["ID"])?>" data-onchange="<?=Text\HtmlFilter::encode(CUtil::PhpToJSObject($item["ONCHANGE"]))?>" title="<?=Text\HtmlFilter::encode($item["TITLE"])?>"><?php 
												?><a href="<?=Text\HtmlFilter::encode($item["HREF"])?>" class="main-grid-link<?=$item["CLASS"] ? " ".Text\HtmlFilter::encode($item["CLASS"]) : ""?>" id="<?=Text\HtmlFilter::encode($item["ID"])?>_control"><?=$item["TEXT"]?></a><?php 
											?></span><?php 
										endif; ?><?php 
										if ($item["TYPE"] === "DATE") :
										?><span class="main-grid-panel-control-container<?=$item["DISABLED"] ? " main-grid-disable" : "";?>" id="<?=Text\HtmlFilter::encode($item["ID"])?>" data-onchange="<?=Text\HtmlFilter::encode(CUtil::PhpToJSObject($item["ONCHANGE"]))?>" title="<?=Text\HtmlFilter::encode($item["TITLE"])?>"><?php 
											?><span class="main-ui-control main-ui-date main-grid-panel-date"><?php 
												?><span class="main-ui-date-button"></span><?php 
												?><input type="text" name="<?=$item["TYPE"]?>" tabindex="1" autocomplete="off" data-time="<?=$item["TIME"] ? "true" : "false"?>" class="main-ui-control-input main-ui-date-input" value="<?=$item["VALUE"]?>" placeholder="<?=$item["PLACEHOLDER"]?>"><?php 
												?><div class="main-ui-control-value-delete<?=empty($item["VALUE"]) ? " main-ui-hide" : ""?>"><?php 
													?><span class="main-ui-control-value-delete-item"></span><?php 
												?></div><?php 
											?></span><?php 
										?></span><?php 
										endif; ?><?php 
									endforeach; ?><?php 
								?></td><?php 
							endforeach; ?><?php 
							if ($arParams["SHOW_SELECTED_COUNTER"]) : ?><?php 
								?><td class="main-grid-panel-cell main-grid-panel-counter main-grid-cell-left"><?php 
									?><div class="main-grid-panel-content main-grid-panel-counter-for-selected"><?php 
										?><span class="main-grid-panel-content-title"><?=getMessage('interface_grid_checked') ?></span>&nbsp;<?php 
										?><span class="main-grid-panel-content-text"><?php 
											?><span class="main-grid-counter-selected">0</span><?php 
											?>&nbsp;/&nbsp;<?php 
											?><span class="main-grid-counter-displayed"><?=count($arResult["ROWS"])?></span><?php 
										?></span><?php 
									?></div><?php 
									?><div class="main-grid-panel-content main-grid-panel-counter-for-all"><?php 
										?><span class="main-grid-panel-content-text"><?=Loc::getMessage("interface_grid_all_selected")?></span><?php 
									?></div><?php 
								?></td><?php 
							endif; ?><?php 
						?></tr><?php 
					?></table><?php 
				?></div><?php 
			?></div><?php 
		endif; ?><?php 
	?></div><?php 
?></form><?php 
?><iframe height="0" width="100%" id="main-grid-tmp-frame-<?=$arParams["GRID_ID"]?>" name="main-grid-tmp-frame-<?=$arParams["GRID_ID"]?>" style="position: absolute; z-index: -1; opacity: 0; border: 0;"></iframe><?php 
?></div>

<?php 
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if (\Bitrix\Main\Grid\Context::isInternalRequest()) :
?><script>
	(function() {
		var action = '<?=\CUtil::JSEscape($request->get("grid_action"))?>';
		var editableData = eval(<?=CUtil::phpToJSObject($arResult["DATA_FOR_EDIT"])?>);
		var defaultColumns = eval(<?=CUtil::phpToJSObject($arResult["DEFAULT_COLUMNS"])?>);
		var Grid = BX.Main.gridManager.getById('<?=\CUtil::JSEscape($arParams["GRID_ID"])?>');
		var messages = eval(<?=CUtil::phpToJSObject($arResult["MESSAGES"])?>);

		Grid = Grid ? Grid.instance : null;

		if (Grid)
		{
			Grid.arParams.DEFAULT_COLUMNS = defaultColumns;
			Grid.arParams.MESSAGES = messages;

			if (action !== 'more')
			{
				Grid.arParams.EDITABLE_DATA = editableData;
			}
			else
			{
				var editableDataKeys = Object.keys(editableData);
				editableDataKeys.forEach(function(key) {
					Grid.arParams.EDITABLE_DATA[key] = editableData[key];
				});
			}

			BX.onCustomEvent(window, 'BX.Main.grid:paramsUpdated', []);
		}
	})();
</script><?php 
endif; ?>

<?php  if (!$arResult['IS_AJAX'] || !$arResult['IS_INTERNAL']) : ?><?php 
?><script>
		BX(function() { BX.Main.dropdownManager.init(); });
		BX(function() {

			<?php  if(isset($arParams['TOP_ACTION_PANEL_RENDER_TO'])): ?>
				var actionPanel = new BX.UI.ActionPanel({
					params: {
						gridId: '<?=\CUtil::jsEscape($arParams['GRID_ID']) ?>'
					},
					pinnedMode: <?=\CUtil::phpToJsObject($arParams['TOP_ACTION_PANEL_PINNED_MODE']) ?>,
					renderTo: document.querySelector('<?=\CUtil::jsEscape($arParams['TOP_ACTION_PANEL_RENDER_TO']) ?>'),
					className: '<?=\CUtil::jsEscape($arParams['TOP_ACTION_PANEL_CLASS']) ?>',
					groupActions: <?=\Bitrix\Main\Web\Json::encode($arParams['ACTION_PANEL']) ?>
				});
				actionPanel.draw();
			<?php  endif; ?>

			BX.Main.gridManager.push(
				'<?=\CUtil::jSEscape($arParams["GRID_ID"])?>',
				new BX.Main.grid(
					'<?=\CUtil::jSEscape($arParams["GRID_ID"])?>',
					<?=CUtil::PhpToJSObject(
						array(
							"ALLOW_COLUMNS_SORT" => $arParams["ALLOW_COLUMNS_SORT"],
							"ALLOW_ROWS_SORT" => $arParams["ALLOW_ROWS_SORT"],
							"ALLOW_COLUMNS_RESIZE" => $arParams["ALLOW_COLUMNS_RESIZE"],
							"SHOW_ROW_CHECKBOXES" => $arParams["SHOW_ROW_CHECKBOXES"],
							"ALLOW_HORIZONTAL_SCROLL" => $arParams["ALLOW_HORIZONTAL_SCROLL"],
							"ALLOW_PIN_HEADER" => $arParams["ALLOW_PIN_HEADER"],
							"SHOW_ACTION_PANEL" => $arParams["SHOW_ACTION_PANEL"],
							"PRESERVE_HISTORY" => $arParams["PRESERVE_HISTORY"],
							"BACKEND_URL" => $arResult["BACKEND_URL"],
							"ALLOW_CONTEXT_MENU" => $arResult["ALLOW_CONTEXT_MENU"],
							"DEFAULT_COLUMNS" => $arResult["DEFAULT_COLUMNS"],
							"ENABLE_COLLAPSIBLE_ROWS" => $arParams["ENABLE_COLLAPSIBLE_ROWS"],
							"EDITABLE_DATA" => $arResult["DATA_FOR_EDIT"],
							"SETTINGS_TITLE" => Loc::getMessage("interface_grid_settings_title"),
							"APPLY_SETTINGS" => Loc::getMessage("interface_grid_apply_settings"),
							"CANCEL_SETTINGS" => Loc::getMessage("interface_grid_cancel_settings"),
							"CONFIRM_APPLY" => Loc::getMessage("interface_grid_confirm_apply"),
							"CONFIRM_CANCEL" => Loc::getMessage("interface_grid_confirm_cancel"),
							"CONFIRM_MESSAGE" => Loc::getMessage("interface_grid_confirm_message"),
							"CONFIRM_FOR_ALL_MESSAGE" => Loc::getMessage("interface_grid_confirm_for_all_message"),
							"CONFIRM_RESET_MESSAGE" => Loc::getMessage("interface_grid_settings_confirm_message"),
							"RESET_DEFAULT" => Loc::getMessage("interface_grid_restore_to_default"),
							"SETTINGS_FOR_ALL_LABEL" => Loc::getMessage("interface_grid_settings_for_all_label"),
							"SETTINGS_FOR_ALL_CONFIRM_MESSAGE" => Loc::getMessage("interface_grid_settings_for_all_confirm_message"),
							"SETTINGS_FOR_ALL_CONFIRM_APPLY" => Loc::getMessage("interface_grid_settings_for_all_apply"),
							"SETTINGS_FOR_ALL_CONFIRM_CANCEL" => Loc::getMessage("interface_grid_settings_for_all_cancel"),
							"MAIN_UI_GRID_IMAGE_EDITOR_BUTTON_EDIT" => Loc::getMessage("interface_grid_image_editor_button_edit"),
							"MAIN_UI_GRID_IMAGE_EDITOR_BUTTON_REMOVE" => Loc::getMessage("interface_grid_image_editor_button_remove"),
							"CLOSE" => Loc::getMessage("interface_grid_settings_close"),
							"IS_ADMIN" => $USER->CanDoOperation("edit_other_settings"),
							"MESSAGES" => $arResult["MESSAGES"],
							"LAZY_LOAD" => $arResult["LAZY_LOAD"],
							"ALLOW_VALIDATE" => $arParams["ALLOW_VALIDATE"],
							"HANDLE_RESPONSE_ERRORS" => $arResult["HANDLE_RESPONSE_ERRORS"],
                            "ALLOW_STICKED_COLUMNS" => $arParams["ALLOW_STICKED_COLUMNS"],
                            "CHECKBOX_COLUMN_ENABLED" => $arParams["SHOW_ROW_CHECKBOXES"],
                            "ACTION_COLUMN_ENABLED" => ($arParams["SHOW_ROW_ACTIONS_MENU"] || $arParams["SHOW_GRID_SETTINGS_MENU"]),
						)
					)?>,
					<?=CUtil::PhpToJSObject($arResult["OPTIONS"])?>,
					<?=CUtil::PhpToJSObject($arResult["OPTIONS_ACTIONS"])?>,
					'<?=$arResult["OPTIONS_HANDLER_URL"]?>',
					<?=CUtil::PhpToJSObject($arResult["PANEL_ACTIONS"])?>,
					<?=CUtil::PhpToJSObject($arResult["PANEL_TYPES"])?>,
					<?=CUtil::PhpToJSObject($arResult["EDITOR_TYPES"])?>,
					<?=CUtil::PhpToJSObject($arResult["MESSAGE_TYPES"])?>
				)
			);
		});
	</script>
<?php  endif; ?>
