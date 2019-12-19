<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

/** @var PageNavigationComponent $component */
$this->setFrameMode(true);

$ClientID = $arResult['ID'];
?>
<div class="navigation">
<?php 
if($arResult["REVERSED_PAGES"] === true)
{
	// to show always first and last pages

	$sPrevHref = '';
	if ($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"])
	{
		$bPrevDisabled = false;
		if ($arResult["PAGE_COUNT"] == ($arResult["CURRENT_PAGE"]+1))
		{
			$sPrevHref = htmlspecialcharsbx($arResult["URL"]);
		}
		else
		{
			$sPrevHref = htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]+1));
		}
	}
	else
	{
		$bPrevDisabled = true;
	}

	$sNextHref = '';
	if ($arResult["PAGE_COUNT"] > 1)
	{
		$bNextDisabled = false;
		$sNextHref = htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]-1));
	}
	else
	{
		$bNextDisabled = true;
	}
	?>

	<div class="navigation-pages">
		<span class="navigation-title"><?=GetMessage("mp_pages")?></span>
	<?php 
	$bFirst = true;
	$bPoints = false;
	do
	{
		$NavRecordGroupPrint = $arResult["PAGE_COUNT"] - $arResult["START_PAGE"] + 1;
		if ($arResult["START_PAGE"] <= 2 || $arResult["PAGE_COUNT"]-$arResult["START_PAGE"] <= 1 || abs($arResult['START_PAGE']-$arResult["CURRENT_PAGE"])<=2)
		{
			if ($arResult["START_PAGE"] == $arResult["CURRENT_PAGE"]):
				?><span class="navigation-current-page"><?=$NavRecordGroupPrint?></span><?php 
			elseif($arResult["START_PAGE"] == $arResult["PAGE_COUNT"]):
				?><a data-slider-ignore-autobinding="true" class="navigation-page-numb" href="<?=htmlspecialcharsbx(
				$arResult["URL"]
			)?>"><?=$NavRecordGroupPrint?></a><?php 
			else:
				?><a data-slider-ignore-autobinding="true" class="navigation-page-numb" href="<?=htmlspecialcharsbx(
				$component->replaceUrlTemplate($arResult["START_PAGE"])
			)?>"><?=$NavRecordGroupPrint?></a><?php 
			endif;
			$bFirst = false;
			$bPoints = true;
		}
		else
		{
			if ($bPoints)
			{
				?><span class="navigation-points">...</span><?php 
				$bPoints = false;
			}
		}
		$arResult["START_PAGE"]--;
	}
	while ($arResult["START_PAGE"] >= $arResult["END_PAGE"]);

	if ($arResult["SHOW_ALL"])
	{
		if ($arResult["ALL_RECORDS"])
		{
			?><a
			data-slider-ignore-autobinding="true"
			class="navigation-page-numb navigation-page-all"
			href="<?=htmlspecialcharsbx($arResult["URL"])?>"><?=GetMessage("mp_nav_paged")?></a><?php 
		}
		else
		{
			?><a
			data-slider-ignore-autobinding="true"
			class="navigation-page-numb navigation-page-all"
			href="<?=htmlspecialcharsbx($component->replaceUrlTemplate("all"))?>"><?=GetMessage("mp_nav_all")?></a><?php 
		}
	}
	?>

	</div>
	<div class="navigation-arrows">
		<<?php 
		if (!$bPrevDisabled):?>a href="<?=$sPrevHref;?>" data-slider-ignore-autobinding="true" id="<?=$ClientID?>_previous_page"<?php  else:?>span<?php endif ?> class="navigation-button<?php 
		if ($bPrevDisabled):?> navigation-disabled<?php endif ?>"><span class="navigation-text"><span class="navigation-ctrl-before">Ctrl</span><span class="navigation-text-cont"><?=GetMessage(
						"mp_nav_prev"
					)?></span></span><?php 
			if (!$bPrevDisabled): ?></a><?php 
		else: ?></span><?php 
		endif ?><<?php 
		if (!$bNextDisabled):?>a href="<?=$sNextHref;?>" data-slider-ignore-autobinding="true" id="<?=$ClientID?>_next_page"<?php  else:?>span<?php endif ?> class="navigation-button<?php 
		if ($bNextDisabled):?> navigation-disabled<?php endif ?>"><span class="navigation-text"><span class="navigation-text-cont"><?=GetMessage(
						"mp_nav_next"
					)?></span><span class="navigation-ctrl-after">Ctrl</span></span><?php 
			if (!$bNextDisabled): ?></a><?php 
	else:?></span><?php 
	endif ?>
	</div>
	<?php 
}
else
{
	// to show always first and last pages
	$arResult["START_PAGE"] = 1;
	$arResult["END_PAGE"] = $arResult["PAGE_COUNT"];

	$sPrevHref = '';
	if ($arResult["CURRENT_PAGE"] > 1)
	{
		$bPrevDisabled = false;
		if ($arResult["CURRENT_PAGE"] > 2)
		{
			$sPrevHref = htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]-1));
		}
		else
		{
			$sPrevHref = htmlspecialcharsbx($arResult["URL"]);
		}

	}
	else
	{
		$bPrevDisabled = true;
	}

	$sNextHref = '';
	if ($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"])
	{
		$bNextDisabled = false;
		$sNextHref = htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]+1));
	}
	else
	{
		$bNextDisabled = true;
	}
	?>

	<div class="navigation-pages">
		<span class="navigation-title"><?=GetMessage("mp_pages")?></span><?php 

	$bFirst = true;
	$bPoints = false;
	do
	{
		if ($arResult["START_PAGE"] <= 2 || $arResult["END_PAGE"]-$arResult["START_PAGE"] <= 1 || abs($arResult['START_PAGE']-$arResult["CURRENT_PAGE"])<=2)
		{
			if ($arResult["START_PAGE"] == $arResult["CURRENT_PAGE"]):
				?><span class="navigation-current-page"><?=$arResult["START_PAGE"]?></span><?php 
			elseif($arResult["START_PAGE"] == 1):
				?><a data-slider-ignore-autobinding="true" class="navigation-page-numb" href="<?=htmlspecialcharsbx(
				$arResult["URL"]
			)?>"><?=$arResult["START_PAGE"]?></a><?php 
			else:
				?><a data-slider-ignore-autobinding="true" class="navigation-page-numb" href="<?=htmlspecialcharsbx(
				$component->replaceUrlTemplate($arResult["START_PAGE"])
			)?>"><?=$arResult["START_PAGE"]?></a><?php 
			endif;
			$bFirst = false;
			$bPoints = true;
		}
		else
		{
			if ($bPoints)
			{
				?><span class="navigation-points">...</span><?php 
				$bPoints = false;
			}
		}
		$arResult["START_PAGE"]++;
	}
	while($arResult["START_PAGE"] <= $arResult["END_PAGE"]);

	if ($arResult["SHOW_ALL"])
	{
		if ($arResult["ALL_RECORDS"])
		{
			?><a
			data-slider-ignore-autobinding="true"
			class="navigation-page-numb navigation-page-all"
			href="<?=htmlspecialcharsbx($arResult["URL"])?>"><?=GetMessage("mp_nav_paged")?></a><?php 
		}
		else
		{
			?><a
			data-slider-ignore-autobinding="true"
			class="navigation-page-numb navigation-page-all"
			href="<?=htmlspecialcharsbx($component->replaceUrlTemplate("all"))?>"><?=GetMessage("mp_nav_all")?></a><?php 
		}
	}

	?>
	</div>
	<div class="navigation-arrows">
		<<?php 
		if (!$bPrevDisabled):?>a href="<?=$sPrevHref;?>" data-slider-ignore-autobinding="true" id="<?=$ClientID?>_previous_page"<?php  else:?>span<?php endif ?> class="navigation-button<?php 
		if ($bPrevDisabled):?> navigation-disabled<?php endif ?>"><span class="navigation-text"><span class="navigation-ctrl-before">Ctrl</span><span class="navigation-text-cont"><?=GetMessage(
						"mp_nav_prev"
					)?></span></span><?php 
			if (!$bPrevDisabled): ?></a><?php 
		else: ?></span><?php 
		endif ?><<?php 
		if (!$bNextDisabled):?>a href="<?=$sNextHref;?>" data-slider-ignore-autobinding="true" id="<?=$ClientID?>_next_page"<?php  else:?>span<?php endif ?> class="navigation-button<?php 
		if ($bNextDisabled):?> navigation-disabled<?php endif ?>"><span class="navigation-text"><span class="navigation-text-cont"><?=GetMessage(
						"mp_nav_next"
					)?></span><span class="navigation-ctrl-after">Ctrl</span></span><?php 
			if (!$bNextDisabled): ?></a><?php 
	else:?></span><?php 
	endif ?>
	</div>
	<?php 
}
?>
</div>

<?php CJSCore::Init();?>
<script type="text/javascript">
	BX.bind(document, "keydown", function (event) {

		event = event || window.event;
		if (!event.ctrlKey)
			return;

		var target = event.target || event.srcElement;
		if (target && target.nodeName && (target.nodeName.toUpperCase() == "INPUT" || target.nodeName.toUpperCase() == "TEXTAREA"))
			return;

		var key = (event.keyCode ? event.keyCode : (event.which ? event.which : null));
		if (!key)
			return;

		var link = null;
		if (key == 39)
			link = BX('<?=$ClientID?>_next_page');
		else if (key == 37)
			link = BX('<?=$ClientID?>_previous_page');

		if (link && link.href)
			document.location = link.href;
	});
</script>