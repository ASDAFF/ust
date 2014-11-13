<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Навесное оборудование для строительной техники");
//$APPLICATION->SetPageProperty("keywords", "Навесное оборудование для строительной техники");
//$APPLICATION->SetPageProperty("description", "Навесное оборудование для строительной техники");
/* $rsResult = CIBlockSection::GetByID(244);
if ($arResult = $rsResult -> GetNextElement())
{

$arItem = $arResult -> GetFields(); // получаем поля элемента инфоблока
$arProp = $arResult -> GetProperties(); // получаем свойства элемента инфоблока
echo '<div style="display:none" class="fff">'.print_r($arItem,true).'</div>';
$file = $_SERVER['DOCUMENT_ROOT']."/catalog/navesnoe-oborudovanie-dlya-stroitelnoy-tekhniki/test_cat.txt";
file_put_contents($file, print_r($arItem,true));
$APPLICATION -> SetPageProperty("description", $arProp['description']['VALUE']); //устанавливаем дескрипшн
$APPLICATION -> SetPageProperty("keywords", $arProp['keywords']['VALUE']); // устанавливаем кейвордс
} */ 
/* $file = $_SERVER['DOCUMENT_ROOT']."/catalog/navesnoe-oborudovanie-dlya-stroitelnoy-tekhniki/test_cat.txt";
$res = CIBlockSection::GetList(Array(),Array("IBLOCK_ID"=>6,"SECTION_ID"=>244),false,array("NAME","PROPERTY_KEYWORDS","PROPERTY_DESCRIPTION"));

   if($res->SelectedRowsCount()>0)
   {
      $ar_fields = $res->GetNext();
      $img=$ar_fields['DETAIL_PICTURE'];
      $name=$ar_fields['NAME'];
      $short_descr=$ar_fields['PREVIEW_TEXT'];
      $descr=$ar_fields['DETAIL_TEXT'];
      $keywords=$ar_fields['PROPERTY_KEYWORDS_VALUE'];
      $description=$ar_fields['PROPERTY_DESCRIPTION_VALUE'];
   }
   file_put_contents($file, $name); */
?>
<?$page = $APPLICATION->GetCurPage();
$pieces = explode("/", $page);
unset($pieces[0]); 
unset($pieces[4]);

$arSelect = Array("ID", "NAME");
$arFilter = Array("ACTIVE"=>"Y", "CODE"=>$pieces[3]);
$res1 = CIBlockSection::GetList(Array(), $arFilter, false, Array(), $arSelect);
while($ob = $res1->Fetch())
{
    $arFields11["IBLOCK_SECTION_ID"] = $ob["IBLOCK_SECTION_ID"]; 
}        
       
$res = CIBlockSection::GetByID($arFields11["IBLOCK_SECTION_ID"]);
if($ar_res = $res->GetNext()){
    //echo $ar_res['CODE'];    
}
  
//$is_404 = false;
//$file = $_SERVER['DOCUMENT_ROOT']."/catalog/navesnoe-oborudovanie-dlya-stroitelnoy-tekhniki/test_catxx.txt";
//$head_request = get_headers('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
//if (strpos($head_request[0], "404") !== false) file_put_contents($file, "asdasd");/*$is_404 = true;*/
//else file_put_contents($file, "zzzz");http_response_code


if(($pieces[2] !== $ar_res['CODE']) && ($pieces[3] !== $ar_res['CODE']) && ($pieces[3] !== 'index.php') /*|| (http_response_code() == "404")*/){
    global $APPLICATION;
    $APPLICATION->RestartBuffer();
    CHTTP::SetStatus("404 Not Found");
    include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php");
    include($_SERVER["DOCUMENT_ROOT"] . "/404.php");
    include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php");
} 
     
