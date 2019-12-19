<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
$this->SetViewTarget("sidebar", 100);
?>
<div class="rounded-block">
	<div class="corner left-top"></div>
	<div class="corner right-top"></div>
	<div class="block-content">
		<div class="content-list user-sidebar">
			<div class="content-item">
				<div class="content-avatar">
					<a<?php if ($arResult["CurrentUserPerms"]["Operations"]["viewprofile"]):?> href="<?=$arResult["Urls"]["main"]?>"<?php endif;?><?php if (strlen($arResult["User"]["PersonalPhotoFile"]["src"]) > 0):?> style="background:url('<?=$arResult["User"]["PersonalPhotoFile"]["src"]?>') no-repeat scroll center center transparent;"<?php endif;?>>
					<?php 
					if ($arResult["IS_BIRTHDAY"]):
						?><span class="birthday"></span><?php 
					endif;
					?>
					</a>
				</div>			
				<div class="content-info">
					<div class="content-title"><a <?php if ($arResult["CurrentUserPerms"]["Operations"]["viewprofile"]):?>href="<?=$arResult["Urls"]["main"]?>"<?php endif;?>><?=$arResult["User"]["NAME_FORMATTED"]?></a></div>
				</div>
			</div>
			<?php 
			if ($GLOBALS["USER"]->IsAuthorized() && !$arResult["CurrentUserPerms"]["IsCurrentUser"]):
				if ($arResult["CurrentUserPerms"]["Operations"]["message"] || $arResult["CurrentUserPerms"]["Operations"]["videocall"]):
					?>
					<div class="content-links">
						<?php if ($arResult["CurrentUserPerms"]["Operations"]["message"]):?>
							<a href="<?= $arResult["Urls"]["MessageChat"] ?>" onclick="if (BX.IM) { BXIM.openMessenger(<?=$arResult["User"]["ID"]?>); return false; } else {window.open('<?= $arResult["Urls"]["MessageChat"] ?>', '', 'location=yes,status=no,scrollbars=yes,resizable=yes,width=700,height=550,top='+Math.floor((screen.height - 550)/2-14)+',left='+Math.floor((screen.width - 700)/2-5)); return false;}"><?=GetMessage("SONET_UM_SEND_MESSAGE")?></a>
						<?php endif;?>
						<?php if ($arResult["CurrentUserPerms"]["Operations"]["videocall"]):?>
							<a href="<?= $arResult["Urls"]["VideoCall"] ?>" onclick="window.open('<?= $arResult["Urls"]["VideoCall"] ?>', '', 'location=yes,status=no,scrollbars=yes,resizable=yes,width=1000,height=600,top='+Math.floor((screen.height - 600)/2-14)+',left='+Math.floor((screen.width - 1000)/2-5)); return false;"><?=GetMessage("SONET_UM_VIDEO_CALL")?></a>
						<?php endif;?>
					</div>
					<?php 
				endif;
			endif;			
			?>
		</div>
		<div class="hr"></div>
		<ul class="mdash-list">
			<li class="<?php if ($arParams["PAGE_ID"] == "user"):?>selected<?php endif?>"><a href="<?=$arResult["Urls"]["Main"]?>"><?=GetMessage("SONET_UM_GENERAL")?></a></li>
			<?php if (CSocNetUser::IsFriendsAllowed() && $arResult["CurrentUserPerms"]["Operations"]["viewfriends"]):?>
				<li class="<?php if ($arParams["PAGE_ID"] == "user_friends"):?>selected<?php endif?>"><a href="<?=$arResult["Urls"]["Friends"]?>"><?=GetMessage("SONET_UM_FRIENDS")?></a></li>
			<?php endif;?>
			<?php if ($arResult["CurrentUserPerms"]["Operations"]["viewgroups"]):?>
				<li class="<?php if ($arParams["PAGE_ID"] == "user_groups"):?>selected<?php endif?>"><a href="<?=$arResult["Urls"]["Groups"]?>"><?=GetMessage("SONET_UM_GROUPS")?></a></li>
			<?php endif;?>
			<?php 
			foreach ($arResult["CanView"] as $key => $val)
			{
				if (!$val)
					continue;
					?><li class="<?php if ($arParams["PAGE_ID"] == "user_".$key):?>selected<?php endif?>"><a href="<?= $arResult["Urls"][$key] ?>"><?=$arResult["Title"][$key]?></a></li><?php 
			}
			?>
		</ul>
	</div>
	<div class="corner left-bottom"></div>
	<div class="corner right-bottom"></div>
</div>
<?php 
$this->EndViewTarget();
?>