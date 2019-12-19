<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");

__IncludeLang($_SERVER['DOCUMENT_ROOT'].'/bitrix/components/bitrix/map.yandex.view/lang/'.LANGUAGE_ID.'/settings.php');

//if(!$USER->IsAdmin())
//	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$obJSPopup = new CJSPopup('',
	array(
		'TITLE' => GetMessage('MYMV_SET_POPUP_TITLE'),
		'SUFFIX' => 'yandex_map',
		'ARGS' => ''
	)
);

$arData = array();
if ($_REQUEST['MAP_DATA'])
{
	CUtil::JSPostUnescape();

	if (CheckSerializedData($_REQUEST['MAP_DATA']))
	{
		$arData = unserialize($_REQUEST['MAP_DATA']);

		if (is_array($arData) && is_array($arData['PLACEMARKS']) && ($cnt = count($arData['PLACEMARKS'])))
		{
			for ($i = 0; $i < $cnt; $i++)
			{
				$arData['PLACEMARKS'][$i]['TEXT'] = str_replace('###RN###', "\r\n", $arData['PLACEMARKS'][$i]['TEXT']);
			}
		}
	}
}
?>
<script type="text/javascript" src="/bitrix/components/bitrix/map.yandex.view/settings/settings_load.js"></script>
<script type="text/javascript">
BX.loadCSS('/bitrix/components/bitrix/map.yandex.view/settings/settings.css');
window._global_BX_UTF = <?php echo defined('BX_UTF') && BX_UTF == true ? 'true' : 'false'?>;
window.jsYandexMess = {
	noname: '<?php echo CUtil::JSEscape(GetMessage('MYMV_SET_NONAME'))?>',
	MAP_VIEW_MAP: '<?php echo CUtil::JSEscape(GetMessage('MYMS_PARAM_INIT_MAP_TYPE_MAP'))?>',
	MAP_VIEW_SATELLITE: '<?php echo CUtil::JSEscape(GetMessage('MYMS_PARAM_INIT_MAP_TYPE_SATELLITE'))?>',
	MAP_VIEW_HYBRID: '<?php echo CUtil::JSEscape(GetMessage('MYMS_PARAM_INIT_MAP_TYPE_HYBRID'))?>',
	MAP_VIEW_PUBLIC: '<?php echo CUtil::JSEscape(GetMessage('MYMS_PARAM_INIT_MAP_TYPE_PUBLIC'))?>',
	MAP_VIEW_PUBLIC_HYBRID: '<?php echo CUtil::JSEscape(GetMessage('MYMS_PARAM_INIT_MAP_TYPE_PUBLIC_HYBRID'))?>',
	poly_start_point: '<?php echo CUtil::JSEscape(GetMessage('MYMV_SET_POLY_START_POINT'))?>',
	current_view: '<?php echo CUtil::JSEscape($_REQUEST['INIT_MAP_TYPE'])?>',
	nothing_found: '<?php echo CUtil::JSEscape(GetMessage('MYMS_PARAM_INIT_MAP_NOTHING_FOUND'))?>',

	poly_finish: '<?php echo CUtil::JSEscape(GetMessage('MYMV_SET_POLY_FINISH'))?>',
	poly_settings: '<?php echo CUtil::JSEscape(GetMessage('MYMV_SET_POLY_SETTINGS'))?>',

	poly_opt_header: '<?php echo CUtil::JSEscape(GetMessage('MYMV_SET_POLY_SETTINGS'))?>',
	poly_opt_title: '<?php echo CUtil::JSEscape(GetMessage('MYMV_SET_POLY_TITLE'))?>',
	poly_opt_color: '<?php echo CUtil::JSEscape(GetMessage('MYMV_SET_POLY_COLOR'))?>',
	poly_opt_width: '<?php echo CUtil::JSEscape(GetMessage('MYMV_SET_POLY_WIDTH'))?>',
	poly_opt_opacity: '<?php echo CUtil::JSEscape(GetMessage('MYMV_SET_POLY_OPACITY'))?>'

};

