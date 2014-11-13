<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
//pr($arResult);
/**/
$obCache = new CPHPCache();
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
}


//------------
 $sections_UF=get_sections_url_on_name();
 
 //pr($sections_UF);
//----------------
//--------

foreach ($arResult as $key => $arItem)
{
    if ($sections_UF[$arItem["TEXT"]] != "")
    {
        $arResult[$key]["LINK_DOMAIN"] = $domains[$sections_UF[$arItem["TEXT"]]];
    }
}


/**/
?>