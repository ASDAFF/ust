<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

if (CModule::IncludeModule("iblock"))
{
   
    $arResult = array();
    $id_sect = "";
    if (isset($arParams["SECTION_ID"]))
    {
        $id_sect = (int) $arParams["SECTION_ID"];
    }

  
    if ($this->StartResultCache(FALSE, "sect".$id_sect))
    {
        if ($id_sect == "")
        {
            // pr(1);
            $res = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => BANNERS_MAIN_PAGE, "ACTIVE" => "Y"), false, false, array("ID", "PREVIEW_PICTURE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "NAME", "PROPERTY_URL"));
        }
        else
        {
            //pr(2);
            $res = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => BANNERS_MAIN_PAGE, "SECTION_ID" => $id_sect, "ACTIVE" => "Y"), false, false, array("ID", "PREVIEW_PICTURE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "NAME", "PROPERTY_URL"));
        }

        while ($element = $res->GetNext())
        {
            unset($url);
            if (!empty($element["PROPERTY_URL_VALUE"]))
            {
                if (stripos($element["PROPERTY_URL_VALUE"], "http://") === false && stripos($element["PROPERTY_URL_VALUE"], "https://") === false)
                    $url = "http://" . $element["PROPERTY_URL_VALUE"];
                else
                    $url = $element["PROPERTY_URL_VALUE"];
            }
            $arResult["BANNERS"][] = array(
                "ID" => $element["ID"],
                "NAME" => $element["NAME"],
                "PREVIEW_TEXT" => $element["PREVIEW_TEXT"],
                "PREVIEW_TEXT_TYPE" => $element["PREVIEW_TEXT_TYPE"],
                "PREVIEW_PICTURE" => CFile::GetPath($element["PREVIEW_PICTURE"]),
                "URL" => $url
            );
        }
        /* if ($cache_time > 0)
          {
          $cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
          $cache->EndDataCache(array("actions_banner".$id_sect => $arResult));
          } */
    }

    if (!empty($arResult["BANNERS"]) && CModule::IncludeModule("ust"))
    {
        $arResult["SPEED"] = COption::GetOptionInt("ust", "speed_of_main_banner");
    }
    $this->IncludeComponentTemplate();
}
?>