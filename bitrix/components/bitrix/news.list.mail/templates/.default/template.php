<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
//$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;

if(empty($arResult['ITEMS']))
{
	return;
}

?>


<table width="" cellpadding="0" cellspacing="0">
	<tbody>
<?php foreach($arResult["ITEMS"] as $item):

	$showLink = false;
	if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($item["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"]))
	{
		$showLink = true;
	}

	$textBlockWidth = "";
	?>

	<tr><td colspan="5" height="25"></td></tr>

	<tr>
		<td colspan="5">
			<?php if($showLink):?>
				<a href="<?=$item['DETAIL_PAGE_URL']?>" style="font-size: 14px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #000; text-decoration: none;">
			<?php endif;?>
			<?php if($arParams["DISPLAY_DATE"]!="N" && $item["DISPLAY_ACTIVE_FROM"]):?>
				<?=$item["DISPLAY_ACTIVE_FROM"]?>
			<?php endif?>
			<?php if($arParams["DISPLAY_NAME"]!="N" && $item["NAME"]):?>
				<b><?=$item['NAME']?></b>
			<?php endif?>
			<?php if($showLink):?>
				</a>
			<?php endif;?>

		</td>
	</tr>

	<tr><td colspan="5" height="15px;"></td></tr>

	<tr>
		<td>
			<?php if($item['PREVIEW_PICTURE'] && $item['PREVIEW_PICTURE']['SRC']):
				$textBlockWidth = 'width="400"';
				?>
			<table cellpadding="0" cellspacing="0" valign="top" style="display: inline-block">
				<tbody>
				<tr>
					<td width="170">
						<table height="170" border="1" bordercolor="#ebebeb" cellpadding="0" cellspacing="0">
							<tbody>
							<tr>
								<td width="168" height="168">
									<?php if($showLink):?>
										<a href="<?=$item['DETAIL_PAGE_URL']?>">
									<?php endif;?>
									<img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" style="display: block; margin: auto;">
									<?php if($showLink):?>
										</a>
									<?php endif;?>
								</td>
							</tr>
							</tbody>
						</table>
					</td>
					<td width="15"></td>
				</tr>
				<tr><td height="15"></td></tr>
				</tbody>
			</table>
			<?php  endif ?>


			<table style="display: inline-block" valign="top">
				<tbody>
				<tr>
					<td <?=$textBlockWidth?>>
						<?php if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $item["PREVIEW_TEXT"]):?>
							<p style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #2f2f2f; font-size: 13px; margin:0 0 0px;">
							<?php echo $item["PREVIEW_TEXT"];?>
							</p>
						<?php endif;?>
					</td>
				</tr>
				<tr>
					<td height="15"></td>
				</tr>
				</tbody>
			</table>

			<?php  if(!empty($item['FIELDS'])): ?>
				<table cellpadding="0" cellspacing="0" style="display: inline-block" valign="top">
					<tbody>
					<tr>
						<td>
							<p style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #2f2f2f; font-size: 12px; margin:0 0 5px;">
							<?php foreach($item["FIELDS"] as $code=>$value):?>
								<?php if($code == 'PREVIEW_PICTURE' || $code == 'DETAIL_PICTURE'):?>
									<table height="170" border="1" bordercolor="#ebebeb" cellpadding="0" cellspacing="0">
										<tbody>
										<tr>
											<td width="168" height="168">
												<?php if($showLink):?>
													<a href="<?=$item['DETAIL_PAGE_URL']?>">
												<?php endif;?>

												<img src="<?=$value['SRC']?>" style="display: block; margin: auto;">

												<?php if($showLink):?>
													</a>
												<?php endif;?>
											</td>
										</tr>
										</tbody>
									</table>
								<?php else:?>
									<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
								<?php endif;?>
								<br />
							<?php  endforeach ?>
							</p>
						</td>
						<td width="45"></td>
					</tr>
					<tr>
						<td height="15"></td>
					</tr>
					</tbody>
				</table>
			<?php  endif ?>

			<?php  if(!empty($item['DISPLAY_PROPERTIES'])): ?>
				<table cellpadding="0" cellspacing="0" style="display: inline-block" valign="top">
					<tbody>
					<tr>
						<td>
							<p style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #2f2f2f; font-size: 12px; margin:0 0 5px;">
								<?php foreach($item["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
									<?=$arProperty["NAME"]?>:&nbsp;
									<?php if(is_array($arProperty["DISPLAY_VALUE"])):?>
										<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
									<?php else:?>
										<?=$arProperty["DISPLAY_VALUE"];?>
									<?php endif?>
									<br />
								<?php endforeach;?>
							</p>
						</td>
						<td width="45"></td>
					</tr>
					<tr>
						<td height="15"></td>
					</tr>
					</tbody>
				</table>
			<?php  endif ?>

			<?php if($showLink):?>
			<br />
			<table valign="top" align="left">
				<tbody>
				<tr>
					<td width="112" height="22" bgcolor="#5d9728" valign="middle" align="middle">
						<a href="<?=$item['DETAIL_PAGE_URL']?>" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #fff; font-weight: bold; font-size: 12px; display: block; line-height: 22px; text-decoration: none;">
							<?=Loc::getMessage('T_IBLOCK_TEMPLATE_BTN_MORE');?>
						</a>
					</td>
				</tr>
				<tr>
					<td height="15"></td>
				</tr>
				</tbody>
			</table>
			<?php  endif ?>

		</td>
	</tr>



<?php endforeach;?>
	</tbody>
</table>