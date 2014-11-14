<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

global $DB;
global $USER;
global $APPLICATION;
global $CACHE_MANAGER;
global $INTRANET_TOOLBAR;

$obCache = new CPHPCache();

$domains = get_domains();


// ------------------------------


$sections_UF = get_sections_url_on_id();
//----------------------------------

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

//if (!isset($arParams["CACHE_TIME"]))
$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);

$arParams["SECTION_ID"] = intval($arParams["~SECTION_ID"]);
if ($arParams["SECTION_ID"] > 0 && $arParams["SECTION_ID"] . "" != $arParams["~SECTION_ID"])
{
    ShowError(GetMessage("CATALOG_SECTION_NOT_FOUND"));
    @define("ERROR_404", "Y");
    if ($arParams["SET_STATUS_404"] === "Y")
        CHTTP::SetStatus("404 Not Found");
    return;
}

if (!isset($arParams["GENERAL_PROPERTIES_LIST"]))
    $arParams["GENERAL_PROPERTIES_LIST"] = array("PHOTO", "LOT", "YEAR_OF_RELEASE", "MTBF", "LOCATION", "CONTACTS", "ENABLE_DETAILED_PAGE", "DESCRIPTION_DETAILED_PAGE", "VIDEO", "PRICE_BY");

if (!in_array($arParams["INCLUDE_SUBSECTIONS"], array('Y', 'A', 'N')))
    $arParams["INCLUDE_SUBSECTIONS"] = 'Y';
$arParams["SHOW_ALL_WO_SECTION"] = $arParams["SHOW_ALL_WO_SECTION"] === "Y";

if (empty($arParams["ELEMENT_SORT_FIELD"]))
    $arParams["ELEMENT_SORT_FIELD"] = "sort";
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENT_SORT_ORDER"]))
    $arParams["ELEMENT_SORT_ORDER"] = "asc";
if (empty($arParams["ELEMENT_SORT_FIELD2"]))
    $arParams["ELEMENT_SORT_FIELD2"] = "id";
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENT_SORT_ORDER2"]))
    $arParams["ELEMENT_SORT_ORDER2"] = "desc";

if (strlen($arParams["FILTER_NAME"]) <= 0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
{
    $arrFilter = array();
}
else
{
    global ${$arParams["FILTER_NAME"]};
    $arrFilter = ${$arParams["FILTER_NAME"]};
    if (!is_array($arrFilter))
        $arrFilter = array();
}

$arParams["SECTION_URL"] = trim($arParams["SECTION_URL"]);
$arParams["DETAIL_URL"] = trim($arParams["DETAIL_URL"]);

$arParams["SECTION_ID_VARIABLE"] = trim($arParams["SECTION_ID_VARIABLE"]);
if (strlen($arParams["SECTION_ID_VARIABLE"]) <= 0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["SECTION_ID_VARIABLE"]))
    $arParams["SECTION_ID_VARIABLE"] = "SECTION_ID";

$arParams["SET_TITLE"] = $arParams["SET_TITLE"] != "N";
$arParams["ADD_SECTIONS_CHAIN"] = $arParams["ADD_SECTIONS_CHAIN"] === "Y"; //Turn off by default
$arParams["DISPLAY_COMPARE"] = $arParams["DISPLAY_COMPARE"] == "Y";

$arParams["PAGE_ELEMENT_COUNT"] = intval($arParams["PAGE_ELEMENT_COUNT"]);
if ($arParams["PAGE_ELEMENT_COUNT"] <= 0)
    $arParams["PAGE_ELEMENT_COUNT"] = 20;
$arParams["LINE_ELEMENT_COUNT"] = intval($arParams["LINE_ELEMENT_COUNT"]);
if ($arParams["LINE_ELEMENT_COUNT"] <= 0)
    $arParams["LINE_ELEMENT_COUNT"] = 3;

if (!is_array($arParams["PROPERTY_CODE"]))
    $arParams["PROPERTY_CODE"] = array();
foreach ($arParams["PROPERTY_CODE"] as $k => $v)
    if ($v === "")
        unset($arParams["PROPERTY_CODE"][$k]);

if (!is_array($arParams["PRICE_CODE"]))
    $arParams["PRICE_CODE"] = "BASE";

