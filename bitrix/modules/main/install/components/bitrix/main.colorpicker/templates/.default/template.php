<?php 
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
?>
<script type="text/javascript">
jsColorPickerMess = window.jsColorPickerMess = {
	DefaultColor: '<?php echo CUtil::JSEscape(GetMessage('DefaultColor'));?>'
}
</script>
<?php 
if ($arParams['SHOW_BUTTON'] == 'Y')
{
		$ID = $arParams['ID'] ? $arParams['ID'] : RandString();
?>
<span id="bx_colorpicker_<?php echo $ID?>"></span>
<style>#bx_btn_<?php echo $ID?>{background-position: -280px -21px;}</style>
<script type="text/javascript">
var CP_<?php echo CUtil::JSEscape($ID)?> = new window.BXColorPicker({
	'id':'<?php echo CUtil::JSEscape($ID)?>'<?php if ($arParams['NAME']):?>,'name':'<?php echo CUtil::JSEscape($arParams['~NAME']);?>'<?php endif;if ($arParams['ONSELECT']):?>,'OnSelect':<?php echo $arParams['ONSELECT'];endif;?>
});
BX.ready(function () {document.getElementById('bx_colorpicker_<?php echo CUtil::JSEscape($ID)?>').appendChild(CP_<?php echo CUtil::JSEscape($ID)?>.pCont)});
</script>
<?php 
}
?>