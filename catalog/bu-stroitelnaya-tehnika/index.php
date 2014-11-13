<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Б у строительная техника");
?> <?  
$page = $APPLICATION->GetCurPage();
$pieces = explode("/", $page);
unset($pieces[0]); 
unset($pieces[5]);
$arSelect = Array("DETAIL_PAGE_URL", "ID", "NAME");
$arFilter = Array("IBLOCK_ID"=>"7",  "ACTIVE"=>"Y", "CODE"=>$pieces[4]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
while($ob = $res->GetNextElement())
{
    $arFields11 = $ob->GetFields();

}

$res = CIBlockSection::GetByID($arFields11["IBLOCK_SECTION_ID"]);
if($ar_res = $res->GetNext())
  //echo $ar_res['CODE'];

if($pieces[3] !== $ar_res['CODE'] && $pieces[3] !== 'index.php'){
    global $APPLICATION;
    $APPLICATION->RestartBuffer();
    CHTTP::SetStatus("404 Not Found");
    include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php");
    include($_SERVER["DOCUMENT_ROOT"] . "/404.php");
    include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php");
          
}    

?> 
<div class="bu-page"> <?$APPLICATION->IncludeComponent(
	"bitrix:catalog",
	"used_items",
	Array(
		"IBLOCK_TYPE" => "used",
		"IBLOCK_ID" => "7",
		"HIDE_NOT_AVAILABLE" => "N",
		"BASKET_URL" => "",
		"ACTION_VARIABLE" => "",
		"PRODUCT_ID_VARIABLE" => "",
		"SECTION_ID_VARIABLE" => "",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"PRODUCT_PROPS_VARIABLE" => "",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/catalog/bu-stroitelnaya-tehnika/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "N",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_FILTER" => "N",
		"USE_COMPARE" => "N",
		"PRICE_CODE" => array(0=>"BASE",),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_PROPERTIES" => array(),
		"USE_PRODUCT_QUANTITY" => "N",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"SHOW_TOP_ELEMENTS" => "N",
		"SECTION_COUNT_ELEMENTS" => "N",
		"SECTION_TOP_DEPTH" => "2",
		"PAGE_ELEMENT_COUNT" => "3",
		"LINE_ELEMENT_COUNT" => "1",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"LIST_PROPERTY_CODE" => array(0=>"",1=>"",),
		"INCLUDE_SUBSECTIONS" => "Y",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"DETAIL_PROPERTY_CODE" => array(0=>"",1=>"",),
		"GENERAL_PROPERTIES_LIST" => array("PHOTO","LOT","YEAR_OF_RELEASE","MTBF","LOCATION","CONTACTS","ENABLE_DETAILED_PAGE","DESCRIPTION_DETAILED_PAGE","VIDEO"),
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
		"SEF_URL_TEMPLATES" => Array(
			"sections" => "",
			"section" => "#SECTION_CODE#/",
			"element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
			"compare" => "compare.php?action=#ACTION_CODE#"
		),
		"VARIABLE_ALIASES" => Array(
			"sections" => Array(),
			"section" => Array(),
			"element" => Array(),
			"compare" => Array(
				"ACTION_CODE" => "action"
			),
		)
	)
);?> </div>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>