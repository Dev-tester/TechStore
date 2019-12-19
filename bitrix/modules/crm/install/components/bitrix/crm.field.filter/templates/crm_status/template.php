<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>
<?php $arField = $arParams['arUserField'];?>
<?php $bMultiple = isset($arField['MULTIPLE']) && $arField['MULTIPLE'] == 'Y';?>

<select name="<?=$arField['FIELD_NAME']?>"<?=$bMultiple ? ' multiple="multiple"' : ''?>>
<?php if(isset($arParams['bShowNotSelected']) && !is_array($arParams['bShowNotSelected']) && $arParams['bShowNotSelected'] == true)
{
	//Bugfix #24115
	?><option value=""><?=htmlspecialcharsbx(GetMessage('MAIN_NO'))?></option><?php 
}?>
<?php $bWasSelect = false;?>
<?php foreach ($arField['USER_TYPE']['FIELDS'] as $key => $val)
{
	$bSelected = (!$bWasSelect || $bMultiple) && in_array($key, $arResult['VALUE']);
	?><option value="<?=htmlspecialcharsbx($key)?>"<?= $bSelected ? ' selected' : ''?>><?=htmlspecialcharsbx($val)?></option><?php 
	if(!$bWasSelect && $bSelected)
	{
		$bWasSelect = true;
	}
}?>
</select>