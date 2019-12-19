<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<b><?= GetMessage("SALE_NOTIFY_TITLE")?></b><br /><br />
<table class="sale_basket_basket data-table">
	<tr>
		<?php if (in_array("NAME", $arParams["COLUMNS_LIST"])):?>
			<th align="center"><?php echo GetMessage("SALE_NAME")?></th>
		<?php endif;?>
		<?php if (in_array("PROPS", $arParams["COLUMNS_LIST"])):?>
			<th align="center"><?php echo GetMessage("SALE_PROPS")?></th>
		<?php endif;?>
		<?php if (in_array("DELETE", $arParams["COLUMNS_LIST"])):?>
			<th align="center"><?php echo GetMessage("SALE_DELETE")?></th>
		<?php endif;?>
	</tr>
	<?php 
	foreach($arResult["ITEMS"]["ProdSubscribe"] as $arBasketItems)
	{
		?>
		<tr>
			<?php if (in_array("NAME", $arParams["COLUMNS_LIST"])):?>
				<td><?php 
				if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):
					?><a href="<?=$arBasketItems["DETAIL_PAGE_URL"] ?>"><?php 
				endif;
				?><b><?=$arBasketItems["NAME"]?></b><?php 
				if (strlen($arBasketItems["DETAIL_PAGE_URL"])>0):
					?></a><?php 
				endif;
				?>
				</td>
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
			<?php if (in_array("DELETE", $arParams["COLUMNS_LIST"])):?>
				<td align="center"><input type="checkbox" name="DELETE_<?php echo $arBasketItems["ID"] ?>" value="Y"></td>
			<?php endif;?>
		</tr>
		<?php 
	}
	?>
</table>

<br />
<div width="30%">
	<input type="submit" value="<?= GetMessage("SALE_REFRESH") ?>" name="BasketRefresh"><br />
	<small><?= GetMessage("SALE_REFRESH_NOTIFY_DESCR") ?></small>
</div>
<?