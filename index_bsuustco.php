/<?
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

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "бетонный завод, бетонный завод купить");
$APPLICATION->SetPageProperty("description", "Компактные, мобильные бетонные заводы от известного бренда NISBAU. Качество, сделанное в Германии. Монтаж и демонтаж в течение 2х дней.");
$APPLICATION->SetPageProperty("SHOW_BANNERS_BOTTOM", "N");
$APPLICATION->SetTitle("Бетонные заводы");
?>
<div class="index-slider">
    <div class="slider-wrapper">
        <?
        $APPLICATION->IncludeComponent("areal:banners.main", "template1", Array("SECTION_ID"=>"974"), false); 
        ?>
        <?
        $APPLICATION->IncludeComponent("bitrix:menu", "catalog_main", array(
            "ROOT_MENU_TYPE" => "catalog_main",
            "MENU_CACHE_TYPE" => "N",
            "MENU_CACHE_TIME" => "36000000",
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

<div class="ustco">
<? $APPLICATION->IncludeComponent("areal:actions.main.slider", ".default"); ?>
</div>

<div class="index-main-plate">
    <div class="wrapper">
        <? $APPLICATION->IncludeComponent("areal:main.page.information", ".default"); ?>
        <div class="site-descr">
            <h1 class="sub-title">Бетонные заводы</h1>
            <?
            $APPLICATION->IncludeComponent(
                    "bitrix:main.include", "main", Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/include/seo_main_bsu.php",
                "EDIT_TEMPLATE" => "main_seo.php"
                    )
            );
            ?>
        </div>
    </div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>