<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

$filter_subdomain = array('IBLOCK_ID' => SUBDOMAIN, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y');
$ar_sel_domain = array("ID", "PROPERTY_URL");
$els_domain = CIBlockElement::GetList(array(), $filter_subdomain, false, false, $ar_sel_domain);


while ($el_domain = $els_domain->GetNext())
{
    $domains[$el_domain["ID"]] = $el_domain["PROPERTY_URL_VALUE"];
}
$k=0;

if (!empty($arResult))
{
    foreach ($arResult as $key => $arItem)
    {

        if ($arItem["DEPTH_LEVEL"] == 1)
        {
            $arResult["NEW"][$k] = $arItem;

            $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y", "NAME" => $arItem["TEXT"]), false, array("UF_URL"));
            if ($section_UF = $sections->GetNext())
            {
                // print "<pre UF_URL style='display:none'>";         print_r($section_UF);         print "</pre>";

                if ($section_UF["UF_URL"] != "")
                {
                 //   $arResult["NEW"][$k]["LINK_DOMAIN"] = $domains[$section_UF["UF_URL"]];
                }
            }
            
            
            $k++;
        }
    }
}
$this->__component->SetResultCacheKeys(array("NEW"));
?>