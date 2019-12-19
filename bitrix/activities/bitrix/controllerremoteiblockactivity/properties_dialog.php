<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?php if($is_module_installed):?>
<tr id="tr_all" valign="top">
	<td align="right" width="40%"><?php echo GetMessage("BPCRIA_SITES_FILTER_TYPE") ?>:</td>
	<td width="60%">
		<label><input onclick="ChangeFilterType(this.id)" type="radio" name="sites_filter_type" id="sites_filter_type_all" value="all" <?php if($arCurrentValues["sites_filter_type"]!="groups" && $arCurrentValues["sites_filter_type"]!="sites") echo "checked"?>>&nbsp;<?php echo GetMessage("BPCRIA_SITES_FILTER_ALL")?></label><br>
		<label><input onclick="ChangeFilterType(this.id)" type="radio" name="sites_filter_type" id="sites_filter_type_groups" value="groups" <?php if($arCurrentValues["sites_filter_type"]=="groups") echo "checked"?>>&nbsp;<?php echo GetMessage("BPCRIA_SITES_FILTER_GROUPS")?></label><br>
		<label><input onclick="ChangeFilterType(this.id)" type="radio" name="sites_filter_type" id="sites_filter_type_sites" value="sites" <?php if($arCurrentValues["sites_filter_type"]=="sites") echo "checked"?>>&nbsp;<?php echo GetMessage("BPCRIA_SITES_FILTER_SITES")?></label><br>
		<script>
		function ChangeFilterType(to)
		{
			document.getElementById('tr_groups').style.display='none';
			document.getElementById('tr_sites').style.display='none';
			if(to == 'sites_filter_type_groups')
				document.getElementById('tr_groups').style.display=document.getElementById('tr_all').style.display;
			if(to == 'sites_filter_type_sites')
				document.getElementById('tr_sites').style.display=document.getElementById('tr_all').style.display;
		}
		function ChangeSitesGroup(sel)
		{
			<?php foreach($arSites as $group_id => $arGroupSites):?>
				document.getElementById('sites_filter_sites_<?php echo $group_id?>').style.display='none';
			<?php endforeach;?>
			document.getElementById('sites_filter_sites_' + sel.value).style.display='inline';
		}
		</script>
	</td>
</tr>
<tr id="tr_groups" valign="top" <?php if($arCurrentValues["sites_filter_type"]!="groups") echo 'style="display:none"'?>>
	<td align="right" width="40%"><?php echo GetMessage("BPCRIA_SITES_GROUPS")?>:</td>
	<td width="60%">
		<select multiple name="sites_filter_groups[]" size="5">
		<?php foreach($arSiteGroups as $key => $value):?>
			<option value="<?php echo $key?>" <?php if(in_array($key, $arCurrentValues["sites_filter_groups"])) echo "selected"?>><?php echo $value?></option>
		<?php endforeach;?>
		</select>
	</td>
</tr>
<tr id="tr_sites" <?php if($arCurrentValues["sites_filter_type"]!="sites") echo 'style="display:none"'?>>
	<?php if(!array_key_exists($arCurrentValues["sites_filter_sites_group"], $arSites))
	{
		list($arCurrentValues["sites_filter_sites_group"], $arGroupSites) = each($arSites);
		reset($arSites);
	}
	?>
	<td align="right" width="40%"><?php echo GetMessage("BPCRIA_SITES_SITES")?>:</td>
	<td width="60%" valign="top">
		<select name="sites_filter_sites_group" size="1" OnChange="ChangeSitesGroup(this)" id="sites_filter_sites_group">
		<?php foreach($arSites as $group_id => $arGroupSites):?>
			<option value="<?php echo $group_id?>" <?php if($group_id == $arCurrentValues["sites_filter_sites_group"]) echo "selected"?>><?php echo $arSiteGroups[$group_id]?></option>
		<?php endforeach;?>
		</select><br>
		<?php foreach($arSites as $group_id => $arGroupSites):?>
			<select multiple name="sites_filter_sites[]" size="5" id="sites_filter_sites_<?php echo $group_id?>"  <?php if($group_id != $arCurrentValues["sites_filter_sites_group"]) echo 'style="display:none"'?>>
				<?php foreach($arGroupSites as $site_id => $site_name):?>
					<option value="<?php echo $site_id?>" <?php if(in_array($site_id, $arCurrentValues["sites_filter_sites"])) echo "selected"?>><?php echo $site_name?></option>
				<?php endforeach;?>
			</select>
		<?php endforeach;?>
	</td>
</tr>
<tr id="tr_sync" valign="top">
	<td align="right" width="40%"><?php echo GetMessage("BPCRIA_SYNC_TIME")?>:</td>
	<td width="60%">
		<label><input type="radio" name="sync_time" value="immediate" <?php if($arCurrentValues["sync_time"]!="task") echo "checked"?>>&nbsp;<?php echo GetMessage("BPCRIA_SYNC_IMMEDIATE")?></label><br>
		<label><input type="radio" name="sync_time" value="task" <?php if($arCurrentValues["sync_time"]=="task") echo "checked"?>>&nbsp;<?php echo GetMessage("BPCRIA_SYNC_TASKS")?></label><br>
	</td>
</tr>
<?php else:?>
<tr valign="top">
	<td align="center" colspan="2"><span style="color:#FF0000;"><?php echo GetMessage("BPCRIA_NO_MODULE")?></span></td>
</tr>
<?php endif;?>