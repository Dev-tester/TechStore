<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult["ERROR_MESSAGE"])): 
?>
<div class="vote-note-box vote-note-error">
	<div class="vote-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"])?></div>
</div>
<?php 
endif;

if (!empty($arResult["OK_MESSAGE"])): 
?>
<div class="vote-note-box vote-note-note">
	<div class="vote-note-box-text"><?=ShowNote($arResult["OK_MESSAGE"])?></div>
</div>
<?php 
endif;

if (empty($arResult["VOTE"])):
	return true;
endif;

?>
<div class="voting-result-box">
	<ol class="vote-items-list voting-list-box">
		<li class="vote-item-vote vote-item-vote-first vote-item-vote-last vote-item-vote-odd <?php 
					?><?=($arVote["LAMP"]=="green" ? "vote-item-vote-active " : "")?><?php 
					?><?=($arVote["LAMP"]=="red" ? "vote-item-vote-disable " : "")?><?php 
					?>">
		<div class="vote-item-header">
<?php 
	if (strlen($arResult["VOTE"]["TITLE"]) > 0):
?>
			<span class="vote-item-title"><?=$arResult["VOTE"]["TITLE"];?></span>
<?php 
		if ($arResult["VOTE"]["LAMP"]=="green"):
/*?>
			<span class="vote-item-lamp vote-item-lamp-green">[ <span class="active"><?=GetMessage("VOTE_IS_ACTIVE_SMALL")?></span> ]</span>
<?php */
		elseif ($arResult["VOTE"]["LAMP"]=="red"):
?>
			<span class="vote-item-lamp vote-item-lamp-red">[ <span class="disable"><?=GetMessage("VOTE_IS_NOT_ACTIVE_SMALL")?></span> ]</span>
<?php 
		endif;
	endif;
?>
			<div class="vote-clear-float"></div>
		</div>
		
<?php 
	if ($arResult["VOTE"]["DATE_START"] || ($arResult["VOTE"]["DATE_END"] && $arResult["VOTE"]["DATE_END"] != "31.12.2030 23:59:59")):
?>
		<div class="vote-item-date">
<?php 
		if ($arResult["VOTE"]["DATE_START"]):
?>
			<span class="vote-item-date-start"><?=$arResult["VOTE"]["DATE_START"]?></span>
<?php 

		endif;
		if ($arResult["VOTE"]["DATE_END"] && $arResult["VOTE"]["DATE_END"]!="31.12.2030 23:59:59"):
			if ($arResult["VOTE"]["DATE_START"]):
?>
			<span class="vote-item-date-sep"> - </span>
<?php 
			endif;
?>
			<span class="vote-item-date-end"><?=$arResult["VOTE"]["DATE_END"]?></span>
<?php 
		endif;
?>
		</div> 
<?php 
	endif;
?>
		<div class="vote-item-counter"><span><?=GetMessage("VOTE_VOTES")?>:</span> <?=$arResult["VOTE"]["COUNTER"]?></div>

<?php 
	if (strlen($arResult["VOTE"]["TITLE"]) <= 0):
		if ($arResult["VOTE"]["LAMP"]=="green"):
?>
		<div class="vote-item-lamp vote-item-lamp-green"><span class="active"><?=GetMessage("VOTE_IS_ACTIVE")?></span></div>
<?php 
		elseif ($arResult["VOTE"]["LAMP"]=="red"):
?>
		<div class="vote-item-lamp vote-item-lamp-red"><span class="disable"><?=GetMessage("VOTE_IS_NOT_ACTIVE")?></span></div>
<?php 
		endif;
	endif;
	
	if ($arResult["VOTE"]["IMAGE"] !== false || !empty($arResult["VOTE"]["DESCRIPTION"])):
?>
		<div class="vote-item-footer">
<?php 
		if ($arResult["VOTE"]["IMAGE"] !== false):
?>
			<div class="vote-item-image">
				<img src="<?=$arResult["VOTE"]["IMAGE"]["SRC"]?>" width="<?=$arResult["VOTE"]["IMAGE"]["WIDTH"]?>" height="<?=$arResult["VOTE"]["IMAGE"]["HEIGHT"]?>" border="0" />
			</div>
<?php 
		endif;
	
		if (!empty($arResult["VOTE"]["DESCRIPTION"])):
?>
			<div class="vote-item-description"><?=$arResult["VOTE"]["DESCRIPTION"];?></div>
<?php 
		endif
?>
			<div class="vote-clear-float"></div>
		</div>
<?php 
	endif;
?>
<?php 
	if (!empty($arResult["QUESTIONS"])):
?>
		<ol class="vote-items-list vote-question-list">
<?php 
		$iCount = 0;
		foreach ($arResult["QUESTIONS"] as $arQuestion):
			$iCount++;
