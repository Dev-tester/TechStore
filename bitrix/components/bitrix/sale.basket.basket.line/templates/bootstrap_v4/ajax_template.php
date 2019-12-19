<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$this->IncludeLangFile('template.php');

$cartId = $arParams['cartId'];

require(realpath(dirname(__FILE__)).'/top_template.php');

if ($arParams["SHOW_PRODUCTS"] == "Y" && ($arResult['NUM_PRODUCTS'] > 0 || !empty($arResult['CATEGORIES']['DELAY'])))
{
?>
	<div data-role="basket-item-list" class="bx-basket-item-list">

		<?php if ($arParams["POSITION_FIXED"] == "Y"):?>
			<div id="<?=$cartId?>status" class="bx-basket-item-list-action" onclick="<?=$cartId?>.toggleOpenCloseCart()"><?=GetMessage("TSB1_COLLAPSE")?></div>
		<?php endif?>

		<?php if ($arParams["PATH_TO_ORDER"] && $arResult["CATEGORIES"]["READY"]):?>
			<div class="bx-basket-item-list-button-container">
				<a href="<?=$arParams["PATH_TO_ORDER"]?>" class="btn btn-primary"><?=GetMessage("TSB1_2ORDER")?></a>
			</div>
		<?php endif?>

		<div id="<?=$cartId?>products" class="bx-basket-item-list-container">
			<?php foreach ($arResult["CATEGORIES"] as $category => $items):
				if (empty($items))
					continue;
				?>
				<div class="bx-basket-item-list-item-status"><?=GetMessage("TSB1_$category")?></div>
				<?php foreach ($items as $v):?>
					<div class="bx-basket-item-list-item">
						<div class="bx-basket-item-list-item-img">
							<?php if ($arParams["SHOW_IMAGE"] == "Y" && $v["PICTURE_SRC"]):?>
								<?php if($v["DETAIL_PAGE_URL"]):?>
									<a href="<?=$v["DETAIL_PAGE_URL"]?>"><img src="<?=$v["PICTURE_SRC"]?>" alt="<?=$v["NAME"]?>"></a>
								<?php else:?>
									<img src="<?=$v["PICTURE_SRC"]?>" alt="<?=$v["NAME"]?>" />
								<?php endif?>
							<?php endif?>
							<div class="bx-basket-item-list-item-remove" onclick="<?=$cartId?>.removeItemFromCart(<?=$v['ID']?>)" title="<?=GetMessage("TSB1_DELETE")?>"></div>
						</div>
						<div class="bx-basket-item-list-item-name">
							<?php if ($v["DETAIL_PAGE_URL"]):?>
								<a href="<?=$v["DETAIL_PAGE_URL"]?>"><?=$v["NAME"]?></a>
							<?php else:?>
								<?=$v["NAME"]?>
							<?php endif?>
						</div>
						<?php if (true):/*$category != "SUBSCRIBE") TODO */?>
							<div class="bx-basket-item-list-item-price-block">
								<?php if ($arParams["SHOW_PRICE"] == "Y"):?>
									<div class="bx-basket-item-list-item-price"><strong><?=$v["PRICE_FMT"]?></strong></div>
									<?php if ($v["FULL_PRICE"] != $v["PRICE_FMT"]):?>
										<div class="bx-basket-item-list-item-price-old"><?=$v["FULL_PRICE"]?></div>
									<?php endif?>
								<?php endif?>
								<?php if ($arParams["SHOW_SUMMARY"] == "Y"):?>
									<div class="bx-basket-item-list-item-price-summ">
										<strong><?=$v["QUANTITY"]?></strong> <?=$v["MEASURE_NAME"]?> <?=GetMessage("TSB1_SUM")?>
										<strong><?=$v["SUM"]?></strong>
									</div>
								<?php endif?>
							</div>
						<?php endif?>
					</div>
				<?php endforeach?>
			<?php endforeach?>
		</div>
	</div>

	<script>
		BX.ready(function(){
			<?=$cartId?>.fixCart();
		});
	</script>
<?php 
}