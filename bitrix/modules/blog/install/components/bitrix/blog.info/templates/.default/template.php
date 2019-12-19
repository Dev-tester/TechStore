<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if (!$this->__component->__parent || empty($this->__component->__parent->__name) || $this->__component->__parent->__name != "bitrix:blog"):
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/blog/templates/.default/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/blog/templates/.default/themes/blue/style.css');
endif;
?>
<?php 
if(!empty($arResult))
{
	if(!empty($arResult["CATEGORY"]))
	{
		?>
		<noindex>
		<h3 class="blog-sidebar-title"><?=GetMessage("BLOG_BLOG_TAG_CLOUD")?></h3>
		<div class="blog-tags-cloud" <?=$arParams["WIDTH"]?>>
			<?php 
			foreach($arResult["CATEGORY"] as $arCategory)
			{
				if($arCategory["SELECTED"]=="Y")
					echo "<b>";
				?><a href="<?=$arCategory["urlToCategory"]?>" title="<?php GetMessage("BLOG_BLOG_BLOGINFO_CAT_VIEW")?>" style="font-size: <?=$arCategory["FONT_SIZE"]?>px;" rel="nofollow"><?=$arCategory["NAME"]?></a> <?php 
				if($arCategory["SELECTED"]=="Y")
						echo "</b>";
			}
		?></div>
		</noindex>
		<?php 
	}
}
?>	
