<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
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
	if (strlen($arResult["ErrorMessage"]) > 0)
	{
		?>
		<span class='errortext'><?=$arResult["ErrorMessage"]?></span><br /><br />
		<?php 
	}

	?><form method="post" name="form1" action="<?=POST_FORM_ACTION_URI?>" enctype="multipart/form-data">
	<?php if (StrLen($arResult["NAV_STRING"]) > 0):?>
		<?=$arResult["NAV_STRING"]?><br /><br />
	<?php endif;?>
	<div class="sonet-cntnr-user-ban">
	<table width="100%" class="sonet-user-profile-friends data-table">
		<tr>
			<th><?= GetMessage("SONET_C32_T_BAN") ?></th>
		</tr>
		<tr>
			<td>
				<?php 
				if ($arResult["Ban"] && $arResult["Ban"]["List"])
				{
					?>
					<table width="100%" border="0" class="sonet-user-profile-friend-box">
					<tr>
						<td align="left" valign="top">						
					<?php 
					$ind = 0;
					$ind_row = 0;
					
					$colcnt = 2;
					$cnt = count($arResult["Ban"]["List"]);
					$rowcnt = intval(round($cnt / $colcnt));
					
					foreach ($arResult["Ban"]["List"] as $ban)
					{
						if ($ind_row >= $rowcnt)
						{
							echo "</td><td align=\"left\" valign=\"top\" width=\"".intval(100 / $colcnt)."%\">";
							$ind_row = 0;
						}

						?><div class="user-div"><?php 

						if ($ban["CAN_DELETE_BAN"])
						{
							?><table cellspacing="0" cellpadding="0" border="0" class="sonet-user-profile-friend-user">
							<tr>
								<td align="right" class="checkbox-cell"><?php 
								echo "<input type=\"checkbox\" name=\"checked_".$ind."\" value=\"Y\">";
								echo "<input type=\"hidden\" name=\"id_".$ind."\" value=\"".$ban["ID"]."\">";
								?></td>
								<td><?php 
						}


						$APPLICATION->IncludeComponent("bitrix:main.user.link",
							'',
							array(
								"ID" => $ban["USER_ID"],
								"HTML_ID" => "user_ban_".$ban["USER_ID"],
								"NAME" => htmlspecialcharsback($ban["USER_NAME"]),
								"LAST_NAME" => htmlspecialcharsback($ban["USER_LAST_NAME"]),
								"SECOND_NAME" => htmlspecialcharsback($ban["USER_SECOND_NAME"]),
								"LOGIN" => htmlspecialcharsback($ban["USER_LOGIN"]),
								"PERSONAL_PHOTO_IMG" => $ban["USER_PERSONAL_PHOTO_IMG"],
								"PERSONAL_PHOTO_FILE" => $ban["USER_PERSONAL_PHOTO_FILE"],
								"PROFILE_URL" => $ban["USER_PROFILE_URL"],
								"THUMBNAIL_LIST_SIZE" => $arParams["THUMBNAIL_LIST_SIZE"],
								"PATH_TO_SONET_MESSAGES_CHAT" => $arParams["~PATH_TO_MESSAGES_CHAT"],
								"PATH_TO_SONET_USER_PROFILE" => $arParams["~PATH_TO_USER"],
								"PATH_TO_VIDEO_CALL" => $arParams["~PATH_TO_VIDEO_CALL"],
								"DATE_TIME_FORMAT" => $arParams["DATE_TIME_FORMAT"],
								"SHOW_YEAR" => $arParams["SHOW_YEAR"],
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
								"SHOW_LOGIN" => $arParams["SHOW_LOGIN"],
								"PATH_TO_CONPANY_DEPARTMENT" => $arParams["~PATH_TO_CONPANY_DEPARTMENT"],
							),
							false,
							array("HIDE_ICONS" => "Y")
						);

						if ($ban["CAN_DELETE_BAN"])
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
				{
					echo GetMessage("SONET_C32_T_EMPTY");
				}
				?>
			</td>
		</tr>
	</table>
	</div>
	<?php if (StrLen($arResult["NAV_STRING"]) > 0):?>
		<br><?=$arResult["NAV_STRING"]?><br /><br />
	<?php endif;?>
	<br />
	<input type="hidden" name="max_count" value="<?= $ind ?>">
	<?=bitrix_sessid_post()?>
	<input type="submit" name="delete" value="<?= GetMessage("SONET_C32_T_DELETE") ?>">		
	</form><?php 
}
?>