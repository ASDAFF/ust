<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

global $APPLICATION;

if (!function_exists("GetTreeRecursive"))
{
    $arMenuLinks = $APPLICATION->IncludeComponent(
            "pixel:menu.sections", "", Array(
                "ID" => $_REQUEST["ID"],
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => "6",
                "SECTION_URL" => "#SITE_DIR#/catalog/#SECTION_CODE#/",
                "DEPTH_LEVEL" => "2",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
            )
    );

    $aMenuLinks = array_merge($arMenuLinks, array(
        Array(
            "Б/У техника",
            "/catalog/bu-stroitelnaya-tehnika/",
            Array(),
            Array("DOMENID" => array("22220")),
            ""
        )
    ));
}
?>