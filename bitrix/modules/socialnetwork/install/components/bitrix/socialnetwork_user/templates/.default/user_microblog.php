<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
LocalRedirect(CComponentEngine::MakePathFromTemplate($arResult["PATH_TO_USER_BLOG"], array("user_id" => $arResult["VARIABLES"]["user_id"])));
die();
?>