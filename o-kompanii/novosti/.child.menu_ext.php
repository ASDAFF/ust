<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;

if (!function_exists("GetTreeRecursive")) {
	$aMenuLinks = $APPLICATION->IncludeComponent(
		"pixel:menu.sections",
		"",
		Array(
			"IBLOCK_TYPE" => "about_company",
			"IBLOCK_ID" => NEWS,
			"SECTION_URL" => "#SITE_DIR#/o-kompanii/novosti/#SECTION_CODE#/",
			"DEPTH_LEVEL" => "3",
			"CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600000"
		)
	);
}
?>