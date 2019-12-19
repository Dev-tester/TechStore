<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult["ITEMS"])):?>
<ul class="mp_top_nav_ul">
	<?php foreach($arResult["ITEMS"] as $index => $arItem):?>
	<li class="mp_top_nav_ul_li <?=$arItem["PARAMS"]["class"]?> <?php if ($arItem["SELECTED"] && !isset($_GET["app"]) && !isset($_GET["category"])):?>active<?php endif?>">
		<a href="<?=($arItem["PARAMS"]["class"] == "category" ? "javascript:void(0)" : $arItem["LINK"])?>" <?php if ($arItem["PARAMS"]["class"] == "category"):?>onclick="BX.addClass(this.parentNode, 'active');ShowCategoriesPopup(this);"<?php endif?>>
			<span class="leftborder"></span><span class="icon"></span><?=$arItem["TEXT"]?>
			<?php if ($arItem["PARAMS"]["class"] == "category"):?>
				<span class="arrow"></span>
			<?php elseif($arItem["PARAMS"]["class"] == "updates"):?>
				<?php 
				$numUpdates = COption::GetOptionInt("bitrix24", "mp_num_updates", "");
				?>
				<span id="menu_num_updates">
					<?php if ($numUpdates) echo " (".$numUpdates.")";?>
				</span>
			<?php elseif($arItem["PARAMS"]["class"] == "sale" && $arResult["UNINSTALLED_PAID_APPS_COUNT"] > 0):?>
				(<?=$arResult["UNINSTALLED_PAID_APPS_COUNT"]?>)
			<?php endif?>
			<span class="rightborder"></span>
		</a>
	</li>
	<?php endforeach?>
</ul>
<?php endif?>
