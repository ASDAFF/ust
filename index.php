<?
set_time_limit(3600);
if ($_SERVER["SERVER_NAME"] == "u-st.ru")
{
    // header("HTTP/1.1 301 Moved Permanently");
    //  header("Location: /catalog/stroitelnaya-tekhnika/");
    //  exit(); 
} 

if ($_SERVER["SERVER_NAME"] == "generatory.ust-co.ru")
{
 // header("HTTP/1.1 301 Moved Permanently");
//    header("Location: /catalog/generatory/");
  //  exit();
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Складская техника, складское оборудование, спецтехника");
$APPLICATION->SetPageProperty("description", "«Универсал-Спецтехника» - лидер продаж погрузчиков и складской техники. Складское оборудование: погрузчики, штабелеры, ричтраки, тележки - все со склада в Москве и регионах России. Запчасти для погрузчиков, ремонт и сервисное обслуживание погрузчиков");
$APPLICATION->SetPageProperty("SHOW_BANNERS_BOTTOM", "N");
$APPLICATION->SetTitle("Складские погрузчики и спецтехника — продажа, аренда, лизинг.");
?>
<div class="index-slider">
    <div class="slider-wrapper">
        <?
        $APPLICATION->IncludeComponent("areal:banners.main", "template1", Array("SECTION_ID"=>"944"), false); 
        ?>
        <?
        $APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"catalog_main", 
	array(
		"ROOT_MENU_TYPE" => "catalog_main",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_TIME" => "0",
		"MENU_CACHE_USE_GROUPS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N"
	),
	false
);
        ?>
        <? $APPLICATION->IncludeComponent("areal:brands.bottom", ".default", array("CATALOG" => "Y")); ?>
    </div>
</div>
</div>

<div class="ustco">
<? $APPLICATION->IncludeComponent("areal:actions.main.slider", "template1", Array(
	
	),
	false
); ?>
</div>

<div class="index-main-plate">
    <div class="wrapper">
        <? $APPLICATION->IncludeComponent("areal:main.page.information", ".default"); ?>
        <div class="site-descr">
            <h1 class="sub-title">Складские погрузчики и спецтехника — продажа, аренда, лизинг.</h1>
            <?
            $APPLICATION->IncludeComponent(
                    "bitrix:main.include", "main", Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/include/seo_main.php",
                "EDIT_TEMPLATE" => "main_seo.php"
                    )
            );
            ?>
        </div>
    </div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
