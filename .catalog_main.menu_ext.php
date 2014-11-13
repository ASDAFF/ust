<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

global $APPLICATION;

if (!function_exists("GetTreeRecursive"))
{

    if ($_SERVER["HTTP_HOST"] == "u-st.ru")
    {
        $arMenuLinks = $APPLICATION->IncludeComponent(
                "pixel:menu.sections", "", Array(
            "ID" => $_REQUEST["ID"],
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => "",
            "SECTION_URL" => "#SITE_DIR#/catalog/#SECTION_CODE#/",
            "DEPTH_LEVEL" => "1",
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "36000000",
            "PARENT_ID" => "190"
                )
        );
        $aMenuLinks = array_merge($arMenuLinks, array(
		   Array(
                "Автогрейдеры",
                "/catalog/avtogreydery/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
		   Array(
                "Асфальтоукладчики",
                "/catalog/asfaltoukladchiki/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
		   Array(
                "Бульдозеры",
                "/catalog/buldozery/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
			 Array(
                "Бетонные заводы",
                "/catalog/betonnye-zavody/",
                Array(),
                Array("DOMENID" => array("22221")),
                ""
            ),
		   Array(
                "Виброплиты",
                "/catalog/vibroplity/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
		   Array(
                "Вибротрамбовки",
                "/catalog/vibrotrambovki/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
		   Array(
                "Гусеничные самосвалы",
                "/catalog/gusenichnye-samosvaly/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
		   Array(
                "Карьерные самосвалы",
                "/catalog/karernye-samosvaly/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
		   Array(
                "Катки",
                "/catalog/katki/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
		   Array(
                "Мини погрузчики",
                "/catalog/mini-pogruzchiki/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
			Array(
                "Мобильные краны",
                "/catalog/mobilnye-krany/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
		 Array(
                "Подъемники",
                "/catalog/podemniki/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
		 Array(
                "Телескопические погрузчики",
                "/catalog/teleskopicheskie-pogruzchiki/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
		Array(
                "Фронтальные погрузчики",
                "/catalog/frontalnye-pogruzchiki/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
		 Array(
                "Экскаваторы",
                "/catalog/ekskavatory/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
		Array(
                "Экскаваторы-погрузчики",
                "/catalog/ekskavatory-pogruzchiki/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
            Array(
                "Буровое и сваебойное оборудование",
                "/catalog/burovoe-i-svaeboynoe-oborudovanie/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
            /* Array( 
              "Бурение восстающих выработок",
              "/catalog/burenie-vosstayushchikh-vyrabotok/",
              Array(),
              Array("DOMENID" => array("22220")),
              ""
              ), */
            Array(
                "Навесное оборудование",
                "/catalog/navesnoe-oborudovanie-dlya-stroitelnoy-tekhniki/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
            Array(
                "Дробильно-сортировочное оборудование",
                "/catalog/drobilno-sortirovochnoe-oborudovanie/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            ),
            Array(
                "Б/У техника",
                "/catalog/bu-stroitelnaya-tehnika/",
                Array(),
                Array("DOMENID" => array("22220")),
                ""
            )
        ));
    }
    if ($_SERVER["HTTP_HOST"] == "generatory.ust-co.ru")
    {
        $arMenuLinks = $APPLICATION->IncludeComponent(
                "pixel:menu.sections", "", Array(
            "ID" => $_REQUEST["ID"],
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => "6",
            "SECTION_URL" => "#SITE_DIR#/catalog/#SECTION_CODE#/",
            "DEPTH_LEVEL" => "1",
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "36000000",
            "PARENT_ID" => "248"
                )
        );
        $aMenuLinks = $arMenuLinks;
    }
    if ($_SERVER["HTTP_HOST"] == "forestry.u-st.ru")
    {

        $arMenuLinks = $APPLICATION->IncludeComponent(
                "pixel:menu.sections", "", Array(
            "ID" => $_REQUEST["ID"],
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => "6",
            "SECTION_URL" => "#SITE_DIR#/catalog/#SECTION_CODE#/",
            "DEPTH_LEVEL" => "1",
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "36000000",
            "PARENT_ID" => "987"
                )
        );
        $aMenuLinks = $arMenuLinks;
    }
    if ($_SERVER["HTTP_HOST"] == "bsu.ust-co.ru")
    {
        $arMenuLinks = array();
        $aMenuLinks = array_merge($arMenuLinks, array(
            Array(
                "Бетонный завод NISBAU EUROMIX 30",
                "/catalog/betonnye-zavody/betonnyy-zavod-nisbau-euromix-30/",
                Array(),
                Array("DOMENID" => array("22221")),
                ""
            ),
            Array(
                "Бетонный завод NISBAU EUROMIX 120",
                "/catalog/betonnye-zavody/betonnyy-zavod-nisbau-euromix-120/",
                Array(),
                Array("DOMENID" => array("22221")),
                ""
            ),
            Array(
                "Бетонный завод NISBAU EUROMIX 100",
                "/catalog/betonnye-zavody/betonnyy-zavod-nisbau-euromix-100/",
                Array(),
                Array("DOMENID" => array("22221")),
                ""
            ),
            Array(
                "Бетонный завод NISBAU EUROMIX 60",
                "/catalog/betonnye-zavody/betonnyy-zavod-nisbau-euromix-60/",
                Array(),
                Array("DOMENID" => array("22221")),
                ""
            )
        ));
    }
    if ($_SERVER["HTTP_HOST"] == "ust-co.ru")
    {
        $arMenuLinks = $APPLICATION->IncludeComponent(
                "pixel:menu.sections", "", Array(
            "ID" => $_REQUEST["ID"],
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => "6",
            "SECTION_URL" => "#SITE_DIR#/catalog/#SECTION_CODE#/",
            "DEPTH_LEVEL" => "1",
            "CACHE_TYPE" => "N",
            "CACHE_TIME" => "36000000",
            "PARENT_ID" => "206"
                )
        );

        $aMenuLinks = array_merge($arMenuLinks, array(
            Array(
                "Б/У техника",
                "/catalog/bu-skladskaya-tehnika/",
                Array(),
                Array("DOMENID" => array("22224")),
                ""
            )
        ));
    }
}
?>