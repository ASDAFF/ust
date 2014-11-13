<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

if (!empty($arResult["ITEMS"]))
{



    // print "<pre domains style='display:none'>";
    //  print_r($arResult["ITEMS"]);
    //  print "</pre>";


    foreach ($arResult["ITEMS"] as $key => $arItem)
    {

        if (isset($arItem["PROPERTIES"]["METKA"]["VALUE"][0]))
        {
            $filter_subdomain = array('IBLOCK_ID' => SUBDOMAIN, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', "PROPERTY_METKA" => $arItem["PROPERTIES"]["METKA"]["VALUE"][0]);
            $ar_sel_domain = array("ID", "PROPERTY_URL", "PROPERTY_METKA");
            $els_domain = CIBlockElement::GetList(array(), $filter_subdomain, false, false, $ar_sel_domain);
            $el_domain = $els_domain->GetNext();
            $url_domain = $el_domain["PROPERTY_URL_VALUE"];

            $arResult["ITEMS"][$key]["LINK_DOMAIN"] = $url_domain;

           // print "<pre domains style='display:none'>";
           // print_r($url_domain);
           // print "</pre>";
        }

        $arResult["ITEMS"][$key]["PREVIEW_PICTURE_RESIZE"] = CFile::ResizeImageGet(
                        $arItem["PREVIEW_PICTURE"], array("width" => 184, "height" => 117), BX_RESIZE_IMAGE_PROPORTIONAL, true
        );
        if (!empty($arItem["IBLOCK_SECTION_ID"]))
        {
            $sec = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arItem["IBLOCK_ID"], "ID" => $arItem["IBLOCK_SECTION_ID"]), false);
            if ($section = $sec->GetNext())
                $arResult["ITEMS"][$key]["CATEGORY"] = $section["NAME"];
        }
    }
}
// horizontal_filter_test1
?>
