<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if (
	isset($_REQUEST["FORMAT"])
	&& $_REQUEST["FORMAT"] == 'json'
)
{
	$APPLICATION->RestartBuffer();
	header('Content-Type: application/x-javascript; charset='.LANG_CHARSET);
	CMain::FinalActions(CUtil::PhpToJSObject($arResult));
	die();
}
else
{
if(!empty($arResult["CATEGORIES"])):?>
	<table class="title-search-result">
		<colgroup>
			<col width="150px">
			<col width="*">
		</colgroup>
		<tbody>
			<?php foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
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
						<td class="title-search-all"><a href="<?php echo $arItem["URL"]?>"><?php echo $arItem["NAME"]?></td>
					<?php elseif(isset($arItem["ICON"])):?>
						<td class="title-search-item"><a href="<?php echo $arItem["URL"]?>"><?php echo $arItem["NAME"]?></td>
					<?php else:?>
						<td class="title-search-more"><a href="<?php echo $arItem["URL"]?>"><?php echo $arItem["NAME"]?></td>
					<?php endif;?>
				</tr>
				<?php endforeach;?>
			<?php endforeach;?>
			<tr>
				<th class="title-search-separator">&nbsp;</th>
				<td class="title-search-separator">&nbsp;</td>
			</tr>
		</tbody>
	</table>
<?php endif;
}
//echo "<pre>",htmlspecialcharsbx(print_r($arResult,1)),"</pre>";
?>