<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$GLOBALS['APPLICATION']->AddHeadScript('/bitrix/js/main/utils.js');
CBPDocument::AddShowParameterInit($arParams["DOCUMENT_TYPE"][0], "only_users", $arParams["DOCUMENT_TYPE"][2], $arParams["DOCUMENT_TYPE"][1]);
?>
<div class="bizproc-page-workflow-start">
	<?php 
	if (!empty($arResult["ERROR_MESSAGE"])):
		ShowError($arResult["ERROR_MESSAGE"]);
	endif;

	/** @var CBPDocumentService $documentService */
	$documentService = $arResult["DocumentService"];
?>
	<form method="post" name="start_workflow_form1" action="<?=POST_FORM_ACTION_URI?>" enctype="multipart/form-data" data-role="bizproc-start-form">
		<?= bitrix_sessid_post() ?>
		<input type="hidden" name="site" value="<?= SITE_ID ?>">
		<input type="hidden" name="document_type" value="<?= htmlspecialcharsbx($arParams["DOCUMENT_TYPE"][2]) ?>">
		<input type="hidden" name="auto_execute_type" value="<?= htmlspecialcharsbx($arResult["EXEC_TYPE"]) ?>">
		<?php foreach ($arResult['TEMPLATES'] as $template):?>
		<fieldset class="bizproc-item bizproc-workflow-template bizproc-workflow-template-parameters">
			<legend class="bizproc-item-legend bizproc-workflow-template-title">
				<?= htmlspecialcharsbx($template["NAME"])?>
			</legend>
			<?php if($template["DESCRIPTION"]!=''):?>
				<div class="bizproc-item-description bizproc-workflow-template-description">
					<?= htmlspecialcharsbx($template["DESCRIPTION"]) ?>
				</div>
			<?php endif;

			if (!empty($template["PARAMETERS"]))
			{
				?>
				<div class="bizproc-item-text">
					<ul class="bizproc-list bizproc-workflow-template-params">
						<?php 
						foreach ($template["PARAMETERS"] as $parameterKey => $arParameter)
						{
							?>
							<li class="bizproc-list-item bizproc-workflow-template-param">
								<div class="bizproc-field bizproc-field-<?=htmlspecialcharsbx($arParameter["Type"])?>">
									<label class="bizproc-field-name">
										<?=($arParameter["Required"] ? "<span class=\"required\">*</span> " : "")?>
										<span class="bizproc-field-title"><?=htmlspecialcharsbx($arParameter["Name"])?></span><?php 
										if (strlen($arParameter["Description"]) > 0):
											?><span class="bizproc-field-description"> (<?=htmlspecialcharsbx($arParameter["Description"])?>)</span><?php 
										endif;
										?>:
									</label>
									<span class="bizproc-field-value"><?php 
										echo $documentService->GetFieldInputControl(
											$arParams["DOCUMENT_TYPE"],
											$arParameter,
											array("Form" => "start_workflow_form1", "Field" => $parameterKey),
											$arParameter['Default'],
											false,
											true
										);
										?></span>
								</div>
							</li>
							<?php 
						}
						?>
					</ul>
				</div>
				<?php 
			}
			?>
		</fieldset>
		<?php endforeach;?>
	</form>
</div>