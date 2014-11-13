<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
if(CModule::IncludeModule("iblock")) {	
	$url = $_SERVER["REQUEST_URI"];
	$expl_url = explode("/", $url);
	if($expl_url[count($expl_url)-1] == "index.php") $expl_url[count($expl_url)-1] = "";
        
        
	$new_url = implode("/", $expl_url);
	$full_url = str_replace("/", "_", $new_url);
	$cache = new CPHPCache();
	$cache_time = 3600;
	$arResult = array();
	$cache_dir_id = 'tabs_'.$full_url;		
	$cache_dir_path = '/tabs_'.$full_url."/";
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
	   $res = $cache->GetVars();
	   if (is_array($res['tabs_'.$full_url]) && (count($res['tabs_'.$full_url]) > 0))
		  $arResult = array_merge($arResult, $res['tabs_'.$full_url]);
	}
	else {
		$res = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => TABS, "ACTIVE" => "Y", "PROPERTY_URL" => $new_url), false, false, array("ID", "PREVIEW_PICTURE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "NAME", "CODE", "PROPERTY_URL"));
		while($element = $res->GetNext()) {
			$arResult["TABS"][] = array(
				"ID" => $element["ID"],
				"NAME" => $element["NAME"],
				"CODE" => $element["CODE"],
				"PREVIEW_TEXT" => $element["PREVIEW_TEXT"],
				"PREVIEW_TEXT_TYPE" => $element["PREVIEW_TEXT_TYPE"],
				"PREVIEW_PICTURE" => CFile::ResizeImageGet( 
					$element["PREVIEW_PICTURE"], 
					array("width" => 218, "height" => 156), 
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true 
				)
			);
		}
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array('tabs_'.$full_url => $arResult));
		}
	}
	
	$this->IncludeComponentTemplate();
}
?>