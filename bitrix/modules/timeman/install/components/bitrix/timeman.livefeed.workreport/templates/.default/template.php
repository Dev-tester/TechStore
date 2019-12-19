<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CJSCore::Init(array('timeman'));
?><script>
	BX.message({
		timemanLFReportConfirmWithMark: '<?=CUtil::JSEscape(GetMessage("REPORT_COMMENT_CONFIRM_W_MARK"))?>',
		timemanLFReportConfirmWithoutMark: '<?=CUtil::JSEscape(GetMessage("REPORT_COMMENT_CONFIRM_WO_MARK"))?>',
		timemanLFReportConfirmMarkG: '<?=CUtil::JSEscape(GetMessage("REPORT_COMMENT_CONFIRM_VALUE_G"))?>',
		timemanLFReportConfirmMarkB: '<?=CUtil::JSEscape(GetMessage("REPORT_COMMENT_CONFIRM_VALUE_B"))?>'
	});
</script>
<div class="feed-workday-table"><?php 
	?><span class="feed-workday-left-side"><?php 
		?><span class="feed-workday-table-text"><?=GetMessage("REPORT_FROM")?>:</span><?php 
		?><span class="feed-workday-avatar"
			<?php  if (strlen($arParams["USER"]["PHOTO"]) > 0): ?>
				style="background:url('<?=$arParams["USER"]["PHOTO"]?>') no-repeat center; background-size: cover;"
			<?php  endif ?>
		></span><?php 
		?><span class="feed-user-name-wrap"><a href="<?=$arParams['USER']["URL"]?>" class="feed-workday-user-name" bx-tooltip-user-id="<?=$arParams['USER']["ID"]?>"><?=$arParams['USER']["NAME"]?></a><?php 
		if (!empty($arParams['USER']["WORK_POSITION"]))
		{
			?><span class="feed-workday-user-position"><?=$arParams['USER']["WORK_POSITION"]?></span><?php 
		}
		?></span><?php 
	?></span><?php 
	?><span class="feed-workday-right-side"><?php 
		?><span class="feed-workday-table-text"><?=GetMessage("REPORT_TO")?>:</span><?php 
		?><span class="feed-workday-avatar"
			<?php  if (strlen($arParams["MANAGER"]["PHOTO"]) > 0): ?>
				style="background:url('<?=$arParams["MANAGER"]["PHOTO"]?>') no-repeat center; background-size: cover;"
			<?php  endif ?>
		></span><?php 
		?><span class="feed-user-name-wrap"><a href="<?=$arParams['MANAGER']["URL"]?>" class="feed-workday-user-name" bx-tooltip-user-id="<?=$arParams['MANAGER']["ID"]?>"><?=$arParams['MANAGER']["NAME"]?></a><?php 
			if (!empty($arParams['MANAGER']["WORK_POSITION"]))
			{
				?><span class="feed-workday-user-position"><?=$arParams['MANAGER']["WORK_POSITION"]?></span><?php 
			}
		?></span><?php 
	?></span><?php 
?></div><?php 
if (in_array($arParams["MARK"], array("N", "G", "B")))
{
	?><div class="feed-workday-comments" id="report_comments_<?=$arParams["REPORT_ID"]?>"><?php 
		if (in_array($arParams["MARK"], array("G", "B")))
		{
			?><span class="feed-workday-com-icon"></span><?php 
			?><?=str_replace("#VALUE#", '<span class="feed-post-color-'.($arParams["MARK"] == "G" ? "green" : "red").'">'.GetMessage("REPORT_COMMENT_CONFIRM_VALUE_".$arParams["MARK"]).'</span>', GetMessage("REPORT_COMMENT_CONFIRM_W_MARK"))?><?php 
		}
		else
		{
			?><?=GetMessage("REPORT_COMMENT_CONFIRM_WO_MARK")?><?php 
		}
	?></div><?php 
}
?>

<script type="text/javascript">
BX.addCustomEvent('onWorkReportMarkChange',
	function(data){

		if (data.INFO.ID != '<?=$arParams["REPORT_ID"]?>')
			return;

		var logNode = BX.findParent(BX('report_comments_<?=$arParams["REPORT_ID"]?>'), {'tag': 'div', 'className': 'feed-post-block'});

		if (BX(logNode))
		{
			if (data.INFO.MARK == 'G')
			{
				BX.removeClass(logNode, 'feed-workday-edit');
				BX.removeClass(logNode, 'feed-workday-rejected');
				BX.addClass(logNode, 'feed-workday-confirm');
			}
			else if (data.INFO.MARK == 'B')
			{
				BX.removeClass(logNode, 'feed-workday-edit');
				BX.removeClass(logNode, 'feed-workday-confirm');
				BX.addClass(logNode, 'feed-workday-rejected');
			}
			else
			{
				BX.removeClass(logNode, 'feed-workday-rejected');
				BX.removeClass(logNode, 'feed-workday-confirm');
				BX.addClass(logNode, 'feed-workday-edit');
			}
		}

		var commentsNode = BX("report_comments_<?=$arParams["REPORT_ID"]?>");

		if (BX(commentsNode))
		{
			if (data.INFO.MARK == 'N')
				BX(commentsNode).innerHTML = BX.message('timemanLFReportConfirmWithoutMark');
			else if (data.INFO.MARK == 'G')
				BX(commentsNode).innerHTML ='<span class="feed-workday-com-icon"></span>'+BX.message('timemanLFReportConfirmWithMark').replace('#VALUE#', '<span class="feed-post-color-green">' + BX.message('timemanLFReportConfirmMarkG') + '</span>');
			else if (data.INFO.MARK == 'B')
				BX(commentsNode).innerHTML = '<span class="feed-workday-com-icon"></span>'+BX.message('timemanLFReportConfirmWithMark').replace('#VALUE#', '<span class="feed-post-color-red">' + BX.message('timemanLFReportConfirmMarkB') + '</span>');

			if (data.INFO.MARK == 'X')
				BX(commentsNode).style.display = 'none';
			else
				BX(commentsNode).style.display = 'block';
		}
	}
);
</script>

