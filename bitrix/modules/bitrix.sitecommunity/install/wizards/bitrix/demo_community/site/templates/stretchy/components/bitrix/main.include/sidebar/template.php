<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["FILE"] <> ''):

	if (!$GLOBALS["bSidebarSearchShown"]):
	
		?>
		<div id="search-wrapper">
		<?php $APPLICATION->IncludeComponent("bitrix:search.form", "", Array(
			"PAGE"	=>	SITE_DIR."search/"
		)
		);?>
		</div>
		<?php 
	endif;

	include($arResult["FILE"]);
	$GLOBALS["bSidebarSearchShown"] = true;
endif;
?>