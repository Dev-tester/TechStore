<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
$this->SetViewTarget("sidebar", 100);
$frame = $this->createFrame()->begin();

$this->addExternalCss(SITE_TEMPLATE_PATH."/css/sidebar.css");

if (count($arResult["ITEMS"]) > 0):
?>

<div class="sidebar-widget sidebar-widget-calendar">
	<div class="sidebar-widget-top">
		<div class="sidebar-widget-top-title"><?=GetMessage("WIDGET_CALENDAR_TITLE")?></div>
		<a href="<?=$arParams["DETAIL_URL"]?>?EVENT_ID=NEW" class="plus-icon"></a>
	</div>
	<div class="sidebar-widget-content">
	<?php 
	foreach($arResult["ITEMS"] as $i => $arItem):?>
		<a  href="<?=$arItem["_DETAIL_URL"]?>" class="sidebar-widget-item<?php if($i == 0):?> widget-first-item<?php endif?><?php if($i == count($arResult["ITEMS"])-1):?> widget-last-item<?php endif?>">
			<span class="calendar-item-date"><?= $arItem["~FROM_TO_HTML"]?></span>
			<span class="calendar-item-text">
				<span class="calendar-item-link"><?=htmlspecialcharsbx($arItem["NAME"])?></span>
			</span>
			<span class="calendar-item-icon">
				<span class="calendar-item-icon-day"><?=($arItem["WEEK_DAY"])?></span>
				<span class="calendar-item-icon-date"><?=$arItem["ICON_DAY"]?></span>
			</span>
		</a>
	<?php endforeach?>
	</div>
</div><?php 
else:
	echo " "; //Buffering hack
endif;
$frame->end();
$this->EndViewTarget();