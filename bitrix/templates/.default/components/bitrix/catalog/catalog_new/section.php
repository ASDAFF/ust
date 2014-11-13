<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? print 1; ?>
<?
if ($arResult["VARIABLES"]["SECTION_CODE"] || $arResult["VARIABLES"]["SECTION_ID"])
{
    if ($arResult["VARIABLES"]["SECTION_CODE"])
        $filter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $arResult["VARIABLES"]["SECTION_CODE"]);
    elseif ($arResult["VARIABLES"]["SECTION_ID"])
        $filter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ID" => $arResult["VARIABLES"]["SECTION_ID"]);

    $sect = CIBlockSection::GetList(array(), $filter, false, array("UF_LONG_SEO_TEXT", "UF_SHORT_SEO_TEXT", "keywords"));
    if ($sec = $sect->GetNext())
    {
        $SECTION_ID = $sec["ID"];
        if (!empty($sec["~UF_SHORT_SEO_TEXT"]))
            $SHORT_SEO_TEXT = $sec["~UF_SHORT_SEO_TEXT"];
        if (!empty($sec["~UF_LONG_SEO_TEXT"]))
            $LONG_SEO_TEXT = $sec["~UF_LONG_SEO_TEXT"];
    }
}

//if ($USER->IsAdmin())  {
	//раздел 
	if ($arResult["VARIABLES"]["SECTION_ID"])  {
			   $id=$arResult["VARIABLES"]["SECTION_ID"];
		   }

	else if ($arResult["VARIABLES"]["SECTION_CODE"]) {
			$db_list = CIBlockSection::GetList(Array(), Array('IBLOCK_ID'=>6, 'CODE'=>$arResult["VARIABLES"]["SECTION_CODE"]), true);
			 while ($arSect = $db_list->GetNext())
		   {
			   $id=$arSect["ID"];
		   }
	}
	$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(6, $id);
	$arRes["IPROPERTY_VALUES"] = $ipropValues->getValues(); 
	$APPLICATION->SetPageProperty("keywords", $arRes["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"]);
	$APPLICATION->SetPageProperty("description", $arRes["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"]);
	$APPLICATION->SetPageProperty("title", $arRes["IPROPERTY_VALUES"]["SECTION_META_TITLE"]);
//}
//$file = $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/.default/components/bitrix/catalog/catalog_new/test_cat.txt";
//file_put_contents($file, print_r($id,true));

if (!empty($SHORT_SEO_TEXT)):
    ?>
    <div class="catalog_description">
        <?= $SHORT_SEO_TEXT ?>
        <p><a href="#" class="to_useful">Подробнее &raquo;</a></p>
    </div>
<? endif; ?>
<?

//print "<pre>"; print_r($arParams["SECTION_CODE"]);print "</pre>";
// шаблон для разделов навески
$APPLICATION->IncludeComponent(
        "bitrix:catalog.section.list", "", array(
    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
    "CACHE_TYPE" => "N",
    "CACHE_TIME" => $arParams["CACHE_TIME"],
    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
    "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
    "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
    "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
    "SECTION_CODE" => "navesnoe-oborudovanie",
    "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
    "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"]
        ), $component
);
//global $USER;

//if ($USER->GetID() == 10)
//{
    $arFilter = array('CODE' => $arResult["VARIABLES"]["SECTION_CODE"], 'IBLOCK_ID' => 6, 'IBLOCK_TYPE' => 'catalog');
    $arGroupBy = false;
    $arNavStartParams = false;
    $arSelectFields = array('ID', 'IBLOCK_ID', 'CODE', 'NAME');

    $rcs = CIBlockSection::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
    while ($arSect = $rcs->GetNext())
       {
        $arResult["VARIABLES"]["SECTION_ID"] = $arSect["ID"];   
       } 
    //print_r($arResult["VARIABLES"]["SECTION_ID"]) ;
    $_SESSION["CATALOG_FILTER_NAME"] = $arParams["FILTER_NAME"];
    $_SESSION["CATALOG_IBLOCK_TYPE"] = $arParams["IBLOCK_TYPE"];
    $_SESSION["CATALOG_IBLOCK_ID"] = $arParams["IBLOCK_ID"];  
    
    $arFilter = array('IBLOCK_ID' =>  $arParams["IBLOCK_ID"], "ID" => $arResult["VARIABLES"]["SECTION_ID"]);
    $db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, array("UF_TEMPLATE"));
    if($ar_result_uf = $db_list->GetNext())
    {
        $_REQUEST["UF_TEMPLATE"] = $ar_result_uf["UF_TEMPLATE"];
    }
    
    $APPLICATION->IncludeComponent(
    "kombox:filter", 
    ($ar_result_uf["UF_TEMPLATE"])?$ar_result_uf["UF_TEMPLATE"]:".default", 
    Array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
        "FILTER_NAME" => $arParams["FILTER_NAME"],
        "PRICE_CODE" => $arParams["PRICE_CODE"],
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_NOTES" => "",
        "CACHE_GROUPS" => "Y",
        "SAVE_IN_SESSION" => "N",
        "CLOSED_PROPERTY_CODE" => array(),
        "CLOSED_OFFERS_PROPERTY_CODE" => array()
            ), $component
    );
    global $arrFilter;
        foreach($arrFilter as $key=>$arrFiltered){
            foreach($arrFiltered as $index=>$resulted){   
                if(strpos($key, 'PROPERTY') !== false){  
                    preg_match('/\d+$/', $key, $matches);
                    $result = array($matches[0]=>$resulted);
                    $filterres = array("PROPERTY"=>$result);
                    
                } 
            }   
        }   
