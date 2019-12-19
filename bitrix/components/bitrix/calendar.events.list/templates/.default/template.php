<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="news-list">
<?php if ($arResult['ACCESS_DENIED']):?>
	<?=GetMessage('ECL_T_ACCESS_DENIED')?>
<?php elseif($arResult['INACTIVE_FEATURE']):?>
	<?=GetMessage('ECL_T_INACTIVE_FEATURE')?>
<?php elseif(count($arResult["ITEMS"]) == 0):?>
	<?=GetMessage('ECL_T_NO_ITEMS')?>
<?php else:?>
<?php foreach($arResult["ITEMS"] as $arItem):?>
	<div class="calendar-icon"></div>
	<span class="news-date-time intranet-date<?=$arItem["_ADD_CLASS"]?>"><?= $arItem["~FROM_TO_HTML"]?></span><?=$arItem["_Q_ICON"]?>
	<a class="calendar-link<?=$arItem["_ADD_CLASS"]?>" href="<?=$arItem["_DETAIL_URL"]?>"><?=$arItem["NAME"]?></a><br />
<?php endforeach;?>
<?php endif;?>
</div>