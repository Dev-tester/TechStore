<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php CUtil::InitJSCore();?>
<?php if (!empty($arResult["ITEMS"])):?>

<div class="learn-course-tree">
	<ul>

	<?php

		$bracketLevel = 0;
		foreach ($arResult["ITEMS"] as $arItem):
			if ( $arItem["DEPTH_LEVEL"] <= $bracketLevel )
			{
				$deltaLevel = $bracketLevel - $arItem['DEPTH_LEVEL'] + 1;
				echo str_repeat("</ul></li>", $deltaLevel);
				$bracketLevel -= $deltaLevel;
			}

		if ($arItem["TYPE"] == "CH"):
			$bracketLevel++;
			?>
			<li class="tree-item tree-item-chapter <?php if($arItem["CHAPTER_OPEN"] === false):?> tree-item-closed<?php endif?>">
				<div class="tree-item-wrapper<?php if($arItem["SELECTED"] === true):?> tree-item-selected<?php endif?>" onmouseover="BX.addClass(this, 'tree-item-hover'); BX.PreventDefault(event);" onmouseout="BX.removeClass(this, 'tree-item-hover')">
					<b class="r0"></b>
					<div class="tree-item-text">
						<div class="chapter" onclick="JMenu.OpenChapter(this,'<?=$arItem["ID"]?>')"></div>
						<a class="tree-item-link" hidefocus="true" href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a>
					</div>
					<b class="r0"></b>
				</div>
				<ul>
		<?php elseif($arItem["TYPE"] == "LE"):?>
			<li class="tree-item tree-lesson">
				<div class="tree-item-wrapper<?php if($arItem["SELECTED"]):?> tree-item-selected<?php endif?>" onmouseover="BX.addClass(this, 'tree-item-hover'); BX.PreventDefault(event);" onmouseout="BX.removeClass(this, 'tree-item-hover'); BX.PreventDefault(event);">
					<b class="r0"></b>
					<div class="tree-item-text">
						<div class="lesson" onclick="window.location=BX.findNextSibling(this, { className : 'tree-item-link'}).href"></div>
						<a class="tree-item-link" hidefocus="true" href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a>
					</div>
					<b class="r0"></b>
				</div>
			</li>
		<?php elseif($arItem["TYPE"] == "CD"):?>
			<li class="tree-item tree-item-course">
				<div class="tree-item-wrapper<?php if($arItem["SELECTED"]):?> tree-item-selected<?php endif?>" onmouseover="BX.addClass(this, 'tree-item-hover'); BX.PreventDefault(event);" onmouseout="BX.removeClass(this, 'tree-item-hover'); BX.PreventDefault(event);">
					<b class="r0"></b>
					<div class="tree-item-text">
						<div class="course-detail"></div>
						<a class="tree-item-link" hidefocus="true" href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a>
					</div>
					<b class="r0"></b>
				</div>
			</li>
		<?php elseif($arItem["TYPE"] == "TL"):?>
			<li class="tree-item tree-item-tests">
				<div class="tree-item-wrapper<?php if($arItem["SELECTED"]):?> tree-item-selected<?php endif?>" onmouseover="BX.addClass(this, 'tree-item-hover'); BX.PreventDefault(event);" onmouseout="BX.removeClass(this, 'tree-item-hover'); BX.PreventDefault(event);">
					<b class="r0"></b>
					<div class="tree-item-text">
						<div class="test-list"></div>
						<a class="tree-item-link" hidefocus="true" href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a>
					</div>	
					<b class="r0"></b>
				</div>
			</li>
		<?php endif?>

	<?php endforeach?>

	</ul>
</div>

<script type="text/javascript">
	var JMenu = new JCMenu('<?=(array_key_exists("LEARN_MENU_".$arParams["COURSE_ID"],$_COOKIE ) ? CUtil::JSEscape($_COOKIE["LEARN_MENU_".$arParams["COURSE_ID"]]) :"")?>', '<?=$arParams["COURSE_ID"]?>');
</script>

<?php endif?>