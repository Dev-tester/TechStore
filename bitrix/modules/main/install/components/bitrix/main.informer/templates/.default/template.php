<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<script type="text/javascript">
	jsBXMI.Init(
		{
			'STEP': '<?php echo CUtil::JSEscape($arResult["informer"]["step"]);?>',		
			'STEPS': '<?php echo CUtil::JSEscape($arResult["informer"]["steps"]);?>',
			'ID': '<?php echo CUtil::JSEscape($arParams["ID"])?>',
			'TEXT': <?php echo CUtil::PhpToJSObject($arResult["text"])?>			
		}
	);
</script>
		
<div class="wd-infobox wd-info-banner"><?php 
	?><div class="wd-infobox-inner"><?php 
		?><div class="wd-info-banner-head"><?php 
			?><a href="#banner" class="btn-close" onclick="BXWdCloseBnr(this.parentNode.parentNode.parentNode);return false;" <?php 
				?>title="<?=GetMessage('WD_BANNER_CLOSE')?>"></a></div>
		<div class="wd-info-banner-body">
			<table cellpadding="0" border="0" class="wd-info-banner-body">
				<tr>
					<th class="wd-info-banner-icon" rowspan="2">
						<a class="wd-info-banner-icon"></a>
					</th>
					<td class="wd-info-banner-content">
						<div class="wd-info-banner-content" id="wd_informer_text">
							<?=$arResult["text"][$arResult["informer"]["step"]-1]?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="wd-info-banner-buttons"><?php 
					?><a href="#next" onclick="BXWdStepBnr(document.getElementById('wd_informer_text'), this.nextSibling, this, 'next'); <?php 
						?> return false;" class="bx-bnr-button" <?php 
						?><?=($arResult["informer"]["step"] >= $arResult["informer"]["steps"] ? "style='display:none;'" : "")?><?php 
						?>><?=GetMessage("WD_NEXT_ADVICE")?></a><?php 
					?><a href="#prev" onclick="BXWdStepBnr(document.getElementById('wd_informer_text'), this, this.previousSibling, 'prev'); <?php 
						?>return false;" class="bx-bnr-button" <?php 
						?><?=($arResult["informer"]["step"] <= 1 ? "style='display:none;'" : "")?><?php 
						?>><?=GetMessage("WD_PREV_ADVICE")?></a><?php 
					?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>