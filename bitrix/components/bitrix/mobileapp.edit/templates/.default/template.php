<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(isset($arParams["HEAD"])):?>
	<div class="order_acceptpay_title"><?=$arParams["HEAD"]?></div>
<?php endif;?>

<?php if(!$arResult["SKIP_FORM"]):?>
	<form id="<?=$arResult["FORM_ID"]?>" name="<?=$arResult["FORM_NAME"]?>" enctype="multipart/form-data" action="<?=$arResult["FORM_ACTION"]?>" method="POST">
<?php endif;?>

	<?php if(is_array($arParams["DATA"])):?>
		<?php foreach ($arParams["DATA"] as $arField):?>
				<?=CAdminMobileEdit::getFieldHtml($arField)?>
		<?php endforeach;?>
	<?php endif;?>

<?php if(!$arResult["SKIP_FORM"]):?>
	</form>
<?php endif;?>

<?php if(isset($arParams["TITLE"])):?>
	<script type="text/javascript">
		app.setPageTitle({title: "<?=$arParams["TITLE"]?>"});
	</script>
<?php endif;?>

<?php if(isset($arParams["BUTTONS"]) && is_array($arParams["BUTTONS"])):?>
	<script type="text/javascript">
	<?php if(in_array("SAVE", $arParams["BUTTONS"])):?>
		app.addButtons({
			saveButton:
			{
				type: "right_text",
				style: "custom",
				name: "<?=GetMessage('MAPP_ME_BUTT_SAVE')?>",
				callback: function()
				{
					var form = BX("<?=$arResult["FORM_ID"]?>");

					if(form)
					{
						<?php if(isset($arParams["ON_JS_CLICK_SUBMIT_BUTTON"])):?>
							if(typeof window["<?=$arParams["ON_JS_CLICK_SUBMIT_BUTTON"]?>"] == "function")
								window["<?=$arParams["ON_JS_CLICK_SUBMIT_BUTTON"]?>"](form);
						<?php else:?>
							<?php if(isset($arResult["ON_BEFORE_FORM_SUBMIT"])):?>
								app.onCustomEvent("<?=$arResult["ON_BEFORE_FORM_SUBMIT"]?>");
								BX.onCustomEvent("<?=$arResult["ON_BEFORE_FORM_SUBMIT"]?>");
							<?php endif;?>
							form.submit();
						<?php endif;?>
					}
				}
			}
		});
	<?php endif;?>
	</script>
<?php endif;?>