<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="photo-detail">
<table cellspacing="0" cellpadding="0" border="0" width="100%" class="data-table">
<tr>
	<td colspan="5" align="center">
		<?php if(is_array($arResult["PICTURE"])):?>
			<img
				border="0"
				src="<?=$arResult["PICTURE"]["SRC"]?>"
				width="<?=$arResult["PICTURE"]["WIDTH"]?>"
				height="<?=$arResult["PICTURE"]["HEIGHT"]?>"
				alt="<?=$arResult["PICTURE"]["ALT"]?>"
				title="<?=$arResult["PICTURE"]["TITLE"]?>"
				/><br />
		<?php endif?>
	</td>
</tr>
<?php if(count($arParams["FIELD_CODE"])>0 || count($arResult["DISPLAY_PROPERTIES"])>0):?>
<tr>
	<th colspan="5">
		<?php foreach($arParams["FIELD_CODE"] as $code):
			if ('PREVIEW_PICTURE' == $code || 'DETAIL_PICTURE' == $code)
			{
				?><?=GetMessage("IBLOCK_FIELD_".$code)?>&nbsp;:&nbsp;<?php 
				if (!empty($arResult[$code]) && is_array($arResult[$code]))
				{
					?><img border="0" src="<?=$arResult[$code]["SRC"]?>" width="<?=$arResult[$code]["WIDTH"]?>" height="<?=$arResult[$code]["HEIGHT"]?>"><?php 
				}
			}
			else
			{
				?><?=GetMessage("IBLOCK_FIELD_".$code)?>&nbsp;:&nbsp;<?=$arResult[$code]?><?php 
			}
			?><br />
		<?php endforeach?>
		<?php foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
			<?=$arProperty["NAME"]?>:&nbsp;<?php 
			if(is_array($arProperty["DISPLAY_VALUE"]))
				echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
			else
				echo $arProperty["DISPLAY_VALUE"];?><br />
		<?php endforeach?>
	</th>
</tr>
<?php endif?>
<?php if($arResult["DETAIL_TEXT"] || $arResult["PREVIEW_TEXT"]):?>
<tr>
	<td colspan="5" valign="center" align="left">
		<?php if($arResult["DETAIL_TEXT"]):?>
			<?=$arResult["DETAIL_TEXT"]?>
		<?php elseif($arResult["PREVIEW_TEXT"]):?>
			<?=$arResult["PREVIEW_TEXT"]?>
		<?php endif;?>
	</td>
