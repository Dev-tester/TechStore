<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if ($arResult["ShowReady"]=="Y" || $arResult["ShowDelay"]=="Y" || $arResult["ShowSubscribe"]=="Y" || $arResult["ShowNotAvail"]=="Y")
{
	foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
		$arHeader["name"] = (isset($arHeader["name"]) ? (string)$arHeader["name"] : '');
		if ($arHeader["name"] == '')
		{
			$arResult["GRID"]["HEADERS"][$id]["name"] = GetMessage("SALE_".$arHeader["id"]);
			if(strlen($arResult["GRID"]["HEADERS"][$id]["name"])==0)
				$arResult["GRID"]["HEADERS"][$id]["name"] = GetMessage("SALE_".str_replace("_FORMATED", "", $arHeader["id"]));
		}
	endforeach;

?><table class="sale_basket_small"><?php 
	if ($arResult["ShowReady"]=="Y")
	{
		?><tr><td align="center"><?php  echo GetMessage("TSBS_READY"); ?></td></tr>
		<tr><td><ul><?php 
		foreach ($arResult["ITEMS"]["AnDelCanBuy"] as &$v)
		{
			?><li><?php 
			foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader)
			{
				if(isset($v[$arHeader['id']]) && !empty($v[$arHeader['id']]))
				{
					if(in_array($arHeader['id'], array("NAME")))
					{
						if ('' != $v["DETAIL_PAGE_URL"])
						{
							?><a href="<?php echo $v["DETAIL_PAGE_URL"]; ?>"><b><?php echo $v[$arHeader['id']]?></b></a><br /><?php 
						}
						else
						{
							?><b><?php echo $v[$arHeader['id']]?></b><br /><?php 
						}
					}
					else if(in_array($arHeader['id'], array("PRICE_FORMATED")))
					{
						?><?= $arHeader['name']?>:&nbsp;<b><?php echo $v[$arHeader['id']]?></b><br /><?php 
					}
					else if(in_array($arHeader['id'], ["DETAIL_PICTURE", "PREVIEW_PICTURE"]) && !empty($v[$arHeader['id']."_SRC"]))
					{
						?><?= $arHeader['name']?>:&nbsp;<br/><img src="<?php echo $v[$arHeader['id']."_SRC"]?>"><br/><?php 
					}
					else
					{
						?><?= $arHeader['name']?>:&nbsp;<?php echo $v[$arHeader['id']]?><br /><?php 
					}
				}
			}
			?></li><?php 
		}
		if (isset($v))
			unset($v);
		?></ul></td></tr><?php 
		if ('' != $arParams["PATH_TO_BASKET"])
		{
			?><tr><td align="center"><a href="<?=$arParams["PATH_TO_BASKET"]?>"><?= GetMessage("TSBS_2BASKET") ?></a>
			</td></tr><?php 
		}
		if ('' != $arParams["PATH_TO_ORDER"])
		{
			?><tr><td align="center"><a href="<?=$arParams["PATH_TO_ORDER"]?>"><?= GetMessage("TSBS_2ORDER") ?></a>
			</td></tr><?php 
		}
	}
	if ($arResult["ShowDelay"]=="Y")
	{
		?><tr><td align="center"><?= GetMessage("TSBS_DELAY") ?></td></tr>
		<tr><td><ul>
		<?php 
		foreach ($arResult["ITEMS"]["DelDelCanBuy"] as &$v)
		{
			?><li><?php 
			foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader)
			{
				if(isset($v[$arHeader['id']]) && !empty($v[$arHeader['id']]))
				{
					if(in_array($arHeader['id'], array("NAME")))
					{
						if ('' != $v["DETAIL_PAGE_URL"])
						{
							?><a href="<?php echo $v["DETAIL_PAGE_URL"]; ?>"><b><?php echo $v[$arHeader['id']]?></b></a><br /><?php 
						}
						else
						{
							?><b><?php echo $v[$arHeader['id']]?></b><br /><?php 
						}
					}
					else if(in_array($arHeader['id'], array("PRICE_FORMATED")))
					{
						?><?= $arHeader['name']?>:&nbsp;<b><?php echo $v[$arHeader['id']]?></b><br /><?php 
					}
					else if(in_array($arHeader['id'], ["DETAIL_PICTURE", "PREVIEW_PICTURE"]) && !empty($v[$arHeader['id']."_SRC"]))
					{
						?><?= $arHeader['name']?>:&nbsp;<br/><img src="<?php echo $v[$arHeader['id']."_SRC"]?>"><br/><?php 
					}
					else
					{
						?><?= $arHeader['name']?>:&nbsp;<?php echo $v[$arHeader['id']]?><br /><?php 
					}
				}
			}
			?></li><?php 
		}
		if (isset($v))
			unset($v);
		?></ul></td></tr><?php 
		if ('' != $arParams["PATH_TO_BASKET"])
		{
			?><tr><td align="center"><a href="<?=$arParams["PATH_TO_BASKET"]?>"><?= GetMessage("TSBS_2BASKET") ?></a>
			</td></tr><?php 
		}
	}
	if ($arResult["ShowSubscribe"]=="Y")
	{
		?><tr><td align="center"><?= GetMessage("TSBS_SUBSCRIBE") ?></td></tr>
		<tr><td><ul><?php 
		foreach ($arResult["ITEMS"]["ProdSubscribe"] as &$v)
		{
			?><li><?php 
			foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader)
			{
				if(isset($v[$arHeader['id']]) && !empty($v[$arHeader['id']]))
				{
					if(in_array($arHeader['id'], array("NAME")))
					{
						if ('' != $v["DETAIL_PAGE_URL"])
						{
							?><a href="<?php echo $v["DETAIL_PAGE_URL"]; ?>"><b><?php echo $v[$arHeader['id']]?></b></a><br /><?php 
						}
						else
						{
							?><b><?php echo $v[$arHeader['id']]?></b><br /><?php 
						}
					}
					else if(in_array($arHeader['id'], array("PRICE_FORMATED")))
					{
						?><?= $arHeader['name']?>:&nbsp;<b><?php echo $v[$arHeader['id']]?></b><br /><?php 
					}
					else if(in_array($arHeader['id'], ["DETAIL_PICTURE", "PREVIEW_PICTURE"]) && !empty($v[$arHeader['id']."_SRC"]))
					{
						?><?= $arHeader['name']?>:&nbsp;<br/><img src="<?php echo $v[$arHeader['id']."_SRC"]?>"><br/><?php 
					}
					else
					{
						?><?= $arHeader['name']?>:&nbsp;<?php echo $v[$arHeader['id']]?><br /><?php 
					}
				}
			}
			?></li><?php 
		}
		if (isset($v))
			unset($v);
		?></ul></td></tr><?php 
	}
	if ($arResult["ShowNotAvail"]=="Y")
	{
		?><tr><td align="center"><?= GetMessage("TSBS_UNAVAIL") ?></td></tr>
		<tr><td><ul><?php 
		foreach ($arResult["ITEMS"]["nAnCanBuy"] as &$v)
		{
			?><li><?php 
			foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader)
			{
				if(isset($v[$arHeader['id']]) && !empty($v[$arHeader['id']]))
				{
					if(in_array($arHeader['id'], array("NAME")))
					{
						if ('' != $v["DETAIL_PAGE_URL"])
						{
							?><a href="<?php echo $v["DETAIL_PAGE_URL"]; ?>"><b><?php echo $v[$arHeader['id']]?></b></a><br /><?php 
						}
						else
						{
							?><b><?php echo $v[$arHeader['id']]?></b><br /><?php 
						}
					}
					else if(in_array($arHeader['id'], array("PRICE_FORMATED")))
					{
						?><?= $arHeader['name']?>:&nbsp;<b><?php echo $v[$arHeader['id']]?></b><br /><?php 
					}
					else if(in_array($arHeader['id'], ["DETAIL_PICTURE", "PREVIEW_PICTURE"]) && !empty($v[$arHeader['id']."_SRC"]))
					{
						?><?= $arHeader['name']?>:&nbsp;<br/><img src="<?php echo $v[$arHeader['id']."_SRC"]?>"><br/><?php 
					}
					else
					{
						?><?= $arHeader['name']?>:&nbsp;<?php echo $v[$arHeader['id']]?><br /><?php 
					}
				}
			}
			?></li><?php 
		}
		if (isset($v))
			unset($v);
		?></ul></td></tr><?php 
	}
	?></table><?php 
}
?>