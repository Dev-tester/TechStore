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

$themeClass = isset($arParams['TEMPLATE_THEME']) ? ' bx-'.$arParams['TEMPLATE_THEME'] : '';

$this->setFrameMode(true);
?>
<div class="mb-3 catalog-filter<?=$themeClass?>">
	<form name="<?php echo $arResult["FILTER_NAME"]."_form"?>" action="<?php echo $arResult["FORM_ACTION"]?>" method="get">
		<div class="container-fluid">
			<div class="mb-3 row">
				<div class="col bx-filter-title"><?=GetMessage("CT_BCF_FILTER_TITLE")?></div>
			</div>
			<div class="mb-3 row">
				<?php foreach($arResult["ITEMS"] as $arItem):?>
					<?php if(array_key_exists("HIDDEN", $arItem)):?>
						<?=$arItem["INPUT"]?>

					<?php elseif ($arItem["TYPE"] == "RANGE"):?>
						<div class="f1 mb-2 col-sm-6 col-md-4 catalog-filter-block">
							<div class="mb-1 catalog-filter-block-title"><?=$arItem["NAME"]?></div>
							<div class="catalog-filter-block-body d-flex">
								<div class="flex-6">
									<input
										class="form-control"
										type="text"
										value="<?=$arItem["INPUT_VALUES"][0]?>"
										name="<?=$arItem["INPUT_NAMES"][0]?>"
										placeholder="<?=GetMessage("CT_BCF_FROM")?>"
									/>
								</div>
								<div class="catalog-filter-field-separator"></div>
								<div class="flex-6">
									<input
										class="form-control"
										type="text"
										value="<?=$arItem["INPUT_VALUES"][1]?>"
										name="<?=$arItem["INPUT_NAMES"][1]?>"
										placeholder="<?=GetMessage("CT_BCF_TO")?>"
									/>
								</div>
							</div>
						</div>

					<?php elseif ($arItem["TYPE"] == "DATE_RANGE"):?>
						<div class="f2 mb-2 col-sm-6 col-md-4 catalog-filter-block">
							<div class="mb-1 catalog-filter-block-title"><?=$arItem["NAME"]?>></div>
							<div class="catalog-filter-block-body">
								<div class="col-6">
									<?php $APPLICATION->IncludeComponent(
										'bitrix:main.calendar',
										'',
										array(
											'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
											'SHOW_INPUT' => 'Y',
											'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]).'"',
											'INPUT_NAME' => $arItem["INPUT_NAMES"][0],
											'INPUT_VALUE' => $arItem["INPUT_VALUES"][0],
											'SHOW_TIME' => 'N',
											'HIDE_TIMEBAR' => 'Y',
										),
										null,
										array('HIDE_ICONS' => 'Y')
									);?>
								</div>
								<div class="col-6">
									<?php $APPLICATION->IncludeComponent(
										'bitrix:main.calendar',
										'',
										array(
											'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
											'SHOW_INPUT' => 'Y',
											'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]).'"',
											'INPUT_NAME' => $arItem["INPUT_NAMES"][1],
											'INPUT_VALUE' => $arItem["INPUT_VALUES"][1],
											'SHOW_TIME' => 'N',
											'HIDE_TIMEBAR' => 'Y',
										),
										null,
										array('HIDE_ICONS' => 'Y')
									);?>
								</div>
							</div>
						</div>

					<?php elseif ($arItem["TYPE"] == "SELECT"):?>
						<div class="f3 mb-2 col-sm-6 col-md-4 catalog-filter-block">
							<div class="mb-1 catalog-filter-block-title"><?=$arItem["NAME"]?></div>
							<div class="catalog-filter-block-body">
								<select name="<?=$arItem["INPUT_NAME"].($arItem["MULTIPLE"] == "Y" ? "[]" : "")?>">
									<?php foreach ($arItem["LIST"] as $key => $value):?>
										<option
											value="<?=htmlspecialcharsBx($key)?>"
											<?php if ($key == $arItem["INPUT_VALUE"]) echo 'selected="selected"'?>
										><?=htmlspecialcharsEx($value)?></option>
									<?php endforeach?>
								</select>
							</div>
						</div>

					<?php elseif ($arItem["TYPE"] == "CHECKBOX"):?>
						<div class="f4 mb-2 col-sm-6 col-md-4 catalog-filter-block">
							<div class="mb-1 catalog-filter-block-title"><?=$arItem["NAME"]?></div>
							<div class="catalog-filter-block-body">
								<?php  $arListValue = (is_array($arItem["~INPUT_VALUE"]) ? $arItem["~INPUT_VALUE"] : array($arItem["~INPUT_VALUE"]));
								foreach ($arItem["LIST"] as $key => $value):?>
									<div class="form-check">
										<input
											type="checkbox"
											class="form-check-input"
											value="<?=htmlspecialcharsBx($key)?>"
											name="<?php echo $arItem["INPUT_NAME"]?>[]"
											<?php if (in_array($key, $arListValue)) echo 'checked="checked"'?>
										>
										<label class="form-check-label" for="<?php echo $arItem["INPUT_NAME"]?>">?=htmlspecialcharsEx($value)?></label>
									</div>
								<?php endforeach?>
							</div>
						</div>

					<?php elseif ($arItem["TYPE"] == "RADIO"):?>
						<div class="f5 mb-2 col-sm-6 col-md-4 catalog-filter-block">
							<div class="mb-1 catalog-filter-block-title"><?=$arItem["NAME"]?></div>
							<div class="catalog-filter-block-body">
								<?php  $arListValue = (is_array($arItem["~INPUT_VALUE"]) ? $arItem["~INPUT_VALUE"] : array($arItem["~INPUT_VALUE"]));
								foreach ($arItem["LIST"] as $key => $value):?>
									<div class="form-check">
										<input
											type="radio"
											class="form-check-input"
											value="<?=htmlspecialcharsBx($key)?>"
											name="<?php echo $arItem["INPUT_NAME"]?>"
											<?php if (in_array($key, $arListValue)) echo 'checked="checked"'?>
										>
										<label class="form-check-label" for="<?php echo $arItem["INPUT_NAME"]?>"><?=htmlspecialcharsEx($value)?></label>
									</div>
								<?php endforeach?>
							</div>
						</div>

					<?php else:?>
						<div class="f6 mb-2 col-sm-6 col-md-4 catalog-filter-block">
							<div class="mb-1 catalog-filter-block-title"><?=$arItem["NAME"]?></div>
							<div class="catalog-filter-block-body"><?=$arItem["INPUT"]?></div>
						</div>
					<?php endif?>
				<?php endforeach;?>
			</div>
			<div class="row">
				<div class="col">
					<input type="submit" name="set_filter" value="<?=GetMessage("CT_BCF_SET_FILTER")?>" class="btn btn-primary" />
					<input type="hidden" name="set_filter" value="Y" />
					<input type="submit" name="del_filter" value="<?=GetMessage("CT_BCF_DEL_FILTER")?>" class="btn btn-link" />
				</div>
			</div>
		</div>
	</form>
</div>