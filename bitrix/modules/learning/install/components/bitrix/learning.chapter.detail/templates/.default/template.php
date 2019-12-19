<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult["CHAPTER"])):?>

	<?php if($arResult["CHAPTER"]["DETAIL_PICTURE_ARRAY"] !== false):?>
		<?=ShowImage(
			$arResult["CHAPTER"]["DETAIL_PICTURE_ARRAY"],
			250,
			250,
			"hspace='8' vspace='1' align='left' border='0'"
				. ' alt="' . htmlspecialcharsbx($arResult["CHAPTER"]["DETAIL_PICTURE_ARRAY"]['DESCRIPTION']) . '"',
			"",
			true);?>
	<?php endif?>



	<?php if (strlen($arResult["CHAPTER"]["DETAIL_TEXT"])>0):?>
		<br /><?=$arResult["CHAPTER"]["DETAIL_TEXT"]?>
	<?php endif;?>

	<br clear="all" />

	<?php if (!empty($arResult["CONTENTS"])):?>
	<div class="learn-chapter-contents">
		<b><?php echo GetMessage("LEARNING_CHAPTER_CONTENTS");?>:</b>
		<?php foreach ($arResult["CONTENTS"] as $arContent):?>
			<?=str_repeat("<ul>", $arContent["DEPTH_LEVEL"]);?>
			<li><a href="<?=$arContent["URL"]?>"><?=$arContent["NAME"]?></a></li>
			<?=str_repeat("</ul>", $arContent["DEPTH_LEVEL"]);?>
		<?php endforeach?>
	</div>
	<?php endif?>

	<?php if($arResult["CHAPTER"]["SELF_TEST_EXISTS"]):?>
		<a href="<?=$arResult["CHAPTER"]["SELF_TEST_URL"]?>" title="<?=GetMessage("LEARNING_PASS_SELF_TEST")?>">
			<div title="<?php echo GetMessage("LEARNING_PASS_SELF_TEST")?>" class="learn-self-test-icon float-right"></div>
		</a>
	<?php endif?>
	<?php 
	$arParams["SHOW_RATING"] = $arResult["COURSE"]["RATING"];
	CRatingsComponentsMain::GetShowRating($arParams);
	if($arParams["SHOW_RATING"] == 'Y'):
	?>
	<br>
		<div class="learn-rating">
		<?php $APPLICATION->IncludeComponent(
			"bitrix:rating.vote", $arResult["COURSE"]["RATING_TYPE"],
			Array(
				"ENTITY_TYPE_ID" => "LEARN_LESSON",
				"ENTITY_ID" => $arResult["CHAPTER"]["LESSON_ID"],
				"OWNER_ID" => $arResult["CHAPTER"]["CREATED_BY"],
				"PATH_TO_USER_PROFILE" => $arParams["PATH_TO_USER_PROFILE"],
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);?>
		</div>
	<?php endif?>
	<?php if($arResult["CHAPTER"]["SELF_TEST_EXISTS"]):?>
		<div class="float-clear"></div>
		<br /><div title="<?php echo GetMessage("LEARNING_PASS_SELF_TEST")?>" class="learn-self-test-icon float-left"></div>&nbsp;<a href="<?=$arResult["CHAPTER"]["SELF_TEST_URL"]?>"><?=GetMessage("LEARNING_PASS_SELF_TEST")?></a><br />
	<?php endif?>

<?php endif?>