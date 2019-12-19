<?php
namespace Bitrix\Landing\Field;

class Textarea extends \Bitrix\Landing\Field\Text
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
		<textarea <?php 
		?><?= isset($params['additional']) ? $params['additional'] . ' ' : ''?><?php 
		?><?= isset($params['id']) ? 'id="' . \htmlspecialcharsbx($params['id']) . '" ' : ''?><?php 
		?><?= $this->maxlength > 0 ? 'maxlength="'. $this->maxlength . '" ' : ''?><?php 
		?><?= $this->placeholder != '' ? 'placeholder="'. $this->placeholder . '" ' : ''?><?php 
		?>class="<?= isset($params['class']) ? \htmlspecialcharsbx($params['class']) : ''?>" <?php 
		?>data-code="<?= \htmlspecialcharsbx($this->code)?>" <?php 
		?>name="<?= \htmlspecialcharsbx(isset($params['name_format'])
				? str_replace('#field_code#', $this->code, $params['name_format'])
				: $this->code)?>" <?php 
		?> ><?= \htmlspecialcharsbx($this->value)?></textarea>
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
