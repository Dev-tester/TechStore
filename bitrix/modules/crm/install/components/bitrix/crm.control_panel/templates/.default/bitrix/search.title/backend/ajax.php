<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if(!empty($arResult["CATEGORIES"])):?>
	<table class="title-search-result">
		<?php foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
			<tr>
				<td class="title-search-separator">&nbsp;</td>
			</tr>
			<?php foreach($arCategory["ITEMS"] as $i => $arItem):?>
			<tr>
				<?php if($category_id === "all"):?>
					<td class="title-search-all"><a href="<?php echo $arItem["URL"]?>"><?php echo $arItem["NAME"]?></td>
				<?php elseif(isset($arItem["ICON"])):?>
					<td class="title-search-item"><img src="<?php echo $arItem["ICON"]?>"><a href="<?php echo $arItem["URL"]?>"><?php echo $arItem["NAME"]?></td>
				<?php else:?>
					<td class="title-search-more"><a href="<?php echo $arItem["URL"]?>"><?php echo $arItem["NAME"]?></td>
				<?php endif;?>
			</tr>
			<?php endforeach;?>
		<?php endforeach;?>
		<tr>
			<td class="title-search-separator">&nbsp;</td>
		</tr>
	</table><div class="title-search-fader"></div>
<?php endif;?>
