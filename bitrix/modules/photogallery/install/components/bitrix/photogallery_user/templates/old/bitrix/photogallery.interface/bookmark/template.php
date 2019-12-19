<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeAJAX();
/********************************************************************
				Input params
********************************************************************/
$arParams["DATA"] = (!is_array($arParams["DATA"]) ? array() : $arParams["DATA"]);
/********************************************************************
				/Input params
********************************************************************/
if (empty($arParams["DATA"]))
	return "";
$arResult["DATA"] = array(
	"HEADER" => array(),
	"BODY" => array());
$bFinedActive = false;
$count = 0;
foreach ($arParams["DATA"] as $res):
	if (empty($res["HEADER"]["TITLE"]))
		continue;
	$count++;
	$res["ACTIVE"] = (($res["ACTIVE"] != "Y" || $bFinedActive) ? "N" : "Y");
	$bFinedActive = ($res["ACTIVE"] == "Y" ? $count : $bFinedActive);
	
	$arResult["DATA"]["HEADER"][] = array(
		"ID" => $count,
		"TITLE" => $res["HEADER"]["TITLE"],
		"LINK" => $res["HEADER"]["LINK"],
		"ACTIVE" => $res["ACTIVE"],
		"HREF" => ($res["HEADER"]["HREF"] == "Y" ? "Y" : "N"),
		"AJAX_USE" => ((($res["AJAX_USE"] == "Y" || $arParams["AJAX_USE"] == "Y") && !empty($res["HEADER"]["LINK"])) ? "Y" : "N"));

	$arResult["DATA"]["BODY"][] = array(
		"ID" => $count,
		"TEXT" => $res["BODY"],
		"ACTIVE" => $res["ACTIVE"],
		"AJAX_USE" => ($res["AJAX_USE"] == "Y" || $arParams["AJAX_USE"] == "Y" ? "Y" : "N"));
endforeach;
if (!$bFinedActive)
{
	$arResult["DATA"]["HEADER"][0]["ACTIVE"] = "Y";
	$arResult["DATA"]["BODY"][0]["ACTIVE"] = "Y";
	$bFinedActive = 1;
}
$iObjectID = md5(serialize($arResult["DATA"]));
?><script type="text/javascript">
if (typeof oPhotoTabs != "object" || oPhotoTabs == null)
	var oPhotoTabs = {};
if (typeof oPhotoTabs["<?=$iObjectID?>"] != "object" || oPhotoTabs["<?=$iObjectID?>"] == null)
	oPhotoTabs["<?=$iObjectID?>"] = new PhotoTabControl("<?=$iObjectID?>", "<?=$bFinedActive?>");
</script>
<table border="0" cellpadding="0" cellspacing="0" class="photo-tabs" width="100%">
	<tr class="header"><td class="header">
			<table class="photo-tabs-header" cellpadding="0" cellspacing="0" border="0">
				<tr>
<?php 
foreach ($arResult["DATA"]["HEADER"] as $res):
	if ($res["HREF"] == "Y"):
				?><td class="href"><noindex><a rel="nofollow" href="<?=$res["LINK"]?>"><?=$res["TITLE"]?></a></noindex></td><?php 
	else:
?>
				<td class="<?=($res["ACTIVE"] == "Y" ? "" : "no-")?>active" onclick="if(oPhotoTabs['<?=$iObjectID?>']){oPhotoTabs['<?=$iObjectID?>'].SelectTab('<?=$res["ID"]?>');<?php 
					if ($res["AJAX_USE"] == "Y"):
						?>oPhotoTabs['<?=$iObjectID?>'].SendAjax('<?=CUtil::JSEscape($res["LINK"])?>', '<?=$res["ID"]?>');<?php 
					endif;
				?>}" id="header_<?=$iObjectID?>_<?=$res["ID"]?>">
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tab-header">
						<tr class="top">
							<td class="left"><div class="empty"></div></td>
							<td class="center"><div class="empty"></div></td>
							<td class="right"><div class="empty"></div></td>
						</tr>
						<tr class="middle">
							<td class="left"><div class="empty"></div></td>
							<td class="center"><div class="title">
							<?php if (!empty($res["LINK"])):?>
							<noindex><a rel="nofollow" href="<?=$res["LINK"]?>" onclick="return false;">
							<?=$res["TITLE"]?></a></noindex>
							<?php else:?>
							<?=$res["TITLE"]?>
							<?php endif;?></div></td>
							<td class="right"><div class="empty"></div></td>
						</tr>
					</table>
				</td>
<?php 
	endif;
endforeach;
?>
				</tr>
			</table>
	</td></tr>
	<tr class="body"><td class="body">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tab-header">
				<tr class="top">
					<td class="left-strong"><div class="empty"></div></td>
					<td class="center"><div class="empty"></div></td>
					<td class="right"><div class="empty"></div></td>
				</tr>
				<tr class="middle">
					<td class="left"><div class="empty"></div></td>
					<td class="body-text"><?php 
foreach ($arResult["DATA"]["BODY"] as $res):
?>
				<div class="photo-body-text" <?=($res["ACTIVE"] == "Y" ? "" : "style=\"display:none;\"")?> id="body_<?=$iObjectID?>_<?=$res["ID"]?>">
					<?php 
					if (empty($res["TEXT"]) && $res["AJAX_USE"] == "Y"):
						?><div class="photo-body-text-ajax"><div id="photo_waitwindow" class="waitwindow"><?=GetMessage("P_LOADING")?></div></div><?php 
					else:
						?><?=$res["TEXT"]?><?php 
					endif;
					?>
				</div>
<?php 
	if (!empty($res["LINK"]) && $res["AJAX_USE"] == "Y"):
		?><div class="photo-body-link"><noindex><a rel="nofollow" href="<?=$res["LINK"]?>" id="text_a_<?=$iObjectID?>_<?=$res["ID"]?>"><?=GetMessage("P_GO_TO_PAGE")?></a></noindex></div><?php 
	endif;

endforeach;
?>					</td>
					<td class="right"><div class="empty"></div></td>
				</tr>
				<tr class="bottom">
					<td class="left"><div class="empty"></div></td>
					<td class="center"><div class="empty"></div></td>
					<td class="right"><div class="empty"></div></td>
				</tr>
			</table>
	</td></tr>
</table>