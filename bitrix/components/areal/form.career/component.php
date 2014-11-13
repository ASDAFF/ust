<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	/*Сообщение об успешной отправке*/
	$messages = CIBlockElement::GetList(array(), array("IBLOCK_ID" => FORM_MESSAGES, "PROPERTY_FORM_CODE" => "career"), false, false, array("PROPERTY_FORM_CODE", "PROPERTY_MESSAGE"));
	if($message = $messages->GetNext())
		$arResult["SUCCESS_MESSAGE"] = $message["PROPERTY_MESSAGE_VALUE"];
		
	if ($_SERVER["REQUEST_METHOD"] == "POST" && bitrix_sessid_post() && !empty($_REQUEST["sessid"]) && !empty($_REQUEST["jssid"]) && $_REQUEST["sessid"] == $_REQUEST["jssid"]) {
		$el = new CIBlockElement;
		$PROP["EMAIL"] = $_REQUEST["EMAIL"];
		$PROP["PHONE"] = $_REQUEST["PHONE"];
		$PROP["FILE"] = $_FILES["RESUME"];
		$arCallback = Array(
			"IBLOCK_ID" => CAREER_FORM,
			"PROPERTY_VALUES" => $PROP,
			"NAME" => $_REQUEST["NAME"],
			"ACTIVE" => "Y"
		);
		if($ID = $el->Add($arCallback)) {
			if(check_email($_REQUEST["EMAIL"])) {
				$arSend = array(
					"NAME" => $_REQUEST["NAME"],
					"EMAIL" => $_REQUEST["EMAIL"],
					"PHONE" => $_REQUEST["PHONE"]
				);
				CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 76);
			}
			$res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => CAREER_FORM, "ID" => $ID), false, false, array("ID", "PROPERTY_FILE"));
			if($element = $res->GetNext()) 
				if(!empty($element["PROPERTY_FILE_VALUE"]))
				$file = CFile::GetPath($element["PROPERTY_FILE_VALUE"]);
			
			$arSend = array(
				"ID" => $ID,
				"NAME" => $_REQUEST["NAME"],
				"EMAIL" => $_REQUEST["EMAIL"] ? $_REQUEST["EMAIL"] : "Email не указан",
				"PHONE" => $_REQUEST["PHONE"],
				"FILE" => (!empty($file)) ? "http://".$_SERVER["HTTP_HOST"].$file : "файл не прикреплен"
			);
			CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 77);
			LocalRedirect($APPLICATION->GetCurPageParam("success=Y", array(), false)."#form");
		}
		else $arResult["ERROR"] = $el->LAST_ERROR;
	}
	$this->IncludeComponentTemplate();
}
?>