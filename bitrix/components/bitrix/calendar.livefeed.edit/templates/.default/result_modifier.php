<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/********************************************************************
				Input params
 ********************************************************************/
/***************** BASE ********************************************/
$arParams["BUTTONS"] = is_array($arParams["BUTTONS"]) ? $arParams["BUTTONS"] : array('UploadFile', 'CreateLink', 'InputVideo');
$arParams["BUTTONS"] = array_values($arParams["BUTTONS"]);
$arParams["BUTTONS_HTML"] = is_array($arParams["BUTTONS_HTML"]) ? $arParams["BUTTONS_HTML"] : array();

$arParams["TEXT"] = (is_array($arParams["~TEXT"]) ? $arParams["~TEXT"] : array());
$arParams["TEXT"]["ID"] = (!empty($arParams["TEXT"]["ID"]) ? $arParams["TEXT"]["ID"] : "POST_MESSAGE");
$arParams["TEXT"]["NAME"] = (!empty($arParams["TEXT"]["NAME"]) ? $arParams["TEXT"]["NAME"] : "POST_MESSAGE");
$arParams["TEXT"]["TABINDEX"] = intval($arParams["TEXT"]["TABINDEX"] <= 0 ? 10 : $arParams["TEXT"]["TABINDEX"]);
$arParams["TEXT"]["~SHOW"] = $arParams["TEXT"]["SHOW"];
$userOption = CUserOptions::GetOption("main.post.form", "postEdit");
if(isset($userOption["showBBCode"]) && $userOption["showBBCode"] == "Y")
	$arParams["TEXT"]["SHOW"] = "Y";

$arParams["ADDITIONAL"] = (is_array($arParams["~ADDITIONAL"]) ? $arParams["~ADDITIONAL"] : array());
$addSpan = true;
if (!empty($arParams["ADDITIONAL"]))
{
	$res = reset($arParams["ADDITIONAL"]);
	$res = trim($res);
	$addSpan = (substr($res, 0, 1) == "<");
}

$arParams["ADDITIONAL_TYPE"] = ($addSpan ? "html" : "popup");

$arParams["UPLOAD_WEBDAV_ELEMENT"] = (is_array($arParams["UPLOAD_WEBDAV_ELEMENT"]) ? $arParams["UPLOAD_WEBDAV_ELEMENT"] : array());
$arParams["UPLOAD_WEBDAV_ELEMENT_HTML"] = "";
$arParams["UPLOAD_WEBDAV_ELEMENT_CID"] = "";

$arParams["FILES"] = (is_array($arParams["FILES"]) ? $arParams["FILES"] : array());
$arParams["FILES"]["VALUE"] = (is_array($arParams["FILES"]["VALUE"]) ? $arParams["FILES"]["VALUE"] : array());
$arParams["FILES"]["SHOW"] = (empty($arParams["FILES"]["VALUE"]) ? "N" : $arParams["FILES"]["SHOW"]);
$arParams["FILES"]["POSTFIX"] = trim($arParams["FILES"]["POSTFIX"]);
$arParams["FILES"]["VALUE_JS"] = array();
$arParams["FILES"]["VALUE_HTML"] = array();
$arParams["FILES"]["DEL_LINK"] = trim($arParams["FILES"]["DEL_LINK"]);

?>