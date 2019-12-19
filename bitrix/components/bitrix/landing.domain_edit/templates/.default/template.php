<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

\Bitrix\Landing\Manager::setPageTitle(
	Loc::getMessage('LANDING_TPL_TITLE')
);

if ($arResult['ERRORS'])
{
	\showError(implode("\n", $arResult['ERRORS']));
}

if ($arResult['FATAL'])
{
	return;
}

$row = $arResult['DOMAIN'];
?>
<form action="<?= POST_FORM_ACTION_URI?>" method="post">
	<input type="hidden" name="fields[SAVE_FORM]" value="Y" />
	<?= bitrix_sessid_post()?>
	<table>
		<tr>
			<td><?= $row['ACTIVE']['TITLE']?>:</td>
			<td>
				<input type="checkbox" name="fields[ACTIVE]" value="Y"<?php if ($row['ACTIVE']['CURRENT'] == 'Y') {?> checked="checked"<?php }?>>
			</td>
		</tr>
		<tr>
			<td><?= $row['PROTOCOL']['TITLE']?>:</td>
			<td>
				<select name="fields[PROTOCOL]" class="content-edit-form-field-input-select">
				<?php foreach (\Bitrix\Landing\Domain::getProtocolList() as $code => $val):?>
					<option value="<?= $code?>"<?php if ($val == $row['PROTOCOL']['CURRENT']){?> selected="selected"<?php }?>>
						<?= $val?>
					</option>
				<?php endforeach;?>
				</select>
			</td>
		</tr>
		<tr>
			<td><?= $row['DOMAIN']['TITLE']?>:</td>
			<td>
				<input type="text" name="fields[DOMAIN]" value="<?= $row['DOMAIN']['CURRENT']?>" class="content-edit-form-field-input-text">
			</td>
		</tr>
	</table>
	<input type="submit" value="<?= Loc::getMessage('LANDING_TPL_BUTTON_SAVE')?>" />
</form>