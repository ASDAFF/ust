<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_mode=CModule::IncludeModuleEx("grain.links");

if($module_mode==MODULE_NOT_FOUND) {
	ShowError(GetMessage("GRAIN_LINKS_EDIT_COMPONENT_ERROR_M_NOT_FOUND"));
	return;
}

if($module_mode==MODULE_DEMO_EXPIRED) {
	ShowError(GetMessage("GRAIN_LINKS_EDIT_COMPONENT_ERROR_M_EXPIRED"));
	return;
}

$arParams["DATA_SOURCE"] = strtolower($arParams["DATA_SOURCE"]);

$arParams["NAME_TRUNCATE_LEN"] = intval($arParams["NAME_TRUNCATE_LEN"]);

$arParams["MULTIPLE"] = $arParams["MULTIPLE"] == "Y";
$arParams["INPUT_NAME"] = strlen($arParams["INPUT_NAME"])>0?$arParams["INPUT_NAME"]:"";

$arParams["USE_AJAX"] = ($arParams["USE_AJAX"]=="Y" && !in_array($arParams["DATA_SOURCE"],Array("array_simple","array_extended","array_bitrix","html_select")));
$arParams["SHOW_URL"] = ($arParams["SHOW_URL"]=="Y" && !in_array($arParams["DATA_SOURCE"],Array("array_simple","array_bitrix","html_select")));

$arParams["USE_SEARCH"] = $arParams["USE_SEARCH"]=="Y";
$arParams["USE_SEARCH_COUNT"] = intval($arParams["USE_SEARCH_COUNT"]);
$arParams["EMPTY_SHOW_ALL"] = $arParams["EMPTY_SHOW_ALL"]=="Y";

$arParams["SCRIPTS_ONLY"] = $arParams["SCRIPTS_ONLY"]=="Y";

$arParams["ADMIN_SECTION"] = $arParams["ADMIN_SECTION"]=="Y";

$arParams["LEAVE_EMPTY_INPUTS"] = $arParams["LEAVE_EMPTY_INPUTS"]=="Y";

$arParams["USE_VALUE_ID"] = $arParams["USE_VALUE_ID"]=="Y";

if($arParams["MULTIPLE"] && !is_array($arParams["VALUE"])) $arParams["VALUE"] = Array();

if(
	($arParams["CACHE_TYPE"] != "A" && $arParams["CACHE_TYPE"] != "Y") // cache is off by default
	|| ($arParams["CACHE_TYPE"] == "A" && COption::GetOptionString("main", "component_cache_on", "Y") == "N")
)
	$arParams["CACHE_TIME"] = 0;
else
	$arParams["CACHE_TIME"] = intval($arParams["CACHE_TIME"]);


/* Creating list data array */

$arResult = Array();

$arInstanceID = $arParams;
unset($arInstanceID["VALUE"],$arInstanceID["~VALUE"]);
$arResult["INSTANCE_IDENTIFIER"] = md5(serialize($arInstanceID));

$arResult["AJAX_SEARCH_QUERY"] = $_REQUEST["ajax_search_".$arResult["INSTANCE_IDENTIFIER"]];
$arResult["AJAX_RETURN"] = ($arParams["USE_AJAX"] && strlen($arResult["AJAX_SEARCH_QUERY"])>0)?true:false;
if($arResult["AJAX_RETURN"]) {
	$arResult["AJAX_SEARCH_QUERY"]=$APPLICATION->UnJSEscape($arResult["AJAX_SEARCH_QUERY"]);
	$arParams["CACHE_TIME"] = 0;
}

$arResult["DATA"] = Array();

