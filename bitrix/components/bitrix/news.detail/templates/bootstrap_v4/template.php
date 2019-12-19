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
$themeClass = isset($arParams['TEMPLATE_THEME']) ? ' bx-'.$arParams['TEMPLATE_THEME'] : '';
CUtil::InitJSCore(array('fx'));
?>
<div class="news-detail<?=$themeClass?>">
	<div class="mb-3" id="<?php echo $this->GetEditAreaId($arResult['ID'])?>">

		<?php if($arParams["DISPLAY_PICTURE"]!="N"):?>
			<?php  if ($arResult["VIDEO"])
			{
				?>
				<div class="mb-5 news-detail-youtube embed-responsive embed-responsive-16by9" style="display: block;">
					<iframe src="<?php 
					echo $arResult["VIDEO"] ?>" frameborder="0" allowfullscreen=""></iframe>
				</div>
				<?php 
			}
			else if ($arResult["SOUND_CLOUD"])
			{
				?>
				<div class="mb-5 news-detail-audio">
					<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=<?php 
					echo urlencode($arResult["SOUND_CLOUD"]) ?>&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"></iframe>
				</div>
				<?php 
			}
			else if ($arResult["SLIDER"] && count($arResult["SLIDER"]) > 1)
			{
				?>
				<div class="mb-5 news-detail-slider">
					<div class="news-detail-slider-container" style="width: <?php  echo count($arResult["SLIDER"]) * 100 ?>%;left: 0%;">
						<?php  foreach ($arResult["SLIDER"] as $file):?>
							<div style="width: <?php  echo 100 / count($arResult["SLIDER"]) ?>%;" class="news-detail-slider-slide">
								<img src="<?= $file["SRC"] ?>" alt="<?= $file["DESCRIPTION"] ?>">
							</div>
						<?php  endforeach ?>
						<div style="clear: both;"></div>
					</div>
					<div class="news-detail-slider-arrow-container-left">
						<div class="news-detail-slider-arrow"><i class="fa fa-angle-left"></i></div>
					</div>
					<div class="news-detail-slider-arrow-container-right">
						<div class="news-detail-slider-arrow"><i class="fa fa-angle-right"></i></div>
					</div>
					<ul class="news-detail-slider-control">
						<?php  foreach ($arResult["SLIDER"] as $i => $file):?>
							<li rel="<?= ($i + 1) ?>" <?php  if (!$i)
								echo 'class="current"' ?>><span></span></li>
						<?php  endforeach ?>
					</ul>
				</div>
				<?php 
			}
			else if ($arResult["SLIDER"])
			{
				?>
				<div class="mb-5 news-detail-img">
					<img
						class="card-img-top"
						src="<?= $arResult["SLIDER"][0]["SRC"] ?>"
						alt="<?= $arResult["SLIDER"][0]["ALT"] ?>"
						title="<?= $arResult["SLIDER"][0]["TITLE"] ?>"
					/>
				</div>
				<?php 
			}
			else if (is_array($arResult["DETAIL_PICTURE"]))
			{
				?>
				<div class="mb-5 news-detail-img">
					<img
						class="card-img-top"
						src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
						alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
						title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
						/>
				</div>
				<?php 
			}
			?>
		<?php endif?>

		<div class="news-detail-body">

			<?php if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
				<h3 class="news-detail-title"><?=$arResult["NAME"]?></h3>
			<?php endif;?>

			<div class="news-detail-content">
				<?php if($arResult["NAV_RESULT"]):?>
					<?php if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?php endif;?>
					<?php echo $arResult["NAV_TEXT"];?>
					<?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?php endif;?>
				<?php elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
					<?php echo $arResult["DETAIL_TEXT"];?>
				<?php else:?>
					<?php echo $arResult["PREVIEW_TEXT"];?>
				<?php endif?>
			</div>

		</div>

		<?php if(($arParams["USE_RATING"]=="Y") && ($arParams["USE_SHARE"] == "Y")) {?> <div class="d-flex justify-content-between"> <?php  } ?>

			<?php if($arParams["USE_RATING"]=="Y"):?>
				<div>
					<?php $APPLICATION->IncludeComponent(
						"bitrix:iblock.vote",
						"bootstrap_v4",
						Array(
							"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
							"IBLOCK_ID" => $arParams["IBLOCK_ID"],
							"ELEMENT_ID" => $arResult["ID"],
							"MAX_VOTE" => $arParams["MAX_VOTE"],
							"VOTE_NAMES" => $arParams["VOTE_NAMES"],
							"CACHE_TYPE" => $arParams["CACHE_TYPE"],
							"CACHE_TIME" => $arParams["CACHE_TIME"],
							"DISPLAY_AS_RATING" => $arParams["DISPLAY_AS_RATING"],
							"SHOW_RATING" => "Y",
						),
						$component
					);?>
				</div>
			<?php endif?>

			<?php if ($arParams["USE_SHARE"] == "Y"):?>
				<div>
					<noindex>
						<?php 
						$APPLICATION->IncludeComponent(
							"bitrix:main.share",
							$arParams["SHARE_TEMPLATE"],
							array(
								"HANDLERS" => $arParams["SHARE_HANDLERS"],
								"PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
								"PAGE_TITLE" => $arResult["~NAME"],
								"SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
								"SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
								"HIDE" => $arParams["SHARE_HIDE"],
							),
							$component,
							array("HIDE_ICONS" => "Y")
						);
						?>
					</noindex>
				</div>
			<?php endif?>

		<?php if(($arParams["USE_RATING"]=="Y") && ($arParams["USE_SHARE"] == "Y")) {?> </div> <?php  } ?>

	<?php foreach($arResult["FIELDS"] as $code=>$value):?>
		<?php if($code == "SHOW_COUNTER"):?>
			<div class="news-detail-view"><?=GetMessage("IBLOCK_FIELD_".$code)?>: <?=intval($value);?></div>
		<?php elseif($code == "SHOW_COUNTER_START" && $value):?>
			<?php  $value = CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($value, CSite::GetDateFormat())); ?>
			<div class="news-detail-date"><?=GetMessage("IBLOCK_FIELD_".$code)?>: <?=$value;?> </div>
		<?php elseif($code == "TAGS" && $value):?>
			<div class="news-detail-tags"><?=GetMessage("IBLOCK_FIELD_".$code)?>: <?=$value;?> </div>
		<?php elseif($code == "CREATED_USER_NAME"):?>
			<div class="news-detail-author"><?=GetMessage("IBLOCK_FIELD_".$code)?>: <?=$value;?> </div>
		<?php elseif ($value != ""):?>
			<div class="news-detail-other"><?=GetMessage("IBLOCK_FIELD_".$code)?>: <?=$value;?></div>
		<?php endif;?>
	<?php endforeach;?>

	<?php foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
		<?php 
		if(is_array($arProperty["DISPLAY_VALUE"]))
			$value = implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
		else
			$value = $arProperty["DISPLAY_VALUE"];
		?>
		<?php if($arProperty["CODE"] == "FORUM_MESSAGE_CNT"):?>
			<div class="news-detail-comments"><?=$arProperty["NAME"]?>: <?=$value;?> </div>
		<?php elseif ($value != ""):?>
			<div class="news-detail-other"><?=$arProperty["NAME"]?>: <?=$value;?> </div>
		<?php endif;?>
	<?php endforeach;?>

	<?php if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<div class="news-detail-date"><?php echo $arResult["DISPLAY_ACTIVE_FROM"]?></div>
	<?php endif?>



	</div>
</div>
<script type="text/javascript">
	BX.ready(function() {
		var slider = new JCNewsSlider('<?=CUtil::JSEscape($this->GetEditAreaId($arResult['ID']));?>', {
			imagesContainerClassName: 'news-detail-slider-container',
			leftArrowClassName: 'news-detail-slider-arrow-container-left',
			rightArrowClassName: 'news-detail-slider-arrow-container-right',
			controlContainerClassName: 'news-detail-slider-control'
		});
	});
</script>
