<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?php 
if (empty($arResult["TOPIC"]))
	return 0;
?>
<ul class="last-items-list">
<?php 
	foreach ($arResult["TOPIC"] as $res)
	{
?>
	<li>
<?php 
		if (intVal($res["USER_START_ID"]) > 0 ):
			?><a href="<?=$res["user_start_id_profile"]?>" class="item-author"><?=$res["USER_START_NAME"]?></a><?php 
		else:
			?><span class="item-author"><?=$res["USER_START_NAME"]?></span><?php 
		endif;
	?> <i>&gt;</i> <?php 
	?> <a href="<?=$arResult["FORUM"][$res["FORUM_ID"]]["URL"]["LIST"]?>" class="item-category"><?=$arResult["FORUM"][$res["FORUM_ID"]]["NAME"]?></a> <?php 
	?> <i>&gt;</i> <?php 
	?> <a href="<?=$res["read"]?>" class="item-name"><?=$res["TITLE"]?></a> 
	</li>
<?php 
	}
?>
</ul>