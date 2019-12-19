<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
if ($arResult['DELAYED'])
{
	ShowError(GetMessage('LEARNING_DELAYED_LESSON', array('#DATETIME#' => $arResult['DELAYED'])));
	return;
}
?>

<?php if (!empty($arResult["LESSON"])):?>

	<?php if ($arResult["LESSON"]["DETAIL_TEXT_TYPE"] == "file"):?>
		<iframe width="100%" height="95%" src="<?php echo $arResult["LESSON"]["LAUNCH"]?>" frameborder="0"></iframe>
	<?php else:?>
		<?php if($arResult["LESSON"]["SELF_TEST_EXISTS"]):?>
			<a href="<?=$arResult["LESSON"]["SELF_TEST_URL"]?>" title="<?=GetMessage("LEARNING_PASS_SELF_TEST")?>">
				<div title="<?php echo GetMessage("LEARNING_PASS_SELF_TEST")?>" class="learn-self-test-icon float-right"></div>
			</a>
		<?php endif?>

		<?php if($arResult["LESSON"]["DETAIL_PICTURE_ARRAY"] !== false):?>
			<div>
			<?=ShowImage(
				$arResult["LESSON"]["DETAIL_PICTURE_ARRAY"],
				250,
				250,
				"hspace='10' vspace='1' border='0'"
					. ' alt="' . htmlspecialcharsbx($arResult["LESSON"]["DETAIL_PICTURE_ARRAY"]['DESCRIPTION']) . '"',
				"",
				true);?>
			</div>
		<?php endif?>

		<?=$arResult["LESSON"]["DETAIL_TEXT"]?>

		<?php 
		$arParams["SHOW_RATING"] = $arResult["COURSE"]["RATING"];
		CRatingsComponentsMain::GetShowRating($arParams);
		if($arParams["SHOW_RATING"] == 'Y'):
		?>
		<div class="learn-rating">
		<?php 
		$APPLICATION->IncludeComponent(
			"bitrix:rating.vote", $arResult["COURSE"]["RATING_TYPE"],
			Array(
				"ENTITY_TYPE_ID" => "LEARN_LESSON",
				"ENTITY_ID" => $arResult["LESSON"]["LESSON_ID"],
				"OWNER_ID" => $arResult["LESSON"]["CREATED_BY"],
				"PATH_TO_USER_PROFILE" => $arParams["PATH_TO_USER_PROFILE"],
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);?>
		</div>
		<?php endif?>
		<?php if($arResult["LESSON"]["SELF_TEST_EXISTS"]):?>
			<div class="float-clear"></div>
			<br /><div title="<?php echo GetMessage("LEARNING_PASS_SELF_TEST")?>" class="learn-self-test-icon float-left"></div>&nbsp;<a href="<?=$arResult["LESSON"]["SELF_TEST_URL"]?>"><?=GetMessage("LEARNING_PASS_SELF_TEST")?></a><br />
		<?php endif?>
	<?php endif?>
<?php endif?>