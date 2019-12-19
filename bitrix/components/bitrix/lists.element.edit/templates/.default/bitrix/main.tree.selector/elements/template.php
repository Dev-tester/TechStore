<?php 
if(!Defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$name_x = CUtil::JSEscape($arParams['NAME']);
$arParams['INPUT_NAME'] = CUtil::JSEscape($arParams['INPUT_NAME']);

if($arParams['SHOW_INPUT'] == 'Y'):?>
	<input type="text" id="<?php echo $arParams['INPUT_NAME']?>" name="<?php echo $arParams['INPUT_NAME']?>" value="<?php echo $arParams['INPUT_VALUE']?>" size="3" />
<?php endif;?>

<?php if($arParams['SHOW_BUTTON'] == 'Y'):?>
	<input type="button" onclick="<?php echo $name_x?>.Show()" value="<?php echo $arParams['BUTTON_CAPTION'] ? $arParams['BUTTON_CAPTION'] : '...'?>" />
<?php endif;?>

<script type="text/javascript">
<?php if($arParams['INPUT_NAME'] && !$arParams['ONSELECT']):?>
	function OnSelect_<?=$name_x?>(value){
		document.getElementById('<?=$arParams['INPUT_NAME']?>').value = value;
	}
	<?php 
	$arParams['ONSELECT'] = 'OnSelect_'.$name_x;
endif;?>

var <?=$name_x?> = new JCTreeSelectControl({
	'AJAX_PAGE' : '<?php echo CUtil::JSEscape($this->GetFolder()."/ajax.php")?>',
	'AJAX_PARAMS' : <?php echo CUtil::PhpToJsObject(array(
		"IBLOCK_TYPE_ID" => $arParams["IBLOCK_TYPE_ID"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"LANGUAGE_ID" => LANGUAGE_ID,
		"SOCNET_GROUP_ID" => $arParams["SOCNET_GROUP_ID"],
	))?>,
	'MULTIPLE' : <?php echo $arParams['MULTIPLE'] == 'Y' ? 'true' : 'false'?>,
	'GET_FULL_INFO': <?php echo $arParams['GET_FULL_INFO'] == 'Y' ? 'true' : 'false'?>,
	'ONSELECT' : function(v){<?php echo $arParams['ONSELECT']?>(v)},
	'START_TEXT' : '<?php echo CUtil::JSEscape($arParams["START_TEXT"])?>',
	'NO_SEARCH_RESULT_TEXT' : '<?php echo CUtil::JSEscape($arParams["NO_SEARCH_RESULT_TEXT"])?>',
	'INPUT_NAME' : '<?php echo CUtil::JSEscape($arParams["INPUT_NAME"])?>'
});

<?php if($arParams['INPUT_NAME']):?>
//BX.ready(function() {
//	<?php echo $name_x?>.SetValueFromInput('<?php echo $arParams['INPUT_NAME']?>');
//	var inp = document.getElementById('<?php echo $arParams['INPUT_NAME']?>');
//	if(inp)
//		inp.onchange = function () {<?php echo $name_x?>.SetValue(this.value)}
//});
<?php endif;?>
</script>