<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php 
echo '<'.'?xml version="1.0" encoding="utf-8" ?'.'>';

//We'll show at least one price
$priceCounter = 0;
foreach($arResult["ITEMS"] as $arItem)
{
	if (isset($arItem["PRICE"]) && isset($arItem["VALUES"]["MIN"]["VALUE"]) && isset($arItem["VALUES"]["MAX"]["VALUE"]))
		$priceCounter++;
}

if ($priceCounter > 0)
{
	$priceCounter = 1;//When price is showed
	$rangeCounter = 0;//Range propery will be skipped
	$totalCounter = 8;//Overall properties count
}
else
{
	$priceCounter = 0;//When price is NOT showed
	$rangeCounter = 1;//We can show no more than one range propery
	$totalCounter = 8;//Overall properties count
}

?>
<site
	xmlns="http://interactive-answers.webmaster.yandex.ru/schemas/site/0.0.1"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://interactive-answers.webmaster.yandex.ru/schemas/site/0.0.1  http://interactive-answers.webmaster.yandex.ru/schemas/site-0.0.1.xsd"
>
	<title><?php echo $arResult["SECTION_TITLE"]?></title>
	<description><?php echo $arResult["SECTION_DESCRIPTION"]?></description>
	<rootUrl><?php echo CHTTP::urn2uri("/");?></rootUrl>
	<resource>
		<fixed name="<?php echo CHTTP::urn2uri($arResult["FORM_ACTION"]);?>"/>
	</resource>
	<filters>
	<constant key="Y">
		<description caption="set_filter">
			<setParameter name="set_filter"/>
		</description>
	</constant>
	<?php foreach($arResult["HIDDEN"] as $arItem):?>
		<constant key="<?php echo $arItem["HTML_VALUE"]?>">
			<description caption="<?php echo $arItem["CONTROL_ID"]?>">
				<setParameter name="<?php echo $arItem["CONTROL_NAME"]?>"/>
			</description>
		</constant>
	<?php endforeach;?>
	<?php foreach($arResult["ITEMS"] as $arItem):?>
		<?php if($priceCounter && isset($arItem["PRICE"])):?>
			<?php if(isset($arItem["VALUES"]["MIN"]["VALUE"]) && isset($arItem["VALUES"]["MAX"]["VALUE"])):?>
				<?php $priceCounter--;$totalCounter--;?>
				<rangeFilter min="<?php echo floor($arItem["VALUES"]["MIN"]["VALUE"])?>" max="<?php echo ceil($arItem["VALUES"]["MAX"]["VALUE"])?>" step="1" <?php if (count($arItem["CURRENCIES"]) == 1) echo ' unit="'.current($arItem["CURRENCIES"]).'"';?>>
					<description caption="<?php echo $arItem["NAME"]?>">
						<setParameter name="<?php echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"/>
						<setParameter name="<?php echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"/>
					</description>
				</rangeFilter>
			<?php endif;?>
		<?php elseif($rangeCounter && $arItem["PROPERTY_TYPE"] == "N"):?>
			<?php if(isset($arItem["VALUES"]["MIN"]["VALUE"]) && isset($arItem["VALUES"]["MAX"]["VALUE"])):?>
				<?php $rangeCounter--;$totalCounter--;?>
				<rangeFilter min="<?php echo floor($arItem["VALUES"]["MIN"]["VALUE"])?>" max="<?php echo ceil($arItem["VALUES"]["MAX"]["VALUE"])?>" step="1" <?php if (count($arItem["CURRENCIES"]) == 1) echo ' unit="'.current($arItem["CURRENCIES"]).'"';?>>
					<description caption="<?php echo $arItem["NAME"]?>">
						<setParameter name="<?php echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"/>
						<setParameter name="<?php echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"/>
					</description>
				</rangeFilter>
			<?php endif;?>
		<?php elseif($totalCounter && !empty($arItem["VALUES"])):;?>
			<?php $totalCounter--;?>
			<dropDown>
				<description caption="<?php echo $arItem["NAME"]?>">
					<?php $ar = current($arItem["VALUES"])?>
					<setParameter name="<?php echo $ar["CONTROL_NAME_ALT"]?>"/>
				</description>
				<?php foreach($arItem["VALUES"] as $val => $ar):?>
					<dropDownValue key="<?php echo $ar["HTML_VALUE_ALT"]?>" caption="<?php echo $ar["VALUE"];?>"/>
				<?php endforeach;?>
			</dropDown>
		<?php endif;?>
	<?php endforeach;?>
	</filters>
</site>
