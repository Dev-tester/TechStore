<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$id = "new-b24-user-".randString(5);
?><div class="lenta-new-employee-icon">
	<div class="avatar" id="<?=$id?>"<?php if($arParams['AVATAR_SRC']):?> data-src="<?=$arParams['AVATAR_SRC']?>"<?php endif?>></div>
</div>
<?php if($arParams['AVATAR_SRC']):?>
<script>BitrixMobile.LazyLoad.registerImage("<?=$id?>");</script>
<?php endif?>
<div class="lenta-info-block-content">
	<div class="lenta-important-block-title"><a href="<?=$arParams['USER_URL']?>"><?=CUser::FormatName($arParams['PARAMS']['NAME_TEMPLATE'], $arParams['USER'])?></a></div>
	<div class="lenta-important-block-text"><?=htmlspecialcharsbx($arParams['USER']['WORK_POSITION'])?></div>
</div>