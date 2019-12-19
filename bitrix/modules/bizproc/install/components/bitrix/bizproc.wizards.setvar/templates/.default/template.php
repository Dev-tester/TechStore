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
			"TEXT"=>GetMessage("BPWC_WVCT_2LIST"),
			"TITLE"=>GetMessage("BPWC_WVCT_2LIST"),
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

	<form name="bizprocform" method="post" action="<?= POST_FORM_ACTION_URI ?>" enctype="multipart/form-data">
		<input type="hidden" name="back_url" value="<?= htmlspecialcharsbx($arResult["BackUrl"]) ?>">
		<?=bitrix_sessid_post()?>

		<table class="bpwiz1-view-form data-table" cellpadding="0" cellspacing="0">
		<tr>
			<th colspan="2"><?= GetMessage("BPWC_WVCT_SUBTITLE") ?></th>
		</tr>
		<?php 
		foreach ($arResult["WorkflowVariables"] as $parameterKey => $arParameter)
		{
			?>
			<tr>
				<td align="right" width="40%" valign="top"><?= $arParameter["Required"] ? "<span style=\"color:red\">*</span> " : ""?><?= htmlspecialcharsbx($arParameter["Name"]) ?>:<?php if (strlen($arParameter["Description"]) > 0) echo "<br /><small>".htmlspecialcharsbx($arParameter["Description"])."</small><br />";?></td>
				<td width="60%" valign="top"><?php 
					echo $arResult["DocumentService"]->GetFieldInputControl(
						array("bizproc", "CBPVirtualDocument", "type_".$arResult["Block"]["ID"]),
						$arParameter,
						array("Form" => "bizprocform", "Field" => $parameterKey),
						$arParameter["Default"],
						false,
						true
					);
				?></td>
			</tr>
			<?php 
		}
		if (count($arResult["WorkflowVariables"]) <= 0)
		{
			?>
			<tr><td><?= GetMessage("BPWC_WVCT_EMPTY") ?></td></tr>
			<?php 
		}
		?>
		</table>
		<br><br>

		<input type="submit" name="save_variables" value="<?= GetMessage("BPWC_WVCT_SAVE") ?>">
		<input type="submit" name="apply_variables" value="<?= GetMessage("BPWC_WVCT_APPLY") ?>">
		<input type="submit" name="cancel_variables"  value="<?= GetMessage("BPWC_WVCT_CANCEL") ?>">
	</form>
	<?php 
}
?>