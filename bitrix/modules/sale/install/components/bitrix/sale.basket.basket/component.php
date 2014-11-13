<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("sale"))
{
	ShowError(GetMessage("SALE_MODULE_NOT_INSTALL"));
	return;
}

include(dirname(__FILE__)."/functions.php");

$arResult["WARNING_MESSAGE"] = array();

$headersData = $this->getCustomColumns(); // custom product table columns

$GLOBALS['CATALOG_ONETIME_COUPONS_BASKET'] = null;
$GLOBALS['CATALOG_ONETIME_COUPONS_ORDER'] = null;

// BASKET REFRESH
if (strlen($_REQUEST["BasketRefresh"]) > 0 || strlen($_REQUEST["BasketOrder"]) > 0 || strlen($_REQUEST["action"]) > 0 )
{
	// tmp hack until ajax recalculation is made
	if (isset($_REQUEST["BasketRefresh"]) && strlen($_REQUEST["BasketRefresh"]) > 0)
		unset($_REQUEST["BasketOrder"]);

	if (strlen($_REQUEST["action"]) > 0)
	{
		$id = intval($_REQUEST["id"]);
		if ($id > 0)
		{
			$dbBasketItems = CSaleBasket::GetList(
				array(),
				array(
					"FUSER_ID" => CSaleBasket::GetBasketUserID(),
					"LID" => SITE_ID,
					"ORDER_ID" => "NULL",
					"ID" => $id,
				),
				false,
				false,
				array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "CURRENCY")
			);
			if ($arItem = $dbBasketItems->Fetch())
			{
				if (CSaleBasketHelper::isSetItem($arItem))
					continue;

				if ($_REQUEST["action"] == "delete" && in_array("DELETE", $arParams["COLUMNS_LIST"]))
				{
					CSaleBasket::Delete($arItem["ID"]);
				}
				elseif ($_REQUEST["action"] == "delay" && in_array("DELAY", $arParams["COLUMNS_LIST"]))
				{
					if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y")
						CSaleBasket::Update($arItem["ID"], array("DELAY" => "Y"));
				}
				elseif ($_REQUEST["action"] == "add" && in_array("DELAY", $arParams["COLUMNS_LIST"]))
				{
					if ($arItem["DELAY"] == "Y" && $arItem["CAN_BUY"] == "Y")
						CSaleBasket::Update($arItem["ID"], array("DELAY" => "N"));
				}
				unset($_SESSION["SALE_BASKET_NUM_PRODUCTS"][SITE_ID]);
			}
		}

		LocalRedirect($APPLICATION->GetCurPage());
	}
	else
	{
		if ($arParams["HIDE_COUPON"] != "Y")
		{
			$COUPON = trim($_REQUEST["COUPON"]);
			if (strlen($COUPON) > 0)
			{
				$res = CCatalogDiscountCoupon::SetCoupon($COUPON);
				$arResult["VALID_COUPON"] = $res;
			}
			else
				CCatalogDiscountCoupon::ClearCoupon();
		}

		$arTmpItems = array();
		$dbBasketItems = CSaleBasket::GetList(
			array("PRICE" => "DESC"),
			array(
				"FUSER_ID" => CSaleBasket::GetBasketUserID(),
				"LID" => SITE_ID,
				"ORDER_ID" => "NULL"
			),
			false,
			false,
			array(
				"ID", "NAME", "PRODUCT_PROVIDER_CLASS", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID",
				"QUANTITY", "DELAY", "CAN_BUY", "CURRENCY", "SUBSCRIBE", "TYPE", "SET_PARENT_ID"
			)
		);
		while ($arItem = $dbBasketItems->Fetch())
		{
			if (CSaleBasketHelper::isSetItem($arItem))
				continue;

			$arTmpItems[] = $arItem;
		}

		if (!empty($arTmpItems) && CModule::IncludeModule("catalog"))
			$arTmpItems = getRatio($arTmpItems);

		foreach ($arTmpItems as $arItem)
		{
			$isFloatQuantity = ((isset($arItem["MEASURE_RATIO"]) && floatval($arItem["MEASURE_RATIO"]) > 0 && $arItem["MEASURE_RATIO"] != 1) || $arParams["QUANTITY_FLOAT"] == "Y") ? true : false;

			if (!isset($_REQUEST["QUANTITY_".$arItem["ID"]]) || floatval($_REQUEST["QUANTITY_".$arItem["ID"]]) <= 0)
			{
				$quantityTmp = ($isFloatQuantity) ? floatval($arItem["QUANTITY"]) : intval($arItem["QUANTITY"]);
			}
			else
			{
				$quantityTmp = ($isFloatQuantity) ? floatval($_REQUEST["QUANTITY_".$arItem["ID"]]) : intval($_REQUEST["QUANTITY_".$arItem["ID"]]);
			}

			if ($arItem["CAN_BUY"] == "Y")
			{
				$res = checkQuantity($arItem, $quantityTmp);
				if (!empty($res))
					$arResult["WARNING_MESSAGE"][] = $res["ERROR"];
			}

			$deleteTmp = ($_REQUEST["DELETE_".$arItem["ID"]] == "Y") ? "Y" : "N";
			$delayTmp = ($_REQUEST["DELAY_".$arItem["ID"]] == "Y") ? "Y" : "N";

			if ($deleteTmp == "Y" && in_array("DELETE", $arParams["COLUMNS_LIST"]))
			{
				if ($arItem["SUBSCRIBE"] == "Y" && is_array($_SESSION["NOTIFY_PRODUCT"][$USER->GetID()]))
				{
					unset($_SESSION["NOTIFY_PRODUCT"][$USER->GetID()][$arItem["PRODUCT_ID"]]);
				}
				CSaleBasket::Delete($arItem["ID"]);
			}
			elseif ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y")
			{
				unset($arFields);
				$arFields = array();
				if (in_array("QUANTITY", $arParams["COLUMNS_LIST"]))
					$arFields["QUANTITY"] = $quantityTmp;
				if (in_array("DELAY", $arParams["COLUMNS_LIST"]))
					$arFields["DELAY"] = $delayTmp;

				if (count($arFields) > 0
					&&
						($arItem["QUANTITY"] != $arFields["QUANTITY"] && in_array("QUANTITY", $arParams["COLUMNS_LIST"])
							|| $arItem["DELAY"] != $arFields["DELAY"] && in_array("DELAY", $arParams["COLUMNS_LIST"]))
					)
					CSaleBasket::Update($arItem["ID"], $arFields);
			}
			elseif ($arItem["DELAY"] == "Y" && $arItem["CAN_BUY"] == "Y")
			{
				unset($arFields);
				$arFields = array();
				if (in_array("DELAY", $arParams["COLUMNS_LIST"]))
					$arFields["DELAY"] = $delayTmp;

				if (count($arFields) > 0
					&&
						($arItem["DELAY"] != $arFields["DELAY"] && in_array("DELAY", $arParams["COLUMNS_LIST"]))
					)
					CSaleBasket::Update($arItem["ID"], $arFields);
			}
		}

		unset($_SESSION["SALE_BASKET_NUM_PRODUCTS"][SITE_ID]);

		if (strlen($_REQUEST["BasketOrder"]) > 0 && empty($arResult["WARNING_MESSAGE"]))
		{
			LocalRedirect($arParams["PATH_TO_ORDER"]);
		}
		else
		{
			unset($_REQUEST["BasketRefresh"]);
			unset($_REQUEST["BasketOrder"]);

			if (!empty($arResult["WARNING_MESSAGE"]))
				$_SESSION["SALE_BASKET_MESSAGE"] = $arResult["WARNING_MESSAGE"];

			LocalRedirect($APPLICATION->GetCurPage());
		}
	}
}

$basketData = $this->getBasketItems();

$arResult = array_merge($arResult, $basketData);

$arResult["GRID"]["HEADERS"] = $headersData;

if (is_array($_SESSION["SALE_BASKET_MESSAGE"]))
{
	foreach ($_SESSION["SALE_BASKET_MESSAGE"] as $message)
		$arResult["WARNING_MESSAGE"][] = $message;

	unset($_SESSION["SALE_BASKET_MESSAGE"]);
}

$this->IncludeComponentTemplate();

?>