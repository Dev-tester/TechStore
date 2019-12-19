<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\UI;

UI\Extension::load("ui.tooltip");

if(!CModule::IncludeModule("socialnetwork"))
{
	return false;
}

$sRatingTemplate = ($arParams["RATING_TYPE"] == "") ? COption::GetOptionString("main", "rating_vote_type", "standart") : $arParams["RATING_TYPE"];

?><h4 class="bx-sonet-user-desc-username"><?=htmlspecialcharsback($arGadgetParams['USER_NAME'])?></h4><?php 

if ($arGadgetParams['CAN_VIEW_PROFILE'])
{

	?><table width="100%" cellspacing="2" cellpadding="3"><?php 
	if ($arGadgetParams['FIELDS_MAIN_SHOW'] == "Y")
	{
		foreach ($arGadgetParams['FIELDS_MAIN_DATA'] as $fieldName => $arUserField)
		{
			if (StrLen($arUserField["VALUE"]) > 0)
			{
				?><tr valign="top">
					<td width="40%"><?= $arUserField["NAME"] ?>:</td>
					<td width="60%"><?php if (StrLen($arUserField["SEARCH"]) > 0):?><a href="<?= $arUserField["SEARCH"] ?>"><?php endif;?><?= $arUserField["VALUE"] ?><?php if (StrLen($arUserField["SEARCH"]) > 0):?></a><?php endif;?></td>
				</tr><?php 
			}
		}
	}

	if ($arGadgetParams['PROPERTIES_MAIN_SHOW'] == "Y")
	{
		foreach ($arGadgetParams['PROPERTIES_MAIN_DATA'] as $fieldName => $arUserField)
		{
			if (is_array($arUserField["VALUE"]) && count($arUserField["VALUE"]) > 0 || !is_array($arUserField["VALUE"]) && StrLen($arUserField["VALUE"]) > 0)
			{
				?><tr valign="top">
					<td width="40%"><?=$arUserField["EDIT_FORM_LABEL"]?>:</td>
					<td width="60%"><?php 
					$bInChain = ($fieldName == "UF_DEPARTMENT" ? "Y" : "N");
					$GLOBALS["APPLICATION"]->IncludeComponent(
						"bitrix:system.field.view", 
						$arUserField["USER_TYPE"]["USER_TYPE_ID"], 
						array("arUserField" => $arUserField, "inChain" => $bInChain),
						null,
						array("HIDE_ICONS"=>"Y")
					);
					?></td>
				</tr><?php 
			}
		}
	}

	if (
		is_array($arGadgetParams['MANAGERS']) 
		&& !empty($arGadgetParams['MANAGERS'])
	)
	{
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
		?><tr valign="top">
			<td width="40%"><?php echo GetMessage("GD_SONET_USER_DESC_MANAGER")?></td>
			<td width="60%"><?php 
				foreach($arGadgetParams['MANAGERS'] as $manager)
				{
					?><div style="margin-bottom:4px;"><?php 
						?><a href="<?=CComponentEngine::MakePathFromTemplate($arParams["~PATH_TO_USER"], array("user_id" => $manager["ID"], "USER_ID" => $manager["ID"], "ID" => $manager["ID"]))?>" bx-tooltip-user-id="<?=$manager["ID"]?>"><?=CUser::FormatName($arParams["NAME_TEMPLATE"], $manager, ($arParams["SHOW_LOGIN"] != "N"))?></a><?php 
					?></div><?php 
				}
			?></td>
		</tr><?php 
	}

	if(is_array($arGadgetParams['DEPARTMENTS']) && !empty($arGadgetParams['DEPARTMENTS']))
	{
		?><tr valign="top">
			<td width="40%"><?php echo GetMessage("GD_SONET_USER_DESC_EMPLOYEES")?></td>
			<td width="60%"><?php 
				foreach($arGadgetParams['DEPARTMENTS'] as $dep)
				{
					?><a href="<?=$dep['URL']?>"><?=$dep['NAME']?></a><?php if($dep['EMPLOYEE_COUNT'] > 0):?><span title="<?php echo GetMessage("GD_SONET_USER_DESC_EMPLOYEES_NUM")?>"> (<?=$dep['EMPLOYEE_COUNT']?>)<span><?php endif?><br><?php 
				}
			?></td>
		</tr><?php 
	}

	if ($sRatingTemplate == "standart")
	{
		if (
			array_key_exists("RATING_MULTIPLE", $arGadgetParams)
			&& is_array($arGadgetParams["RATING_MULTIPLE"])
			&& count($arGadgetParams["RATING_MULTIPLE"]) > 0
		)
		{
			foreach($arGadgetParams["RATING_MULTIPLE"] as $arRating)
			{
				?><tr valign="top">
					<td width="40%"><?=$arRating["NAME"]?>:</td>
					<td width="60%"><?=$arRating["VALUE"]?></td>
				</tr><?php 
			}
			?><tr valign="top">
				<td width="40%"><?=GetMessage("GD_SONET_USER_DESC_VOTE")?>:</td>
				<td width="60%">
					<?php $APPLICATION->IncludeComponent("bitrix:rating.vote","",
						array(
							"ENTITY_TYPE_ID" => "USER",
							"ENTITY_ID" => $arParams["USER_ID"],
							"OWNER_ID" => $arParams["USER_ID"]
						),
						null,
						array("HIDE_ICONS" => "Y")
					);?>
				</td>
			</tr>
			<?php 
		}
		elseif (strlen($arGadgetParams['RATING_NAME']) > 0)
		{
			?><tr valign="top">
				<td width="40%"><?=$arGadgetParams['RATING_NAME']?>:</td>
				<td width="60%"><?=$arGadgetParams['RATING_VALUE']?></td>
			</tr>
			<tr valign="top">
				<td width="40%"><?=GetMessage("GD_SONET_USER_DESC_VOTE")?>:</td>
				<td width="60%"><?php 
					?><?php $APPLICATION->IncludeComponent("bitrix:rating.vote","",
						array(
							"ENTITY_TYPE_ID" => "USER",
							"ENTITY_ID" => $arParams["USER_ID"],
							"OWNER_ID" => $arParams["USER_ID"]
						),
						null,
						array("HIDE_ICONS" => "Y")
					);?><?php 
				?></td>
			</tr><?php 
		}
	}

	?></table><?php 

	if (!empty($arGadgetParams["EMAIL_FORWARD_TO"]))
	{
		?><h4 class="bx-sonet-user-desc-contact"><?= GetMessage("GD_SONET_USER_DESC_FORWARD_TO") ?></h4>
		<table width="100%" cellspacing="2" cellpadding="3"><?php 
			if (!empty($arGadgetParams["EMAIL_FORWARD_TO"]['BLOG_POST']))
			{
				?><tr valign="top">
					<td width="40%" class="user-profile-mail-link"><?=GetMessage("GD_SONET_USER_DESC_FORWARD_TO_BLOG_POST")?>:</td>
					<td width="60%" class="user-profile-block-right user-profile-mail-link">
						<div class="user-profile-mail-link-block">
							<span class="user-profile-short-link" data-link=""><?=$arGadgetParams["EMAIL_FORWARD_TO"]['BLOG_POST']?></span>
							<input type="text" class="user-profile-link-input" data-input="" value="<?=$arGadgetParams["EMAIL_FORWARD_TO"]['BLOG_POST']?>">
							<a href="javascript:void(0);" onclick="socnetUserDescObj.showLink(this);" class="user-profile-link user-profile-show-link-btn"><?=GetMessage("GD_SONET_USER_DESC_FORWARD_TO_SHOW")?></a>
						</div>
					</td>
				</tr><?php 
			}
			if (!empty($arGadgetParams["EMAIL_FORWARD_TO"]['TASKS_TASK']))
			{
				?><tr valign="top">
				<td width="40%" class="user-profile-mail-link"><?=GetMessage("GD_SONET_USER_DESC_FORWARD_TO_TASK")?>:</td>
				<td width="60%" class="user-profile-block-right user-profile-mail-link">
					<div class="user-profile-mail-link-block">
						<span class="user-profile-short-link" data-link=""><?=$arGadgetParams["EMAIL_FORWARD_TO"]['TASKS_TASK']?></span>
						<input type="text" class="user-profile-link-input" data-input="" value="<?=$arGadgetParams["EMAIL_FORWARD_TO"]['TASKS_TASK']?>">
						<a href="javascript:void(0);" onclick="socnetUserDescObj.showLink(this);" class="user-profile-link user-profile-show-link-btn"><?=GetMessage("GD_SONET_USER_DESC_FORWARD_TO_SHOW")?></a>
					</div>
				</td>
				</tr><?php 
			}
		?></table><?php 
	}

	?><h4 class="bx-sonet-user-desc-contact"><?= GetMessage("GD_SONET_USER_DESC_CONTACT_TITLE") ?></h4>
	<table width="100%" cellspacing="2" cellpadding="3"><?php 
	if ($arGadgetParams['CAN_VIEW_CONTACTS'])
	{
		$bContactsEmpty = true;
		if ($arGadgetParams['FIELDS_CONTACT_SHOW'] == "Y")
		{
			foreach ($arGadgetParams['FIELDS_CONTACT_DATA'] as $fieldName => $arUserField)
			{
				if (StrLen($arUserField["VALUE"]) > 0)
				{
					?><tr valign="top">
						<td width="40%"><?= $arUserField["NAME"] ?>:</td>
						<td width="60%"><?php if (StrLen($arUserField["SEARCH"]) > 0):?><a href="<?= $arUserField["SEARCH"] ?>"><?php endif;?><?= $arUserField["VALUE"] ?><?php if (StrLen($arUserField["SEARCH"]) > 0):?></a><?php endif;?></td>
					</tr><?php 
					$bContactsEmpty = false;
				}
			}
		}

		if ($arGadgetParams['PROPERTIES_CONTACT_SHOW'] == "Y")
		{
			foreach ($arGadgetParams['PROPERTIES_CONTACT_DATA'] as $fieldName => $arUserField)
			{
				if (is_array($arUserField["VALUE"]) && count($arUserField["VALUE"]) > 0 || !is_array($arUserField["VALUE"]) && StrLen($arUserField["VALUE"]) > 0)
				{
					?><tr valign="top">
						<td width="40%"><?=$arUserField["EDIT_FORM_LABEL"]?>:</td>
						<td width="60%"><?php 
						$bInChain = ($fieldName == "UF_DEPARTMENT" ? "Y" : "N");
						$GLOBALS["APPLICATION"]->IncludeComponent(
							"bitrix:system.field.view", 
							$arUserField["USER_TYPE"]["USER_TYPE_ID"], 
							array("arUserField" => $arUserField, "inChain" => $bInChain),
							null,
							array("HIDE_ICONS"=>"Y")
						);
						?></td>
					</tr><?php 
					$bContactsEmpty = false;
				}
			}
		}

		if ($bContactsEmpty)
		{
			?><tr>
				<td colspan="2"><?= GetMessage("GD_SONET_USER_DESC_CONTACT_UNSET") ?></td>
			</tr><?php 
		}
	}
	else
	{
		?><tr>
			<td colspan="2"><?= GetMessage("GD_SONET_USER_DESC_CONTACT_UNAVAIL") ?></td>
		</tr><?php 
	}
	?></table><?php 
	
	if ($arGadgetParams['FIELDS_PERSONAL_SHOW'] == "Y" || $arGadgetParams['PROPERTIES_PERSONAL_SHOW'] == "Y"):
		?><h4 class="bx-sonet-user-desc-personal"><?= GetMessage("GD_SONET_USER_DESC_PERSONAL_TITLE") ?></h4>
		<table width="100%" cellspacing="2" cellpadding="3"><?php 
		$bNoPersonalInfo = true;
		if ($arGadgetParams['FIELDS_PERSONAL_SHOW'] == "Y"):
			foreach ($arGadgetParams['FIELDS_PERSONAL_DATA'] as $fieldName => $arUserField):
				if (StrLen($arUserField["VALUE"]) > 0):
					?><tr valign="top">
						<td width="40%"><?= $arUserField["NAME"] ?>:</td>
						<td width="60%"><?php if (StrLen($arUserField["SEARCH"]) > 0):?><a href="<?= $arUserField["SEARCH"] ?>"><?php endif;?><?= $arUserField["VALUE"] ?><?php if (StrLen($arUserField["SEARCH"]) > 0):?></a><?php endif;?></td>
					</tr><?php 
					$bNoPersonalInfo = false;
				endif;
			endforeach;
		endif;
		if ($arGadgetParams['PROPERTIES_PERSONAL_SHOW'] == "Y"):
			foreach ($arGadgetParams['PROPERTIES_PERSONAL_DATA'] as $fieldName => $arUserField):
				if (is_array($arUserField["VALUE"]) && count($arUserField["VALUE"]) > 0 || !is_array($arUserField["VALUE"]) && StrLen($arUserField["VALUE"]) > 0):
					?><tr valign="top">
						<td width="40%"><?=$arUserField["EDIT_FORM_LABEL"]?>:</td>
						<td width="60%">
						<?php 
						$bInChain = ($fieldName == "UF_DEPARTMENT" ? "Y" : "N");
						$GLOBALS["APPLICATION"]->IncludeComponent(
							"bitrix:system.field.view", 
							$arUserField["USER_TYPE"]["USER_TYPE_ID"], 
							array("arUserField" => $arUserField, "inChain" => $bInChain),
							null,
							array("HIDE_ICONS"=>"Y")
						);
						?>
						</td>
					</tr><?php 
					$bNoPersonalInfo = false;
				endif;
			endforeach;
		endif;
		if ($bNoPersonalInfo):
			?><tr>
				<td colspan="2"><?= GetMessage("GD_SONET_USER_DESC_PERSONAL_UNAVAIL") ?></td>
			</tr><?php 
		endif;
		?></table><?php 
	endif;

	$arJSParams = array(
		"ajaxPath" => "/bitrix/gadgets/bitrix/sonet_user_desc/ajax.php"
	);

	if (
		$arGadgetParams["OTP"]["IS_ENABLED"] !== "N"
		&&
		(
			$arGadgetParams["OTP"]["IS_MANDATORY"]
				&& ($USER->GetID() == $arParams["USER_ID"] || $USER->CanDoOperation('security_edit_user_otp'))
			|| (!$arGadgetParams["OTP"]["IS_MANDATORY"]
				&&$arGadgetParams["OTP"]["IS_EXIST"]
				&& ($USER->GetID() == $arParams["USER_ID"] || $USER->CanDoOperation('security_edit_user_otp')))
		)
	)
	{
	?>
		<h4 class="bx-sonet-user-desc-personal"><?= GetMessage("GD_SONET_USER_DESC_SECURITY_TITLE") ?></h4>
		<table width="100%" cellspacing="2" cellpadding="3">
			<tr>
				<td class="user-profile-nowrap" style="width: 40%"><?=GetMessage("GD_SONET_USER_DESC_OTP_AUTH")?>:</td>
				<td style="width: 60%">
					<?php 
					if ($arGadgetParams["OTP"]["IS_ACTIVE"])
					{
						?>
						<span><?=GetMessage("GD_SONET_USER_DESC_OTP_ACTIVE")?></span>

						<?php if (
						!$arGadgetParams["OTP"]["IS_MANDATORY"] && ($USER->GetID() == $arParams["USER_ID"] || $USER->CanDoOperation('security_edit_user_otp'))
						|| $arGadgetParams["OTP"]["IS_MANDATORY"] && $USER->CanDoOperation('security_edit_user_otp')
						):?>
							<a href="javascript:void(0)" onclick="socnetUserDescObj.showOtpDaysPopup(this, '<?=CUtil::JSEscape($arParams["USER_ID"])?>', 'activate')"><?=GetMessage("GD_SONET_USER_DESC_OTP_DEACTIVATE")?></a>
						<?php endif?>

						<?php if ($USER->GetID() == $arParams["USER_ID"]):?>
							<a href="<?=$arParams["G_SONET_USER_LINKS_URL_SECURITY"]?>"><?=GetMessage("GD_SONET_USER_DESC_OTP_CHANGE_PHONE")?></a>
						<?php endif?>
						<?php 
					}
					elseif (!$arGadgetParams["OTP"]["IS_ACTIVE"] && $arGadgetParams["OTP"]["IS_MANDATORY"])
					{
						?>
						<span><?=GetMessage("GD_SONET_USER_DESC_OTP_NOT_ACTIVE")?></span>

						<?php if ($USER->GetID() == $arParams["USER_ID"]):?>
							<?php if ($arGadgetParams["OTP"]["IS_EXIST"]):?>
								<a href="javascript:void(0)" onclick="socnetUserDescObj.activateUserOtp('<?=CUtil::JSEscape($arParams["USER_ID"])?>')"><?=GetMessage("GD_SONET_USER_DESC_OTP_ACTIVATE")?></a>
								<a href="<?=$arParams["G_SONET_USER_LINKS_URL_SECURITY"]?>"><?=GetMessage("GD_SONET_USER_DESC_OTP_CHANGE_PHONE")?></a>
							<?php else:?>
								<a href="<?=$arParams["G_SONET_USER_LINKS_URL_SECURITY"]?>"><?=GetMessage("GD_SONET_USER_DESC_OTP_ACTIVATE")?></a>
							<?php endif?>
						<?php elseif ($USER->CanDoOperation('security_edit_user_otp')):?>
							<?php if ($arGadgetParams["OTP"]["IS_EXIST"]):?>
								<a href="javascript:void(0)" onclick="socnetUserDescObj.activateUserOtp('<?=CUtil::JSEscape($arParams["USER_ID"])?>')"><?=GetMessage("GD_SONET_USER_DESC_OTP_ACTIVATE")?></a>
							<?php else:?>
								<a href="javascript:void(0)" onclick="socnetUserDescObj.showOtpDaysPopup(this, '<?=CUtil::JSEscape($arParams["USER_ID"])?>', 'defer')">
									<?=GetMessage("GD_SONET_USER_DESC_OTP_PROROGUE")?>
								</a>
							<?php endif?>
						<?php endif?>
							<?php if ($arGadgetParams["OTP"]["NUM_LEFT_DAYS"]):?>
							<span><?=GetMessage("GD_SONET_USER_DESC_OTP_LEFT_DAYS", array("#NUM#" => "<strong>".$arGadgetParams["OTP"]["NUM_LEFT_DAYS"]."</strong>"))?></span>
						<?php endif?>
					<?php 
					}
					elseif (
						!$arGadgetParams["OTP"]["IS_ACTIVE"]
						&& $arGadgetParams["OTP"]["IS_EXIST"]
						&& !$arGadgetParams["OTP"]["IS_MANDATORY"]
						&& $USER->GetID() == $arParams["USER_ID"]
					)
					{
						?>
						<span><?=GetMessage("GD_SONET_USER_DESC_OTP_NOT_ACTIVE")?></span>
						<a href="javascript:void(0)" onclick="socnetUserDescObj.activateUserOtp('<?=CUtil::JSEscape($arParams["USER_ID"])?>')"><?=GetMessage("GD_SONET_USER_DESC_OTP_ACTIVATE")?></a>
						<a href="<?=$arParams["G_SONET_USER_LINKS_URL_SECURITY"]?>"><?=GetMessage("GD_SONET_USER_DESC_OTP_CHANGE_PHONE")?></a>
					<?php 
					}
					?>
				</td>
			</tr>
			<?php if ($USER->GetID() == $arParams["USER_ID"]):?>
				<tr>
					<td class="user-profile-nowrap" style="width: 40%"><?=GetMessage("GD_SONET_USER_DESC_OTP_PASSWORDS")?>:</td>
					<td style="width: 60%">
						<a href="<?=$arParams["G_SONET_USER_LINKS_URL_PASSWORDS"]?>"><?=GetMessage("GD_SONET_USER_DESC_OTP_PASSWORDS_SETUP")?></a>
					</td>
				</tr>
			<?php endif?>

			<?php if (!empty($arParams["G_SONET_USER_LINKS_URL_SYNCHRONIZE"]) && $USER->GetID() == $arParams["USER_ID"]):?>
                <tr>
                    <td class="user-profile-nowrap" style="width: 40%"><?=GetMessage("GD_SONET_USER_DESC_SYNCHRONIZE")?>:</td>
                    <td style="width: 60%">
                        <a href="<?=$arParams["G_SONET_USER_LINKS_URL_SYNCHRONIZE"]?>"><?=GetMessage("GD_SONET_USER_DESC_SYNCHRONIZE_SETUP")?></a>
                    </td>
                </tr>
			<?php endif?>

			<?php if ($USER->GetID() == $arParams["USER_ID"] && $arGadgetParams["OTP"]["IS_ACTIVE"] && $arGadgetParams["OTP"]["ARE_RECOVERY_CODES_ENABLED"]):?>
			<tr>
				<td class="user-profile-nowrap" style="width: 40%"><?=GetMessage("GD_SONET_USER_DESC_OTP_CODES")?>:</td>
				<td style="width: 60%">
					<a href="<?=$arParams["G_SONET_USER_LINKS_URL_CODES"]?>"><?=GetMessage("GD_SONET_USER_DESC_OTP_CODES_SHOW")?></a>
				</td>
			</tr>
			<?php endif?>
		</table>

		<?php 
		$arDays = array();
		for($i=1; $i<=10; $i++)
		{
			$arDays[$i] = FormatDate("ddiff", time()-60*60*24*$i);
		}
		$arDays[0] = GetMessage("GD_SONET_USER_DESC_OTP_NO_DAYS");

		$arJSParams["otpDays"] = $arDays;
	}
	?>
	<script>
		BX.namespace("BX.Socialnetwork.Gadget");
		BX.Socialnetwork.Gadget.UserDesc = (function()
		{
			var UserDesc = function(arParams)
			{
				this.ajaxPath = "";
				this.otpDays = {};

				if (typeof arParams === "object")
				{
					this.ajaxPath = arParams.ajaxPath;
					this.otpDays = arParams.otpDays;
				}
			};

			UserDesc.prototype.deactivateUserOtp = function(userId, numDays)
			{
				if (!parseInt(userId))
					return false;

				BX.ajax({
					method: 'POST',
					dataType: 'json',
					url: this.ajaxPath,
					data:
					{
						userId: userId,
						sessid: BX.bitrix_sessid(),
						numDays: numDays,
						action: "deactivate"
					},
					onsuccess: function(json)
					{
						if (json.error)
						{

						}
						else
						{
							location.reload();
						}
					}
				});
			};

			UserDesc.prototype.deferUserOtp = function(userId, numDays)
			{
				if (!parseInt(userId))
					return false;

				BX.ajax({
					method: 'POST',
					dataType: 'json',
					url: this.ajaxPath,
					data:
					{
						userId: userId,
						sessid: BX.bitrix_sessid(),
						numDays: numDays,
						action: "defer"
					},
					onsuccess: function(json)
					{
						if (json.error)
						{

						}
						else
						{
							location.reload();
						}
					}
				});
			};

			UserDesc.prototype.activateUserOtp = function(userId)
			{
				if (!parseInt(userId))
					return false;

				BX.ajax({
					method: 'POST',
					dataType: 'json',
					url: this.ajaxPath,
					data:
					{
						userId: userId,
						sessid: BX.bitrix_sessid(),
						action: "activate"
					},
					onsuccess: function(json)
					{
						if (json.error)
						{

						}
						else
						{
							location.reload();
						}
					}
				});
			};

			UserDesc.prototype.showOtpDaysPopup = function(bind, userId, handler)
			{
				if (!parseInt(userId))
					return false;

				handler = (handler == "defer") ? "defer" : "activate";
				var self = this;

				var daysObj = [];
				for (var i in this.otpDays)
				{
					daysObj.push({
						text: this.otpDays[i],
						numDays: i,
						onclick: function(event, item)
						{
							this.popupWindow.close();
							if (handler == "activate")
								self.deactivateUserOtp(userId, item.numDays);
							else
								self.deferUserOtp(userId, item.numDays);
						}
					});
				}

				BX.PopupMenu.show('securityOtpDaysPopup', bind, daysObj,
					{   offsetTop:10,
						offsetLeft:0
					}
				);
			};

			UserDesc.prototype.showLink = function(btn)
			{
				var wrapper = btn.parentNode;
				var input = wrapper.querySelector('[data-input]');
				var link = wrapper.querySelector('[data-link]');
				var inpWidth, linkWidth;

				input.style.width = 'auto';
				BX.addClass(wrapper, 'user-profile-show-input');
				inpWidth = input.offsetWidth;
				linkWidth = link.offsetWidth;
				btn.style.display = 'none';

				setTimeout(function()
				{
					link.style.display = 'none';
					input.style.width = linkWidth + 'px';
				}, 50);

				setTimeout(function()
				{
					input.style.opacity = 1;
					input.style.width = inpWidth + 'px';
				}, 100);

				BX.bind(input, 'transitionend', function()
				{
					input.select();
				})
			}

			return UserDesc;
		})();

		var socnetUserDescObj = new BX.Socialnetwork.Gadget.UserDesc(<?=CUtil::PhpToJSObject($arJSParams)?>);
	</script>
	<?php 
}
?>