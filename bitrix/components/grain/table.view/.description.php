<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("GRAIN_TABLES_TV_DESCRIPTION_NAME"),
	"DESCRIPTION" => GetMessage("GRAIN_TABLES_TV_DESCRIPTION_DESCRIPTION"),
	"ICON" => "/images/table_view.png",
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "content",
		"NAME" => GetMessage("MAIN_G_CONTENT"),
		"CHILD" => array(
			"ID" => "grain_tables",
			"NAME" => GetMessage("GRAIN_TABLES_TV_DESCRIPTION_MENU"),
		)
	),
);

?>