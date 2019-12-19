<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if($arParams["SET_NAV_CHAIN"]=="Y")
	$APPLICATION->AddChainItem(GetMessage("IDEA_SUBSCRIBE_MINE_TITLE"), $arResult["~PATH_TO_USER_SUBSCRIBE"]);
if($arParams["SET_TITLE"]=="Y")
	$APPLICATION->SetTitle(GetMessage("IDEA_SUBSCRIBE_MINE_TITLE"));
?>
<div class="idea-managment-content">
	<?php if(!empty($arResult["ACTIONS"])):?>
	<?php $APPLICATION->IncludeComponent(
		"bitrix:main.interface.toolbar",
		"",
		array(
			"BUTTONS" => $arResult["ACTIONS"]
		),
		$component
	);?>
	<?php endif;?>
	<?php //Side bar tools?>
	<?php $this->SetViewTarget("sidebar", 100)?>
		<?php $APPLICATION->IncludeComponent(
				"bitrix:idea.category.list",
				"",
				Array(
					"IBLOCK_CATEGORIES" => $arParams["IBLOCK_CATEGORIES"],
					"PATH_TO_CATEGORY_1" => $arResult["PATH_TO_CATEGORY_1"],
					"PATH_TO_CATEGORY_2" => $arResult["PATH_TO_CATEGORY_2"],
				),
				$component
		);
		?>
		<?php $APPLICATION->IncludeComponent(
				"bitrix:idea.statistic",
				"",
				Array(
					"BLOG_URL" => $arResult["VARIABLES"]["blog"],
					"PATH_WITH_STATUS" => $arResult["PATH_TO_STATUS_0"],
					"PATH_TO_INDEX" => $arResult["PATH_TO_INDEX"],
				),
				$component
		);
		?>
		<?php $APPLICATION->IncludeComponent(
				"bitrix:idea.tags",
				"",
				Array(
					"BLOG_URL" => $arParams["BLOG_URL"],
					"PATH_TO_BLOG_CATEGORY" => $arResult["PATH_TO_BLOG_CATEGORY"],
					"SET_NAV_CHAIN" => $arParams["SET_NAV_CHAIN"],
					"TAGS_COUNT" => $arParams["TAGS_COUNT"]
				),
				$component
		);
		?>
	<?php $this->EndViewTarget();?>
	<?php $this->SetViewTarget("idea_body", 100)?>
		<?php $APPLICATION->IncludeComponent(
			"bitrix:idea.subscribe",
			"",
			array(
				"PATH_TO_USER_IDEAS" => $arResult["PATH_TO_USER_IDEAS"],
				"NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"]
			),
			$component
		);?>
	<?php $this->EndViewTarget();?>
	<?php if($arResult["IS_CORPORTAL"] != "Y"):?>
		<div class="idea-managment-content-left">
			<?php $APPLICATION->ShowViewContent("sidebar")?>
		</div>
	<?php endif;?>
	<div class="idea-managment-content-right">
		<?php $APPLICATION->ShowViewContent("idea_filter")?>
		<?php $APPLICATION->ShowViewContent("idea_body")?>
	</div>
	<div style="clear:both;"></div>
</div>