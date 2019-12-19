<?php  if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */

?>

<div class="list-element-preview">
	<div class="list-element-preview-header">
		<span class="list-element-preview-header-title">
			<?=$arResult['ENTITY_NAME']?>:
			<a href="<?=$arParams['URL']?>" target="_blank"><?=$arResult['ENTITY_TITLE']?></a>
		</span>
	</div>
	<table class="list-element-preview-info">
		<?php  foreach($arResult['FIELDS'] as $field): ?>
			<?php  if(strlen($field['NAME']) > 0): ?>
				<tr>
					<td><?=$field['NAME']?>:</td>
					<td><?=$field['HTML']?></td>
				</tr>
			<?php  endif ?>
		<?php  endforeach ?>
	</table>
</div>