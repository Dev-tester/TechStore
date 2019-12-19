<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult)):?>
<ul id="top-menu">
<?php foreach($arResult as $arItem):?>
	<?php if($arItem["SELECTED"]):?>
		<li class="selected"><b class="r1"></b><b class="r0"></b><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a><b class="r0"></b><b class="r1"></b></li>
	<?php else:?>
		<li><b class="r1"></b><b class="r0"></b><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a><b class="r0"></b><b class="r1"></b></li>
	<?php endif?>
	
<?php endforeach?>
</ul>		
<?php endif?>