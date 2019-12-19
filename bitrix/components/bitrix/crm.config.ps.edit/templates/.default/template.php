<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$isSidePanel = (isset($_REQUEST['IFRAME']) && $_REQUEST['IFRAME'] === 'Y');

global $APPLICATION;

$userPropsList = '';
if ($arResult['USER_COLUMN_FIELDS'])
{
	$menuItems = array();
	foreach ($arResult['USER_COLUMN_FIELDS'] as $id => $name)
	{
		$name = CUtil::addslashes($name);
		$menuItems[] = '{text : \''.$name.'\', onclick : function() {BX.crmPSActionFile.addUserColumn(\''.$id.'\', \''.$name.'\');}}';
	}

	$userPropsList = '<input type="hidden" name="TYPE_USER_COLUMNS" id="TYPE_USER_COLUMNS" value="USER_COLUMN_LIST"><span onclick="BX.PopupMenu.show(\'user-props-list\', event, ['.implode(',', $menuItems).'], {angle : {offset : 50, position : \'top\'}});" class="crm-add-user-props">'.Loc::getMessage('CRM_ADD_USER_PROP').'</span>';
}

$formStyle = ($isSidePanel ? 'padding: 15px' : '');

ob_start();
?>
<div style="<?=$formStyle?>">
<form name="form_<?=$arResult["FORM_ID"]?>" id="form_<?=$arResult["FORM_ID"]?>" action="<?=POST_FORM_ACTION_URI?>" method="POST" enctype="multipart/form-data">
	<?php 
	if ($isSidePanel && !empty($arResult['SIDE_PANEL_ERROR']))
	{
		showError($arResult['SIDE_PANEL_ERROR']);
	}
	?>
	<input type="hidden" name="PS_ACTION_FIELDS_LIST" id="PS_ACTION_FIELDS_LIST" value="<?=$arResult['ACTION_FIELDS_LIST'];?>"/>
	<input type="hidden" name="ps_id" id="ps_id" value="<?=$arResult['PS_ID'];?>"/>
	<?=bitrix_sessid_post();?>
	<table width="100%" class="bx-settings-table">
		<tbody id="PS_INFO">
			<?php if ($arResult['PAY_SYSTEM']['ID']): ?>
				<tr>
					<td width="40%" class="bx-field-name">ID: </td>
					<td class="bx-field-value"><?=$arResult['PAY_SYSTEM']['ID'] ?: ''?></td>
				</tr>
			<?php endif;?>
			<tr>
				<td width="40%" class="bx-field-name"><?=Loc::getMessage('CRM_PS_FIELD_NAME')?>: </td>
				<td class="bx-field-value"><input type="text" name="NAME" value="<?=$arResult['PAY_SYSTEM']['NAME'] ? htmlspecialcharsbx($arResult['PAY_SYSTEM']['NAME']) : ''?>"></td>
			</tr>
			<tr>
				<td width="40%" class="bx-field-name"><?=Loc::getMessage('CRM_PS_FIELD_ACTION_FILE')?>: </td>
				<td class="bx-field-value">
					<select name="ACTION_FILE" id="ACTION_FILE">
						<?php foreach ($arResult['PAY_SYSTEM_LIST'] as $id => $name):?>
							<option value="<?=$id;?>" <?=(strpos($arResult['PAY_SYSTEM']['ACTION_FILE'], $id) !== false) ? 'selected' : ''?>><?=$name;?></option>
						<?php endforeach;?>
					</select>
				</td>
			</tr>
			<?php 
				$isInvoiceHandler = strpos($arResult['PAY_SYSTEM']['ACTION_FILE'], 'invoicedocument') === 0;
				$postfix = $isInvoiceHandler ? '_DOCUMENT' : '';
			?>
			<?php if (isset($arResult['PS_MODE']) || $isInvoiceHandler):?>
				<tr>
					<td width="40%" class="bx-field-name"><?=Loc::getMessage('CRM_PS_FIELD_PS_MODE'.$postfix)?>: </td>
					<td class="bx-field-value">
						<?php if ($arResult['PS_MODE']):?>
							<select name="PS_MODE" id="PS_MODE" onchange="BX.crmPSActionFile.onHandlerModeChange(this);">
								<?php foreach ($arResult['PS_MODE'] as $id => $name):?>
									<option value="<?=$id;?>" <?=($arResult['PAY_SYSTEM']['PS_MODE'] == $id) ? 'selected' : ''?>><?=$name;?></option>
								<?php endforeach;?>
							</select>
						<?php endif;?>
						<?php if ($isInvoiceHandler):?>
							<span class="bx-button-add-template" onclick='BX.SidePanel.Instance.open("<?=$arResult['INVOICE_DOC_ADD_LINK'];?>", {width: 930, events: {onCloseComplete: function() {BX.crmPSActionFile.onSelect(BX("ACTION_FILE"));}}});'>
								<?=Loc::getMessage('CRM_PS_FIELD_TEMPLATE_DOCUMENT_ADD');?>
							</span>
						<?php endif;?>
					</td>
				</tr>
			<?php endif;?>
			<tr>
				<td width="40%" class="bx-field-name"><?=Loc::getMessage('CRM_PS_FIELD_SORT')?>: </td>
				<td class="bx-field-value"><input type="text" name="SORT" value="<?=$arResult['PAY_SYSTEM']['SORT'] ?: ''?>"></td>
			</tr>
			<tr>
				<?php 
					$checked = ($arResult['PAY_SYSTEM']['ACTIVE'] !== 'N');
				?>
				<td width="40%" class="bx-field-name"><?=Loc::getMessage('CRM_PS_FIELD_ACTIVE')?>: </td>
				<td class="bx-field-value"><input type="checkbox" name="ACTIVE" value="Y" <?=$checked ? 'checked' : ''?>></td>
			</tr>
			<tr>
				<td width="40%" class="bx-field-name"><?=Loc::getMessage('CRM_PS_FIELD_DESCRIPTION')?>: </td>
				<td class="bx-field-value"><textarea name="DESCRIPTION" cols="40" rows="3"><?=$arResult['PAY_SYSTEM']['DESCRIPTION'] ? htmlspecialcharsbx($arResult['PAY_SYSTEM']['DESCRIPTION']) : ''?></textarea></td>
			</tr>
			<tr>
				<td width="40%" class="bx-field-name"><?=Loc::getMessage('CRM_PS_FIELD_PERSON_TYPE_ID')?>: </td>
				<td class="bx-field-value">
					<select name="PERSON_TYPE_ID">
						<?php foreach ($arResult['PERSON_TYPE_LIST'] as $id => $name):?>
							<option value="<?=$id;?>" <?=($arResult['PAY_SYSTEM']['PERSON_TYPE_ID'] == $id) ? 'selected' : ''?>><?=$name;?></option>
						<?php endforeach;?>
					</select>
				</td>
			</tr>
		</tbody>
		<?php if (isset($arResult['SECURITY'])):?>
			<tbody id="SECURITY">
			<?php foreach ($arResult['SECURITY'] as $name => $value):?>
				<tr>
					<td width="40%" class="bx-field-name"><?=$value['NAME'];?>:</td>
					<td class="bx-field-value"><?=$value['VALUE'];?></td>
				<tr>
			<?php endforeach;?>
			</tbody>
		<?php endif;?>
		<tbody>
			<tr>
				<td colspan="2">
					<a onclick="BX.crmPaySys.switchMode();" class="crm-ps-mode-switcher" href="javascript:void(0);" id="MODE_SWITCHER"><?=($arResult['SIMPLE_MODE'] ? GetMessage("CRM_PS_SHOW_FIELDS") : GetMessage("CRM_PS_HIDE_FIELDS"));?></a>
				</td>
			</tr>
		</tbody>
	</table>

	<table cellspacing="0" class="bx-edit-tabs" width="52%" id="bx-tabs">
		<tr>
			<td class="bx-tab-indent"><div class="empty"></div></td>
			<?php foreach($arResult['BUSINESS_VALUE'] as $tab):?>
				<?php 
					$bSelected = $tab["id"] === 'PAYMENT';
				?>
				<td title="<?=htmlspecialcharsbx($tab["title"])?>" id="tab_cont_<?=$tab["id"]?>" class="bx-tab-container<?=($bSelected? "-selected":"")?>" onclick="BX.crmPSPropType.SelectTab('<?=$tab["id"]?>');" onmouseover="BX.crmPSPropType.HoverTab('<?=$tab["id"]?>', true);" onmouseout="BX.crmPSPropType.HoverTab('<?=$tab["id"]?>', false);">
					<table cellspacing="0">
						<tr>
							<td class="bx-tab-left<?=($bSelected? "-selected":"")?>" id="tab_left_<?=$tab["id"]?>"><div class="empty"></div></td>
							<td class="bx-tab<?=($bSelected? "-selected":"")?>" id="tab_<?=$tab["id"]?>"><?=htmlspecialcharsbx($tab["name"])?></td>
							<td class="bx-tab-right<?=($bSelected? "-selected":"")?>" id="tab_right_<?=$tab["id"]?>"><div class="empty"></div></td>
						</tr>
					</table>
				</td>
			<?php endforeach;?>
			<td width="100%" style="white-space:nowrap; text-align:right"></td>
		</tr>
	</table>
	<table cellspacing="0" class="bx-edit-tab" width="100%" style="table-layout: auto">
					<tr>
						<td width="60%" style="vertical-align: top;">
	<?php 
	$arResult["SELECTED_TAB"] = 'PAYMENT';
	$bWasRequired = false;
	foreach($arResult["BUSINESS_VALUE"] as $groupId => $tab):
	?>
	<div id="inner_tab_<?=$tab["id"]?>" class="bx-edit-tab-inner"<?php if($tab["id"] <> $arResult["SELECTED_TAB"]) echo ' style="display:none;"'?>>
	<div style="height: 100%;">
	<?php if($tab["title"] <> ''):?>
		<div class="bx-edit-tab-title">
		<table cellpadding="0" cellspacing="0" border="0" class="bx-edit-tab-title">
			<tr>
		<?php 
			if($tab["icon"] <> ""):
		?>
				<td class="bx-icon"><div class="<?=htmlspecialcharsbx($tab["icon"])?>"></div></td>
		<?php 
			endif
		?>
				<td class="bx-form-title"><?=htmlspecialcharsbx($tab["title"])?></td>
			</tr>
		</table>
		</div>
	<?php endif;?>

	<div class="bx-edit-table">
	<table cellpadding="0" cellspacing="0" border="0" class="bx-edit-table <?=(isset($tab["class"]) ? $tab['class'] : '')?>" id="<?=$tab["id"]?>_edit_table">
	<?php 
	$i = 0;
	$j = 0;
	$cnt = count($tab["fields"]);
	$prevType = '';
	foreach($tab["fields"] as $id => $field):
		$style = '';
		if(isset($field["show"]))
		{
			if($field["show"] == "N")
				$style = "display:none;";
		}

		$i++;
		$j++;

		if ($i % 3 === 0 && (strpos($id, 'BILLUA_COLUMN_SUM_') !== false || strpos($id, 'BILLUA_COLUMN_PRICE_') !== false))
			$j--;

		if(!is_array($field))
			continue;

		$className = array();
		if($i == 1)
			$className[] = 'bx-top';
		if($i == $cnt)
			$className[] = 'bx-bottom';
		if($prevType == 'section')
			$className[] = 'bx-after-heading';

		if(strlen($field['class']))
			$className[] = $field['class'];
	?>
		<tr<?php if(!empty($className)):?> class="<?=implode(' ', $className)?>"<?php endif?><?php if(!empty($style)):?> style="<?= $style ?>"<?php endif?>>
	<?php 
		$val = (isset($field["value"])? $field["value"] : $arParams["~DATA"][$field["id"]]);

		//default attributes
		if(!is_array($field["params"]))
			$field["params"] = array();
		if($field["type"] == '' || $field["type"] == 'text')
		{
			if($field["params"]["size"] == '')
				$field["params"]["size"] = "30";
		}

		$params = '';
		if(is_array($field["params"]) && $field["type"] <> 'file')
		{
			foreach($field["params"] as $p=>$v)
				$params .= ' '.$p.'="'.$v.'"';
		}

		if($field["colspan"] <> true):
			if($field["required"])
				$bWasRequired = true;
	?>
			<td class="bx-field-name<?php if($field["type"] <> 'label') echo' bx-padding'?> bx-props-field-width"<?php if($field["title"] <> '') echo ' title="'.htmlspecialcharsEx($field["title"]).'"'?>><?=($field["required"]? '<span class="required">*</span>':'')?><?php if(strlen($field["name"])):?><?=htmlspecialcharsEx($field["name"])?>:<?php endif?></td>
	<?php 
		endif
	?>
			<td class="bx-field-value"<?=($field["colspan"]? ' colspan="2"':'')?>>
	<?php 
		switch($field["type"]):
			case 'label':
			case 'custom':
				echo $val;
				break;
			default:
	?>
	<input type="text" name="<?=$field["id"]?>" value="<?=$val?>"<?=$params?>>
	<?php 
				break;
		endswitch;
	?>
			</td>
		</tr>
	<?php if ($groupId === 'COLUMN_SETTINGS' && $j % 3 === 0) : ?>
		<tr><td colspan="2"></td></tr>
	<?php endif;?>
	<?php 
		$prevType = $field["type"];
	endforeach;
	?>
	<?php if ($tab["id"] == 'COLUMN_SETTINGS'):?>
		<tr id="ADD_USER_PROP">
			<td colspan="2" style="text-align: center;"><?=$userPropsList;?></td>
		</tr>
	<?php endif;?>
	</table>
	</div>
	</div>
	</div>
	<?php 
	endforeach;
	?>
		</td>
		<td style="vertical-align: top;">
			<div style='transform: scale(0.6); margin-top: -200px; border: 1px #000 solid; display: none;'>
				<iframe frameborder="0" allowtransparency="true" scrolling="no" width="820" height="1150" id="frame"></iframe>
			</div>
		</td>
	</tr>
