<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<title><?=getMessage('SECURITY_USER_RECOVERY_CODES_PRINT_TITLE')?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET?>">
		<script type="application/javascript">
			var __readyHandler = null;

			/* ready */
			if (document.addEventListener)
			{
				__readyHandler = function()
				{
					document.removeEventListener('DOMContentLoaded', __readyHandler, false);
					onReady();
				}
			}
			else if (document.attachEvent)
			{
				__readyHandler = function()
				{
					if (document.readyState === 'complete')
					{
						document.detachEvent('onreadystatechange', __readyHandler);
						onReady();
					}
				}
			}

			function bindReady()
			{
				if (document.readyState === 'complete')
				{
					return onReady();
				}

				if (document.addEventListener)
				{
					document.addEventListener('DOMContentLoaded', __readyHandler, false);
				}
				else if (document.attachEvent) // IE
				{
					document.attachEvent('onreadystatechange', __readyHandler);
				}
			}

			function onReady()
			{
				setTimeout(window.print, 100);
				setTimeout(window.close, 500);
			}
		</script>
	</head>
<body>
	<h3>
		<?=getMessage('SECURITY_USER_RECOVERY_CODES_PRINT_TITLE')?>
	</h3>

<?php if ($arResult["MESSAGE"]):?>
	<?=htmlspecialcharsbx($arResult["MESSAGE"]);?>
<?php else:?>
	<p>
		<?=getMessage('SECURITY_USER_RECOVERY_CODES_PRINT_ISSUER', array(
			'#ISSUER#' => htmlspecialcharsbx($arResult['ISSUER'])
		))?>
		<br />
		<?=getMessage('SECURITY_USER_RECOVERY_CODES_PRINT_LOGIN', array(
			'#LOGIN#' => htmlspecialcharsbx($USER->getLogin())
		))?>
	<?php if ($arResult['CREATE_DATE']):?>
		<br />
		<?=getMessage('SECURITY_USER_RECOVERY_CODES_PRINT_CREATED', array(
			'#DATE#' => htmlspecialcharsbx($arResult['CREATE_DATE'])
		))?>
	<?php endif?>
	</p>
	<ol>
		<?php foreach ($arResult['CODES'] as $code):?>
			<?php if ($code['USED'] === 'N'):?>
				<li style="clear: both;"><?=htmlspecialcharsbx($code['VALUE'])?></li>
			<?php endif;?>
		<?php endforeach;?>
	</ol>
	<p>
		<?=getMessage('SECURITY_USER_RECOVERY_CODES_PRINT_NOTE')?>
	</p>
	</body>
	<script>
		bindReady();
	</script>
	</html>
<?php endif?>

<?php if (\Bitrix\Main\Context::getCurrent()->getRequest()->isPost()):?>
	<span class="ui-btn ui-btn-light-border" onclick="BX.Intranet.UserProfile.Security.showRecoveryCodesComponent()">
		<?=GetMessage("SECURITY_USER_RECOVERY_CODES_PRINT_BACK")?>
	</span>
<?php endif?>
