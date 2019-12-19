<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (empty($arResult))
	return;
?>
<div class="bottom-menu-left">
	<div class="bottom-menu-title"><?=$arParams['MENU_TITLE']?></div>
	<ul>
		<?php foreach($arResult as $itemIdex => $arItem):?>
		<li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
		<?php endforeach;?>
	</ul>
</div>