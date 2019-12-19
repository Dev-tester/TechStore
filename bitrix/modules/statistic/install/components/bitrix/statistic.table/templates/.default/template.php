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
$this->createFrame()->begin();
?>
<div class="statistic-table">
	<br />
	<div class="container" title="<?php echo GetMessage("STATT_VIEW_HITS")?>">
		<div class="inner-dots">
			<div class="left"><?php 
				if ($arResult["IS_ADMIN"]) :
					?><a href="/bitrix/admin/hit_list.php?lang=<?=LANGUAGE_ID?>&amp;del_filter=Y"><?php echo GetMessage("STATT_HITS")?></a><?php 
				else :
					?><?php echo GetMessage("STATT_HITS")?><?php 
				endif;
			?></div>
			<div class="right"><?php 
				if ($arResult["IS_ADMIN"]) :
					?><a href="/bitrix/admin/hit_list.php?lang=<?=LANGUAGE_ID?>&amp;del_filter=Y"><?php echo $arResult["STATISTIC"]["TOTAL_HITS"]?></a><?php 
				else :
					?><?php echo $arResult["STATISTIC"]["TOTAL_HITS"]?><?php 
				endif;
			?></div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="container" title="<?php echo GetMessage("STATT_VIEW_TODAY_HITS")." (".$arResult["NOW"].")"?>">
		<div class="inner">
			<div class="right today"><?php 
				if ($arResult["IS_ADMIN"]) :
					?><a href="/bitrix/admin/hit_list.php?lang=<?=LANGUAGE_ID?>&amp;find_date1=<?php echo $arResult["TODAY"]?>&amp;find_date2=<?php echo $arResult["TODAY"]?>&amp;set_filter=Y"><?php echo $arResult["STATISTIC"]["TODAY_HITS"]?></a><?php 
				else :
					?><?php echo $arResult["STATISTIC"]["TODAY_HITS"]?><?php 
				endif;
			?></div>
			<div class="clear"></div>
		</div>
	</div>
	<br />
	<div class="container" title="<?php echo GetMessage("STATT_VIEW_HOSTS")?>">
		<div class="inner-dots">
			<div class="left"><?php 
				if ($arResult["IS_ADMIN"]) :
					?><a href="/bitrix/admin/stat_list.php?lang=<?=LANGUAGE_ID?>&amp;del_filter=Y"><?php echo GetMessage("STATT_HOSTS")?></a><?php 
				else :
					?><?php echo GetMessage("STATT_HOSTS")?><?php 
				endif;
			?></div>
			<div class="right"><?php 
				if ($arResult["IS_ADMIN"]) :
					?><a href="/bitrix/admin/stat_list.php?lang=<?=LANGUAGE_ID?>&amp;del_filter=Y"><?php echo $arResult["STATISTIC"]["TOTAL_HOSTS"]?></a><?php 
				else :
					?><?php echo $arResult["STATISTIC"]["TOTAL_HOSTS"]?><?php 
				endif;
			?></div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="container" title="<?php echo GetMessage("STATT_VIEW_TODAY_HOSTS")." (".$arResult["NOW"].")"?>">
		<div class="inner">
			<div class="right today"><?php 
				if ($arResult["IS_ADMIN"]) :
					?><a href="/bitrix/admin/stat_list.php?lang=<?=LANGUAGE_ID?>&amp;del_filter=Y"><?php echo $arResult["STATISTIC"]["TODAY_HOSTS"]?></a><?php 
				else :
					?><?php echo $arResult["STATISTIC"]["TODAY_HOSTS"]?><?php 
				endif;
			?></div>
			<div class="clear"></div>
		</div>
	</div>
	<br />
	<div class="container" title="<?php echo GetMessage("STATT_VIEW_GUESTS")?>">
		<div class="inner-dots">
			<div class="left"><?php 
				if ($arResult["IS_ADMIN"]) :
					?><a href="/bitrix/admin/guest_list.php?lang=<?=LANGUAGE_ID?>&amp;del_filter=Y"><?php echo GetMessage("STATT_VISITORS")?></a><?php 
				else :
					?><?php echo GetMessage("STATT_VISITORS")?><?php 
				endif;
			?></div>
			<div class="right"><?php 
				if ($arResult["IS_ADMIN"]) :
					?><a href="/bitrix/admin/guest_list.php?lang=<?=LANGUAGE_ID?>&amp;del_filter=Y"><?php echo $arResult["STATISTIC"]["TOTAL_GUESTS"]?></a><?php 
				else :
					?><?php echo $arResult["STATISTIC"]["TOTAL_GUESTS"]?><?php 
				endif;
			?></div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="container" title="<?php echo GetMessage("STATT_VIEW_TODAY_GUESTS")." (".$arResult["NOW"].")"?>">
		<div class="inner">
			<div class="right today"><?php 
				if ($arResult["IS_ADMIN"]) :
					?><a href="/bitrix/admin/guest_list.php?lang=<?=LANGUAGE_ID?>&amp;find_period_date1=<?php echo $arResult["TODAY"]?>&amp;find_period_date2=<?php echo $arResult["TODAY"]?>&amp;set_filter=Y"><?php echo $arResult["STATISTIC"]["TODAY_GUESTS"]?></a><?php 
				else :
					?><?php echo $arResult["STATISTIC"]["TODAY_GUESTS"]?><?php 
				endif;
			?></div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="container" title="<?php echo GetMessage("STATT_VIEW_USERS_ONLINE")." (".$arResult["NOW"].")"?>">
		<div class="inner">
			<div class="right today"><?php 
				if ($arResult["IS_ADMIN"]) :
					?><a href="/bitrix/admin/users_online.php?lang=<?=LANGUAGE_ID?>"><?php echo $arResult["STATISTIC"]["ONLINE_GUESTS"]?></a><?php 
				else :
					?><?php echo $arResult["STATISTIC"]["ONLINE_GUESTS"]?><?php 
				endif;
			?></div>
			<div class="clear"></div>
		</div>
	</div>
</div>
