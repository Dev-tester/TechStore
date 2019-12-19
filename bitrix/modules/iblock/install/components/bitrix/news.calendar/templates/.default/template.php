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
$this->setFrameMode(true);
?>
<div class="news-calendar">
	<?php if($arParams["SHOW_CURRENT_DATE"]):?>
		<p align="right" class="NewsCalMonthNav"><b><?=$arResult["TITLE"]?></b></p>
	<?php endif?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="NewsCalMonthNav" align="left">
				<?php if($arResult["PREV_MONTH_URL"]):?>
					<a href="<?=$arResult["PREV_MONTH_URL"]?>" title="<?=$arResult["PREV_MONTH_URL_TITLE"]?>"><?=GetMessage("IBL_NEWS_CAL_PR_M")?></a>
				<?php endif?>
				<?php if($arResult["PREV_MONTH_URL"] && $arResult["NEXT_MONTH_URL"] && !$arParams["SHOW_MONTH_LIST"]):?>
					&nbsp;&nbsp;|&nbsp;&nbsp;
				<?php endif?>
				<?php if($arResult["SHOW_MONTH_LIST"]):?>
					&nbsp;&nbsp;
					<select onChange="b_result()" name="MONTH_SELECT" id="month_sel">
						<?php foreach($arResult["SHOW_MONTH_LIST"] as $month => $arOption):?>
							<option value="<?=$arOption["VALUE"]?>" <?php if($arResult["currentMonth"] == $month) echo "selected";?>><?=$arOption["DISPLAY"]?></option>
						<?php endforeach?>
					</select>
					&nbsp;&nbsp;
					<script language="JavaScript" type="text/javascript">
					<!--
					function b_result()
					{
						var idx=document.getElementById("month_sel").selectedIndex;
						<?php if($arParams["AJAX_ID"]):?>
							BX.ajax.insertToNode(document.getElementById("month_sel").options[idx].value, 'comp_<?php echo CUtil::JSEscape($arParams['AJAX_ID'])?>', <?php echo $arParams["AJAX_OPTION_SHADOW"]=="Y"? "true": "false"?>);
						<?php else:?>
							window.document.location.href=document.getElementById("month_sel").options[idx].value;
						<?php endif?>
					}
					-->
					</script>
				<?php endif?>
				<?php if($arResult["NEXT_MONTH_URL"]):?>
					<a href="<?=$arResult["NEXT_MONTH_URL"]?>" title="<?=$arResult["NEXT_MONTH_URL_TITLE"]?>"><?=GetMessage("IBL_NEWS_CAL_N_M")?></a>
				<?php endif?>
			</td>
			<?php if($arParams["SHOW_YEAR"]):?>
			<td class="NewsCalMonthNav" align="right">
				<?php if($arResult["PREV_YEAR_URL"]):?>
					<a href="<?=$arResult["PREV_YEAR_URL"]?>" title="<?=$arResult["PREV_YEAR_URL_TITLE"]?>"><?=GetMessage("IBL_NEWS_CAL_PR_Y")?></a>
				<?php endif?>
				<?php if($arResult["PREV_YEAR_URL"] && $arResult["NEXT_YEAR_URL"]):?>
					&nbsp;&nbsp;|&nbsp;&nbsp;
				<?php endif?>
				<?php if($arResult["NEXT_YEAR_URL"]):?>
					<a href="<?=$arResult["NEXT_YEAR_URL"]?>" title="<?=$arResult["NEXT_YEAR_URL_TITLE"]?>"><?=GetMessage("IBL_NEWS_CAL_N_Y")?></a>
				<?php endif?>
			</td>
			<?php endif?>
		</tr>
	</table>
	<br />
	<table width='100%' border='0' cellspacing='1' cellpadding='4' class='NewsCalTable'>
	<tr>
	<?php foreach($arResult["WEEK_DAYS"] as $WDay):?>
		<td class='NewsCalHeader'><?=$WDay["FULL"]?></td>
	<?php endforeach?>
	</tr>
	<?php foreach($arResult["MONTH"] as $arWeek):?>
	<tr>
		<?php foreach($arWeek as $arDay):?>
		<td align="left" valign="top" class='<?=$arDay["td_class"]?>' width="14%">
			<span class="<?=$arDay["day_class"]?>"><?=$arDay["day"]?></span>
			<?php foreach($arDay["events"] as $arEvent):?>
				<div class="NewsCalNews" style="padding-top:5px;"><?=$arEvent["time"]?><a href="<?=$arEvent["url"]?>" title="<?=$arEvent["preview"]?>"><?=$arEvent["title"]?></a></div>
			<?php endforeach?>
		</td>
		<?php endforeach?>
	</tr >
	<?php endforeach?>
	</table>
</div>
