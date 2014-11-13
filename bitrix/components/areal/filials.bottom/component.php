<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$cache = new CPHPCache();
	$cache_time = 360000;
	$arResult = array();
	$cache_dir_id = 'filials';
	$cache_dir_path = '/filials/';
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
	   $res = $cache->GetVars();
	   if (is_array($res["filials"]) && (count($res["filials"]) > 0))
		  $arResult = array_merge($arResult, $res["filials"]);
	}
	else { 
            
                $res1 = CIBlockElement::GetList(array("SORT" => "ASC", "NAME" => "ASC"), array("IBLOCK_ID" => FILIALS, "ACTIVE" => "Y","PROPERTY_TYPE_VALUE"=>"Филиал"), false, false, array("ID", "NAME", "PROPERTY_TOWN"));
		
                while($element1 = $res1->GetNext()) {
			
                      $townsId[]=  $element1["PROPERTY_TOWN_VALUE"];
                }
                
                
                
             //  print "<pre style='display:none'>"; print_r($townsId); print "</pre>"; 
               
		$res = CIBlockElement::GetList(array("SORT" => "ASC", "NAME" => "ASC"), array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y"), false, false, array("ID", "NAME", "PROPERTY_SHOW_FOOTER","CODE"));
		
                while($element = $res->GetNext()) {
                    
                    if(in_array($element["ID"], $townsId))
                    {
			if($element["PROPERTY_SHOW_FOOTER_VALUE"] )
				$arResult["TOP"][] = array("ID" => $element["ID"], "NAME" => $element["NAME"],"CODE"=>$element["CODE"]);
			$arResult["BOTTOM"][] = array("ID" => $element["ID"], "NAME" => $element["NAME"],"CODE"=>$element["CODE"]);
                    }
                        
                }
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array("filials" => $arResult));
		}
	}
	
	$this->IncludeComponentTemplate();
}
?>