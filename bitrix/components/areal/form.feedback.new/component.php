<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
    $cache = new CPHPCache();
    $cache_time = 3600;
    $arResult = array();
    $cache_dir_id = 'form_feedback';
    $cache_dir_path = '/form_feedback/';
    if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
       $res = $cache->GetVars();
       if (is_array($res["form_feedback"]) && (count($res["form_feedback"]) > 0))
          $arResult = array_merge($arResult, $res["form_feedback"]);
    }
    else {
        $res = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => FEEDBACK_THEMES, "ACTIVE" => "Y"), false, false, array("ID", "NAME", "PROPERTY_MESSAGE"));
        while($element = $res->GetNext())
            $arResult["THEMES"][$element["ID"]] = array(
                "NAME" => $element["NAME"],
                "MESSAGE" => $element["PROPERTY_MESSAGE_VALUE"]
            );
        /*Сообщение об успешной отправке*/
        $messages = CIBlockElement::GetList(array(), array("IBLOCK_ID" => FORM_MESSAGES, "PROPERTY_FORM_CODE" => "feedback"), false, false, array("PROPERTY_FORM_CODE", "PROPERTY_MESSAGE"));
        if($message = $messages->GetNext())
            $arResult["SUCCESS_MESSAGE"] = $message["PROPERTY_MESSAGE_VALUE"];
        if ($cache_time > 0) {
            $cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
            $cache->EndDataCache(array("form_feedback" => $arResult));
        }
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && bitrix_sessid_post() && !empty($_REQUEST["sessid"]) && !empty($_REQUEST["jssid"]) && $_REQUEST["sessid"] == $_REQUEST["jssid"]) {
        $el = new CIBlockElement;
        /*$PROP["PHONE"] = $_REQUEST["PHONE"];
        $PROP["EMAIL"] = $_REQUEST["EMAIL"];
        $PROP["THEME"] = $_REQUEST["THEME"];*/
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
            if($key == "PLIST_THEME_MESSAGE"){ 
                $ar_resul = CIBlockPropertyEnum::GetByID($arProperts);                         
                $res = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID"=>"14"), false, false, array());
                while ($prop_fields = $res->GetNext())
                { 
                    if($prop_fields["NAME"] == $ar_resul["VALUE"]){
                        $res2 =  CIBlockElement::GetProperty(14, $prop_fields["ID"]);
                        if($ar_res = $res2->GetNext()){
                            $PROP["MESSAGE_FOR"] = $ar_res["VALUE"];
                        }
                    }       
                }  
            }      
        }        
        $arCallback = Array(
            "IBLOCK_ID" => FEEDBACK_CONTACTS,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $_REQUEST["PROPS_NAME"],
            "ACTIVE" => "Y",
            "PREVIEW_TEXT" => $_REQUEST["PROPS_COMMENT"],
            "PREVIEW_TEXT_TYPE" => "text"
        );
        
        if($ID = $el->Add($arCallback)) {
            if(check_email($PROP["EMAIL"])) {
                $arSend = array(
                    "NAME" => $PROP["NAME"],
                    "EMAIL" => $PROP["EMAIL"],
                    "PHONE" => $PROP["PHONE"],
                    "THEME" => $PROP["THEME_MESSAGE"]["NAME"],            
                    "MESSAGE" => $PROP["MESSAGE_FOR"],
                    "MESSAGE_USER" => $PROP["MESSAGE"],
                    "FORM_NAME" => 'Форма обратной связи'
                );
                CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 72);
            }
            $arSend = array(
                "NAME" => $PROP["NAME"],
                "EMAIL" => $PROP["EMAIL"],
                "PHONE" => $PROP["PHONE"],
                "THEME" => $PROP["THEME_MESSAGE"]["NAME"],
                "MESSAGE_USER" => $PROP["MESSAGE"],
                "FORM_NAME" => 'Форма обратной связи'
            );
            CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 73);
            LocalRedirect($APPLICATION->GetCurPageParam("success=Y", array(), false));
        }
        else $arResult["ERROR"] = $el->LAST_ERROR; 
    }
    
    $this->IncludeComponentTemplate();
}
?>