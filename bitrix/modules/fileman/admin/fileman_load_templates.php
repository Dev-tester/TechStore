<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/fileman/prolog.php");

if (!$USER->CanDoOperation('fileman_view_file_structure') && !$USER->CanDoOperation('edit_other_settings'))
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/fileman/include.php");

if(CModule::IncludeModule("compression"))
	CCompress::Disable2048Spaces();

if (isset($_GET['cname']) && isset($_GET['stid']) && isset($_GET['tname']) && isset($_GET['mode']) && $_GET['mode']=='params')
{
	?>
<script>
window.arComp2TemplateProps = [];
<?php 
	$arCurrentValues = (isset($_POST['curval'])) ? $_POST['curval'] : array();
	$arTemplateProps = CComponentUtil::GetTemplateProps($_GET['cname'], $_GET['tname'], $_GET['stid'], $arCurrentValues);
	foreach ($arTemplateProps as $paramName => $arTemplateProp)
	{
		?>
var tempAr2 = {};
tempAr2.param_name = '<?= CUtil::JSEscape($paramName)?>';
<?php 
	foreach ($arTemplateProp  as $k => $prop)
	{
		if (is_array($prop))
		{
?>tempAr2.<?= $k;?> = {<?php 
			echo "\n";
			$f = true;
			foreach ($prop as $k2 => $prop_)
			{
				if (!$f)
					echo",\n";
				else
					$f = false;
				echo '\''.CUtil::JSEscape($k2).'\' : \''.CUtil::JSEscape($prop_).'\'';
			}
			echo "\n";
?>}<?php 
		}
		else
		{
?>tempAr2.<?= $k;?> = '<?= CUtil::JSEscape($prop)?>';<?php 
		}
		echo "\n";
	}
?>
window.arComp2TemplateProps.push(tempAr2);
<?php 
	}
?></script><?php 
	//__GetTemplateProps($_GET['cname'], $_GET['tname'], $_GET['stid'],$arCurrentValues);
}
else if (isset($_GET['stid']) && isset($_GET['mode']) && $_GET['mode']=='list')
{
?>
<script>
window.arComp2TemplateLists = {};
<?php 
	$arComponents = (isset($_POST['complist'])) ? CEditorUtils::UnJSEscapeArray($_POST['complist']) : array();
	$len = count($arComponents);
	for ($i = 0; $i < $len; $i++)
	{
		$cName = $arComponents[$i];
		$arTemplates = CComponentUtil::GetTemplatesList($cName, $siteTemplate);
		$tempLen = count($arTemplates);
?>
window.arComp2TemplateLists['<?= CUtil::JSEscape($cName)?>'] = {};
		<?php for ($j = 0; $j < $tempLen; $j++):?>

window.arComp2TemplateLists['<?= CUtil::JSEscape($cName)?>']['<?= CUtil::JSEscape($arTemplates[$j]["NAME"])?>'] =
{
	name : '<?= CUtil::JSEscape($arTemplates[$j]["NAME"])?>',
	template : '<?= CUtil::JSEscape($arTemplates[$j]["TEMPLATE"])?>',
	title : '<?= CUtil::JSEscape($arTemplates[$j]["TITLE"])?>',
	description : '<?= CUtil::JSEscape($arTemplates[$j]["DESCRIPTION"])?>'
};
		<?php endfor;
	}
	?></script><?php 
}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_after.php");
?>