<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div style="float: left; padding-top: 2px;"><?=GetMessage("SEARCH_LABEL")?>&nbsp;</div><?php $APPLICATION->IncludeComponent(
	"bitrix:search.form",
	"flat",
	Array(
		"PAGE" => "search.php"
	),
	$component
);?>
<br />
<?php if (!empty($arResult["COURSES"])):?>
<div class="learning-course-list">
	<?php foreach($arResult["COURSES"] as $arCourse):?>
		<?php if ($arCourse["PREVIEW_PICTURE_ARRAY"]!==false):?>
			<?php echo ShowImage(
				$arCourse["PREVIEW_PICTURE_ARRAY"], 
				200, 
				200, 
				"hspace='6' vspace='6' align='left' border='0'"
					. ' alt="' . htmlspecialcharsbx($arCourse['PREVIEW_PICTURE_ARRAY']['DESCRIPTION']) . '"', 
				"", 
				true);?>
		<?php endif;?>

		<a href="<?=$arCourse["COURSE_DETAIL_URL"]?>" target="_blank"><?=$arCourse["NAME"]?></a>
		<?php if(strlen($arCourse["PREVIEW_TEXT"])>0):?>
			<br /><?=$arCourse["PREVIEW_TEXT"]?>
		<?php endif?>
		<br clear="all"><br />
	<?php endforeach;?>

</div>
	<?=$arResult["NAV_STRING"]?>
<?php endif?>