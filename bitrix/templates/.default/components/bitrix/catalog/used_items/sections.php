<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="bu-top">
	<div class="left-side">
		<div class="catalog-section-descr">
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "catalog", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/used_stroy_default_seo.php"), false);?>
		</div>
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "catalog", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/used_advantages.php"), false);?>
	</div>
	<?$APPLICATION->IncludeComponent("areal:form.order", "template3", Array(
	"THEME_TYPE" => "b-u-tekhnika"
	),
	false
);?>
	<div class="clear"></div>
</div>
<?$APPLICATION->IncludeComponent(
	"areal:catalog.section.list",
	"",
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_USER_FIELDS" => array("UF_SHORT_SEO_TEXT"),
		"SECTION_FIELDS" => array("DESCRIPTION"),
		"DEFAULT_PAGE" => $arResult["FOLDER"]
	),
	$component
);?>
<?if(!empty($_REQUEST["quantity"])) {
	$_SESSION["quantity"] = $_REQUEST["quantity"];
	$quantity = $_REQUEST["quantity"];
}
if(isset($_SESSION["quantity"]) && $_SESSION["quantity"] > 0)
	$quantity = $_SESSION["quantity"];
else $quantity = 1000;
?>
<div class="hr"></div>
<?ShowPagging($quantity);?>
<?$APPLICATION->IncludeComponent(
	"areal:catalog.section",
	"",
	array(
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
		"GENERAL_PROPERTIES_LIST" => $arParams["GENERAL_PROPERTIES_LIST"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"SET_STATUS_404" => "N",
		"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
		"PAGE_ELEMENT_COUNT" => $quantity ? $quantity : 6,
		"LINE_ELEMENT_COUNT" => 1,
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
		"SHOW_ALL_WO_SECTION" => "Y",
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"]
	),
	$component
);
?>
<?ShowPagging($quantity);?>
<div class="clear"></div>
<div class="dialog" id="order_catalog">
	<?$APPLICATION->IncludeComponent("areal:form.order", "popup", array("THEME_TYPE" => "b-u-tekhnika"));?>
</div>