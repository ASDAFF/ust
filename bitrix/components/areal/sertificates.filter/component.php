<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


if(CModule::IncludeModule("iblock")) {
	$cache = new CPHPCache();
	$cache_time = 3600;
	$arResult = array();
	$cache_dir_id = 'sertificates_filter';
	$cache_dir_path = '/sertificates_filter/';
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
	   $res = $cache->GetVars();
	   if (is_array($res["sertificates_filter"]) && (count($res["sertificates_filter"]) > 0))
		  $arResult = array_merge($arResult, $res["sertificates_filter"]);
	}
	else {
            
		$res = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => CERTIFICATES, "ACTIVE" => "Y"), false, false, array("ID", "PROPERTY_CATALOG"));
		$types = array();
		while($element = $res->GetNext())
			$types = array_merge($types, $element["PROPERTY_CATALOG_VALUE"]);
		$types = array_unique($types);
		
		$sections = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y", "ID" => $types), false);
		while($section = $sections->GetNext())
			$arResult["SECTIONS"][] = array(
				"ID" => $section["ID"],
				"NAME" => $section["NAME"]
			);
		
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array("sertificates_filter" => $arResult));
		}
	}		
	$this->IncludeComponentTemplate();
	
	if(isset($_REQUEST["type"]) && $_REQUEST["type"] > 0) {
		$GLOBALS[$arParams["FILTER_NAME"]] = array("PROPERTY_CATALOG" => $_REQUEST["type"]);
	}
}?>