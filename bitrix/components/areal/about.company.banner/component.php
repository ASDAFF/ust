<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
if(CModule::IncludeModule("iblock")) {
	$cache = new CPHPCache();
	$cache_time = 3600;
	$arResult = array();
	$cache_dir_id = "about_company_".$arParams["LOCATION"];
	$cache_dir_path = "/about_company_".$arParams["LOCATION"]."/";
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
	   $res = $cache->GetVars();
	   if (is_array($res['about_company_'.$arParams["LOCATION"]]) && (count($res['about_company_'.$arParams["LOCATION"]]) > 0))
		  $arResult = array_merge($arResult, $res['about_company_'.$arParams["LOCATION"]]);
	}
	else {
		$property_enums = CIBlockPropertyEnum::GetList(Array(), Array("IBLOCK_ID" => BANNER_ABOUT_COMPANY, "CODE" => "TYPE"));
		while($enum_fields = $property_enums->GetNext())
			$type[$enum_fields["XML_ID"]] = $enum_fields["ID"];
		
		$res = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), array("IBLOCK_ID" => BANNER_ABOUT_COMPANY, "ACTIVE" => "Y", "PROPERTY_TYPE" => $type[$arParams["LOCATION"]]), false, false, array("ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_TYPE"));
		while($banner = $res->GetNext())
			$arResult["BANNER"][] = array(
				"ID" => $banner["ID"],
				"NAME" => $banner["NAME"],
				"PREVIEW_PICTURE" => ($arParams["LOCATION"] == "TOP")
				? (CFile::ResizeImageGet( 
					$banner["PREVIEW_PICTURE"], 
					array("width" => 590, "height" => 198), 
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true 
				)) : (CFile::ResizeImageGet( 
					$banner["PREVIEW_PICTURE"], 
					array("width" => 199, "height" => 127), 
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true 
				))
			);
		
			
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array('about_company_'.$arParams["LOCATION"] => $arResult));
		}
	}

	$this->IncludeComponentTemplate();
}
?>