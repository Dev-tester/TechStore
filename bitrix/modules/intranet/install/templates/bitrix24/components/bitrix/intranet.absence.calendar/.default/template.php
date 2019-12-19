<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$bodyClass = $APPLICATION->GetPageProperty("BodyClass");
$APPLICATION->SetPageProperty("BodyClass", ($bodyClass ? $bodyClass." " : "")."page-one-column");

if ($arParams['bAdmin']):
	$arAbsenceParams["MESS"] = array(
		"INTR_ABSENCE_TITLE" => GetMessage("INTR_ABSENCE_TITLE"),
		"INTR_ABSENCE_BUTTON" => GetMessage("INTR_ABSENCE_BUTTON"),
		"INTR_CLOSE_BUTTON" => GetMessage("INTR_CLOSE_BUTTON"),
		"INTR_LOADING" => GetMessage("INTR_LOADING"),
	);
	$arAbsenceParams["IBLOCK_ID"] = $arParams['IBLOCK_ID'];
endif;

$BGCOLORS = array(
	'#f1e28f',
	'#a0c4f1',
	'#e3a5d8',
	'#fe3737',
	'#f2a584',
	'#c9e079',
	'#d3d3d3',
	'#a9b8bf',
	'#9ddbd3',

	'#43d2d7',
	'#43d795',
	'#92d743',
	'#d68085',
	'#d680c1',
	'#adaae7',
	'#aad5e7',
	'#aae7cb',
	'#aee7aa',
	'#d9eccc',
	'#ece6cc'
);
$c_id = 0;
$TYPE_BGCOLORS = array();
$TYPES = array();
$property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$arParams['IBLOCK_ID'], "CODE"=>"ABSENCE_TYPE"));
while($enum_fields = $property_enums->GetNext())
{
	$TYPES[] = array(
		'NAME'  => $enum_fields['XML_ID'],
		'TITLE' => Bitrix\Intranet\UserAbsence::getTypeCaption($enum_fields['XML_ID'], $enum_fields['VALUE']),
	);
	if (!isset($TYPE_BGCOLORS[$enum_fields['XML_ID']]))
	{
		$TYPE_BGCOLORS[$enum_fields['XML_ID']] = $BGCOLORS[$c_id++];

		if (!isset($BGCOLORS[$c_id]))
		{
			$c_id = 0;
		}
	}
}

