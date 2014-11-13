<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
global $DB;
global $USER;
global $APPLICATION;
global $CACHE_MANAGER;
//print 1;
if (!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
$arParams["SECTION_ID"] = intval($arParams["SECTION_ID"]);

$arParams["ELEMENT_ID"] = intval($arParams["~ELEMENT_ID"]);
if ($arParams["ELEMENT_ID"] > 0 && $arParams["ELEMENT_ID"] . "" != $arParams["~ELEMENT_ID"])
{
    ShowError(GetMessage("CATALOG_ELEMENT_NOT_FOUND"));
    @define("ERROR_404", "Y");
    if ($arParams["SET_STATUS_404"] === "Y")
        CHTTP::SetStatus("404 Not Found");
    return;
}

$arParams["SECTION_URL"] = trim($arParams["SECTION_URL"]);
$arParams["DETAIL_URL"] = trim($arParams["DETAIL_URL"]);

$arParams["META_KEYWORDS"] = trim($arParams["META_KEYWORDS"]);
$arParams["META_DESCRIPTION"] = trim($arParams["META_DESCRIPTION"]);
$arParams["BROWSER_TITLE"] = trim($arParams["BROWSER_TITLE"]);

$arParams["SET_TITLE"] = $arParams["SET_TITLE"] != "N";
$arParams["ADD_SECTIONS_CHAIN"] = $arParams["ADD_SECTIONS_CHAIN"] != "N"; //Turn on by default

$arParams['CACHE_GROUPS'] = trim($arParams['CACHE_GROUPS']);
if ('N' != $arParams['CACHE_GROUPS'])
    $arParams['CACHE_GROUPS'] = 'Y';

$arParams['USE_ELEMENT_COUNTER'] = (isset($arParams['USE_ELEMENT_COUNTER']) && 'N' == $arParams['USE_ELEMENT_COUNTER'] ? 'N' : 'Y');

if ($this->StartResultCache(false, ($arParams["CACHE_GROUPS"] === "N" ? false : $USER->GetGroups())))
{
    if (!CModule::IncludeModule("iblock") || !CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog"))
    {
        $this->AbortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return 0;
    }

    $arSelect = array(
        "ID",
        "IBLOCK_ID",
        "IBLOCK_SECTION_ID",
        "CODE",
        "NAME",
        "ACTIVE",
        "PREVIEW_TEXT",
        "PREVIEW_PICTURE",
        "DETAIL_PICTURE",
        "PREVIEW_TEXT_TYPE"
    );
    if (is_array($arParams["PERMANENT_PROPERTY"]))
        $default_prop = $arParams["PERMANENT_PROPERTY"];
    else
        $default_prop = array(
            "BRAND",
            "PRICE",
            "OLD_PRICE",
            "ACTIONS",
            "NEW",
            "SALE",
            "CREDIT",
            "SEASONAL",
            "ATTACHMENTS",
            "OPTIONS",
            "VIDEO",
            "PHOTO",
            "SERIES",
            "GROUP_PAGE",
            "RELATED_PRODUCTS",
            "INTERESTED_PRODUCTS",
            "DISPLAY_AS_SERIES",
            "SHORT_NAME"
        );
    $arRelatedProducts = array();
    $arInterestedProducts = array();

    if (isset($arParams["ELEMENT_CODE"]))
    {
        $groups = CIBlockElement::GetList(array(), array("IBLOCK_ID" => GROUPING_ELEMENT, "ACTIVE" => "Y", "CODE" => $arParams["ELEMENT_CODE"]), false, false, array("IBLOCK_ID", "ID", "NAME", "CODE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "PREVIEW_PICTURE", "DETAIL_PICTURE"));
        if ($group = $groups->GetNextElement())
        {
            $arResult = $group->GetFields();
            $arProps = $group->GetProperties();
            /*Сохраняем фото*/
            if (!empty($arResult["DETAIL_PICTURE"]))
                        $arPhoto[] = $arResult["DETAIL_PICTURE"];
            
           // pr($arResult);
            /* Выборка всех элементов группы */
            $arSeries = array();
            $elements_in_group = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "PROPERTY_GROUP_PAGE" => $arResult["CODE"], "!PROPERTY_DISPLAY_AS_SERIES" => false), false, false, array("IBLOCK_ID", "ID", "IBLOCK_SECTION_ID", "NAME", "PROPERTY_GROUP_PAGE", "PROPERTY_DISPLAY_AS_SERIES", "PROPERTY_SERIES"));
            while ($element_in_group = $elements_in_group->GetNextElement())
            {
                unset($item);
                $fields = $element_in_group->GetFields();
                $item_properties = $element_in_group->GetProperties();
                if (!in_array($item_properties["SERIES"]["VALUE"], $arSeries))
                    $arSeries[] = $item_properties["SERIES"]["VALUE"];
                $item = array(
                    "ID" => $fields["ID"],
                    "NAME" => $fields["NAME"],
                    "SHORT_NAME" => $item_properties["SHORT_NAME"]["VALUE"],
                );
                /* Характеристики элементов группировки */
                foreach ($item_properties as $key => $prop)
                {
                    if (!in_array($key, $default_prop))
                        $item["PROPERTIES"][$key] = $prop;
                }
                if (!empty($item_properties["ATTACHMENTS"]["VALUE"]))
                    $arAttachments = $item_properties["ATTACHMENTS"]["VALUE"];

                if (!empty($item_properties["RELATED_PRODUCTS"]["VALUE"]))
                    $arRelatedProducts = array_merge($arRelatedProducts, $item_properties["RELATED_PRODUCTS"]["VALUE"]);
                if (!empty($item_properties["INTERESTED_PRODUCTS"]["VALUE"]))
                    $arInterestedProducts = array_merge($arInterestedProducts, $item_properties["INTERESTED_PRODUCTS"]["VALUE"]);

                /* Опеределение раздела элементов */
                $arResult["IBLOCK_SECTION_ID"] = $fields["IBLOCK_SECTION_ID"];
                $arResult["IBLOCK_ID"] = $fields["IBLOCK_ID"];
                $arElements[] = $item;
            }
            $arResult["ITEMS"] = $arElements;

            /* Вычисление всех серий группы для получения фотографий и видеозаписей */
            if (!empty($arSeries))
            {
                $serieses = CIBlockElement::GetList(array(), array("IBLOCK_ID" => SERIES_ELEMENT, "ACTIVE" => "Y", "ID" => $arSeries), false, false, array("ID", "PROPERTY_PHOTO", "PROPERTY_VIDEO"));
                while ($series = $serieses->GetNext())
                {
                    /* Фотографии
                    if (!empty($series["PROPERTY_PHOTO_VALUE"]))
                        $arPhoto = $series["PROPERTY_PHOTO_VALUE"]; */
                    /* Видео */
                    if (!empty($series["PROPERTY_VIDEO_VALUE"]))
                        $arVideo = $series["PROPERTY_VIDEO_VALUE"];
                }
            }
            
             
             
            $arParams["TYPE"] = "SERIES";
        }
        else
        {
            $serieses = CIBlockElement::GetList(array(), array("IBLOCK_ID" => SERIES_ELEMENT, "ACTIVE" => "Y", "CODE" => $arParams["ELEMENT_CODE"]), false, false, array("IBLOCK_ID", "ID", "NAME", "CODE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE","PREVIEW_PICTURE","DETAIL_PICTURE", "PROPERTY_PHOTO"));
                  
            if ($series = $serieses->GetNextElement())
            {
                $arResult = $series->GetFields();
                $arProps = $series->GetProperties();
                /* Фотографии 
                if (!empty($arProps["PROPERTY_PHOTO_VALUE"]["VALUE"]))
                    $arPhoto = $arProps["PHOTO"]["VALUE"];*/
                /* Видео */
                if (!empty($arProps["VIDEO"]["VALUE"]))
                    $arVideo = $arProps["VIDEO"]["VALUE"];

                $arParams["TYPE"] = "SERIES";
               
                /* Выборка всех элементов серии */
                $elements_in_group = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "PROPERTY_GROUP_PAGE" => $arResult["CODE"], "!PROPERTY_DISPLAY_AS_SERIES" => false, "PROPERTY_SERIES" => $arResult["ID"]), false, false, array("IBLOCK_ID", "IBLOCK_SECTION_ID", "ID", "NAME", "PROPERTY_GROUP_PAGE", "PROPERTY_DISPLAY_AS_SERIES", "PROPERTY_SERIES"));
                while ($element_in_group = $elements_in_group->GetNextElement())
                {
                    unset($item);
                    $fields = $element_in_group->GetFields();
                    $item_properties = $element_in_group->GetProperties();
                    if ($fields["IBLOCK_SECTION_ID"])
                        $arResult["IBLOCK_SECTION_ID"] = 1;

                    if (!in_array($item_properties["SERIES"]["VALUE"], $arSeries))
                        $arSeries[] = $item_properties["SERIES"]["VALUE"];
                    $item = array(
                        "ID" => $fields["ID"],
                        "NAME" => $fields["NAME"],
                        "SHORT_NAME" => $item_properties["SHORT_NAME"]["VALUE"],
                    );
                    /* Характеристики элементов группировки */
                    foreach ($item_properties as $key => $prop)
                    {
                        if (!in_array($key, $default_prop))
                            $item["PROPERTIES"][$key] = $prop;
                    }
                    if (!empty($item_properties["ATTACHMENTS"]["VALUE"]))
                        $arAttachments = $item_properties["ATTACHMENTS"]["VALUE"];

                    if (!empty($item_properties["RELATED_PRODUCTS"]["VALUE"]))
                        $arRelatedProducts = array_merge($arRelatedProducts, $item_properties["RELATED_PRODUCTS"]["VALUE"]);
                    if (!empty($item_properties["INTERESTED_PRODUCTS"]["VALUE"]))
                        $arInterestedProducts = array_merge($arInterestedProducts, $item_properties["INTERESTED_PRODUCTS"]["VALUE"]);

                    /* Опеределение раздела элементов */
                    $arResult["IBLOCK_SECTION_ID"] = $fields["IBLOCK_SECTION_ID"];
                    $arResult["IBLOCK_ID"] = $fields["IBLOCK_ID"];

                    $arElements[] = $item;
                }
                $arResult["ITEMS"] = $arElements;
            }
            else
            {
                $elements = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "CODE" => $arParams["ELEMENT_CODE"]), false, false, $arSelect);
                if ($element = $elements->GetNextElement())
                {
                    $arResult = $element->GetFields();

                    /* $ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arItem["IBLOCK_ID"], $arItem["ID"]);
                      $arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();

                      $arResult["PREVIEW_PICTURE"] = (0 < $arResult["PREVIEW_PICTURE"] ? CFile::GetFileArray($arResult["PREVIEW_PICTURE"]) : false);
                      if ($arResult["PREVIEW_PICTURE"])
                      {
                      $arResult["PREVIEW_PICTURE"]["ALT"] = $arResult["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"];
                      if ($arResult["PREVIEW_PICTURE"]["ALT"] == "")
                      $arResult["PREVIEW_PICTURE"]["ALT"] = $arResult["NAME"];
                      $arResult["PREVIEW_PICTURE"]["TITLE"] = $arResult["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"];
                      if ($arResult["PREVIEW_PICTURE"]["TITLE"] == "")
                      $arResult["PREVIEW_PICTURE"]["TITLE"] = $arResult["NAME"];
                      } */

                    $arProps = $element->GetProperties();
                    $arParams["TYPE"] = "ELEMENT";
                    /* Фотографии 
                    if (!empty($arProps["PHOTO"]["VALUE"]))
                        $arPhoto = $arProps["PHOTO"]["VALUE"];*/
                    /* Видео */
                    if (!empty($arProps["VIDEO"]["VALUE"]))
                        $arVideo = $arProps["VIDEO"]["VALUE"];
                    /* Навесное оборудование */
                    if (!empty($arProps["ATTACHMENTS"]["VALUE"]))
                        $arAttachments = $arProps["ATTACHMENTS"]["VALUE"];
                    if (!empty($arProps["RELATED_PRODUCTS"]["VALUE"]))
                        $arRelatedProducts = array_merge($arRelatedProducts, $arProps["RELATED_PRODUCTS"]["VALUE"]);
                    if (!empty($arProps["INTERESTED_PRODUCTS"]["VALUE"]))
                        $arInterestedProducts = array_merge($arInterestedProducts, $arProps["INTERESTED_PRODUCTS"]["VALUE"]);

                    $item = array(
                        "ID" => $arResult["ID"],
                        "NAME" => $arResult["NAME"],
                        "SHORT_NAME" => $arProps["SHORT_NAME"]["VALUE"],
                    );
                    /* Характеристики */
                    foreach ($arProps as $key => $prop)
                    {
                        if (!in_array($key, $default_prop))
                            $item["PROPERTIES"][$key] = $prop;
                        else
                            $arResult["PROPERTIES"][$key] = $prop;
                    }
                    $arResult["ITEMS"][] = $item;
                }
            }
        }

        /* Обработка опций и характеристик */
        if (is_array($arProps["OPTIONS"]["VALUE"]))
            $arResult["OPTIONS"] = $arProps["OPTIONS"]["~VALUE"];
        if (is_array($arProps["CHARACTERISTICS"]["VALUE"]))
            $arResult["CHARACTERISTICS"] = $arProps["CHARACTERISTICS"]["~VALUE"];

        /* Обработка фотографий */
        if (!empty($arPhoto))
        {
           //pr($arPhoto);
            foreach ($arPhoto as $photo)
            {
                unset($photos);
                $photos["NATURE"] = CFile::GetPath($photo);
                $photos["STANDART"] = CFile::ResizeImageGet(
                                $photo, array("width" => 388, "height" => 278), BX_RESIZE_IMAGE_PROPORTIONAL, true
                );
                $photos["SMALL"] = CFile::ResizeImageGet(
                                $photo, array("width" => 125, "height" => 90), BX_RESIZE_IMAGE_PROPORTIONAL, true
                );
                $arResult["PHOTOS"][] = $photos;
            }
            //  pr($arResult["PHOTOS"]); 
        }

        /* Обработка видеозаписей */
        if (!empty($arVideo))
        {
            $videos = CIBlockElement::GetList(array("SORT" => "ASC", "CREATED" => "ASC"), array("IBLOCK_ID" => VIDEO, "ACTIVE" => "Y", "ID" => $arVideo), false, false, array("ID", "NAME", "PREVIEW_PICTURE"));
            while ($video = $videos->GetNext())
            {
                $arResult["VIDEO"][] = array(
                    "ID" => $video["ID"],
                    "NAME" => $video["NAME"],
                    "PREVIEW_PICTURE" => CFile::ResizeImageGet(
                            $video["PREVIEW_PICTURE"], array("width" => 257, "height" => 149), BX_RESIZE_IMAGE_PROPORTIONAL, true
                    )
                );
            }
        }

        /* Навесное оборудование */
        
        
        if (!empty($arAttachments))
        {
            $attachments = CIBlockElement::GetList(array("SORT" => "ASC", "NAME" => "ASC"), array("IBLOCK_ID" => ATTACHMENTS, "ACTIVE" => "Y", "ID" => $arAttachments), false, false, array("ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_URL"));
            while ($attachment = $attachments->GetNext())
                $arResult["ATTACHMENTS"][] = array(
                    "ID" => $attachment["ID"],
                    "NAME" => $attachment["NAME"],
                    "PREVIEW_PICTURE" => CFile::ResizeImageGet(
                            $attachment["PREVIEW_PICTURE"], array("width" => 100, "height" => 100), BX_RESIZE_IMAGE_PROPORTIONAL, true
                    ),
                    "URL" => $attachment["PROPERTY_URL_VALUE"]
                );
        }

        /* Характеристики */
        if (!empty($arResult["ITEMS"]))
            foreach ($arResult["ITEMS"] as $arItem)
                if (!empty($arItem["PROPERTIES"]))
                    foreach ($arItem["PROPERTIES"] as $key => $value)
                        if (!empty($value["VALUE"]))
                            $arPropery[$value["NAME"]] = $value["CODE"];
        $arPropery = array_unique($arPropery);

        /* Группы характеристик */
        $section_groups = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => CHARACTERISTIC_GROUP, "ACTIVE" => "Y", "CNT_ACTIVE" => "Y"), true);
        while ($section_group = $section_groups->GetNext())
        {
            unset($elements_grouping);
            unset($elements_group);
            unset($element_group);
            if ($section_group["ELEMENT_CNT"] > 0)
            {
                $elements_group = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => CHARACTERISTIC_GROUP, "ACTIVE" => "Y", "SECTION_ID" => $section_group["ID"]), false, false, array("ID", "NAME", "CODE"));
                while ($element_group = $elements_group->GetNext())
                {
                    if (in_array($element_group["CODE"], $arPropery))
                    {
                        $elements_grouping[$element_group["CODE"]] = $element_group["NAME"];
                        $arPropInArray[] = $element_group["CODE"];
                    }
                }
                if (!empty($elements_grouping))
                    $arResult["GROUPING_CHARS"][] = array(
                        "NAME" => $section_group["NAME"],
                        "ITEMS" => $elements_grouping
                    );
            }
        }
        $arPropInArray = array_unique($arPropInArray);

        if (count(array_diff($arPropery, $arPropInArray)) > 0 || empty($arPropInArray))
        {
            // для каких-то свойств нет групы
            $arResult["GROUPING_CHARS"][] = array(
                "NAME" => "Другие",
                "ITEMS" => (!empty($arPropInArray)) ? array_flip(array_diff($arPropery, $arPropInArray)) : array_flip($arPropery)
            );
        }
        //pr($arResult["GROUPING_CHARS"]);

        if ($USER->IsAdmin())
        {
            //echo "<pre>";
            /* print_r($arResult["GROUPING_CHARS"]); */
            //echo "</pre>";
        }

        /* Сопутствующие товары */
        if (!empty($arRelatedProducts))
        {
            $arRelatedProducts = array_unique($arRelatedProducts);
            $relatings = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), array("IBLOCK_ID" => RELATED_PRODUCTS, "ACTIVE" => "Y", "ID" => $arRelatedProducts), false, false, array("ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_PRICE", "PROPERTY_URL"));
            while ($relating = $relatings->GetNext())
                $arResult["RELATED_PRODUCTS"][] = array(
                    "NAME" => $relating["NAME"],
                    "URL" => $relating["PROPERTY_URL_VALUE"],
                    "PRICE" => FormatCurrency($relating["PROPERTY_PRICE_VALUE"], "RUB"),
                    "PREVIEW_PICTURE" => CFile::ResizeImageGet(
                            $relating["PREVIEW_PICTURE"], array("width" => 120, "height" => 90), BX_RESIZE_IMAGE_PROPORTIONAL, true
                    )
                );
        }

        /* Вам могут быть интересны */
        if (COption::GetOptionInt("ust", "catalog_detail_you_interested") == 1 && !empty($arInterestedProducts))
        {
            $arInterestedProducts = array_unique($arInterestedProducts);
            $interestings = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $arInterestedProducts), false, false, array("ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_PRICE", "DETAIL_PAGE_URL"));
            while ($interesting = $interestings->GetNext())
            {
                $arResult["INTERESTED_PRODUCTS"][] = array(
                    "NAME" => $interesting["NAME"],
                    "URL" => $interesting["DETAIL_PAGE_URL"],
                    "PRICE" => FormatCurrency($interesting["PROPERTY_PRICE_VALUE"], "RUB"),
                    "PREVIEW_PICTURE" => CFile::ResizeImageGet(
                            $interesting["PREVIEW_PICTURE"], array("width" => 119, "height" => 100), BX_RESIZE_IMAGE_PROPORTIONAL, true
                    )
                );
            }
        }

        if (!$arSection && $arResult["IBLOCK_SECTION_ID"] > 0)
        {
            $arSectionFilter = array(
                "ID" => $arResult["IBLOCK_SECTION_ID"],
                "IBLOCK_ID" => $arResult["IBLOCK_ID"],
                "ACTIVE" => "Y",
            );
            $rsSection = CIBlockSection::GetList(Array(), $arSectionFilter);
            $rsSection->SetUrlTemplates("", $arParams["SECTION_URL"]);
            $arSection = $rsSection->GetNext();
        }
        if ($arSection)
        {
            $arSection["PATH"] = array();
            $rsPath = CIBlockSection::GetNavChain($arResult["IBLOCK_ID"], $arSection["ID"]);
            $rsPath->SetUrlTemplates("", $arParams["SECTION_URL"]);
            while ($arPath = $rsPath->GetNext())
            {
                $arSection["PATH"][] = $arPath;
            }
            $arResult["SECTION"] = $arSection;
        }
    }

    if ($arResult["ID"] > 0)
    {
        $ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arResult["IBLOCK_ID"], $arResult["ID"]);
        $arResult["IPROPERTY_VALUES"] = $ipropValues->getValues();
        $this->IncludeComponentTemplate();
    }
    else
    {
        $this->AbortResultCache();
        ShowError(GetMessage("CATALOG_ELEMENT_NOT_FOUND"));
        @define("ERROR_404", "Y");
        if ($arParams["SET_STATUS_404"] === "Y")
            CHTTP::SetStatus("404 Not Found");
    }
}

