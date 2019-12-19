<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$uid = $arParams["UID"];

if (!empty($arResult["ERROR_MESSAGE"])):?>
<div class="vote-note-box vote-note-error">
	<div class="vote-note-box-text"><?=ShowError($arResult["ERROR_MESSAGE"])?></div>
</div>
<?php endif;

if (empty($arResult["VOTE"]) || empty($arResult["QUESTIONS"]) ):
	return true;
endif;

?>
	<ol class="bx-vote-question-list" id="vote-<?=$uid?>">
	<?php foreach ($arResult["QUESTIONS"] as $arQuestion):?>
		<li id="question<?=$arQuestion["ID"]?>"<?php if($arQuestion["REQUIRED"]=="Y"): ?> class="bx-vote-question-required"<?php  endif; ?>>
			<?php if (!empty($arQuestion["IMAGE"]) && !empty($arQuestion["IMAGE"]["SRC"])): ?><div class="bx-vote-question-image"><img src="<?=$arQuestion["IMAGE"]["SRC"]?>" /></div><?php  endif; ?>
			<div class="bx-vote-question-title"><?=$arQuestion["QUESTION"]?></div>
				<table class="bx-vote-answer-list" cellspacing="0">
				<?php foreach ($arQuestion["ANSWERS"] as $arAnswer):?>
				<tr id="answer<?=$arAnswer["ID"]?>" class="bx-vote-answer-item">
					<td>
						<div class="bx-vote-bar"><?php 
				?><span class="bx-vote-block-input-wrap"><?php 
					?><span class="bx-vote-block-input"></span><?php 
					?><label><?=$arAnswer["MESSAGE"]?></label><?php 
				?></span>
							<div class="bx-vote-result-bar" style="width:<?=$arAnswer["PERCENT"]?>%;"></div>
						</div>
					</td>
					<td>
						<span class="bx-vote-voted-users-wrap"><?php 
							?><a href="javascript:void(0);" class="bx-vote-voted-users" rel="<?=$arAnswer["USERS"]?>" rev="<?=$arAnswer["ID"]?>"><?=$arAnswer["COUNTER"]?></a><?php 
						?></span>
					</td>
					<td><span class="bx-vote-data-percent"><?=$arAnswer["PERCENT"]?>%</span></td>
				</tr>
				<?php endforeach;?>
			</table>
		</li>
	<?php endforeach;?>
	</ol>
<?php 
$this->__component->arParams["RETURN"] = array(
	"lastVote" => $arResult["LAST_VOTE"]);
?>