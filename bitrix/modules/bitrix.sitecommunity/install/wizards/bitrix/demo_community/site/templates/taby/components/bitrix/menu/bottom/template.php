<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult)):?>
	<?php  
	$firstNum = 0; 
	$lastNum = 0;

	for ($i = 0; $i < count($arResult); $i++)
	{
		if ($arResult[$i]["PERMISSION"] <= "D") 
			continue;
		if ($firstNum == 0) $firstNum = $i;
		$lastNum = i;
	}

	?>
	<ul id="footer-links">
	<?php 
	for ($i = 0; $i < count($arResult); $i++ )
	{
		$arItem = $arResult[$i];
		if ($arItem["PERMISSION"] > "D"):
	
			$cssClass = "";
			if ($i == $firstNum) 
				$cssClass .= (strlen($cssClass) > 0 ? " first-item" : "first-item");
				
			if ($arItem["SELECTED"]) 
				$cssClass .= (strlen($cssClass) > 0 ? " selected" : "selected");
				
			if ($i == $lastNum) 
				$cssClass .= (strlen($cssClass) > 0 ? " last-item" : "last-item");
                
			?>
			<li <?php  if (strlen($cssClass) > 0) { ?>class="<?= $cssClass ?>"<?php  } ?>>
				<a href="<?=$arItem["LINK"]?>"><span><?=$arItem["TEXT"]?></span></a>
		            </li>
			<?php 
		endif;
	};
	?>
	</ul>
	<?php 
    ?>
<?php  endif; ?>