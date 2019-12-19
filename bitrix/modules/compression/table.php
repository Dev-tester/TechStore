<?php 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
IncludeModuleLangFile(__FILE__);

$Contents = ob_get_contents();
$Contents_len = defined('BX_UTF')? mb_strlen($Contents, 'latin1'): strlen($Contents);
$gzContents = gzcompress($Contents);
$gzContents_len = defined('BX_UTF')? mb_strlen($gzContents, 'latin1'): strlen($gzContents);

?>
<div style='margin:5px;'>
<table cellpadding='1' cellspacing='0' class='tableborder'>
	<tr>
		<td>
			<table cellpadding='3' cellspacing='1' class='tablebody'>
				<tr>
					<td nowrap colspan="2" class="tablebodytext"><b><?php echo GetMessage('LIBRARY')?></b></td>
				</tr>
				<tr>
					<td nowrap class="tablebodytext"><?php echo GetMessage("NOT_COMPRESSED")?></td>
					<td align='right' class="tablebodytext"><font color='green'><?php echo $Contents_len?></font></td>
				</tr>
				<tr>
					<td nowrap class="tablebodytext"><?php echo GetMessage("COMPRESSED")?></td>
					<td align='right' class="tablebodytext"><?php echo $gzContents_len?></td>
				</tr>
				<?php if($gzContents_len > 0):?>
				<tr>
					<td nowrap class="tablebodytext"><?php echo GetMessage("COEFFICIENT")?></td>
					<td align='right' class="tablebodytext"><?php echo round($Contents_len/$gzContents_len,2)?></td>
				</tr>
				<?php endif;?>
			</table>
		</td>
	</tr>
</table>
</div>
<div class="empty"></div>