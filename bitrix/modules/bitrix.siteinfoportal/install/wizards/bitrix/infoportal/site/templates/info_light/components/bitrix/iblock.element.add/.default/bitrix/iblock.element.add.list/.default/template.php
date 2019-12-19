<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

//echo "<pre>"; print_r($arParams); echo "</pre>";
//echo "<pre>"; print_r($arResult); echo "</pre>";
$colspan = 2;
if ($arResult["CAN_EDIT"] == "Y") $colspan++;
if ($arResult["CAN_DELETE"] == "Y") $colspan++;
?>
<?php if (strlen($arResult["MESSAGE"]) > 0):?>
	<?=ShowNote($arResult["MESSAGE"])?>
<?php endif?>
<table class="data-table">
<?php if($arResult["NO_USER"] == "N"):?>
	<thead>
		<tr>
			<td<?=$colspan > 1 ? " colspan=\"".$colspan."\"" : ""?>><?=GetMessage("IBLOCK_ADD_LIST_TITLE")?></td>
		</tr>
	</thead>
	<tbody>
	<?php if (count($arResult["ELEMENTS"]) > 0):?>
		<?php foreach ($arResult["ELEMENTS"] as $arElement):?>
		<tr>
			<td class="data-name"><!--a href="detail.php?CODE=<?=$arElement["ID"]?>"--><?=$arElement["NAME"]?><!--/a--></td>
			<td class="data-status"><small><?=is_array($arResult["WF_STATUS"]) ? $arResult["WF_STATUS"][$arElement["WF_STATUS_ID"]] : $arResult["ACTIVE_STATUS"][$arElement["ACTIVE"]]?></small></td>
			<?php if ($arResult["CAN_EDIT"] == "Y"):?>
			<td class="data-add"><?php if ($arElement["CAN_EDIT"] == "Y"):?><a href="<?=$arParams["EDIT_URL"]?>?edit=Y&amp;CODE=<?=$arElement["ID"]?>"><?=GetMessage("IBLOCK_ADD_LIST_EDIT")?><?php else:?>&nbsp;<?php endif?></a></td>
			<?php endif?>
			<?php if ($arResult["CAN_DELETE"] == "Y"):?>
			<td class="data-dell"><?php if ($arElement["CAN_DELETE"] == "Y"):?><a href="?delete=Y&amp;CODE=<?=$arElement["ID"]?>&amp;<?=bitrix_sessid_get()?>" onClick="return confirm('<?php echo CUtil::JSEscape(str_replace("#ELEMENT_NAME#", $arElement["NAME"], GetMessage("IBLOCK_ADD_LIST_DELETE_CONFIRM")))?>')"><?=GetMessage("IBLOCK_ADD_LIST_DELETE")?></a><?php else:?>&nbsp;<?php endif?></td>
			<?php endif?>
		</tr>
		<?php endforeach?>
	<?php else:?>
		<tr>
			<td<?=$colspan > 1 ? " colspan=\"".$colspan."\"" : ""?>><?=GetMessage("IBLOCK_ADD_LIST_EMPTY")?></td>
		</tr>
	<?php endif?>
	</tbody>
<?php endif?>
	<tfoot>
		<tr>
			<td<?=$colspan > 1 ? " colspan=\"".$colspan."\"" : ""?>><?php if ($arParams["MAX_USER_ENTRIES"] > 0 && $arResult["ELEMENTS_COUNT"] < $arParams["MAX_USER_ENTRIES"]):?><a href="<?=$arParams["EDIT_URL"]?>?edit=Y"><?=GetMessage("IBLOCK_ADD_LINK_TITLE")?></a><?php else:?><?=GetMessage("IBLOCK_LIST_CANT_ADD_MORE")?><?php endif?></td>
		</tr>
	</tfoot>
</table>
<?php if (strlen($arResult["NAV_STRING"]) > 0):?><?=$arResult["NAV_STRING"]?><?php endif?>