<?php  if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var \Bitrix\Bizproc\Activity\PropertiesDialog $dialog */

$documentType = $dialog->getDocumentType();
$useAutoComplete = $documentType[1] === 'CCrmDocumentLead' || $documentType[1] === 'CCrmDocumentDeal';
$map = $dialog->getMap();
if (!$useAutoComplete)
{
	unset($map['AutoComplete']);
}

foreach ($map as $fieldId => $field):
?>
<tr>
	<td align="right" width="40%">
		<?php if (!empty($field['Required'])):?><span class="adm-required-field"><?php endif;?>
		<?=htmlspecialcharsbx($field['Name'])?>:
		<?php if (!empty($field['Required'])):?></span><?php endif;?>
	</td>
	<td width="60%">
		<?php  $filedType = $dialog->getFieldTypeObject($field);

		echo $filedType->renderControl(array(
			'Form' => $dialog->getFormName(),
			'Field' => $field['FieldName']
		), $dialog->getCurrentValue($field['FieldName']), true, 0);
		?>
	</td>
</tr>
<?php endforeach;?>