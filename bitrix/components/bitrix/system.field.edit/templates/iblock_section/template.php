<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(
	$arParams["arUserField"]["ENTITY_VALUE_ID"] <= 0
	&& $arParams["arUserField"]["SETTINGS"]["DEFAULT_VALUE"] > 0
)
{
	$arResult['VALUE'] = array($arParams["arUserField"]["SETTINGS"]["DEFAULT_VALUE"]);
}
else
{
	$arResult['VALUE'] = array_filter($arResult["VALUE"]);
}

if($arParams['arUserField']["SETTINGS"]["DISPLAY"] != "CHECKBOX")
{
	if($arParams["arUserField"]["MULTIPLE"] == "Y")
	{
		?>
		<select multiple="multiple" name="<?php echo $arParams["arUserField"]["FIELD_NAME"]?>" size="<?php echo $arParams["arUserField"]["SETTINGS"]["LIST_HEIGHT"]?>" style="width: 225px;">
		<?php 
		foreach ($arParams["arUserField"]["USER_TYPE"]["FIELDS"] as $key => $val)
		{
			$bSelected = in_array($key, $arResult["VALUE"]);
			?>
			<option value="<?php echo $key?>" <?php echo ($bSelected? "selected" : "")?> title="<?php echo trim($val, " .")?>"><?php echo $val?></option>
			<?php 
		}
		?>
		</select>
		<?php 
	}
	else
	{
		?>
		<select name="<?php echo $arParams["arUserField"]["FIELD_NAME"]?>" size="<?php echo $arParams["arUserField"]["SETTINGS"]["LIST_HEIGHT"]?>" style="width: 225px;">
		<?php 
		$bWasSelect = false;
		foreach ($arParams["arUserField"]["USER_TYPE"]["FIELDS"] as $key => $val)
		{
			if($bWasSelect)
				$bSelected = false;
			else
				$bSelected = in_array($key, $arResult["VALUE"]);

			if($bSelected)
				$bWasSelect = true;
			?>
			<option value="<?php echo $key?>" <?php echo ($bSelected? "selected" : "")?> title="<?php echo trim($val, " .")?>"><?php echo $val?></option>
			<?php 
		}
		?>
		</select>
		<?php 
	}
}
else
{
	if($arParams["arUserField"]["MULTIPLE"] == "Y")
	{
		?>
		<input type="hidden" value="" name="<?php echo $arParams["arUserField"]["FIELD_NAME"]?>">
		<?php 
		foreach ($arParams["arUserField"]["USER_TYPE"]["FIELDS"] as $key => $val)
		{
			$id = $arParams["arUserField"]["FIELD_NAME"]."_".$key;

			$bSelected = in_array($key, $arResult["VALUE"]);
			?>
			<input type="checkbox" value="<?php echo $key?>" name="<?php echo $arParams["arUserField"]["FIELD_NAME"]?>" <?php echo ($bSelected? "checked" : "")?> id="<?php echo $id?>"><label for="<?php echo $id?>"><?php echo $val?></label><br />
			<?php 
		}
	}
	else
	{
		if($arParams["arUserField"]["MANDATORY"] != "Y")
		{
			$id = $arParams["arUserField"]["FIELD_NAME"]."_no";
			?>
			<input type="radio" value="" name="<?php echo $arParams["arUserField"]["FIELD_NAME"]?>" id="<?php echo $id?>"><label for="<?php echo $id?>"><?php echo GetMessage("MAIN_NO")?></label><br />
			<?php 
		}

		$bWasSelect = false;
		foreach ($arParams["arUserField"]["USER_TYPE"]["FIELDS"] as $key => $val)
		{
			$id = $arParams["arUserField"]["FIELD_NAME"]."_".$key;

			if($bWasSelect)
				$bSelected = false;
			else
				$bSelected = in_array($key, $arResult["VALUE"]);

			if($bSelected)
				$bWasSelect = true;
			?>
			<input type="radio" value="<?php echo $key?>" name="<?php echo $arParams["arUserField"]["FIELD_NAME"]?>" <?php echo ($bSelected? "checked" : "")?> id="<?php echo $id?>"><label for="<?php echo $id?>"><?php echo $val?></label><br />
			<?php 
		}
	}
}
?>