$arParams["DISPLAY_TOP_PAGER"] = $arParams["DISPLAY_TOP_PAGER"] == "Y";
$arParams["DISPLAY_BOTTOM_PAGER"] = $arParams["DISPLAY_BOTTOM_PAGER"] != "N";
$arParams["PAGER_TITLE"] = trim($arParams["PAGER_TITLE"]);
$arParams["PAGER_SHOW_ALWAYS"] = $arParams["PAGER_SHOW_ALWAYS"] != "N";
$arParams["PAGER_TEMPLATE"] = trim($arParams["PAGER_TEMPLATE"]);
$arParams["PAGER_DESC_NUMBERING"] = $arParams["PAGER_DESC_NUMBERING"] == "Y";
$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] = intval($arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]);
$arParams["PAGER_SHOW_ALL"] = $arParams["PAGER_SHOW_ALL"] !== "N";

$arNavParams = array(
    "nPageSize" => $arParams["PAGE_ELEMENT_COUNT"],
    "bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
    "bShowAll" => $arParams["PAGER_SHOW_ALL"],
);
$arNavigation = CDBResult::GetNavParams($arNavParams);

if ($arNavigation["PAGEN"] == 0 && $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] > 0)
    $arParams["CACHE_TIME"] = $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];

$arParams['CACHE_GROUPS'] = trim($arParams['CACHE_GROUPS']);
if ('N' != $arParams['CACHE_GROUPS'])
    $arParams['CACHE_GROUPS'] = 'Y';

$arParams["CACHE_FILTER"] = $arParams["CACHE_FILTER"] == "Y";
if (!$arParams["CACHE_FILTER"] && count($arrFilter) > 0)
    $arParams["CACHE_TIME"] = 0;



