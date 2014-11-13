<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!function_exists("checkQuantity"))
{
	function checkQuantity($arBasketItem, $desiredQuantity)
	{
		$arResult = array();

		/** @var $productProvider IBXSaleProductProvider */
		if ($productProvider = CSaleBasket::GetProductProvider($arBasketItem))
		{
			$arFieldsTmp = $productProvider::GetProductData(array(
				"PRODUCT_ID" => $arBasketItem["PRODUCT_ID"],
				"QUANTITY"   => $desiredQuantity,
				"RENEWAL"    => "N",
				"USER_ID"    => $userId,
				"SITE_ID"    => $siteId,
				"CHECK_QUANTITY" => "Y"
			));
		}
		elseif (isset($arBasketItem["CALLBACK_FUNC"]) && strlen($arBasketItem["CALLBACK_FUNC"]) > 0)
		{
			$arFieldsTmp = CSaleBasket::ExecuteCallbackFunction(
				$arBasketItem["CALLBACK_FUNC"],
				$arBasketItem["MODULE"],
				$arBasketItem["PRODUCT_ID"],
				$desiredQuantity,
				"N",
				$userId,
				$siteId
			);
		}
		else
		{
			return $arResult;
		}

		if (empty($arFieldsTmp) || !isset($arFieldsTmp["QUANTITY"]))
		{
			$arResult["ERROR"] = GetMessage("SBB_PRODUCT_NOT_AVAILABLE", array("#PRODUCT#" => $arBasketItem["NAME"]));
		}
		else if ($desiredQuantity > doubleval($arFieldsTmp["QUANTITY"]))
		{
			$arResult["ERROR"] = GetMessage("SBB_PRODUCT_NOT_ENOUGH_QUANTITY", array("#PRODUCT#" => $arBasketItem["NAME"], "#NUMBER#" => $desiredQuantity));
		}

		return $arResult;
	}
}

if (!function_exists("getProductByProps"))
{
	function getProductByProps($iblockID, $arSkuProps)
	{
		$result = false;
		$arSelect = array();
		$arOfFilter = array(
			"IBLOCK_ID" => $iblockID,
		);

		$rsProps = CIBlockProperty::GetList(
			array('SORT' => 'ASC', 'ID' => 'ASC'),
			array('ACTIVE' => 'Y', 'IBLOCK_ID' => $iblockID)
		);
		while ($arProp = $rsProps->Fetch())
		{
			if (in_array($arProp["CODE"], array_keys($arSkuProps)))
			{
				if ($arProp["CODE"] == "CML2_LINK" || ($arProp['PROPERTY_TYPE'] == 'S' && $arProp['USER_TYPE'] == 'directory'))
				{
					$arOfFilter["PROPERTY_".$arProp["CODE"]] = $arSkuProps[$arProp["CODE"]];
				}
				elseif ($arProp["PROPERTY_TYPE"] == "L" || $arProp['PROPERTY_TYPE'] == 'E')
				{
					$arOfFilter["PROPERTY_".$arProp["CODE"]."_VALUE"] = $arSkuProps[$arProp["CODE"]];
				}

				$arSelect[] = "PROPERTY_".$arProp["CODE"];
			}
		}

		$rsOffers = CIBlockElement::GetList(
			array(),
			$arOfFilter,
			false,
			false,
			array_merge(array("ID"), $arSelect)
		);
		if ($arOffer = $rsOffers->GetNext())
			$result = $arOffer["ID"];

		return $result;
	}
}

?>