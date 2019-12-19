<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if(!empty($arResult["OK_MESSAGE"]))
{
	foreach($arResult["OK_MESSAGE"] as $v)
	{
		?>
		<span class='notetext'><?=$v?></span><br /><br />
		<?php 
	}
}
if(!empty($arResult["MESSAGE"]))
{
	foreach($arResult["MESSAGE"] as $v)
	{
		?>
		<?=$v?><br /><br />
		<?php 
	}
}
if(!empty($arResult["ERROR_MESSAGE"]))
{
	foreach($arResult["ERROR_MESSAGE"] as $v)
	{
		?>
		<span class='errortext'><?=$v?></span><br /><br />
		<?php 
	}
}
if(count($arResult["POST"])>0)
{
	foreach($arResult["POST"] as $CurPost)
	{
		?>
		<table class="blog-table-post">
		<tr>
			<th nowrap width="100%">
				<table class="blog-table-post-table">
				<tr>
					<td width="100%" align="left">
						<span class="blog-post-date"><?=$CurPost["DATE_PUBLISH_FORMATED"]?></span><br />
						<span class="blog-author"><b><a href="<?=$CurPost["urlToPost"]?>"><?=$CurPost["TITLE"]?></a></b></span>
					</td>
					<?php if(strLen($CurPost["urlToEdit"])>0):?>
						<td>
							<a href="<?=$CurPost["urlToEdit"]?>" class="blog-post-edit"></a>
						</td>
					<?php endif;?>
					<?php if(strLen($CurPost["urlToDelete"])>0):?>
						<td>
							<a href="javascript:if(confirm('<?=GetMessage("BLOG_MES_DELETE_POST_CONFIRM")?>')) window.location='<?=$CurPost["urlToDelete"]."&".bitrix_sessid_get()?>'" class="blog-post-delete"></a>
						</td>
					<?php endif;?>
				</tr>
				</table>
			</th>
		</tr>
		<tr>
			<td>
				<span class="blog-text">
				<?php 
				if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
				{
					?>
					<div class="blog-post-share" style="float: right;">
						<noindex>
						<?php 
						$APPLICATION->IncludeComponent("bitrix:main.share", "", array(
								"HANDLERS" => $arParams["SHARE_HANDLERS"],
								"PAGE_URL" => htmlspecialcharsback($CurPost["urlToPost"]),
								"PAGE_TITLE" => htmlspecialcharsback($CurPost["TITLE"]),
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
				<?=$CurPost["TEXT_FORMATED"]?></span><?php 
				if ($CurPost["CUT"] == "Y")
				{
					?><br /><br /><div align="left" class="blog-post-date"><a href="<?=$CurPost["urlToPost"]?>"><?=GetMessage("BLOG_BLOG_BLOG_MORE")?></a></div><?php 
				}
				?>
				<?php if($CurPost["POST_PROPERTIES"]["SHOW"] == "Y"):?>
					<br /><br />
					<table cellpadding="0" cellspacing="0" border="0" class="blog-table-post-table" style="width:0%;">
					<?php foreach ($CurPost["POST_PROPERTIES"]["DATA"] as $FIELD_NAME => $arPostField):?>
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
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="blog-table-post-table">
				<tr>
					<td colspan="2"><div class="blog-line"></div></td>
				</tr>
				<tr>
					<td align="left">						
						<?php 
						if(!empty($CurPost["CATEGORY"]))
						{
							echo GetMessage("BLOG_BLOG_BLOG_CATEGORY");
							$i=0;
							foreach($CurPost["CATEGORY"] as $v)
							{
								if($i!=0)
									echo ",";
								?> <a href="<?=$v["urlToCategory"]?>"><?=$v["NAME"]?></a><?php 
								$i++;
							}
						}
						?></td>
					<td align="right" nowrap><a href="<?=$CurPost["urlToPost"]?>"><?=GetMessage("BLOG_BLOG_BLOG_PERMALINK")?></a>&nbsp;|&nbsp;
					<?php if($arResult["enable_trackback"] == "Y" && $CurPost["ENABLE_TRACKBACK"]=="Y"):?>
						<a href="<?=$CurPost["urlToPost"]?>#trackback">Trackbacks: <?=$CurPost["NUM_TRACKBACKS"];?></a>&nbsp;|&nbsp;
					<?php endif;?>
					<a href="<?=$CurPost["urlToPost"]?>"><?=GetMessage("BLOG_BLOG_BLOG_VIEWS")?> <?=IntVal($CurPost["VIEWS"]);?></a>&nbsp;|&nbsp;
					<a href="<?=$CurPost["urlToPost"]?>#comments"><?=GetMessage("BLOG_BLOG_BLOG_COMMENTS")?> <?=$CurPost["NUM_COMMENTS"];?></a></td>

				</tr>
				</table>
			</td>
		</tr>
		</table>
		<br />
		<?php 
	}
	if(strlen($arResult["NAV_STRING"])>0)
		echo $arResult["NAV_STRING"];
}
elseif(!empty($arResult["BLOG"]))
	echo GetMessage("BLOG_BLOG_BLOG_NO_AVAIBLE_MES");
?>	