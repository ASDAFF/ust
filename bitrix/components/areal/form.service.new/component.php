<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
    $cache = new CPHPCache();
    $cache_time = 3600;
    $arResult = array();
    $cache_dir_id = 'form_service';
    $cache_dir_path = '/form_service/';
    if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
       $res = $cache->GetVars();
       if (is_array($res["form_service"]) && (count($res["form_service"]) > 0))
          $arResult = array_merge($arResult, $res["form_service"]);
    }
    else {
        $res = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => SERVICE_TYPE_OF_WORK, "ACTIVE" => "Y"), false, false, array("ID", "NAME"));
        while($element = $res->GetNext())
            $arResult["TYPE"][$element["ID"]] = array("NAME" => $element["NAME"]);
        /*Сообщение об успешной отправке*/
        $messages = CIBlockElement::GetList(array(), array("IBLOCK_ID" => FORM_MESSAGES, "PROPERTY_FORM_CODE" => "service"), false, false, array("PROPERTY_FORM_CODE", "PROPERTY_MESSAGE"));
        if($message = $messages->GetNext())
            $arResult["SUCCESS_MESSAGE"] = $message["PROPERTY_MESSAGE_VALUE"];
        if ($cache_time > 0) {
            $cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
            $cache->EndDataCache(array("form_service" => $arResult));
        }
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && bitrix_sessid_post() && !empty($_REQUEST["sessid"]) && !empty($_REQUEST["jssid"]) && $_REQUEST["sessid"] == $_REQUEST["jssid"]) {
        $el = new CIBlockElement;
        /*$PROP["EMAIL"] = $_REQUEST["EMAIL"];
        $PROP["PHONE"] = $_REQUEST["PHONE"];
        $PROP["TYPE_OF_WORK"] = $_REQUEST["TYPE"];
        $PROP["TOWN"] = $_REQUEST["TOWN"];
        $PROP["ELEMENT_NAME"] = $_REQUEST["TEHNIK_BRAND"];
        $PROP["SERIAL_NUMBER"] = $_REQUEST["SERIAL_NUMBER"];
        $PROP["MOTO"] = $_REQUEST["MOTO"]; */
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
        //echo '<pre>'; print_r($PROP); echo '</pre>';
        $arCallback = Array(
            "IBLOCK_ID" => SERVICE_FORM,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $_REQUEST["PROPS_NAME"],
            "ACTIVE" => "Y",
            "PREVIEW_TEXT" => $_REQUEST["PROPS_MESSAGE"],
            "PREVIEW_TEXT_TYPE" => "text"
        );
        if($ID = $el->Add($arCallback)) {
            if(check_email($PROP["EMAIL"])) {
                $arSend = array(
                    "ID" => $ID,
                    "NAME" => $PROP["NAME"],
                    "EMAIL" => $PROP["EMAIL"],
                    "PHONE" => ($PROP["PHONE"] ? $PROP["PHONE"] : "(телефон не указан)"),
                    "THEME" => $PROP["WORK_TYPE"]["NAME"],
                    "TOWN" => $PROP["TOWN"],
                    "FORM_NAME" => 'Форма записи по сервису'
                );
                CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 74);
            }
            $arSend = array(
                "ID" => $ID,
                "NAME" => $PROP["NAME"],
                "PHONE" => $PROP["PHONE"] ? $PROP["PHONE"] : "Телефон не указан",
                "EMAIL" => $PROP["EMAIL"] ? $PROP["EMAIL"] : "Email не указан",
                "TOWN" => $PROP["TOWN"] ? $PROP["TOWN"] : "Город не указан",
                "THEME" => $PROP["WORK_TYPE"]["NAME"],
                "TECH" => $PROP["TEHNIK_BRAND"],
                "SERIAL_NUMBER" => $PROP["SERIAL_NUMBER"],
                "MOTO" => $PROP["MOTO"],
                "MESSAGE" => $PROP["MESSAGE"],
                "URL" => "http://".$_SERVER["HTTP_HOST"].$APPLICATION->GetCurPage(false),
                "FORM_NAME" => 'Форма записи по сервису'
            );
            CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 75);
            LocalRedirect($APPLICATION->GetCurPageParam("success=Y", array(), false));
        }  
        else $arResult["ERROR"] = $el->LAST_ERROR;       
    } 
    $this->IncludeComponentTemplate();
}
?>