<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("grain.links"))
	return;

$arDataSources = CGrain_LinksAdminTools::GetDataSourceList();

$arComponentParameters = array(
	"GROUPS" => array(
		"SEARCH" => array(
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_GROUP_SEARCH"),
		),
	),
	"PARAMETERS" => array(
		"DATA_SOURCE" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_DATA_SOURCE"),
			"TYPE" => "LIST",
			"VALUES" => $arDataSources,
			"DEFAULT" => "array_simple",
			"REFRESH" => "Y",
		),
		"INPUT_NAME" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_INPUT_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"MULTIPLE" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_MULTIPLE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
		"USE_SEARCH" => Array(
			"PARENT" => "SEARCH",
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_USE_SEARCH"),
	    	"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"USE_SEARCH_COUNT" => Array(
			"PARENT" => "SEARCH",
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_USE_SEARCH_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"EMPTY_SHOW_ALL" => Array(
			"PARENT" => "SEARCH",
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_EMPTY_SHOW_ALL"),
	    	"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"NAME_TRUNCATE_LEN" => Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_NAME_TRUNCATE_LEN"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"USE_AJAX" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_USE_AJAX"),
			"TYPE" => "CHECKBOX",
		    "DEFAULT" => "N",
			"REFRESH" => "Y",
		),
		"SHOW_URL" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_SHOW_URL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SCRIPTS_ONLY" => Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_SCRIPTS_ONLY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"ON_AFTER_SELECT" => Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_ON_AFTER_SELECT"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"ON_AFTER_REMOVE" => Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_ON_AFTER_REMOVE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
	),
);


if(in_array($arCurrentValues["DATA_SOURCE"],Array("array_simple","array_extended","array_bitrix","html_select")))
{

	$arComponentParameters["PARAMETERS"]["DATA"] = Array(
		"PARENT" => "DATA_SOURCE",
		"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_DATA"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);

} else {

	if($arCurrentValues["USE_AJAX"]!="Y") {
		$arComponentParameters["PARAMETERS"]["CACHE_TIME"] = Array("DEFAULT"=>0);
	}

	if(is_file($_SERVER["DOCUMENT_ROOT"].GRAIN_LINKS_USER_LIST_HANDLERS."/".$arCurrentValues["DATA_SOURCE"]."/.parameters.php")) {
	    __IncludeLang($_SERVER["DOCUMENT_ROOT"].GRAIN_LINKS_USER_LIST_HANDLERS."/".$arCurrentValues["DATA_SOURCE"]."/lang/".LANGUAGE_ID."/.parameters.php");
	    require($_SERVER["DOCUMENT_ROOT"].GRAIN_LINKS_USER_LIST_HANDLERS."/".$arCurrentValues["DATA_SOURCE"]."/.parameters.php");
	} elseif(is_file($_SERVER["DOCUMENT_ROOT"].GRAIN_LINKS_SYSTEM_LIST_HANDLERS."/".$arCurrentValues["DATA_SOURCE"]."/.parameters.php")) {
	    __IncludeLang($_SERVER["DOCUMENT_ROOT"].GRAIN_LINKS_SYSTEM_LIST_HANDLERS."/".$arCurrentValues["DATA_SOURCE"]."/lang/".LANGUAGE_ID."/.parameters.php");
	    require($_SERVER["DOCUMENT_ROOT"].GRAIN_LINKS_SYSTEM_LIST_HANDLERS."/".$arCurrentValues["DATA_SOURCE"]."/.parameters.php");
	}

}


$arComponentParameters["PARAMETERS"]["VALUE"] = Array(
	"PARENT" => "DATA_SOURCE",
	"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_PARAM_VALUE"),
	"TYPE" => "STRING",
	"DEFAULT" => "",
);
	

?>