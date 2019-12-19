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
					<a<?php if ($arResult["CurrentUserPerms"]["UserCanViewGroup"]):?> href="<?=$arResult["Urls"]["View"]?>"<?php endif;?><?php if (strlen($arResult["Group"]["IMAGE_FILE"]["src"]) > 0):?> style="background:url('<?=$arResult["Group"]["IMAGE_FILE"]["src"]?>') no-repeat scroll center center transparent;"<?php endif;?>></a>
				</div>			
				<div class="content-info">
					<div class="content-title"><a <?php if ($arResult["CurrentUserPerms"]["UserCanViewGroup"]):?> href="<?=$arResult["Urls"]["View"]?>"<?php endif;?>><?=$arResult["Group"]["NAME"]?></a></div>
					<?php if($arResult["Group"]["CLOSED"] == "Y"):?>
						<div class="content-description"><?= GetMessage("SONET_UM_ARCHIVE_GROUP") ?></div>
					<?php endif;?>
				</div>
			</div>
		</div>
		<div class="hr"></div>
		<ul class="mdash-list">
			<li class="<?php if ($arParams["PAGE_ID"] == "group"):?>selected<?php endif?>"><a href="<?=$arResult["Urls"]["View"]?>"><?=GetMessage("SONET_UM_GENERAL")?></a></li>
			<?php 
			foreach ($arResult["CanView"] as $key => $val)
			{
				if (!$val)
					continue;
				?><li class="<?php if ($arParams["PAGE_ID"] == "group_".$key):?>selected<?php endif?>"><a href="<?= $arResult["Urls"][$key] ?>"><?=$arResult["Title"][$key]?></a></li><?php 
			}
			?>
			<li class="<?php if ($arParams["PAGE_ID"] == "group_users"):?>selected<?php endif?>"><a href="<?=$arResult["Urls"]["GroupUsers"]?>"><?=GetMessage("SONET_UM_USERS")?></a></li>
		</ul>
	</div>
	<div class="corner left-bottom"></div>
	<div class="corner right-bottom"></div>
</div>
<?php 
$this->EndViewTarget();
?>