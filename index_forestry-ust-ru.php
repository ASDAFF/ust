<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "");
$APPLICATION->SetPageProperty("description", "");
$APPLICATION->SetPageProperty("SHOW_BANNERS_BOTTOM", "N");
$APPLICATION->SetTitle("");
?> 
<div class="index-slider">
    <div class="slider-wrapper">
        <?
        $APPLICATION->IncludeComponent("areal:banners.main", "template1", Array("SECTION_ID"=>"992"), false); 
        ?>
        <?
        $APPLICATION->IncludeComponent("bitrix:menu", "catalog_main", array(
            "ROOT_MENU_TYPE" => "catalog_main",
            "MENU_CACHE_TYPE" => "N",
            "MENU_CACHE_TIME" => "0",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "MENU_CACHE_GET_VARS" => array(
            ),
            "MAX_LEVEL" => "1",
            "CHILD_MENU_TYPE" => "",
            "USE_EXT" => "Y",
            "DELAY" => "N",
            "ALLOW_MULTI_SELECT" => "N"
                ), false
        );
        ?>
<? $APPLICATION->IncludeComponent("areal:brands.bottom", ".default", array("CATALOG" => "Y")); ?>
    </div>
</div>
</div>
<? $APPLICATION->IncludeComponent("areal:actions.main.slider", ".default"); ?>
<div class="index-main-plate">
    <div class="wrapper">
<? $APPLICATION->IncludeComponent("areal:main.page.information", ".default"); ?>
        <div class="site-descr">
            <h1 class="sub-title"></h1>
            <?
            $APPLICATION->IncludeComponent(
                    "bitrix:main.include", "main", Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/include/",
                "EDIT_TEMPLATE" => "main_seo.php"
                    )
            );
            ?>
        </div>
    </div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>