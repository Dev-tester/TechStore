<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var \Bitrix\Disk\Internals\BaseComponent $component */
?>
<?php
$firstObject = array_shift($arResult['BREADCRUMBS']);
?>
<div class="tal" style="font-size: 26px;"><?php echo htmlspecialcharsbx($arResult['STORAGE']['NAME']) ?></div>
<div id="disk-breadcrumbs-tree-<?= $component->getComponentId() ?>" style="display: none;"></div>



<script type="text/javascript">
		BX.Disk['BreadcrumbsTreeClass_<?= $component->getComponentId() ?>'] = new BX.Disk.BreadcrumbsTreeClass({
			storageBaseUrl: '<?= CUtil::JSUrlEscape($arResult['STORAGE']['LINK']) ?>',
			rootObject: {
				id: <?= $arResult['STORAGE']['ID'] ?>,
				name: '<?= CUtil::JSEscape($arResult['STORAGE']['NAME']) ?>'
			},
			<?php  if($firstObject){ ?>
			firstObject: {
				id: <?= $firstObject['ID'] ?>,
				name: '<?= CUtil::JSEscape($firstObject['NAME']) ?>'
			},
			<?php  } ?>
			containerId: "disk-breadcrumbs-tree-<?= $component->getComponentId() ?>"
		});

	<?php  foreach($arResult['BREADCRUMBS'] as $crumb)	{ ?>
		BX.Disk['BreadcrumbsTreeClass_<?= $component->getComponentId() ?>'].buildTree(BX.Disk['BreadcrumbsTreeClass_<?= $component->getComponentId() ?>'].lastNode, {
			status: 'success',
			items: [{
				id: <?= $crumb['ID'] ?>,
				name: '<?= CUtil::JSEscape($crumb['NAME']) ?>'
			}]
		});
	<?php  } ?>
	BX.Disk['BreadcrumbsTreeClass_<?= $component->getComponentId() ?>'].lazyLoadSubFolders(function(){
		BX.show(BX("disk-breadcrumbs-tree-<?= $component->getComponentId() ?>"), 'block');
	});

</script>
