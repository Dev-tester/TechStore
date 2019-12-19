<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/subscribe/include.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/subscribe/prolog.php");

IncludeModuleLangFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("subscribe");
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$MAIN_RIGHT = $APPLICATION->GetGroupRight("main");

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("imp_import_tab"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("imp_import_tab_title")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs, true, true);

$arError = array();
$bShowRes = false;
if(!is_array($USER_GROUP_ID))
	$USER_GROUP_ID=array();

if($REQUEST_METHOD=="POST" && !empty($Import) && $POST_RIGHT>="W" && check_bitrix_sessid())
{
	//*************************************
	//Prepare emails
	//*************************************
	//This is from the form
	$sAddr = $ADDR_LIST.",";
	//And this is from the file
	if(!empty($_FILES["ADDR_FILE"]["tmp_name"]))
	{
		if((integer)$_FILES["ADDR_FILE"]["error"] <> 0)
			$arError[] = array("id"=>"ADDR_FILE", "text"=>GetMessage("subscr_imp_err1")." (".GetMessage("subscr_imp_err2")." ".$_FILES["ADDR_FILE"]["error"].")");
		else
			$sAddr .= file_get_contents($_FILES["ADDR_FILE"]["tmp_name"]);
	}

	//explode to emails array
	$aEmail = array();
	$addr = strtok($sAddr, ", \r\n\t");
	while($addr!==false)
	{
		if(strlen($addr) > 0)
			$aEmail[$addr] = true;
		$addr = strtok(", \r\n\t");
	}

	//check for duplicate emails
	$addr = CSubscription::GetList();
	while($addr_arr = $addr->Fetch())
		if(isset($aEmail[$addr_arr["EMAIL"]]))
			unset($aEmail[$addr_arr["EMAIL"]]);

	//*************************************
	//add users and subscribers
	//*************************************

	//constant part of the subscriber
	$subscr = new CSubscription;
	$arFields = Array(
		"ACTIVE" => "Y",
		"FORMAT" => ($FORMAT <> "html"? "text":"html"),
		"CONFIRMED" => ($CONFIRMED <> "Y"? "N":"Y"),
		"SEND_CONFIRM" => ($SEND_CONFIRM <> "Y"? "N":"Y"),
		"ALL_SITES" => "Y",
		"RUB_ID" => $RUB_ID
	);

	//constant part of the user
	if($USER_TYPE == "U")
		$user = new CUser;

	$nError = 0;
	$nSuccess = 0;
	foreach($aEmail as $email=>$temp)
	{
		$USER_ID = false;
		if($USER_TYPE == "U")
		{
			//add user
			$sPassw = randString(6);
			$arUserFields = Array(
				"LOGIN" => randString(50),
				"CHECKWORD" => randString(8),
				"PASSWORD" => $sPassw,
				"CONFIRM_PASSWORD" => $sPassw,
				"EMAIL" => $email,
				"ACTIVE" => "Y",
				"GROUP_ID" => ($MAIN_RIGHT >= "W"?$USER_GROUP_ID:array(COption::GetOptionString("main", "new_user_registration_def_group")))
			);
			if($USER_ID = $user->Add($arUserFields))
			{
				$user->Update($USER_ID, array("LOGIN"=>"user".$USER_ID));

				//send registration message
				if($SEND_REG_INFO == "Y")
					$user->SendUserInfo($USER_ID, $LID, GetMessage("subscr_send_info"));
			}
			else
			{
				$arError[] = array("id"=>"", "text"=>$email.": ".$user->LAST_ERROR);
				$nError++;
				continue;
			}
		}//$USER_TYPE == "U"

		//add subscription
		$arFields["USER_ID"] = $USER_ID;
		$arFields["EMAIL"] = $email;
		if(!$subscr->Add($arFields, $LID))
		{
			$arError[] = array("id"=>"", "text"=>$email.": ".$subscr->LAST_ERROR);
			$nError++;
		}
		else
			$nSuccess++;

	}//foreach
	$bShowRes = true;
}//$REQUEST_METHOD=="POST"
else
{
	//default falues
	$CONFIRMED = "Y";
	$USER_TYPE = "A";
	$SEND_REG_INFO = "Y";
	$FORMAT = "text";
	$USER_GROUP_ID = array();
	$RUB_ID = array();
}

$APPLICATION->SetTitle(GetMessage("imp_title"));

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

