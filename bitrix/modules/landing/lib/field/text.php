<?php
namespace Bitrix\Landing\Field;

class Text extends \Bitrix\Landing\Field
{
	/**
	 * Max length of the field.
	 * @var int
	 */
	protected $maxlength;

	/**
	 * Placeholder for input.
	 * @var string
	 */
	protected $placeholder;

	/**
	 * Class constructor.
	 * @param string $code Field code.
	 * @param array $params Field params.
	 */
	public function __construct($code, array $params = array())
	{
		$this->code = strtoupper($code);
		$this->value = null;
		$this->id = isset($params['id']) ? $params['id'] : '';
		$this->title = isset($params['title']) ? $params['title'] : '';
		$this->help = isset($params['help']) ? $params['help'] : '';
		$this->placeholder = isset($params['placeholder']) ? $params['placeholder'] : '';
		$this->maxlength = isset($params['maxlength']) ? (int)$params['maxlength'] : 0;
	}

	/**
	 * Gets true, if current value is empty.
	 * @return bool
	 */
	public function isEmptyValue()
	{
		return $this->value === '';
	}

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
		<input type="text" <?php 
		?><?= isset($params['additional']) ? $params['additional'] . ' ' : ''?><?php 
		?><?= isset($params['id']) ? 'id="' . \htmlspecialcharsbx($params['id']) . '" ' : ''?><?php 
		?><?= $this->maxlength > 0 ? 'maxlength="'. $this->maxlength . '" ' : ''?><?php 
		?><?= $this->placeholder != '' ? 'placeholder="'. \htmlspecialcharsbx($this->placeholder) . '" ' : ''?><?php 
		?>class="<?= isset($params['class']) ? \htmlspecialcharsbx($params['class']) : ''?>" <?php 
		?>data-code="<?= \htmlspecialcharsbx($this->code)?>" <?php 
		?>name="<?= \htmlspecialcharsbx(isset($params['name_format'])
				? str_replace('#field_code#', $this->code, $params['name_format'])
				: $this->code)?>" <?php 
		?>value="<?= \htmlspecialcharsbx($this->value)?>" <?php 
		?> />
		<?php 
	}

	/**
	 * Set value to the field.
	 * @param string $value Value.
	 * @return void
	 */
	public function setValue($value)
	{
		if ($this->maxlength > 0)
		{
			$this->value = substr($value, 0, $this->maxlength);
		}
		else
		{
			$this->value = $value;
		}
	}

	/**
	 * Magic method return value as string.
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this->value;
	}
}
