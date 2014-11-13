<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {	
	$url = $_SERVER["SCRIPT_URL"];
	$expl_url = explode("/", $url);
	if($expl_url[count($expl_url)-1] == "index.php") $expl_url[count($expl_url)-1] = "";
	$new_url = implode("/", $expl_url);
	$full_url = str_replace("/", "_", $new_url);
	$cache = new CPHPCache();
	$cache_time = 3600;
	$arResult = array();
	$cache_dir_id = 'left_banner_'.$full_url;		
	$cache_dir_path = '/left_banner_'.$full_url."/";
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
	   $res = $cache->GetVars();
	   if (is_array($res['left_banner_'.$full_url]) && (count($res['left_banner_'.$full_url]) > 0))
		  $arResult = array_merge($arResult, $res['left_banner_'.$full_url]);
	}
	else {
		$res = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => LEFT_BANNERS, "ACTIVE" => "Y", "PROPERTY_URL" => $new_url), false, false, array("NAME", "PREVIEW_PICTURE", "PROPERTY_URL", "PROPERTY_FLASH"));
		if($element = $res->GetNext()) {
			$arResult["BANNER"] = array(
				"NAME" => $element["NAME"],
				"PICTURE" => CFile::ResizeImageGet(
					$element['PREVIEW_PICTURE'], 
					array('width' => 264, 'height' => 1000), 
					BX_RESIZE_IMAGE_PROPORTIONAL, 
					true
				),
				"FLASH" => CFile::GetPath($element["PROPERTY_FLASH_VALUE"])
			);
		}
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array('left_banner_'.$full_url => $arResult));
		}
	}
	$this->IncludeComponentTemplate();
}
?>