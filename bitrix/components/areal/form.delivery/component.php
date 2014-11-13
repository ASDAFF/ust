<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
    /*Сообщение об успешной отправке*/
    $messages = CIBlockElement::GetList(array(), array("IBLOCK_ID" => FORM_MESSAGES, "PROPERTY_FORM_CODE" => "delivery"), false, false, array("PROPERTY_FORM_CODE", "PROPERTY_MESSAGE"));
    if($message = $messages->GetNext())
        $arResult["SUCCESS_MESSAGE"] = $message["PROPERTY_MESSAGE_VALUE"];
            
    if ($_SERVER["REQUEST_METHOD"] == "POST" && bitrix_sessid_post() && !empty($_REQUEST["sessid"]) && !empty($_REQUEST["jssid"]) && $_REQUEST["sessid"] == $_REQUEST["jssid"]) {
        $el = new CIBlockElement;
        $PROP["EMAIL"] = $_REQUEST["PHONE"];
        $PROP["PHONE"] = $_REQUEST["EMAIL"];
        $PROP["TOWN"] = $_REQUEST["TOWN"];
        $PROP["ADDRESS"] = $_REQUEST["ADDRESS"];
        $PROP["MODEL"] = $_REQUEST["MODEL"];
        
        $arCallback = Array(
            "IBLOCK_ID" => DELIVERY_FORM,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $_REQUEST["NAME"],
            "ACTIVE" => "Y",
            "PREVIEW_TEXT" => $_REQUEST["MESSAGE"],
            "PREVIEW_TEXT_TYPE" => "text" 
        );
        if($ID = $el->Add($arCallback)) {
            if(check_email($_REQUEST["EMAIL"])) {
                $arSend = array(
                    "NAME" => $_REQUEST["NAME"],
                    "EMAIL" => $_REQUEST["EMAIL"]
                );
                CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 78);
            }
            $arSend = array(
                "ID" => $ID,
                "NAME" => $_REQUEST["NAME"],
                "EMAIL" => $_REQUEST["EMAIL"] ? $_REQUEST["EMAIL"] : "Email не указан",
                "PHONE" => $_REQUEST["PHONE"] ? $_REQUEST["PHONE"] : "Телефон не указан",
                "TOWN" => $_REQUEST["TOWN"] ? $_REQUEST["TOWN"] : "Город не указан",
                "ADDRESS" => $_REQUEST["ADDRESS"] ? $_REQUEST["ADDRESS"] : "Адрес не указан",
                "MODEL" => $_REQUEST["MODEL"] ? $_REQUEST["MODEL"] : "Модель не указана",
                "MESSAGE" => $_REQUEST["MESSAGE"] ? $_REQUEST["MESSAGE"] : "Комментарии отсутствуют"
            );
            CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 79);
            LocalRedirect($APPLICATION->GetCurPageParam("success=Y", array(), false));
        }
        else $arResult["ERROR"] = $el->LAST_ERROR;
    }
    $this->IncludeComponentTemplate();
}
?>