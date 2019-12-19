<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="bp-setconstants <?php if ($arParams['POPUP']):?>bp-setconstants-popup<?php endif?>">
<?php 
if (strlen($arResult["FatalErrorMessage"]) > 0)
{
	?>
	<span class="bp-question"><span>!</span><?= htmlspecialcharsbx($arResult["FatalErrorMessage"]) ?></span>
	<?php 
}
else
{
	if (strlen($arResult["ErrorMessage"]) > 0)
	{
		?>
		<span class="bp-question"><span>!</span><?= htmlspecialcharsbx($arResult["ErrorMessage"]) ?></span>
		<?php 
	}
	if ($arResult['DESCRIPTION'])
	{
		?>
		<p><?= nl2br(htmlspecialcharsbx($arResult['DESCRIPTION'])) ?></p>
		<?php 
	}
	?>

	<form name="bizprocform" method="post" action="<?= POST_FORM_ACTION_URI ?>" enctype="multipart/form-data"<?php 
		if ($arParams['POPUP']):?> onsubmit="return function (form, e)
			{
				if (form.BPRUNNING)
					return;
				BX.PreventDefault(e);
				form.BPRUNNING = true;
				form.action = '/bitrix/components/bitrix/bizproc.workflow.setconstants/popup.php';
				BX.ajax.submit(form, function (response) {
					form.BPRUNNING = false;
					response = BX.parseJSON(response);
					if (response.ERROR_MESSAGE)
						alert(response.ERROR_MESSAGE);
					else
					{
						if(!!form.modalWindow)
							form.modalWindow.close();
						else
							BX.PopupWindowManager.getCurrentPopup().close();
					}
				});
				return false;
			}(this, event);"<?php endif
	?>>
		<?=bitrix_sessid_post()?>
		<input type="hidden" name="back_url" value="<?= htmlspecialcharsbx($arResult["BackUrl"]) ?>">
		<input type="hidden" name="ID" value="<?= $arParams["ID"] ?>">
		<input type="hidden" name="save_action" value="Y">
		<?php 
		foreach ($arResult["CONSTANTS"] as $parameterKey => $arParameter)
		{
			?>
			<span class="bp-question-title"><?= htmlspecialcharsbx($arParameter["Name"]) ?>:</span>
			<?php if (strlen($arParameter["Description"]) > 0):?>
			<p class="hint"><?=htmlspecialcharsbx($arParameter["Description"])?></p>
			<?php endif?>
			<div class="bp-question-item"><?php 
				echo $arResult["DocumentService"]->GetFieldInputControl(
					$arResult["DOCUMENT_TYPE"],
					$arParameter,
					array("Form" => "bizprocform", "Field" => $parameterKey),
					$arParameter["Default"],
					false,
					true
				);
				?>
			</div>
			<div class="bp-question-divider"></div>
			<?php 
		}
		if (count($arResult["CONSTANTS"]) <= 0)
		{
			?>
			<span class="bp-question"><span>!</span><?= GetMessage("BPWFSCT_EMPTY") ?></span>
			<?php 
		}
		?>
		<?php if (!$arParams['POPUP'] && $arResult["CONSTANTS"]):?>
		<div class="bp-question-item">
			<input type="submit" value="<?= GetMessage("BPWFSCT_SAVE") ?>" class="ui-btn ui-btn-success">
		</div>
		<?php endif?>
	</form>
	<?php 
}
?>
</div>