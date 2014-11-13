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
        $PROP["EMAIL"] = $_REQUEST["EMAIL"];
        $PROP["PHONE"] = $_REQUEST["PHONE"];
        $PROP["TYPE_OF_WORK"] = $_REQUEST["TYPE"];
        $PROP["TOWN"] = $_REQUEST["TOWN"];
        $PROP["ELEMENT_NAME"] = $_REQUEST["TEHNIK_BRAND"];
        $PROP["SERIAL_NUMBER"] = $_REQUEST["SERIAL_NUMBER"];
        $PROP["MOTO"] = $_REQUEST["MOTO"];
        
        $arCallback = Array(
            "IBLOCK_ID" => SERVICE_FORM,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $_REQUEST["NAME"],
            "ACTIVE" => "Y",
            "PREVIEW_TEXT" => $_REQUEST["MESSAGE"],
            "PREVIEW_TEXT_TYPE" => "text"
        );
        if($ID = $el->Add($arCallback)) {
            if(check_email($_REQUEST["EMAIL"])) {
                $arSend = array(
                    "ID" => $ID,
                    "NAME" => $_REQUEST["NAME"],
                    "EMAIL" => $_REQUEST["EMAIL"],
                    "PHONE" => ($_REQUEST["PHONE"] ? $_REQUEST["PHONE"] : "(телефон не указан)"),
                    "THEME" => $arResult["TYPE"][$_REQUEST["TYPE"]]["NAME"],
                    "TOWN" => $_REQUEST["TOWN"]
                );
                CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 74);
            }
            $arSend = array(
                "ID" => $ID,
                "NAME" => $_REQUEST["NAME"],
                "PHONE" => $_REQUEST["PHONE"] ? $_REQUEST["PHONE"] : "Телефон не указан",
                "EMAIL" => $_REQUEST["EMAIL"] ? $_REQUEST["EMAIL"] : "Email не указан",
                "TOWN" => $_REQUEST["TOWN"] ? $_REQUEST["TOWN"] : "Город не указан",
                "THEME" => $arResult["TYPE"][$_REQUEST["TYPE"]]["NAME"],
                "TECH" => $_REQUEST["TEHNIK_BRAND"],
                "SERIAL_NUMBER" => $_REQUEST["SERIAL_NUMBER"],
                "MOTO" => $_REQUEST["MOTO"],
                "MESSAGE" => $_REQUEST["MESSAGE"],
                "URL" => "http://".$_SERVER["HTTP_HOST"].$APPLICATION->GetCurPage(false)
            );
            CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 75);
            LocalRedirect($APPLICATION->GetCurPageParam("success=Y", array(), false));
        }
        else $arResult["ERROR"] = $el->LAST_ERROR;
    }
    $this->IncludeComponentTemplate();
}
?>