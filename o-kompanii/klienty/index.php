<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Клиенты");?> <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => SITE_DIR."include/about_company/klienti.php",
		"EDIT_TEMPLATE" => ""
	)
);?> 
<?$APPLICATION->IncludeComponent("areal:news.list", "onlypicture", array("IBLOCK_ID" => "32", "NEWS_COUNT" => 30, "SORT_BY1" => "SORT", "SORT_ORDER1" => "ASC", "SORT_BY2" => "NAME", "SORT_ORDER2" => "ASC", "CACHE_TYPE" => "A", "CACHE_TIME" => "36000000", "SET_TITLE" => "Y"));?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>