</tr>
<?php endif?>
<tr>
	<td align="center" width="20%">
		<?php if(is_array($arResult["PREV"][1])):?>
			<a href="<?=$arResult["PREV"][1]["DETAIL_PAGE_URL"]?>"><img
					border="0"
					src="<?=$arResult["PREV"][1]["PICTURE"]["SRC"]?>"
					width="<?=$arResult["PREV"][1]["PICTURE"]["WIDTH"]?>"
					height="<?=$arResult["PREV"][1]["PICTURE"]["HEIGHT"]?>"
					alt="<?=$arResult["PREV"][1]["PICTURE"]["ALT"]?>"
					title="<?=$arResult["PREV"][1]["PICTURE"]["TITLE"]?>"
					/></a><br /><a href="<?=$arResult["PREV"][1]["DETAIL_PAGE_URL"]?>"><?=$arResult["PREV"][1]["NAME"]?></a>
		<?php else:?>
			<?=GetMessage("NO_PHOTO")?>
		<?php endif?>
	</td>
	<td align="center" width="20%">
		<?php if(is_array($arResult["PREV"][0])):?>
			<a href="<?=$arResult["PREV"][0]["DETAIL_PAGE_URL"]?>"><img
					border="0"
					src="<?=$arResult["PREV"][0]["PICTURE"]["SRC"]?>"
					width="<?=$arResult["PREV"][0]["PICTURE"]["WIDTH"]?>"
					height="<?=$arResult["PREV"][0]["PICTURE"]["HEIGHT"]?>"
					alt="<?=$arResult["PREV"][0]["PICTURE"]["ALT"]?>"
					title="<?=$arResult["PREV"][0]["PICTURE"]["TITLE"]?>"
					/></a><br /><a href="<?=$arResult["PREV"][0]["DETAIL_PAGE_URL"]?>"><?=$arResult["PREV"][0]["NAME"]?></a>
		<?php else:?>
			<?=GetMessage("NO_PHOTO")?>
		<?php endif?>
	</td>
	<td align="center" width="20%">
		<img
			border="0"
			src="<?=$arResult["PREVIEW_PICTURE"]["SRC"]?>"
			width="<?=$arResult["PREVIEW_PICTURE"]["WIDTH"]?>"
			height="<?=$arResult["PREVIEW_PICTURE"]["HEIGHT"]?>"
			alt="<?=$arResult["PREVIEW_PICTURE"]["ALT"]?>"
			title="<?=$arResult["PREVIEW_PICTURE"]["TITLE"]?>"
			/><br />
		<?=$arResult["NAME"]?>
	</td>
	<td align="center" width="20%">
		<?php if(is_array($arResult["NEXT"][0])):?>
			<a href="<?=$arResult["NEXT"][0]["DETAIL_PAGE_URL"]?>"><img
					border="0"
					src="<?=$arResult["NEXT"][0]["PICTURE"]["SRC"]?>"
					width="<?=$arResult["NEXT"][0]["PICTURE"]["WIDTH"]?>"
					height="<?=$arResult["NEXT"][0]["PICTURE"]["HEIGHT"]?>"
					alt="<?=$arResult["NEXT"][0]["PICTURE"]["ALT"]?>"
					title="<?=$arResult["NEXT"][0]["PICTURE"]["TITLE"]?>"
					/></a><br /><a href="<?=$arResult["NEXT"][0]["DETAIL_PAGE_URL"]?>"><?=$arResult["NEXT"][0]["NAME"]?></a>
		<?php else:?>
			<?=GetMessage("NO_PHOTO")?>
		<?php endif?>
	</td>
	<td align="center" width="20%">
		<?php if(is_array($arResult["NEXT"][1])):?>
			<a href="<?=$arResult["NEXT"][1]["DETAIL_PAGE_URL"]?>"><img
					border="0"
					src="<?=$arResult["NEXT"][1]["PICTURE"]["SRC"]?>"
					width="<?=$arResult["NEXT"][1]["PICTURE"]["WIDTH"]?>"
					height="<?=$arResult["NEXT"][1]["PICTURE"]["HEIGHT"]?>"
					alt="<?=$arResult["NEXT"][1]["PICTURE"]["ALT"]?>"
					title="<?=$arResult["NEXT"][1]["PICTURE"]["TITLE"]?>"
					/></a><br /><a href="<?=$arResult["NEXT"][1]["DETAIL_PAGE_URL"]?>"><?=$arResult["NEXT"][1]["NAME"]?></a>
		<?php else:?>
			<?=GetMessage("NO_PHOTO")?>
		<?php endif?>
	</td>
</tr>
<tr>
	<td align="center" width="20%">
		<?php if(is_array($arResult["PREV"][1])):?>
			<a href="<?=$arResult["PREV"][1]["DETAIL_PAGE_URL"]?>">&lt;&lt;</a>
		<?php else:?>
			&nbsp;
		<?php endif?>
	</td>
	<td align="center" width="20%">
		<?php if(is_array($arResult["PREV"][0])):?>
			<a href="<?=$arResult["PREV"][0]["DETAIL_PAGE_URL"]?>">&lt;</a>
		<?php else:?>
			&nbsp;
		<?php endif?>
	</td>
	<td align="center" width="20%">
		<?=GetMessage("NO_OF_COUNT",array("#NO#"=>$arResult["CURRENT"]["NO"],"#TOTAL#"=>$arResult["CURRENT"]["COUNT"]))?>
	</td>
	<td align="center" width="20%">
		<?php if(is_array($arResult["NEXT"][0])):?>
			<a href="<?=$arResult["NEXT"][0]["DETAIL_PAGE_URL"]?>">&gt;</a>
		<?php else:?>
			&nbsp;
		<?php endif?>
	</td>
	<td align="center" width="20%">
		<?php if(is_array($arResult["NEXT"][1])):?>
			<a href="<?=$arResult["NEXT"][1]["DETAIL_PAGE_URL"]?>">&gt;&gt;</a>
		<?php else:?>
			&nbsp;
		<?php endif?>
	</td>
</tr>
</table>
<p>
	<a href="<?=is_array($arResult["SECTION"])?$arResult["SECTION"]["SECTION_PAGE_URL"]:""?>"><?=GetMessage("PHOTO_BACK")?></a>
</p>
</div>