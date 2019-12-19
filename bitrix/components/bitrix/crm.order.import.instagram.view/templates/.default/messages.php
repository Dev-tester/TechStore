<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

if ($arResult['messages'])
{
	?>
	<div class="ui-alert ui-alert-primary" style="margin: 13px 0;">
		<?php 
		foreach ($arResult['messages'] as $value)
		{
			?>
			<span class="ui-alert-message"><?=$value?></span>
			<?php 
		}
		?>
	</div>
	<?php 
}

if ($arResult['error'])
{
	?>
	<div class="ui-alert ui-alert-warning" style="margin: 13px 0;">
		<?php 
		foreach ($arResult['error'] as $value)
		{
			?>
			<span class="ui-alert-message"><?=$value?></span>
			<?php 
		}
		?>
	</div>
	<?php 
}