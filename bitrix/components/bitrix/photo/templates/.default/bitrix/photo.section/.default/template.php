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
?>
<div class="photo-section">
<?php if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?php endif;?>
<table cellpadding="0" cellspacing="0" border="0" class="data-table">
	<?php foreach($arResult["ROWS"] as $arItems):?>
		<tr class="head-row" valign="top">
		<?php foreach($arItems as $arItem):?>
			<?php if(is_array($arItem)):?>
				<?php 
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BPS_ELEMENT_DELETE_CONFIRM')));
				?>
				<td width="<?=$arResult["TD_WIDTH"]?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					&nbsp;
					<?php if($arResult["USER_HAVE_ACCESS"]):?>
						<?php if(is_array($arItem["PICTURE"])):?>
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img
									border="0"
									src="<?=$arItem["PICTURE"]["SRC"]?>"
									width="<?=$arItem["PICTURE"]["WIDTH"]?>"
									height="<?=$arItem["PICTURE"]["HEIGHT"]?>"
									alt="<?=$arItem["PICTURE"]["ALT"]?>"
									title="<?=$arItem["PICTURE"]["TITLE"]?>"
									/></a><br />
						<?php endif?>
					<?php else:?>
						<?php if(is_array($arItem["PICTURE"])):?>
							<img
								border="0"
								src="<?=$arItem["PICTURE"]["SRC"]?>"
								width="<?=$arItem["PICTURE"]["WIDTH"]?>"
								height="<?=$arItem["PICTURE"]["HEIGHT"]?>"
								alt="<?=$arItem["PICTURE"]["ALT"]?>"
								title="<?=$arItem["PICTURE"]["TITLE"]?>"
								/><br />
						<?php endif?>
					<?php endif?>
				</td>
			<?php else:?>
				<td width="<?=$arResult["TD_WIDTH"]?>" rowspan="<?=$arResult["nRowsPerItem"]?>">
					&nbsp;
				</td>
			<?php endif;?>
		<?php endforeach?>
		</tr>
		<tr class="data-row">
		<?php foreach($arItems as $arItem):?>
			<?php if(is_array($arItem)):?>
				<th valign="top" width="<?=$arResult["TD_WIDTH"]?>" class="data-cell">
					&nbsp;
					<?php if($arResult["USER_HAVE_ACCESS"]):?>
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?><?php if($arParams["USE_RATING"] && $arItem["PROPERTIES"]["rating"]["VALUE"]) echo "(".$arItem["PROPERTIES"]["rating"]["VALUE"].")"?></a><br />
					<?php else:?>
						<?=$arItem["NAME"]?><?php if($arParams["USE_RATING"] && $arItem["PROPERTIES"]["rating"]["VALUE"]) echo "(".$arItem["PROPERTIES"]["rating"]["VALUE"].")"?><br />
					<?php endif?>
				</th>
			<?php endif;?>
		<?php endforeach?>
		</tr>
		<?php if($arResult["bDisplayFields"]):?>
		<tr class="data-row">
		<?php foreach($arItems as $arItem):?>
			<?php if(is_array($arItem)):?>
				<th valign="top" width="<?=$arResult["TD_WIDTH"]?>" class="data-cell">
					<?php foreach($arParams["FIELD_CODE"] as $code):?>
						<small><?=GetMessage("IBLOCK_FIELD_".$code)?>&nbsp;:&nbsp;<?=$arItem[$code]?></small><br />
					<?php endforeach?>
					<?php foreach($arItem["DISPLAY_PROPERTIES"] as $arProperty):?>
						<small><?=$arProperty["NAME"]?>:&nbsp;<?php 
							if(is_array($arProperty["DISPLAY_VALUE"]))
								echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
							else
								echo $arProperty["DISPLAY_VALUE"];?></small><br />
					<?php endforeach?>
				</th>
			<?php endif;?>
		<?php endforeach?>
		</tr>
		<?php endif;?>
	<?php endforeach?>
</table>
<?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?php endif;?>
</div>
