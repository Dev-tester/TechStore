<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/interface/navigation.php");
IncludeModuleLangFile(__FILE__);

$nav_func_name = 'GetAdminList';

$sQueryString = CUtil::JSUrlEscape($strNavQueryString);
$sJSUrlPath = CUtil::JSUrlEscape($sUrlPath);

if($this->NavRecordCount>0)
{
?>
<div class="adm-navigation">
	<div class="adm-nav-pages-block">
<?php 
	if($this->NavPageNomer > 1)
	{
?>
		<a class="adm-nav-page adm-nav-page-prev" href="javascript:<?php echo $this->table_id?>.<?=$nav_func_name?>('<?php echo $sJSUrlPath.'?PAGEN_'.$this->NavNum.'='.($this->NavPageNomer-1).'&amp;SIZEN_'.$this->NavNum.'='.$this->NavPageSize.$sQueryString;?>');"><span class="adm-subnav-page-prev-before"></span></a>
<?php 
	}
	else //$this->NavPageNomer > 1
	{
?>
		<span class="adm-nav-page adm-nav-page-prev"><span class="adm-subnav-page-prev-before"></span></span>
<?php 
	} //$this->NavPageNomer > 1

	//$NavRecordGroup = $this->nStartPage;
	$NavRecordGroup = 1;
	while($NavRecordGroup <= $this->NavPageCount)
	{
		if($NavRecordGroup == $this->NavPageNomer)
		{
?>
		<span class="adm-nav-page-active adm-nav-page"><?=$NavRecordGroup?></span>
<?php 
		}
		else // ($NavRecordGroup == $this->NavPageNomer):
		{
?>
		<a href="javascript:<?=$this->table_id?>.<?=$nav_func_name?>('<?=$sJSUrlPath.'?PAGEN_'.$this->NavNum.'='.$NavRecordGroup.'&amp;SIZEN_'.$this->NavNum.'='.$this->NavPageSize.$sQueryString?>');" class="adm-nav-page"><?=$NavRecordGroup?></a>
<?php 
		} //endif($NavRecordGroup == $this->NavPageNomer):

		if($NavRecordGroup == 2 && $this->nStartPage > 3)
		{
			if($this->nStartPage - $NavRecordGroup > 1)
			{
				$middlePage = ceil(($this->nStartPage + $NavRecordGroup)/2);
?>
		<a href="javascript:<?=$this->table_id?>.<?=$nav_func_name?>('<?=$sJSUrlPath.'?PAGEN_'.$this->NavNum.'='.$middlePage.'&amp;SIZEN_'.$this->NavNum.'='.$this->NavPageSize.$sQueryString?>');" class="adm-nav-page-separator"><?=$middlePage?></a>
<?php 
			}
			$NavRecordGroup = $this->nStartPage;
		}
		elseif($NavRecordGroup == $this->nEndPage && $this->nEndPage < $this->NavPageCount - 2)
		{
			if( $this->NavPageCount-1 - $NavRecordGroup > 1)
			{
				$middlePage = floor(($this->NavPageCount + $this->nEndPage - 1)/2);
?>
		<a href="javascript:<?=$this->table_id?>.<?=$nav_func_name?>('<?=$sJSUrlPath.'?PAGEN_'.$this->NavNum.'='.$middlePage.'&amp;SIZEN_'.$this->NavNum.'='.$this->NavPageSize.$sQueryString?>');" class="adm-nav-page-separator"><?=$middlePage?></a>
<?php 
			}

			$NavRecordGroup = $this->NavPageCount-1;
		}
		else
		{
			$NavRecordGroup++;
		}

	} // endwhile;//($NavRecordGroup <= $this->nEndPage):

	if($this->NavPageNomer < $this->NavPageCount)
	{
?>
		<a class="adm-nav-page adm-nav-page-next" href="javascript:<?php echo $this->table_id?>.<?=$nav_func_name?>('<?php echo $sJSUrlPath.'?PAGEN_'.$this->NavNum.'='.($this->NavPageNomer+1).'&amp;SIZEN_'.$this->NavNum.'='.$this->NavPageSize.$sQueryString;?>');"><span class="adm-subnav-page-next-before"></span></a>
<?php 
	}
	else //($this->NavPageNomer < $this->NavPageCount):
	{
?>
		<span class="adm-nav-page adm-nav-page-next"><span class="adm-subnav-page-next-before"></span></span>
<?php 
	} //endif; //($this->NavPageNomer < $this->NavPageCount):
?>
	</div>
<?php 
	if($this->NavRecordCount>0)
	{
?>
	<div class="adm-nav-pages-total-block"><?php 
	echo $title." ".(($this->NavPageNomer-1)*$this->NavPageSize+1)." &ndash; ";
	if($this->NavPageNomer <> $this->NavPageCount)
		echo($this->NavPageNomer * $this->NavPageSize);
	else
		echo($this->NavRecordCount);
	echo " ".GetMessage("navigation_records_of")." ".$this->NavRecordCount;
	?></div>
<?php 
	} // endif($this->NavRecordCount>0);
?>
	<div class="adm-nav-pages-number-block"><span class="adm-nav-pages-number">
		<?php if(!$this->NavRecordCountChangeDisable)
		{
			?><span class="adm-nav-pages-number-text"><?php echo GetMessage("navigation_records")?></span><span class="adm-select-wrap"><select name="" class="adm-select" onchange="if(this[selectedIndex].value=='0'){<?php echo $this->table_id?>.<?=$nav_func_name?>('<?php echo $sJSUrlPath."?PAGEN_".$this->NavNum."=1&amp;SHOWALL_".$this->NavNum."=1".CUtil::addslashes($strNavQueryString);?>');}else{<?php echo $this->table_id?>.<?=$nav_func_name?>('<?php echo $sJSUrlPath."?PAGEN_".$this->NavNum."=1&amp;SHOWALL_".$this->NavNum."=0"."&amp;SIZEN_".$this->NavNum."="?>'+this[selectedIndex].value+'<?php echo CUtil::addslashes($strNavQueryString);?>');}">
<?php 
	$aSizes = array(10, 20, 50, 100, 200, 500);
	if($this->nInitialSize > 0 && !in_array($this->nInitialSize, $aSizes))
		array_unshift($aSizes, $this->nInitialSize);
	$reqSize = intval($_REQUEST["SIZEN_".$this->NavNum]);
	if($reqSize > 0 && !in_array($reqSize, $aSizes))
		array_unshift($aSizes, $reqSize);
	foreach($aSizes as $size)
	{
?>
		<option value="<?php echo $size?>"<?php if($this->NavPageSize == $size)echo ' selected="selected"'?>><?php echo $size?></option>
<?php 
	} //endforeach;

	if($this->bShowAll)
	{
?>
			<option value="0"<?php if($this->NavShowAll) echo ' selected="selected"'?>><?php echo GetMessage("navigation_records_all")?></option>
<?php 
	} //endif;
?>
	</select><?php }?></span></span></div>
</div>
<?php 
} //endif; //$this->NavRecordCount>0;
?>