if(count($arError)>0)
{
	$e = new CAdminException($arError);
	$message = new CAdminMessage(GetMessage("imp_error"), $e);
	echo $message->Show();
}
?>
<?php 
if($bShowRes)
{
	CAdminMessage::ShowMessage(array(
		"MESSAGE"=>GetMessage("imp_results"),
		"DETAILS"=>GetMessage("imp_results_total").' <b>'.count($aEmail).'</b><br>'
			.GetMessage("imp_results_added").' <b>'.$nSuccess.'</b><br>'
			.GetMessage("imp_results_err").' <b>'.$nError.'</b>',
		"HTML"=>true,
		"TYPE"=>"PROGRESS",
	));
}
?>
<form ENCTYPE="multipart/form-data" action="<?php echo $APPLICATION->GetCurPage();?>" method="POST" name="impform">
<?php 
$tabControl->Begin();
$tabControl->BeginNextTab();
?>
	<tr class="heading">
		<td colspan="2"><?php echo GetMessage("imp_delim")?></td>
	</tr>
	<tr>
		<td><?php echo GetMessage("imp_file")?></td>
		<td><input type=file name="ADDR_FILE" size=30></td>
	</tr>
	<tr>
		<td class="adm-detail-valign-top"><?php echo GetMessage("imp_list")?></td>
		<td><textarea name="ADDR_LIST" rows=10 cols=45></textarea></td>
	</tr>
	<tr>
		<td><?php echo GetMessage("imp_send_code")?></td>
		<td><input type="checkbox" name="SEND_CONFIRM" value="Y"<?php if($SEND_CONFIRM == "Y") echo " checked"?>></td>
	</tr>
	<tr>
		<td><?php echo GetMessage("imp_conf")?></td>
		<td><input type="checkbox" name="CONFIRMED" value="Y"<?php if($CONFIRMED == "Y") echo " checked"?>></td>
	</tr>
	<tr class="heading">
		<td colspan="2"><?php echo GetMessage("imp_user")?><br><?php echo GetMessage("imp_user_anonym")?></td>
	</tr>
	<tr>
		<td class="adm-detail-valign-top"><?php echo GetMessage("imp_add")?></td>
		<td>
		<input id="USER_TYPE_1" name="USER_TYPE" type="radio" value="A"<?php if($USER_TYPE == "A") echo " checked"?> onClick="DisableControls(true);"><label for="USER_TYPE_1"><?php echo GetMessage("imp_add_anonym")?></label><br>
		<input id="USER_TYPE_2" name="USER_TYPE" type="radio" value="U"<?php if($USER_TYPE == "U") echo " checked"?> onClick="DisableControls(false);"><label for="USER_TYPE_2"><?php echo GetMessage("imp_add_users")?></label></td>
	</tr>
	<tr>
		<td><?php echo GetMessage("imp_send_reg")?></td>
		<td><input type="checkbox" name="SEND_REG_INFO" value="Y"<?php if($SEND_REG_INFO == "Y") echo " checked"?>>
<?php if($MAIN_RIGHT < "W"):?>
		<script language="JavaScript">
		function DisableControls(bDisable)
		{
		document.impform.SEND_REG_INFO.disabled=bDisable;
		}
		<?php 
		if($USER_TYPE == "A"):
		?>DisableControls(true);<?php 
		endif;
		?></script>
<?php endif;?>
		</td>
	</tr>
<?php if($MAIN_RIGHT >= "W"):?>
	<tr>
		<td class="adm-detail-valign-top"><?php echo GetMessage("imp_add_gr")?></td>
		<td><select name="USER_GROUP_ID[]" multiple size=10><?php 
		$groups = CGroup::GetList(($by1="sort"), ($order1="asc"), Array("ACTIVE"=>"Y"));
		while(($gr = $groups->Fetch())):
		?><OPTION VALUE="<?php echo $gr["ID"]?>"<?php if(in_array($gr["ID"], $USER_GROUP_ID)) echo " SELECTED"?>><?php echo htmlspecialcharsbx($gr["NAME"])." [".$gr["ID"]."]"?></OPTION><?php 
		endwhile;
		?></SELECT>
		<script language="JavaScript">
		function DisableControls(bDisable)
		{
		document.impform.SEND_REG_INFO.disabled=bDisable;
		document.impform.elements['USER_GROUP_ID[]'].disabled=bDisable;
		}
		<?php 
		if($USER_TYPE == "A"):
		?>DisableControls(true);<?php 
		endif;
		?></script>
		</td>
	</tr>
<?php endif;?>
	<tr>
		<td class="adm-detail-valign-top"><?php echo GetMessage("imp_subscr")?></td>
		<td>
			<div class="adm-list">
			<?php 
		$rubrics = CRubric::GetList(array("LID"=>"ASC", "SORT"=>"ASC", "NAME"=>"ASC"), array("ACTIVE"=>"Y"));
		$n=1;
		while(($rub=$rubrics->Fetch())):
			?>
			<div class="adm-list-item">
				<div class="adm-list-control"><input type="checkbox" id="RUB_ID_<?php echo $n?>" name="RUB_ID[]" value="<?php echo $rub["ID"]?>"<?php if(!$bShowRes || in_array($rub["ID"], $RUB_ID)) echo " checked"?>></div>
				<div class="adm-list-label"><label for="RUB_ID_<?php echo $n?>"><?php echo "[".$rub["LID"]."]&nbsp;".htmlspecialcharsbx($rub["NAME"])?></label></div>
			</div>
			<?php 
			$n++;
		endwhile;
		?></div></td>
	</tr>
	<tr>
		<td><?php echo GetMessage("imp_fmt")?></td>
		<td><input id="FORMAT_1" name="FORMAT" type="radio" value="text"<?php if($FORMAT == "text") echo " checked"?>><label for="FORMAT_1"><?php echo GetMessage("imp_fmt_text")?></label>&nbsp;/<input id="FORMAT_2" name="FORMAT" type="radio" value="html"<?php if($FORMAT == "html") echo " checked"?>><label for="FORMAT_2">HTML</label></td>
	</tr>
	<tr>
		<td><?php echo GetMessage("imp_site")?></td>
		<td><?php echo CLang::SelectBox("LID", $LID);?></td>
	</tr>
<?php 
$tabControl->Buttons();
?>
<input<?php if($POST_RIGHT<"W") echo " disabled";?> type="submit" name="Import" value="<?php echo GetMessage("imp_butt")?>" class="adm-btn-save">
<input type="hidden" name="lang" value="<?php echo LANG?>">
<?php echo bitrix_sessid_post();?>
<?php 
$tabControl->End();
?>
</form>

<?php 
$tabControl->ShowWarnings("impform", $message);
?>

<?php  require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>