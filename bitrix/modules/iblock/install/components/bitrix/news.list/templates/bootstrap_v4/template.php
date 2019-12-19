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
?>
<div class="row news-list<?=$themeClass?>">
	<div class="col">
		<?php if($arParams["DISPLAY_TOP_PAGER"]):?>
			<?=$arResult["NAV_STRING"]?><br />
		<?php endif;?>

		<div class="row">
			<?php foreach($arResult["ITEMS"] as $arItem):?>
				<?php 
					$this->AddEditAction(
						$arItem['ID'],
						$arItem['EDIT_LINK'],
						CIBlock::GetArrayByID(
							$arItem["IBLOCK_ID"],
							"ELEMENT_EDIT"
						)
					);
					$this->AddDeleteAction(
						$arItem['ID'],
						$arItem['DELETE_LINK'],
						CIBlock::GetArrayByID(
							$arItem["IBLOCK_ID"],
							"ELEMENT_DELETE"),
						array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))
					);
				?>
				<div class="news-list-item mb-2 col-sm" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<div class="card">
						<?php if($arParams["DISPLAY_PICTURE"]!="N"):?>

							<?php 
							if ($arItem["VIDEO"])
							{
								?>
								<div class="news-list-item-embed-video embed-responsive embed-responsive-16by9">
									<iframe
										class="embed-responsive-item"
										src="<?php echo $arItem["VIDEO"]?>"
										frameborder="0"
										allowfullscreen=""
										></iframe>
								</div>
								<?php 
							}
							else if ($arItem["SOUND_CLOUD"])
							{
								?>
								<div class="news-list-item-embed-audio embed-responsive embed-responsive-16by9">
									<iframe
										class="embed-responsive-item"
										width="100%"
										scrolling="no"
										frameborder="no"
										src="https://w.soundcloud.com/player/?url=<?php echo urlencode($arItem["SOUND_CLOUD"])?>&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"
										></iframe>
								</div>
								<?php 
							}
							else if ($arItem["SLIDER"] && count($arItem["SLIDER"]) > 1)
							{
								?>
								<div class="news-list-item-embed-slider">
									<div class="news-list-slider-container" style="width: <?php 
									echo count($arItem["SLIDER"]) * 100 ?>%;left: 0;">
										<?php 
										foreach ($arItem["SLIDER"] as $file):?>
											<div class="news-list-slider-slide">
												<img src="<?= $file["SRC"] ?>" alt="<?= $file["DESCRIPTION"] ?>">
											</div>
										<?php endforeach ?>
									</div>
									<div class="news-list-slider-arrow-container-left">
										<div class="news-list-slider-arrow"><i class="fa fa-angle-left"></i></div>
									</div>
									<div class="news-list-slider-arrow-container-right">
										<div class="news-list-slider-arrow"><i class="fa fa-angle-right"></i></div>
									</div>
									<ul class="news-list-slider-control">
										<?php 
										foreach ($arItem["SLIDER"] as $i => $file):?>
											<li rel="<?= ($i + 1) ?>" <?php 
											if (!$i)
												echo 'class="current"' ?>><span></span></li>
										<?php endforeach ?>
									</ul>
								</div>
								<script type="text/javascript">
								BX.ready(function () {
									new JCNewsSlider('<?=CUtil::JSEscape($this->GetEditAreaId($arItem['ID']));?>', {
										imagesContainerClassName: 'news-list-slider-container',
										leftArrowClassName: 'news-list-slider-arrow-container-left',
										rightArrowClassName: 'news-list-slider-arrow-container-right',
										controlContainerClassName: 'news-list-slider-control'
									});
								});
							</script>
								<?php 
							}
							else if ($arItem["SLIDER"])
							{
								?>
								<div class="news-list-item-embed-img">
									<?php  if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]))
									{
										?>
										<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
											<img
												class="card-img-top"
												src="<?= $arItem["SLIDER"][0]["SRC"] ?>"
												width="<?= $arItem["SLIDER"][0]["WIDTH"] ?>"
												height="<?= $arItem["SLIDER"][0]["HEIGHT"] ?>"
												alt="<?= $arItem["SLIDER"][0]["ALT"] ?>"
												title="<?= $arItem["SLIDER"][0]["TITLE"] ?>"
											/>
										</a>
										<?php 
									}
									else
									{
										?>
										<img
											class="card-img-top"
											src="<?= $arItem["SLIDER"][0]["SRC"] ?>"
											width="<?= $arItem["SLIDER"][0]["WIDTH"] ?>"
											height="<?= $arItem["SLIDER"][0]["HEIGHT"] ?>"
											alt="<?= $arItem["SLIDER"][0]["ALT"] ?>"
											title="<?= $arItem["SLIDER"][0]["TITLE"] ?>"
										/>
										<?php 
									}
									?>
								</div>
								<?php 
							}
							else if (is_array($arItem["PREVIEW_PICTURE"]))
							{
								if (!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]))
								{
									?>
									<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
										<img
											class="card-img-top"
											src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
											alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
											title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
										/>
									</a>
									<?php 
								}
								else
								{
									?>
									<img
										src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
										class="card-img-top"
										alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
										title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"
									/>
								<?php 
								}
							}
							?>

						<?php endif;?>

						<div class="card-body">

							<?php if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
								<h4 class="card-title">
									<?php if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
										<a href="<?php echo $arItem["DETAIL_PAGE_URL"]?>"><?php echo $arItem["NAME"]?></a>
									<?php else:?>
										<?php echo $arItem["NAME"]?>
									<?php endif;?>
								</h4>
							<?php endif;?>

							<?php if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
								<p class="card-text"><?php echo $arItem["PREVIEW_TEXT"];?></p>
							<?php endif;?>

							<?php foreach($arItem["FIELDS"] as $code=>$value):?>
								<?php if($code == "SHOW_COUNTER"):?>
									<div class="news-list-view news-list-post-params">
										<span class="news-list-icon news-list-icon-eye"></span>
										<span class="news-list-param"><?=GetMessage("IBLOCK_FIELD_".$code)?>: </span>
										<span class="news-list-value"><?=intval($value);?></span>
									</div>
								<?php elseif(
									$value
									&& (
										$code == "SHOW_COUNTER_START"
										|| $code == "DATE_ACTIVE_FROM"
										|| $code == "ACTIVE_FROM"
										|| $code == "DATE_ACTIVE_TO"
										|| $code == "ACTIVE_TO"
										|| $code == "DATE_CREATE"
										|| $code == "TIMESTAMP_X"
									)
								):?>
									<?php 
									$value = CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($value, CSite::GetDateFormat()));
									?>
									<div class="news-list-view news-list-post-params">
										<span class="news-list-icon news-list-icon-calendar"></span>
										<span class="news-list-param"><?=GetMessage("IBLOCK_FIELD_".$code)?>: </span>
										<span class="news-list-value"><?=$value;?></span>
									</div>
								<?php elseif($code == "TAGS" && $value):?>
									<div class="news-list-view news-list-post-params">
										<span class="news-list-icon news-list-icon-tag"></span>
										<span class="news-list-param"><?=GetMessage("IBLOCK_FIELD_".$code)?>:</span>
										<span class="news-list-value"><?=$value;?></span>
									</div>
								<?php elseif(
									$value
									&& (
										$code == "CREATED_USER_NAME"
										|| $code == "USER_NAME"
									)
								):?>
									<div class="news-list-view news-list-post-params">
										<span class="news-list-icon news-list-icon-user"></span>
										<span class="news-list-param"><?=GetMessage("IBLOCK_FIELD_".$code)?>:</span>
										<span class="news-list-value"><?=$value;?></span>
									</div>
								<?php elseif ($value != ""):?>
									<div class="news-list-view news-list-post-params">
										<span class="news-list-icon"></span>
										<span class="news-list-param"><?=GetMessage("IBLOCK_FIELD_".$code)?>:</span>
										<span class="news-list-value"><?=$value;?></span>
									</div>
								<?php endif;?>
							<?php endforeach;?>

							<?php foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
								<?php 
								if(is_array($arProperty["DISPLAY_VALUE"]))
									$value = implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
								else
									$value = $arProperty["DISPLAY_VALUE"];
								?>
								<?php if($arProperty["CODE"] == "FORUM_MESSAGE_CNT"):?>
									<div class="news-list-view news-list-post-params">
										<span class="news-list-icon news-list-icon-comments"></span>
										<span class="news-list-param"><?=$arProperty["NAME"]?>:<?=$value;?></span>
										<span class="news-list-value"><?=$value;?></span>
									</div>
								<?php elseif ($value != ""):?>
									<div class="news-list-view news-list-post-params">
										<span class="news-list-icon"></span>
										<span class="news-list-param"><?=$arProperty["NAME"]?>:</span>
										<span class="news-list-value"><?=$value;?></span>
									</div>
								<?php endif;?>
							<?php endforeach;?>
							<div class="d-flex justify-content-between">

								<?php if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
									<div class="news-list-view news-list-post-params">
										<span class="news-list-icon news-list-icon-calendar"></span>
										<span class="news-list-param"><?php echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
									</div>
								<?php endif?>

								<?php if($arParams["USE_RATING"]=="Y"):?>
									<div>
										<?php $APPLICATION->IncludeComponent(
											"bitrix:iblock.vote",
											"bootstrap_v4",
											Array(
												"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
												"IBLOCK_ID" => $arParams["IBLOCK_ID"],
												"ELEMENT_ID" => $arItem["ID"],
												"MAX_VOTE" => $arParams["MAX_VOTE"],
												"VOTE_NAMES" => $arParams["VOTE_NAMES"],
												"CACHE_TYPE" => $arParams["CACHE_TYPE"],
												"CACHE_TIME" => $arParams["CACHE_TIME"],
												"DISPLAY_AS_RATING" => $arParams["DISPLAY_AS_RATING"],
												"SHOW_RATING" => "N",
											),
											$component
										);?>
									</div>
								<?php endif?>
							</div>
							<div class="d-flex justify-content-between align-items-center">
								<?php if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
									<div class="news-list-more">
										<a class="btn btn-primary btn-sm" href="<?php echo $arItem["DETAIL_PAGE_URL"]?>"><?php echo GetMessage("CT_BNL_GOTO_DETAIL")?></a>
									</div>
								<?php endif;?>
								<?php  if ($arParams["USE_SHARE"] == "Y")
								{
									?>
									<div class="text-right">
										<?php 
										$APPLICATION->IncludeComponent("bitrix:main.share", $arParams["SHARE_TEMPLATE"], array(
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
									</div>
									<?php 
								}
								?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach;?>
		</div>

		<?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
			<?=$arResult["NAV_STRING"]?>
		<?php endif;?>
	</div>
</div>
