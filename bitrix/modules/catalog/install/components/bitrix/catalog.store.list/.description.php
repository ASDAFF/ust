<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("CP_CATALOG_STORE_CSL_NAME"),
	"DESCRIPTION" => GetMessage("CP_CATALOG_STORE_CSL_DESCRIPTION"),
	"ICON" => "/images/store_list.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 1010,
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "catalog",
			"NAME" => GetMessage("CP_CHILD_CATALOG"),
			"SORT" => 30,
		)
	),
);
?>