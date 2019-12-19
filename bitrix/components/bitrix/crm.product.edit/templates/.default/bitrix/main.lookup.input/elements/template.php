<?php if(!Defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$control_id = $arParams['CONTROL_ID'];
$textarea_id = $arParams['INPUT_NAME_STRING'] ? $arParams['INPUT_NAME_STRING'] : 'visual_'.$control_id;

$INPUT_VALUE = array();
if(isset($arParams['INPUT_VALUE_STRING']) && strlen($arParams['INPUT_VALUE_STRING']))
{
	$arTokens = preg_split('/(?<=])[\n;,]+/', $arParams['~INPUT_VALUE_STRING']);
	foreach($arTokens as $key => $token)
	{
		if(preg_match("/^(.*) \\[(\\d+)\\]/", $token, $match))
			$INPUT_VALUE[] = array(
				"ID" => $match[2],
				"NAME" => $match[1],
			);
	}
}


?>
<div class="mli-layout" id="layout_<?=$control_id?>">
	<?php if($arParams["MULTIPLE"]=="Y"):?>
	<textarea name="<?=$textarea_id?>" id="<?=$textarea_id?>"><?php if (isset($arParams['INPUT_VALUE_STRING'])) echo htmlspecialcharsbx($arParams['INPUT_VALUE_STRING']);?></textarea>
	<?php else:?>
	<input autocomplete="off" type="text" name="<?=$textarea_id?>" id="<?=$textarea_id?>" value="<?php if (isset($arParams['INPUT_VALUE_STRING'])) echo htmlspecialcharsbx($arParams['INPUT_VALUE_STRING']);?>">
	<?php endif?>
</div>
<script type="text/javascript">
var jsMLI_<?=$control_id?> = new JCMainLookupSelector({
	'AJAX_PAGE' : '<?php echo CUtil::JSEscape($this->GetFolder()."/ajax.php")?>',
	'AJAX_PARAMS' : <?php echo CUtil::PhpToJsObject(array(
		"IBLOCK_TYPE_ID" => $arParams["IBLOCK_TYPE_ID"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SOCNET_GROUP_ID" => $arParams["SOCNET_GROUP_ID"],
	))?>,
	'CONTROL_ID': '<?php echo CUtil::JSEscape($control_id)?>',
	'LAYOUT_ID': 'layout_<?php echo CUtil::JSEscape($control_id)?>',
	'INPUT_NAME': '<?php echo CUtil::JSEscape($arParams['INPUT_NAME'])?>',
	<?php if($arParams['INPUT_NAME_SUSPICIOUS']):?>
		'INPUT_NAME_SUSPICIOUS': '<?php echo CUtil::JSEscape($arParams['INPUT_NAME_SUSPICIOUS'])?>',
	<?php endif;?>
	'VALUE': <?php echo CUtil::PhpToJsObject($INPUT_VALUE)?>,
	'VISUAL': {
		'ID': '<?=$textarea_id?>',
		'MAX_HEIGHT': <?php echo $arParams['TEXTAREA_MAX_HEIGHT'] ? intval($arParams['TEXTAREA_MAX_HEIGHT']) : '1000'?>,
		'MIN_HEIGHT': <?php echo $arParams['TEXTAREA_MIN_HEIGHT'] ? intval($arParams['TEXTAREA_MIN_HEIGHT']) : '30'?>,
		'START_TEXT': '<?php echo CUtil::JSEscape($arParams['START_TEXT'])?>'
	}
});
</script>