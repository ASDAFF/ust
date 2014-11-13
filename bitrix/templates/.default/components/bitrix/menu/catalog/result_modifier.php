<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
if (!empty($arResult))
{
    foreach ($arResult as $key => $value)
    {
        if ($value["DEPTH_LEVEL"] == 1)
        {
            $depth_1[] = $key;
        }
    }
    foreach ($depth_1 as $key => $level)
    {
        $flag = 0;
        for ($i = $level + 1; $i < $depth_1[$key + 1]; $i++)
        {
            if ($arResult[$i]["SELECTED"])
                $flag = 1;
        }
        if ($flag == 1)
        {
            $arResult[$level]["SELECTED"] = 1;
            $arResult[$level]["SELECTED_THIS"] = 1;
        }
        else
            $arResult[$level]["SELECTED_THIS"] = 0;
    }


  /*  $obCache = new CPHPCache();
    $cacheLifetime = 360000;
    $cacheID = 'domains';
    $cachePath = '/' . $cacheID;
    if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
    {
        $vars = $obCache->GetVars();

        $domains = $vars['domains'];
    }
    elseif ($obCache->StartDataCache())
    {
        $filter_subdomain = array('IBLOCK_ID' => SUBDOMAIN, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y');
        $ar_sel_domain = array("ID", "PROPERTY_URL");
        $els_domain = CIBlockElement::GetList(array(), $filter_subdomain, false, false, $ar_sel_domain);


        while ($el_domain = $els_domain->GetNext())
        {
            $domains[$el_domain["ID"]] = $el_domain["PROPERTY_URL_VALUE"];
        }
        $obCache->EndDataCache(array('domains' => $domains));
    }*/
    
    $domains=  get_domains();



//-------------
 $sections_UF=get_sections_url_on_name();


//--------
    foreach ($arResult as $key => $arItem)
    {

        if ($sections_UF[$arItem["TEXT"]] != "")
        {
            $arResult[$key]["LINK_DOMAIN"] = $domains[$sections_UF[$arItem["TEXT"]]];
        }
    }
}
?>