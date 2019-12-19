<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div class="order_acceptpay_infoblock">

	<?php if($arResult["TITLE"]):?>
		<div class="order_acceptpay_infoblock_title"><?=$arResult["TITLE"]?></div>
	<?php endif;?>

	<?php require($_SERVER['DOCUMENT_ROOT'] . $templateFolder.'/nowrap.php')?>

</div>