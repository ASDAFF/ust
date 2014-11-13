<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
    $cache = new CPHPCache();
    $cache_time = 3600;
    $arResult = array();
    $cache_dir_id = 'callbackparams';
    $cache_dir_path = '/callbackparams/';
    if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
       $res = $cache->GetVars();
       if (is_array($res["callbackparams"]) && (count($res["callbackparams"]) > 0))
          $arResult = array_merge($arResult, $res["callbackparams"]);
    }
    else {
        $hints = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), array("IBLOCK_ID" => CALLBACK_HINTS, "ACTIVE" => "Y"), false, false, array("ID", "NAME"));
        while($hint = $hints->GetNext())
            $arResult["HINTS"][] = $hint["NAME"];
            
        $times = CIBlockPropertyEnum::GetList(
            array(),
            array("IBLOCK_ID" => CALLBACK, "PROPERTY_ID" => "TIME")
        );
        while($time = $times->GetNext()) {
            $arResult["TIMES"][$time["ID"]] = $time["VALUE"];
        }
        
        
        if ($cache_time > 0) {
            $cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
            $cache->EndDataCache(array("callbackparams" => $arResult));
        }
    }
    $this->IncludeComponentTemplate();
}
?>