<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<table class="learn-certificates-table data-table">
	<tr>
		<th width="20%"><?=GetMessage("LEARNING_MYCOURSES_CODE")?></th>
		<th><?=GetMessage("LEARNING_MYCOURSES_COURSE")?></th>
		<th width="10%"><?=GetMessage("LEARNING_MYCOURSES_RESULT")?></th>
		<th width="15%"><?=GetMessage("LEARNING_MYCOURSES_SCORE")?></th>
	</tr>

<?php if (!empty($arResult["COURSES"])):?>

<?php foreach($arResult["COURSES"] as $arCourse):?>
	<tr>
		<td><?=$arCourse["CODE"]?></td>
		<td><a href="<?=$arCourse["COURSE_DETAIL_URL"]?>"><?=$arCourse["NAME"]?></a></td>

		<?php if ($arCourse["COMPLETED"]):?>
			<td><?=GetMessage("LEARNING_MYCOURSES_YES")?></td>
			<td><?=$arResult["CERTIFICATES"][$arCourse["ID"]]["SUMMARY"]?> / <?=$arResult["CERTIFICATES"][$arCourse["ID"]]["MAX_SUMMARY"]?></td>
		<?php else:?>
			<td><?php if($arCourse["NO_TESTS"]):?><?=GetMessage("LEARNING_MYCOURSES_NO_TESTS")?><?php else:?><a href="<?=$arCourse["TESTS_LIST_URL"]?>"><?=GetMessage("LEARNING_MYCOURSES_NO")?></a><?php endif?></td>
			<td>0</td>
		<?php endif?>
				
	</tr>
<?php endforeach?>

<?php else:?>
	<tr>
		<td colspan="4">-&nbsp;<?=GetMessage("LEARNING_MYCOURSES_NO_DATA")?>&nbsp;-</td>
	</tr>
<?php endif?>
</table>