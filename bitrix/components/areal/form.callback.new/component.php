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
    
    /*if ($_SERVER["REQUEST_METHOD"] == "POST" && bitrix_sessid_post() && !empty($_REQUEST["sessid"]) && !empty($_REQUEST["jssid"]) && $_REQUEST["sessid"] == $_REQUEST["jssid"]) {
        $el = new CIBlockElement;
        foreach($_REQUEST as $key=>$arProperts){
            $pos = strpos($key, "PROPS_");
            if($pos !== false){
                $res = substr($key, 6);
                $PROP[$res] = $arProperts;
            }
            $pos1 = strpos($key, 'PLIST_');
            if($pos1 !== false){
                $res1 = substr($key, 6);
                $PROP[$res1]["VALUE"] = $arProperts;
                $ar_resul = CIBlockPropertyEnum::GetByID($arProperts);
                $PROP[$res1]["NAME"] = $ar_resul["VALUE"];
            }
        }
        $arCallback = Array(
            "IBLOCK_ID" => CALLBACK,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $_REQUEST["PROPS_NAME"],
            "ACTIVE" => "Y",
            "PREVIEW_TEXT" => $_REQUEST["PROPS_COMMENT"],
            "PREVIEW_TEXT_TYPE" => "text"
        );

        if($ID = $el->Add($arCallback)) {
            $arSend = array(
                "NAME" => $PROP["NAME"],
                "EMAIL" => $PROP["EMAIL"],
                "PHONE" => $PROP["PHONE"],
                "TOWN" => $PROP["TOWN"],
                "TIME" => $PROP["TIME"],
                "COMMENT" => $PROP["MESSAGE"],
                "FORM_NAME" => 'Форма заказа обратного звонка'
            );
            CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 69);
            if(!isset($_REQUEST["ajax"]))
                LocalRedirect($APPLICATION->GetCurPageParam("success=Y", array(), false));
            elseif(isset($_REQUEST["ajax"])) {
                echo json_encode($arResult);
            }
        }
        else $arResult["ERROR"] = $el->LAST_ERROR;
    }  */
    $this->IncludeComponentTemplate();
}
?>