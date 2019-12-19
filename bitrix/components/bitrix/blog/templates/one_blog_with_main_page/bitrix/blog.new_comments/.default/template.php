<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
foreach($arResult as $arComment)
{
	if($arComment["FIRST"]!="Y")
	{
		?><div class="blog-line"></div><?php 
	}
	?>
	<span class="blog-author">
	<?php 
	if(strlen($arComment["urlToAuthor"])>0)
	{
		?>
		<a href="<?=$arComment["urlToAuthor"]?>" class="blog-user-grey"></a>&nbsp;<a href="<?=$arComment["urlToAuthor"]?>"><?=$arComment["AuthorName"]?></a>
		<?php 
	}
	elseif(strlen($arComment["urlToAuthor"])>0)
	{
		?>
		<a href="<?=$arComment["urlToAuthor"]?>" class="blog-user-grey"></a>&nbsp;<a href="<?=$arComment["urlToAuthor"]?>"><?=$arComment["AuthorName"]?></a>
		<?php 
	}
	else
	{
		?>
		<div class="blog-user-grey"></div>&nbsp;<?=$arComment["AuthorName"]?>
		<?php 
	}
	?>
	</span>
	<span class="blog-post-info">
		&nbsp;&nbsp;<a href="<?=$arComment["urlToComment"]?>" class="blog-clock" title="<?=GetMessage("BLOG_BLOG_M_DATE")?>"><?=$arComment["DATE_CREATE_FORMATED"]?></a>
	</span>
	
	<br clear="all"/>	
	<?php 
	if(strlen($arComment["TitleFormated"])>0) 
	{
		?>
		<span class="blog-post-date"><b><a href="<?=$arComment["urlToComment"]?>"><?php 
			echo $arComment["TitleFormated"];
		?></a></b></span><br /><?php 
	}
	else
	{
		?><a href="<?=$arComment["urlToComment"]?>"><?php 
	}
	?>
	<small><?=$arComment["TEXT_FORMATED"]?></small>
	<?php 
	if(strlen($arComment["TitleFormated"])>0) 
	{
		?></a><?php 
	}
	?>
	<br />

	<?php 
}
?>	
