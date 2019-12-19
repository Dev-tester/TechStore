<?php  if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */

?>

<div class="bx-lists-interface-form">

	<script type="text/javascript">
		var bxForm_<?=$arParams["FORM_ID"]?> = null;
	</script>

	<?php if($arParams["SHOW_FORM_TAG"]):?>
		<form
			name="form_<?=$arParams["FORM_ID"]?>" id="form_<?=$arParams["FORM_ID"]?>"
			action="<?=POST_FORM_ACTION_URI?>" method="POST" enctype="multipart/form-data"
		>
		<?=bitrix_sessid_post();?>
		<input
			type="hidden" id="<?=$arParams["FORM_ID"]?>_active_tab" name="<?=$arParams["FORM_ID"]?>_active_tab"
			value="<?=htmlspecialcharsbx($arResult["SELECTED_TAB"])?>"
		>
	<?php  endif; ?>

	<div class="bx-lists-tabs-block">
		<?php  foreach($arResult["TABS"] as $tab): ?>
			<?php 
				$selected = ($tab["id"] == $arResult["SELECTED_TAB"]);
				$callback = '';
				if(strlen($tab['onselect_callback']))
				{
					$callback = trim($tab['onselect_callback']);
					if(!preg_match('#^[a-z0-9-_\.]+$#i', $callback))
						$callback = '';
				}
			?>
			<span
				title="<?=htmlspecialcharsbx($tab["title"])?>"
				class="bx-lists-tab <?=($selected ? "bx-lists-tab-active":"")?>" id="tab_cont_<?=$tab["id"]?>"
				onclick="<?php if(strlen($callback)):?><?=$callback?>('<?=$tab["id"]?>');<?php endif?>
					bxForm_<?=$arParams["FORM_ID"]?>.SelectTab('<?=$tab["id"]?>');"
			>
				<?=htmlspecialcharsbx($tab["name"])?>
			</span>
		<?php  endforeach ?>
	</div>

	<div class="bx-lists-tab-contents">
		<?php  foreach($arResult["TABS"] as $tab): ?>
			<?php  $selected = ($tab["id"] == $arResult["SELECTED_TAB"]); ?>
			<div
				id="bx-lists-tab-content_<?=$tab["id"]?>"
				class="bx-lists-tab-content <?=($selected ? "active":"")?>"
			>
				<table cellpadding="0" cellspacing="0" border="0" class="bx-lists-table-content <?=(isset($tab["class"]) ? $tab['class'] : '')?>" id="<?=$tab["id"]?>_edit_table">
					<?php 
					$i = 0;
					$cnt = count($tab["fields"]);
					$prevType = '';
					foreach($tab["fields"] as $field):

						if($field['type'] == 'file') : ?>
							<script>
								var wrappers = document.getElementsByClassName('bx-lists-input-file');
								for (var i = 0; i < wrappers.length; i++)
								{
									var inputs = wrappers[i].getElementsByTagName('input');
									for (var j = 0; j < inputs.length; j++)
									{
										inputs[j].onchange = getName;
									}
								}
								function getName ()
								{
									var str = this.value, i;
									if (str.lastIndexOf('\\'))
									{
										i = str.lastIndexOf('\\')+1;
									}
									else
									{
										i = str.lastIndexOf('/')+1;
									}
									str = str.slice(i);
									var uploaded = this.parentNode.parentNode.getElementsByClassName('fileformlabel')[0];
									uploaded.innerHTML = str;
								}
							</script>
						<?php  endif;

						$style = '';
						if(isset($field["show"]))
						{
							if($field["show"] == "N")
								$style = "display:none;";
						}

						$i++;
						if(!is_array($field))
							continue;

						$className = array();
						if($i == 1)
							$className[] = 'bx-top';
						if($i == $cnt)
							$className[] = 'bx-bottom';
						if($prevType == 'section')
							$className[] = 'bx-after-heading';

						if(strlen($field['class']))
							$className[] = $field['class'];
						?>
						<tr<?php if(!empty($className)):?> class="<?=implode(' ', $className)?>"<?php endif?><?php if(!empty($style)):?> style="<?= $style ?>"<?php endif?>>
							<?php 
							if($field["type"] == 'section'):
								?>
								<td colspan="2" class="bx-heading"><?=htmlspecialcharsbx($field["name"])?></td>
							<?php 
							else:
								$val = (isset($field["value"])? $field["value"] : $arParams["~DATA"][$field["id"]]);
								$valEncoded = htmlspecialcharsbx(htmlspecialcharsback($val));

								//default attributes
								if(!is_array($field["params"]))
									$field["params"] = array();
								if($field["type"] == '' || $field["type"] == 'text')
								{
									if($field["params"]["size"] == '')
										$field["params"]["size"] = "30";
								}
								elseif($field["type"] == 'textarea')
								{
									if($field["params"]["cols"] == '')
										$field["params"]["cols"] = "40";
									if($field["params"]["rows"] == '')
										$field["params"]["rows"] = "3";
								}
								elseif($field["type"] == 'date')
								{
									if($field["params"]["size"] == '')
										$field["params"]["size"] = "10";
								}

								$params = '';
								if(is_array($field["params"]) && $field["type"] <> 'file')
								{
									foreach($field["params"] as $p=>$v)
										$params .= ' '.$p.'="'.$v.'"';
								}

								if($field["colspan"] <> true):
									if($field["required"])
										$bWasRequired = true;
									?>
									<td class="bx-field-name<?php if($field["type"] <> 'label') echo' bx-padding'?>"<?php if($field["title"] <> '') echo ' title="'.htmlspecialcharsEx($field["title"]).'"'?>><?=($field["required"]? '<span class="required">*</span>':'')?><?php if(strlen($field["name"])):?><?=htmlspecialcharsEx($field["name"])?>:<?php endif?></td>
								<?php 
								endif
								?>
								<td class=""<?=($field["colspan"]? ' colspan="2"':'')?>>
									<?php 
									switch($field["type"]):
										case 'label':
										case 'custom':
											echo $val;
											break;
										case 'checkbox':
											?>
											<input type="hidden" name="<?=$field["id"]?>" value="N">
											<input type="checkbox" name="<?=$field["id"]?>" value="Y"<?=($val == "Y"? ' checked':'')?><?=$params?>>
											<?php 
											break;
										case 'textarea':
											?>
											<textarea name="<?=$field["id"]?>"<?=$params?>><?=$valEncoded?></textarea>
											<?php 
											break;
										case 'list':

											if(!empty($params)):
												$class = 'bx-lists-select-linking';
												$spanOne = '';
												$spanTwo = '';
											else:
												$spanOne = '<span class="bx-lists-select">';
												$spanTwo = '</span>';
											endif;

											if(is_array($field["items"])): ?>

												<?= $spanOne ?>
												<select name="<?=$field["id"]?>"<?=$params?>>

													<?php  if(!is_array($val))
														$val = array($val);

													foreach($field["items"] as $k=>$v): ?>
														<option value="<?=htmlspecialcharsbx($k)?>"<?=(in_array($k, $val)? ' selected':'')?>>
															<?=htmlspecialcharsbx($v)?></option>
													<?php  endforeach; ?>

												</select>
												<?= $spanTwo ?>

											<?php  endif;

											break;
										case 'file':
											?>
											<span class="file-wrapper">
												<span class="bx-lists-input-file">
													<span class="webform-small-button bx-lists-small-button"><?= Loc::getMessage('INTERFACE_FORM_FILE_ADD') ?></span>
													<input name="<?= $field['id'] ?>" size="<?= $field['params']['size'] ?>" type="file">
												</span>
												<span class="fileformlabel bx-lists-input-file-name"></span>
											</span>
											<?php 
											break;
										case 'date':
											?>
											<input class="bx-lists-input-calendar" value="<?=$valEncoded?>" type="text" name="<?= $field['id'] ?>"
												   onclick="BX.calendar({node: this.parentNode, field: this,bTime: true, bHideTime: false});">
											<span class="bx-lists-calendar-icon" onclick="BX.calendar({node:this, field:'<?= $field['id'] ?>', form: '',
												bTime: true, bHideTime: false});" onmouseover="BX.addClass(this, 'calendar-icon-hover');"
												  onmouseout="BX.removeClass(this, 'calendar-icon-hover');" border="0"></span>
											<?php 
											break;
										default:
											?>
											<input type="text" name="<?=$field["id"]?>" value="<?=$valEncoded?>"<?=$params?>>
											<?php 
											break;
									endswitch;
									?>
								</td>
							<?php endif?>
						</tr>
						<?php 
						$prevType = $field["type"];
					endforeach;
					?>
				</table>
			</div>
		<?php  endforeach ?>
	</div>

	<?php  if(isset($arParams["BUTTONS"])): ?>
		<div class="bx-lists-buttons-block">
			<?php if($arParams["~BUTTONS"]["standard_buttons"] !== false):?>
				<?php if($arParams["BUTTONS"]["back_url"] <> ''):?>
					<input
						type="submit" name="save" value="<?= Loc::getMessage("interface_form_save")?>"
						title="<?= Loc::getMessage("interface_form_save_title")?>"
						class="webform-small-button webform-small-button-accept"
					>
				<?php endif?>
				<input
					type="submit" name="apply" value="<?= Loc::getMessage("interface_form_apply")?>"
					title="<?= Loc::getMessage("interface_form_apply_title")?>"
					class="webform-small-button webform-small-button-cancel"
				>
				<?php if($arParams["BUTTONS"]["back_url"] <> ''):?>
					<a
						href="javascript:void(0)"
						class="bx-lists-cancel-button"
						onclick="window.location='<?= htmlspecialcharsbx(CUtil::addslashes($arParams["~BUTTONS"]["back_url"]))?>'"
						title="<?= Loc::getMessage("interface_form_cancel_title")?>"
					>
						<?= Loc::getMessage("interface_form_cancel")?>
					</a>
				<?php endif?>
			<?php endif?>
			<?=$arParams["~BUTTONS"]["custom_html"]?>
		</div>
	<?php endif?>

	<?php if($arParams["SHOW_FORM_TAG"]):?>
		</form>
	<?php endif?>

	<script type="text/javascript">
		var formSettingsDialog<?=$arParams["FORM_ID"]?>;

		bxForm_<?=$arParams["FORM_ID"]?> = new BxInterfaceForm('<?=$arParams["FORM_ID"]?>',
			<?=CUtil::PhpToJsObject(array_keys($arResult["TABS"]))?>);
		bxForm_<?=$arParams["FORM_ID"]?>.vars = <?=CUtil::PhpToJsObject($variables)?>;
	</script>

</div>