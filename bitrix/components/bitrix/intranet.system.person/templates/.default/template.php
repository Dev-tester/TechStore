<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\UI;

UI\Extension::load("ui.tooltip");

$arUser = is_array($arParams['~USER']) ? $arParams['~USER'] : array();
$name = CUser::FormatName($arParams['NAME_TEMPLATE'], $arUser, $arResult["bUseLogin"]);

$arUserData = array();
if (is_array($arParams['USER_PROPERTY']))
{
	foreach ($arParams['USER_PROPERTY'] as $key)
	{
		if ($arUser[$key])
			$arUserData[$key] = $arUser[$key];
	}
}

if (!defined('INTRANET_ISP_MUL_INCLUDED')):
	$APPLICATION->IncludeComponent("bitrix:main.user.link",
		'',
		array(
			"AJAX_ONLY" => "Y",
			"PATH_TO_SONET_USER_PROFILE" => COption::GetOptionString('intranet', 'search_user_url', '/company/personal/user/#ID#/'),
			"PATH_TO_SONET_MESSAGES_CHAT" => $arParams["PM_URL"],
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

	define('INTRANET_ISP_MUL_INCLUDED', 1);
endif;
?>

<div class="bx-user-info-big">
	<div class="bx-user-info-inner-big">
	<?php 
	if ($arUser['SUBTITLE']):
		?>
		<div class="bx-user-subtitle<?php echo $arUser['SUBTITLE_FEATURED'] == 'Y' ? ' bx-user-subtitle-featured' : ''?>">
			<?php echo (!empty($arUser['PREVIEW_TEXT_TYPE']) && $arUser['PREVIEW_TEXT_TYPE'] == 'html' ? $arUser['SUBTITLE'] : htmlspecialcharsbx($arUser['SUBTITLE']))?>
		</div>
		<?php 
	endif;
	?>
	<div class="bx-user-controls">
	<?php 
	if ($USER->IsAuthorized() && (!isset($arUser['ACTIVE']) || $arUser['ACTIVE'] == 'Y')):
		?>
		<div class="bx-user-control">
		<ul>
			<?php 
			if ($arResult['CAN_MESSAGE'] && $arParams['PM_URL']):
				?>
				<li class="bx-icon bx-icon-message"><a href="<?php echo ($url = str_replace('#USER_ID#', $arUser['ID'], $arParams['PM_URL']))?>" onclick="if (typeof(BX) != 'undefined' && BX.IM) { BXIM.openMessenger(<?=$arUser['ID']?>); return false; } else { window.open('<?=$url?>', '', 'status=no,scrollbars=yes,resizable=yes,width=700,height=550,top='+Math.floor((screen.height - 550)/2-14)+',left='+Math.floor((screen.width - 700)/2-5)); return false; }"><?php echo GetMessage('INTR_ISP_PM')?></a></li>
				<?php 
			endif;
			?>
			<?php 
			if ($arResult['CAN_VIDEO_CALL'] && $arParams['PATH_TO_VIDEO_CALL']):
				?>
				<li class="bx-icon bx-icon-video"><a href="<?php echo $arResult["Urls"]["VideoCall"]?>" onclick="window.open('<?php echo $arResult["Urls"]["VideoCall"] ?>', '', 'status=no,scrollbars=yes,resizable=yes,width=1000,height=600,top='+Math.floor((screen.height - 600)/2-14)+',left='+Math.floor((screen.width - 1000)/2-5)); return false;"><?php echo GetMessage('INTR_ISP_VIDEO_CALL')?></a></li>
				<?php 
			endif;
			if ($arResult['CAN_EDIT_USER'] == false && $arResult['CAN_EDIT_USER_SELF'] == true) :
				?>
				<li class="bx-icon bx-icon-edit"><a href="<?=CComponentEngine::MakePathFromTemplate(
					$arParams["PATH_TO_USER_EDIT"],
					array(
						"user_id" => $arUser['ID']
					))?>"><?php echo GetMessage('INTR_ISP_EDIT_USER')?></a></li>
				<?php 
			elseif ($arResult['CAN_EDIT_USER']):
				?>
				<li class="bx-icon bx-icon-edit"><a href="javascript:<?php echo $APPLICATION->GetPopupLink(
						array(
							'URL' => '/bitrix/admin/user_edit.php?lang='.LANGUAGE_ID.'&bxpublic=Y&from_module=main&ID='.$arUser['ID'],
							"PARAMS"=>array("width"=>780, "height"=>500, "resize"=>false),
				))?>"><?php echo GetMessage('INTR_ISP_EDIT_USER')?></a></li>
				<?php 
			endif;
			?>
		</ul>
		</div>
		<?php 
	endif;
	if ($arUser['IS_ONLINE'] || $arUser['IS_BIRTHDAY'] || $arUser['IS_ABSENT'] || $arUser['IS_FEATURED']):
		?>
		<div class="bx-user-control">
		<ul>
			<?php if ($arUser['IS_ONLINE']):?><li class="bx-icon bx-icon-online"><?php echo GetMessage('INTR_ISP_IS_ONLINE')?></li><?php endif;?>
			<?php if ($arUser['IS_ABSENT']):?><li class="bx-icon bx-icon-away"><?php echo GetMessage('INTR_ISP_IS_ABSENT')?></li><?php endif;?>
			<?php if ($arUser['IS_BIRTHDAY']):?><li class="bx-icon bx-icon-birth"><?php echo GetMessage('INTR_ISP_IS_BIRTHDAY')?></li><?php endif;?>
			<?php if ($arUser['IS_FEATURED']):?><li class="bx-icon bx-icon-featured"><?php echo GetMessage('INTR_ISP_IS_FEATURED')?></li><?php endif;?>
		</ul>
		</div>
		<?php 
	endif;
	?></div><?php 
	if (
		is_array($arParams['USER_PROPERTY'])
		&& in_array('PERSONAL_PHOTO', $arParams['USER_PROPERTY'])
	)
	{
		?><div class="bx-user-image<?php  if (!$arUser['PERSONAL_PHOTO']) { ?> bx-user-image-default<?php  } ?>"><?php 
		if ($arResult['CAN_VIEW_PROFILE'])
		{
			?><a href="<?php echo $arUser['DETAIL_URL']?>"><?php 
		}
		if ($arUser['PERSONAL_PHOTO']) 
		{
			echo $arUser['PERSONAL_PHOTO']; 
		}
		if ($arResult['CAN_VIEW_PROFILE'])
		{
			?></a><?php  
		}
		?></div><?php 
	}
	?><div class="bx-user-text<?php  if (!is_array($arParams['USER_PROPERTY']) || !in_array('PERSONAL_PHOTO', $arParams['USER_PROPERTY'])) { ?> no-photo<?php  } ?>">
		<div class="bx-user-name">
			<a href="<?=$arUser['DETAIL_URL']?>" bx-tooltip-user-id="<?=$arUser["ID"]?>"><?=CUser::FormatName($arParams['NAME_TEMPLATE'], $arUser, $arParams["SHOW_LOGIN"] != 'N');?></a>
		</div>
		<div class="bx-user-post"><?php echo htmlspecialcharsbx($arUser['WORK_POSITION'])?></div>
		<div class="bx-user-properties">
		<?php  foreach ($arUserData as $key => $value)
		{
			if (in_array($key, array('PERSONAL_PHOTO')))
			{
				continue;
			}
			echo $arParams['USER_PROP'][$key] ? $arParams['USER_PROP'][$key] : GetMessage('ISL_'.$key); ?>:
			<?php  switch($key)
			{
				case 'EMAIL':
					echo '<a href="mailto:',urlencode($value),'">',htmlspecialcharsbx($value),'</a>';
					break;

				case 'PERSONAL_WWW':
					echo '<a href="http://',urlencode($value),'" target="_blank">',htmlspecialcharsbx($value),'</a>';
					break;

				case 'PERSONAL_PHONE':
				case 'WORK_PHONE':
				case 'PERSONAL_MOBILE':
				case 'UF_PHONE_INNER':
					$value_encoded = preg_replace('/[^\d\+]+/', '', $value);
					echo '<a href="callto:',$value_encoded,'">',htmlspecialcharsbx($value),'</a>';
					break;

				case 'PERSONAL_GENDER':
					echo $value == 'F' ? GetMessage('INTR_ISP_GENDER_F') : ($value == 'M' ? GetMessage('INTR_ISP_GENDER_M') : '');
					break;

				case 'PERSONAL_BIRTHDAY':
					echo FormatDateEx(
						$value,
						false,
						$arParams['DATE_FORMAT'.(($arParams['SHOW_YEAR'] == 'N' || $arParams['SHOW_YEAR'] == 'M' && $arUser['PERSONAL_GENDER'] == 'F') ? '_NO_YEAR' : '')]
					);

					break;

				case 'DATE_REGISTER':
					echo FormatDateEx(
						$value,
						false,
						$arParams['DATE_TIME_FORMAT']
					);

					break;

				case 'UF_DEPARTMENT':
					$bFirst = true;
					if (is_array($value) && count($value) > 0)
					{
						foreach ($value as $dept_id => $dept_name)
						{
							if (!$bFirst && $dept_name) echo ', ';
							else $bFirst = false;

							if (CModule::IncludeModule('extranet') && CExtranet::IsExtranetSite())
								echo htmlspecialcharsbx($dept_name);
							else
							{
								if (strlen(trim($arParams["PATH_TO_CONPANY_DEPARTMENT"])) > 0)
									echo '<a href="',CComponentEngine::MakePathFromTemplate($arParams["~PATH_TO_CONPANY_DEPARTMENT"], array("ID" => $dept_id)),'">',htmlspecialcharsbx($dept_name),'</a>';
								else
									echo '<a href="',$arParams['STRUCTURE_PAGE'].'?set_filter_',$arParams['STRUCTURE_FILTER'],'=Y&',$arParams['STRUCTURE_FILTER'],'_UF_DEPARTMENT=',$dept_id,'">',htmlspecialcharsbx($dept_name),'</a>';
							}

						}
					}
					break;

				default:
					if (substr($key, 0, 3) == 'UF_' && is_array($arResult['USER_PROP'][$key]))
					{
						$arResult['USER_PROP'][$key]['VALUE'] = $value;
						$APPLICATION->IncludeComponent(
							'bitrix:system.field.view',
							$arResult['USER_PROP'][$key]['USER_TYPE_ID'],
							array(
								'arUserField' => $arResult['USER_PROP'][$key],
							)
						);
					}
					else
						echo htmlspecialcharsbx($value);

					break;
			} ?>
			<br />
		<?php  } ?>
		</div>
	</div>
	<div class="bx-users-delimiter"></div>
	</div>
</div>
<?php 
if ($arParams['LIST_OBJECT'])
{
?>
<script><?php echo CUtil::JSEscape($arParams['LIST_OBJECT'])?>[<?php echo CUtil::JSEscape($arParams['LIST_OBJECT'])?>.length] = {ID:<?php echo $arUser['ID']?>,NAME:'<?php echo CUtil::JSEscape($name)?>',CURRENT:<?php echo $arUser['IS_HEAD'] ? 'true' : 'false'?>}</script>
<?php 
}
?>