</table>
	<div class="bx-buttons">
		<input type="submit" name="save" value="<?php echo GetMessage("CRM_BUTTON_FORM_SAVE")?>" title="<?php echo GetMessage("CRM_BUTTON_FORM_SAVE_TITLE")?>" />
		<input type="submit" name="apply" value="<?php echo GetMessage("CRM_BUTTON_FORM_APPLY")?>" title="<?php echo GetMessage("CRM_BUTTON_FORM_APPLY_TITLE")?>" />
		<input type="button" value="<?php echo GetMessage("CRM_BUTTON_FORM_CANCEL")?>" name="cancel" onclick="cancelEdit()" title="<?php echo GetMessage("CRM_BUTTON_FORM_CANCEL_TITLE")?>" />
	</div>
</form>
</div>
<?php 

$typeValuesTmpl = '<select name="TYPE_#FIELD_ID#" id="TYPE_#FIELD_ID#">'.
					'<option value="">'.GetMessage("CRM_PS_TYPES_OTHER").'</option>'.
					//'<option value="USER">'.GetMessage("CRM_PS_TYPES_USER").'</option>'.
					'<option value="ORDER">'.GetMessage("CRM_PS_TYPES_ORDER").'</option>'.
					'<option value="PAYMENT">'.GetMessage("CRM_PS_TYPES_PAYMENT").'</option>'.
					'<option value="PROPERTY">'.GetMessage("CRM_PS_TYPES_PROPERTY").'</option>'.
					'<option value="REQUISITE">'.GetMessage("CRM_PS_TYPES_REQUISITE").'</option>'.
					'<option value="BANK_DETAIL">'.GetMessage("CRM_PS_TYPES_BANK_DETAIL").'</option>'.
					'<option value="CRM_COMPANY">'.GetMessage("CRM_PS_TYPES_CRM_COMPANY").'</option>'.
					'<option value="CRM_CONTACT">'.GetMessage("CRM_PS_TYPES_CRM_CONTACT").'</option>'.
					'<option value="MC_REQUISITE">'.GetMessage("CRM_PS_TYPES_MC_REQUISITE").'</option>'.
					'<option value="MC_BANK_DETAIL">'.GetMessage("CRM_PS_TYPES_MC_BANK_DETAIL").'</option>'.
					'<option value="CRM_MYCOMPANY">'.GetMessage("CRM_PS_TYPES_CRM_MYCOMPANY").'</option>'.
					'</select>&nbsp;'.
					'<select name="VALUE1_#FIELD_ID#" id="VALUE1_#FIELD_ID#"></select>'.
					'<input type="text" value="" name="VALUE2_#FIELD_ID#" id="VALUE2_#FIELD_ID#" size="40">';

