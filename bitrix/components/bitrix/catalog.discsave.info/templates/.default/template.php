<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (empty($arResult))
{
	?><h3><?php  echo GetMessage('BX_CMP_CDI_TPL_MESS_NO_DISCOUNT_SAVE'); ?></h3><?php 
}
else
{
	?><h3><?php  echo GetMessage('BX_CMP_CDI_TPL_MESS_DISCOUNT_SAVE'); ?></h3><?php 
	foreach ($arResult as &$arDiscountSave)
	{
		?><h4><?php  echo $arDiscountSave['NAME']; ?></h4><?php 
		?><p><?php  echo GetMessage('BX_CMP_CDI_TPL_MESS_SIZE'); ?> <?php 
		if ('P' == $arDiscountSave['VALUE_TYPE'])
		{
			echo $arDiscountSave['VALUE']; ?>&nbsp;%<?php 
		}
		else
		{
			echo CCurrencyLang::CurrencyFormat($arDiscountSave['VALUE'], $arDiscountSave['CURRENCY'], true);
		}
		if (isset($arDiscountSave['NEXT_LEVEL']) && is_array($arDiscountSave['NEXT_LEVEL']))
		{
			$strNextLevel = '';
			if ('P' == $arDiscountSave['NEXT_LEVEL']['VALUE_TYPE'])
			{
				$strNextLevel = $arDiscountSave['NEXT_LEVEL']['VALUE'].'&nbsp;%';
			}
			else
			{
				$strNextLevel = CCurrencyLang::CurrencyFormat($arDiscountSave['NEXT_LEVEL']['VALUE'], $arDiscountSave['CURRENCY'], true);
			}

			?><br /><?php  echo str_replace(array('#SIZE#', '#SUMM#'), array($strNextLevel, CCurrencyLang::CurrencyFormat(($arDiscountSave['NEXT_LEVEL']['RANGE_FROM'] - $arDiscountSave['RANGE_SUMM']),$arDiscountSave['CURRENCY'], true)), GetMessage('BX_CMP_CDI_TPL_MESS_NEXT_LEVEL')); ?><?php 
		}
		?></p><br /><?php 
	}
}
?>