<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
    /*Сообщение об успешной отправке*/
    $messages = CIBlockElement::GetList(array(), array("IBLOCK_ID" => FORM_MESSAGES, "PROPERTY_FORM_CODE" => "order"), false, false, array("PROPERTY_FORM_CODE", "PROPERTY_MESSAGE"));
    if($message = $messages->GetNext())
        $arResult["SUCCESS_MESSAGE"] = $message["PROPERTY_MESSAGE_VALUE"];
    if(!empty($arParams["THEME_TYPE"]))    {
        $res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => FORM_ORDER_THEME, "CODE" => $arParams["THEME_TYPE"]), false, false, array("ID", "PROPERTY_THEME", "CODE", "NAME"));
        if($element = $res->GetNext()) {
            $arResult["THEME"] = $element["PROPERTY_THEME_VALUE"];
            $arResult["SECTION"] = $element["NAME"]; 
        }
    }
      
    if (($_SERVER["REQUEST_METHOD"] == "POST" && bitrix_sessid_post() && !empty($_REQUEST["sessid"]) && !empty($_REQUEST["jssid"]) && $_REQUEST["sessid"] == $_REQUEST["jssid"]) || (isset($_REQUEST["ajax"]) && !empty($_REQUEST["sessid"]) && !empty($_REQUEST["jssid"]) && $_REQUEST["sessid"] == $_REQUEST["jssid"])) 
    {
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
        $PROP["TYPE"] = $arResult["SECTION"].": ".$_REQUEST["THEME"];
        $PROP["DESCRIPTION"] = $_REQUEST["DESCRIPTION"];
        /*$PROP["EMAIL"] = $_REQUEST["EMAIL"];
        $PROP["PHONE"] = $_REQUEST["PHONE"];
        $PROP["DESCRIPTION"] = $_REQUEST["DESCRIPTION"];
        $PROP["TOWN"] = $_REQUEST["TOWN"]; */
        $PROP["URL"] = ($_REQUEST["URL"] && isset($_REQUEST["ajax"])) ?  $_REQUEST["URL"] : "http://".$_SERVER["HTTP_HOST"].$APPLICATION->GetCurPage(false);
        $arCallback = Array(
            "IBLOCK_ID" => ORDER_FORM,
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
                    "THEME" => $arResult["SECTION"].": ".$_REQUEST["THEME"],
                    "FORM_NAME" => 'Форма заказа'
                );
                CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 70);
            }
            $arSend = array(
                "ID" => $ID,
                "NAME" => $PROP["NAME"],
                "EMAIL" => $PROP["EMAIL"],
                "DESCRIPTION" => $PROP["DESCRIPTION"],
                "PHONE" => ($PROP["PHONE"] ? $PROP["PHONE"] : "(телефон не указан)"),
                "TOWN" => $PROP["TOWN"],
                "THEME" => $arResult["SECTION"].": ".$_REQUEST["THEME"],
                "URL" => $PROP["URL"],
                "MESSAGE" => $PROP["MESSAGE"],
                "FORM_NAME" => 'Форма заказа'
            );
            CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 71);
            if(!isset($_REQUEST["ajax"]))
                LocalRedirect($APPLICATION->GetCurPageParam("success=Y", array(), false));
            elseif(isset($_REQUEST["ajax"])) {
                echo json_encode($arResult);
            }
        }
        else $arResult["ERROR"] = $el->LAST_ERROR;
    }
    if(!isset($_REQUEST["ajax"]))
        $this->IncludeComponentTemplate();
}
?>