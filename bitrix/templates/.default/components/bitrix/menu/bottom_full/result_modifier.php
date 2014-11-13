<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

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

//----------------


 
$cacheID = 'sectionsCatalogUrl';
$cachePath = '/' . $cacheID;
if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
{
    $vars = $obCache->GetVars();
    $sections_UF = $vars['sectionsCatalogUrl'];
}
elseif ($obCache->StartDataCache())
{
    $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y"), false, array("NAME", "UF_URL"));
    while ($section_UF = $sections->GetNext())
    {
        $sections_UF[$section_UF["NAME"]] = $section_UF["UF_URL"];
    }
    $obCache->EndDataCache(array('sectionsCatalogUrl' => $sections_UF));
}

$sectionsCatalogUrl=$sections_UF;
unset($sections_UF);

 
$cacheID = 'sectionsArendaUrl';
$cachePath = '/' . $cacheID;
if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
{
    $vars = $obCache->GetVars();
    $sections_UF = $vars['sectionsArendaUrl'];
}
elseif ($obCache->StartDataCache())
{
    $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y"), false, array("NAME", "UF_URL"));
    while ($section_UF = $sections->GetNext())
    {
        $sections_UF[$section_UF["NAME"]] = $section_UF["UF_URL"];
    }
    $obCache->EndDataCache(array('sectionsArendaUrl' => $sections_UF));
}


$sections_UF=array_merge($sectionsCatalogUrl,$sections_UF);

//----------------

 
foreach ($arResult as $key => $arItem)
{
    if ($sections_UF[$arItem["TEXT"]] != "")
    {
        $arResult[$key]["LINK_DOMAIN"] = $domains[$sections_UF[$arItem["TEXT"]]];
    }
}

?>