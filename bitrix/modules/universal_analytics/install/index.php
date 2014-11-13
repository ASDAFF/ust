<?php

Class universal_analytics extends CModule
{

    var $MODULE_ID = "universal_analytics";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    function universal_analytics()
    {
        $this->MODULE_VERSION = "1.0.0";
        $this->MODULE_VERSION_DATE = "2014-11-08";
        $this->MODULE_NAME = "Universal Analytics";
        $this->MODULE_DESCRIPTION = "После установки вы сможете пользоваться универсальной аналитикой Вашего сайта";
    }

    function CreateIblocks()
    {
        CModule::IncludeModule("iblock");
        $arFields = Array(
            'ID' => 'universal_analytics',
            'SECTIONS' => 'Y',
            'IN_RSS' => 'N',
            'SORT' => 100,
            'LANG' => Array(
                'ru' => Array(
                    'NAME' => 'Универсальная аналитика',
                    'SECTION_NAME' => 'Разделы',
                    'ELEMENT_NAME' => 'Элементы'
                )
            )
        );

        $obBlocktype = new CIBlockType;
        $res = $obBlocktype->Add($arFields);
        if (!$res)
        {
            $DB->Rollback();
            echo 'Error: ' . $obBlocktype->LAST_ERROR . '<br>';
        }
        else
        {
            $this->create_users();
            $this->create_user_forms();
            $this->create_user_logs();
            $this->create_entry_points();
            $this->create_user_history();
            $this->create_user_remote();
        }

        return true;
    }

    function create_users()
    {
        $ib = new CIBlock;
        $iblockproperty = new CIBlockProperty;
        $arFields = Array(
            "ACTIVE" => "Y",
            "NAME" => "Пользователи",
            "CODE" => "ua_users",
            "LIST_PAGE_URL" => "ua",
            "DETAIL_PAGE_URL" => "ua",
            "IBLOCK_TYPE_ID" => "universal_analytics",
            "SITE_ID" => Array("s1", "s2"),
            "SORT" => "ASK",
            "DESCRIPTION" => "",
            "DESCRIPTION_TYPE" => "",
            "GROUP_ID" => Array("1" => "D", "2" => "R")
        );
        $ID = $ib->Add($arFields);  /**/

        $arFields = Array(
            "NAME" => "1С ID",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "1CID",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => "Номер пользователя",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "ID_USER_NUM",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);


        $arFields = Array(
            "NAME" => "Гео информация",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "GEOINFO",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => "Номер рекламной кампании",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "UTM_CAMPAIGN",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => "Ссылка перехода с баннера",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "UTM_BANNER_REFERER",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => "Поисковый запрос",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "UTM_WORD",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);


        $arFields = Array(
            "NAME" => "Первоначальная страница",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "LANDING_PAGE",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);


        $arFields = Array(
            "NAME" => "Телефон, который был показан первый раз",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "PHONE_SHOWED",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => "IP Адрес",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "IP_ADRESS",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => " ",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => " ",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);
    }

    function create_user_forms()
    {
        $ib = new CIBlock;
        $iblockproperty = new CIBlockProperty;
        $arFields = Array(
            "ACTIVE" => "Y",
            "NAME" => "Данные форм пользователей",
            "CODE" => "ua_user_forms",
            "LIST_PAGE_URL" => "ua",
            "DETAIL_PAGE_URL" => "ua",
            "IBLOCK_TYPE_ID" => "universal_analytics",
            "SITE_ID" => Array("s1"),
            "SORT" => "ASK",
            "DESCRIPTION" => "",
            "DESCRIPTION_TYPE" => "",
            "GROUP_ID" => Array("1" => "D", "2" => "R")
        );

        $ID = $ib->Add($arFields);  /**/

        $arFields = Array(
            "NAME" => "ID Пользователя",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "ID_USER",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => "ID Формы",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "ID_FORM",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => "Данные",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "DATA",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);
    }

    function create_user_logs()
    {

        $ib = new CIBlock;
        $iblockproperty = new CIBlockProperty;
        $arFields = Array(
            "ACTIVE" => "Y",
            "NAME" => "Лог пользователей",
            "CODE" => "ua_user_logs",
            "LIST_PAGE_URL" => "ua",
            "DETAIL_PAGE_URL" => "ua",
            "IBLOCK_TYPE_ID" => "universal_analytics",
            "SITE_ID" => Array("s1"),
            "SORT" => "ASK",
            "DESCRIPTION" => "",
            "DESCRIPTION_TYPE" => "",
            "GROUP_ID" => Array("1" => "D", "2" => "R")
        );

        $ID = $ib->Add($arFields);  /**/

        $arFields = Array(
            "NAME" => "ID Пользователя",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "ID_USER",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => "Лог информация",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "LOG",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);
    }

    function create_entry_points()
    {

        $ib = new CIBlock;
        $iblockproperty = new CIBlockProperty;
        $arFields = Array(
            "ACTIVE" => "Y",
            "NAME" => "Точки изменения пользовательского поведения",
            "CODE" => "ua_user_entry_points",
            "LIST_PAGE_URL" => "ua",
            "DETAIL_PAGE_URL" => "ua",
            "IBLOCK_TYPE_ID" => "universal_analytics",
            "SITE_ID" => Array("s1"),
            "SORT" => "ASK",
            "DESCRIPTION" => "",
            "DESCRIPTION_TYPE" => "",
            "GROUP_ID" => Array("1" => "D", "2" => "R")
        );

        $ID = $ib->Add($arFields);  /**/

        $arFields = Array(
            "NAME" => "ID Пользователя",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "ID_USER",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => "Источник",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "UTM_SOURCE",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);


        $arFields = Array(
            "NAME" => "Номер рекламной кампании",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "UTM_CAMPAIGN",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => "Поисковый запрос",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "UTM_WORD",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);


        $arFields = Array(
            "NAME" => "Цена поискового запроса",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "UTM_WORD_PRICE",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);
    }

    function create_user_history()
    {
        $ib = new CIBlock;
        $iblockproperty = new CIBlockProperty;
        $arFields = Array(
            "ACTIVE" => "Y",
            "NAME" => "История поведения пользователя",
            "CODE" => "ua_user_entry_points",
            "LIST_PAGE_URL" => "ua",
            "DETAIL_PAGE_URL" => "ua",
            "IBLOCK_TYPE_ID" => "universal_analytics",
            "SITE_ID" => Array("s1"),
            "SORT" => "ASK",
            "DESCRIPTION" => "",
            "DESCRIPTION_TYPE" => "",
            "GROUP_ID" => Array("1" => "D", "2" => "R")
        );

        $ID = $ib->Add($arFields);  /**/

        $arFields = Array(
            "NAME" => "ID Пользователя",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "ID_USER",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => "ID Последней категории",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "LAST_CATEGORY_ID",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);

        $arFields = Array(
            "NAME" => "ID Последнего продукта категории",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "LAST_PRODUCT_ID",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);
    }

    function create_user_remote()
    {
        $ib = new CIBlock;
        $iblockproperty = new CIBlockProperty;
        $arFields = Array(
            "ACTIVE" => "Y",
            "NAME" => "История заходов пользователя",
            "CODE" => "ua_user_remote",
            "LIST_PAGE_URL" => "ua",
            "DETAIL_PAGE_URL" => "ua",
            "IBLOCK_TYPE_ID" => "universal_analytics",
            "SITE_ID" => Array("s1"),
            "SORT" => "ASK",
            "DESCRIPTION" => "",
            "DESCRIPTION_TYPE" => "",
            "GROUP_ID" => Array("1" => "D","2"=>"R")
        );

        $arFields = Array(
            "NAME" => "ID пользователя",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "ID_USER",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);
        $arFields = Array(
            "NAME" => "HTTP_REFERER",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "HTTP_REFERER",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
        $PropertyID = $iblockproperty->Add($arFields);
        $arFields = Array(
            "NAME" => "REMOTE_ADDR",
            "ACTIVE" => "Y",
            "SORT" => "100",
            "CODE" => "REMOTE_ADDR",
            "PROPERTY_TYPE" => "s",
            "IBLOCK_ID" => $ID
        );
    }

    function DeleteIblocks()
    {

        if (!CIBlockType::Delete("universal_analytics"))
        {
            echo 'Delete error!';
        }

        return true;
    }

    function InstallComponents()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/universal_analytics/install/components", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/", true, true);
        // CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/universal_analytics/install/components/base", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/universal_analytics/base", true, true);
        return true;
    }

    function UnInstallComponents()
    {
        //  DeleteDirFilesEx("/bitrix/components/universal_analytics");
    }

    function DoInstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        $this->CreateIblocks();
        $this->InstallComponents();

        RegisterModule("universal_analytics");
        $APPLICATION->IncludeAdminFile("Установка модуля universal_analytics", $DOCUMENT_ROOT . "/bitrix/modules/universal_analytics/install/step1.php");
    }

    function DoUninstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        $this->DeleteIblocks();
        $this->UnInstallComponents();
        UnRegisterModule("universal_analytics");
        $APPLICATION->IncludeAdminFile("Деинсталляция модуля universal_analytics", $DOCUMENT_ROOT . "/bitrix/modules/universal_analytics/install/unstep1.php");
    }

}

?>