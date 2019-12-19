<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);
?>
<script type="text/javascript">

if (!window.GLOBAL_arMapObjects)
	window.GLOBAL_arMapObjects = {};

function init_<?php echo $arParams['MAP_ID']?>()
{
	if (!window.ymaps)
		return;

	if(typeof window.GLOBAL_arMapObjects['<?php echo $arParams['MAP_ID']?>'] !== "undefined")
		return;

	var node = BX("BX_YMAP_<?php echo $arParams['MAP_ID']?>");
	node.innerHTML = '';

	var map = window.GLOBAL_arMapObjects['<?php echo $arParams['MAP_ID']?>'] = new ymaps.Map(node, {
		center: [<?php echo $arParams['INIT_MAP_LAT']?>, <?php echo $arParams['INIT_MAP_LON']?>],
		zoom: <?php echo $arParams['INIT_MAP_SCALE']?>,
		type: 'yandex#<?=$arResult['ALL_MAP_TYPES'][$arParams['INIT_MAP_TYPE']]?>'
	});

<?php 
foreach ($arResult['ALL_MAP_OPTIONS'] as $option => $method)
{
	if (in_array($option, $arParams['OPTIONS'])):
?>
	map.behaviors.enable("<?php echo $method?>");
<?php 
	else:
?>
	if (map.behaviors.isEnabled("<?php echo $method?>"))
		map.behaviors.disable("<?php echo $method?>");
<?php 
	endif;
}

foreach ($arResult['ALL_MAP_CONTROLS'] as $control => $method)
{
	if (in_array($control, $arParams['CONTROLS'])):
?>
	map.controls.add('<?=$method?>');
<?php 
	endif;
}


if ($arParams['DEV_MODE'] == 'Y'):
?>
	window.bYandexMapScriptsLoaded = true;
<?php 
endif;

if ($arParams['ONMAPREADY']):
?>
	if (window.<?php echo $arParams['ONMAPREADY']?>)
	{
<?php 
	if ($arParams['ONMAPREADY_PROPERTY']):
?>
		<?php echo $arParams['ONMAPREADY_PROPERTY']?> = map;
		window.<?php echo $arParams['ONMAPREADY']?>();
<?php 
	else:
?>
		window.<?php echo $arParams['ONMAPREADY']?>(map);
<?php 
	endif;
?>
	}
<?php 
endif;
?>
}
<?php 
if ($arParams['DEV_MODE'] == 'Y'):
?>
function BXMapLoader_<?php echo $arParams['MAP_ID']?>()
{
	if (null == window.bYandexMapScriptsLoaded)
	{
		function _wait_for_map(){
			if (window.ymaps && window.ymaps.Map)
				init_<?php echo $arParams['MAP_ID']?>();
			else
				setTimeout(_wait_for_map, 50);
		}

		BX.loadScript('<?=$arResult['MAPS_SCRIPT_URL']?>', _wait_for_map);
	}
	else
	{
		init_<?php echo $arParams['MAP_ID']?>();
	}
}
<?php 
	if ($arParams['WAIT_FOR_EVENT']):
?>
	<?=CUtil::JSEscape($arParams['WAIT_FOR_EVENT'])?> = BXMapLoader_<?=$arParams['MAP_ID']?>;
<?php 
	elseif ($arParams['WAIT_FOR_CUSTOM_EVENT']):
?>
	BX.addCustomEvent('<?=CUtil::JSEscape($arParams['WAIT_FOR_EVENT'])?>', BXMapLoader_<?=$arParams['MAP_ID']?>);
<?php 
	else:
?>
	BX.ready(BXMapLoader_<?php echo $arParams['MAP_ID']?>);
<?php 
	endif;
else: // $arParams['DEV_MODE'] == 'Y'
?>

(function bx_ymaps_waiter(){
	if(typeof ymaps !== 'undefined')
		ymaps.ready(init_<?php echo $arParams['MAP_ID']?>);
	else
		setTimeout(bx_ymaps_waiter, 100);
})();

<?php 
endif; // $arParams['DEV_MODE'] == 'Y'
?>

/* if map inits in hidden block (display:none)
*  after the block showed
*  for properly showing map this function must be called
*/
function BXMapYandexAfterShow(mapId)
{
	if(window.GLOBAL_arMapObjects[mapId] !== undefined)
		window.GLOBAL_arMapObjects[mapId].container.fitToViewport();
}

</script>
<div id="BX_YMAP_<?php echo $arParams['MAP_ID']?>" class="bx-yandex-map" style="height: <?php echo $arParams['MAP_HEIGHT'];?>; width: <?php echo $arParams['MAP_WIDTH']?>;"><?php echo GetMessage('MYS_LOADING'.($arParams['WAIT_FOR_EVENT'] ? '_WAIT' : ''));?></div>