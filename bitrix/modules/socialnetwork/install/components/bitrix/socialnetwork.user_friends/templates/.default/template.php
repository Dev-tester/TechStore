<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if(strlen($arResult["FatalError"])>0)
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
	?>	
	<?php if ($arResult["CanViewLog"]):?>
		<a href="<?= $arResult["Urls"]["LogUsers"] ?>"><?= GetMessage("SONET_C33_T_UPDATES") ?></a><br /><br />
	<?php endif;?>
	<?php 
	if ($arResult["CurrentUserPerms"]["IsCurrentUser"]):
		?><form method="post" name="form1" action="<?=POST_FORM_ACTION_URI?>" enctype="multipart/form-data"><?php 
	endif;
	?>
	<?php if (StrLen($arResult["NAV_STRING"]) > 0):?>
		<?=$arResult["NAV_STRING"]?><br /><br />
	<?php endif;?>
	<div class="sonet-cntnr-user-friends">
	<table width="100%" class="sonet-user-profile-friends data-table">
		<tr>
			<th colspan="2"><?= GetMessage("SONET_C33_T_FRIENDS") ?></th>
		</tr>
		<tr>
			<td>
				<?php 
				if ($arResult["CurrentUserPerms"]["Operations"]["viewprofile"] && $arResult["CurrentUserPerms"]["Operations"]["viewfriends"])
				{
					if ($arResult["Friends"] && $arResult["Friends"]["List"])
					{
						?>
						<table width="100%" border="0" class="sonet-user-profile-friend-box">
						<tr>
							<td align="left" valign="top">								
						<?php 
						$ind = 0;
						$ind_row = 0;
					
						$colcnt = 2;
						$cnt = count($arResult["Friends"]["List"]);
						$rowcnt = intval(round($cnt / $colcnt));
					
						foreach ($arResult["Friends"]["List"] as $friend)
						{
							if ($ind_row >= $rowcnt)
							{
								echo "</td><td align=\"left\" valign=\"top\" width=\"".intval(100 / $colcnt)."%\">";
								$ind_row = 0;
							}

							?><div class="user-div"><?php 
							
							if ($arResult["CurrentUserPerms"]["IsCurrentUser"])
							{
								?><table cellspacing="0" cellpadding="0" border="0" class="sonet-user-profile-friend-user">
								<tr>
									<td align="right" class="checkbox-cell"><?php 
									echo "<input type=\"checkbox\" name=\"checked_".$ind."\" value=\"Y\">";
									echo "<input type=\"hidden\" name=\"id_".$ind."\" value=\"".$friend["USER_ID"]."\">";
									?></td>
									<td><?php 
							}
							
							$APPLICATION->IncludeComponent("bitrix:main.user.link",
								'',
								array(
									"ID" => $friend["USER_ID"],
									"HTML_ID" => "user_friends_".$friend["USER_ID"],
									"NAME" => htmlspecialcharsback($friend["USER_NAME"]),
									"LAST_NAME" => htmlspecialcharsback($friend["USER_LAST_NAME"]),
									"SECOND_NAME" => htmlspecialcharsback($friend["USER_SECOND_NAME"]),
									"LOGIN" => htmlspecialcharsback($friend["USER_LOGIN"]),
									"PERSONAL_PHOTO_IMG" => $friend["USER_PERSONAL_PHOTO_IMG"],
									"PROFILE_URL" => htmlspecialcharsback($friend["USER_PROFILE_URL"]),
									"PATH_TO_SONET_MESSAGES_CHAT" => $arParams["~PATH_TO_MESSAGES_CHAT"],
									"PATH_TO_SONET_USER_PROFILE" => $arParams["~PATH_TO_USER"],
									"THUMBNAIL_LIST_SIZE" => $arParams["THUMBNAIL_LIST_SIZE"],
									"DATE_TIME_FORMAT" => $arParams["DATE_TIME_FORMAT"],
									"SHOW_YEAR" => $arParams["SHOW_YEAR"],
									"CACHE_TYPE" => $arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
									"SHOW_LOGIN" => $arParams["SHOW_LOGIN"],
									"PATH_TO_CONPANY_DEPARTMENT" => $arParams["~PATH_TO_CONPANY_DEPARTMENT"],
									"PATH_TO_VIDEO_CALL" => $arParams["~PATH_TO_VIDEO_CALL"],
								),
								false
								, array("HIDE_ICONS" => "Y")
							);

							if (StrLen($friend["REQUEST_GROUP_LINK"]) > 0 || $friend["CAN_ADD2FRIENDS"] || $friend["CAN_DELETE_FRIEND"])							
							{
								?><div class="desc-div"><?php 
								if (StrLen($friend["REQUEST_GROUP_LINK"]) > 0)
									echo "<br><a href=\"".$friend["REQUEST_GROUP_LINK"]."\" class=\"action-link\"><b>".GetMessage("SONET_C33_T_INVITE")."</b></a>";
								?></div><?php 
							}
							
							if ($arResult["CurrentUserPerms"]["IsCurrentUser"])
							{
									?></td>
								</tr>
								</table><?php 
							}
							
							$ind++;
							$ind_row++;						
							?></div><?php 
						}
						?>
							</td>
						</tr>
						</table>
						<?php 
					}
					else
						echo GetMessage("SONET_C33_T_NO_FRIENDS");
				}
				else
					echo GetMessage("SONET_C33_T_FR_UNAVAIL");
				?>
				<?php if ($arResult["CurrentUserPerms"]["IsCurrentUser"]):?>
					<a href="<?= $arResult["Urls"]["Search"] ?>"><?= (StrLen($friend["REQUEST_GROUP_LINK"]) > 0) ? GetMessage("SONET_C33_T_ADD_FRIEND1") : GetMessage("SONET_C33_T_ADD_FRIEND") ?></a>
				<?php endif;?>
			</td>
		</tr>
	</table>
	</div>
	<?php if (StrLen($arResult["NAV_STRING"]) > 0):?>
		<br><?=$arResult["NAV_STRING"]?><br /><br />
	<?php endif;?>
	<?php 
	if ($arResult["CurrentUserPerms"]["IsCurrentUser"]):
		?><br />
		<input type="hidden" name="max_count" value="<?= $ind ?>">
		<?=bitrix_sessid_post()?>
		<input type="submit" name="delete" value="<?= GetMessage("SONET_C33_T_DELETE") ?>">		
		</form><?php 
	endif;	
}
?>