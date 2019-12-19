<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="bx-sonet-layout-include">
	<?php if (count($arResult["Groups"]) > 0):?>
		<?php foreach ($arResult["Groups"] as $arGroup):?>
			<div class="bx-sonet-group-info">
				<div class="bx-sonet-group-info-inner">
					<?php if ($arParams["DISPLAY_IMAGE"] != "N"):?>
						<div class="bx-sonet-group-image"><?= $arGroup["IMAGE_IMG"]; ?></div>
					<?php endif;?>
					<div class="bx-sonet-group-date intranet-date<?php if ($arParams["DISPLAY_IMAGE"] == "N"):?> no-image<?php endif;?>"><?= $arGroup["FULL_DATE_CHANGE_FORMATED"] ?></div>
					<div class="bx-user-name<?php if ($arParams["DISPLAY_IMAGE"] == "N"):?> no-image<?php endif;?>"><a href="<?= $arGroup["GROUP_URL"] ?>"><?= $arGroup["NAME"] ?></a></div>
					<?php if ($arParams["DISPLAY_DESCRIPTION"] != "N" && StrLen($arGroup["DESCRIPTION"]) > 0):?>
						<div class="bx-user-post<?php if ($arParams["DISPLAY_IMAGE"] == "N"):?> no-image<?php endif;?>"><?= $arGroup["DESCRIPTION"] ?></div>
					<?php endif;?>
					<?php if ($arParams["DISPLAY_NUMBER_OF_MEMBERS"] != "N" && IntVal($arGroup["NUMBER_OF_MEMBERS"]) > 0):?>
						<div class="bx-user-post<?php if ($arParams["DISPLAY_IMAGE"] == "N"):?> no-image<?php endif;?>"><?= GetMessage("SONET_C68_T_MEMBERS") ?>: <?= $arGroup["NUMBER_OF_MEMBERS"] ?></div>
					<?php endif;?>
					<?php if ($arParams["DISPLAY_SUBJECT"] != "N" && StrLen($arGroup["SUBJECT_NAME"]) > 0):?>
						<div class="bx-user-post<?php if ($arParams["DISPLAY_IMAGE"] == "N"):?> no-image<?php endif;?>"><?= GetMessage("SONET_C68_T_SUBJ") ?>: <?= $arGroup["SUBJECT_NAME"] ?></div>
					<?php endif;?>
					<div class="bx-users-delimiter"></div>
				</div>
			</div>
		<?php endforeach;?>
		<br /><a href="<?= $arResult["Urls"]["GroupSearch"] ?>"><?= GetMessage("SONET_C68_T_ALL") ?></a> <a href="<?= $arResult["Urls"]["GroupSearch"] ?>" class="bx-sonet-group-arrows"></a>
	<?php else:?>
		<?= GetMessage("SONET_C68_T_EMPTY") ?>
	<?php endif;?>
</div>