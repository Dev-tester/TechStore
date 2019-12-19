<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->SetAdditionalCSS('/bitrix/gadgets/bitrix/bitrix24/styles.css');

$logoFile = "/bitrix/gadgets/bitrix/bitrix24/images/logo-".LANGUAGE_ID.".png";
if (!file_exists($_SERVER["DOCUMENT_ROOT"].$logoFile))
	$logoFile = "/bitrix/gadgets/bitrix/bitrix24/images/logo-en.png";
?>
<div class="bx-gadget-top-label-wrap"><div class="bx-gadget-top-label"><?php echo GetMessage("GD_BITRIX24")?></div></div>
<div class="bx-gadget-title-wrap">
	<span class="bx-gadget-title-text"><?php echo GetMessage("GD_BITRIX24_TITLE")?></span><img src="<?php echo $logoFile?>" alt="Bitrix24"/>
</div>
<a class="bx-gadget-bitrix24-btn" href="<?php echo htmlspecialcharsBx(GetMessage("GD_BITRIX24_LINK"));?>"><?php echo GetMessage("GD_BITRIX24_BUTTON")?></a>
<div class="bx-gadget-bitrix24-text-wrap">
	<span class="bx-gadget-bitrix24-text-left"></span><span class="bx-gadget-bitrix24-text">
	<?php echo GetMessage("GD_BITRIX24_LIST")?>
	<span class="bx-gadget-bitrix24-text-other"><?php echo GetMessage("GD_BITRIX24_MORE")?></span> <br>
	</span>
</div>
<div class="bx-gadget-shield"></div>
