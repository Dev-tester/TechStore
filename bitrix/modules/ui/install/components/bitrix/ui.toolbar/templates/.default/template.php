<?php 

use Bitrix\UI\Toolbar\Facade\Toolbar;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

$this->setFrameMode(true);

$filter = Toolbar::getFilter();
$rightButtons = Toolbar::renderRightButtons();
$filterButtons = Toolbar::renderAfterFilterButtons();
$favoriteStar = Toolbar::hasFavoriteStar()? '<span class="ui-toolbar-star" id="uiToolbarStar"></span>' : '';

$titleProps = "";
if (Toolbar::getTitleMinWidth() !== null)
{
	$titleProps .= 'min-width:'.Toolbar::getTitleMinWidth().'px'.';';
}

if (Toolbar::getTitleMaxWidth() !== null)
{
	$titleProps .= 'max-width:'.Toolbar::getTitleMaxWidth().'px';
}

$titleStyles = !empty($titleProps) ? ' style="'.$titleProps.'"' : "";

?>

<div id="uiToolbarContainer" class="ui-toolbar">
	<div id="pagetitleContainer" class="ui-toolbar-title-box"<?=$titleStyles?>><?php 
		?><span id="pagetitle" class="ui-toolbar-title-item"><?=$APPLICATION->getTitle(false)?></span><?php 
		?><?= $favoriteStar ?>
	</div><?php 

	if (strlen($filter)):
		?><div class="ui-toolbar-filter-box"><?=$filter?><?=$filterButtons?></div><?php 
	endif;

	if (strlen($rightButtons)):
		?><div class="ui-toolbar-btn-box"><?=$rightButtons?></div><?php 
	endif;
?></div>

<script>
    new BX.UI.Toolbar(<?=\Bitrix\Main\Web\Json::encode([
        "titleMinWidth" => Toolbar::getTitleMinWidth(),
		"titleMaxWidth" => Toolbar::getTitleMaxWidth(),
		"buttonIds" => array_map(function(\Bitrix\UI\Buttons\BaseButton $button){
			return $button->getUniqId();
		}, Toolbar::getButtons()),
    ])?>,
		targetContainer = document.getElementById('uiToolbarContainer')
	);
</script>
