<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);
$arAllMapOptions = array_merge($arResult['ALL_MAP_OPTIONS'], $arResult['ALL_MAP_CONTROLS']);
$arMapOptions = array_merge($arParams['OPTIONS'], $arParams['CONTROLS']);
?>
<script>
if (!window.GLOBAL_arMapObjects)
	window.GLOBAL_arMapObjects = {};

function init_<?php echo $arParams['MAP_ID']?>()
{
	if (!window.google || !window.google.maps)
		return;

	var opts = {
		zoom: <?php echo $arParams['INIT_MAP_SCALE']?>,
		center: new google.maps.LatLng(<?php echo $arParams['INIT_MAP_LAT']?>, <?php echo $arParams['INIT_MAP_LON']?>),
<?php 
foreach ($arAllMapOptions as $option => $method)
{

	echo "\t\t".(
		in_array($option, $arMapOptions)
		? str_replace(array('#true#', '#false#'), array('true', 'false'), $method)
		: str_replace(array('#false#', '#true#'), array('true', 'false'), $method)
	).",\r\n";
}
?>

		mapTypeId: google.maps.MapTypeId.<?php echo $arParams['INIT_MAP_TYPE']?>

	};

	window.GLOBAL_arMapObjects['<?php echo $arParams['MAP_ID']?>'] = new window.google.maps.Map(BX("BX_GMAP_<?php echo $arParams['MAP_ID']?>"), opts);

<?php 
if ($arParams['DEV_MODE'] == 'Y'):
?>
	window.bGoogleMapScriptsLoaded = true;
<?php 
endif;
?>
}

<?php 
if ($arParams['DEV_MODE'] == 'Y'):
?>
function BXMapLoader_<?php echo $arParams['MAP_ID']?>(MAP_KEY)
{
	if (null == window.bGoogleMapScriptsLoaded)
	{
		if (window.google && window.google.maps)
		{
			window.bGoogleMapScriptsLoaded = true;
			BX.ready(init_<?php echo $arParams['MAP_ID']?>);
		}
		else
		{
			if(window.bGoogleMapsScriptLoading)
			{
				window.bInt<?php echo $arParams['MAP_ID']?> = setInterval(
					function()
					{
						if(window.bGoogleMapScriptsLoaded)
						{
							clearInterval(window.bInt<?php echo $arParams['MAP_ID']?>);
							init_<?php echo $arParams['MAP_ID']?>();
						}
						else
							return;
					},
					500
				);

				return;
			}

			window.bGoogleMapsScriptLoading = true;

			<?php $scheme = (CMain::IsHTTPS() ? "https" : "http");?>

			BX.loadScript(
				'<?=$scheme?>://www.google.com/jsapi?key=<?=$arParams['API_KEY']?>&rnd=' + Math.random(),
				function ()
				{
					if (BX.browser.IsIE())
						setTimeout("window.google.load('maps', <?= intval($arParams['GOOGLE_VERSION'])?>, {callback: init_<?php echo $arParams['MAP_ID']?>, other_params: 'language=<?=LANGUAGE_ID?>&key=<?=$arParams['API_KEY']?>'})", 1000);
					else
						google.load('maps', <?php echo intval($arParams['GOOGLE_VERSION'])?>, {callback: init_<?php echo $arParams['MAP_ID']?>, other_params: 'language=<?=LANGUAGE_ID?>&key=<?=$arParams['API_KEY']?>'});
				}
			);
		}
	}
	else
	{
		init_<?php echo $arParams['MAP_ID']?>();
	}
}
<?php 
	if (!$arParams['WAIT_FOR_EVENT']):
?>
BXMapLoader_<?php echo $arParams['MAP_ID']?>('<?php echo $arParams['KEY']?>');
<?php 
	else:
		echo CUtil::JSEscape($arParams['WAIT_FOR_EVENT']),' = BXMapLoader_',$arParams['MAP_ID'],';';
	endif;
else:
?>
BX.ready(init_<?php echo $arParams['MAP_ID']?>);
<?php 
endif;
?>

/* if map inits in hidden block (display:none),
*  after the block showed,
*  for properly showing map this function must be called
*/
function BXMapGoogleAfterShow(mapId)
{
	if(google.maps !== undefined && window.GLOBAL_arMapObjects[mapId] !== undefined)
		google.maps.event.trigger(window.GLOBAL_arMapObjects[mapId],'resize');
}

</script>
<div id="BX_GMAP_<?php echo $arParams['MAP_ID']?>" class="bx-google-map" style="height: <?php echo $arParams['MAP_HEIGHT'];?>; width: <?php echo $arParams['MAP_WIDTH']?>;"><?php echo GetMessage('MYS_LOADING'.($arParams['WAIT_FOR_EVENT'] ? '_WAIT' : ''));?></div>