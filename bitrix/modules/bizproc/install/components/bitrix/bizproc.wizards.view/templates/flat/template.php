<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if (strlen($arResult["FatalErrorMessage"]) > 0)
{
	?>
	<span class='errortext'><?= $arResult["FatalErrorMessage"] ?></span><br /><br />
	<?php 
}
else
{
	if (strlen($arResult["ErrorMessage"]) > 0)
	{
		?>
		<span class='errortext'><?= $arResult["ErrorMessage"] ?></span><br /><br />
		<?php 
	}
	$arButtons = array(
		array(
			"TEXT"=>GetMessage("BPWC_WNCT_2LIST"),
			"TITLE"=>GetMessage("BPWC_WNCT_2LIST"),
			"LINK"=>$arResult["PATH_TO_LIST"],
			"ICON"=>"btn-list",
		),
	);
	$APPLICATION->IncludeComponent(
		"bitrix:main.interface.toolbar",
		"",
		array(
			"BUTTONS" => $arButtons
		),
		$component
	);
	?>
	<br>

	<table class="bpwiz1-view-form data-table" cellpadding="0" cellpadding="0" border="0">
		<tr>
			<th colspan="2"><?= $arResult["BP"]["NAME"] ?></th>
		</tr>
		<tr>
			<td valign="top" align="right"><?= GetMessage("BPWC_WNCT_STATE") ?>:</td>
			<td valign="top"><a href="<?= $arResult["PATH_TO_LOG"] ?>"><?= $arResult["BP"]["DOCUMENT_STATE"]["STATE_TITLE"] ?></a></td>
		</tr>
		<?php 
		if (count($arResult["BP"]["DOCUMENT_STATE_TASKS"]) > 0)
		{
			?>
			<tr>
				<td valign="top" align="right"><?= GetMessage("BPWC_WNCT_TASKS") ?>:</td>
				<td valign="top"><?php 
					foreach ($arResult["BP"]["DOCUMENT_STATE_TASKS"] as $arTask)
						echo '<a href="'.$arTask["URL"].'" onclick="" title="'.strip_tags($arTask["DESCRIPTION"]).'">'.$arTask["NAME"].'</a><br />';
				?></td>
			</tr>
			<?php 
		}
		?>
		<?php 
		if (count($arResult["BP"]["DOCUMENT_STATE_EVENTS"]) > 0)
		{
			?>
			<tr>
				<td valign="top" align="right"><?= GetMessage("BPWC_WNCT_EVENTS") ?>:</td>
				<td valign="top"><?php 
					foreach ($arResult["BP"]["DOCUMENT_STATE_EVENTS"] as $e)
						echo '<a href="'.$e["URL"].'">'.$e["TITLE"].'</a><br />';
				?></td>
			</tr>
			<?php 
		}
		?>
		<?php 
		if (count($arResult["Block"]["VISIBLE_FIELDS"]) <= 0 || in_array("NAME", $arResult["Block"]["VISIBLE_FIELDS"]))
		{
			?>
			<tr>
				<td valign="top" align="right"><?= GetMessage("BPWC_WNCT_NAME") ?>:</td>
				<td valign="top"><?= $arResult["BP"]["NAME"] ?></td>
			</tr>
			<?php 
		}
		?>
		<?php 
		foreach ($arResult["DocumentFields"] as $key => $value)
		{
			if (count($arResult["Block"]["VISIBLE_FIELDS"]) > 0 && !in_array($key, $arResult["Block"]["VISIBLE_FIELDS"]))
				continue;
			if ($key == "NAME")
				continue;
			?>
			<tr>
				<td valign="top" align="right"><?= $value["Name"] ?>:</td>
				<td valign="top"><?= $arResult["BP"][$key] ?></td>
			</tr>
			<?php 
		}
		?>
	</table>
	<?php 
}
?>