?>
<script type="text/javascript">
function GetAbsenceDialog(abcenseID)
{
	<?php 
	$arAbsenceParams["MESS"]["INTR_ABSENCE_TITLE"] = GetMessage("INTR_ABSENCE_TITLE_EDIT");
	$arAbsenceParams["ABSENCE_ELEMENT_ID"] = "#ABSENCE_ID#";
	?>
	var dialog = "<?="BX.AbsenceCalendar.ShowForm(".CUtil::PhpToJSObject($arAbsenceParams).")"?>";
	return dialog.replace("#ABSENCE_ID#", abcenseID);
}
jsBXAC.Init(
	{
		'LOADER': '/bitrix/components/bitrix/intranet.absence.calendar/ajax.php',
		'NAME_TEMPLATE': '<?php echo CUtil::JSEscape($arParams['NAME_TEMPLATE'])?>',
		'SERVER_TIMEZONE_OFFSET': <?php echo date('Z')?>,
		'FIRST_DAY': <?php echo $arParams['FIRST_DAY']?>,
		'DAY_START': <?php echo $arParams['DAY_START']?>,
		'DAY_FINISH': <?php echo $arParams['DAY_FINISH']?>,
		'PAGE_NUMBER': <?=$arParams['PAGE_NUMBER'] ?>,
		'DAY_SHOW_NONWORK': <?php echo $arParams['DAY_SHOW_NONWORK'] == 'Y' ? 'true' : 'false'?>,
		'DETAIL_URL_PERSONAL': '<?php echo CUtil::JSEscape($arParams['DETAIL_URL_PERSONAL'])?>',
		'DETAIL_URL_DEPARTMENT': '<?php echo CUtil::JSEscape($arParams['DETAIL_URL_DEPARTMENT'])?>',
		'CONTROLS': <?php echo CUtil::PhpToJsObject($arResult['CONTROLS'])?>,
		'SITE_ID': '<?php echo SITE_ID?>',
		'IBLOCK_ID': <?php echo intval($arParams['IBLOCK_ID'])?>,
		'CALENDAR_IBLOCK_ID': <?php echo intval($arParams['CALENDAR_IBLOCK_ID'])?>,
		'MONTHS': [<?php for($i=1;$i<13;$i++){echo ($i==1 ? '' : ','),"'",CUtil::JSEscape(GetMessage('IAC_MONTH_'.$i)),"'";}?>],
		'MONTHS_R': [<?php for($i=1;$i<13;$i++){echo ($i==1 ? '' : ','),"'",CUtil::JSEscape(GetMessage('IAC_MONTH_R_'.$i)),"'";}?>],
		'DAYS': [<?php for($i=1;$i<8;$i++){echo ($i==1 ? '' : ','),"'",CUtil::JSEscape(GetMessage('IAC_DAY_'.$i)),"'";}?>],
		'DAYS_FULL': [<?php for($i=1;$i<8;$i++){echo ($i==1 ? '' : ','),"'",CUtil::JSEscape(GetMessage('IAC_DAY_FULL_'.$i)),"'";}?>],
		'TYPE_BGCOLORS': <?=\CUtil::phpToJsObject($TYPE_BGCOLORS) ?>,
		'TYPES': <?=\CUtil::phpToJsObject($TYPES) ?>,
		'MESSAGES': {
			'IAC_MAIN_TITLE': '<?php echo CUtil::JSEscape(GetMessage('INTR_IAC_MAIN_TITLE'))?>',
			'IAC_FILTER_TYPEFILTER': '<?php echo CUtil::JSEscape(GetMessage('IAC_FILTER_TYPEFILTER'))?>',
			'IAC_FILTER_TYPEFILTER_ALL': '<?php echo CUtil::JSEscape(GetMessage('IAC_FILTER_TYPEFILTER_ALL'))?>',
			'IAC_FILTER_SHOW_ALL': '<?php echo CUtil::JSEscape(GetMessage('IAC_FILTER_SHOW_ALL'))?>',
			'IAC_FILTER_DEPARTMENT': '<?php echo CUtil::JSEscape(GetMessage('IAC_FILTER_DEPARTMENT'))?>',
			'INTR_ABSC_TPL_FILTER_ON': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_FILTER_ON'))?>',
			'INTR_ABSC_TPL_FILTER_OFF': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_FILTER_OFF'))?>',
			'INTR_ABSC_TPL_PERSONAL_LINK_TITLE': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_PERSONAL_LINK_TITLE'))?>',
			'INTR_ABSC_TPL_WARNING_MONTH': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_WARNING_MONTH'))?>',
			'INTR_ABSC_TPL_REPEATING_EVENT': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_REPEATING_EVENT'))?>',
			'INTR_ABSC_TPL_REPEATING_EVENT_DAILY': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_REPEATING_EVENT_DAILY'))?>',
			'INTR_ABSC_TPL_REPEATING_EVENT_WEEKLY': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_REPEATING_EVENT_WEEKLY'))?>',
			'INTR_ABSC_TPL_REPEATING_EVENT_MONTHLY': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_REPEATING_EVENT_MONTHLY'))?>',
			'INTR_ABSC_TPL_REPEATING_EVENT_YEARLY': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_REPEATING_EVENT_YEARLY'))?>',
			'INTR_ABSC_TPL_INFO_CLOSE': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_INFO_CLOSE'))?>',
			'INTR_ABSC_TPL_PAGE_BAR': '<?=CUtil::jsEscape(getMessage('INTR_ABSC_TPL_PAGE_BAR'))?>',
			'INTR_ABSC_TPL_PAGE_NEXT': '<?=CUtil::jsEscape(getMessage('INTR_ABSC_TPL_PAGE_NEXT'))?>',
		},
		'ERRORS': {
			'ERR_NO_VIEWS_REGISTERED': '<?php echo CUtil::JSEscape(GetMessage('IAC_ERR_NO_VIEWS_REGISTERED'))?>',
			'ERR_VIEW_NOT_REGISTERED': '<?php echo CUtil::JSEscape(GetMessage('IAC_ERR_VIEW_NOT_REGISTERED'))?>',
			'ERR_WRONG_LAYOUT': '<?php echo CUtil::JSEscape(GetMessage('IAC_ERR_WRONG_LAYOUT'))?>',
			'ERR_WRONG_HANDLER': '<?php echo CUtil::JSEscape(GetMessage('IAC_ERR_WRONG_HANDLER'))?>',
			'ERR_RUNTIME_NO_VIEW': '<?php echo CUtil::JSEscape(GetMessage('IAC_ERR_RUNTIME_NO_VIEW'))?>'
		}
	}
);

