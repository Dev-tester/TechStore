<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php if(CModule::IncludeModule('advertising')):?>
<?php $APPLICATION->IncludeComponent("bitrix:advertising.banner", "leftfirst", array(
	"TYPE" => "LEFT1",
	"NOINDEX" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "0"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);?>
<?php endif;?>