if (null != window.jsYandexCESearch)
	jsYandexCESearch.clear();

if (null != window.jsYandexCE)
	jsYandexCE.clear();
<?php 
if (is_array($arData) && count($arData) > 0):
?>
jsYandexCE.onInitCompleted = function()
{
<?php 
	if (is_array($arData['PLACEMARKS']))
	{
		foreach ($arData['PLACEMARKS'] as $arPoint)
		{
?>
	jsYandexCE.addCustomPoint({LON:<?php  echo doubleval($arPoint['LON'])?>,LAT:<?php  echo doubleval($arPoint['LAT'])?>,TEXT:'<?php  echo CUtil::JSEscape($arPoint['TEXT'])?>'});
<?php 
		}
	}

	if (is_array($arData['POLYLINES']))
	{
		foreach ($arData['POLYLINES'] as $arPoly)
		{
?>
	jsYandexCE.addCustomPoly(<?php echo CUtil::PhpToJSObject($arPoly)?>);
<?php 
		}
	}
?>
}
<?php 
endif;
?>
</script>
<form name="bx_popup_form_yandex_map">
<?php 
$obJSPopup->ShowTitlebar();
$obJSPopup->StartDescription('bx-edit-menu');
?>
	<p><b><?php echo GetMessage('MYMV_SET_POPUP_WINDOW_TITLE')?></b></p>
	<p class="note"><?php echo GetMessage('MYMV_SET_POPUP_WINDOW_DESCRIPTION')?></p>
<?php 
$obJSPopup->StartContent();
?>
<div id="bx_yandex_map_control" style="position: absolute; margin-right: 275px; margin-top: -2px; margin-left: -2px; border: solid 1px #B8C1DD;">
<?php 
$APPLICATION->IncludeComponent('bitrix:main.colorpicker', '', array('SHOW_BUTTON' => 'N'), false, array('HIDE_ICONS' => 'Y'));

$APPLICATION->IncludeComponent('bitrix:map.yandex.system', '', array(
	'KEY' => $_REQUEST['KEY'],
	'INIT_MAP_TYPE' => $_REQUEST['INIT_MAP_TYPE'],
	'MAP_WIDTH' => 500,
	'MAP_HEIGHT' => 385,
	'INIT_MAP_LAT' => $arData['yandex_lat'],
	'INIT_MAP_LON' => $arData['yandex_lon'],
	'INIT_MAP_SCALE' => $arData['yandex_scale'],
	'CONTROLS' => array("ZOOM","MINIMAP","TYPECONTROL","SCALELINE"),
	'OPTIONS' => array('ENABLE_SCROLL_ZOOM', 'ENABLE_DBLCLICK_ZOOM', 'ENABLE_DRAGGING'),
	'MAP_ID' => 'system_view_edit',
	'ONMAPREADY' => 'jsYandexCE.init',
	'ONMAPREADY_PROPERTY' => 'jsYandexCE.map',
	'DEV_MODE' => 'Y',
), false, array('HIDE_ICONS' => 'Y'));
?>
</div><div class="bx-yandex-map-address-search" id="bx_yandex_map_address_search" style="visibility: hidden; ">
	<?php echo GetMessage('MYMV_SET_ADDRESS_SEARCH')?>: <input type="text" name="address" value="" style="width: 380px;" onkeyup="jsYandexCESearch.setTypingStarted(this)" autocomplete="off" />
