<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$cache = new CPHPCache();
	$cache_time = 3600;
	$arResult = array();
	$cache_dir_id = 'towns';
	$cache_dir_path = '/towns/';
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
	   $res = $cache->GetVars();
	   if (is_array($res["towns"]) && (count($res["towns"]) > 0))
		  $arResult = array_merge($arResult, $res["towns"]);
	}
	else {
		$res = CIBlockElement::GetList(array("SORT" => "ASC", "NAME" => "ASC"), array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y", "!PROPERTY_SHOW_IN_TOWN_FORM" => false), false, false, array("ID", "NAME"));
		while($element = $res->GetNext())
			$arResult["TOWNS"][] = array(
				"ID" => $element["ID"],
				"NAME" => $element["NAME"],
				"SELECTED" => 0
			);
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array("towns" => $arResult));
		}
	}
	foreach($arResult["TOWNS"] as $key => $town)
		if($town["NAME"] == $_SESSION["SELECTED_TOWN"])
			$arResult["TOWNS"][$key]["SELECTED"] = 1;
		
	$this->IncludeComponentTemplate();
}
?>