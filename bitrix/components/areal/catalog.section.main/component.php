<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$cache = new CPHPCache();
	$cache_time = 3600;
	$arResult = array();
	$cache_dir_id = 'catalog.section.main';
	$cache_dir_path = '/catalog.section.main/';
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
	   $res = $cache->GetVars();
	   if (is_array($res["catalog.section.main"]) && (count($res["catalog.section.main"]) > 0))
		  $arResult = array_merge($arResult, $res["catalog.section.main"]);
	}
	else {
		$sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y", "DEPTH_LEVEL" => 2), false);
		$count = 0;
		while($section = $sections->GetNext()) {
			if($count < 12)
				$arResult["SECTIONS"] = array(
					"NAME" => $section["NAME"],
					"SECTION_PAGE_URL" => $section["NAME"]
				);
			$count++;
		}
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array("catalog.section.main" => $arResult));
		}
	}
	
	$this->IncludeComponentTemplate();
}
?>