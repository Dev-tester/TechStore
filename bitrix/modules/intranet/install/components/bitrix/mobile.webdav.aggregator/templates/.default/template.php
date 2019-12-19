<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<div id='wd_aggregator_tree'>
<ul data-role="listview" data-inset="true">
<?php 
foreach ($arResult['STRUCTURE'] as $node)
{
	//$link = rtrim($arParams['SEF_FOLDER'],'/') . $node['PATH'];
	$link = ($node['PATH']);

	$arLink = explode('/', $link);
	foreach ($arLink as $i => &$lnk)
		$lnk = urlencode($lnk);
	$link = implode('/', $arLink);

	?><li><?php 
	if($node["TYPE"] == "file")
	{
		?><a data-icon="none" href="<?=$link?>" rel="external"><img class="ui-li-icon" src="<?=$templateFolder?>/images/icons/ic<?=substr($node["FILE_EXTENTION"], 1)?>.gif" border="0"><?=$node['NAME']?></a><?php 
	}
	elseif($node["TYPE"] == "up")
	{
		?><a href="<?=$link?>" data-rel="back"><img class="ui-li-icon" src="<?=$templateFolder?>/images/icons/up.gif" border="0">..</a><?php 
	}
	else
	{
		?><a href="<?=$link?>"><img class="ui-li-icon" src="<?=$templateFolder?>/images/icons/section.gif" border="0"><?=$node['NAME']?></a><?php 
	}
	?></li><?php 
}
?>
</ul>
</div>
