<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $APPLICATION;
$this->setFrameMode(true);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID ?>" lang="<?=LANGUAGE_ID ?>">
	<head>
		<?php  $APPLICATION->ShowHead(); ?>
	</head>
	<body>
		<?php 
		$APPLICATION->IncludeComponent(
			$arParams['POPUP_COMPONENT_NAME'],
			$arParams['POPUP_COMPONENT_TEMPLATE_NAME'],
			$arParams['POPUP_COMPONENT_PARAMS']
		);
		?>
	</body>
</html>

