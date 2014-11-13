<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$cache = new CPHPCache();
	$cache_time = 3600;
	$arResult = array();
	$cache_dir_id = 'actions';
	$cache_dir_path = '/actions/';
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
	   $res = $cache->GetVars();
	   if (is_array($res["actions"]) && (count($res["actions"]) > 0))
		  $arResult = array_merge($arResult, $res["actions"]);
	}
	else {
		$res = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => ACTIONS, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y"), false, false, array("ID", "PREVIEW_PICTURE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "NAME", "DETAIL_PAGE_URL", "PROPERTY_METKA", "IBLOCK_ID"));
		while($element = $res->GetNext())
			$arResult["ACTIONS"][] = array(
				"ID" => $element["ID"],
				"NAME" => $element["NAME"],
				"PREVIEW_PICTURE" =>  CFile::ResizeImageGet( 
					$element["PREVIEW_PICTURE"], 
					array("width" => 252, "height" => 128), 
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true 
				),
				"PREVIEW_TEXT" => $element["PREVIEW_TEXT"],
				"PREVIEW_TEXT_TYPE" => $element["PREVIEW_TEXT_TYPE"],
				"DETAIL_PAGE_URL" => $element["DETAIL_PAGE_URL"],
				"METKA" => $element["PROPERTY_METKA_VALUE"]
			);
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array("actions" => $arResult));
		}
	}
	$this->IncludeComponentTemplate();
}
?>