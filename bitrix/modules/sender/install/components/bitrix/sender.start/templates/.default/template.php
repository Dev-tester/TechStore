<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Json;
use Bitrix\Main\UI\Extension;

/** @var \CAllMain $APPLICATION */
/** @var array $arParams */
/** @var array $arResult */

$bodyClass = $APPLICATION->GetPageProperty("BodyClass");
$APPLICATION->SetPageProperty("BodyClass", ($bodyClass ? $bodyClass." " : "") . "no-all-paddings no-background");
Extension::load(["ui.icons"]);

$containerId = 'sender-start-container';
?>
<div id="<?=htmlspecialcharsbx($containerId)?>" class="sender-start-wrap">

	<?php if (!empty($arResult['MESSAGES']['MAILING']['TILES'])):?>
		<div class="sender-start-block">
			<div class="sender-start-title">
				<?=Loc::getMessage('SENDER_START_CREATE_LETTER')?>
			</div>
			<?php $APPLICATION->IncludeComponent("bitrix:ui.tile.list", "", [
				'ID' => 'sender-start-mailings',
				'LIST' => $arResult['MESSAGES']['MAILING']['TILES'],
			]);?>
		</div>
	<?php endif;?>

	<?php if (!empty($arResult['MESSAGES']['ADS']['TILES'])):?>
		<div class="sender-start-block">
			<div class="sender-start-title">
				<?=Loc::getMessage('SENDER_START_CREATE_AD')?>
			</div>
			<?php $APPLICATION->IncludeComponent("bitrix:ui.tile.list", "", [
				'ID' => 'sender-start-ad',
				'LIST' => $arResult['MESSAGES']['ADS']['TILES'],
			]);?>
		</div>
	<?php endif;?>

	<?php if (!empty($arResult['MESSAGES']['RC']['TILES'])):?>
		<div class="sender-start-block">
			<div class="sender-start-title">
				<?=Loc::getMessage('SENDER_START_CREATE_RC')?>
			</div>
			<?php $APPLICATION->IncludeComponent("bitrix:ui.tile.list", "", [
				'ID' => 'sender-start-rc',
				'LIST' => $arResult['MESSAGES']['RC']['TILES'],
			]);?>
		</div>
	<?php endif;?>

	<script type="text/javascript">
		BX.ready(function () {
			BX.Sender.Start.init(<?=Json::encode(array(
				'containerId' => $containerId
			))?>);
		});
	</script>

</div>