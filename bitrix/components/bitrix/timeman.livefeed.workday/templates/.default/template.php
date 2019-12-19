<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CJSCore::Init(array('timeman'));
?><div class="feed-workday-table"><?php 
	?><span class="feed-workday-left-side"><?php 
		?><span class="feed-workday-table-text"><?=GetMessage("TIMEMAN_ENTRY_FROM")?>:</span><?php 
		?><span class="feed-workday-avatar"
			<?php  if (strlen($arParams["USER"]["PHOTO"]) > 0): ?>
				style="background:url('<?=$arParams["USER"]["PHOTO"]?>') no-repeat center; background-size: cover;"
			<?php  endif ?>
			><?php 
		?></span><?php 
		?><span class="feed-user-name-wrap"><a href="<?=$arParams['USER']["URL"]?>" class="feed-workday-user-name" bx-tooltip-user-id="<?=$arParams['USER']["ID"]?>"><?=$arParams['USER']["NAME"]?></a><?php 
		if (!empty($arParams['USER']["WORK_POSITION"]))
		{
			?><span class="feed-workday-user-position"><?=$arParams['USER']["WORK_POSITION"]?></span><?php 
		}
		?></span><?php 
	?></span><?php 
	?><span class="feed-workday-right-side"><?php 
		?><span class="feed-workday-table-text"><?=GetMessage("TIMEMAN_ENTRY_TO")?>:</span><?php 
		?><span class="feed-workday-avatar"
			<?php  if (strlen($arParams["MANAGER"]["PHOTO"]) > 0): ?>
				style="background:url('<?=$arParams["MANAGER"]["PHOTO"]?>') no-repeat center; background-size: cover;"
			<?php  endif ?>
			><?php 
		?></span><?php 
		?><span class="feed-user-name-wrap"><a href="<?=$arParams['MANAGER']["URL"]?>" class="feed-workday-user-name" bx-tooltip-user-id="<?=$arParams['MANAGER']["ID"]?>"><?=$arParams['MANAGER']["NAME"]?></a><?php 
		if (!empty($arParams['MANAGER']["WORK_POSITION"]))
		{
			?><span class="feed-workday-user-position"><?=$arParams['MANAGER']["WORK_POSITION"]?></span><?php 
		}
		?></span><?php 
	?></span><?php 
?></div><?php 
?>