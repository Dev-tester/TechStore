<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (is_array($arResult['SECTIONS']) && count($arResult['SECTIONS']) >= 0):
?>
<ul class="bx-user-sections-layout">
<?php 
	foreach ($arResult['SECTIONS'] as $arSection):

?>
	<li><?php if ($arSection['URL']):?><a href="<?php echo $arSection['URL']?>"><?php endif;?><?php echo htmlspecialcharsbx($arSection['NAME'])?><?php if ($arSection['URL']):?></a><?php endif;?></li>
<?php 
	endforeach;
?>
</ul>
<?php 
endif;
?>