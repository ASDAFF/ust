<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("CP_CATALOG_STORE_CSD_NAME"),
	"DESCRIPTION" => GetMessage("CP_CATALOG_STORE_CSD_DESCRIPTION"),
	"ICON" => "/images/store_detail.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 1020,
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