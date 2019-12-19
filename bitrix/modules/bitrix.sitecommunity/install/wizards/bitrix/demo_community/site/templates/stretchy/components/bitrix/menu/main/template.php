<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<ul id="top-menu-list">
	<?php foreach($arResult as $arItem):?>
		<?php if ($arItem["PERMISSION"] > "D"):?>
			<li<?php if ($arItem["SELECTED"]):?> class="selected"<?php endif?>><span><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></span></li>
		<?php endif?>	
	<?php endforeach?>
</ul>