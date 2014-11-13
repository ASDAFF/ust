<?
define("CATALOG_COMPARE_SHOW", "Y");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("БУ");?>

 <?
 
 if(!isset($_GET["new_tpl"]))
 {
     
 $APPLICATION->IncludeComponent("bitrix:catalog", "catalog_used", array(
    "IBLOCK_TYPE" => "used",
    "IBLOCK_ID" => "54",
    "HIDE_NOT_AVAILABLE" => "N",
    "SECTION_ID_VARIABLE" => "SECTION_ID",
    "SEF_MODE" => "Y",
    "SEF_FOLDER" => "/catalog/",
    "AJAX_MODE" => "N",
    "AJAX_OPTION_JUMP" => "N",
    "AJAX_OPTION_STYLE" => "Y",
    "AJAX_OPTION_HISTORY" => "N",
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "36000000",
    "CACHE_FILTER" => "N",
    "CACHE_GROUPS" => "Y",
    "SET_TITLE" => "Y",
    "SET_STATUS_404" => "N",
    "USE_ELEMENT_COUNTER" => "Y",
    "USE_FILTER" => "Y",
    "FILTER_NAME" => "",
    "FILTER_FIELD_CODE" => array(
        0 => "",
        1 => "",
    ),
    "FILTER_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
    ),
    "FILTER_PRICE_CODE" => array(
    ),
    "USE_COMPARE" => "N",
    "PRICE_CODE" => array(
    ),
    "USE_PRICE_COUNT" => "N",
    "SHOW_PRICE_COUNT" => "1",
    "PRICE_VAT_INCLUDE" => "Y",
    "PRICE_VAT_SHOW_VALUE" => "N",
    "CONVERT_CURRENCY" => "N",
    "BASKET_URL" => "/personal/basket.php",
    "ACTION_VARIABLE" => "action",
    "PRODUCT_ID_VARIABLE" => "id",
    "USE_PRODUCT_QUANTITY" => "N",
    "ADD_PROPERTIES_TO_BASKET" => "Y",
    "PRODUCT_PROPS_VARIABLE" => "prop",
    "PARTIAL_PRODUCT_PROPERTIES" => "N",
    "PRODUCT_PROPERTIES" => array(
    ),
    "SHOW_TOP_ELEMENTS" => "Y",
    "TOP_ELEMENT_COUNT" => "9",
    "TOP_LINE_ELEMENT_COUNT" => "3",
    "TOP_ELEMENT_SORT_FIELD" => "sort",
    "TOP_ELEMENT_SORT_ORDER" => "asc",
    "TOP_ELEMENT_SORT_FIELD2" => "id",
    "TOP_ELEMENT_SORT_ORDER2" => "desc",
    "TOP_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
    ),
    "SECTION_COUNT_ELEMENTS" => "Y",
    "SECTION_TOP_DEPTH" => "2",
    "PAGE_ELEMENT_COUNT" => "30",
    "LINE_ELEMENT_COUNT" => "3",
    "ELEMENT_SORT_FIELD" => "sort",
    "ELEMENT_SORT_ORDER" => "asc",
    "ELEMENT_SORT_FIELD2" => "id",
    "ELEMENT_SORT_ORDER2" => "desc",
    "LIST_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
    ),
    "INCLUDE_SUBSECTIONS" => "Y",
    "LIST_META_KEYWORDS" => "-",
    "LIST_META_DESCRIPTION" => "-",
    "LIST_BROWSER_TITLE" => "-",
    "DETAIL_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
    ),
    "DETAIL_META_KEYWORDS" => "-",
    "DETAIL_META_DESCRIPTION" => "-",
    "DETAIL_BROWSER_TITLE" => "-",
    "LINK_IBLOCK_TYPE" => "",
    "LINK_IBLOCK_ID" => "",
    "LINK_PROPERTY_SID" => "",
    "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
    "USE_ALSO_BUY" => "N",
    "USE_STORE" => "N",
    "PAGER_TEMPLATE" => ".default",
    "DISPLAY_TOP_PAGER" => "Y",
    "DISPLAY_BOTTOM_PAGER" => "Y",
    "PAGER_TITLE" => "Товары",
    "PAGER_SHOW_ALWAYS" => "N",
    "PAGER_DESC_NUMBERING" => "N",
    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
    "PAGER_SHOW_ALL" => "Y",
    "AJAX_OPTION_ADDITIONAL" => "",
    "PRODUCT_QUANTITY_VARIABLE" => "quantity",
    "SEF_URL_TEMPLATES" => array(
        "sections" => "",
        "section" => "#SECTION_CODE_PATH#/",
        "element" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
        "compare" => "compare.php?action=#ACTION_CODE#",
    ),
    "VARIABLE_ALIASES" => array(
        "compare" => array(
            "ACTION_CODE" => "action",
        ),
    )
    ),
    false
);
 }
 else 
 {
        
 $APPLICATION->IncludeComponent("bitrix:catalog", "catalog_new", array(
    "IBLOCK_TYPE" => "used",
    "IBLOCK_ID" => "54",
    "HIDE_NOT_AVAILABLE" => "N",
    "SECTION_ID_VARIABLE" => "SECTION_ID",
    "SEF_MODE" => "Y",
    "SEF_FOLDER" => "/catalog/bu-skladskaya-tehnika/",
    "AJAX_MODE" => "N",
    "AJAX_OPTION_JUMP" => "N",
    "AJAX_OPTION_STYLE" => "Y",
    "AJAX_OPTION_HISTORY" => "N",
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "36000000",
    "CACHE_FILTER" => "N",
    "CACHE_GROUPS" => "Y",
    "SET_TITLE" => "Y",
    "SET_STATUS_404" => "N",
    "USE_ELEMENT_COUNTER" => "Y",
    "USE_FILTER" => "Y",
    "FILTER_NAME" => "",
    "FILTER_FIELD_CODE" => array(
        0 => "",
        1 => "",
    ),
    "FILTER_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
    ),
    "FILTER_PRICE_CODE" => array(
    ),
    "USE_COMPARE" => "N",
    "PRICE_CODE" => array(
    ),
    "USE_PRICE_COUNT" => "N",
    "SHOW_PRICE_COUNT" => "1",
    "PRICE_VAT_INCLUDE" => "Y",
    "PRICE_VAT_SHOW_VALUE" => "N",
    "CONVERT_CURRENCY" => "N",
    "BASKET_URL" => "/personal/basket.php",
    "ACTION_VARIABLE" => "action",
    "PRODUCT_ID_VARIABLE" => "id",
    "USE_PRODUCT_QUANTITY" => "N",
    "ADD_PROPERTIES_TO_BASKET" => "Y",
    "PRODUCT_PROPS_VARIABLE" => "prop",
    "PARTIAL_PRODUCT_PROPERTIES" => "N",
    "PRODUCT_PROPERTIES" => array(
    ),
    "SHOW_TOP_ELEMENTS" => "Y",
    "TOP_ELEMENT_COUNT" => "9",
    "TOP_LINE_ELEMENT_COUNT" => "3",
    "TOP_ELEMENT_SORT_FIELD" => "sort",
    "TOP_ELEMENT_SORT_ORDER" => "asc",
    "TOP_ELEMENT_SORT_FIELD2" => "id",
    "TOP_ELEMENT_SORT_ORDER2" => "desc",
    "TOP_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
    ),
    "SECTION_COUNT_ELEMENTS" => "N",
    "SECTION_TOP_DEPTH" => "1",
    "PAGE_ELEMENT_COUNT" => "30",
    "LINE_ELEMENT_COUNT" => "3",
    "ELEMENT_SORT_FIELD" => "sort",
    "ELEMENT_SORT_ORDER" => "asc",
    "ELEMENT_SORT_FIELD2" => "id",
    "ELEMENT_SORT_ORDER2" => "desc",
    "LIST_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
    ),
    "INCLUDE_SUBSECTIONS" => "A",
    "LIST_META_KEYWORDS" => "-",
    "LIST_META_DESCRIPTION" => "-",
    "LIST_BROWSER_TITLE" => "-",
    "DETAIL_PROPERTY_CODE" => array(
        0 => "",
        1 => "PHOTOS",
        2 => "",
        3 => "",
    ),
    "DETAIL_META_KEYWORDS" => "-",
    "DETAIL_META_DESCRIPTION" => "-",
    "DETAIL_BROWSER_TITLE" => "-",
    "LINK_IBLOCK_TYPE" => "",
    "LINK_IBLOCK_ID" => "",
    "LINK_PROPERTY_SID" => "",
    "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
    "USE_ALSO_BUY" => "N",
    "USE_STORE" => "N",
    "PAGER_TEMPLATE" => ".default",
    "DISPLAY_TOP_PAGER" => "Y",
    "DISPLAY_BOTTOM_PAGER" => "Y",
    "PAGER_TITLE" => "Товары",
    "PAGER_SHOW_ALWAYS" => "N",
    "PAGER_DESC_NUMBERING" => "N",
    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
    "PAGER_SHOW_ALL" => "Y",
    "AJAX_OPTION_ADDITIONAL" => "",
    "PRODUCT_QUANTITY_VARIABLE" => "quantity",
    "SEF_URL_TEMPLATES" => array(
        "sections" => "",
        "section" => "#SECTION_CODE#/",
        "element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
        "compare" => "compare.php?action=#ACTION_CODE#",
    ),
    "VARIABLE_ALIASES" => array(
        "compare" => array(
            "ACTION_CODE" => "action",
        ),
    )
    ),
    false
);
     
 }
 
 ?>


   <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>