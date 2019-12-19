<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;

$APPLICATION->restartBuffer();

\CJSCore::init('sidepanel');

?><!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript">
			if (window === window.top)
			{
				window.location = '<?=CUtil::jsEscape($APPLICATION->getCurPageParam('', array('IFRAME'))) ?>';
			}
		</script>
		<?php  $APPLICATION->showHead(); ?>
	</head>
	<body>
		<div style="padding: 0 20px 20px 20px; ">
			<div class="mail-msg-sidepanel-header">
				<div class="mail-msg-sidepanel-title-container">
					<div class="mail-msg-sidepanel-title">
						<?php  $APPLICATION->showViewContent('pagetitle_icon'); ?>
						<span class="mail-msg-sidepanel-title-text"><?php  $APPLICATION->showTitle(); ?></span>
					</div>
					<?php  $APPLICATION->showViewContent('inside_pagetitle'); ?>
				</div>
				<div class="mail-msg-sidepanel-title-below">
					<?php  $APPLICATION->showViewContent('below_pagetitle'); ?>
					<div class="mail-msg-sidepanel-title-below-border"></div>
				</div>
			</div>

			<div style="position: relative; ">
				<?php  call_user_func_array(array($APPLICATION, 'includeComponent'), $arParams['~COMPONENT_ARGUMENTS']); ?>
			</div>

		</div>
	</body>
</html><?php 

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
exit;
