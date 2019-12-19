<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $INTRANET_TOOLBAR;
$INTRANET_TOOLBAR->Show();
?><?php 
ob_start();

$arFilterValues = $APPLICATION->IncludeComponent("bitrix:intranet.structure.selector", "sections", $arParams, $component);
$current_section = intval($arFilterValues[$arParams['FILTER_NAME'].'_UF_DEPARTMENT']);

$sFilter = ob_get_contents();
ob_end_clean();
?>
<br />
<div class="bx-links-container">
<?php 
	if ($arResult['USER_CAN_SET_HEAD'] && $current_section):
		$arParams['LIST_OBJECT'] = 'arEmployees';
?>
	<script>
var arEmployees = [];
var bx_head_menu = new PopupMenu('bx_head_menu');
function ShowHeadMenu(el)
{
	if (arEmployees.length > 0)
	{
		var items = [];

		for (var i = 0; i < arEmployees.length; i++)
		{
			items[i] = {
				ICONCLASS: !arEmployees[i].CURRENT ? '' : 'checked',
				TEXT: arEmployees[i].NAME,
				ONCLICK: 'SetHead(' + arEmployees[i].ID + ')'
			}
		}

		bx_head_menu.ShowMenu(el, items);
	}
}

function SetHead(id)
{
	var url = '/bitrix/tools/intranet_set_head.php?SECTION_ID=<?php echo $current_section?>&USER_ID=' + parseInt(id) + '&<?php echo bitrix_sessid_get()?>';
	BX.ajax.get(url);
}

BX.ready(function() {if (arEmployees.length > 0) document.getElementById('bx_head_link').style.display = 'inline';});
</script><span id="blank"></span>
<a href="javascript:void(0)" class="bx-head-link" id="bx_head_link" onclick="ShowHeadMenu(this);" style="display: none;"><span><?php echo GetMessage('INTR_IS_TPL_HEAD')?></span></a>
<?php 
	endif;
	if ($arParams['SEARCH_URL']):
?>
	<a href="<?php echo $arParams['SEARCH_URL'].($current_section ? '?structure_department='.$current_section : '')?>" class="bx-search-link"><?php echo GetMessage('INTR_IS_TPL_SEARCH'.($current_section ? '_DEPARTMENT' : ''))?></a>
<?php 
	endif;
?>
	<a href="javascript:<?php echo htmlspecialcharsbx(CIntranetUtils::GetStsSyncURL(
		array(
			'LINK_URL' => $APPLICATION->GetCurPage()
		), 'contacts'
	))?>" class="bx-outlook-link" title="<?php echo GetMessage('INTR_IS_TPL_OUTLOOK_TITLE')?>"><?php echo GetMessage('INTR_IS_TPL_OUTLOOK')?></a>
</div>
<?php 
echo $sFilter;
?>
<br />
<?php 
if ($arFilterValues[$arParams['FILTER_NAME'].'_UF_DEPARTMENT']):
	$APPLICATION->IncludeComponent("bitrix:intranet.structure.list", "list", $arParams, $component);
endif;
?>