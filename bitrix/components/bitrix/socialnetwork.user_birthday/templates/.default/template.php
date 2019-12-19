<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use Bitrix\Main\UI;

UI\Extension::load("ui.tooltip");

if(strlen($arResult["FatalError"])>0)
{
	?><span class='errortext'><?=$arResult["FatalError"]?></span><br /><br /><?php 
}
else
{
	if(strlen($arResult["ErrorMessage"])>0)
	{
		?><span class='errortext'><?=$arResult["ErrorMessage"]?></span><br /><br /><?php 
	}

	if ($arResult["CurrentUserPerms"]["Operations"]["viewprofile"] && $arResult["CurrentUserPerms"]["Operations"]["viewfriends"])
	{
		if ($arResult["Users"] && $arResult["Users"]["List"])
		{
			?><div class="sonet-cntnr-user-birthday"><?php 
				?><table width="100%" border="0" class="sonet-user-profile-friend-box"><?php 

				if (!empty($arResult["Users"]["List"]))
				{
					$APPLICATION->SetAdditionalCSS('/bitrix/components/bitrix/main.user.link/templates/.default/style.css');

					$APPLICATION->IncludeComponent("bitrix:main.user.link",
						'',
						array(
							"AJAX_ONLY" => "Y",
							"PATH_TO_SONET_USER_PROFILE" => $arParams["~PATH_TO_USER"],
							"PATH_TO_SONET_MESSAGES_CHAT" => $arParams["~PATH_TO_MESSAGES_CHAT"],
							"DATE_TIME_FORMAT" => $arParams["DATE_TIME_FORMAT"],
							"SHOW_YEAR" => $arParams["SHOW_YEAR"],
							"NAME_TEMPLATE" => $arParams["NAME_TEMPLATE"],
							"SHOW_LOGIN" => $arParams["SHOW_LOGIN"],
							"PATH_TO_CONPANY_DEPARTMENT" => $arParams["~PATH_TO_CONPANY_DEPARTMENT"],
							"PATH_TO_VIDEO_CALL" => $arParams["~PATH_TO_VIDEO_CALL"],
						),
						false,
						array("HIDE_ICONS" => "Y")
					);
				}
			
				foreach ($arResult["Users"]["List"] as $friend)
				{
					?><tr><?php 
						?><td align="left"><?php 

						$arTmpUser = array(
							"ID" => $friend["USER_ID"],
							"NAME" => htmlspecialcharsback($friend["NAME"]),
							"LAST_NAME" => htmlspecialcharsback($friend["LAST_NAME"]),
							"SECOND_NAME" => htmlspecialcharsback($friend["SECOND_NAME"]),
							"LOGIN" => htmlspecialcharsback($friend["LOGIN"])
						);

						$link = CComponentEngine::MakePathFromTemplate($arParams["~PATH_TO_USER"], array("user_id" => $friend["USER_ID"], "USER_ID" => $friend["ID"], "ID" => $friend["ID"]));

						?><table cellspacing="0" cellpadding="0" border="0" class="bx-user-info-anchor" bx-tooltip-user-id="<?=$friend["ID"]?>"><?php 
						?><tr><?php 
							?><td class="bx-user-info-anchor-cell"><?php 
								?><div class="bx-user-info-thumbnail" align="center" valign="middle" style="width: 30px; height: 32px;"><?php 
									?><?=$friend["PERSONAL_PHOTO_IMG"]?><?php 
								?></div><?php 
							?></td><?php 
							?><td class="bx-user-info-anchor-cell" valign="top"><?php 
								?><a class="bx-user-info-name" href="<?=$link?>"><?=CUser::FormatName($arParams["NAME_TEMPLATE"], $arTmpUser, ($arParams["SHOW_LOGIN"] != "N"))?></a><?php 
							?></td><?php 
						?></tr><?php 
						?></table><?php 

						?><div style="padding-top:5px;"><?php 
							if ($friend["NOW"])
							{
								?><b><?=$friend["BIRTHDAY"]?></b><?php 
							}
							else
							{
								?><?=$friend["BIRTHDAY"]?><?php 
							}
						?></div><?php 
						?></td><?php 
					?></tr><?php 
				}
				?></table><?php 
			?></div><?php 
		}
		else
		{
			?><?=GetMessage("SONET_C33_T_NO_FRIENDS")?><?php 
		}
	}
	else
	{
		?><?=GetMessage("SONET_C33_T_FR_UNAVAIL")?><?php 
	}
}
?>