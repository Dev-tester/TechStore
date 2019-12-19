<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php  if (!empty($arResult)): ?>
	<div id="submenu">
		<div id="submenu-items">
			<ul id="submenu-list">
				<?php  foreach($arResult as $arItem): ?>
					<?php  if ($arItem["PERMISSION"] > "D"): ?>
						<li <?php  if ($arItem["SELECTED"]) { ?>class="selected"<?php  } ?>><a href="<?= $arItem["LINK"]?>"><span><?= $arItem["TEXT"]?></span></a></li>
					<?php  endif; ?>
				<?php  endforeach; ?>
			</ul>
		</div>
	</div>
<?php  endif; ?>