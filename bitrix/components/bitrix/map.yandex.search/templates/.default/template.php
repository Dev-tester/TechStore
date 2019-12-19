<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);
if ($arParams['BX_EDITOR_RENDER_MODE'] == 'Y'):
?>
<img src="/bitrix/components/bitrix/map.yandex.search/templates/.default/images/screenshot.png" border="0" />
<?php 
else:
?>
<div class="bx-yandex-search-layout">
	<div class="bx-yandex-search-form">
		<form name="search_form_<?php echo $arParams['MAP_ID']?>" onsubmit="jsYandexSearch_<?php echo $arParams['MAP_ID']?>.searchByAddress(this.address.value); return false;">
			<?php echo GetMessage('MYMS_TPL_SEARCH')?>: <input type="text" name="address" value="" style="width: 300px;" /><input type="submit" value="<?php echo GetMessage('MYMS_TPL_SUBMIT')?>" />
		</form>
	</div>

	<div class="bx-yandex-search-results" id="results_<?php echo $arParams['MAP_ID']?>"></div>

	<div class="bx-yandex-search-map">
<?php 
	$arParams['ONMAPREADY'] = 'BXWaitForMap_search'.$arParams['MAP_ID'];
	$APPLICATION->IncludeComponent('bitrix:map.yandex.system', '.default', $arParams, null, array('HIDE_ICONS' => 'Y'));
?>
	</div>
	
</div>
<script type="text/javascript">
function BXWaitForMap_search<?php echo $arParams['MAP_ID']?>() 
{
	window.jsYandexSearch_<?php echo $arParams['MAP_ID']?> = new JCBXYandexSearch('<?php echo $arParams['MAP_ID']?>', document.getElementById('results_<?php echo $arParams['MAP_ID']?>'), {
		mess_error: '<?php echo GetMessage('MYMS_TPL_JS_ERROR')?>',
		mess_search: '<?php echo GetMessage('MYMS_TPL_JS_SEARCH')?>',
		mess_found: '<?php echo GetMessage('MYMS_TPL_JS_RESULTS')?>',
		mess_search_empty: '<?php echo GetMessage('MYMS_TPL_JS_RESULTS_EMPTY')?>'
	});
}
</script>
<?php 
endif;
?>