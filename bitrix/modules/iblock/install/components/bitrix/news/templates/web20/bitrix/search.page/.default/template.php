<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="search-page">
<form action="" method="get">
	<input type="text" name="q" value="<?=$arResult["REQUEST"]["QUERY"]?>" size="40" />
<?php if($arParams["SHOW_WHERE"]):?>
	&nbsp;<select name="where">
	<option value=""><?=GetMessage("SEARCH_ALL")?></option>
	<?php foreach($arResult["DROPDOWN"] as $key=>$value):?>
	<option value="<?=$key?>"<?php if($arResult["REQUEST"]["WHERE"]==$key) echo " selected"?>><?=$value?></option>
	<?php endforeach?>
	</select>
<?php endif;?>
	&nbsp;<input type="submit" value="<?=GetMessage("SEARCH_GO")?>" />
	<input type="hidden" name="how" value="<?php echo $arResult["REQUEST"]["HOW"]=="d"? "d": "r"?>" />
</form><br />
<?php if(isset($arResult["REQUEST"]["ORIGINAL_QUERY"])):
	?>
	<div class="search-language-guess">
		<?php echo GetMessage("CT_BSP_KEYBOARD_WARNING", array("#query#"=>'<a href="'.$arResult["ORIGINAL_QUERY_URL"].'">'.$arResult["REQUEST"]["ORIGINAL_QUERY"].'</a>'))?>
	</div><br /><?php 
endif;?>
<?php if($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false):?>
<?php elseif($arResult["ERROR_CODE"]!=0):?>
	<p><?=GetMessage("SEARCH_ERROR")?></p>
	<?php ShowError($arResult["ERROR_TEXT"]);?>
	<p><?=GetMessage("SEARCH_CORRECT_AND_CONTINUE")?></p>
	<br /><br />
	<p><?=GetMessage("SEARCH_SINTAX")?><br /><b><?=GetMessage("SEARCH_LOGIC")?></b></p>
	<table border="0" cellpadding="5">
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_OPERATOR")?></td><td valign="top"><?=GetMessage("SEARCH_SYNONIM")?></td>
			<td><?=GetMessage("SEARCH_DESCRIPTION")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_AND")?></td><td valign="top">and, &amp;, +</td>
			<td><?=GetMessage("SEARCH_AND_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_OR")?></td><td valign="top">or, |</td>
			<td><?=GetMessage("SEARCH_OR_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top"><?=GetMessage("SEARCH_NOT")?></td><td valign="top">not, ~</td>
			<td><?=GetMessage("SEARCH_NOT_ALT")?></td>
		</tr>
		<tr>
			<td align="center" valign="top">( )</td>
			<td valign="top">&nbsp;</td>
			<td><?=GetMessage("SEARCH_BRACKETS_ALT")?></td>
		</tr>
	</table>
<?php elseif(count($arResult["SEARCH"])>0):?>
	<?=$arResult["NAV_STRING"]?>
	<br /><hr />
	<?php foreach($arResult["SEARCH"] as $arItem):?>
		<a href="<?php echo $arItem["URL"]?>"><?php echo $arItem["TITLE_FORMATED"]?></a>
		<p><?php echo $arItem["BODY_FORMATED"]?></p>
		<small><?=GetMessage("SEARCH_MODIFIED")?> <?=$arItem["DATE_CHANGE"]?></small><br /><?php 
		if($arItem["CHAIN_PATH"]):?>
			<small><?=GetMessage("SEARCH_PATH")?>&nbsp;<?=$arItem["CHAIN_PATH"]?></small><?php 
		endif;
		?><hr />
	<?php endforeach;?>
	<?=$arResult["NAV_STRING"]?>
	<br />
	<p>
	<?php if($arResult["REQUEST"]["HOW"]=="d"):?>
		<a href="<?=$arResult["URL"]?>&amp;how=r"><?=GetMessage("SEARCH_SORT_BY_RANK")?></a>&nbsp;|&nbsp;<b><?=GetMessage("SEARCH_SORTED_BY_DATE")?></b>
	<?php else:?>
		<b><?=GetMessage("SEARCH_SORTED_BY_RANK")?></b>&nbsp;|&nbsp;<a href="<?=$arResult["URL"]?>&amp;how=d"><?=GetMessage("SEARCH_SORT_BY_DATE")?></a>
	<?php endif;?>
	</p>
<?php else:?>
	<?php ShowNote(GetMessage("SEARCH_NOTHING_TO_FOUND"));?>
<?php endif;?>
</div>