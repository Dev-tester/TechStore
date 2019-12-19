<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?php 
if(count($arResult["Topics"]) <= 0)
	echo GetMessage("SONET_FORUM_EMPTY");
foreach($arResult["Topics"] as $arTopic)
{
	if($arTopic["FIRST"]!="Y")
	{
		?><div class="sonet-forum-line"></div><?php 
	}
	?>
	<b><a href="<?=$arTopic["read"]?>"><?php 
		echo $arTopic["TITLE"]; 
	?></a></b><br />
	<?php if(strlen($arTopic["DESCRIPTION"]) > 0)
	{
		?><small><br /><?=$arTopic["DESCRIPTION"]?></small><br clear="left"/><?php 
	}?>	
	<br clear="left"/>
	<span class="sonet-forum-post-info">
		<span class="sonet-forum-post-date"><?=$arTopic["LAST_POST_DATE"]?></span>	
		<?php if(IntVal($arTopic["VIEWS"]) > 0):?>
			<span class="sonet-forum-eye"><?=GetMessage("SONET_FORUM_M_VIEWS")?>:&nbsp;<?=$arTopic["VIEWS"]?>&nbsp;</span>
		<?php endif;?>
		<?php if(IntVal($arTopic["POSTS"]) > 0):?>
			<span class="sonet-forum-comment-num "><?=GetMessage("SONET_FORUM_M_NUM_COMMENTS")?></span>:&nbsp;<?=$arTopic["POSTS"]?>
		<?php endif;?>
	</span>
	<?php 
}
?>