<?php
namespace Bitrix\Landing\Field;

class Hidden extends \Bitrix\Landing\Field
{
	/**
	 * Vew field.
	 * @param array $params Array params:
	 * name - field name
	 * class - css-class for this element
	 * additional - some additional params as is.
	 * @return void
	 */
	public function viewForm(array $params = array())
	{
		?>
		<input type="hidden" <?php 
		?><?= isset($params['additional']) ? $params['additional'] . ' ' : ''?><?php 
		?><?= isset($params['id']) ? 'id="' . \htmlspecialcharsbx($params['id']) . '" ' : ''?><?php 
		?>data-code="<?= \htmlspecialcharsbx($this->code)?>" <?php 
		?>name="<?= \htmlspecialcharsbx(isset($params['name_format'])
				? str_replace('#field_code#', $this->code, $params['name_format'])
				: $this->code)?>" <?php 
		?>value="<?= \htmlspecialcharsbx($this->value)?>" <?php 
		?> />
		<?php 
	}

	/**
	 * Gets true, if current value is empty.
	 * @return bool
	 */
	public function isEmptyValue()
	{
		return $this->value === '';
	}
}
