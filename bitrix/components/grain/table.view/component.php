<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(
	!is_array($arParams["SETTINGS"]) 
	|| !is_array($arParams["SETTINGS"]["COLUMNS"]) 
	|| (is_array($arParams["SETTINGS"]["COLUMNS"]) && count($arParams["SETTINGS"]["COLUMNS"])<=0)
) {

	return;

}

$this->IncludeComponentTemplate();
?>