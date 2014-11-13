<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

if (CModule::IncludeModule("iblock"))
{

    $domains = get_domains();
    //$sections_Domain = get_sections_url_on_name();
    $obCache = new CPHPCache();
    $domains = get_domains();
    $cacheLifetime = 360000;
    $cacheID = 'sectionsAction';
    $cachePath = '/' . $cacheID;
    if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
    {
        $vars = $obCache->GetVars();
        $sections_UF = $vars['sectionsAction'];
    }
    elseif ($obCache->StartDataCache())
    {
        $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => ACTIONS, "ACTIVE" => "Y"), false, array("NAME", "UF_URL"));
        while ($section_UF = $sections->GetNext())
        {
            $sections_UF[$section_UF["NAME"]] = $section_UF["UF_URL"];
        }
        $obCache->EndDataCache(array('sectionsAction' => $sections_UF));
    }


    $cache = new CPHPCache();
    $cache_time = 3600;
    $arResult = array();
    $cache_dir_id = 'actions_filter';
    $cache_dir_path = '/actions_filter/';
    if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path))
    {
        $res = $cache->GetVars();
        if (is_array($res["actions_filter"]) && (count($res["actions_filter"]) > 0))
            $arResult = array_merge($arResult, $res["actions_filter"]);
    }
    else
    {
        $sections = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => ACTIONS, "ACTIVE" => "Y"), false);
        while ($element = $sections->GetNext())
            $arResult["FILTER"][] = array(
                "ID" => $element["ID"],
                "NAME" => $element["NAME"],
                "CODE" => $element["CODE"],
                "LINK_DOMAIN" => $domains[$sections_UF[$element["NAME"]]],
                "SECTION_PAGE_URL" => $element["SECTION_PAGE_URL"],
                "SELECTED" => 0
            );
        if ($cache_time > 0)
        {
            $cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
            $cache->EndDataCache(array("actions_filter" => $arResult));
        }
    }
    $arResult["SELECTING"] = 0;
    if (isset($arParams["SECTION_CODE"]) && !empty($arParams["SECTION_CODE"]))
        foreach ($arResult["FILTER"] as $key => $filter)
            if ($filter["CODE"] == $arParams["SECTION_CODE"])
            {
                $arResult["FILTER"][$key]["SELECTED"] = 1;
                $arResult["SELECTING"] = 1;
            }
    $this->IncludeComponentTemplate();
}
?>