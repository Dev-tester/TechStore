<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if(!empty($arResult))
{
	?>
	<div class="blog-info">
	<?php if(strlen($arResult["Avatar_FORMATED"])>0)
	{
		?>
		<?=$arResult["Avatar_FORMATED"]?>
		<br /><br />
		<?php 
	}
	?>
	</div>
	<?php 
	if(!empty($arResult["CATEGORY"]))
	{
		?>
		<div align="left" style="padding-left:20px;" class="blog-info">
		<b><?=GetMessage("BLOG_BLOG_BLOGINFO_CAT")?></b><br />
		<?php 
		foreach($arResult["CATEGORY"] as $arCategory)
		{
			if($arCategory["SELECTED"]=="Y")
				echo "<b>";
			?>
			<a href="<?=$arCategory["urlToCategory"]?>" title="<?php GetMessage("BLOG_BLOG_BLOGINFO_CAT_VIEW")?>"><?=$arCategory["NAME"]?></a>
			<?php 
			if($arCategory["SELECTED"]=="Y")
				echo "</b>";
			?>
			<br />
			<?php 
		}
		?></div><?php 
	}
	if($arResult["BLOG_PROPERTIES"]["SHOW"] == "Y"):
		?><br /><div align="left" style="padding-left:20px;">
		<table cellspacing="0" cellpadding="2" class="blog-info" style="width:0%;"><?php 
		foreach ($arResult["BLOG_PROPERTIES"]["DATA"] as $FIELD_NAME => $arBlogField):
			if(strlen($arBlogField["VALUE"])>0):?>
				<tr>
					<td valign="top"><b><?=$arBlogField["EDIT_FORM_LABEL"]?>:</b></td>
					<td valign="top">
							<?php $APPLICATION->IncludeComponent(
								"bitrix:system.field.view", 
								$arBlogField["USER_TYPE"]["USER_TYPE_ID"], 
								array("arUserField" => $arBlogField), null, array("HIDE_ICONS"=>"Y"));?>
					</td>
					<td>&nbsp;</td>
				</tr>			
			<?php endif;
		endforeach;
		?></table></div><br /><?php 
	endif;
}
?>	
