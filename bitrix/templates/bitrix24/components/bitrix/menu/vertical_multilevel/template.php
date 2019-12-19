<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$isCompositeMode = defined("USE_HTML_STATIC_CACHE") ? true : false;
$this->setFrameMode(true);

if(!function_exists("IsSubItemSelected"))
{
	function IsSubItemSelected($ITEMS)
	{
		if (is_array($ITEMS))
		{
			foreach($ITEMS as $arItem)
			{
				if ($arItem["SELECTED"])
					return true;
			}
		}
		return false;
	}
}
if (empty($arResult))
	return;

$arHiddenItemsSelected = array();
$sumHiddenCounters = 0;
$arHiddenItemsCounters = array();
$arAllItemsCounters = array();
?>
<div id="bx_b24_menu">
<?php 
foreach($arResult["TITLE_ITEMS"] as $title => $arTitleItem)
{
	if (is_array($arResult["SORT_ITEMS"][$title]["show"]) || is_array($arResult["SORT_ITEMS"][$title]["hide"]))
	{
		$hideOption = CUserOptions::GetOption("bitrix24", $arTitleItem["PARAMS"]["class"]);
		$SubItemSelected = false;
		if (!is_array($hideOption) || $hideOption["hide"] == "Y")
			$SubItemSelected = IsSubItemSelected($arResult["SORT_ITEMS"][$title]["show"]) || IsSubItemSelected($arResult["SORT_ITEMS"][$title]["hide"]) ? true : false;

		if (IsModuleInstalled("bitrix24"))
			$disabled = (!is_array($hideOption) && $arTitleItem["PARAMS"]["class"]=="menu-crm" && !$SubItemSelected) || (is_array($hideOption) && $hideOption["hide"] == "Y" && !$SubItemSelected);
		else
			$disabled = (!is_array($hideOption) && $arTitleItem["PARAMS"]["class"]!="menu-favorites" && !$SubItemSelected) || (is_array($hideOption) && $hideOption["hide"] == "Y" && !$SubItemSelected);?>

		<div class="menu-items-block <?=$arTitleItem["PARAMS"]["class"]?> " <?php if ($arTitleItem["PARAMS"]["is_empty"] == "Y"):?>style="display:none"<?php endif?> id="div_<?=$arTitleItem["PARAMS"]["menu_item_id"]?>">
			<div id="<?=$arTitleItem["PARAMS"]["menu_item_id"]?>" class="menu-items-title <?=$arTitleItem["PARAMS"]["class"]?>"><?php 
				if ($arTitleItem["PARAMS"]["class"] == "menu-favorites"):?>
					<span class="menu-items-title-text"><?php echo $arTitleItem["TEXT"]?></span>
					<span class="menu-favorites-settings" id="menu_favorites_settings" onclick="B24menuItemsObj.applyEditMode();" title="<?=GetMessage("MENU_SETTINGS_TITLE")?>"><span class="menu-fav-settings-icon"></span></span>
					<span class="menu-favorites-btn-done" onclick="B24menuItemsObj.applyEditMode();"><?=GetMessage("MENU_EDIT_READY")?></span><?php 
				else:
					echo $arTitleItem["TEXT"];
					?><span class="menu-toggle-text"><?=($disabled ? GetMessage("MENU_SHOW") : GetMessage("MENU_HIDE"))?></span><?php 
				endif
			?></div>
			<ul  class="menu-items<?php if ($disabled):?> menu-items-close<?php endif;?>" id="ul_<?=$arTitleItem["PARAMS"]["menu_item_id"]?>">
				<li class="menu-items-empty-li" id="empty_li_<?=$arTitleItem["PARAMS"]["menu_item_id"]?>" style="height: 3px;"></li>
				<?php 
				$arTmp = array("show", "hide");
				foreach($arTmp as $status)
				{
					if ($status=="hide"):?>
					<li class="menu-item-separator" id="separator_<?=$arTitleItem["PARAMS"]["menu_item_id"]?>">
						<span class="menu-item-sepor-text"><?=GetMessage("MENU_HIDDEN_ITEMS")?></span>
						<span class="menu-item-sepor-line"></span>
					</li>
					<li class="menu-item-block menu-item-favorites-more" id="hidden_items_li_<?=$arTitleItem["PARAMS"]["menu_item_id"]?>">
						<ul class="menu-items-fav-more-block" id="hidden_items_ul_<?=$arTitleItem["PARAMS"]["menu_item_id"]?>">
					<?php endif;
					if (is_array($arResult["SORT_ITEMS"][$title][$status]))
					{
						foreach($arResult["SORT_ITEMS"][$title][$status] as $arItem)
						{
							if ($arItem["PERMISSION"] > "D")
							{
								$couterId = "";
								$counter = 0;
								if (array_key_exists("counter_id", $arItem["PARAMS"]) && strlen($arItem["PARAMS"]["counter_id"]) > 0)
								{
									$couterId = $arItem["PARAMS"]["counter_id"] == "live-feed" ? "**" : $arItem["PARAMS"]["counter_id"];
									$counter = isset($GLOBALS["LEFT_MENU_COUNTERS"]) && array_key_exists($couterId, $GLOBALS["LEFT_MENU_COUNTERS"]) ? $GLOBALS["LEFT_MENU_COUNTERS"][$couterId] : 0;
									if ($couterId == "crm_cur_act")
									{
										$counterCrm = (isset($GLOBALS["LEFT_MENU_COUNTERS"]) && array_key_exists("CRM_**", $GLOBALS["LEFT_MENU_COUNTERS"]) ? intval($GLOBALS["LEFT_MENU_COUNTERS"]["CRM_**"]) : 0);
										$counterAct = $counter;
										$counter += $counterCrm;
									}
								}

								if ($couterId == "bp_tasks" && IsModuleInstalled("bitrix24"))
								{
									$showMenuItem = CUserOptions::GetOption("bitrix24", "show_bp_in_menu", false);
									if ($showMenuItem === false && $counter > 0)
									{
										CUserOptions::SetOption("bitrix24", "show_bp_in_menu", true);
										$showMenuItem = true;
									}

									if ($showMenuItem === false)
										continue;
								}

								if ($couterId)
								{
									$arAllItemsCounters[$couterId] = $isCompositeMode ? 0 : $counter;
									if ($status=="hide")
									{
										$sumHiddenCounters+= $counter;
										$arHiddenItemsCounters[] = $couterId;
									}
								}
								?>
								<li <?php if ($title!= "menu-favorites" && in_array($arItem["PARAMS"]["menu_item_id"],$arResult["ALL_FAVOURITE_ITEMS_ID"])):?>style="display:none; " <?php endif?>
									id="<?php if ($title!= "menu-favorites" && in_array($arItem["PARAMS"]["menu_item_id"],$arResult["ALL_FAVOURITE_ITEMS_ID"])) echo "hidden_"; echo $arItem["PARAMS"]["menu_item_id"]?>"
									data-status="<?=$status?>"
									data-title-item="<?=$arTitleItem["PARAMS"]["menu_item_id"]?>"
									data-counter-id="<?=$couterId?>"
									data-can-delete-from-favorite="<?=$arItem["PARAMS"]["can_delete_from_favourite"]?>"
									<?php if (isset($arItem["PARAMS"]["is_application"])):?>
										data-app-id="<?=$arItem["PARAMS"]["app_id"]?>"
									<?php endif?>
									class="menu-item-block <?php if ($isCompositeMode === false && $arItem["SELECTED"]):?> menu-item-active<?php endif?><?php if($isCompositeMode === false && $counter > 0 && strlen($couterId) > 0 && (!$arItem["SELECTED"] || ($arItem["SELECTED"] && $couterId == "bp_tasks"))):?> menu-item-with-index<?php endif?><?php if ((IsModuleInstalled("bitrix24") && $arItem["PARAMS"]["menu_item_id"] == "menu_live_feed") || $arItem["PARAMS"]["menu_item_id"] == "menu_all_groups"):?> menu-item-live-feed<?php endif?>">
									<?php if (!((IsModuleInstalled("bitrix24") && $arItem["PARAMS"]["menu_item_id"] == "menu_live_feed") || $arItem["PARAMS"]["menu_item_id"] == "menu_all_groups")):?>
										<span class="menu-fav-editable-btn menu-favorites-btn" onclick="B24menuItemsObj.openMenuPopup(this, '<?=CUtil::JSEscape($arItem["PARAMS"]["menu_item_id"])?>')"><span class="menu-favorites-btn-icon"></span></span>
										<span class="menu-favorites-btn menu-favorites-draggable" onmousedown="BX.addClass(this.parentNode, 'menu-item-draggable');" onmouseup="BX.removeClass(this.parentNode, 'menu-item-draggable');"><span class="menu-fav-draggable-icon"></span></span>
									<?php endif?>
									<?php 
									$curLink = "";
									if (preg_match("~^".SITE_DIR."index\.php~i", $arItem["LINK"]))
										$curLink = SITE_DIR;
									elseif (isset($arItem["PARAMS"]["onclick"]) && !empty($arItem["PARAMS"]["onclick"]))
										$curLink = "javascript:void(0)";
									else
										$curLink = $arItem["LINK"];
									?>
									<a class="menu-item-link" href="<?=$curLink?>" title="<?=$arItem["TEXT"]?>" onclick="
										if (B24menuItemsObj.isEditMode())
											return false;

										<?php if (isset($arItem["PARAMS"]["onclick"])):?>
											<?=CUtil::JSEscape($arItem["PARAMS"]["onclick"])?>
										<?php endif?>
									">
										<span class="menu-item-link-text"><?=$arItem["TEXT"]?></span>
										<?php if (strlen($couterId) > 0):
											$itemCounter = "";
											$crmAttrs = "";
											if ($isCompositeMode === false)
											{
												$itemCounter = ($arItem["PARAMS"]["counter_id"] == "mail_unseen" ? ($counter > 99 ? "99+" : $counter) : ($counter > 50 ? "50+" : $counter));
												$crmAttrs = ($arItem["PARAMS"]["counter_id"] == "crm_cur_act" ? ' data-counter-crmstream="'.intval($counterCrm).'" data-counter-crmact="'.intval($counterAct).'"' : "");
											}
										?><span class="menu-item-index-wrap"><span class="menu-item-index" <?=$crmAttrs?> id="menu-counter-<?=strtolower($arItem["PARAMS"]["counter_id"])?>"><?=$itemCounter?></span></span>
											<?php if (!empty($arItem["PARAMS"]["warning_link"])):?>
												<span onclick="window.location.replace('<?=$arItem["PARAMS"]["warning_link"]; ?>'); return false; "
													<?php  if (!empty($arItem["PARAMS"]["warning_title"])):?>title="<?=$arItem["PARAMS"]["warning_title"]; ?>"<?php endif?>
													class="menu-post-warn-icon"
													id="menu-counter-warning-<?=strtolower($arItem["PARAMS"]["counter_id"]); ?>"></span>
											<?php endif?>
										<?php endif;?>
									</a>
								</li>
							<?php 
							}
						}
					}
					if ($status=="hide"):?>
						</ul>
					</li>
					<?php endif;
				}
				?>
			</ul>
			<div class="menu-favorites-more-btn<?php if ($disabled):?> menu-items-close<?php endif;?>" id="more_btn_<?=$arTitleItem["PARAMS"]["menu_item_id"]?>" <?php if (!is_array($arResult["SORT_ITEMS"][$title]["hide"])):?>style="display:none;"<?php endif?> onclick="B24menuItemsObj.showHideMoreItems(this, '<?=CUtil::JSEscape($arTitleItem["PARAMS"]["menu_item_id"])?>');">
				<span class="menu-favorites-more-text"><?=GetMessage("MENU_MORE_ITEMS_SHOW")?></span>
				<?php if ($title == "menu-favorites"):?>
					<span class="menu-item-index menu-item-index-more" id="menu-hidden-counter" <?php if ($isCompositeMode || $sumHiddenCounters <= 0):?>style="display:none"<?php endif?>><?= ($isCompositeMode ? "" : ($sumHiddenCounters > 50 ? "50+" : $sumHiddenCounters))?></span>
				<?php endif?>
				<span class="menu-favorites-more-icon"></span>
			</div>
			<?php 
			if (IsSubItemSelected($arResult["SORT_ITEMS"][$title]["hide"]))
				$arHiddenItemsSelected[] = $arTitleItem["PARAMS"]["menu_item_id"];
			?>
		</div>
	<?php 
	}
}
?>
</div>

