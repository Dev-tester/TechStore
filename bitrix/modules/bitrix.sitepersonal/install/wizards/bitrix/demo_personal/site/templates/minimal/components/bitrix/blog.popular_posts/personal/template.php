<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<dl class="block-list">
<?php 
foreach($arResult as $arPost)
{
	?>
	<dt><?=$arPost["DATE_PUBLISH_FORMATED"]?></dt>
	<dd><a href="<?=$arPost["urlToPost"]?>"><?php echo $arPost["TITLE"]; ?></a></dd>
	<?php 
}
?>	
</dl>