<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?php IncludeTemplateLangFile(__FILE__);?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET;?>">
<meta name="robots" content="all">
<?php $APPLICATION->ShowMeta("keywords")?>
<?php $APPLICATION->ShowMeta("description")?>
<title><?php $APPLICATION->ShowTitle()?></title>
<?php $APPLICATION->ShowCSS();?>

<?php if (!isset($_GET["print_course"])):?>
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH."/print_style.css"?>" type="text/css" media="print" />
<?php else:?>
	<meta name="robots" content="noindex, follow" />
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH."/print_style.css"?>" type="text/css" />
<?php endif?>

<?php $APPLICATION->ShowHeadScripts();?>
<?php $APPLICATION->ShowHeadStrings();?>

<script type="text/javascript">
function ShowSwf(sSwfPath, width1, height1)
{
	var scroll = 'no';
	var top=0, left=0;
	if(width1 > screen.width-10 || height1 > screen.height-28)
		scroll = 'yes';
	if(height1 < screen.height-28)
		top = Math.floor((screen.height - height1)/2-14);
	if(width1 < screen.width-10)
		left = Math.floor((screen.width - width1)/2);
	width = Math.min(width1, screen.width-10);
	height = Math.min(height1, screen.height-28);
	window.open('<?=SITE_TEMPLATE_PATH."/js/swfpg.php"?>?width='+width1+'&height='+height1+'&img='+sSwfPath,'','scrollbars='+scroll+',resizable=yes, width='+width+',height='+height+',left='+left+',top='+top);
}
</script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH."/js/imgshw.js"?>"></script>
</head>

<?php 
function ShowPanel()
{
	$GLOBALS["APPLICATION"]->AddBufferContent("GetPanel");
}

function GetPanel()
{
	$panel = CTopPanel::GetPanelHtml();
	if (strlen($panel) > 0)
		return 	"<tr><td id=\"panel\">".$panel."</td></tr>";

}
?>

<body>

<table id="layout" cellspacing="0">
	<?php ShowPanel()?>
	<tr>
		<td id="header">
			<table id="header-layout" cellspacing="0">
				<tr>
					<td id="logo">
						<div id="logo-box">
							<div id="logo-text"><?=GetMessage("LEARNING_LOGO_TEXT")?></div>
							<div id="toolbar">
								<a class="toolbar-item" title="<?=GetMessage("LEARNING_PASS_TEST")?>" href="<?php $APPLICATION->ShowProperty("learning_test_list_url")?>"><i class="toolbar-icon toolbar-icon-tests"></i></a>
								<a class="toolbar-item" href="<?php $APPLICATION->ShowProperty("learning_gradebook_url")?>" title="<?=GetMessage("LEARNING_GRADEBOOK")?>"><i class="toolbar-icon toolbar-icon-gradebook"></i></a>
								<a class="toolbar-item" href="<?php $APPLICATION->ShowProperty("learning_course_contents_url")?>" title="<?=GetMessage("LEARNING_ALL_COURSE_CONTENTS")?>"><i class="toolbar-icon toolbar-icon-contents"></i></a>
								<a class="toolbar-item" href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("print_course=Y", array("print_course")), false)?>" rel="nofollow" title="<?=GetMessage("LEARNING_PRINT_PAGE")?>"><i class="toolbar-icon toolbar-icon-printer"></i></a>
							</div>
						</div>
					</td>
					<td id="title">
						<table id="title-layout" cellspacing="0">
							<tr>
								<td id="course-title">
									<div id="course-title-box">
										<div id="course-title-text"><?php $APPLICATION->ShowProperty("learning_course_name")?>&nbsp;</div>
										<?php /*$APPLICATION->IncludeComponent(
											"bitrix:search.form",
											"",
											Array(
												"USE_SUGGEST" => "N",
												"PAGE" => "search.php"
											),
											false
										);*/?>

									</div>
								</td>
							</tr>
							<tr>
								<td id="page-title">
									<div id="page-title-box">
										<b class="r1"></b><b class="r0"></b>
										<div id="page-title-text"><?php $APPLICATION->ShowTitle()?></div>
										<div id="page-title-numbers"><span title="<?=GetMessage("LEARNING_CURRENT_LESSON")?>"><?php $APPLICATION->ShowProperty("learning_lesson_current")?></span>&nbsp;/&nbsp;<span title="<?=GetMessage("LEARNING_ALL_LESSONS")?>"><?php $APPLICATION->ShowProperty("learning_lesson_count")?></span></div>
										<b class="r0"></b><b class="r1"></b>
									</div>
								</td>
							</tr>
						</table>

					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td id="workarea">

<?php  if (defined("BX_AUTH_FORM") && BX_AUTH_FORM === true): ?>
<table class="learn-work-table">
<tr>
	<td class="learn-left-data" valign="top"></td>
	<td class="learn-right-data" valign="top">
<?php 
endif;