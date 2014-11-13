<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

if (!function_exists("GetTreeRecursive")) {
	$aMenuLinks = $APPLICATION->IncludeComponent(
		"bitrix:menu.sections",
		"",
		Array(
			"ID" => $_REQUEST["ID"],
			"IBLOCK_TYPE" => "rentals",
			"IBLOCK_ID" => "10",
			"SECTION_URL" => "#SITE_DIR#/arenda/#SECTION_CODE#/",
			"DEPTH_LEVEL" => "1",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600"
		)
	);
}

?>