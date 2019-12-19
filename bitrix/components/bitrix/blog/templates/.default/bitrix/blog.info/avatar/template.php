<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if(!empty($arResult))
{
	if(!empty($arResult["Avatar_FORMATED"]))
	{
		?>
		<ul>
		<li class="blog-tags">
			<div class="blog-sidebar-avatar">
			<?=$arResult["Avatar_FORMATED"]?>
			</div>
		</li>
		</ul>
		<?php 
	}
}
?>	