if ($this->StartResultCache(360000, array($arrFilter, ($arParams["CACHE_GROUPS"] === "N" ? false : $USER->GetGroups()), $arNavigation)))
{
    if (!CModule::IncludeModule("iblock") && !CModule::IncludeModule("catalog"))
    {
        $this->AbortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }

    $arResultModules = array(
        'iblock' => true,
        'catalog' => false,
        'currency' => false
    );

    $arSelect = array();
    if (isset($arParams["SECTION_USER_FIELDS"]) && is_array($arParams["SECTION_USER_FIELDS"]))
    {
        foreach ($arParams["SECTION_USER_FIELDS"] as $field)
            if (is_string($field) && preg_match("/^UF_/", $field))
                $arSelect[] = $field;
    }
    if (preg_match("/^UF_/", $arParams["META_KEYWORDS"]))
        $arSelect[] = $arParams["META_KEYWORDS"];
    if (preg_match("/^UF_/", $arParams["META_DESCRIPTION"]))
        $arSelect[] = $arParams["META_DESCRIPTION"];
    if (preg_match("/^UF_/", $arParams["BROWSER_TITLE"]))
        $arSelect[] = $arParams["BROWSER_TITLE"];

    $arFilter = array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "IBLOCK_ACTIVE" => "Y",
        "ACTIVE" => "Y",
        "GLOBAL_ACTIVE" => "Y",
    );

    $bSectionFound = false;

    if ($arParams["BY_LINK"] === "Y")
    {
        $arResult = array(
            "ID" => 0,
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        );
        $bSectionFound = true;
    }
    elseif ($arParams["SECTION_ID"] > 0)
    {
        $arFilter["ID"] = $arParams["SECTION_ID"];
        $rsSection = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
        $rsSection->SetUrlTemplates("", $arParams["SECTION_URL"]);
        $arResult = $rsSection->GetNext();
        if ($arResult)
            $bSectionFound = true;
    }
    elseif (strlen($arParams["SECTION_CODE"]) > 0)
    {
        $arFilter["=CODE"] = $arParams["SECTION_CODE"];
        $rsSection = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
        $rsSection->SetUrlTemplates("", $arParams["SECTION_URL"]);
        $arResult = $rsSection->GetNext();
        if ($arResult)
            $bSectionFound = true;
    }
    else
    {
        //Root section (no section filter)
        $arResult = array(
            "ID" => 0,
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        );
        $bSectionFound = true;
    }

    if (!$bSectionFound)
    {
        $this->AbortResultCache();
        ShowError(GetMessage("CATALOG_SECTION_NOT_FOUND"));
        @define("ERROR_404", "Y");
        if ($arParams["SET_STATUS_404"] === "Y")
            CHTTP::SetStatus("404 Not Found");
        return;
    }
    elseif ($arResult["ID"] > 0 && $arParams["ADD_SECTIONS_CHAIN"])
    {
        $arResult["PATH"] = array();
        $rsPath = CIBlockSection::GetNavChain($arResult["IBLOCK_ID"], $arResult["ID"]);
        $rsPath->SetUrlTemplates("", $arParams["SECTION_URL"]);
        while ($arPath = $rsPath->GetNext())
        {
            $arResult["PATH"][] = $arPath;
        }
    }

    $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arResult["IBLOCK_ID"], $arResult["ID"]);
    $arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();

    //определим ID цены с указанным названием
    if (CModule::IncludeModule("catalog"))
    {
        $dbPriceType = CCatalogGroup::GetList(
                        array("SORT" => "ASC"), array("NAME" => $arParams["PRICE_CODE"])
        );
        if ($arPriceType = $dbPriceType->Fetch())
        {
            $arResult["PRICE_ID"] = $arPriceType["ID"];
        }
    }
    // list of the element fields that will be used in selection
    $arSelect = array(
        "ID",
        "IBLOCK_ID",
        "IBLOCK_SECTION_ID",
        "CODE",
        "XML_ID",
        "NAME",
        "ACTIVE",
        "DATE_ACTIVE_FROM",
        "DATE_ACTIVE_TO",
        "SORT",
        "PREVIEW_TEXT",
        "PREVIEW_TEXT_TYPE",
        "DETAIL_TEXT",
        "DETAIL_TEXT_TYPE",
        "DATE_CREATE",
        "CREATED_BY",
        "TIMESTAMP_X",
        "MODIFIED_BY",
        "TAGS",
        "DETAIL_PAGE_URL",
        "DETAIL_PICTURE",
        "PREVIEW_PICTURE",
        "CATALOG_GROUP_" . $arResult["PRICE_ID"]
    );

    $arFilter = array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "IBLOCK_LID" => SITE_ID,
        "IBLOCK_ACTIVE" => "Y",
        "ACTIVE_DATE" => "Y",
        "ACTIVE" => "Y",
        "CHECK_PERMISSIONS" => "Y",
        "MIN_PERMISSION" => "R",
        "INCLUDE_SUBSECTIONS" => ($arParams["INCLUDE_SUBSECTIONS"] == 'N' ? 'N' : 'Y'),
    );
    if ($arParams["INCLUDE_SUBSECTIONS"] == 'A')
        $arFilter["SECTION_GLOBAL_ACTIVE"] = "Y";
    if ($bIBlockCatalog && 'Y' == $arParams['HIDE_NOT_AVAILABLE'])
        $arFilter['CATALOG_AVAILABLE'] = 'Y';
    if ($arResult["ID"] > 0)
        $arFilter["SECTION_ID"] = $arResult["ID"];
    $arSort = array(
        $arParams["ELEMENT_SORT_FIELD"] => $arParams["ELEMENT_SORT_ORDER"],
        $arParams["ELEMENT_SORT_FIELD2"] => $arParams["ELEMENT_SORT_ORDER2"],
    );

    //EXECUTE
    $rsElements = CIBlockElement::GetList($arSort, array_merge($arrFilter, $arFilter), false, $arNavParams, $arSelect);
    $rsElements->SetUrlTemplates($arParams["DETAIL_URL"]);
    if ($arParams["BY_LINK"] !== "Y" && !$arParams["SHOW_ALL_WO_SECTION"])
        $rsElements->SetSectionContext($arResult);

    $arResult["ITEMS"] = array();


    while ($obElement = $rsElements->GetNextElement())
    {
        $arItem = $obElement->GetFields();
        if ($arItem["PREVIEW_PICTURE"])
        {
            $arPic = CFile::ResizeImageGet(
                            $arItem["PREVIEW_PICTURE"], array("width" => 380, "height" => 245), BX_RESIZE_IMAGE_PROPORTIONAL, true
            );
            $arItem["PICTURE_ID"] = $arItem["PREVIEW_PICTURE"];
            $arItem["BIG_PICTURE"] = CFile::GetPath($arItem["PREVIEW_PICTURE"]);
            unset($arItem["PREVIEW_PICTURE"]);
            $arItem["PREVIEW_PICTURE"] = $arPic;
        }
        $arItem["PROPERTIES"] = $obElement->GetProperties();
        if (isset($arItem["PROPERTIES"]["LOCATION"]))
        {
            $towns = CIBlockElement::GetList(array(), array("IBLOCK_ID" => TOWNS, "ID" => $arItem["PROPERTIES"]["LOCATION"]["VALUE"]), false, false, array("ID", "NAME"));
            if ($town = $towns->GetNext())
                $arItem["PROPERTIES"]["LOCATION"]["VALUE"] = $town["NAME"];
        }

        //
        $res = CIBlockSection::GetByID($arItem["IBLOCK_SECTION_ID"]);

        if (strpos($arParams["DETAIL_URL"], "/#SECTION_CODE#/#ELEMENT_CODE#/") !== FALSE)
        {
            if ($ar_res = $res->GetNext())
                $arItem["SECTION_CODE"] = $ar_res['CODE'];

            $folder = str_replace("#SECTION_CODE#/#ELEMENT_CODE#/", "", $arParams["DETAIL_URL"]);
            $arItem["FOLDER"] = $folder;
            $arItem["DETAIL_PAGE_URL"] = $arItem["FOLDER"] . $arItem["SECTION_CODE"] . "/" . $arItem["CODE"] . "/";
        }

        // $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y", "ID" => $arItem["IBLOCK_SECTION_ID"]), false, array("UF_URL"));
        // if ($section_UF = $sections->GetNext())
        //  {
        // print "<pre UF_URL style='display:none'>"; print_r($section_UF); print "</pre>";
        if (isset($sections_UF[$arItem["IBLOCK_SECTION_ID"]]))
        {
            // $arResult[$key]["LINK_DOMAIN"] = $domains[$section_UF["UF_URL"]];
            $link_domain = $domains[$sections_UF[$arItem["IBLOCK_SECTION_ID"]]];
            
            if (isset($link_domain))
                $arItem["DETAIL_PAGE_URL"] = "http://" . $link_domain . $arItem["DETAIL_PAGE_URL"];
            else
                $arItem["DETAIL_PAGE_URL"] = "http://" . DEFAULT_DOMAIN . $arItem["DETAIL_PAGE_URL"];
        }
        //  }

        /* $arFilter = array('IBLOCK_ID' => CATALOG, "ID"=>$arItem["IBLOCK_SECTION_ID"]);
          $rsSections = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), $arFilter,1,"UF_");
          while ($arSction = $rsSections->Fetch()) {
          echo $arSection['NAME'] . ' LEFT_MARGIN: ' . $arSection['LEFT_MARGIN'] . ' RIGHT_MARGIN: ' . $arSection['RIGHT_MARGIN'] . '<br>';
          } */

        $arResult["ITEMS"][] = $arItem;
    }

    // pr($arResult["ITEMS"], false);

    $arResult["NAV_STRING"] = $rsElements->GetPageNavStringEx($navComponentObject, $arParams["PAGER_TITLE"], $arParams["PAGER_TEMPLATE"], $arParams["PAGER_SHOW_ALWAYS"]);
    $arResult["NAV_CACHED_DATA"] = $navComponentObject->GetTemplateCachedData();
    $arResult["NAV_RESULT"] = $rsElements;
    $this->SetResultCacheKeys(array_keys($arResult));
    $this->IncludeComponentTemplate();
}

