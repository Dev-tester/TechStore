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

$this->addExternalCss("/bitrix/css/main/bootstrap.css");
$this->addExternalCss("/bitrix/css/main/font-awesome.css");
CJSCore::Init(array("ajax"));

//Let's determine what value to display: rating or average ?
if($arParams["DISPLAY_AS_RATING"] == "vote_avg")
{
	if($arResult["PROPERTIES"]["vote_count"]["VALUE"])
		$DISPLAY_VALUE = round($arResult["PROPERTIES"]["vote_sum"]["VALUE"]/$arResult["PROPERTIES"]["vote_count"]["VALUE"], 2);
	else
		$DISPLAY_VALUE = 0;
}
else
{
	$DISPLAY_VALUE = $arResult["PROPERTIES"]["rating"]["VALUE"];
}
$voteContainerId = 'vote_'.$arResult["ID"];
?>
<div class="bx-rating" id="<?php echo $voteContainerId?>">
	<?php echo GetMessage("CT_BIV_RATING_TITLE");?>
	<?php 
	$onclick = "JCFlatVote.do_vote(this, '".$voteContainerId."', ".$arResult["AJAX_PARAMS"].")";
	foreach ($arResult["VOTE_NAMES"] as $i => $name)
	{
		if ($DISPLAY_VALUE && round($DISPLAY_VALUE) > $i)
			$className = "fa fa-star";
		else
			$className = "fa fa-star-o";

		$itemContainerId = $voteContainerId.'_'.$i;
		?><i
			class="<?php echo $className?>"
			id="<?php echo $itemContainerId?>"
			title="<?php echo $name?>"
			<?php if (!$arResult["VOTED"] && $arParams["READ_ONLY"]!=="Y"):?>
				onmouseover="JCFlatVote.trace_vote(this, true);"
				onmouseout="JCFlatVote.trace_vote(this, false)"
				onclick="<?php echo htmlspecialcharsbx($onclick);?>"
			<?php endif;?>
		></i><?php 
	}
	if ($arParams["SHOW_RATING"] == "Y"):?>
		(<?php echo $DISPLAY_VALUE?>)
	<?php endif;
?>
</div>