<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->addExternalCss(SITE_TEMPLATE_PATH."/css/sidebar.css");

$this->setFrameMode(true);

if(empty($arResult))
	return;

$this->SetViewTarget("sidebar", 250);
?>

<div class="sidebar-widget sidebar-widget-popular">
	<div class="sidebar-widget-top">
		<div class="sidebar-widget-top-title"><?=GetMessage("BLOG_WIDGET_TITLE")?></div>
	</div>
	<?php 
	$i = 0;
	foreach($arResult as $arPost):
	?>
	<a href="<?=$arPost["urlToPost"]?>" class="sidebar-widget-item<?php if(++$i == count($arResult)):?> widget-last-item<?php endif?>">
		<span class="user-avatar user-default-avatar"
			<?php if (isset($arPost["AVATAR_file"]["src"])):?>
				style="background:url('<?=$arPost["AVATAR_file"]["src"]?>') no-repeat center; background-size: cover;"
			<?php endif?>>
		</span>
		<span class="sidebar-user-info">
			<span class="user-post-name"><?=$arPost["AuthorName"]?></span>
			<span class="user-post-title"><?=htmlspecialcharsbx($arPost["TITLE"])?></span>
		</span>
	</a>
	<?php endforeach?>
</div>



