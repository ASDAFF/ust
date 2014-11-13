<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$cache = new CPHPCache();
	$cache_time = 3600;
	$arResult = array();
	$cache_dir_id = 'career';
	$cache_dir_path = '/career/';
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
	   $res = $cache->GetVars();
	   if (is_array($res["career"]) && (count($res["career"]) > 0))
		  $arResult = array_merge($arResult, $res["career"]);
	}
	else {
		/* Слайдеры */
		$sliders = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => CAREER_SLIDER, "ACTIVE" => "Y"), false, array("nTopCount" => 3), array("ID", "NAME", "PROPERTY_IMAGE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "PROPERTY_URL"));
		while($slider = $sliders->GetNext()) {
			unset($url);
			unset($photo);
			if(stripos($slider['PROPERTY_URL_VALUE'], "http://") === true || stripos($slider['PROPERTY_URL_VALUE'], "https://") === true)
				$url = $slider['PROPERTY_URL_VALUE'];
			elseif(stripos($slider['PROPERTY_URL_VALUE'], "www") === true)
				$url = "http://".$slider['PROPERTY_URL_VALUE'];
			else
				$url = $slider['PROPERTY_URL_VALUE'];
				
			if(!empty($slider["PROPERTY_IMAGE_VALUE"])) 
				foreach($slider["PROPERTY_IMAGE_VALUE"] as $arImage)
					$photo[] = CFile::ResizeImageGet(
						$arImage, 
						array('width' => 278, 'height' => 164), 
						BX_RESIZE_IMAGE_PROPORTIONAL, 
						true
					);
			$arResult["SLIDERS"][] = array(
				"ID" => $slider["ID"],
				"NAME" => $slider["NAME"],
				"PREVIEW_TEXT" => $slider["PREVIEW_TEXT"],
				"PREVIEW_TEXT_TYPE" => $slider["PREVIEW_TEXT_TYPE"],
				"PHOTO" => $photo,
				"URL" => $url
			);
		}
		
		/* Вакансии */
		$vacancies = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => CAREER_VACANCY, "ACTIVE" => "Y"), false, array("nTopCount" => 2), array("ID", "PREVIEW_PICTURE", "NAME", "PROPERTY_URL"));
		while($vacancy = $vacancies->GetNext()) {
			if(stripos($vacancy['PROPERTY_URL_VALUE'], "http://") === false && stripos($vacancy['PROPERTY_URL_VALUE'], "https://") === false)
				$url = "http://".$vacancy['PROPERTY_URL_VALUE'];
			else
				$url = $vacancy['PROPERTY_URL_VALUE'];
			$arResult["VACANCIES"][] = array(
				"NAME" => $vacancy["NAME"],
				"PREVIEW_PICTURE" =>  CFile::ResizeImageGet(
					$vacancy['PREVIEW_PICTURE'], 
					array('width' => 278, 'height' => 158), 
					BX_RESIZE_IMAGE_PROPORTIONAL, 
					true
				),
				"URL" => $url
			);			
		}
		
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array("career" => $arResult));
		}
	}	
	
	$this->IncludeComponentTemplate();
}
?>