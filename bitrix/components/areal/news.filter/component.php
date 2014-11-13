<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
if (CModule::IncludeModule("iblock"))
{

    $filter_subdomain = array('IBLOCK_ID' => SUBDOMAIN, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y');
    $ar_sel_domain = array("ID", "PROPERTY_URL");
    $els_domain = CIBlockElement::GetList(array(), $filter_subdomain, false, false, $ar_sel_domain);


    while ($el_domain = $els_domain->GetNext())
    {
        $domains[$el_domain["ID"]] = $el_domain["PROPERTY_URL_VALUE"];
    }

    $arFilter = Array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y');


    $sections = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilter, true, array("UF_URL"));
    while ($section = $sections->GetNext())
    {
        // print "<pre style='display:none;'>";
        // print_r($section);
        $arResult['SECTIONS'][] = array(
            "ID" => $section['ID'],
            "NAME" => $section['NAME'],
            "CODE" => $section['CODE'],
            "SECTION_PAGE_URL" => $section['SECTION_PAGE_URL'],
            "ELEMENT_CNT" => $section['ELEMENT_CNT'],
            "SELECTED" => ($arParams["SECTION_CODE"] == $section["CODE"]) ? 1 : 0,
            "LINK_DOMAIN" => $domains[$section["UF_URL"][0]]
        );

        //print "</pre>";
    }


    $current_section = "";
    if (!empty($arParams["SECTION_CODE"]))
    {
        //pr($arParams["SECTION_CODE"]);
       // $current_section = "43";
        foreach ($arResult['SECTIONS'] as $sec)
        {
            if ($sec["SELECTED"] == 1 && $sec["CODE"] == $arParams["SECTION_CODE"])
            {
                $current_section = $sec["ID"];
            }
        }
    }

    if (isset($arParams["SECTION_CODE"]) and $current_section == "")
    {
        header("HTTP/1.0 404 Not Found");
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        header("Location: /404.php");
        exit();
    }

    if (SITE_SERVER_NAME != $_SERVER["SERVER_NAME"]) // поддомен
    {
        $filter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'PROPERTY_METKA' => $ar_metka, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y');
    }
    else
    {
        $filter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y');
    }

    if ($current_section)
        $filter = array_merge($filter, array("SECTION_ID" => $current_section));

    $filter = array_merge($filter, array('SECTION_ACTIVE' => 'Y'));

    //pr($filter);
    $elements = CIBlockElement::GetList(array(), $filter, false, false, array("DATE_ACTIVE_FROM", "ID"));


    while ($element = $elements->GetNext())
    {
        list($month, $year) = explode(" ", CIBlockFormatProperties::DateFormat("m:f Y", MakeTimeStamp($element["DATE_ACTIVE_FROM"], CSite::GetDateFormat())));
        list($month_number, $month_string) = explode(":", $month);
        $years[$year][$month_number] = $month_string;
    }
    foreach ($years as $key => $year)
        ksort($years[$key]);
    krsort($years);
    $arResult['YEARS'] = $years;

    $this->IncludeComponentTemplate();

    global $arrFilter;

    if (SITE_SERVER_NAME != $_SERVER["SERVER_NAME"])
        $arrFilter["PROPERTY_METKA"] = $ar_metka; // добавляем метки для поддомена
    $arrFilter["SECTION_ACTIVE"] = "Y";

    if (!empty($_REQUEST["year"]))
    {
        if (isset($_REQUEST["month"]) && $_REQUEST["month"] > 0)
        {
            $arrFilter['><DATE_ACTIVE_FROM'] = array(
                date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")), mktime(0, 0, 0, $_REQUEST['month'], 1, $_REQUEST['year'])),
                date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")), mktime(0, 0, 0, ($_REQUEST['month'] + 1), 0, $_REQUEST['year']))
            );
        }
        else
        {
            $arrFilter['><DATE_ACTIVE_FROM'] = array(
                date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")), mktime(0, 0, 0, 1, 1, $_REQUEST['year'])),
                date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")), mktime(0, 0, 0, 12, 31, $_REQUEST['year']))
            );
        }
    }
    else
        unset($arrFilter['><DATE_ACTIVE_FROM']);

    //pr($arrFilter);
}
?>