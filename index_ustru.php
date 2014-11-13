<?
if ($_SERVER["SERVER_NAME"] == "u-st.ru")
{
    // header("HTTP/1.1 301 Moved Permanently");
    //  header("Location: /catalog/stroitelnaya-tekhnika/");
    //  exit(); 
}
 
if ($_SERVER["SERVER_NAME"] == "generatory.ust-co.ru")
{
  //  header("HTTP/1.1 301 Moved Permanently");
  //  header("Location: /catalog/generatory/");
  //  exit();
}
if ($_SERVER["SERVER_NAME"] == "burovoe-oborudovanie.u-st.ru")
{
   // header("HTTP/1.1 301 Moved Permanently");
   // header("Location: /catalog/burovoe-i-svaeboynoe-oborudovanie/");
   // exit();
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "дорожно-строительная техника, купить строительную технику, магазин строительной техники, сайт строительной техники, интернет магазин строительной техники, подъемные краны, генераторные установки, дробильно-сортировочного оборудования, буровое оборудование, бетонные заводы");
$APPLICATION->SetPageProperty("description", "Компания Универсал-Спецтехника является официальным партнером известных мировых производителей и занимается продажей дорожно-строительной техники, подъемных кранов, генераторных установок, дробильно-сортировочного оборудования, бурового оборудования, бетонных заводов и другого строительного оборудования.");
$APPLICATION->SetPageProperty("SHOW_BANNERS_BOTTOM", "N");
$APPLICATION->SetTitle("Дорожно-строительная техника - продажа, аренда, лизинг.");
?>
<div class="index-slider">
    <div class="slider-wrapper">
        <?
        $APPLICATION->IncludeComponent("areal:banners.main", "template1", Array("SECTION_ID"=>"943"), false); 
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
            <h1 class="sub-title">Дорожно-строительная техника - продажа, аренда, лизинг.</h1>
            <?
            $APPLICATION->IncludeComponent(
                    "bitrix:main.include", "main", Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/include/seo_main_ust.php",
                "EDIT_TEMPLATE" => "main_seo.php"
                    )
            );
            ?>
        </div>
    </div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>