?>
<?
$APPLICATION->IncludeComponent("bitrix:catalog", "catalog_new", array(
    "IBLOCK_TYPE" => "catalog",
    "IBLOCK_ID" => "6",
    "HIDE_NOT_AVAILABLE" => "N",
    "SECTION_ID_VARIABLE" => "SECTION_ID",
    "SEF_MODE" => "Y",
    "SEF_FOLDER" => "/catalog/",
    "AJAX_MODE" => "N",
    "AJAX_OPTION_JUMP" => "N",
    "AJAX_OPTION_STYLE" => "Y",
    "AJAX_OPTION_HISTORY" => "N",
    "CACHE_TYPE" => "A",
    "CACHE_TIME" => "36000000",
    "CACHE_FILTER" => "N",
    "CACHE_GROUPS" => "Y",
    "SET_TITLE" => "Y",
    "SET_STATUS_404" => "Y",
    "USE_ELEMENT_COUNTER" => "Y",
    "USE_FILTER" => "Y",
    "FILTER_NAME" => "",
    "FILTER_FIELD_CODE" => array(
        0 => "",
        1 => "",
    ),
    "FILTER_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
    ),
    "FILTER_PRICE_CODE" => array(
    ),
    "USE_COMPARE" => "N",
    "PRICE_CODE" => array(
    ),
    "USE_PRICE_COUNT" => "N",
    "SHOW_PRICE_COUNT" => "1",
    "PRICE_VAT_INCLUDE" => "Y",
    "PRICE_VAT_SHOW_VALUE" => "N",
    "CONVERT_CURRENCY" => "N",
    "BASKET_URL" => "/personal/basket.php",
    "ACTION_VARIABLE" => "action",
    "PRODUCT_ID_VARIABLE" => "id",
    "USE_PRODUCT_QUANTITY" => "N",
    "ADD_PROPERTIES_TO_BASKET" => "Y",
    "PRODUCT_PROPS_VARIABLE" => "prop",
    "PARTIAL_PRODUCT_PROPERTIES" => "N",
    "PRODUCT_PROPERTIES" => array(
    ),
    "SHOW_TOP_ELEMENTS" => "Y",
    "TOP_ELEMENT_COUNT" => "9",
    "TOP_LINE_ELEMENT_COUNT" => "3",
    "TOP_ELEMENT_SORT_FIELD" => "sort",
    "TOP_ELEMENT_SORT_ORDER" => "asc",
    "TOP_ELEMENT_SORT_FIELD2" => "id",
    "TOP_ELEMENT_SORT_ORDER2" => "desc",
    "TOP_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
    ),
    "SECTION_COUNT_ELEMENTS" => "N",
    "SECTION_TOP_DEPTH" => "1",
    "PAGE_ELEMENT_COUNT" => "30",
    "LINE_ELEMENT_COUNT" => "3",
    "ELEMENT_SORT_FIELD" => "sort",
    "ELEMENT_SORT_ORDER" => "asc",
    "ELEMENT_SORT_FIELD2" => "id",
    "ELEMENT_SORT_ORDER2" => "desc",
    "LIST_PROPERTY_CODE" => array(
        0 => "",
        1 => "",
    ),
    "INCLUDE_SUBSECTIONS" => "A",
    "LIST_META_KEYWORDS" => "-",
    "LIST_META_DESCRIPTION" => "-",
    "LIST_BROWSER_TITLE" => "-",
    "DETAIL_PROPERTY_CODE" => array(
        0 => "",
        1 => "PHOTOS",
        2 => "",
    ),
    "DETAIL_META_KEYWORDS" => "-",
    "DETAIL_META_DESCRIPTION" => "-",
    "DETAIL_BROWSER_TITLE" => "-",
    "LINK_IBLOCK_TYPE" => "",
    "LINK_IBLOCK_ID" => "",
    "LINK_PROPERTY_SID" => "",
    "LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
    "USE_ALSO_BUY" => "N",
    "USE_STORE" => "N",
    "PAGER_TEMPLATE" => ".default",
    "DISPLAY_TOP_PAGER" => "Y",
    "DISPLAY_BOTTOM_PAGER" => "Y",
    "PAGER_TITLE" => "Товары",
    "PAGER_SHOW_ALWAYS" => "N",
    "PAGER_DESC_NUMBERING" => "N",
    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
    "PAGER_SHOW_ALL" => "Y",
    "AJAX_OPTION_ADDITIONAL" => "",
    "PRODUCT_QUANTITY_VARIABLE" => "quantity",
    "SEF_URL_TEMPLATES" => array(
        "sections" => "",
        "section" => "#SECTION_CODE#/",
        "element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
        "compare" => "compare.php?action=#ACTION_CODE#",
    ),
    "VARIABLE_ALIASES" => array(
        "compare" => array(
            "ACTION_CODE" => "action",
        ),
    )
    ),
    false
);

//проверка на 404 в хедере
    if (http_response_code() == "404") {
    global $APPLICATION;
    $APPLICATION->RestartBuffer();
    CHTTP::SetStatus("404 Not Found");
    include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php");
    include($_SERVER["DOCUMENT_ROOT"] . "/404.php");
    include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php");
} 

?><?$APPLICATION->IncludeComponent(
	"kuznica:metatags.keysautoset",
	"",
	Array(
		"COMPONENT_MODE" => "2",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "6",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_NOTES" => "",
		"ELEMENT_ID" => $_REQUEST["ELEMENT_ID"],
		"SECTION_ID" => $arFields11["IBLOCK_SECTION_ID"],
		"ELEMENT_CODE" => $_REQUEST["ELEMENT_CODE"],
		"SECTION_CODE" => $_REQUEST["SECTION_CODE"],
		"COMPLEX_COMPONENT_PATH" => "/catalog/navesnoe-oborudovanie-dlya-stroitelnoy-tekhniki/",
		"COMPLEX_SECTION_PATH" => "#SECTION_CODE#/",
		"COMPLEX_ELEMENT_PATH" => "#SECTION_CODE#/#ELEMENT_CODE#/"
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>