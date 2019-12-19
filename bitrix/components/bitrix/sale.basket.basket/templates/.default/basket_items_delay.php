<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<b><?= GetMessage("SALE_OTLOG_TITLE")?></b><br /><br />
<table class="sale_basket_basket data-table">
	<tr>
		<?php if (in_array("NAME", $arParams["COLUMNS_LIST"])):?>
			<th align="center"><?= GetMessage("SALE_NAME")?></th>
		<?php endif;?>
		<?php if (in_array("PROPS", $arParams["COLUMNS_LIST"])):?>
			<th align="center"><?= GetMessage("SALE_PROPS")?></th>
		<?php endif;?>
		<?php if (in_array("PRICE", $arParams["COLUMNS_LIST"])):?>
			<th align="center"><?= GetMessage("SALE_PRICE")?></th>
		<?php endif;?>
		<?php if (in_array("TYPE", $arParams["COLUMNS_LIST"])):?>
			<th align="center"><?= GetMessage("SALE_PRICE_TYPE")?></th>
		<?php endif;?>
		<?php if (in_array("QUANTITY", $arParams["COLUMNS_LIST"])):?>
			<th align="center"><?= GetMessage("SALE_QUANTITY")?></th>
		<?php endif;?>
		<?php if (in_array("DELETE", $arParams["COLUMNS_LIST"])):?>
			<th align="center"><?= GetMessage("SALE_DELETE")?></th>
		<?php endif;?>
		<?php if (in_array("DELAY", $arParams["COLUMNS_LIST"])):?>
			<th align="center"><?= GetMessage("SALE_OTLOG")?></th>
		<?php endif;?>
		<?php if (in_array("WEIGHT", $arParams["COLUMNS_LIST"])):?>
			<th align="center"><?= GetMessage("SALE_WEIGHT")?></th>
		<?php endif;?>
	</tr>
	<?php 
	foreach($arResult["ITEMS"]["DelDelCanBuy"] as $arBasketItems)
	{
		?>
		<tr>
			<?php if (in_array("NAME", $arParams["COLUMNS_LIST"])):?>
				<td><?php 
				if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):
					?><a href="<?php echo $arBasketItems["DETAIL_PAGE_URL"] ?>"><?php 
				endif;
				?><b><?php echo $arBasketItems["NAME"]?></b><?php 
				if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):
					?></a><?php 
				endif;
				?></td>
			<?php endif;?>
			<?php if (in_array("PROPS", $arParams["COLUMNS_LIST"])):?>
				<td>
				<?php 
				foreach($arBasketItems["PROPS"] as $val)
				{
					echo $val["NAME"].": ".$val["VALUE"]."<br />";
				}
				?>
				</td>
			<?php endif;?>
			<?php if (in_array("PRICE", $arParams["COLUMNS_LIST"])):?>
				<td align="right"><?=$arBasketItems["PRICE_FORMATED"]?></td>
			<?php endif;?>
			<?php if (in_array("TYPE", $arParams["COLUMNS_LIST"])):?>
				<td><?php echo $arBasketItems["NOTES"]?></td>
			<?php endif;?>
			<?php if (in_array("QUANTITY", $arParams["COLUMNS_LIST"])):?>
				<td align="center"><?php echo $arBasketItems["QUANTITY"]?></td>
			<?php endif;?>
			<?php if (in_array("DELETE", $arParams["COLUMNS_LIST"])):?>
				<td align="center"><input type="checkbox" name="DELETE_<?php echo $arBasketItems["ID"] ?>" value="Y"></td>
			<?php endif;?>
			<?php if (in_array("DELAY", $arParams["COLUMNS_LIST"])):?>
				<td align="center"><input type="checkbox" name="DELAY_<?php echo $arBasketItems["ID"] ?>" value="Y" checked></td>
			<?php endif;?>
			<?php if (in_array("WEIGHT", $arParams["COLUMNS_LIST"])):?>
				<td align="right"><?php echo $arBasketItems["WEIGHT_FORMATED"] ?></td>
			<?php endif;?>
		</tr>
		<?php 
	}
	?>
</table>
<br />
<div width="30%">
	<input type="submit" value="<?= GetMessage("SALE_REFRESH")?>" name="BasketRefresh"><br />
	<small><?= GetMessage("SALE_REFRESH_DESCR")?></small><br />
</div>
<br />
<?