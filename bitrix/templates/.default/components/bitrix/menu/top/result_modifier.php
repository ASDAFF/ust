<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?

$flag = 0;
$subdomain = false;

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

/*
  $filter_subdomain = array('IBLOCK_ID' => SUBDOMAIN, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y');
  $ar_sel_domain = array("ID", "PROPERTY_URL");
  $els_domain = CIBlockElement::GetList(array(), $filter_subdomain, false, false, $ar_sel_domain);


  while($el_domain = $els_domain->GetNext())
  {
  $domains[$el_domain["ID"]]=$el_domain["PROPERTY_URL_VALUE"];
  } /* */


// -------
//$sections_UF=get_sections_url_on_name();

$sections_Data = get_sections_data_on_name();

//pr($sections_Data);
//----------


foreach ($arResult as $key => $arItem)
{
    if ($arItem["LINK"] == "/catalog/" && $arItem["DEPTH_LEVEL"] == 1)
    {
        $flag = 1;
    }
    elseif ($arItem["DEPTH_LEVEL"] == 1)
    {
        $flag = 0;
    }
    if ($arItem["DEPTH_LEVEL"] == 2 && $flag == 1)
    {
        unset($sections);
        unset($section);
        // $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y", "NAME" => $arItem["TEXT"]), false, false, array("NAME", "DESCRIPTION", "PICTURE", "DETAIL_PICTURE"));
        if (isset($sections_Data[$arItem["TEXT"]]))
        {
            $section = $sections_Data[$arItem["TEXT"]];
            if (!empty($section["DESCRIPTION"]))
            {
                $arResult[$key]["DESCRIPTION"] = $section["DESCRIPTION"];
                $arResult[$key]["DESCRIPTION_TYPE"] = $section["DESCRIPTION_TYPE"];
            }
            if (!empty($section["PICTURE"]))
            {
                $arResult[$key]["PICTURE"] = CFile::ResizeImageGet(
                                $section["PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true
                );
                $arResult[$key]["DETAIL_PICTURE"] = CFile::ResizeImageGet(
                                $section["DETAIL_PICTURE"], array("width" => 30, "height" => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true
                );
            }
            //  print "<pre style='display:none'>";
            // print_r($section);
            // print "</pre>";

            if (!empty($section["UF_URL"]))
            {
                //$arResult[$key]["UF_URL"] = $section["UF_URL"];
            }
        }
        if ($arItem["LINK"] == "/catalog/bu-stroitelnaya-tehnika/" or strpos($arItem["LINK"], "/catalog/bu-skladskaya-tehnika/") !== false)
        {
            $arResult[$key]["PICTURE"] = array(
                "src" => "/images/bu_gray.png",
                "width" => 30,
                "height" => 30
            );
            $arResult[$key]["DETAIL_PICTURE"] = array(
                "src" => "/images/bu_red.png",
                "width" => 30,
                "height" => 30
            );
        }
    }

    if (is_array($sections_Data[$arItem["TEXT"]]["UF_URL"]))
    {
        $arResult[$key]["LINK_DOMAIN"] = $domains[$sections_Data[$arItem["TEXT"]]["UF_URL"][0]];
    }
    elseif ($sections_Data[$arItem["TEXT"]]["UF_URL"] != "")
    {
        $arResult[$key]["LINK_DOMAIN"] = $domains[$sections_Data[$arItem["TEXT"]]["UF_URL"]];
    }

    if (isset($arItem["PARAMS"]["DOMENID"]) and $subdomain == true)
    {

        if (!in_array($el_domain["ID"], $arItem["PARAMS"]["DOMENID"]))
        {
            //  unset($arResult[$key]);
        }/**/
    }

    if ($arItem["UF_URL_VALUE"] != "")
    {
        //print_r($arItem);
    }
}
?>