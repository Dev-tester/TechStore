<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Localization\Loc;
?>
<div class="imconnector-field-box">
	<div class="imconnector-field-box-subtitle-darken">
		<?=Loc::getMessage('IMCONNECTOR_COMPONENT_BOTFRAMEWORK_INSTRUCTION_TITLE')?>
	</div>
	<div class="imconnector-field-button-box">
		<div onclick="top.BX.Helper.show('<?=Loc::getMessage('IMCONNECTOR_COMPONENT_BOTFRAMEWORK_INFO_CONNECT_ID')?>');"
		   class="imconnector-field-button imconnector-field-button-connect">
			<div class="imconnector-field-button-icon"></div>
			<div class="imconnector-field-button-text">
				<?=Loc::getMessage('IMCONNECTOR_COMPONENT_BOTFRAMEWORK_CONNECT_HELP')?>
			</div>
		</div>
	</div>
</div>
