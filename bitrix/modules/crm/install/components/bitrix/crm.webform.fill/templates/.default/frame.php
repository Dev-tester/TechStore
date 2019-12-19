<?php
use Bitrix\Main\Context;
use Bitrix\Main\Text\HtmlFilter;
use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var \CMain $APPLICATION */

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<?php $APPLICATION->ShowHead();?>
		<style type="text/css">
			.content-wrap {
				padding: 10px 0 10px 0;
			}
			.content {
				margin: 0;
			}
		</style>
	</head>
	<body class="<?php $APPLICATION->showProperty("BodyClass")?> crm-webform-iframe">
		<div class="content-wrap">
			<div class="content">
				<?php if(!empty($arResult['ERRORS'])):
					foreach($arResult['ERRORS'] as $error)
					{
						ShowError($error);
					}
					?>
					<script>
						BX.ready(function(){
							BX.CrmWebForm = new CrmWebForm({});
						});
					</script>
					<?php 
				else:
					$this->getComponent()->includeWebFormTemplate();
				endif;?>
			</div>
		</div>
	</body>
</html>
<?