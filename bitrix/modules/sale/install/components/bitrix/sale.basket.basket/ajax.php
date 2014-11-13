<?
define('STOP_STATISTICS', true);

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if (!CModule::IncludeModule('sale') || !CModule::IncludeModule('iblock') || !CModule::IncludeModule('catalog'))
	return;

global $USER, $APPLICATION;

if (!$USER->IsAuthorized() || !check_bitrix_sessid() || $_SERVER['REQUEST_METHOD'] != 'POST')
	return;

include(dirname(__FILE__)."/functions.php");

CUtil::JSPostUnescape();

$arRes = array();
$newProductId = false;
$newBasketId = false;

if (isset($_POST["action"]) && strlen($_POST["action"]) > 0)
{
	if ($_POST["action"] == "basket_item_select")
	{
		$arPropsValues = isset($_POST["props"]) ? $_POST["props"] : array();
		$strColumns = isset($_POST["select_props"]) ? $_POST["select_props"] : "";
		$arColumns = explode(",", $strColumns);
		$strOffersProps = isset($_POST["offers_props"]) ? $_POST["offers_props"] : "";

		$arSelect = array("ID", "IBLOCK_ID", "PROPERTY_CML2_LINK");
		foreach ($arColumns as &$columnName)
		{
			if ((strpos($columnName, "PROPERTY_", 0) === 0))
			{
				$columnName = str_replace("_VALUE", "", $columnName);
				$arSelect[] = $columnName;
			}
		}
		unset($columnName);

		$arItem = CSaleBasket::GetByID(intval($_POST["basketItemId"]));

		$dbRes = CIBlockElement::GetList(
			array("SORT" => "ASC", "ID" => "ASC"),
			array("ID" => $arItem["PRODUCT_ID"]),
			false,
			false,
			$arSelect
		);
		if ($arElement = $dbRes->Fetch())
		{
			$arPropsValues["CML2_LINK"] = $arElement["PROPERTY_CML2_LINK_VALUE"];

			$newProductId = getProductByProps($arElement["IBLOCK_ID"], $arPropsValues);

			$newBasketId = Add2BasketByProductID($newProductId, $arItem["QUANTITY"], array());

			if ($newBasketId)
			{
				// delete previous basket item
				$del = CSaleBasket::Delete(intval($_POST["basketItemId"]));
				if ($del)
					$arRes["DELETE_ORIGINAL"] = "Y";

				// recalculate basket data
				CBitrixComponent::includeComponentClass("bitrix:sale.basket.basket");

				$basket = new CBitrixBasketComponent();

				$basket->weightKoef = htmlspecialcharsbx(COption::GetOptionString('sale', 'weight_koef', 1, SITE_ID));
				$basket->weightUnit = htmlspecialcharsbx(COption::GetOptionString('sale', 'weight_unit', "", SITE_ID));
				$basket->columns = $arColumns;
				$basket->offersProps = explode(",", $strOffersProps);

				$basket->quantityFloat = (isset($_POST["QUANTITY_FLOAT"]) && $_POST["QUANTITY_FLOAT"] == "Y") ? "Y" : "N";
				$basket->countDiscount4AllQuantity = (isset($_POST["COUNT_DISCOUNT_4_ALL_QUANTITY"]) && $_POST["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N";
				$basket->priceVatShowValue = (isset($_POST["PRICE_VAT_SHOW_VALUE"]) && $_POST["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N";
				$basket->hideCoupon = (isset($_POST["HIDE_COUPON"]) && $_POST["HIDE_COUPON"] == "Y") ? "Y" : "N";
				$basket->usePrepayment = (isset($_POST["USE_PREPAYMENT"]) && $_POST["USE_PREPAYMENT"] == "Y") ? "Y" : "N";

				$columnsData = $basket->getCustomColumns();
				$basketData  = $basket->getBasketItems();

				$arRes["BASKET_ID"] = $newBasketId;
				$arRes["BASKET_DATA"] = $basketData;

				$arRes["BASKET_DATA"]["GRID"]["HEADERS"] = $columnsData;
				$arRes["COLUMNS"] = $strColumns;
			}
		}
	}
}

$arRes["PARAMS"]["QUANTITY_FLOAT"] = (isset($_POST["QUANTITY_FLOAT"]) && $_POST["QUANTITY_FLOAT"] == "Y") ? "Y" : "N";

$arRes["CODE"] = ($newBasketId) ? "SUCCESS" : "ERROR";

$APPLICATION->RestartBuffer();
header('Content-Type: application/json; charset='.LANG_CHARSET);

echo CUtil::PhpToJSObject($arRes);
die();
?>