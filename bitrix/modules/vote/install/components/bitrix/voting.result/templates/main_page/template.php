<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (empty($arResult["VOTE"]) || empty($arResult["QUESTIONS"])):
	return true;
endif;
?>
<ol class="vote-items-list vote-question-list vote-question-list-main-page">
<?php 

$iCount = 0;
foreach ($arResult["QUESTIONS"] as $arQuestion):
	$iCount++;
?>
	<li class="vote-question-item <?=($iCount == 1 ? "vote-item-vote-first " : "")?><?php 
				?><?=($iCount == count($arResult["QUESTIONS"]) ? "vote-item-vote-last " : "")?><?php 
				?><?=($iCount%2 == 1 ? "vote-item-vote-odd " : "vote-item-vote-even ")?><?php 
				?>">
		<div class="vote-item-title vote-item-question"><?=$arQuestion["QUESTION"]?></div>
		<ol class="vote-items-list vote-answers-list">
<?php 
	foreach ($arQuestion["ANSWERS"] as $arAnswer):
?>
			<li class="vote-answer-item">
<?php 
		if ($arParams["THEME"] == ""):
?>
				<?=$arAnswer["MESSAGE"]?>
				<?php  if (isset($arResult['GROUP_ANSWERS'][$arAnswer['ID']])): 
						if (trim($arAnswer["MESSAGE"]) != '') 
							echo '&nbsp';
						echo '('.GetMessage('VOTE_GROUP_TOTAL') .')';
					endif; ?> - <?=$arAnswer["COUNTER"]?> (<?=$arAnswer["PERCENT"]?>%)<br />
				<div class="graph-bar" style="width: <?=$arAnswer["BAR_PERCENT"]?>%;background-color:#<?=htmlspecialcharsbx($arAnswer["COLOR"])?>">&nbsp;</div>
				<?php  if (isset($arResult['GROUP_ANSWERS'][$arAnswer['ID']])): ?>
					<?php  $arGroupAnswers = $arResult['GROUP_ANSWERS'][$arAnswer['ID']]; ?> 
					<?php foreach ($arGroupAnswers as $arGroupAnswer):?>
						</li>
						<li class="vote-answer-item">
							<?php  if (trim($arAnswer["MESSAGE"]) != '') { ?>
								<span class='vote-answer-lolight'><?=$arAnswer["MESSAGE"]?>:&nbsp;</span>
							<?php  } ?>
							<?=$arGroupAnswer["MESSAGE"]?> - <?=($arGroupAnswer["COUNTER"] > 0?'&nbsp;':'')?><?=$arGroupAnswer["COUNTER"]?> (<?=$arGroupAnswer["PERCENT"]?>%)<br />
							<div class="graph-bar" style="width: <?=$arGroupAnswer["PERCENT"]?>%;background-color:#<?=htmlspecialcharsbx($arAnswer["COLOR"])?>">&nbsp;</div>
					<?php endforeach?>
				<?php  endif; // GROUP_ANSWERS ?>
<?php 
		else:
?>
				<?=$arAnswer["MESSAGE"]?>
				<?php  if (isset($arResult['GROUP_ANSWERS'][$arAnswer['ID']])): 
						if (trim($arAnswer["MESSAGE"]) != '') 
							echo '&nbsp';
						echo '('.GetMessage('VOTE_GROUP_TOTAL') .')';
					endif; ?>
				<div class="graph">
					<nobr class="bar" style="width: <?=(round($arAnswer["BAR_PERCENT"]))?>%;">
						<span><?=$arAnswer["COUNTER"]?> (<?=$arAnswer["PERCENT"]?>%)</span>
					</nobr>
				</div>
				<?php  if (isset($arResult['GROUP_ANSWERS'][$arAnswer['ID']])): ?>
					<?php  $arGroupAnswers = $arResult['GROUP_ANSWERS'][$arAnswer['ID']]; ?> 
					<?php foreach ($arGroupAnswers as $arGroupAnswer):?>
						</li>
						<li class="vote-answer-item">
							<?php  if (trim($arAnswer["MESSAGE"]) != '') { ?>
								<span class='vote-answer-lolight'><?=$arAnswer["MESSAGE"]?>:&nbsp;</span>
							<?php  } ?>
							<?=$arGroupAnswer["MESSAGE"]?>
							<div class="graph">
								<nobr class="bar" style="width: <?=(round($arGroupAnswer["PERCENT"]))?>%;">
									<span><?=$arGroupAnswer["COUNTER"]?> (<?=$arGroupAnswer["PERCENT"]?>%)</span>
								</nobr>
							</div>
					<?php endforeach?>
				<?php  endif; // GROUP_ANSWERS ?>
<?php 
		endif;
?>
			</li>
<?php 
	endforeach; 
?>
		</ol>
	</li>
<?php 
endforeach; 
?>
</ol>