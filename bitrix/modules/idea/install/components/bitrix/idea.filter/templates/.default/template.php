<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @var array $arResult
 * @var array $arParams
 * @var CMain $APPLICATION
 */
?>
<div class="status-box-l">
	<div class="status-box-r">
		<div class="status-box-m">
			<?php foreach($arResult["STATUSES"] as $arStatus):?>
				<div class="status-item<?php if($arStatus["SELECTED"]):?>-selected<?php endif;?>">
					<div>
						<div>
							<a <?php if(!$arStatus["SELECTED"]):?>href="<?=$arStatus["URL"]?>"<?php endif;?>><?=$arStatus["VALUE"]?></a>
						</div>
					</div>
				</div>
			<?php endforeach;?>
			<br clear="both" />
		</div>
	</div>
</div>
<?php 
$arSort = array(
	"DATE_PUBLISH" => GetMessage("IDEA_SORT_BY_DATE_PUBLISH"),
	"RATING_TOTAL_VALUE" => GetMessage("IDEA_SORT_BY_RATING_TOTAL_VALUE"),
	"NUM_COMMENTS" => GetMessage("IDEA_SORT_BY_NUM_COMMENTS"),
);
?>
<div class="idea-sort-by-box">
	<div class="idea-sort-by-box2">
		<div class="idea-sort-by-box-body">
			<?php foreach($arSort as $Sort=>$SortName):?>
				<div class="idea-sort-by-link<?=$arResult["SORT_ORDER"]==$Sort?"-selected":""?>"><a href="<?=$APPLICATION->GetCurPageParam("order=".$Sort, array("order"))?>"><?=$SortName?></a></div>
			<?php endforeach;?>
			<div class="idea-sort-by-title"><?=GetMessage("IDEA_SORT_BY")?>:</div>
			<br clear="both" />
		</div>
	</div>
</div>