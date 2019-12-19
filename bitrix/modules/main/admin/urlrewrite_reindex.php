<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/prolog.php");
define("HELP_FILE", "settings/urlrewrite_reindex.php");

IncludeModuleLangFile(__FILE__);

if(!$USER->CanDoOperation('edit_php'))
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$res=false;
if(strlen($Reindex)>0 && check_bitrix_sessid())
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");

	if(strlen($Next)<=0 || !CheckSerializedData($_REQUEST['NS']))
	{
		$NS = array(
			"max_execution_time" => $max_execution_time,
			"stepped" => $stepped,
			"max_file_size" => $max_file_size
		);
		if($site_id!="")
			$NS["SITE_ID"] = $site_id;
	}
	else
		$NS = unserialize($_REQUEST['NS']);

	$res = \Bitrix\Main\UrlRewriter::reindexAll(($NS["stepped"]=="Y"? $NS["max_execution_time"]:0), $NS);

	if(is_array($res)):
		//$res["STAT"]=$NS["STAT"];
		//$res["STAT"][]=$res["CNT"]-$NS["CNT"];
		//$perfomance = "<br>",implode($res["STAT"],", ");
		CAdminMessage::ShowMessage(array(
			"MESSAGE"=>GetMessage("url_rewrite_mess_title"),
			"DETAILS"=>GetMessage("MURL_REINDEX_TOTAL")." <b>".$res["CNT"]."</b>",
			"HTML"=>true,
			"TYPE"=>"OK",
		));
	?>
		<input type="hidden" id="NS" name="NS" value="<?=htmlspecialcharsbx(serialize($res))?>">
	<?php else:
		CAdminMessage::ShowMessage(array(
			"MESSAGE"=>GetMessage("MURL_REINDEX_COMPLETE"),
			"DETAILS"=>GetMessage("MURL_REINDEX_TOTAL")." <b>".$res."</b>",
			"HTML"=>true,
			"TYPE"=>"OK",
		));
	?>
		<input type="hidden" id="NSTOP" name="NSTOP" value="Y">
	<?php endif;
	require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin_js.php");
}
else
{

$APPLICATION->SetTitle(GetMessage("MURL_REINDEX_TITLE"));

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("MURL_REINDEX_TAB"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("MURL_REINDEX_TAB_TITLE")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<script language="JavaScript">
var savedNS;
var stop;
function StartReindex()
{
	stop=false;
	savedNS='start!';
	document.getElementById('reindex_result_div').innerHTML='';
	document.getElementById('stop_button').disabled=false;
	document.getElementById('start_button').disabled=true;
	document.getElementById('continue_button').disabled=true;
	setTimeout('DoNext()', 1000);
}
function DoNext()
{
	if(document.getElementById('NS'))
		newNS=document.getElementById('NS').value;
	else
		newNS=null;
	if(document.getElementById('NSTOP'))
	{
		EndReindex();
		return;
	}
	if(newNS!=savedNS)
	{
		queryString='lang=<?php echo htmlspecialcharsbx(LANG)?>';
		if(savedNS!='start!')
		{
			queryString+='&Next=Y';
			if(document.getElementById('NS'))
				queryString+='&NS='+document.getElementById('NS').value;
		}
		site_id = document.fs1.LID.value;
		if(site_id!='NOT_REF')
			queryString+='&site_id='+site_id;
		queryString+='&max_file_size='+document.getElementById('max_file_size').value;
		if(document.getElementById('stepped').checked)
			queryString+='&stepped=Y';
		queryString+='&max_execution_time='+document.getElementById('max_execution_time').value;
		queryString+='&Reindex=Y';
		queryString+='&<?php echo bitrix_sessid_get()?>';
		savedNS=newNS;
		//alert(queryString);
		CHttpRequest.Action = function(result)
		{
			CloseWaitWindow();
			document.getElementById('reindex_result_div').innerHTML = result;
		}
		ShowWaitWindow();
		CHttpRequest.Send('urlrewrite_reindex.php?'+queryString);
	}
	if(!stop)
		setTimeout('DoNext()', 1000);
}
function StopReindex()
{
	stop=true;
	document.getElementById('stop_button').disabled=true;
	document.getElementById('start_button').disabled=false;
	document.getElementById('continue_button').disabled=false;
}
function ContinueReindex()
{
	stop=false;
	document.getElementById('stop_button').disabled=false;
	document.getElementById('start_button').disabled=true;
	document.getElementById('continue_button').disabled=true;
	setTimeout('DoNext()', 1000);
}
function EndReindex()
{
	stop=true;
	document.getElementById('stop_button').disabled=true;
	document.getElementById('start_button').disabled=false;
	document.getElementById('continue_button').disabled=true;
}
</script>

<div id="reindex_result_div" style="margin:0px"></div>

<form method="POST" action="<?php echo $APPLICATION->GetCurPage()?>?lang=<?php echo htmlspecialcharsbx(LANG)?>" name="fs1">
<?php 
$tabControl->Begin();
$tabControl->BeginNextTab();
?>
	<tr>
		<td width="40%"><?=GetMessage("MURL_REINDEX_SITE")?></td>
		<td width="60%"><?php echo CLang::SelectBox("LID", $str_LID, GetMessage("MURL_REINDEX_ALL"), "");?></td>
	</tr>
	<tr>
		<td><?php echo GetMessage("MURL_REINDEX_MAX_SIZE")?></td>
		<td><input type="text" name="max_file_size" id="max_file_size" size="10" value="<?php echo COption::GetOptionString("main", "urlrewrite_max_file_size");?>"><?php echo GetMessage("MURL_REINDEX_MAX_SIZE_kb")?></td>
	</tr>
	<tr>
		<td><?php echo GetMessage("MURL_REINDEX_STEPPED")?></td>
		<td><input type="checkbox" name="stepped" id="stepped" value="Y" OnClick="trs.disabled=!this.checked;document.fs1.max_execution_time.disabled=!this.checked;" <?php if($stepped=="Y") echo " checked"?>></td>
	</tr>
	<tr id="trs" <?php if($stepped!="Y") echo " disabled"?>>
		<td><?php echo GetMessage("MURL_REINDEX_STEP")?></td>
		<td><input type="text" name="max_execution_time" id="max_execution_time" size="3" value="<?php echo htmlspecialcharsbx($max_execution_time);?>"  <?php if($stepped!="Y") echo " disabled"?>> <?php echo GetMessage("MURL_REINDEX_STEP_sec")?></td>
	</tr>

<?php 
$tabControl->Buttons();
?>
	<input type="button" id="start_button" value="<?php echo GetMessage("MURL_REINDEX_REINDEX_BUTTON")?>" OnClick="StartReindex();" class="adm-btn-save">
	<input type="button" id="stop_button" value="<?=GetMessage("MURL_REINDEX_STOP")?>" OnClick="StopReindex();" disabled>
	<input type="button" id="continue_button" value="<?=GetMessage("MURL_REINDEX_CONTINUE")?>" OnClick="ContinueReindex();" disabled>
<?php 
$tabControl->End();
?>
</form>

<?php 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
}
?>
