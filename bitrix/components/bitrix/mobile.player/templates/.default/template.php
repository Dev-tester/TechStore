<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?><div class="disk-mobile-player-container">
	<video preload="metadata"<?php 
if($arParams['HEIGHT'])
{
	?> style="max-height: <?=intval($arParams['HEIGHT']);?>px;"<?php 
}
if(isset($arParams['PLAYER_ID']))
{
	?> id="<?=htmlspecialcharsbx($arParams['PLAYER_ID']);?>"<?php 
}
?> poster="<?=htmlspecialcharsbx($arParams['PREVIEW']);?>"<?php 
?> controls controlsList="nodownload"><?php 
if($arParams['USE_PLAYLIST_AS_SOURCES'] === 'Y' && is_array($arParams['TRACKS']))
{
	foreach($arParams['TRACKS'] as $key => $source)
	{
		?>
		<source src="<?=htmlspecialcharsbx($source['src']);?>" type="<?=htmlspecialcharsbx($source['type']);?>"
        onerror="BX.onCustomEvent(this, 'MobilePlayer:onError', [this.parentNode, this.src]);">
		<?php 
	}
}
else
{
	?>
	<source src="<?=htmlspecialcharsbx($arParams['PATH']);?>" type="<?=htmlspecialcharsbx($arParams['TYPE']);?>"
    onerror="BX.onCustomEvent(this, 'MobilePlayer:onError', [this.parentNode, this.src]);"
	><?php 
}
?>
	</video>
</div>