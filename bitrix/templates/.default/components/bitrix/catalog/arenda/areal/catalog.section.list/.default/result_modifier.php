<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

$domains = get_domains();


$obCache = new CPHPCache();
$domains = get_domains();
$cacheLifetime = 360000;
$cacheID = 'sectionsArenda';
$cachePath = '/' . $cacheID;
if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
{
    $vars = $obCache->GetVars();
    $sections_UF = $vars['sectionsArenda'];
}
elseif ($obCache->StartDataCache())
{
    $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => ARENDA, "ACTIVE" => "Y"), false, array("NAME", "UF_URL"));
    while ($section_UF = $sections->GetNext())
    {
        $sections_UF[$section_UF["NAME"]] = $section_UF["UF_URL"];
    }
    $obCache->EndDataCache(array('sectionsArenda' => $sections_UF));
}



if (!empty($arResult["SECTIONS"]))
{
    foreach ($arResult["SECTIONS"] as $arSec)
    {
        if ($arSec["SELECTED"] == 1)
        {
            if (!empty($arSec["IBLOCK_SECTION_ID"]))
            {
                $firsrSelected = $arSec["IBLOCK_SECTION_ID"];
            }
            else
                $firsrSelected = $arSec["ID"];
        }
    }
}
foreach ($arResult["SECTIONS"] as $key => $arSection)
{
    if ($arSection["ID"] == $firsrSelected && $arSection["DEPTH_LEVEL"] == 1)
        $arResult["SECTIONS"][$key]["SELECTED"] = 1;
    
    if (isset($sections_UF[$arSection["NAME"]]))
    {
        $arResult["SECTIONS"][$key]["LINK_DOMAIN"] = $domains[$sections_UF[$arSection["NAME"]]];
        // pr($arResult["SECTIONS"][$key]["LINK_DOMAIN"]);
    }
}
$arResult["SELECTING_SECTION"] = $firsrSelected;


?>