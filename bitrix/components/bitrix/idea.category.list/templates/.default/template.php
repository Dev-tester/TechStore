<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php if (!empty($arResult["ITEMS"])):?>
	<ul id="bx-idea-left-menu" class="bx-idea-left-menu">
	<?php 
	$previousLevel = 0;
	foreach($arResult["ITEMS"] as $arItem):
	//Edit buttons
	$this->AddEditAction($arItem["ID"], $arItem['EDIT_LINK']["ACTION_URL"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "SECTION_EDIT"));
	$this->AddDeleteAction($arItem["ID"], $arItem['DELETE_LINK']["ACTION_URL"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
			<?php if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
					<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
			<?php endif?>
			<?php if ($arItem["IS_PARENT"] && $arItem["DEPTH_LEVEL"]==1):?>
					<li class="bx-idea-left-menu-li<?php //if($arItem["SELECTED"]):?> bx-idea-left-menu-open<?php //endif;?>"><?php /*<a class="bx-idea-left-menu-link"><?=$arItem["TEXT"]?></a>*/?>
				<a id="<?=$this->GetEditAreaId($arItem["ID"]);?>" href="<?=$arItem["LINK"]?>" class="bx-idea-left-menu-link<?php if($arItem["SELECTED"]):?> bx-idea-active-menu<?php endif;?>"><?=$arItem["TEXT"]?></a>
						<span class="bx-idea-left-menu-bullet"></span>
							<ul class="bx-idea-left-menu_2">
			<?php else:?>
					<?php if($arItem["DEPTH_LEVEL"]==1):?>
						<li class="bx-idea-left-menu-li">
							<a id="<?=$this->GetEditAreaId($arItem["ID"]);?>" class="bx-idea-left-menu-link<?php if($arItem["SELECTED"]):?> bx-idea-active-menu<?php endif;?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
						</li>
					<?php else:?>
						<li class="bx-idea-left-menu-li_2" style="margin-left: <?=($arItem["DEPTH_LEVEL"]-2)*12?>px!important">
							<a id="<?=$this->GetEditAreaId($arItem["ID"]);?>" class="bx-idea-left-menu-link<?php if($arItem["SELECTED"]):?> bx-idea-active-menu<?php endif;?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
						</li>
					<?php endif;?>
			<?php endif?>
			<?php $previousLevel = $arItem["DEPTH_LEVEL"]>2?2:$arItem["DEPTH_LEVEL"];?>
	<?php endforeach?>
	<?php if ($previousLevel > 1)://close last item tags?>
			<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
	<?php endif?>
	</ul>
<?php endif?>