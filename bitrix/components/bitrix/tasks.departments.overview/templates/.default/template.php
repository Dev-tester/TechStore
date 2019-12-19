<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$helper = $arResult['HELPER'];
$arParams =& $helper->getComponent()->arParams; // make $arParams the same variable as $this->__component->arParams, as it really should be
?>

<?php $helper->displayFatals();?>
<?php if(!$helper->checkHasFatals()):?>

	<div id="<?=$helper->getScopeId()?>" class="tasks">

		<?php $helper->displayWarnings();?>

		<?php // make dom node accessible in js controller like that: ?>
		<div class="js-id-skeleton-some-div js-id-another-controller-some-button">
			...
		</div>

	</div>

	<?php $helper->initializeExtension();?>

<?php endif?>