?>
			<li class="vote-question-item <?=($iCount == 1 ? "vote-item-vote-first " : "")?><?php 
						?><?=($iCount == count($arResult["QUESTIONS"]) ? "vote-item-vote-last " : "")?><?php 
						?><?=($iCount%2 == 1 ? "vote-item-vote-odd " : "vote-item-vote-even ")?><?php 
						?>">
				<div class="vote-item-header">

<?php 
			if ($arQuestion["IMAGE"] !== false):
?>
					<div class="vote-item-image"><img src="<?=$arQuestion["IMAGE"]["SRC"]?>" width="30" height="30" /></div>
<?php 
			endif;

?>
					<div class="vote-item-title vote-item-question"><?=$arQuestion["QUESTION"]?></div>
					<div class="vote-clear-float"></div>
				</div>

			<?php if ($arQuestion["DIAGRAM_TYPE"] == "circle"):?>

				<table width="100%">
					<tr>
						<td width="160"><img width="150" height="150" src="<?=$componentPath?>/draw_chart.php?qid=<?=$arQuestion["ID"]?>&dm=150" /></td>
						<td>
						<table class="vote-circle-table" style="<?=(LANG==LANGUAGE_ID ? "font-size:75%" : "" )?>">
								<?php foreach ($arQuestion["ANSWERS"] as $arAnswer):?>
									<tr>
										<td><div class="vote-bar-square" style="background-color:#<?=htmlspecialcharsbx($arAnswer["COLOR"])?>"></div></td>
										<td><nobr><?=$arAnswer["COUNTER"]?> (<?=$arAnswer["PERCENT"]?>%)</nobr></td>
										<td><?=htmlspecialcharsbx($arAnswer["~MESSAGE"])?></td>
									</tr>
								<?php endforeach?>
							</table>
						</td>
					</tr>
				</table>

			<?php else://histogram?>

				<table class="vote-answer-table">
				<?php foreach ($arQuestion["ANSWERS"] as $arAnswer):?>
					<tr class='vote-answer-row'>
						<td width="30%">
							<?=htmlspecialcharsbx($arAnswer["~MESSAGE"])?>
							<?php  if (isset($arResult['GROUP_ANSWERS'][$arAnswer['ID']])) 
							{
								if (trim($arAnswer["MESSAGE"]) != '') 
									echo "&nbsp;";
								echo '('.GetMessage('VOTE_GROUP_TOTAL').')';
							}
							?>
						&nbsp; </td>
						<td width="70%">
							<table class="vote-bar-table">
								<tr>
									<?php  $percent = round($arAnswer["BAR_PERCENT"] * 0.8); // (100% bar * 0.8) + (20% span counter) = 100% td ?>
									<td><div style="height:18px;float:left;width:<?=$percent?>%;background-color:#<?=htmlspecialcharsbx($arAnswer["COLOR"])?>"></div>
									<span style="line-height:18px;width:20%;float:left;" class="answer-counter"><nobr>&nbsp;<?=$arAnswer["COUNTER"]?> (<?=$arAnswer["PERCENT"]?>%)</nobr></span></td>
								</tr>
							</table>
						</td>
					</tr>
						<?php  if (isset($arResult['GROUP_ANSWERS'][$arAnswer['ID']])): ?>
						<?php  $arGroupAnswers = $arResult['GROUP_ANSWERS'][$arAnswer['ID']]; ?> 
								<?php foreach ($arGroupAnswers as $arGroupAnswer):?>
									<tr>
										<td width="30%">
											<?php  if (trim($arAnswer["MESSAGE"]) != '') { ?>
												<span class='vote-answer-lolight'>
													<?=htmlspecialcharsbx($arGroupAnswer["MESSAGE"])?>:&nbsp;</span>
											<?php  } ?>
											<?=htmlspecialcharsbx($arGroupAnswer["MESSAGE"])?></td>
										<td width="70%">
											<table class="vote-bar-table">
												<tr>
													<?php  $percent = round($arGroupAnswer["PERCENT"] * 0.8); // (100% bar * 0.8) + (20% span counter) = 100% td ?>
													<td><div class="vote-answer-bar" style="width:<?=$percent?>%;background-color:#<?=htmlspecialcharsbx($arAnswer["COLOR"])?>"></div>
													<span width="20%" class="vote-answer-counter"><nobr><?=$arGroupAnswer["COUNTER"]?> (<?=$arGroupAnswer["PERCENT"]?>%)</nobr></span></td>
												</tr>
											</table>
										</td>
									</tr>
								<?php endforeach?>
						<?php  endif; // USER_ANSWERS ?>
				<?php endforeach?>
				</table>

			<?php endif?>
			</li>
		<?php endforeach?>
		</ol>
	<?php endif?>
		</li>
	</ol>
</div>
