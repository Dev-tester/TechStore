<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if ($arResult["NEED_AUTH"] == "Y")
{
}
elseif (strlen($arResult["FatalError"])>0)
{
	?>
	<span class='errortext'><?=$arResult["FatalError"]?></span><br /><br />
	<?php 
}
else
{
	if(strlen($arResult["ErrorMessage"])>0)
	{
		?>
		<span class='errortext'><?=$arResult["ErrorMessage"]?></span><br /><br />
		<?php 
	}
	?>
	<?php 
	if ($arResult["Events"] && is_array($arResult["Events"]) && count($arResult["Events"]) > 0)
	{
		$ind = 0;
		$bFirst = true;
		foreach ($arResult["Events"] as $date => $arEvents)
		{
			if (!$bFirst)
			{
				?><div class="sonet-profile-line"></div><?php 
			}
			?>
			<?= $date ?><br />
			<?php 
			foreach ($arEvents as $arEvent)
			{
				?>
				<br /><span class="sonet-log-date"><?=$arEvent["LOG_TIME_FORMAT"]?></span><br />
				<?= $arEvent["TITLE_FORMAT"] ?>
				<?php 
				$bFirst = false;
				$ind++;
			}
			?>
			<?php 
		}
	}
	else
	{
		echo GetMessage("SONET_ACTIVITY_T_NO_UPDATES");
	}
}
?>