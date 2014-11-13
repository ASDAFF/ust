<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


// functions for custom columns view

if (!function_exists("getNameCount"))
{
	function getNameCount($propName, $propCode, $arProps)
	{
		$count = 1;
		foreach ($arProps as $id => $arData)
		{
			if (isset($arData["NAME"]) && $arData["NAME"] == $propName && $propCode != $arData["CODE"])
				$count++;
		}
		return $count;
	}
}

if (!function_exists("getIblockNames"))
{
	function getIblockNames($arIblockIDs, $arIblockNames)
	{
		$str = "";
		foreach ($arIblockIDs as $iblockID)
		{
			$str .= "\"".$arIblockNames[$iblockID]."\", ";
		}
		$str .= "#";

		return str_replace(", #", "", $str);
	}
}

$arColumns = array(
	"NAME" => GetMessage("SBB_BNAME"),
	"DISCOUNT" => GetMessage("SBB_BDISCOUNT"),
	"WEIGHT" => GetMessage("SBB_BWEIGHT"),
	"QUANTITY" => GetMessage("SBB_BQUANTITY"),
	"PROPS" => GetMessage("SBB_BPROPS"),
	"DELETE" => GetMessage("SBB_BDELETE"),
	"DELAY" => GetMessage("SBB_BDELAY"),
	"TYPE" => GetMessage("SBB_BTYPE"),
	"PRICE" => GetMessage("SBB_BPRICE"),
	"SUM" => GetMessage("SBB_BSUM")
);

if (CModule::IncludeModule("catalog"))
{
	// get iblock props from all catalog iblocks including sku iblocks
	$arIblockIDs = array();
	$arIblockNames = array();

	$dbCatalog = CCatalog::GetList(array(), array());
	while ($arCatalog = $dbCatalog->GetNext())
	{
		$arIblockIDs[] = $arCatalog["IBLOCK_ID"];
		$arIblockNames[$arCatalog["IBLOCK_ID"]] = $arCatalog["NAME"];
	}

	// iblock props
	$arProps = array();
	foreach ($arIblockIDs as $iblockID)
	{
		$dbProps = CIBlockProperty::GetList(
			array(
				"SORT"=>"ASC",
				"NAME"=>"ASC"
			),
			array(
				"IBLOCK_ID" => $iblockID,
				"ACTIVE" => "Y",
				"CHECK_PERMISSIONS" => "N",
			)
		);

		while ($arProp = $dbProps->GetNext())
			$arProps[] = $arProp;
	}

	// create properties array where properties with the same codes are considered the same
	$arTmpProperty2Iblock = array();
	foreach ($arProps as $id => $arProperty)
	{
		$arTmpProperty2Iblock["PROPERTY_".$arProperty["CODE"]][] = $arProperty["IBLOCK_ID"];

		if (getNameCount($arProperty["NAME"], $arProperty["CODE"], $arProps) > 1)
			$name = $arProperty["NAME"]." [".$arProperty["CODE"]."] ";
		else
			$name = $arProperty["NAME"];

		if (array_key_exists("PROPERTY_".$arProperty["CODE"], $arColumns))
			$arColumns["PROPERTY_".$arProperty["CODE"]]	= $name." (".getIblockNames($arTmpProperty2Iblock["PROPERTY_".$arProperty["CODE"]], $arIblockNames).")";
		else
			$arColumns["PROPERTY_".$arProperty["CODE"]] = $name;
	}
}
// end of custom columns view functions

$arYesNo = Array(
	"Y" => GetMessage("SBB_DESC_YES"),
	"N" => GetMessage("SBB_DESC_NO"),
);

$arComponentParameters = Array(
	"PARAMETERS" => Array(
		"PATH_TO_ORDER" => Array(
			"NAME" => GetMessage("SBB_PATH_TO_ORDER"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "/personal/order.php",
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),

		"HIDE_COUPON" => Array(
			"NAME"=>GetMessage("SBB_HIDE_COUPON"),
			"TYPE"=>"LIST", "MULTIPLE"=>"N",
			"VALUES"=>array(
					"N" => GetMessage("SBB_DESC_NO"),
					"Y" => GetMessage("SBB_DESC_YES")
				),
			"DEFAULT"=>"N",
			"COLS"=>25,
			"ADDITIONAL_VALUES"=>"N",
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"COLUMNS_LIST" => Array(
			"NAME"=>GetMessage("SBB_COLUMNS_LIST"),
			"TYPE"=>"LIST",
			"MULTIPLE"=>"Y",
			"VALUES"=>$arColumns,
			"DEFAULT"=>array("NAME", "PRICE", "TYPE", "DISCOUNT", "QUANTITY", "DELETE", "DELAY", "WEIGHT"),
			"COLS"=>25,
			"SIZE"=>7,
			"ADDITIONAL_VALUES"=>"N",
			"PARENT" => "VISUAL",
		),

/*
		"PRICE_VAT_INCLUDE" => array(
			"NAME" => GetMessage('SBB_VAT_INCLUDE'),
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"DEFAULT" => "Y",
			"ADDITIONAL_VALUES"=>"N",
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
*/
		"PRICE_VAT_SHOW_VALUE" => array(
			"NAME" => GetMessage('SBB_VAT_SHOW_VALUE'),
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"DEFAULT" => "N",
			"ADDITIONAL_VALUES"=>"N",
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => Array(
			"NAME"=>GetMessage("SBB_COUNT_DISCOUNT_4_ALL_QUANTITY"),
			"TYPE"=>"CHECKBOX",
			"DEFAULT"=>"N",
			"ADDITIONAL_VALUES"=>"N",
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"USE_PREPAYMENT" => array(
			"NAME" => GetMessage('SBB_USE_PREPAYMENT'),
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"DEFAULT" => "N",
			"ADDITIONAL_VALUES"=>"N",
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"QUANTITY_FLOAT" => array(
			"NAME" => GetMessage('SBB_QUANTITY_FLOAT'),
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"DEFAULT" => "N",
			"ADDITIONAL_VALUES"=>"N",
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"SET_TITLE" => Array(),
	)
);
?>