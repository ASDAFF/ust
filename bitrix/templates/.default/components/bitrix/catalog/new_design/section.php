<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
//global $USER;
//if ($USER->IsAdmin())
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

$arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_ID"], "ID" => $arResult["VARIABLES"]["SECTION_ID"]);
$db_list = CIBlockSection::GetList(Array("SORT" => "ASC"), $arFilter, false, array("UF_TEMPLATE"));
if ($ar_result_uf = $db_list->GetNext())
{
    $_REQUEST["UF_TEMPLATE"] = $ar_result_uf["UF_TEMPLATE"];
}

?>


	<?
	$file = $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/.default/components/bitrix/catalog/new_design/test.txt";
	
	$propsUserArray = array();
	$counter = 0;
	$count = 0;
 	$dbProps = CIBlockProperty::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID"=>155));
      while ($arProp = $dbProps->GetNext())
      {
		$dbPropEnum = CIBlockProperty::GetPropertyEnum($arProp["ID"], array("ID"=>"ASC"));
		
			$curName = $arProp["NAME"];
			$curID= $arProp["ID"];
		while($arPropEnum = $dbPropEnum->Fetch())
		{
			$propsUserArray[$count]["VAL"][] = print_r($arPropEnum["VALUE"], true);
		}
		if ($propsUserArray[$count]["NAME"] == "") { 
			$propsUserArray[$count]["NAME"] = $curName;
			$propsUserArray[$count]["ID"] = $curID;
			}
		$count++;
      }
		//file_put_contents($file, print_r($propsUserArray, true));
	

	
	?>
<?if ($APPLICATION->GetCurDir() == "/catalog/betonnye-zavody/"):?>

<div style="display:nones">
<style type="text/css">
.k-wrp {
    display: table;
    width: 100%;
}
	.k-send {
    float: right;
    width: 44%;
}
.konfigurator {
    float: left;
    width: 49%;
}
.k-row {
    display: table-row;
    width: 100%;
}
.k-name {
    display: table-cell;
    padding: 0 20px 10px 0;
    vertical-align: middle;
    width: 60%;
}
.k-row .sbHolder {
    float: right;
    width: 100%;
	margin-bottom: 10px;
}
.sort_button_div
{
    background: url("/design/images/button_red.jpg") repeat-x scroll 0 0 rgba(0, 0, 0, 0);
    border: 1px solid #b1151a;
    border-radius: 4px;
    color: #ffffff;
    cursor: pointer;
    display: inline-block;
    font-family: Verdana;
    font-size: 14px;
    font-weight: bold;
    line-height: 25px;
    outline: medium none;
    padding: 0 10px;
    position: relative;
    text-align: center;
}
.k-model {
    font-size: 14px;
    font-weight: bold;
	margin: 5px 0 0 10px;
	float:right;
}
.k-send .k-row input {
    margin-bottom: 10px;
}
.k-info {
    float: left;
    width: 100%;
	margin: 12px 0;
}
.k-number-title {
    float: left;
    font-size: 15px;
    font-weight: bold;
    margin-bottom: 17px;
    margin-right: 20px;
    margin-top: 17px;
}
.k-num {
	color: #ec2b32;
    float: left;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 17px;
    margin-top: 17px;
}
.k-half-row {
    float: left;
    margin-bottom: 10px;
    width: 49%;
}
.k-send .sort_button_div {
    width: 100%;
}
label.k-half-name {
    background-position: left top;
    display: inline-block;
    padding: 0 0 0 25px;
    position: relative;
}
</style>

	<form action="" class="k-wrp">
	<div class="konfigurator">
	<?$counter = 0;?>
		<?foreach($propsUserArray as $userPropItem):?>
		<?if ($userPropItem["ID"] != 2259 && $userPropItem["ID"] != 2260 && $userPropItem["ID"] != 2261):?>
		<div class="k-row">
			<span class="k-name"><?=$userPropItem["NAME"]?>:</span>
			<select id="var_<?=$counter;?>" value="" class="k-select">
				<?foreach($userPropItem["VAL"] as $propElem):?>
				<option value="<?=$propElem?>"><?=$propElem?></option>
				<?endforeach;?>
			</select>
		</div>
		<?else:?>
		<div class="k-half-row">
			<label class="k-half-name label_checked checkbox_off"><input  id="var_<?=$counter;?>" class="kombox-hidden-input k-select" type="checkbox" value="Y" /><?=$userPropItem["NAME"]?></label>
		</div>
		<?endif;?>
		<?$counter++;?>
		<?endforeach;?>
			<div class="sort_button_div">Рекомендуемая модель завода:</div>
			<span class="k-model">Euromix 60</span>
	</div>
	<div class="k-send">
		<div class="k-row">
			<span class="k-name">Имя<span class="req">*</span>:</span>
			<input type="text"/>
		</div>
		<div class="k-row">
			<span class="k-name">E-mail:</span>
			<input type="text"/>
		</div>
		<div class="k-row">
			<span class="k-name">Телефон<span class="req">*</span>:</span>
			<input type="text"/>
		</div>
		<div class="k-row">
			<span class="k-name">Регион:</span>
			<input type="text"/>
		</div>
			<span class="k-info">* - поля, обязательные для заполнения</span>
		<span class="k-number-title">Ваш уникальный номер: </span>
		<span class="k-num">26459</span>
		<input class="sort_button_div" type="submit" value="Заказать консультацию специалиста" />
	</div>
	</form>
</div>