if (isset($arResult["ID"]))
{
    if ($arParams["SET_TITLE"])
    {
        if ($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != "")
            $APPLICATION->SetTitle($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"], $arTitleOptions);
        else
            $APPLICATION->SetTitle($arResult["NAME"], $arTitleOptions);
    }

    $browserTitle = \Bitrix\Main\Type\Collection::firstNotEmpty(
                    $arResult["PROPERTIES"], array($arParams["BROWSER_TITLE"], "VALUE")
                    , $arResult, $arParams["BROWSER_TITLE"]
                    , $arResult["IPROPERTY_VALUES"], "ELEMENT_META_TITLE"
    );
    if (is_array($browserTitle))
        $APPLICATION->SetPageProperty("title", implode(" ", $browserTitle), $arTitleOptions);
    elseif ($browserTitle != "")
        $APPLICATION->SetPageProperty("title", $browserTitle, $arTitleOptions);

    $metaKeywords = \Bitrix\Main\Type\Collection::firstNotEmpty(
                    $arResult["PROPERTIES"], array($arParams["META_KEYWORDS"], "VALUE")
                    , $arResult["IPROPERTY_VALUES"], "ELEMENT_META_KEYWORDS"
    );
    if (is_array($metaKeywords))
        $APPLICATION->SetPageProperty("keywords", implode(" ", $metaKeywords), $arTitleOptions);
    elseif ($metaKeywords != "")
        $APPLICATION->SetPageProperty("keywords", $metaKeywords, $arTitleOptions);

    $metaDescription = \Bitrix\Main\Type\Collection::firstNotEmpty(
                    $arResult["PROPERTIES"], array($arParams["META_DESCRIPTION"], "VALUE")
                    , $arResult["IPROPERTY_VALUES"], "ELEMENT_META_DESCRIPTION"
    );
    if (is_array($metaDescription))
        $APPLICATION->SetPageProperty("description", implode(" ", $metaDescription), $arTitleOptions);
    elseif ($metaDescription != "")
        $APPLICATION->SetPageProperty("description", $metaDescription, $arTitleOptions);

    if ($arParams["ADD_SECTIONS_CHAIN"] && is_array($arResult["SECTION"]))
    {
        foreach ($arResult["SECTION"]["PATH"] as $arPath)
        {
            $APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
        }
        $APPLICATION->AddChainItem($arResult["NAME"], $APPLICATION->GetCurPage(false));
    }
    return $arResult["ID"];
}
else
{
    return 0;
}
?>