jsBXAC.RegisterView({ID:'day',NAME:'<?php echo CUtil::JSEscape(GetMessage('IAC_VIEW_DAY'))?>',SORT:100});
jsBXAC.RegisterView({ID:'week',NAME:'<?php echo CUtil::JSEscape(GetMessage('IAC_VIEW_WEEK'))?>',SORT:200});
jsBXAC.RegisterView({ID:'month',NAME:'<?php echo CUtil::JSEscape(GetMessage('IAC_VIEW_MONTH'))?>',SORT:300});

BX.ready(function() {
	jsBXAC.Show(document.getElementById('bx_calendar_layout'), '<?php echo $arParams['VIEW_START']?>');
});

<?php 
if ($arParams['bAdmin']):
?>
var jsBXCalendarAdmin = {
	'LANG': '<?php echo LANGUAGE_ID?>',
	'IBLOCK_TYPE': '<?php echo CUtil::JSEscape($arParams['IBLOCK_TYPE'])?>',
	'IBLOCK_ID': '<?php echo $arParams['IBLOCK_ID']?>',
	'EDIT': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_EDIT'))?>',
	'DELETE': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_DELETE'))?>',
	'DELETE_CONFIRM': '<?php echo CUtil::JSEscape(GetMessage('INTR_ABSC_TPL_DELETE_CONFIRM'))?>'
}
<?php 
endif;
?>
</script>

<?php 
if ($arParams['bAdmin'] && $USER->IsAuthorized()):
	$this->SetViewTarget('pagetitle', 100);?>
	<span class="webform-small-button webform-small-button-blue webform-small-button-add"
	   onclick="<?="BX.AbsenceCalendar.ShowForm(".CUtil::PhpToJSObject($arAbsenceParams).")"?>">
		<span class="webform-small-button-icon"></span>
		<span class="webform-small-button-text"><?=GetMessage('INTR_ABSC_TPL_ADD_ENTRY')?></span>
	</span>
	<?php 
	$this->EndViewTarget();
endif;
?>

<div id="bx_calendar_conrol_departments" style="display: none;"><?php 
	CIntranetUtils::ShowDepartmentFilter($arResult['UF_DEPARTMENT_field'], true);
?>
</div><div id="bx_calendar_control_datepicker" style="display: none"><input type="hidden" id="bx_goto_date" name="bx_goto_date" value="<?php echo ConvertTimeStamp();?>" /><img src="/bitrix/js/main/core/images/calendar-icon.gif" class="calendar-icon" onclick="BX.calendar({node:this, field:'bx_goto_date', bTime: false, callback: jsBXAC.InsertDate});" onmouseover="BX.addClass(this, 'calendar-icon-hover');" onmouseout="BX.removeClass(this, 'calendar-icon-hover');" border="0"/></div>
<div id="bx_calendar_layout" style="min-height: 280px;"></div>