if(in_array($arParams["DATA_SOURCE"],Array("array_simple","array_extended","array_bitrix","html_select"))) {

	$arResult["DATA"] = CGrain_LinksTools::ParseDataArray($arParams["DATA_SOURCE"],$arParams["~DATA"],$arResult["DATA"]);

} elseif(strlen($arParams["DATA_SOURCE"])>0 && (!$arParams["USE_AJAX"] || ($arParams["USE_AJAX"] && $arResult["AJAX_RETURN"]))) {

	$obCache = new CPHPCache;

	if($obCache->StartDataCache($arParams["CACHE_TIME"], $arResult["INSTANCE_IDENTIFIER"], $componentPath))
	{
	
		if($CACHE_TIME && defined('BX_COMP_MANAGED_CACHE')) { 
			$GLOBALS['CACHE_MANAGER']->StartTagCache($componentPath); 
		}

		if(is_file($_SERVER["DOCUMENT_ROOT"].GRAIN_LINKS_USER_LIST_HANDLERS."/".$arParams["DATA_SOURCE"]."/list.php"))	{
			__IncludeLang($_SERVER["DOCUMENT_ROOT"].GRAIN_LINKS_USER_LIST_HANDLERS."/".$arParams["DATA_SOURCE"]."/lang/".LANGUAGE_ID."/list.php");
			require($_SERVER["DOCUMENT_ROOT"].GRAIN_LINKS_USER_LIST_HANDLERS."/".$arParams["DATA_SOURCE"]."/list.php");
		} elseif(is_file($_SERVER["DOCUMENT_ROOT"].GRAIN_LINKS_SYSTEM_LIST_HANDLERS."/".$arParams["DATA_SOURCE"]."/list.php")) {
			__IncludeLang($_SERVER["DOCUMENT_ROOT"].GRAIN_LINKS_SYSTEM_LIST_HANDLERS."/".$arParams["DATA_SOURCE"]."/lang/".LANGUAGE_ID."/list.php");
			require($_SERVER["DOCUMENT_ROOT"].GRAIN_LINKS_SYSTEM_LIST_HANDLERS."/".$arParams["DATA_SOURCE"]."/list.php");
		}

		if($CACHE_TIME && defined('BX_COMP_MANAGED_CACHE')) { 
			$GLOBALS['CACHE_MANAGER']->EndTagCache(); 
		} 
	
		$obCache->EndDataCache($arResult["DATA"]);
	}
	else
	{
		//echo "<span style='color:red'>CACHE_TIME=".$arParams["CACHE_TIME"].",INSTANCE_ID=".$arResult["INSTANCE_IDENTIFIER"]."</span>";
		$arResult["DATA"] = $obCache->GetVars();
	}

}


if(is_array($arResult["DATA"]) && !$arParams["USE_AJAX"] && $arParams["USE_SEARCH"] && $arParams["USE_SEARCH_COUNT"]>0) 
	if(count($arResult["DATA"])<$arParams["USE_SEARCH_COUNT"]) $arParams["USE_SEARCH"] = false;
	
$arResult["USE_SEARCH"] = $arParams["USE_SEARCH"];

if(is_array($arResult["DATA"]) && $arParams["NAME_TRUNCATE_LEN"]>0) {

	foreach($arResult["DATA"] as $key => $arItem) {

		$end_pos = $arParams["NAME_TRUNCATE_LEN"];
		while(substr($arItem["NAME"],$end_pos,1)!=" " && $end_pos<strlen($arItem["NAME"]))
			$end_pos++;
		if($end_pos<strlen($arItem["NAME"]))
			$arResult["DATA"][$key]["NAME"] = substr($arItem["NAME"], 0, $end_pos)."...";

	}

}


if($arParams["USE_AJAX"] && !$arResult["AJAX_RETURN"]) {

	if($arParams["VALUE"]) {

		$arResult["DATA"] = CGrain_LinksTools::GetSelected($arParams,$arParams["VALUE"],true);
		
	}

}


$arResult["SELECTED"] = Array();
$arResult["JS_SELECTED"] = Array();

if(is_array($arResult["DATA"]) && $arParams["VALUE"]) {

	if($arParams["MULTIPLE"]) {
		if(is_array($arParams["VALUE"])) foreach($arParams["VALUE"] as $value_id=>$value) {
			if(!array_key_exists($value,$arResult["DATA"]))
				continue;
			$arItem=$arResult["DATA"][$value];
	    	if($arParams["USE_VALUE_ID"])
   				$arItem["VALUE_ID"]=$value_id;
	    	$arResult["SELECTED"][$value] = $arItem;
	    	$arResult["JS_SELECTED"][$value] = true;
	    }
	} else {
	    if(array_key_exists($arParams["VALUE"],$arResult["DATA"])) {
	    	$arResult["SELECTED"]=Array($arParams["VALUE"] => $arResult["DATA"][$arParams["VALUE"]]);
	    	$arResult["JS_SELECTED"]=Array($arParams["VALUE"] => true);
	    }
	}

}

$arResult["JS_SELECTED"] = CUtil::PhpToJsObject($arResult["JS_SELECTED"]);

$arResult["JS_DATA"] = Array();
foreach($arResult["DATA"] as $k=>$v)
	$arResult["JS_DATA"]["v".$k] = $v;
$arResult["JS_DATA"] = CUtil::PhpToJsObject($arResult["JS_DATA"]);

if($arParams["USE_AJAX"]) IncludeAJAX();

$this->IncludeComponentTemplate();

return $arResult["INSTANCE_IDENTIFIER"];

?>