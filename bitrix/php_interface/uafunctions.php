<?

function get_sections_name_on_code()
{
    $obCache = new CPHPCache();
    $cacheLifetime = 360000;
    $cacheID = 'sectionsName';
    $cachePath = '/' . $cacheID;
    if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
    {
        $vars = $obCache->GetVars();
        $sections_Names = $vars['sectionsName'];
    }
    elseif ($obCache->StartDataCache())
    {
        $sections_Names=array();
        $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y"), false, array("NAME", "UF_URL", "CODE"));
        while ($section_Code = $sections->GetNext())
        {
            $sections_Names[$section_Code["CODE"]] = $section_Code["NAME"];
        }

        $obCache->EndDataCache(array('sectionsName' => $sections_Names));
    }
    return $sections_Names;
}

function get_catalog_element_name_on_code()
{
    $obCache = new CPHPCache();
    $cacheLifetime = 360000;
    $cacheID = 'catalogElementsName';
    $cachePath = '/' . $cacheID;
    if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
    {
        $vars = $obCache->GetVars();
        $element_Names = $vars[$cacheID];
    }
    elseif ($obCache->StartDataCache())
    {
        $element_Names=array();
        $sections = CIBlockElement::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y"), false, false, array("NAME", "ID", "CODE"));
        while ($section_Code = $sections->GetNext())
        {
            $element_Names[$section_Code["CODE"]] = $section_Code["NAME"];
        }

        $obCache->EndDataCache(array($cacheID => $element_Names));
    }
    return $element_Names;
}

?>