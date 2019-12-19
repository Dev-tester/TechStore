<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (is_array($arResult['SECTIONS']) && count($arResult['SECTIONS']) > 0):
	?><div class="bx-user-sections-layout"><?php 
	foreach ($arResult['SECTIONS'] as $arSection):
		?><div><?php if ($arSection['URL']):?><a href="<?php echo $arSection['URL']?>"><?php endif;?><?php echo htmlspecialcharsbx($arSection['NAME'])?><?php if ($arSection['URL']):?></a><?php endif;?></div><?php 
	endforeach;
	?></div><?php 
else:
	echo GetMessage('SONET_HEAD_USER_NOT_FOUND');	
endif;
?>