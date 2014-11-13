<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

if (!CModule::IncludeModule("iblock"))
	return;
	
global $APPLICATION;

$arIBlockType = CIBlockParameters::GetIBlockTypes();
$rsIBlock = CIBlock::GetList(array(
	"sort" => "asc",
), array(
	"TYPE" => $arCurrentValues["IBLOCK_TYPE"],
	"ACTIVE" => "Y",
));
while ($arr = $rsIBlock->Fetch())
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];

$boolCatalog = CModule::IncludeModule("catalog");
$arPrice = array();
if ($boolCatalog)
{
	$rsPrice = CCatalogGroup::GetList($v1 = "sort", $v2 = "asc");
	while ($arr = $rsPrice->Fetch())
		$arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
}

$arProperty = array();
$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"]));
while ($arr=$rsProp->Fetch())
{
	if($arr["PROPERTY_TYPE"] != "F")
		$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
}

$arOffers = CIBlockPriceTools::GetOffersIBlock($arCurrentValues["IBLOCK_ID"]);
$OFFERS_IBLOCK_ID = is_array($arOffers)? $arOffers["OFFERS_IBLOCK_ID"]: 0;
$arProperty_Offers = array();
if($OFFERS_IBLOCK_ID)
{
	$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$OFFERS_IBLOCK_ID));
	while($arr=$rsProp->Fetch())
	{
		if($arr["PROPERTY_TYPE"] != "F")
			$arProperty_Offers[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}

$arProperty_UF = array();
$arSProperty_LNS = array();
$arUserFields = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("IBLOCK_".$arCurrentValues["IBLOCK_ID"]."_SECTION");
foreach($arUserFields as $FIELD_NAME=>$arUserField)
{
	$arProperty_UF[$FIELD_NAME] = $arUserField["LIST_COLUMN_LABEL"]? $arUserField["LIST_COLUMN_LABEL"]: $FIELD_NAME;
	if($arUserField["USER_TYPE"]["BASE_TYPE"]=="string")
		$arSProperty_LNS[$FIELD_NAME] = $arProperty_UF[$FIELD_NAME];
}

$arComponentParameters = array(
	"GROUPS" => array(
		"PRICES" => array(
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_GROUP_PRICES"),
		),
		"XML_EXPORT" => array(
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_GROUP_XML_EXPORT"),
		)
	),
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_IBLOCK_ID"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),
		"SECTION_ID" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_SECTION_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["SECTION_ID"]}',
		),
		"SECTION_CODE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_SECTION_CODE"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"FILTER_NAME" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_FILTER_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "arrFilter",
		),
		"PRICE_CODE" => array(
			"PARENT" => "PRICES",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_PRICE_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arPrice,
		),
		"CACHE_TIME" => array(
			"DEFAULT" => 36000000,
		),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"SAVE_IN_SESSION" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_SAVE_IN_SESSION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"INCLUDE_JQUERY" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_INCLUDE_JQUERY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"CLOSED_PROPERTY_CODE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_CLOSED_PROPERTY_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty,
			"ADDITIONAL_VALUES" => "Y",
		),
		"CLOSED_OFFERS_PROPERTY_CODE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_CLOSED_OFFERS_PROPERTY_CODE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty_Offers,
			"ADDITIONAL_VALUES" => "Y",
		),
		"XML_EXPORT" => array(
			"PARENT" => "XML_EXPORT",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_XML_EXPORT"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SECTION_TITLE" => array(
			"PARENT" => "XML_EXPORT",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_SECTION_TITLE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => "-",
			"VALUES" => array_merge(
				array(
					"-" => " ",
					"NAME" => GetMessage("IBLOCK_FIELD_NAME"),
				), $arSProperty_LNS
			),
		),
		"SECTION_DESCRIPTION" => array(
			"PARENT" => "XML_EXPORT",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_SECTION_DESCRIPTION"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => "-",
			"VALUES" => array_merge(
				array(
					"-" => " ",
					"NAME" => GetMessage("IBLOCK_FIELD_NAME"),
					"DESCRIPTION" => GetMessage("IBLOCK_FIELD_DESCRIPTION"),
				), $arSProperty_LNS
			),
		),
		"PAGE_URL" => array(
			"PARENT" => "BASE",
			"NAME"=>GetMessage("KOMBOX_CMP_FILTER_PAGE_URL"),
			"TYPE"=>"STRING",
			"DEFAULT"=>"",
		),
		"IS_SEF" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_IS_SEF"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
		"SEF_BASE_URL" => array(
			"PARENT" => "BASE",
			"NAME"=>GetMessage("KOMBOX_CMP_FILTER_SEF_BASE_URL"),
			"TYPE"=>"STRING",
			"DEFAULT"=>'/catalog/',
		),
		"SECTION_PAGE_URL" => CIBlockParameters::GetPathTemplateParam(
			"SECTION",
			"SECTION_PAGE_URL",
			GetMessage("KOMBOX_CMP_FILTER_SECTION_PAGE_URL"),
			"#SECTION_ID#/",
			"BASE"
		),
		"MESSAGE_ALIGN" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_MESSAGE_ALIGN"),
			"TYPE" => "LIST",
			"VALUES" => array(
				"LEFT" => GetMessage("KOMBOX_CMP_FILTER_MESSAGE_ALIGN_LEFT"),
				"RIGHT" => GetMessage("KOMBOX_CMP_FILTER_MESSAGE_ALIGN_RIGHT"),
			),
			"DEFAULT" => "LEFT"
		),
		"MESSAGE_TIME" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("KOMBOX_CMP_FILTER_MESSAGE_TIME"),
			"TYPE" => "STRING",
			"DEFAULT" => '5',
		)
	),
);