if ($arParams["SET_TITLE"])
{
    if ($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "")
        $APPLICATION->SetTitle($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arTitleOptions);
    elseif (isset($arResult["NAME"]))
        $APPLICATION->SetTitle($arResult["NAME"], $arTitleOptions);

    $browserTitle = \Bitrix\Main\Type\Collection::firstNotEmpty(
                    $arResult["PROPERTIES"], array($arParams["BROWSER_TITLE"], "VALUE")
                    , $arResult["IPROPERTY_VALUES"], "SECTION_META_TITLE"
    );
    if (is_array($browserTitle))
        $APPLICATION->SetPageProperty("title", implode(" ", $browserTitle), $arTitleOptions);
    elseif ($browserTitle != "")
        $APPLICATION->SetPageProperty("title", $browserTitle, $arTitleOptions);

    $metaKeywords = \Bitrix\Main\Type\Collection::firstNotEmpty(
                    $arResult["PROPERTIES"], array($arParams["META_KEYWORDS"], "VALUE")
                    , $arResult["IPROPERTY_VALUES"], "SECTION_META_KEYWORDS"
    );
    if (is_array($metaKeywords))
        $APPLICATION->SetPageProperty("keywords", implode(" ", $metaKeywords), $arTitleOptions);
    elseif ($metaKeywords != "")
        $APPLICATION->SetPageProperty("keywords", $metaKeywords, $arTitleOptions);

    $metaDescription = \Bitrix\Main\Type\Collection::firstNotEmpty(
                    $arResult["PROPERTIES"], array($arParams["META_DESCRIPTION"], "VALUE")
                    , $arResult["IPROPERTY_VALUES"], "SECTION_META_DESCRIPTION"
    );
    if (is_array($metaDescription))
        $APPLICATION->SetPageProperty("description", implode(" ", $metaDescription), $arTitleOptions);
    elseif ($metaDescription != "")
        $APPLICATION->SetPageProperty("description", $metaDescription, $arTitleOptions);

    if ($arParams["ADD_SECTIONS_CHAIN"] && isset($arResult["PATH"]) && is_array($arResult["PATH"]))
    {
        foreach ($arResult["PATH"] as $arPath)
        {
            $APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
        }
    }
}

return $arResult["ID"];
?>