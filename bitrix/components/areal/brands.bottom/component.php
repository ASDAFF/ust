<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

if (CModule::IncludeModule("iblock"))
{
    $cache = new CPHPCache();
    $cache_time = 0;
    $arResult = array();
    $cache_dir_id = 'brandes_bottom';
    $cache_dir_path = '/brandes_bottom/';
    if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path))
    {
        $res = $cache->GetVars();
        if (is_array($res["brandes_bottom"]) && (count($res["brandes_bottom"]) > 0))
            $arResult = array_merge($arResult, $res["brandes_bottom"]);
    }
    else
    {

        $domains = get_domains();

        $res = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), array("IBLOCK_ID" => BRANDES, "ACTIVE" => "Y"), false, false, array("ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_CATALOG", "PROPERTY_GREY_LOGO", "DETAIL_PAGE_URL", "PROPERTY_CATALOG_LINK"));
        while ($brand = $res->GetNext())
        {
            //if($arParams["CATALOG"] == "Y") {				
            unset($secs);
            if (!empty($brand["PROPERTY_CATALOG_VALUE"]))
            {
                unset($sections);
                unset($section);
                $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y", "ID" => $brand["PROPERTY_CATALOG_VALUE"]), false, array("ID", "NAME", "UF_URL", "SECTION_PAGE_URL"));


                while ($section = $sections->GetNext())
                {

                    $section["LINK_DOMAIN"] = $domains[$section["UF_URL"]];
                    if ($section["LINK_DOMAIN"] != "")
                        $section["SECTION_PAGE_URL"] = "http://" . $section["LINK_DOMAIN"] . $section["SECTION_PAGE_URL"];
                    $secs[] = $section;
                }

                // print "<pre domains style='display:none'>";  print_r($secs); print "</pre>";
            }
            /* }  
              else
              $secs = array(); */

            $arResult["BRANDES"][] = array(
                "ID" => $brand["ID"],
                "NAME" => $brand["NAME"],
                "DETAIL_PAGE_URL" => $brand["DETAIL_PAGE_URL"],
                "WHITE_LOGO" => CFile::ResizeImageGet(
                        $brand["DETAIL_PICTURE"], array("width" => 1080, "height" => 100), BX_RESIZE_IMAGE_PROPORTIONAL, true
                ),
                "COLOR_LOGO" => CFile::ResizeImageGet(
                        $brand["PREVIEW_PICTURE"], array("width" => 1080, "height" => 100), BX_RESIZE_IMAGE_PROPORTIONAL, true
                ),
                "GREY_LOGO" => CFile::ResizeImageGet(
                        $brand["PROPERTY_GREY_LOGO_VALUE"], array("width" => 1080, "height" => 100), BX_RESIZE_IMAGE_PROPORTIONAL, true
                ),
                "SECTION_CATALOG" => $secs,
                "CATALOG_LINK" => $brand["PROPERTY_CATALOG_LINK_VALUE"]
            );
        }


        //pr($arResult);

        if ($cache_time > 0)
        {
            $cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
            $cache->EndDataCache(array("brandes_bottom" => $arResult));
        }
    }

    $this->IncludeComponentTemplate();
}
?>