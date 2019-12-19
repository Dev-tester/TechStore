<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (count($arResult['FORMS']) <= 0)
{
	ShowNote(GetMessage('FRLM_NO_RESULTS'));
	return;
}
?>
<div class="bx-mylist-layout">
<?php 
//echo '<pre>'; print_r($arResult); echo '</pre>';
foreach ($arResult['FORMS'] as $FORM_ID => $arForm):
?>
	<div class="bx-mylist-form" id="bx_mylist_form_<?php echo $FORM_ID?>">
		<div class="bx-mylist-form-info">
			<b><?php echo $arForm['NAME']?></b>
		</div>
		<ul class="bx-mylist-form-results">
<?php 
	$i = 0;
	foreach ($arResult['RESULTS'][$FORM_ID] as $arRes):
?>
			<li class="bx-mylist-row-<?php echo ($i++) % 2;?>"><div class="bx-mylist-form-status"><span class="<?=$arRes["STATUS_CSS"]?>"><?=$arRes["STATUS_TITLE"]?></span></div> <div class="bx-mylist-form-data"><span class="bx-mylist-form-date intranet-date"><?php echo FormatDateFromDB($arRes['DATE_CREATE'], 'SHORT')?></span> <a href="<?php echo $arRes['__LINK']?>"><?php echo GetMessage('FRLM_RESULT').$arRes['ID']?><?php echo $arRes['__TITLE'] ? ': '.htmlspecialcharsbx($arRes['__TITLE']) : ''?></a></div></li>
<?php 
	endforeach;
?>
		</ul>
<?php 
	if ($arForm['__LINK']):
?>
		<div class="bx-mylist-form-link">
			<a href="<?php echo $arForm['__LINK']?>"><?php echo GetMessage('FRLM_MORE_RESULTS')?></a>
		</div>
<?php 
	endif;
?>
	</div>
<?php 
endforeach;
?>
</div>