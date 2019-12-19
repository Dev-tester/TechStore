<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="news-index">
<?php foreach($arResult["IBLOCKS"] as $arIBlock):?>
	<?php if(count($arIBlock["ITEMS"])>0):?>
		<b><?=$arIBlock["NAME"]?></b>
		<ul>
		<?php foreach($arIBlock["ITEMS"] as $arItem):?>
			<li><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></li>
		<?php endforeach;?>
		</ul>
	<?php endif?>
<?php endforeach;?>
</div>
