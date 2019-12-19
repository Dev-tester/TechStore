<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<table class="currency-list">
<?php if ($arParams["SHOW_CB"] == "Y"):?>
	<tr>
		<td colspan="3"><b><?=GetMessage("CURRENCY_SITE")?></b></td>
	</tr>
<?php endif;?>
<?php foreach ($arResult["CURRENCY"] as $key => $arCurrency):?>
	<tr>
		<td><?=$arCurrency["FROM"]?></td>
		<td>=</td>
		<td><?=$arCurrency["BASE"]?></td>
	</tr>
<?php endforeach?>
<?php if (is_array($arResult["CURRENCY_CBRF"]) && $arParams["SHOW_CB"] == "Y"):?>
	<tr>
		<td colspan="3"><b><?=GetMessage("CURRENCY_CBRF")?></b></td>
	</tr>
	<?php foreach ($arResult["CURRENCY_CBRF"] as $arCurrency):?>
	<tr>
		<td><?=$arCurrency["FROM"]?></td>
		<td>=</td>
		<td><?=$arCurrency["BASE"]?></td>
	</tr>
	<?php endforeach?>
<?php endif?>
</table>