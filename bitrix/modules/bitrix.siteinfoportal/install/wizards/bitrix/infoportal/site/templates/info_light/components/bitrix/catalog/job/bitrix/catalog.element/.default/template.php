<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="job-element">
<?php $showRequirements = true;?>
<div class="job-prop-text">
<?php foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
	<?php if($arProperty["SORT"] < 501): 
		if($showRequirements): $showRequirements = false;?><div class="job-prop-title"><?=GetMessage("JOB_REQUIREMENTS")?></div><?php endif;?>
		<div><?=$arProperty["NAME"]?>&nbsp;-&nbsp;
		<?php 
		if(is_array($arProperty["DISPLAY_VALUE"])):
			echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
		elseif($pid=="MANUAL"):
			?><a href="<?=$arProperty["VALUE"]?>"><?=GetMessage("CATALOG_DOWNLOAD")?></a><?php 
		else:
			echo $arProperty["DISPLAY_VALUE"];?>
		<?php endif?>
		</div>
	<?php endif;?>
<?php endforeach?>
</div>
<?php foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
	<?php if($arProperty["SORT"] > 500 && $arProperty["SORT"] < 800 ):?>
		<div class="job-prop-title"><?=$arProperty["NAME"]?>:</div>
		<div class="job-prop-text">
			<?php 
			if(is_array($arProperty["DISPLAY_VALUE"])):
				echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
			elseif($pid=="MANUAL"):
				?><a href="<?=$arProperty["VALUE"]?>"><?=GetMessage("CATALOG_DOWNLOAD")?></a><?php 
			else:
				echo $arProperty["DISPLAY_VALUE"];?>
			<?php endif?>
		</div>
	<?php endif;?>
<?php endforeach?>
<?php if(!empty($arResult["PREVIEW_TEXT"])):?>
	<div class="job-prop-title"><?=GetMessage("JOB_DESCRIPTION")?></div>
	<div class="job-prop-text"><?=$arResult["PREVIEW_TEXT"]?></div>
<?php endif;?>
<div class="job-prop-text">
<?php $showEmployer = true;?>
<?php foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
	<?php if($arProperty["SORT"] > 799): 
		if($showEmployer): $showEmployer = false;?><div class="job-prop-title"><?=GetMessage("JOB_EMPLOYER")?></div><?php endif;?>
		<div><?php if($pid != 'FIRM'):?><?=$arProperty["NAME"]?>:&nbsp;<?php endif;?>
		<?php 
		if(is_array($arProperty["DISPLAY_VALUE"])):
			echo implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);
		elseif($pid=="MANUAL"):
			?><a href="<?=$arProperty["VALUE"]?>"><?=GetMessage("CATALOG_DOWNLOAD")?></a><?php 
		else:
			echo $arProperty["DISPLAY_VALUE"];?>
		<?php endif?>
		</div>
	<?php endif;?>
<?php endforeach?>
</div>


<?php if(is_array($arResult["SECTION"])):?>
	<br /><a href="<?=$arResult["SECTION"]["SECTION_PAGE_URL"]?>"><?=GetMessage("CATALOG_BACK")?></a>
<?php endif?>
</div>
