<?
//include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена!");
/*
$APPLICATION->IncludeComponent("bitrix:main.map", ".default", array(
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "36000000",
    "SET_TITLE" => "Y",
    "LEVEL"    =>    "3",
    "COL_NUM"    =>    "2",
    "SHOW_DESCRIPTION" => "Y"
    ),
    false
);    */
?>
    <p style="color: red;">Товар либо раздел, возможно, были удалены или перемещены.
Вы можете воспользоваться фильтром для поиска нужного товара.</p>

<?
    $APPLICATION->IncludeComponent(
        "kombox:filter",
        "404",
        Array(
        "IBLOCK_TYPE" => 'catalog',
        "IBLOCK_ID" => '6',
        "SECTION_ID" => '190',
        "FILTER_NAME" => 'arrFilter',
        "PRICE_CODE" => $arParams["PRICE_CODE"],
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_NOTES" => "",
        "CACHE_GROUPS" => "Y",
        "SAVE_IN_SESSION" => "N",
        "CLOSED_PROPERTY_CODE" => array(),
        "CLOSED_OFFERS_PROPERTY_CODE" => array()
        ),
        $component
    );   
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>