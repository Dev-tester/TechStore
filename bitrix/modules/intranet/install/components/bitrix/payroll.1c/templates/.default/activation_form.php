<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if ($arResult["IS_ACTIVATION"] && $arResult["RESULT"] && !$arResult["RESULT"]['ERROR']):?>
	<?php if($arResult["RESULT"]['html_form']):?>	
			<?=$arResult["RESULT"]['html_form'];?>
	<?php endif;?>

	<div style="width:460px;padding-top:10px;margin-top:10px;clear:both;text-align:left;" onclick="document.location.href = '<?=$arResult["FORWARD_BUTTON_URL"];?>'">
		<span class="popup-window-button">
			<span class="popup-window-button-left"></span>
			<span class="popup-window-button-text"><?=GetMessage($arResult["FORWARD_BUTTON"])?></span>
			<span class="popup-window-button-right"></span>
		</span>
	</div>
<?php else:?>
	<?php if($arResult["RESULT"]['ERROR']):?>
		<div class="error"><?=$arResult["RESULT"]['ERROR'];?></div>
	<?php endif;?>
<div style="background:#EEEEEE;padding:3px;border:1px solid #C2C2C2;width:320px; ">
	<div id="payroll-panel" style="height:130px;" >
		<form name="activation_form" id="activation_form" action="" method="POST">
			<?=GetMessage("ORG_LIST")?><br>
			<select class="payroll-input" name="USERORG">
				<?php foreach ($arResult["ORG_LIST"] as $key=>$arOrgName):?>
					<?php if ($arOrgName):?>						
						<option value="<?=$key?>"><?=$arOrgName;?></option>
					<?php endif;?>
				<?php endforeach;?>
			</select><br>
			<?=GetMessage("ACTIVATION_CODE_TYPE");?>
			<input id="activation_code" onkeypress="if (event.keyCode==13) {DoActivation(); return false;}" class="payroll-input" type="text" value = "" name="ACTIVATION_CODE"><br>
			<input type="hidden" name="ACTIONTYPE" value="ACTIVATION">
			<input type="hidden" name="GETDATA" value="Y">
			<?php if (!$arResult["NEED_ACTIVATION"]):?>
				<span class="bottom-span" style="float:left"><a href="<?=$arResult["PAYROLL_URL"];?>"><?=GetMessage("TO_PAYROLL_FORM");?></a></span>
			<?php endif;?>
			<span class="bottom-span" style="float:right">			
				<span class="popup-window-button">
					<span class="popup-window-button-left"></span>
					<span class="popup-window-button-text" onclick="DoActivation()"><?=GetMessage("GET_PIN")?></span>
					<span class="popup-window-button-right"></span>
				</span>
			</span>		
		</form>
	</div>
</div>
<?php endif;?>
<script>
	
function DoActivation()
{
	var arCode = document.getElementsByName('ACTIVATION_CODE')[0].value;
	if (arCode == "")
	{
		alert("<?=GetMessage("ACTIVATION_CODE_TYPE_PLEASE")?>");
		return;
	}
	document.forms["activation_form"].submit();
}
</script>
