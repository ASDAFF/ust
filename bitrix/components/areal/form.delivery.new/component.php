<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
    /*Сообщение об успешной отправке*/
    $messages = CIBlockElement::GetList(array(), array("IBLOCK_ID" => FORM_MESSAGES, "PROPERTY_FORM_CODE" => "delivery"), false, false, array("PROPERTY_FORM_CODE", "PROPERTY_MESSAGE"));
    if($message = $messages->GetNext())
        $arResult["SUCCESS_MESSAGE"] = $message["PROPERTY_MESSAGE_VALUE"];
            
    if ($_SERVER["REQUEST_METHOD"] == "POST" && bitrix_sessid_post() && !empty($_REQUEST["sessid"]) && !empty($_REQUEST["jssid"]) && $_REQUEST["sessid"] == $_REQUEST["jssid"]) {
        $el = new CIBlockElement;
        /*$PROP["EMAIL"] = $_REQUEST["EMAIL"];
        $PROP["PHONE"] = $_REQUEST["PHONE"];
        $PROP["TOWN"] = $_REQUEST["TOWN"];
        $PROP["ADDRESS"] = $_REQUEST["ADDRESS"];
        $PROP["MODEL"] = $_REQUEST["MODEL"];*/
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
            "IBLOCK_ID" => DELIVERY_FORM,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $_REQUEST["PROPS_NAME"],
            "ACTIVE" => "Y",
            "PREVIEW_TEXT" => $_REQUEST["PROPS_MESSAGE"],
            "PREVIEW_TEXT_TYPE" => "text" 
        );
        if($ID = $el->Add($arCallback)) {
            if(check_email($PROP["EMAIL"])) {
                $arSend = array(
                    "NAME" => $PROP["NAME"],
                    "EMAIL" => $PROP["EMAIL"],
                    "FORM_NAME" => 'Форма записи на доставку'
                );
                CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 78);
            }
            $arSend = array(
                "ID" => $ID,
                "NAME" => $PROP["NAME"],
                "EMAIL" => $PROP["EMAIL"] ? $PROP["EMAIL"] : "Email не указан",
                "PHONE" => $PROP["PHONE"] ? $PROP["PHONE"] : "Телефон не указан",
                "TOWN" => $PROP["TOWN"] ? $PROP["TOWN"] : "Город не указан",
                "ADDRESS" => $PROP["ADDRESS"] ? $PROP["ADDRESS"] : "Адрес не указан",
                "MODEL" => $PROP["MODEL"] ? $PROP["MODEL"] : "Модель не указана",
                "MESSAGE" => $PROP["MESSAGE"] ? $PROP["MESSAGE"] : "Комментарии отсутствуют",
                "FORM_NAME" => 'Форма записи на доставку'
            );
            CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 79);
            LocalRedirect($APPLICATION->GetCurPageParam("success=Y", array(), false));
        }
        else $arResult["ERROR"] = $el->LAST_ERROR;
    }
    $this->IncludeComponentTemplate();
}
?>