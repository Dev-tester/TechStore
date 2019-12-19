<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
if ($arResult["NEED_AUTH"] == "Y")
{
	$APPLICATION->AuthForm("");
}
else
{
	?>
	<div class="sonet-bizproc">
	<h2><?=GetMessage("SONET_BP_TASKS")?></h2>
	<?php 
	if(!empty($arResult["TASKS"]))
	{
		?>
		<table class="data-table">
		<tr>
			<th><?=GetMessage("SONET_BP_NAME")?></th>
			<th><?=GetMessage("SONET_BP_DESCR")?></th>
			<th><?=GetMessage("SONET_BP_DATE")?></th>
			<th><?=GetMessage("SONET_BP_ACTION")?></th>
		</tr>
		<?php 
		foreach($arResult["TASKS"] as $val)
		{
			?>
			<tr valign="top">
			<td><a href="<?=$val["EditUrl"]?>" title="<?=GetMessage("SONET_BP_VIEW_TASK")?>"><?=$val["NAME"]?></a></td>
			<td><?=$val["DESCRIPTION"]?></td>
			<td><?=$val["MODIFIED"]?></td>
			<td><a href="<?=$val["EditUrl"]?>" title="<?=GetMessage("SONET_BP_VIEW_TASK")?>"><?=GetMessage("SONET_BP_EDIT_TASK")?></a></td>
			</tr>
			<?php 
		}
		?>
		</table>
		<?php 
	}
	else
		echo GetMessage("SONET_BP_EPMTY_TASKS");
	?>
	
	<?php 
	if(!empty($arResult["TRACKING"]))
	{
		?><h2><?=GetMessage("SONET_BP_TRACKING")?></h2><?php 
		?>
		<table class="data-table">
		<tr>
			<th><?=GetMessage("SONET_BP_DATE")?></th>
			<th><?=GetMessage("SONET_BP_EVENT")?></th>
			<th><?=GetMessage("SONET_BP_DOC")?></th>
		</tr>
		<?php 
		foreach($arResult["TRACKING"] as $val)
		{
			?>
			<tr>
			<td valign="top"><?=$val["MODIFIED"]?></td>
			<td><?=$val["ACTION_NOTE"]?></td>
			<td valign="top">
				<?php if(strlen($val["STATE"]["Url"]) > 0):?>
					<a href="<?=$val["STATE"]["Url"]?>" title="<?=GetMessage("SONET_BP_VIEW_DOCUMENT")?>"><?=$val["STATE"]["DOCUMENT_ID"][2]?></a>
				<?php else:?>
					<?=$val["STATE"]["DOCUMENT_ID"][2]?>
				<?php endif;?>
			</td>
			</tr>
			<?php 
		}
		?>
		</table>
		<?php 

	}
	?>
	</div>
	<?php 
}
?>