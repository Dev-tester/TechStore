<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 

if (!function_exists("__GetVisibleJS")) 
{
	function __GetVisibleJS($arFeature, $IsCB)
	{
		if (!$IsCB)
		{
			$InheritedKey = "VisibleInherited";
			$Key = "Visible";
		}
		else
		{
			$InheritedKey = "VisibleInheritedCB";
			$Key = "VisibleCB";
		}
		
		if ($arFeature[$InheritedKey] && $arFeature[$Key] == "Y")
		{
			$strArCheckboxVal = "arCheckboxVal = ['I', 'N', 'Y'];\n";
			$strCheckboxClassName = "checkboxClassName = 'subscribe-checkbox subscribe-checkbox-i-Y';\n";
			$strHiddenValue = "hiddenValue = 'I'\n";
		}
		elseif ($arFeature[$InheritedKey])			
		{
			$strArCheckboxVal = "arCheckboxVal = ['I', 'Y', 'N'];\n";
			$strCheckboxClassName = "checkboxClassName = 'subscribe-checkbox subscribe-checkbox-i-N';\n";
			$strHiddenValue = "hiddenValue = 'I'\n";
		}
		else
		{
			$strArCheckboxVal = "arCheckboxVal = ['Y', 'N'];\n";
			$strCheckboxClassName = "checkboxClassName = 'subscribe-checkbox subscribe-checkbox-".$arFeature[$Key]."';\n";
			$strHiddenValue = "hiddenValue = '".$arFeature[$Key]."'\n";
		}

		return array(
				"strArCheckboxVal"		=> $strArCheckboxVal,
				"strCheckboxClassName"	=> $strCheckboxClassName,
				"strHiddenValue"		=> $strHiddenValue
			);
	}
}


