<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

global $APPLICATION;

if (!function_exists("GetTreeRecursive"))
{
    // PRINT  $_REQUEST["ID"];
    $arMenuLinks = $APPLICATION->IncludeComponent(
            "bitrix:menu.sections", "", Array(
            "ID" => $_REQUEST["ID"],
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => "6",
            "SECTION_URL" => "#SITE_DIR#/catalog/#SECTION_CODE#/",
            "DEPTH_LEVEL" => "2",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "TOP_MENU" => "Y",
            "CHILD_MENU_TYPE" => "child",
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
        ),
    ));

    // print "<pre style='display:none'>";
    // print_R($arMenuLinks);
    //  print "</pre>";
}
?>