<?php 
$arJSParams = array(
	"arFavouriteAll" => $arResult["ALL_FAVOURITE_ITEMS_ID"],
	"arFavouriteShowAll" => $arResult["ALL_SHOW_FAVOURITE_ITEMS_ID"],
	"arTitles" => array_keys($arResult["TITLE_ITEMS"]),
	"ajaxPath" => $this->GetFolder()."/ajax.php",
	"isAdmin" => (IsModuleInstalled("bitrix24") && $GLOBALS['USER']->CanDoOperation('bitrix24_config') || !IsModuleInstalled("bitrix24") && $GLOBALS['USER']->IsAdmin()) ? "Y" : "N",
	"hiddenCounters" => $arHiddenItemsCounters,
	"allCounters" => $arAllItemsCounters,
	"isBitrix24" => IsModuleInstalled("bitrix24") ? "Y" : "N",
	"siteId" => SITE_ID,
	"arHiddenItemsSelected" => $isCompositeMode ? array() : $arHiddenItemsSelected,
	"isCompositeMode" => $isCompositeMode
);
?>

<script>
	BX.message({
		add_to_favorite: '<?=CUtil::JSEscape(GetMessage('MENU_ADD_TO_FAVORITE'))?>',
		delete_from_favorite: '<?=CUtil::JSEscape(GetMessage('MENU_DELETE_FROM_FAVORITE'))?>',
		hide_item: '<?=CUtil::JSEscape(GetMessage('MENU_HIDE_ITEM'))?>',
		show_item: '<?=CUtil::JSEscape(GetMessage('MENU_SHOW_ITEM'))?>',
		add_to_favorite_all: '<?=CUtil::JSEscape(GetMessage('MENU_ADD_TO_FAVORITE_ALL'))?>',
		delete_from_favorite_all: '<?=CUtil::JSEscape(GetMessage('MENU_DELETE_FROM_FAVORITE_ALL'))?>',
		more_items_hide: '<?=CUtil::JSEscape(GetMessage('MENU_MORE_ITEMS_HIDE'))?>',
		more_items_show: '<?=CUtil::JSEscape(GetMessage('MENU_MORE_ITEMS_SHOW'))?>',
		edit_error: '<?=CUtil::JSEscape(GetMessage('MENU_ITEM_EDIT_ERROR'))?>',
		set_rights: '<?=CUtil::JSEscape(GetMessage('MENU_ITEM_SET_RIGHTS'))?>',
		menu_show: '<?=CUtil::JSEscape(GetMessage('MENU_SHOW'))?>',
		menu_hide: '<?=CUtil::JSEscape(GetMessage('MENU_HIDE'))?>'
	});

	<?php if ($isCompositeMode):?>
	var path = document.location.pathname;

	if (document.location.pathname !== '<?=SITE_DIR?>')
		path += document.location.search;

	if (!BX.Bitrix24.MenuClass.highlight(path))
	{
		BX.ready(function() {
			BX.Bitrix24.MenuClass.highlight(path);
		});
	}
	<?php endif?>

	BX.ready(function() {
		window.B24menuItemsObj = new BX.Bitrix24.MenuClass(<?=CUtil::PhpToJSObject($arJSParams)?>);
	});
</script>
