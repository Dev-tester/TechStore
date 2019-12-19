<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/xml.php');

$APPLICATION->SetAdditionalCSS('/bitrix/gadgets/bitrix/weather/styles.css');

if($arGadgetParams["CITY"]!='')
	$url = 'region='.substr($arGadgetParams["CITY"], 1).'&ts='.time();
else
	$url = 'ts='.time();

$cache = new CPageCache();
if($arGadgetParams["CACHE_TIME"]>0 && !$cache->StartDataCache($arGadgetParams["CACHE_TIME"], 'c'.$arGadgetParams["CITY"], "gdweather"))
	return;

$http = new \Bitrix\Main\Web\HttpClient();
$http->setTimeout(10);
$res = $http->get("https://export.yandex.ru/bar/reginfo.xml?".$url);

$res = str_replace("\xE2\x88\x92", "-", $res);
$res = $APPLICATION->ConvertCharset($res, 'UTF-8', SITE_CHARSET);

$xml = new CDataXML();
$xml->LoadString($res);
$node = $xml->SelectNodes('/info/region/title');
?>
<h3><?=$node->content?></h3>

<?php 
$node = $xml->SelectNodes('/info/weather/day/day_part/temperature');
$t = Intval($node->content);
?>
<table width="90%">
<tr>
<td nowrap="yes" width="20%"><span class="t<?=intval($t/10)?>"><?=$node->content?></span></td>
<td width="20%"><?php $node = $xml->SelectNodes('/info/weather/day/day_part/image-v3');?><img src="<?=$node->content?>" class="gdwico"></td>
<td width="60%" nowrap>
<?php $node = $xml->SelectNodes('/info/weather/day/day_part/weather_type');?>
<span class="gdweather"><?=$node->content?></span><br>
<span class="gdwinfo">
<?php $node = $xml->SelectNodes('/info/weather/day/day_part/wind_direction');?>
Ветер: <?=$node->content?>, <?php $node = $xml->SelectNodes('/info/weather/day/day_part/wind_speed');?><?=$node->content?> м/сек. <br>
<?php $node = $xml->SelectNodes('/info/weather/day/day_part/pressure');?>
Давление: <?=$node->content?> мм.рт.ст.<br>
<?php $node = $xml->SelectNodes('/info/weather/day/day_part/dampness');?>
Влажность: <?=$node->content?>%<br>

<?php $node = $xml->SelectNodes('/info/weather/day/sun_rise');?>
Восход: <?=$node->content?><br>
<?php $node = $xml->SelectNodes('/info/weather/day/sunset');?>
Заход: <?=$node->content?>

</span>
</td>
</tr>

<?php $node = $xml->SelectNodes('/info/weather/tonight/temperature');?>
<?php if($node):?>
<tr>
<td>Ночью:</td>
<td colspan="2"><?=$node->content?>°C</td>
</tr>
<?php endif?>

<?php $node = $xml->SelectNodes('/info/weather/tomorrow/temperature');?>
<?php if($node):?>
<tr>
<td>Завтра:</td>
<td colspan="2"><?=$node->content?>°C</td>
</tr>
<?php endif?>
</table>
<?php if($arGadgetParams["SHOW_URL"]=="Y"):?>
<br />
<?php $node = $xml->SelectNodes('/info/weather/url');?>
<a href="<?=htmlspecialcharsbx($node->content)?>">Подробнее</a> <a href="<?=htmlspecialcharsbx($node->content)?>"><img width="7" height="7" border="0" src="/bitrix/components/bitrix/desktop/images/arrows.gif" /></a>
<br />
<?php endif?>

<?php $cache->EndDataCache();?>