if ($arResult["NEED_AUTH"] == "Y")
{
	$APPLICATION->AuthForm("");
}
elseif (strlen($arResult["FatalError"]) > 0)
{
	?>
	<span class='errortext'><?=$arResult["FatalError"]?></span><br /><br />
	<?php 
}
else
{
	if(strlen($arResult["ErrorMessage"])>0)
	{
		?>
		<span class='errortext'><?=$arResult["ErrorMessage"]?></span><br /><br />
		<?php 
	}

	if ($arResult["ShowForm"] == "Input")
	{
		?>
		<script language="JavaScript">
		<!--
			BX.message({
				sonetSShowInList: '<?=CUtil::JSEscape(GetMessage('SONET_C3_SHOW_IN_LIST'))?>'
			});	
			var SVisibleCheckbox = null;
			var arCheckboxVal = null;
			var checkboxClassName = null;
			var hiddenValue = null;
		-->
		</script>	
		<form method="post" name="form1" action="<?=POST_FORM_ACTION_URI?>" enctype="multipart/form-data">
			<table class="sonet-subscribe-form" cellspacing="0" cellpadding="0">
				<tr>
					<td class="subscribe-header" colspan="2">
					<table width="100%">
					<tr>
						<td class="subscribe-header-left"><img src="/bitrix/images/1.gif" width="2" height="29"></td>
						<td class="subscribe-header-center"><b><?php 
						echo GetMessage("SONET_C3_ENTITY_TITLE_".$arParams["ENTITY_TYPE"]);
						?></b></td>
						<td class="subscribe-header-right"><img src="/bitrix/images/1.gif" width="2" height="29"></td>
					</tr>
					</table>
					</td>
				</tr>			
				<?php foreach ($arResult["Subscribe"] as $feature => $arFeature):

					if (
						$feature == "files" 
						|| (
							$feature == "system_friends" 
							&& (
								CModule::IncludeModule("extranet") && CExtranet::IsExtranetSite() 
								|| $arResult["FriendsAllowed"] != "Y"
							)
						)
					):
						?><input type="hidden" name="<?= $feature ?>_active" value="<?= ($arFeature["Active"] ? ($arFeature["MailEvent"] ? "M" : "S") : "N"); ?>"><?php 
					else:
						?>
						<tr>
							<td class="subscribe-feature-name">
								<?= $arFeature["SubscribeName"] ?>
							</td>
							<td id="bx-s-<?= $feature ?>">
								<select name="<?= $feature ?>_transport">
									<?php 
									if ($arFeature["TransportInherited"])
									{
										?>
										<option value="I" selected>
										<?php 
										echo GetMessage("SONET_C3_INHERITED")." (";

										switch ($arFeature["Transport"])
										{
											case "N":
												echo GetMessage("SONET_C3_TRANSPORT_NONE");
												break;
											case "M":
												echo GetMessage("SONET_C3_TRANSPORT_MAIL");
												break;
											case "X":
												echo GetMessage("SONET_C3_TRANSPORT_XMPP");
												break;
										}

										echo ")";
										?>
										</option>
										<?php 
									}
									?>
									<option value="N"<?= ($arFeature["Transport"] == "N" && !$arFeature["TransportInherited"] ? " selected" : "") ?>><?= GetMessage("SONET_C3_TRANSPORT_NONE") ?></option>
									<option value="M"<?= ($arFeature["Transport"] == "M" && !$arFeature["TransportInherited"] ? " selected" : "") ?>><?= GetMessage("SONET_C3_TRANSPORT_MAIL") ?></option>
									<?php 
									if (CBXFeatures::IsFeatureEnabled("WebMessenger"))
									{
										?><option value="X"<?= ($arFeature["Transport"] == "X" && !$arFeature["TransportInherited"] ? " selected" : "") ?>><?= GetMessage("SONET_C3_TRANSPORT_XMPP") ?></option><?php 
									}
								?></select><?php 

								if (!defined("DisableSonetLogVisibleSubscr") || DisableSonetLogVisibleSubscr !== true)
								{
									?><br><?php 
									$arVisibleJS = __GetVisibleJS($arFeature, false);

									?><script language="JavaScript">
									<!--
									<?=$arVisibleJS["strArCheckboxVal"]?>
									<?=$arVisibleJS["strCheckboxClassName"]?>
									<?=$arVisibleJS["strHiddenValue"]?>

									SVisibleCheckbox = new BX.CSVisibleCheckbox(
										{
											'arCheckboxVal': arCheckboxVal,
											'bindElement': BX('bx-s-<?= $feature ?>'),
											'checkboxClassName': checkboxClassName,
											'hiddenName': '<?= $feature ?>_visible',
											'hiddenValue': hiddenValue,
											'visibleValue': '<?=$arFeature["Visible"]?>'
										}
									);
				
									SVisibleCheckbox.Show();
									-->
									</script><?php 
								}
							?></td>
						</tr><?php 
					endif;
				endforeach;

				$bFirstBlock = true;
				foreach ($arResult["Subscribe"] as $feature => $arFeature):
					
					if (array_key_exists("TransportCB", $arFeature)):

						if ($feature == "files")
							continue;

						if ($bFirstBlock):
							?><tr>
								<td class="subscribe-header" colspan="2">
								<table width="100%">
								<tr>
									<td class="subscribe-header-left"><img src="/bitrix/images/1.gif" width="2" height="29"></td>
									<td class="subscribe-header-center"><b><?php 
									echo GetMessage("SONET_C3_CREATED_BY_TITLE");
									$bFirstBlock = false;
									?></b></td>
									<td class="subscribe-header-right"><img src="/bitrix/images/1.gif" width="2" height="29"></td>
								</tr>
								</table>
								</td>
							</tr><?php 
						endif;						

						?><tr>
							<td class="subscribe-feature-name"><?= $arFeature["SubscribeName"] ?></td>
							<td id="bx-s-cb-<?= $feature ?>">
								<select name="cb_<?= $feature ?>_transport">
								<?php 
									if ($arFeature["TransportInheritedCB"])
									{
										?>
										<option value="I" selected>
										<?php 
										echo GetMessage("SONET_C3_INHERITED")." (";

										switch ($arFeature["TransportCB"])
										{
											case "N":
												echo GetMessage("SONET_C3_TRANSPORT_NONE");
												break;
											case "M":
												echo GetMessage("SONET_C3_TRANSPORT_MAIL");
												break;
											case "X":
												echo GetMessage("SONET_C3_TRANSPORT_XMPP");
												break;
										}

										echo ")";
										?>
										</option>
										<?php 
									}
									?>
									<option value="N"<?= ($arFeature["TransportCB"] == "N" && !$arFeature["TransportInheritedCB"] ? " selected" : "") ?>><?= GetMessage("SONET_C3_TRANSPORT_NONE") ?></option>
									<option value="M"<?= ($arFeature["TransportCB"] == "M" && !$arFeature["TransportInheritedCB"] ? " selected" : "") ?>><?= GetMessage("SONET_C3_TRANSPORT_MAIL") ?></option>
									<?php 
									if (CBXFeatures::IsFeatureEnabled("WebMessenger"))
									{
										?><option value="X"<?= ($arFeature["TransportCB"] == "X" && !$arFeature["TransportInheritedCB"] ? " selected" : "") ?>><?= GetMessage("SONET_C3_TRANSPORT_XMPP") ?></option><?php 
									}
									?>
								</select><?php 
								if (!defined("DisableSonetLogVisibleSubscr") || DisableSonetLogVisibleSubscr !== true)
								{
									?><br><?php 
									$arVisibleJS = __GetVisibleJS($arFeature, true);
									?>
									<script language="JavaScript">
									<!--
									<?=$arVisibleJS["strArCheckboxVal"]?>
									<?=$arVisibleJS["strCheckboxClassName"]?>
									<?=$arVisibleJS["strHiddenValue"]?>
									
									SVisibleCheckbox = new BX.CSVisibleCheckbox(
										{
											'arCheckboxVal': arCheckboxVal,
											'bindElement': BX('bx-s-cb-<?= $feature ?>'),
											'checkboxClassName': checkboxClassName,
											'hiddenName': 'cb_<?= $feature ?>_visible',
											'hiddenValue': hiddenValue,
											'visibleValue': '<?=$arFeature["Visible"]?>'
										}
									);

									SVisibleCheckbox.Show();
									-->
									</script><?php 
								}
								
							?></td>
						</tr><?php 
					endif;

				endforeach;
			?></table>
			<br><br>
			<input type="hidden" name="SONET_USER_ID" value="<?= $arParams["USER_ID"] ?>">
			<input type="hidden" name="SONET_GROUP_ID" value="<?= $arParams["GROUP_ID"] ?>">
			<?=bitrix_sessid_post()?>
			<br />
			<input type="submit" name="save" value="<?= GetMessage("SONET_C4_SUBMIT") ?>">
			<?php 
			if ($_REQUEST['backurl'] && strpos($_REQUEST['backurl'], "/") === 0)
				$backurl = htmlspecialcharsbx(CUtil::addslashes($_REQUEST['backurl']));
			elseif ($arParams["PAGE_ID"] == "group_subscribe") 
				$backurl = $arResult["Urls"]["Group"];
			else 
				$backurl = $arResult["Urls"]["User"];
			?>
			<input type="reset" name="cancel" value="<?= GetMessage("SONET_C4_T_CANCEL") ?>" OnClick="window.location='<?=$backurl?>'">
		</form>
		<?php 
	}
	else
	{
		?>
		<?php if ($arParams["PAGE_ID"] == "group_subscribe"):?>
			<?= GetMessage("SONET_C4_GR_SUCCESS") ?>
			<br><br>
			<a href="<?= $arResult["Urls"]["Group"] ?>"><?= $arResult["Group"]["NAME"]; ?></a>
		<?php else:?>
			<?= GetMessage("SONET_C4_US_SUCCESS") ?>
			<br><br>
			<a href="<?= $arResult["Urls"]["User"] ?>"><?= $arResult["User"]["~NAME_FORMATTED"]; ?></a>
		<?php endif;?>
		<br><br>
		<a href="<?= $arResult["Urls"]["MySubscribe"] ?>"><?= GetMessage("SONET_C4_T_MY_SUBSCR"); ?></a>
		<?php 
	}
}
?>