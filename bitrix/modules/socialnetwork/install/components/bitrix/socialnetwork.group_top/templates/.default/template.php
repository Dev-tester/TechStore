<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (count($arResult["Groups"]) > 0):?>
	<table width="100%" cellspacing="0" cellpadding="5">
	<?php foreach ($arResult["Groups"] as $arGroup):?>
		<tr>
			<td valign="top">
				<?php if ($arParams["DISPLAY_IMAGE"] != "N"):?>
					<?= $arGroup["IMAGE_IMG"] ?>
				<?php endif;?>
			</td>
			<td valign="top">
				<span class="sonet-date-time"><?= $arGroup["FULL_DATE_CHANGE_FORMATED"] ?></span>
				<a href="<?= $arGroup["GROUP_URL"] ?>"><?= $arGroup["NAME"] ?></a><br />

				<?php if ($arParams["DISPLAY_DESCRIPTION"] != "N" && StrLen($arGroup["DESCRIPTION"]) > 0):?>
					<?= $arGroup["DESCRIPTION"] ?><br />
				<?php endif;?>

				<?php if ($arParams["DISPLAY_NUMBER_OF_MEMBERS"] != "N" && IntVal($arGroup["NUMBER_OF_MEMBERS"]) > 0):?>
					<?= GetMessage("SONET_C68_T_MEMBERS") ?>: <?= $arGroup["NUMBER_OF_MEMBERS"] ?><br />
				<?php endif;?>

				<?php if ($arParams["DISPLAY_SUBJECT"] != "N" && StrLen($arGroup["SUBJECT_NAME"]) > 0):?>
					<?= GetMessage("SONET_C68_T_SUBJ") ?>: <?= $arGroup["SUBJECT_NAME"] ?><br />
				<?php endif;?>
			</td>
		</tr>
	<?php endforeach;?>
	</table>
	<br /><a href="<?= $arResult["Urls"]["GroupSearch"] ?>"><?= GetMessage("SONET_C68_T_ALL") ?></a>
<?php else:?>
	<?= GetMessage("SONET_C68_T_EMPTY") ?>
<?php endif;?>