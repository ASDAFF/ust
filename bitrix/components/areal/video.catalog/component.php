<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$cache = new CPHPCache();
	$cache_time = 3600;
	$arResult = array();
	$name_array_cache = md5(serialize($arParams));
	$cache_dir_id = $name_array_cache;
	$cache_dir_path = '/video/';
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
	   $res = $cache->GetVars();
	   if (is_array($res[$name_array_cache]) && (count($res[$name_array_cache]) > 0))
		  $arResult = array_merge($arResult, $res[$name_array_cache]);
	}
	else {
		$arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
		if(isset($arParams["FILTER"]))
			$arFilter = array_merge($arFilter, $arParams["FILTER"]);
		$videos = CIBlockElement::GetList(array("SORT" => "ASC", "CREATED" => "ASC"), $arFilter, false, false, array("ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_USED_CATALOG", "PROPERTY_ARENDA_CATALOG"));
		$count = 0;
		while($video = $videos->GetNext()) {
			$count++;
			$arResult["VIDEO"][] = array(
				"ID" => $video["ID"],
				"NAME" => $video["NAME"],
				"PREVIEW_PICTURE" => (
					($count <= 4) 
					? 
					(CFile::ResizeImageGet( 
						$video["PREVIEW_PICTURE"], 
						array("width" => 233, "height" => 149), 
						BX_RESIZE_IMAGE_EXACT,
						true 
					)) 
					:
					(CFile::ResizeImageGet( 
					$video["PREVIEW_PICTURE"], 
					array("width" => 104, "height" => 65), 
					BX_RESIZE_IMAGE_EXACT,
					true 
					))
				)
			);
		}
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array($name_array_cache => $arResult));
		}
	}
	$this->IncludeComponentTemplate();	
}
?>