<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

if(!empty($arResult["CATEGORIES"])):?>
	<table class="title-search-result webdav-title-search-result">
		<?php foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
		<?php if ($category_id === 'all') continue; ?>
			<tr>
				<th class="title-search-separator">&nbsp;</th>
				<td class="title-search-separator">&nbsp;</td>
			</tr>
			<?php foreach($arCategory["ITEMS"] as $i => $arItem):?>
			<tr>
				<?php if($i == 0):?>
					<th>&nbsp;<?php echo $arCategory["TITLE"]?></th>
				<?php else:?>
					<th>&nbsp;</th>
				<?php endif?>

				<?php if($category_id === "all"):?>
					<td class="title-search-all"><a href="<?php echo $arItem["URL"]?>"><?php echo $arItem["NAME"]?></a></td>
				<?php elseif(isset($arItem["ICON"])):?>
					<td class="title-search-item"><img src="<?php echo $arItem["ICON"]?>"><a href="<?php echo $arItem["URL"]?>"><?php echo $arItem["NAME"]?></a></td>
				<?php else:?>
					<td class="title-search-more"><a href="javascript:jsControl_<?=$INPUT_ID?>.INPUT.form.submit();"><?php echo $arItem["NAME"]?></a></td>
				<?php endif;?>
			</tr>
			<?php endforeach;?>
		<?php endforeach;?>
		<tr>
			<th class="title-search-separator">&nbsp;</th>
			<td class="title-search-separator">&nbsp;</td>
		</tr>
	</table>
<?php endif;
//echo "<pre>",htmlspecialcharsbx(print_r($arResult,1)),"</pre>";
?>