//}
?>

<?
//if ('Y' == $arParams['USE_FILTER'])
//{
    /* if (CModule::IncludeModule("iblock"))
      {
      $arFilter = array(
      "IBLOCK_ID" => $arParams["IBLOCK_ID"],
      "ACTIVE" => "Y",
      "GLOBAL_ACTIVE" => "Y",
      );
      if(0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
      {
      $arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
      }
      elseif('' != $arResult["VARIABLES"]["SECTION_CODE"])
      {
      $arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
      }

      $obCache = new CPHPCache();
      if($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
      {
      $arCurSection = $obCache->GetVars();
      }
      else
      {
      $arCurSection = array();
      $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));

      if(defined("BX_COMP_MANAGED_CACHE"))
      {
      global $CACHE_MANAGER;
      $CACHE_MANAGER->StartTagCache("/iblock/catalog");

      if ($arCurSection = $dbRes->GetNext())
      {
      $CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);
      }
      $CACHE_MANAGER->EndTagCache();
      }
      else
      {
      if(!$arCurSection = $dbRes->GetNext())
      $arCurSection = array();
      }

      $obCache->EndDataCache($arCurSection);
      }
      }
      $APPLICATION->IncludeComponent(
      "bitrix:catalog.smart.filter",
      ".default",
      Array(
      "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
      "IBLOCK_ID" => $arParams["IBLOCK_ID"],
      "SECTION_ID" => $arCurSection['ID'],
      "FILTER_NAME" => $arParams["FILTER_NAME"],
      "PRICE_CODE" => $arParams["PRICE_CODE"],
      "CACHE_TYPE" => "A",
      "CACHE_TIME" => "36000000",
      "CACHE_NOTES" => "",
      "CACHE_GROUPS" => "Y",
      "SAVE_IN_SESSION" => "N",
      "XML_EXPORT" => "Y",
      "SECTION_TITLE" => "NAME",
      "SECTION_DESCRIPTION" => "DESCRIPTION"
      ),
      $component,
      array('HIDE_ICONS' => 'Y')
      ); */
   /* $APPLICATION->IncludeComponent(
            "areal:catalog.filter", ".default", Array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
        "FILTER_NAME" => $arParams["FILTER_NAME"],
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_NOTES" => "",
        "CACHE_GROUPS" => "Y",
            ), $component, array('HIDE_ICONS' => 'Y')
    );
}   */
?>




<?
$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], "CODE" => $arResult["VARIABLES"]["SECTION_CODE"]); // 
$rsSect = CIBlockSection::GetList(array(), $arFilter);
$arSect = $rsSect->GetNext();

$intSectionID = $arSect["ID"];

//print "<pre>"; print_r($arSect);print "</pre>";

$arFilter = array('IBLOCK_ID' => $arSect['IBLOCK_ID'], '>LEFT_MARGIN' => $arSect['LEFT_MARGIN'], '<RIGHT_MARGIN' => $arSect['RIGHT_MARGIN'], '>DEPTH_LEVEL' => $arSect['DEPTH_LEVEL'], "ACTIVE" => "Y"); // выберет потомков 
//$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter);
$cnt = CIBlockSection::GetCount($arFilter);



if (!empty($_REQUEST["quantity"]))
{
    $_SESSION["quantity"] = $_REQUEST["quantity"];
    $quantity = $_REQUEST["quantity"];
}
if (isset($_SESSION["quantity"]) && $_SESSION["quantity"] > 0)
    $quantity = $_SESSION["quantity"];
else
    $quantity = LIST_COUNT_DEFAULT;
?>
<div class="hr"></div>
<? 

if($cnt==0)ShowPagging($quantity); 

?>
<?
if (!empty($_REQUEST["view_page"]))
{
    $_SESSION["view"] = $_REQUEST["view_page"];
    $view = $_REQUEST["view_page"];
}
if (isset($_SESSION["view"]) && strlen($_SESSION["view"]) > 0)
    $view = $_SESSION["view"];
else
    $view = "plate";