$fileValuesTmpl = '<select name="TYPE_#FIELD_ID#" id="TYPE_#FIELD_ID#" style="display: none;">'.
					'<option selected value="FILE"></option>'.
					'</select>&nbsp;'.
					'<input type="file" name="VALUE1_#FIELD_ID#" id="VALUE1_#FIELD_ID#" size="40">'.
					'<span id="#FIELD_ID#_preview"><br><img id="#FIELD_ID#_preview_img" >'.
					'<br><input type="checkbox" name="#FIELD_ID#_del" value="Y" id="#FIELD_ID#_del" >'.
					'<label for="#FIELD_ID#_del">' . GetMessage("CRM_PS_DEL_FILE") . '</label></span>';

$selectValuesTmpl = '<select name="TYPE_#FIELD_ID#" id="TYPE_#FIELD_ID#" style="display: none;">'.
					'<option selected value="SELECT"></option>'.
					'</select>&nbsp;'.
					'<select name="VALUE1_#FIELD_ID#" id="VALUE1_#FIELD_ID#"></select>';

$checkboxValuesTmpl = '<select name="TYPE_#FIELD_ID#" id="TYPE_#FIELD_ID#" style="display: none;">'.
					'<option selected value="CHECKBOX"></option>'.
					'</select>&nbsp;'.
					'<input type="checkbox" name="VALUE1_#FIELD_ID#" id="VALUE1_#FIELD_ID#" value="Y"></select>';
