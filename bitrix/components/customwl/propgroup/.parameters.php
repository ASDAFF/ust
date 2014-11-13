<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;

$arIBlockTypes = CIBlockParameters::GetIBlockTypes();

$arIBlock=array();

$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
{
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}

$arFreeProps = Array(
	"n" => GetMessage("DONT_SHOW"),
	"before" => GetMessage("SHOW_BEFORE"),
	"after" => GetMessage("SHOW_AFTER"),
);

$arSortField = Array(
	"name" => GetMessage("NAME"),
	"sort" => GetMessage("SORT"),
);

$arSortOrder = Array(
	"asc" => GetMessage("ASC"),
	"desc" => GetMessage("DESC"),
);

$arComponentParameters = Array(
	"GROUPS" => array(
			"WORK_PROPS" => array(
				"NAME" => GetMessage("WORK_PROPS"),
			),
		),
	"PARAMETERS" => Array(
		"IBLOCK_TYPE" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockTypes,
			"DEFAULT" => "",
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlock,
			"DEFAULT" => '',
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "Y",
		),
		"IBLOCK_ELEMENT" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_ELEMENT"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
			"ADDITIONAL_VALUES" => "N",
			"REFRESH" => "Y",
		),
		"FREE_PROPS" => Array(
			"PARENT" => "WORK_PROPS",
			"NAME" => GetMessage("FREE_PROPS"),
			"TYPE" => "LIST",
			"VALUES" => $arFreeProps,
			"DEFAULT" => "asc",
			"ADDITIONAL_VALUES" => "N",
		),
		"SECTION_CONTROL" => Array(
			"PARENT" => "WORK_PROPS",
			"NAME" => GetMessage("SECTION_CONTROL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"GROUP_SORT_FIELD" => Array(
			"PARENT" => "WORK_PROPS",
			"NAME" => GetMessage("GROUP_SORT_FIELD"),
			"TYPE" => "LIST",
			"VALUES" => $arSortField,
			"DEFAULT" => "sort",
			"ADDITIONAL_VALUES" => "N",
		),
		"GROUP_SORT_ORDER" => Array(
			"PARENT" => "WORK_PROPS",
			"NAME" => GetMessage("GROUP_SORT_ORDER"),
			"TYPE" => "LIST",
			"VALUES" => $arSortOrder,
			"DEFAULT" => "asc",
			"ADDITIONAL_VALUES" => "N",
		),
		"PROPERTY_SORT_FIELD" => Array(
			"PARENT" => "WORK_PROPS",
			"NAME" => GetMessage("PROPERTY_SORT_FIELD"),
			"TYPE" => "LIST",
			"VALUES" => $arSortField,
			"DEFAULT" => "sort",
			"ADDITIONAL_VALUES" => "N",
		),
		"PROPERTY_SORT_ORDER" => Array(
			"PARENT" => "WORK_PROPS",
			"NAME" => GetMessage("PROPERTY_SORT_ORDER"),
			"TYPE" => "LIST",
			"VALUES" => $arSortOrder,
			"DEFAULT" => "asc",
			"ADDITIONAL_VALUES" => "N",
		),
		
	)
);
?>