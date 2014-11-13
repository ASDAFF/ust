<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(
	!is_array($arParams["SETTINGS"]) 
	|| !is_array($arParams["SETTINGS"]["COLUMNS"]) 
	|| (is_array($arParams["SETTINGS"]["COLUMNS"]) && count($arParams["SETTINGS"]["COLUMNS"])<=0)
) {

	echo GetMessage("GRAIN_TABLES_TE_COMPONENT_ERROR_NO_COLUMNS");
	return;

}

$this->IncludeComponentTemplate();
?>