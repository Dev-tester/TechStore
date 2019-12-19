<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if(!empty($arResult))
{
	?>
	<table width="0" cellpadding="0" cellspacing="5" border="0">
	<tr>
	<?php 
	foreach($arResult as $v)
	{
		?><td><a href="<?=$v["url"]?>" title="<?=$v["name"]?>" class="blog-rss-<?=$v["type"]?>"></a></td><?php 
	}
	?>
	</tr>
	</table>
	<?php 
}
?>