$arFields = array(
	'SECTION' => GetMessage('KOMBOX_CMP_FILTER_FIELDS_SECTION')
);

if ($boolCatalog)
{
	$arComponentParameters["PARAMETERS"]['HIDE_NOT_AVAILABLE'] = array(
		'PARENT' => 'DATA_SOURCE',
		'NAME' => GetMessage('KOMBOX_CMP_FILTER_HIDE_NOT_AVAILABLE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	);

	if (CModule::IncludeModule('currency'))
	{
		$arComponentParameters["PARAMETERS"]['CONVERT_CURRENCY'] = array(
			'PARENT' => 'PRICES',
			'NAME' => GetMessage('KOMBOX_CMP_FILTER_CONVERT_CURRENCY'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y',
		);

		if (isset($arCurrentValues['CONVERT_CURRENCY']) && 'Y' == $arCurrentValues['CONVERT_CURRENCY'])
		{
			$arCurrencyList = array();
			$by = 'SORT';
			$order = 'ASC';
			$rsCurrencies = CCurrency::GetList($by, $order);
			while ($arCurrency = $rsCurrencies->Fetch())
			{
				$arCurrencyList[$arCurrency['CURRENCY']] = $arCurrency['CURRENCY'];
			}
			$arComponentParameters['PARAMETERS']['CURRENCY_ID'] = array(
				'PARENT' => 'PRICES',
				'NAME' => GetMessage('KOMBOX_CMP_FILTER_CURRENCY_ID'),
				'TYPE' => 'LIST',
				'VALUES' => $arCurrencyList,
				'DEFAULT' => CCurrency::GetBaseCurrency(),
				"ADDITIONAL_VALUES" => "Y",
			);
		}
	}
	
	$arFields['STORES'] = GetMessage('KOMBOX_CMP_FILTER_FIELDS_STORES');
}

$arComponentParameters["PARAMETERS"]['FIELDS'] = array(
	'PARENT' => 'DATA_SOURCE',
	'NAME' => GetMessage('KOMBOX_CMP_FILTER_FIELDS'),
	'TYPE' => 'LIST',
	'DEFAULT' => '',
	"MULTIPLE" => "Y",
	"VALUES" => $arFields
);

if(empty($arPrice))
	unset($arComponentParameters["PARAMETERS"]["PRICE_CODE"]);
	
if($arCurrentValues["IS_SEF"] === "Y"){
    unset($arComponentParameters["PARAMETERS"]["SECTION_ID"]);
}
else {
    unset($arComponentParameters["PARAMETERS"]["SEF_BASE_URL"]);
    unset($arComponentParameters["PARAMETERS"]["SECTION_PAGE_URL"]);
}
?>