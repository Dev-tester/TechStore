<?php 
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

/** @var array $arResult*/
/** @var \CBitrixComponentTemplate $this*/

$frame = $this->createFrame()->begin("");

if ($arResult["IS_AVAILABLE"]):
	\Bitrix\Main\UI\Extension::load("ui.tour");
?>


<script>
	BX.ready(function() {
		try
		{
			<?php 
			if ($arParams["AUTO_START"])
			{
				if ($arParams["AUTO_START_TIMEOUT"] > 0)
				{
					?>setTimeout(function () {
						BX.UI.Tour.Manager.add(<?=CUtil::phpToJsObject($arResult["OPTIONS"])?>);
					}, <?=$arParams["AUTO_START_TIMEOUT"]?>);<?php 
				}
				else
				{
					?>
					BX.UI.Tour.Manager.add(<?=CUtil::phpToJsObject($arResult["OPTIONS"])?>);<?php 
				}
			}
			else
			{
				?>BX.UI.Tour.Manager.create(<?=CUtil::phpToJsObject($arResult["OPTIONS"])?>);<?php 
			}
			?>
		}
		catch (e)
		{

		}
	});
</script>

<?php 
endif;
$frame->end();