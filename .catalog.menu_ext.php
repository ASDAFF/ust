<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

global $APPLICATION;

if (!function_exists("GetTreeRecursive"))
{

    $arMenuLinks = $APPLICATION->IncludeComponent(
            "bitrix:menu.sections", "", Array(
        "ID" => $_REQUEST["ID"],
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => "6",
        "SECTION_URL" => "#SITE_DIR#/catalog/#SECTION_CODE#/",
        "DEPTH_LEVEL" => "4",
        "CACHE_TYPE" => "Y",
        "CACHE_TIME" => "36000000",
        "TOP_MENU" => "Y"
            )
    );

    $aMenuLinks = array_merge($arMenuLinks, array(
        Array(
            "Б/У строительная техника",
            "/catalog/bu-stroitelnaya-tehnika/",
            Array(),
            Array("DOMENID" => array("22220")),
            ""
        ),
        Array(
            "Б/У складская техника",
            "/catalog/bu-skladskaya-tehnika/",
            Array(),
            Array("DOMENID" => array("22224")),
            ""
        )
    ));
}
?>