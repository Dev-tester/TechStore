<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="job-section">
<?php if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?php endif;?>
<table cellpadding="0" cellspacing="0" border="0">
	<thead>
		<tr>
			<th><?=GetMessage('TH_DATE')?></th>
			<th><?=GetMessage('TH_POSITION')?></th>
			<?php foreach($arParams['PROPERTY_CODE'] as $codeProperty):?>
			<th><?php echo $arResult["ITEMS"][0]["PROPERTIES"][$codeProperty]["NAME"];?></th>
			<?php endforeach?>
		</tr>
	</thead>
	<?php foreach($arResult["ITEMS"] as $cell=>$arElement):?>
	<?php 
	$this->AddEditAction($arElement['ID']."_".$q, $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arElement['ID']."_".$q, $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"));
	?>
	<tr>
		<td valign="top" class="job-date"><?=$arElement["ACTIVE_FROM"]?></td>
		<td valign="top"  id="<?=$this->GetEditAreaId($arElement['ID']."_".$q);?>"><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a></td>
		<?php foreach($arParams['PROPERTY_CODE'] as $codeProperty):?>	
		<td valign="top">	
			<?php 
				$arProperty = $arElement["DISPLAY_PROPERTIES"][$codeProperty];
				if(is_array($arProperty)){
				if(is_array($arProperty["DISPLAY_VALUE"]))
					echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
				else
					echo '<nobr>' . $arProperty["DISPLAY_VALUE"] . '</nobr>';?><br />
				<?php }?>
		</td>
		<?php endforeach?>
		
	</tr>
	<?php endforeach;?>
</table>
<?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?php endif;?>
</div>