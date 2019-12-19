<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
LocalRedirect(CComponentEngine::MakePathFromTemplate($arResult["PATH_TO_GROUP_BLOG"], array("group_id" => $arResult["VARIABLES"]["group_id"])));
die();
?>