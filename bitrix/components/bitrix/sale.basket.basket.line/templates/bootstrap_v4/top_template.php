<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/**
 * @global array $arParams
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @global string $cartId
 */
$compositeStub = (isset($arResult['COMPOSITE_STUB']) && $arResult['COMPOSITE_STUB'] == 'Y');
?>
<div class="basket-line">
<?php if (!$compositeStub && $arParams['SHOW_AUTHOR'] == 'Y'):?>
	<div class="mb-1 basket-line-block">
		<?php if ($USER->IsAuthorized()):
			$name = trim($USER->GetFullName());
			if (! $name)
				$name = trim($USER->GetLogin());
			?>
			<a class="basket-line-block-icon-profile" href="<?=$arParams['PATH_TO_PROFILE']?>"><?=htmlspecialcharsbx($name)?></a>
			<a style='margin-right: 0;' href="?logout=yes"><?=GetMessage('TSB1_LOGOUT')?></a>
		<?php else:
			$arParamsToDelete = array(
				"login",
				"login_form",
				"logout",
				"register",
				"forgot_password",
				"change_password",
				"confirm_registration",
				"confirm_code",
				"confirm_user_id",
				"logout_butt",
				"auth_service_id",
				"clear_cache",
				"backurl",
			);

			$currentUrl = urlencode($APPLICATION->GetCurPageParam("", $arParamsToDelete));
			if ($arParams['AJAX'] == 'N')
			{
				?><script type="text/javascript"><?=$cartId?>.currentUrl = '<?=$currentUrl?>';</script><?php 
			}
			else
			{
				$currentUrl = '#CURRENT_URL#';
			}

			$pathToAuthorize = $arParams['PATH_TO_AUTHORIZE'];
			$pathToAuthorize .= (stripos($pathToAuthorize, '?') === false ? '?' : '&');
			$pathToAuthorize .= 'login=yes&backurl='.$currentUrl;
			?>
			<a class="basket-line-block-icon-profile" href="<?=$pathToAuthorize?>"><?=GetMessage('TSB1_LOGIN')?></a>
			<?php 
			if ($arParams['SHOW_REGISTRATION'] === 'Y')
			{
				$pathToRegister = $arParams['PATH_TO_REGISTER'];
				$pathToRegister .= (stripos($pathToRegister, '?') === false ? '?' : '&');
				$pathToRegister .= 'register=yes&backurl='.$currentUrl;
				?>
				<a style="margin-right: 0;" href="<?=$pathToRegister?>"><?=GetMessage('TSB1_REGISTER')?></a>
				<?php 
			}
			?>
		<?php endif?>
	</div>
<?php endif?>

	<div class="basket-line-block">
		<?php 
		if (!$arResult["DISABLE_USE_BASKET"])
		{
			?>
			<a class="basket-line-block-icon-cart" href="<?=$arParams['PATH_TO_BASKET']?>"><?=GetMessage('TSB1_CART')?></a>
			<?php 
		}

		if (!$compositeStub)
		{
			if ($arParams['SHOW_NUM_PRODUCTS'] == 'Y' && ($arResult['NUM_PRODUCTS'] > 0 || $arParams['SHOW_EMPTY_VALUES'] == 'Y'))
			{
				echo $arResult['NUM_PRODUCTS'].' '.$arResult['PRODUCT(S)'];

				if ($arParams['SHOW_TOTAL_PRICE'] == 'Y')
				{
					?>
					<br <?php  if ($arParams['POSITION_FIXED'] == 'Y'): ?>class="d-none d-block-sm"<?php  endif; ?>/>
					<span>
						<?=GetMessage('TSB1_TOTAL_PRICE')?> <strong><?=$arResult['TOTAL_PRICE']?></strong>
					</span>
					<?php 
				}
			}
		}

		if ($arParams['SHOW_PERSONAL_LINK'] == 'Y'):?>
			<div class="mt-1 basket-line-block">
				<a href="<?=$arParams['PATH_TO_PERSONAL']?>"><?=GetMessage('TSB1_PERSONAL')?></a>
			</div>
		<?php  endif ?>
	</div>
</div>