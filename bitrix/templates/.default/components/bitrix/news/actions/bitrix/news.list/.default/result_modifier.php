<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

if (!empty($arResult["ITEMS"]))
{
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
            //  pr($url_domain);
        }

        if (isset($arResult["ITEMS"][$key]["LINK_DOMAIN"]))
        {
            $arResult["ITEMS"][$key]["DETAIL_PAGE_URL"] = "http://" . $url_domain . $arItem["DETAIL_PAGE_URL"];
        }

        $arResult["ITEMS"][$key]["PREVIEW_PICTURE_RESIZE"] = CFile::ResizeImageGet(
                        $arItem["PREVIEW_PICTURE"], array("width" => 278, "height" => 139), BX_RESIZE_IMAGE_PROPORTIONAL, true
        );
        $arResult["ITEMS"][$key]["TIME_LEFT"] = downcounter($arItem["ACTIVE_TO"]);
    }
}
?>