<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); 
foreach($arResult["ACTIONS"] as $key => $arItem)
{
	if (isset($arItem["METKA"][0]))
    {
        $filter_subdomain = array('IBLOCK_ID' => SUBDOMAIN, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', "PROPERTY_METKA" => $arItem["METKA"][0]);
        $ar_sel_domain = array("ID", "PROPERTY_URL", "PROPERTY_METKA");
        $els_domain = CIBlockElement::GetList(array(), $filter_subdomain, false, false, $ar_sel_domain);
        $el_domain = $els_domain->GetNext();
        $url_domain = $el_domain["PROPERTY_URL_VALUE"];

        $arResult["ACTIONS"][$key]["LINK_DOMAIN"] = $url_domain;
        //  pr($url_domain);
    }
    if($arItem["METKA"][0] == "" || empty($arItem["METKA"][0]))
    {
    	$arResult["ACTIONS"][$key]["LINK_DOMAIN"] = "ust-co.ru";
    }
    if (isset($arResult["ACTIONS"][$key]["LINK_DOMAIN"]))
    {
        $arResult["ACTIONS"][$key]["DETAIL_PAGE_URL"] = "http://" . $url_domain . $arItem["DETAIL_PAGE_URL"];
    }
}
?>