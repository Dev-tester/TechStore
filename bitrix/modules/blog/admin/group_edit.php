<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

$blogModulePermissions = $APPLICATION->GetGroupRight("blog");
if ($blogModulePermissions < "R")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/blog/include.php");
IncludeModuleLangFile(__FILE__);

$errorMessage = "";
$bVarsFromForm = false;

$ID = IntVal($ID);

if ($REQUEST_METHOD=="POST" && strlen($Update)>0 && $blogModulePermissions>="W" && check_bitrix_sessid())
{
	$arFields = array(
		"NAME" => $NAME,
		"SITE_ID" => $SITE_ID,
	);

	if ($ID > 0)
	{
		$arBlogGroup = CBlogGroup::GetByID($ID);

		if (!CBlogGroup::Update($ID, $arFields))
		{
			if ($ex = $APPLICATION->GetException())
				$errorMessage .= $ex->GetString().". ";
			else
				$errorMessage .= GetMessage("BLGE_ERROR_SAVING").". ";
		}
	}
	else
	{
		$ID = CBlogGroup::Add($arFields);
		$ID = IntVal($ID);
		if ($ID <= 0)
		{
			if ($ex = $APPLICATION->GetException())
				$errorMessage .= $ex->GetString().". ";
			else
				$errorMessage .= GetMessage("BLGE_ERROR_SAVING").". ";
		}
	}

	if (strlen($errorMessage) <= 0)
	{
		$arBlogGroupTmp = CBlogGroup::GetByID($ID);
		BXClearCache(True, "/".$arBlogGroupTmp["SITE_ID"]."/blog/");
		if (!empty($arBlogGroup))
		{
			BXClearCache(True, "/".$arBlogGroup["SITE_ID"]."/blog/");
		}


		if (strlen($apply) <= 0)
			LocalRedirect("/bitrix/admin/blog_group.php?lang=".LANG."&".GetFilterParams("filter_", false));
	}
	else
	{
		$bVarsFromForm = true;
	}
}

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/blog/prolog.php");

if ($ID > 0)
	$APPLICATION->SetTitle(GetMessage("BLGE_UPDATING"));
else
	$APPLICATION->SetTitle(GetMessage("BLGE_ADDING"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$str_NAME = "";
$str_SITE_ID = "";

if ($ID > 0)
{
	$arGroup = CBlogGroup::GetByID($ID);
	if (!$arGroup)
	{
		if ($blogModulePermissions < "W")
			$errorMessage .= GetMessage("BLGE_NO_PERMS2ADD").". ";
		$ID = 0;
	}
	else
	{
		$str_NAME = htmlspecialcharsbx($arGroup["NAME"]);
		$str_SITE_ID = htmlspecialcharsbx($arGroup["SITE_ID"]);
	}
}

if ($bVarsFromForm)
	$DB->InitTableVarsForEdit("b_blog_group", "", "str_");
?>

<?php 
$aMenu = array(
	array(
		"TEXT" => GetMessage("BLGE_2FLIST"),
		"ICON" => "btn_list",
		"LINK" => "/bitrix/admin/blog_group.php?lang=".LANG."&".GetFilterParams("filter_", false)
	)
);

if ($ID > 0 && $blogModulePermissions >= "W")
{
	$aMenu[] = array("SEPARATOR" => "Y");

	$aMenu[] = array(
			"TEXT" => GetMessage("BLGE_NEW_GROUP"),
			"ICON" => "btn_new",
			"LINK" => "/bitrix/admin/blog_group_edit.php?lang=".LANG."&".GetFilterParams("filter_", false)
		);

	$aMenu[] = array(
			"TEXT" => GetMessage("BLGE_DELETE_GROUP"), 
			"ICON" => "btn_delete",
			"LINK" => "javascript:if(confirm('".GetMessage("BLGE_DELETE_GROUP_CONFIRM")."')) window.location='/bitrix/admin/blog_group.php?ID=".$ID."&action=delete&lang=".LANG."&".bitrix_sessid_get()."#tb';",
			"WARNING" => "Y"
		);
}
$context = new CAdminContextMenu($aMenu);
$context->Show();
?>

<?php CAdminMessage::ShowMessage($errorMessage);?>

<form method="POST" action="<?php echo $APPLICATION->GetCurPage()?>?" name="form1">
<?php echo GetFilterHiddens("filter_");?>
<input type="hidden" name="Update" value="Y">
<input type="hidden" name="lang" value="<?php echo LANG ?>">
<input type="hidden" name="ID" value="<?php echo $ID ?>">
<?=bitrix_sessid_post()?>

<?php 
$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("BLGE_TAB_GROUP"), "ICON" => "blog", "TITLE" => GetMessage("BLGE_TAB_GROUP_DESCR"))
	);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
?>

<?php 
$tabControl->BeginNextTab();
?>

	<?php if ($ID > 0):?>
		<tr>
			<td width="40%">ID:</td>
			<td width="60%"><?=$ID?></td>
		</tr>
	<?php endif;?>
	<tr class="adm-detail-required-field">
		<td width="40%"><?php echo GetMessage("BLGE_NAME")?>:</td>
		<td width="60%">
			<input type="text" name="NAME" size="50" value="<?= $str_NAME ?>">
		</td>
	</tr>
	<tr class="adm-detail-required-field">
		<td><?php echo GetMessage("BLGE_SITE")?>:</td>
		<td>
			<?php echo CSite::SelectBox("SITE_ID", $str_SITE_ID, "", "");?>
		</td>
	</tr>

<?php 
$tabControl->EndTab();
?>

<?php 
$tabControl->Buttons(
		array(
				"disabled" => ($blogModulePermissions < "W"),
				"back_url" => "/bitrix/admin/blog_group.php?lang=".LANG."&".GetFilterParams("filter_", false)
			)
	);
?>

<?php 
$tabControl->End();
?>

</form>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>