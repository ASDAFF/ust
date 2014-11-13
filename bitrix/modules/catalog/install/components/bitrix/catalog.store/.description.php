<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("CP_CATALOG_STORE_CS_NAME"),
	"DESCRIPTION" => GetMessage("CP_CATALOG_STORE_CS_DESCRIPTION"),
	"ICON" => "/images/cat_all.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 1000,
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "catalog",
			"NAME" => GetMessage("CP_CHILD_CATALOG"),
			"SORT" => 30,
			"CHILD" => array(
				"ID" => "catalog_store",
			),
		)
	),
	"COMPLEX" => "Y"
);
?>