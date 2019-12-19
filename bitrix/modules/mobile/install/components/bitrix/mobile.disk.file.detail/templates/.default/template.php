<?php if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$APPLICATION->SetPageProperty("BodyClass","file-card-page");

$openUrlParams = ['url' => $arResult["URL"], 'filename' => $arResult["NAME"]];

?>
<div class="file-card-wrap">
	<div class="file-card-name"><span class="file-card-name-icon" style="background-image:url(<?php  echo $arResult["ICON"]; ?>)"></span><?php  echo htmlspecialcharsbx($arResult["NAME"]); ?></div>
	<div class="file-card-block">
		<div class="file-card-description">
			<?php  echo htmlspecialcharsbx($arResult["DESCRIPTION"]); ?>
		</div>
		<div class="file-card-description-row">
			<span class="file-card-description-left"><?php  echo GetMessage("DISK_MOBILE_SIZE"); ?></span><span class="file-card-description-right"><?php   echo CFile::FormatSize(intval($arResult["SIZE"])); ?></span>
		</div>
		<div class="file-card-description-row">
			<span class="file-card-description-left"><?php  echo GetMessage("DISK_MOBILE_CREATE"); ?></span><span class="file-card-description-right"><?php   echo $arResult["CREATE_TIME"]; ?></span>
		</div>
		<div class="file-card-description-row">
			<span class="file-card-description-left"><?php  echo GetMessage("DISK_MOBILE_MODIFIED"); ?></span><span class="file-card-description-right"><?php   echo $arResult["CREATE_TIME"]; ?></span>
		</div>
	</div>
	<div class="file-card-review-btn" onclick="BXMobileApp.UI.Document.open(<?=htmlspecialcharsbx(\Bitrix\Main\Web\Json::encode($openUrlParams))?>);" ><?php  echo GetMessage("DISK_MOBILE_VIEW_FILE"); ?></div>
</div>