<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

if (!function_exists("GetTreeRecursive")) {
	$arMenuLinks = $APPLICATION->IncludeComponent(
		"bitrix:menu.sections",
		"",
		Array(
			"ID" => $_REQUEST["ID"],
			"IBLOCK_TYPE" => "used",
			"IBLOCK_ID" => "7",
			"SECTION_URL" => "#SITE_DIR#/catalog/bu-stroitelnaya-tehnika/#SECTION_CODE#/",
			"DEPTH_LEVEL" => "3",
			"CACHE_TYPE" => "N",			 
			"TOP_MENU" => "Y"
		)
	);
}

?>