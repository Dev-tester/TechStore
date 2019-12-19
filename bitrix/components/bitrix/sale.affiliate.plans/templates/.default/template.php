<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?php 

if (count($arResult) > 0)
{
	?>
	<ul>
	<?php 
	foreach ($arResult as $arPlan)
	{
		?>
		<li><b><?=$arPlan["NAME"]?></b><br />
		<?php 
		if (StrLen($arPlan["DESCRIPTION"]) > 0)
		{
		?>
			<small><?=$arPlan["DESCRIPTION"]?></small><br />
		<?php 
		}
		?>
		<?=GetMessage("SPCAT1_TARIF")?>
		<?=$arPlan["BASE_RATE_FORMAT"] ?>
		<br />
		<?php 
		if ($arPlan["MIN_PLAN_VALUE"] > 0)
		{?>
			<?=$arPlan["MIN_PLAN_VALUE_FORMAT"]?>
			<br />
		<?php 
		}
		?>
		</li>
		<?php 
	}
	?>
	</ul>
	<?php 
}
else
{
	?>
	<?=ShowError(GetMessage("SPCAT1_NO_PLANS"))?>
	<?php 
}
?>