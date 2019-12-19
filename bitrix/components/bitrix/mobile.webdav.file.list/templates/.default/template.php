<?php if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$APPLICATION->SetPageProperty("BodyClass","file-card-page");
?>
<div class="file-card-wrap">
	<div class="file-card-name"><span class="file-card-name-icon" style="background-image:url(<?php  echo $arResult["IMAGE"]; ?>)"></span><?php  echo htmlspecialcharsbx($arResult["NAME"]); ?></div>
	<div class="file-card-block">
		<div class="file-card-description">
			<?php  echo htmlspecialcharsbx($arResult["DESCRIPTION"]); ?>
		</div>
		<div class="file-card-description-row">
			<span class="file-card-description-left"><?php  echo GetMessage("WD_MOBILE_SIZE"); ?></span><span class="file-card-description-right"><?php   echo CFile::FormatSize(intval($arResult["FILE_SIZE"])); ?></span>
		</div>
		<div class="file-card-description-row">
			<span class="file-card-description-left"><?php  echo GetMessage("WD_MOBILE_CREATE"); ?></span><span class="file-card-description-right"><?php   echo $arResult["DATE_CREATE"]; ?></span>
		</div>
		<div class="file-card-description-row">
			<span class="file-card-description-left"><?php  echo GetMessage("WD_MOBILE_MODIFIED"); ?></span><span class="file-card-description-right"><?php   echo $arResult["DATE_MODIFIED"]; ?></span>
		</div>
	</div>
	<div class="file-card-review-btn" onclick="app.openDocument({'url' : '<?php  echo $arResult["URL"]; ?>'});" ><?php  echo GetMessage("WD_MOBILE_VIEW_FILE"); ?></div>
</div>