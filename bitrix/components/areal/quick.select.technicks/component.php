<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

if (CModule::IncludeModule("iblock"))
{
    $domains = get_domains();
    $sections_Domain = get_sections_url_on_name();
 
    $cache = new CPHPCache();
    $cache_time = 3600;
    $arResult = array();
    $cache_dir_id = 'quick_catalog';
    $cache_dir_path = '/quick_catalog/';
    if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path))
    {
        $res = $cache->GetVars();
        if (is_array($res["quick_catalog"]) && (count($res["quick_catalog"]) > 0))
            $arResult = array_merge($arResult, $res["quick_catalog"]);
    }
    else
    {
        $sections = CIBlockSection::GetList(array("SORT" => "ASC", "NAME" => "ASC"), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y", "DEPTH_LEVEL" => 1), false);
        while ($section = $sections->GetNext())
        {
            unset($preview);
            unset($elements);
            unset($element);
            unset($sections_second);
            $preview = CFile::ResizeImageGet(
                            $section["PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true
            );
            $detail_preview = CFile::ResizeImageGet(
                            $section["DETAIL_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true
            );
            $elements = CIBlockSection::GetList(array("SORT" => "ASC", "NAME" => "ASC"), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y", "DEPTH_LEVEL" => 2, "SECTION_ID" => $section["ID"]), false);
            while ($element = $elements->GetNext())
            {
                $sections_second[] = array(
                    "ID" => $element["ID"],
                    "NAME" => $element["NAME"],
                    "LINK_DOMAIN" => $domains[$sections_Domain[$element["NAME"]]],
                    "SECTION_PAGE_URL" => $element["SECTION_PAGE_URL"],
                    "PICTURE" => CFile::ResizeImageGet(
                            $element["PICTURE"], array("width" => 100, "height" => 70), BX_RESIZE_IMAGE_PROPORTIONAL, true
                    )
                );
            }
            $arResult["SECTIONS"][] = array(
                "ID" => $section["ID"],
                "NAME" => $section["NAME"],
                "LINK_DOMAIN" => $domains[$sections_Domain[$section["NAME"]]],
                "SECTION_PAGE_URL" => $section["SECTION_PAGE_URL"],
                "PICTURE" => $preview,
                "DETAIL_PICTURE" => $detail_preview,
                "ITEMS" => $sections_second
            );
        }
        if ($cache_time > 0)
        {
            $cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
            $cache->EndDataCache(array("quick_catalog" => $arResult));
        }
    }
    $this->IncludeComponentTemplate();
}
?>