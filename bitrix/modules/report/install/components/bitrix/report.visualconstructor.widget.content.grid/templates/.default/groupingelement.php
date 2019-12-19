<?php if(!empty($arResult['link'])):?>
	<a href="<?=$arResult['link']?>" target="_top">
<?php endif;?>
		<div class="report-widget-grid-grouping">
			<?php  if(!empty($arResult['logo'])): ?>
				<div class="ui-icon ui-icon-common-user report-widget-grid-grouping-icon"><i style="background-image: url(<?=$arResult['logo']?>)"></i></div>
			<?php  elseif(!empty($arResult['defaultUserLogo'])):?>
				<div class="ui-icon ui-icon-common-user report-widget-grid-grouping-icon"><i></i></div>
			<?php  endif;?>
			<div class="report-widget-grid-grouping-name">
				<?=$arResult['title']?>
			</div>
		</div>
<?php if(!empty($arResult['link'])):?>
	</a>
<?php endif;?>