<script type="text/javascript">
	$( ".k-row .k-select" ).change(function() {
		//alert( $("body").find(".k-row select#var_1").val() );
 		$.ajax({
		type: "POST",
		url: "/bitrix/templates/.default/components/bitrix/catalog/new_design/konfigurator.php",
		data: { 
			naznachenie: $("body").find(".k-row .k-select#var_0").val(), 
			proizvod: $("body").find(".k-row .k-select#var_1").val(),
			sumarob: $("body").find(".k-row .k-select#var_2").val(),
			himdob: $("body").find(".k-row .k-select#var_3").val(),
			obshivka: $("body").find(".k-row .checkbox_on #var_4").val(),
			obogrev: $("body").find(".k-row .checkbox_on #var_5").val(),
			generator: $("body").find(".k-row .checkbox_on #var_6").val()
			}
		}) 
	});
</script>
<?endif;?>
<?

//($ar_result_uf["UF_TEMPLATE"])?$ar_result_uf["UF_TEMPLATE"]:".default",
$APPLICATION->IncludeComponent(
        "kombox:filter", ($ar_result_uf["UF_TEMPLATE"]) ? $ar_result_uf["UF_TEMPLATE"] : ".default",
        //".default",
        //"horizontal_filter_test",
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
//}
include($_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/meta_tags.php');
print_r ($s_meta_array[$_SERVER['REQUEST_URI']][seosmall]);
//echo $_SERVER['REQUEST_URI'];
?>
<?
//if (0)
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
/*    $APPLICATION->IncludeComponent(
  "areal:catalog.filter",
  ".default",
  Array(
  "IBLOCK_ID" => $arParams["IBLOCK_ID"],
  "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
  "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
  "FILTER_NAME" => $arParams["FILTER_NAME"],
  "CACHE_TYPE" => "A",
  "CACHE_TIME" => "36000000",
  "CACHE_NOTES" => "",
  "CACHE_GROUPS" => "Y",
  ),
  $component,
  array('HIDE_ICONS' => 'Y')
  ); */
//}
?>
<? /* SEO-текст */ ?>

<?
if ($arResult["VARIABLES"]["SECTION_CODE"] || $arResult["VARIABLES"]["SECTION_ID"])
{
    if ($arResult["VARIABLES"]["SECTION_CODE"])
        $filter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $arResult["VARIABLES"]["SECTION_CODE"]);
    elseif ($arResult["VARIABLES"]["SECTION_ID"])
        $filter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ID" => $arResult["VARIABLES"]["SECTION_ID"]);

    $sect = CIBlockSection::GetList(array(), $filter, false, array("UF_LONG_SEO_TEXT", "UF_SHORT_SEO_TEXT"));
    if ($sec = $sect->GetNext())
    {
        $SECTION_ID = $sec["ID"];
        //$DEPTH_LEVEL = $sec["DEPTH_LEVEL"];
        //seo code
        //добавлено условие, чтобы сео-тексты отображались только на главной странице раздела
        if (!empty($sec["~UF_SHORT_SEO_TEXT"]) and !preg_match("/PAGEN|filter/",$_SERVER['REQUEST_URI']))
            $SHORT_SEO_TEXT = $sec["~UF_SHORT_SEO_TEXT"];
        if (!empty($sec["~UF_LONG_SEO_TEXT"]) and !preg_match("/PAGEN|filter/",$_SERVER['REQUEST_URI']))
            $LONG_SEO_TEXT = $sec["~UF_LONG_SEO_TEXT"];
        //seo code end
    }
}
?>
    <? if (!empty($SHORT_SEO_TEXT)): ?>
    <div class="catalog_description">
   
    <?= $SHORT_SEO_TEXT ?>
        <p><a href="#" class="to_useful">Подробнее &raquo;</a></p>
    </div>
<? endif; ?>

<?
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
<? ShowPagging($quantity); ?>
<?
$res = CIBlockSection::GetByID($arResult["VARIABLES"]["SECTION_ID"]); //получаем глубину раздела
if($ar_res = $res->GetNext()) $depth = $ar_res['DEPTH_LEVEL'];

if (!empty($_REQUEST["view_page"]))
{
    $_SESSION["view"] = $_REQUEST["view_page"];
    $view = $_REQUEST["view_page"];
}
if (isset($_SESSION["view"]) && strlen($_SESSION["view"]) > 0)
    $view = $_SESSION["view"];
else if ($depth == 1) $view = "plate";
else if ($depth >= 2) $view = "list";
//else
//    $view = "list";
?>
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
<?
$intSectionID = 0;
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
        //seo code
        if ($SECTION_ID > 0 and !preg_match("/PAGEN|filter/",$_SERVER['REQUEST_URI'])):
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
        endif;
        if ($SECTION_ID > 0 and !preg_match("/PAGEN|filter/",$_SERVER['REQUEST_URI'])):
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
        <? if ($SECTION_ID > 0 and !preg_match("/PAGEN|filter/",$_SERVER['REQUEST_URI'])): ?>
            <? $APPLICATION->IncludeComponent("areal:video.catalog", ".default", array("IBLOCK_ID" => VIDEO, "FILTER" => array("PROPERTY_CATALOG_CATALOG" => $SECTION_ID))); ?>
        <? endif; ?>
    <div class="clear"></div>
<? endif; ?>
<div class="dialog" id="order_catalog">
    <? $APPLICATION->IncludeComponent("areal:form.order.new", "popup", array("THEME_TYPE" => "katalog")); ?>
</div> 