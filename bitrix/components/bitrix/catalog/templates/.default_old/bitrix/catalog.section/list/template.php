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
<div class="catalog-section">
<?php if($arParams["DISPLAY_TOP_PAGER"]):?>
	<p><?=$arResult["NAV_STRING"]?></p>
<?php endif?>
<table class="data-table" cellspacing="0" cellpadding="0" border="0" width="100%">
	<thead>
	<tr>
		<td><?=GetMessage("CATALOG_TITLE")?></td>
		<?php if(count($arResult["ITEMS"]) > 0):
			foreach($arResult["ITEMS"][0]["DISPLAY_PROPERTIES"] as $arProperty):?>
				<td><?=$arProperty["NAME"]?></td>
			<?php endforeach;
		endif;?>
		<?php foreach($arResult["PRICES"] as $code=>$arPrice):?>
			<td><?=$arPrice["TITLE"]?></td>
		<?php endforeach?>
		<?php if(count($arResult["PRICES"]) > 0):?>
			<td>&nbsp;</td>
		<?php endif?>
	</tr>
	</thead>
	<?php foreach($arResult["ITEMS"] as $arElement):?>
	<?php 
	$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
	?>
	<tr id="<?=$this->GetEditAreaId($arElement['ID']);?>">
		<td>
			<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a>
			<?php if(count($arElement["SECTION"]["PATH"])>0):?>
				<br />
				<?php foreach($arElement["SECTION"]["PATH"] as $arPath):?>
					/ <a href="<?=$arPath["SECTION_PAGE_URL"]?>"><?=$arPath["NAME"]?></a>
				<?php endforeach?>
			<?php endif?>
		</td>
		<?php foreach($arElement["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
		<td>
			<?php if(is_array($arProperty["DISPLAY_VALUE"]))
				echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
			elseif($arProperty["DISPLAY_VALUE"] === false)
				echo "&nbsp;";
			else
				echo $arProperty["DISPLAY_VALUE"];?>
		</td>
		<?php endforeach?>
		<?php foreach($arResult["PRICES"] as $code=>$arPrice):?>
		<td>
			<?php if($arPrice = $arElement["PRICES"][$code]):?>
				<?php if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
					<s><?=$arPrice["PRINT_VALUE"]?></s><br /><span class="catalog-price"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></span>
				<?php else:?>
					<span class="catalog-price"><?=$arPrice["PRINT_VALUE"]?></span>
				<?php endif?>
			<?php else:?>
				&nbsp;
			<?php endif;?>
		</td>
		<?php endforeach;?>
		<?php if(count($arResult["PRICES"]) > 0):?>
		<td>
			<?php if($arElement["CAN_BUY"]):?>
				<noindex>
				<a href="<?php echo $arElement["BUY_URL"]?>" rel="nofollow"><?php echo GetMessage("CATALOG_BUY")?></a>
				&nbsp;<a href="<?php echo $arElement["ADD_URL"]?>" rel="nofollow"><?php echo GetMessage("CATALOG_ADD")?></a>
				</noindex>
			<?php elseif((count($arResult["PRICES"]) > 0) || is_array($arElement["PRICE_MATRIX"])):?>
				<?=GetMessage("CATALOG_NOT_AVAILABLE")?>
				<?php $APPLICATION->IncludeComponent("bitrix:sale.notice.product", ".default", array(
							"NOTIFY_ID" => $arElement['ID'],
							"NOTIFY_URL" => htmlspecialcharsback($arElement["SUBSCRIBE_URL"]),
							"NOTIFY_USE_CAPTHA" => "N"
							),
							$component
						);?>
			<?php endif?>&nbsp;
		</td>
		<?php endif;?>
	</tr>
	<?php endforeach;?>
</table>
<?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<p><?=$arResult["NAV_STRING"]?></p>
<?php endif?>
</div>
