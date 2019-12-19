<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php if(strlen($arResult["FATAL_ERROR_MESSAGE"])>0):?>
	<span class='errortext'><?=$arResult["FATAL_ERROR_MESSAGE"]?></span><br /><br />
<?php else:?>
	<div class="bx-idea-cloud-tag-block">
		<div class="bx-idea-cloud-tag-header"><?=GetMessage("IDEA_TAG_TITLE")?></div>
		<div class="bx-idea-cloud-tag-link">
			<?php foreach($arResult["CATEGORY"] as $v):?>
			<a style="font-size:<?=$v["SIZE"]?>%" href="<?=$v["URL"]?>"><?=$v["NAME"]?></a>
			<?php endforeach;?>
		</div>
	</div>
	<div class="bx-idea-right-cont-wrap"></div>
<?php endif;?>