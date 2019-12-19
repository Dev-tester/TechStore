<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?php if ($arParams['ALLOW_CLOSE'] == 'Y'):?><script type="text/javascript">if (null == window.phpVars) window.phpVars = {}; if (!window.phpVars.bitrix_sessid) window.phpVars.bitrix_sessid='<?=bitrix_sessid()?>';</script><?php endif;?>
<div class="bx-intranet-bnr" id="bx_intranet_bnr_<?php echo $arParams['ID']?>">
	<div class="bx-intranet-bnr-head"><?php if ($arParams['ALLOW_CLOSE'] == 'Y'):?><a href="javascript:void(0)" class="btn-close" onclick="BXIntrCloseBnr('<?php echo $arParams['ID']?>')" title="<?php echo htmlspecialcharsbx(GetMessage('INTR_BANNER_CLOSE'));?>"></a><?php endif;?></div>
	<div class="bx-intranet-bnr-body">
		<?php if ($arParams['ICON']):?>
			<a class="bx-intranet-bnr-icon <?php echo $arParams['ICON']?>"<?php if ($arParams['ICON_HREF']):?> href="<?php echo $arParams['ICON_HREF']?>"<?php endif;?>></a>
		<?php endif;?>
		<div class="bx-intranet-bnr-content<?php if ($arParams['ICON']):?> bx-intranet-bnr-margin<?php endif;?>">
			<?php echo $arParams['~CONTENT']?>
		</div>
		<div style="clear: both;"></div>
	</div>
</div>