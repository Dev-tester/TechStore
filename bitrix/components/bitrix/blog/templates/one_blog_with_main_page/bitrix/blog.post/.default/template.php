<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if(strlen($arResult["MESSAGE"])>0)
{
	?>
	<?=$arResult["MESSAGE"]?><br /><br />
	<?php 
}
if(strlen($arResult["ERROR_MESSAGE"])>0)
{
	?>
	<span class='errortext'><?=$arResult["ERROR_MESSAGE"]?></span><br /><br />
	<?php 
}
if(strlen($arResult["FATAL_MESSAGE"])>0)
{
	?>
	<span class='errortext'><?=$arResult["FATAL_MESSAGE"]?></span><br /><br />
	<?php 
}
elseif(strlen($arResult["NOTE_MESSAGE"])>0)
{
	?>
	<?=$arResult["NOTE_MESSAGE"]?><br /><br />
	<?php 
}
else
{
	if(!empty($arResult["Post"])>0)
	{
		?>
		<table class="blog-table-post">
		<tr>
			<th nowrap width="100%">
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="blog-table-post-table">
				<tr>
					<td align="left">
						<span class="blog-post-date"><b><?=$arResult["Post"]["DATE_PUBLISH_FORMATED"]?></b></span>&nbsp;
					</td>
					<td align="right" nowrap>
						<table width="0%" class="blog-table-post-table-author">
						<tr>
							<?php if(strLen($arResult["urlToEdit"])>0):?>
								<td align="right">
									<a href="<?=$arResult["urlToEdit"]?>" class="blog-post-edit"></a>
								</td>
							<?php endif;?>
							<?php if(strLen($arResult["urlToDelete"])>0):?>
								<td align="right">
									<a href="javascript:if(confirm('<?=GetMessage("BLOG_MES_DELETE_POST_CONFIRM")?>')) window.location='<?=$arResult["urlToDelete"]."&".bitrix_sessid_get()?>'" class="blog-post-delete"></a>
								</td>
							<?php endif;?>
						</tr>
						</table>
				</tr>
				</table>
			</th>
		</tr>
		<tr>
			<td>
				<?php 
				if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
				{
					?>
					<div class="blog-post-share" style="float: right;">
						<noindex>
						<?php 
						$APPLICATION->IncludeComponent("bitrix:main.share", "", array(
							"HANDLERS" => $arParams["SHARE_HANDLERS"],
							"PAGE_URL" => htmlspecialcharsback($arResult["urlToPost"]),
							"PAGE_TITLE" => $arResult["Post"]["~TITLE"],
							"SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
							"SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
							"ALIGN" => "right",
							"HIDE" => $arParams["SHARE_HIDE"],
						),
						$component,
						array("HIDE_ICONS" => "Y")
						);
						?>
						</noindex>
					</div>
					<?php 
				}
				?>
				<?=$arResult["BlogUser"]["AVATAR_img"]?>
				<?=$arResult["Post"]["textFormated"]?>
				<br clear="all" />
				<?php if($arResult["POST_PROPERTIES"]["SHOW"] == "Y"):?>
					<br />
					<table cellpadding="0" cellspacing="0" border="0" class="blog-table-post-table" style="width:0%;">
					<?php foreach ($arResult["POST_PROPERTIES"]["DATA"] as $FIELD_NAME => $arPostField):?>
					<?php if(strlen($arPostField["VALUE"])>0):?>
					<tr>
						<td><b><?=$arPostField["EDIT_FORM_LABEL"]?>:</b></td>
						<td>

								<?php $APPLICATION->IncludeComponent(
									"bitrix:system.field.view", 
									$arPostField["USER_TYPE"]["USER_TYPE_ID"], 
									array("arUserField" => $arPostField), null, array("HIDE_ICONS"=>"Y"));?>
						</td>
					</tr>			
					<?php endif;?>
					<?php endforeach;?>
					</table>
				<?php endif;?>

				<?php if(!empty($arResult["Category"]))
				{
					?>
					<div class="blog-line"></div>
					<?=GetMessage("BLOG_BLOG_BLOG_CATEGORY")?>
					<?php $i=0;
					foreach($arResult["Category"] as $v)
					{
						if($i!=0)
							echo ",";
						?> <a href="<?=$v["urlToCategory"]?>"><?=$v["NAME"]?></a><?php 
						$i++;
					}
				}
				
			?>
			</td>
		</tr>
		</table>
		<br />
		<?php 
	}
	else
		echo GetMessage("BLOG_BLOG_BLOG_NO_AVAIBLE_MES");
}
?>