<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="catalog-compare-result">
<a name="compare_table"></a>
	<noindex><p>
	<?php if($arResult["DIFFERENT"]):
		?><a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("DIFFERENT=N",array("DIFFERENT")))?>" rel="nofollow"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></a><?php 
	else:
		?><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?><?php 
	endif
	?>&nbsp;|&nbsp;<?php 
	if(!$arResult["DIFFERENT"]):
		?><a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("DIFFERENT=Y",array("DIFFERENT")))?>" rel="nofollow"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></a><?php 
	else:
		?><?=GetMessage("CATALOG_ONLY_DIFFERENT")?><?php 
	endif?>
	</p></noindex>
	<?php if(count($arResult["DELETED_PROPERTIES"])>0):?>
		<noindex><p>
		<?=GetMessage("CATALOG_REMOVED_FEATURES")?>:
		<?php foreach($arResult["DELETED_PROPERTIES"] as $arProperty):?>
			<a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=ADD_FEATURE&pr_code=".$arProperty["CODE"],array("pr_code","action")))?>" rel="nofollow"><?=$arProperty["NAME"]?></a>
		<?php endforeach?>
		</p></noindex>
	<?php endif?>
	<?php if(count($arResult["SHOW_PROPERTIES"])>0):?>
		<p>
		<form action="<?=$APPLICATION->GetCurPage()?>" method="get">
		<?=GetMessage("CATALOG_REMOVE_FEATURES")?>:<br />
		<?php foreach($arResult["SHOW_PROPERTIES"] as $arProperty):?>
			<input type="checkbox" name="pr_code[]" value="<?=$arProperty["CODE"]?>" /><?=$arProperty["NAME"]?><br />
		<?php endforeach?>
		<input type="hidden" name="action" value="DELETE_FEATURE" />
		<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
		<input type="submit" value="<?=GetMessage("CATALOG_REMOVE_FEATURES")?>"/>
		</form>
		</p>
	<?php endif?>
<br />
<form action="<?=$APPLICATION->GetCurPage()?>" method="get">
	<table class="data-table" cellspacing="0" cellpadding="0" border="0">
		<thead>
		<tr>
			<td valign="top">&nbsp;</td>
			<?php foreach($arResult["ITEMS"] as $arElement):?>
				<td valign="top" width="<?=round(100/count($arResult["ITEMS"]))?>%">
					<input type="checkbox" name="ID[]" value="<?=$arElement["ID"]?>" />
				</td>
			<?php endforeach?>
		</tr>
		<?php foreach($arResult["ITEMS"][0]["FIELDS"] as $code=>$field):?>
		<tr>
			<td valign="top" nowrap><?=GetMessage("IBLOCK_FIELD_".$code)?></td>
			<?php foreach($arResult["ITEMS"] as $arElement):?>
				<td valign="top">
					<?php switch($code):
						case "NAME":
							?><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement[$code]?></a><?php 
							if($arElement["CAN_BUY"]):
								?><noindex><br /><a href="<?=$arElement["BUY_URL"]?>" rel="nofollow"><?=GetMessage("CATALOG_COMPARE_BUY"); ?></a></noindex><?php 
							elseif((count($arResult["PRICES"]) > 0) || is_array($arElement["PRICE_MATRIX"])):
								?><br /><?=GetMessage("CATALOG_NOT_AVAILABLE")?><?php 
							endif;
							break;
						case "PREVIEW_PICTURE":
						case "DETAIL_PICTURE":
							if(is_array($arElement["FIELDS"][$code])):?>
								<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img border="0" src="<?=$arElement["FIELDS"][$code]["SRC"]?>" width="<?=$arElement["FIELDS"][$code]["WIDTH"]?>" height="<?=$arElement["FIELDS"][$code]["HEIGHT"]?>" alt="<?=$arElement["FIELDS"][$code]["ALT"]?>" /></a>
							<?php endif;
							break;
						default:
							echo $arElement["FIELDS"][$code];
							break;
					endswitch;
					?>
				</td>
			<?php endforeach?>
		</tr>
		<?php endforeach;?>
		</thead>
		<?php foreach($arResult["ITEMS"][0]["PRICES"] as $code=>$arPrice):?>
			<?php if($arPrice["CAN_ACCESS"]):?>
			<tr>
				<th valign="top" nowrap><?=$arResult["PRICES"][$code]["TITLE"]?></th>
				<?php foreach($arResult["ITEMS"] as $arElement):?>
					<td valign="top">
						<?php if($arElement["PRICES"][$code]["CAN_ACCESS"]):?>
							<b><?=$arElement["PRICES"][$code]["PRINT_DISCOUNT_VALUE"]?></b>
						<?php endif;?>
					</td>
				<?php endforeach?>
			</tr>
			<?php endif;?>
		<?php endforeach;?>
		<?php foreach($arResult["SHOW_PROPERTIES"] as $code=>$arProperty):
			$arCompare = Array();
			foreach($arResult["ITEMS"] as $arElement)
			{
				$arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
				if(is_array($arPropertyValue))
				{
					sort($arPropertyValue);
					$arPropertyValue = implode(" / ", $arPropertyValue);
				}
				$arCompare[] = $arPropertyValue;
			}
			$diff = (count(array_unique($arCompare)) > 1 ? true : false);
			if($diff || !$arResult["DIFFERENT"]):?>
				<tr>
					<th valign="top" nowrap>&nbsp;<?=$arProperty["NAME"]?>&nbsp;</th>
					<?php foreach($arResult["ITEMS"] as $arElement):?>
						<?php if($diff):?>
						<td valign="top">&nbsp;
							<?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
						</td>
						<?php else:?>
						<th valign="top">&nbsp;
							<?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
						</th>
						<?php endif?>
					<?php endforeach?>
				</tr>
			<?php endif?>
		<?php endforeach;?>
	</table>
	<br />
	<input type="submit" value="<?=GetMessage("CATALOG_REMOVE_PRODUCTS")?>" />
	<input type="hidden" name="action" value="DELETE_FROM_COMPARE_RESULT" />
	<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
</form>
<br />
<?php if(count($arResult["ITEMS_TO_ADD"])>0):?>
<p>
<form action="<?=$APPLICATION->GetCurPage()?>" method="get">
	<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
	<input type="hidden" name="action" value="ADD_TO_COMPARE_RESULT" />
	<select name="id">
	<?php foreach($arResult["ITEMS_TO_ADD"] as $ID=>$NAME):?>
		<option value="<?=$ID?>"><?=$NAME?></option>
	<?php endforeach?>
	</select>
	<input type="submit" value="<?=GetMessage("CATALOG_ADD_TO_COMPARE_LIST")?>" />
</form>
</p>
<?php endif?>
</div>
