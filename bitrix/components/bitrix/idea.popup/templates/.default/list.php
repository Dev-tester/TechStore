<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
$arCategoryList = CIdeaManagment::getInstance()->Idea()->GetCategoryList();
$arRootCategory = array(array("CODE" => "SYS_ALL", "NAME" => GetMessage("IDEA_POPUP_CATEGORY_ALL"), "IS_CATEGOTY" => "N"));
foreach($arCategoryList as $key => $arCategory)
	if(intval($arCategory["IBLOCK_SECTION_ID"]) ==0)
		$arRootCategory[$key] = $arCategory;

$arRootCategory = array_slice($arRootCategory, 0, $arParams["CATEGORIES_CNT"]);
?>
<div id="idea-list-container">
	<div style="margin-right: 8px; padding-top:18px;"><?php 
		?><input id="idea-field-common-show-add-form" class="idea-field-common" <?php 
			?>onclick="BX.Idea.add();" <?php 
			?>type="text" readonly="true" placeholder="<?=GetMessage("IDEA_INPUT_TITLE_IDEA")?>"></div>
	<div style="padding-top: 20px;"></div>
	<div class="status-box-l">
		<div class="status-box-r">
			<div class="status-box-m">
				<?php $i=0; foreach($arRootCategory as $arCategory):?>
				<div class="status-item-categoty status-item<?php if($i++==0):?>-selected<?php endif;?>" onclick="BX.Idea.set(this)" id="idea-category-list-<?=ToLower($arCategory["CODE"])?>">
					<div>
						<div>
							<a><?=$arCategory["NAME"]?></a>
						</div>
					</div>
				</div>
				<?php endforeach;?>
				<div class="status-item status-item-more">
					<div>
						<div>
							<a target="_blank" href="<?=$arParams["PATH_IDEA_INDEX"]?>"><?=GetMessage("IDEA_PATH_IDEA_INDEX")?></a>
						</div>
					</div>
				</div>
				<br clear="all">
			</div>
		</div>
	</div>
	<div id="idea-category-list-box">
		<?php $i = 0; foreach($arRootCategory as $arCategory):?>
			<div class="idea-category-list" id="idea-category-list-<?=ToLower($arCategory["CODE"])?>-content" <?php if($i++>0):?>style="display:none;"<?php endif;?>>
				<?php 
				$arFilter = array();
				if($arCategory["IS_CATEGOTY"] != "N")
					$arFilter = array(
						"IDEA_PARENT_CATEGORY_CODE" => ToUpper($arCategory["CODE"])
					);
				?>
				<?php $APPLICATION->IncludeComponent(
						"bitrix:idea.list",
						"light",
						Array(
								"RATING_TEMPLATE" => $arParams['RATING_TEMPLATE'],
								"SORT_BY1" => "RATING_TOTAL_VALUE",
								"IBLOCK_CATEGORIES" => $arParams["IBLOCK_CATEGORIES"],
								"EXT_FILTER" => $arFilter,
								"MESSAGE_COUNT"			=> $arParams["LIST_MESSAGE_COUNT"],
								"PATH_TO_POST"			=> $arParams["PATH_IDEA_POST"],
								"BLOG_URL"				=> $arParams["BLOG_URL"],
								"CACHE_TYPE"			=> "Y",
								"CACHE_TIME"			=> 3600,
								"SET_NAV_CHAIN"			=> "N",
								"POST_PROPERTY_LIST"	=> CIdeaManagment::getInstance()->GetUserFieldsArray(),
								"DATE_TIME_FORMAT"		=> "d.m.Y",
								"NAME_TEMPLATE" 		=> $arParams["NAME_TEMPLATE"],
								"SHOW_LOGIN" 			=> $arParams["SHOW_LOGIN"],
								"SHOW_RATING" => $arParams["SHOW_RATING"],
						),
						$component
				);
				?>
			</div>
		<?php endforeach;?>
	</div>
</div>