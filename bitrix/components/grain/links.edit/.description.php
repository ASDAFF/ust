<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_DESC_NAME"),
	"DESCRIPTION" => GetMessage("GRAIN_LINKS_EDIT_COMPONENT_SELECT_DESC_DESC"),
	"ICON" => "/images/suggest.gif",
	"SORT" => 20,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "utility",
	),
);

?>