?>
<?if($cnt==0):?>
<div class="paging">
    <div class="page-view">
        <span>Вид страницы:</span>
        <ul>
            <li>
                <a href="<?= $APPLICATION->GetCurPageParam("view_page=plate", array("view_page"), false) ?>" class="<? if ($view == "plate"): ?>active <? endif ?>plate-view"><span></span></a>
                <div class="tooltip">Плиткой</div>
            </li>
            <li>
                <a href="<?= $APPLICATION->GetCurPageParam("view_page=list", array("view_page"), false) ?>" class="<? if ($view == "list"): ?>active <? endif ?> list-view"><span></span></a>
                <div class="tooltip">Списком</div>
            </li>
        </ul>
    </div>
</div>
<?endif;?>
<?
$intSectionID = 0;



 //print_r($cnt);

if ($cnt == "0" ) // если подразделов нет, то выводим товары
{

    $intSectionID = $APPLICATION->IncludeComponent(
            "areal:catalog.section", $view, array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
        "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
        "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
        "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
        "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
        "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
        "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
        "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
        "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
        "BASKET_URL" => $arParams["BASKET_URL"],
        "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
        "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
        "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
        "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
        "FILTER_NAME" => $arParams["FILTER_NAME"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_FILTER" => $arParams["CACHE_FILTER"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "SET_TITLE" => "Y",
        "ADD_SECTIONS_CHAIN" => "Y",
        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
        "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
        "PAGE_ELEMENT_COUNT" => $quantity,
        "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
        "PRICE_CODE" => $arParams["PRICE_CODE"],
        "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
        "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
        "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
        "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
        "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
        "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
        "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
        "PAGER_TITLE" => $arParams["PAGER_TITLE"],
        "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
        "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
        "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
        "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
        "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
        "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
        "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
        'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
        'LABEL_PROP' => $arParams['LABEL_PROP'],
        'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
        'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
        'PERMANENT_PROPERTY' => $arParams['PERMANENT_PROPERTY']
            ), $component
    );

    ShowPagging($quantity);
}
else  // иначе подразделы
{
    $APPLICATION->IncludeComponent(
            "tezart:catalog.section.list", "", array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
        "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
        "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
        "FILTER_NAME" => $filterres,
        "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"]
            ), $component
    );
}
?>
<div class="clear"></div>
<? if ($arResult["VARIABLES"]["SECTION_CODE"] || $arResult["VARIABLES"]["SECTION_ID"]): ?>
    <div class="catalog-hr"></div>
    <div class="catalog-information-left-col">
        <div class="help-plate">
            <div class="need-help">Сложности с выбором?</div>
            <div class="phone">Мы поможем&nbsp;&nbsp;&mdash;&nbsp;<span><? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/phone.php"), false); ?></span></div>
        </div>
        <? if (!empty($LONG_SEO_TEXT)): ?>
            <div class="useful-info">
                <div class="icon-title">Полезная информация<span></span></div>
                <?= $LONG_SEO_TEXT; ?>
            </div>
        <? endif; ?>
        <?
        if ($SECTION_ID > 0):
            global $faq_filter;
            $faq_filter = array("PROPERTY_CATALOG_CATALOG" => $SECTION_ID);
            $APPLICATION->IncludeComponent(
                    "bitrix:news.list", "faq", Array(
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "about_company",
                "IBLOCK_ID" => "17",
                "NEWS_COUNT" => "4",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FILTER_NAME" => "faq_filter",
                "FIELD_CODE" => array(),
                "PROPERTY_CODE" => array(),
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "SET_TITLE" => "N",
                "SET_STATUS_404" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "PAGER_TEMPLATE" => ".default",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "PAGER_TITLE" => "Новости",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N"
                    ), false
            );
            $APPLICATION->IncludeComponent(
                    "bitrix:news.list", "articles", Array(
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "about_company",
                "IBLOCK_ID" => "16",
                "NEWS_COUNT" => "4",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FILTER_NAME" => "faq_filter",
                "FIELD_CODE" => array(),
                "PROPERTY_CODE" => array(),
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "SET_TITLE" => "N",
                "SET_STATUS_404" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "PAGER_TEMPLATE" => ".default",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "PAGER_TITLE" => "Новости",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N"
                    ), false
            );
        endif;
        ?>
    </div>
    <? if ($SECTION_ID > 0): ?>
        <? $APPLICATION->IncludeComponent("areal:video.catalog", ".default", array("IBLOCK_ID" => VIDEO, "FILTER" => array("PROPERTY_CATALOG_CATALOG" => $SECTION_ID))); ?>
    <? endif; ?>
    <div class="clear"></div>
<? endif; ?>
<div class="dialog" id="order_catalog">
    <? $APPLICATION->IncludeComponent("areal:form.order.new", "popup", array("THEME_TYPE" => "katalog")); ?>
</div>