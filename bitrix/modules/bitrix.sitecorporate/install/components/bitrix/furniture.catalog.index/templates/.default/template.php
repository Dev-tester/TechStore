<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?php 
if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0):
?>
<div class="product-list">
<?php 
	foreach ($arResult['ITEMS'] as $arItem):
?>
	<div class="product">
		<div class="product-overlay"></div>
		<div class="product-image"<?php if($arItem['PICTURE']['SRC']):?> style="background-image: url(<?=$arItem['PICTURE']['SRC']?>)"<?php endif;?>></div>
		<a class="product-desc" href="<?=$arItem['DETAIL_URL']?>">
			<b><?=$arItem['NAME']?></b>
			<p><?=$arItem['DESCRIPTION']?></p>
		</a>
	</div>
<?php 
	endforeach;
?>
</div>
<?php 
endif;
?>
