<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$cache = new CPHPCache();
	$cache_time = 3600;
	$arResult = array();
	$name_array_cache = md5(serialize($arParams));
	$cache_dir_id = $name_array_cache;
	$cache_dir_path = '/useful.information/';
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
	   $res = $cache->GetVars();
	   if (is_array($res[$name_array_cache]) && (count($res[$name_array_cache]) > 0))
		  $arResult = array_merge($arResult, $res[$name_array_cache]);
	}
	else {
		if(isset($arParams["SECTION_CODE"]))
			$filter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "CODE" => $arParams["SECTION_CODE"]);
		if(isset($arParams["SECTION_ID"])) 
			$filter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $arParams["SECTION_ID"]);
		$cur_section = CIBlockSection::GetList(array(), $filter, false);
		if($cur = $cur_section->GetNext())
			$depth_level = $cur["DEPTH_LEVEL"];

		$sections = CIBlockSection::GetList(array("SORT" => "ASC", "NAME" => "ASC"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "DEPTH_LEVEL" => $depth_level), false);
		while($section = $sections->GetNext()) {
			if(!empty($section["DESCRIPTION"]) || $section["CODE"] == $arParams["SECTION_CODE"] || $section["ID"] == $arParams["SECTION_ID"])
				$arResult["SECTIONS"][] = array(
					"ID" => $section["ID"],
					"CODE" => $section["CODE"],
					"NAME" => $section["NAME"],
					"DESCRIPTION" => $section["DESCRIPTION"],
					"DESCRIPTION_TYPE" => $section["DESCRIPTION_TYPE"],
					"SELECTED" => 0
				);			
		}
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array($name_array_cache => $arResult));
		}
	}
	
	foreach($arResult["SECTIONS"] as $key => $section) 	
		if($section["CODE"] == $arParams["SECTION_CODE"] || $section["ID"] == $arParams["SECTION_ID"])
			$arResult["SECTIONS"][$key]["SELECTED"] = 1;

	$this->IncludeComponentTemplate();
}
?>