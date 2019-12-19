<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap',
		'TITLE' => 'bx_sitemap_title',
		'LIST' => 'catalog-section-list-list',
	),
	'LINE' => array(
		'TITLE' => 'catalog-section-list-item-title',
		'LIST' =>  'catalog-section-list-line-list mb-4',
		'EMPTY_IMG' => $this->GetFolder().'/images/line-empty.png'
	),
	'TEXT' => array(
		'TITLE' => 'catalog-section-list-item-title',
		'LIST' =>  'catalog-section-list-text-list mb-4'
	),
	'TILE' => array(
		'TITLE' => 'catalog-section-list-item-title',
		'LIST' =>  'catalog-section-list-tile-list row mb-4',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	)
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

?>
<div class="row mb-4">
	<div class="col">
		<?php  if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID'])
		{
			$this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
			$this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

			?><h2 class="mb-3" id="<?php  echo $this->GetEditAreaId($arResult['SECTION']['ID']); ?>" ><?php 
			echo (
			isset($arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != ""
				? $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]
				: $arResult['SECTION']['NAME']
			);
			?>
			</h2><?php 
		}

		if (0 < $arResult["SECTIONS_COUNT"])
		{
		?><ul class="<?php  echo $arCurView['LIST']; ?>"><?php 

			switch ($arParams['VIEW_MODE'])
			{
				case 'LINE':
					foreach ($arResult['SECTIONS'] as &$arSection)
					{
						$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
						$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

						if (false === $arSection['PICTURE'])
							$arSection['PICTURE'] = array(
								'SRC' => $arCurView['EMPTY_IMG'],
								'ALT' => (
									'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
									? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
									: $arSection["NAME"]
								),
								'TITLE' => (
									'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
									? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
									: $arSection["NAME"]
								)
							);
							?>
							<li id="<?php  echo $this->GetEditAreaId($arSection['ID']); ?>" class="catalog-section-list-item">
								<div class="catalog-section-list-line-img-container">
									<a
										href="<?php  echo $arSection['SECTION_PAGE_URL']; ?>"
										class="catalog-section-list-item-img"
										style="background-image:url('<?php  echo $arSection['PICTURE']['SRC']; ?>');"
										title="<?php  echo $arSection['PICTURE']['TITLE']; ?>"
									></a>
								</div>
								<div class="catalog-section-list-item-inner">
									<h3 class="catalog-section-list-item-title">
										<a class="catalog-section-list-item-link" href="<?php  echo $arSection['SECTION_PAGE_URL']; ?>">
											<?php  echo $arSection['NAME']; ?>
										</a>
										<?php  if ($arParams["COUNT_ELEMENTS"])
										{
											?>
											<span class="catalog-section-list-item-counter">(<?php  echo $arSection['ELEMENT_CNT']; ?>)</span>
											<?php 
										}
										?>
									</h3>
									<?php  if ('' != $arSection['DESCRIPTION'])
									{
										?>
										<p class="catalog-section-list-item-description"><?php  echo $arSection['DESCRIPTION']; ?></p>
										<?php 
									}
									?>
								</div>

						</li><?php 
					}
					unset($arSection);
					break;

				case 'TEXT':
					foreach ($arResult['SECTIONS'] as &$arSection)
					{
						$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
						$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

						?>
						<li id="<?php  echo $this->GetEditAreaId($arSection['ID']); ?>" class="catalog-section-list-item">
							<div class="catalog-section-list-item-inner">
								<h3 class="catalog-section-list-item-title">
									<a class="catalog-section-list-item-link" href="<?php  echo $arSection['SECTION_PAGE_URL']; ?>">
										<?php  echo $arSection['NAME']; ?>
									</a>
									<?php  if ($arParams["COUNT_ELEMENTS"])
									{
										?>
										<span class="catalog-section-list-item-counter">(<?php  echo $arSection['ELEMENT_CNT']; ?>)</span>
										<?php 
									}
									?>
								</h3>
							</div>
						</li><?php 
					}
					unset($arSection);
					break;

				case 'TILE':
					foreach ($arResult['SECTIONS'] as &$arSection)
					{
						$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
						$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

						if (false === $arSection['PICTURE'])
							$arSection['PICTURE'] = array(
								'SRC' => $arCurView['EMPTY_IMG'],
								'ALT' => (
									'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
									? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
									: $arSection["NAME"]
								),
								'TITLE' => (
									'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
									? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
									: $arSection["NAME"]
								)
							);
							?>
							<li id="<?php  echo $this->GetEditAreaId($arSection['ID']); ?>"  class="col-6 col-sm-3 col-md-2 catalog-section-list-item">
								<div class="catalog-section-list-tile-img-container">
									<a
										href="<?php  echo $arSection['SECTION_PAGE_URL']; ?>"
										class="catalog-section-list-item-img"
										style="background-image:url('<?php  echo $arSection['PICTURE']['SRC']; ?>');"
										title="<?php  echo $arSection['PICTURE']['TITLE']; ?>"
										></a>
								</div>
								<?php  if ('Y' != $arParams['HIDE_SECTION_NAME'])
								{
									?>
										<div class="catalog-section-list-item-inner">
											<h3 class="catalog-section-list-item-title">
												<a class="catalog-section-list-item-link" href="<?php  echo $arSection['SECTION_PAGE_URL']; ?>">
													<?php  echo $arSection['NAME']; ?>
												</a>
												<?php  if ($arParams["COUNT_ELEMENTS"])
												{
													?>
													<span class="catalog-section-list-item-counter">(<?php  echo $arSection['ELEMENT_CNT']; ?>)</span>
													<?php 
												}
												?>
											</h3>
										</div>
									<?php 
								}
								?>
							</li>
						<?php 
					}
					unset($arSection);
					break;

				case 'LIST':
					$intCurrentDepth = 1;
					$boolFirst = true;
					foreach ($arResult['SECTIONS'] as &$arSection)
					{
						$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
						$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

						if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL'])
						{
							if (0 < $intCurrentDepth)
								echo "\n",str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']),'<ul>';
						}
						elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL'])
						{
							if (!$boolFirst)
								echo '</li>';
						}
						else
						{
							while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL'])
							{
								echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
								$intCurrentDepth--;
							}
							echo str_repeat("\t", $intCurrentDepth-1),'</li>';
						}

						echo (!$boolFirst ? "\n" : ''),str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']);
						?>
						<li class="catalog-section-list-item" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
							<h3 class="catalog-section-list-list-title">
								<a class="catalog-section-list-list-link" href="<?php  echo $arSection["SECTION_PAGE_URL"]; ?>"><?php  echo $arSection["NAME"];?><?php 
									if ($arParams["COUNT_ELEMENTS"])
									{
										?> <span>(<?php  echo $arSection["ELEMENT_CNT"]; ?>)</span><?php 
									}
									?>
								</a>
							</h3>
						<?php 

						$intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];
						$boolFirst = false;
					}
					unset($arSection);
					while ($intCurrentDepth > 1)
					{
						echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
						$intCurrentDepth--;
					}
					if ($intCurrentDepth > 0)
					{
						echo '</li>',"\n";
					}
					break;
			}
			?></ul><?php 
		}
		?>
	</div>
</div>
