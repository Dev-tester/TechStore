<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="main-resume job-list">
<div class="job-list-title"><h2><?=$arParams["NAME_BLOCK"]?></h2></div>
<?php foreach($arResult["ITEMS"] as $arItem):?>
	<?php 
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"));
	?>
	<div class="job-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<a href="<?php echo $arItem["DETAIL_PAGE_URL"]?>"><?php echo $arItem["NAME"]?></a>
	</div>
<?php endforeach;?>
</div>