</div><div class="bx-yandex-map-controls" id="bx_yandex_map_controls" style="margin-left: 510px; visibility: hidden;">
	<div class="bx-yandex-map-controls-group">
		<b><?php echo GetMessage('MYMV_SET_START_POS')?></b><br />
			<ul id="bx_yandex_position">
				<li><?php echo GetMessage('MYMV_SET_START_POS_LAT')?>: <span class="bx-yandex-map-controls-value" id="bx_yandex_lat_value"></span><input type="hidden" name="bx_yandex_lat" value="<?php echo htmlspecialcharsbx($arData['yandex_lat'])?>" /></li>
				<li><?php echo GetMessage('MYMV_SET_START_POS_LON')?>: <span class="bx-yandex-map-controls-value" id="bx_yandex_lon_value"></span><input type="hidden" name="bx_yandex_lon" value="<?php echo htmlspecialcharsbx($arData['yandex_lon'])?>" /></li>
				<li><?php echo GetMessage('MYMV_SET_START_POS_SCALE')?>: <span class="bx-yandex-map-controls-value" id="bx_yandex_scale_value"></span><input type="hidden" name="bx_yandex_scale" value="<?php echo htmlspecialcharsbx($arData['yandex_scale'])?>" /></li>
				<li><?php echo GetMessage('MYMV_SET_START_POS_VIEW')?>: <span class="bx-yandex-map-controls-value" id="bx_yandex_view_value"></span><input type="hidden" name="bx_yandex_view" value="<?php echo htmlspecialcharsbx($_REQUEST['INIT_MAP_TYPE'])?>" /></li>
				<li><input type="checkbox" id="bx_yandex_position_fix" name="bx_yandex_position_fix" value="Y"<?php if ($arData['yandex_scale']):?> checked="checked"<?php endif;?>  /> <label for="bx_yandex_position_fix"><?php echo GetMessage('MYMV_SET_START_POS_FIX')?></label>&nbsp;|&nbsp;<a href="javascript:void(0)" id="bx_restore_position"><?php echo GetMessage('MYMV_SET_START_POS_RESTORE')?></a>
			</ul>
	</div>
	<div class="bx-yandex-map-controls-group" id="bx_yandex_points_group">
		<b><?php echo GetMessage('MYMV_SET_POINTS')?></b><br />
		<ul id="bx_yandex_points"></ul>
		<a href="javascript: void(0)" onclick="jsYandexCE.addPoint(); return false;" id="bx_yandex_addpoint_link" style="display: block;"><?php echo GetMessage('MYMV_SET_POINTS_ADD')?></a>
		<div id="bx_yandex_addpoint_message" style="display: none;"><?php echo GetMessage('MYMV_SET_POINTS_ADD_DESCRIPTION')?> <a href="javascript:void(0)" onclick="jsYandexCE.addPoint(); return false;"><?php echo GetMessage('MYMV_SET_POINTS_ADD_FINISH')?></a>.</div>
	</div>
	<div class="bx-yandex-map-controls-group" id="bx_yandex_polylines_group">
		<b><?php echo GetMessage('MYMV_SET_POLY')?>:</b><br />
		<ul id="bx_yandex_polylines"></ul>
		<a href="javascript: void(0)" onclick="jsYandexCE.addPolyline(); return false;" id="bx_yandex_addpoly_link" style="display: block;"><?php echo GetMessage('MYMV_SET_POLY_ADD')?></a>
		<div id="bx_yandex_addpoly_message" style="display: none;"><?php echo GetMessage('MYMV_SET_POLY_ADD_DESCRIPTION')?> <a href="javascript:void(0)" onclick="jsYandexCE.addPolyline(); return false;"><?php echo GetMessage('MYMV_SET_POLY_ADD_FINISH')?></a></div>
		<div id="bx_yandex_addpoly_message1" style="display: none;"><?php echo GetMessage('MYMV_SET_POLY_ADD_DESCRIPTION1')?></div>
	</div>
</div>
<?php 
$obJSPopup->StartButtons();
?>
<input type="submit" value="<?php echo GetMessage('MYMV_SET_SUBMIT')?>" onclick="return jsYandexCE.__saveChanges();" class="adm-btn-save"/>
<?php 
$obJSPopup->ShowStandardButtons(array('cancel'));
$obJSPopup->EndButtons();
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");?>