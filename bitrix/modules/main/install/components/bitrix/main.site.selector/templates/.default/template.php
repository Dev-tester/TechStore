<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php foreach ($arResult["SITES"] as $key => $arSite):?>

	<?php if ($arSite["CURRENT"] == "Y"):?>
		<span title="<?=$arSite["NAME"]?>"><?=$arSite["NAME"]?></span>&nbsp;
	<?php else:?>
		<a href="<?php if(is_array($arSite['DOMAINS']) && strlen($arSite['DOMAINS'][0]) > 0 || strlen($arSite['DOMAINS']) > 0):?>http://<?php endif?><?=(is_array($arSite["DOMAINS"]) ? $arSite["DOMAINS"][0] : $arSite["DOMAINS"])?><?=$arSite["DIR"]?>" title="<?=$arSite["NAME"]?>"><?=$arSite["NAME"]?></a>&nbsp;
	<?php endif?>

<?php endforeach;?>