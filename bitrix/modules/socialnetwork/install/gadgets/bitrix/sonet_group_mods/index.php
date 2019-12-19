<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use Bitrix\Main\UI;

UI\Extension::load("ui.tooltip");

if(!CModule::IncludeModule("socialnetwork"))
{
	return false;
}

?><table width="100%">
<tr>
	<td><?php 
	if (
		$arGadgetParams["MODERATORS_LIST"]
		&& is_array($arGadgetParams["MODERATORS_LIST"])
	)
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

		?><table width="100%" border="0" class="sonet-user-profile-friend-box"><?php 
		foreach ($arGadgetParams["MODERATORS_LIST"] as $friend)
		{

			?><tr><?php 
				?><td align="left"><?php 
					$arTmpUser = array(
						"ID" => $friend["USER_ID"],
						"NAME" => htmlspecialcharsback($friend["USER_NAME"]),
						"LAST_NAME" => htmlspecialcharsback($friend["USER_LAST_NAME"]),
						"SECOND_NAME" => htmlspecialcharsback($friend["USER_SECOND_NAME"]),
						"LOGIN" => htmlspecialcharsback($friend["USER_LOGIN"])
					);

					$link = CComponentEngine::MakePathFromTemplate($arParams["~PATH_TO_USER"], array("user_id" => $friend["USER_ID"], "USER_ID" => $friend["USER_ID"], "ID" => $friend["USER_ID"]));

					?><table cellspacing="0" cellpadding="0" border="0" class="bx-user-info-anchor" bx-tooltip-user-id="<?=$friend["USER_ID"]?>"><?php 
					?><tr><?php 
						?><td class="bx-user-info-anchor-cell"><?php 
							?><div class="bx-user-info-thumbnail" align="center" valign="middle" style="width: 30px; height: 32px;"><?php 
								?><?=$friend["USER_PERSONAL_PHOTO_IMG"]?><?php 
							?></div><?php 
						?></td><?php 
						?><td class="bx-user-info-anchor-cell" valign="top"><?php 
							?><a class="bx-user-info-name" href="<?=$link?>"><?=CUser::FormatName($arParams["NAME_TEMPLATE"], $arTmpUser, ($arParams["SHOW_LOGIN"] != "N"))?></a><?php 
						?></td><?php 
					?></tr><?php 
					?></table><?php 
				?></td><?php 
			?></tr><?php 
		}
		?></table>
		<br><?php 
	}
	else
	{
		?><?= GetMessage("GD_SONET_GROUP_MODS_NO_MODS") ?>
		<br><br><?php 
	}
	?></td>
</tr>
</table>