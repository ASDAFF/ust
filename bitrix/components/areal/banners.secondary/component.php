<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$ar_url=explode("?",$_SERVER["REQUEST_URI"]);
	$url =$ar_url[0];
	$expl_url = explode("/", $url);
	if($expl_url[count($expl_url)-1] == "index.php") $expl_url[count($expl_url)-1] = "";
	$new_url = implode("/", $expl_url);
	$full_url = str_replace("/", "_", $new_url);
	$cache = new CPHPCache(); 
	$cache_time = 3600;
	$arResult = array();
	$cache_dir_id = 'banner'.$full_url;		
	$cache_dir_path = '/banner'.$full_url."/";
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)   ) {
	   $res = $cache->GetVars();
	   if (is_array($res['banner'.$full_url]) && (count($res['banner'.$full_url]) > 0))
		  $arResult = array_merge($arResult, $res['banner'.$full_url]);
	}
	else {
		$i=0;
		$res = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => BANNERS_SECONDARY, "ACTIVE" => "Y", "PROPERTY_URL" => $new_url), false, false, array("ID", "PREVIEW_PICTURE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "NAME", "PROPERTY_URL"));
		
		while($element = $res->GetNext()) {
			$arResult["BANNERS"][$i] = array(
				"ID" => $element["ID"],
				"NAME" => $element["NAME"],
				"PREVIEW_TEXT" => $element["PREVIEW_TEXT"],
				"PREVIEW_TEXT_TYPE" => $element["PREVIEW_TEXT_TYPE"],
				"PREVIEW_PICTURE" => CFile::ResizeImageGet( 
					$element["PREVIEW_PICTURE"], 
					array("width" => 872, "height" => 260), 
					BX_RESIZE_IMAGE_EXACT,
					true 
				),
				
			);
			$res2 = CIBlockElement::GetProperty(BANNERS_SECONDARY, $element["ID"], array(), array('CODE'=>'link') );
			while ($ob = $res2->GetNext())
			{
				$arResult["BANNERS"][$i][$ob['CODE']] =$ob['VALUE'];
			}
			$i++;
		}
		
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array('banner'.$full_url => $arResult));
		}
	}
	
	$this->IncludeComponentTemplate();
}
?>