?>

<script type="text/javascript">

	BX.message({
		CRM_PS_SHOW_FIELDS: "<?=GetMessage("CRM_PS_SHOW_FIELDS")?>",
		CRM_PS_GENERATE_SUCCESS: "<?=GetMessage("CRM_PS_GENERATE_SUCCESS")?>",
		CRM_PS_MODE_LIST: "<?=GetMessage("CRM_PS_MODE_LIST")?>",
		CRM_PS_HIDE_FIELDS: "<?=GetMessage("CRM_PS_HIDE_FIELDS")?>",
		CRM_COLUMN_NAME: "<?=GetMessage("CRM_COLUMN_NAME")?>",
		CRM_COLUMN_SORT: "<?=GetMessage("CRM_COLUMN_SORT")?>",
		CRM_COLUMN_ACTIVE: "<?=GetMessage("CRM_COLUMN_ACTIVE")?>",
		CRM_TEMPLATE_DOCUMENT_ADD: "<?=GetMessage("CRM_PS_FIELD_TEMPLATE_DOCUMENT_ADD")?>",
		CRM_PROP_ALREADY_EXIST: "<?=GetMessage("CRM_PROP_ALREADY_EXIST")?>"
	});

	BX.crmPaySys.init(<?=CUtil::PhpToJSObject(array(
		'orderProps' => CCrmPaySystem::getOrderPropsList(),
		'orderFields' => CCrmPaySystem::getOrderFieldsList(),
		'paymentFields' => CCrmPaySystem::getPaymentFieldsList(),
		'userProps' => CCrmPaySystem::getUserPropsList(),
		'userFields' => $arResult['USER_FIELDS'],
		'requisiteFields' => $arResult['REQUISITE_FIELDS'],
		'bankDetailFields' => $arResult['BANK_DETAIL_FIELDS'],
		'companyFields' => $arResult['CRM_COMPANY_FIELDS'],
		'contactFields' => $arResult['CRM_CONTACT_FIELDS'],
		'formId' => "form_".$arResult['FORM_ID'],
		'template' => $arResult['TEMPLATE'],
		'userColumnFields' => $arResult['USER_COLUMN_FIELDS'],
		'simpleMode' => $arResult['SIMPLE_MODE'] ? true : false,
		'url' => $componentPath
	));?>);

	BX.crmPSPersonType.init();
	BX.crmPSPropType.init(<?=CUtil::PhpToJsObject(array('aTabs' => array_keys($arResult['BUSINESS_VALUE'])));?>);
	BX.crmPSActionFile.init({
		arFields : {
			<?=CUtil::PhpToJsObject($arResult['ACTION_FILE'])?>: <?=CUtil::PhpToJsObject($arResult['PS_ACT_FIELDS_BY_GROUP'])?>
		},
		arFieldsList : {
			<?=CUtil::PhpToJsObject($arResult['ACTION_FILE'])?>: <?=CUtil::PhpToJsObject($arResult['ACTION_FIELDS_LIST'])?>
		},
		typeValuesTmpl: "<?=CUtil::JSEscape($typeValuesTmpl)?>",
		fileValuesTmpl: "<?=CUtil::JSEscape($fileValuesTmpl)?>",
		checkboxValuesTmpl: "<?=CUtil::JSEscape($checkboxValuesTmpl)?>",
		selectValuesTmpl: "<?=CUtil::JSEscape($selectValuesTmpl)?>",
		userPropsListTmpl: "<?=CUtil::JSEscape($userPropsList)?>"
	});

	BX.crmPSActionFile.onHandlerModeChange();

	<?php  if (isset($_REQUEST['SIDE_PANEL_REQUEST']) && $_REQUEST['SIDE_PANEL_REQUEST'] === 'Y'): ?>
	BX.ready(function () {
		if (top.BX.SidePanel.Instance && top.BX.SidePanel.Instance.getTopSlider())
		{
			var requestType = 'apply';
			<?php  if (isset($_REQUEST['SIDE_PANEL_SAVE']) && $_REQUEST['SIDE_PANEL_SAVE'] === 'Y'): ?>
				requestType = 'save';
			<?php  endif; ?>
			top.BX.onCustomEvent('SidePanel:postMessage', [window, requestType, {}]);
		}
	});
	<?php  endif; ?>

	function cancelEdit()
	{
		<?php  if ($isSidePanel): ?>
			if (top.BX.SidePanel.Instance && top.BX.SidePanel.Instance.getTopSlider())
			{
				top.BX.SidePanel.Instance.close();
			}
		<?php  else: ?>
			window.location='<?=htmlspecialcharsbx(CUtil::addslashes($arResult['BACK_URL']))?>';
		<?php  endif; ?>
	}

</script>

<?php 
$componentContent = ob_get_clean();
if ($isSidePanel)
{
	$APPLICATION->RestartBuffer();
	$APPLICATION->ShowHead();
	echo $componentContent;
	require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
	exit;
}
else
{
	echo $componentContent;
}

?>