<?php 
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UI;

UI\Extension::load("ui.tooltip");

global $APPLICATION;
$APPLICATION->SetAdditionalCSS('/bitrix/js/crm/css/crm-preview.css');
?>

<div class="crm-preview">
	<div class="crm-preview-header">
		<span class="crm-preview-header-icon crm-preview-header-icon-contact"></span>
		<?php  if($arResult['HEAD_IMAGE_URL'] !== ''): ?>
			<span class="crm-preview-header-img">
					<img alt="" src="<?=htmlspecialcharsbx($arResult['HEAD_IMAGE_URL'])?>" />
				</span>
		<?php  endif; ?>
		<span class="crm-preview-header-title">
			<?=GetMessage("CRM_TITLE_CONTACT")?>:
			<a href="<?=htmlspecialcharsbx($arParams['URL'])?>" target="_blank"><?=htmlspecialcharsbx($arResult['FULL_NAME'])?></a>
		</span>
	</div>
	<table class="crm-preview-info">
		<tr>
			<td><?= GetMessage('CRM_CONTACT_RESPONSIBLE')?>: </td>
			<td>
				<a id="a_<?=htmlspecialcharsbx($arResult['ASSIGNED_BY_UNIQID'])?>" href="<?=htmlspecialcharsbx($arResult["ASSIGNED_BY_PROFILE"])?>" bx-tooltip-user-id="<?=htmlspecialcharsbx($arResult["ASSIGNED_BY_ID"])?>">
					<?=htmlspecialcharsbx($arResult['ASSIGNED_BY_FORMATTED_NAME'])?>
				</a>
			</td>
		</tr>
		<?php  foreach($arResult['CONTACT_INFO'] as $contactInfoType => $contactInfoValue): ?>
			<tr>
				<td><?= GetMessage('CRM_CONTACT_INFO_'.$contactInfoType)?>: </td>
				<td>
					<?php 
					$contactInfoValue = htmlspecialcharsbx($contactInfoValue);
					switch($contactInfoType)
					{
						case 'EMAIL':
							?><a href="mailto:<?=$contactInfoValue?>" title="<?=$contactInfoValue?>"><?=$contactInfoValue?></a><?php 
							break;
						case 'PHONE':
							?><a href="callto://<?=$contactInfoValue?>" onclick="if(typeof(BXIM) !== 'undefined') { BXIM.phoneTo('8 4012 531249'); return BX.PreventDefault(event); }" title="<?=$contactInfoValue?>"><?=$contactInfoValue?></a><?php 
							break;
						case 'WEB':
							?><a href="http://<?=$contactInfoValue?>"><?=$contactInfoValue?></a><?php 
							break;
						default:
							echo $contactInfoValue;
					}
					?>
				</td>
			</tr>
		<?php  endforeach ?>
	</table>
</div>