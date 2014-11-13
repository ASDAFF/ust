<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(
	!is_array($arParams["SETTINGS"]) 
	|| !is_array($arParams["SETTINGS"]["COLUMNS"]) 
	|| (is_array($arParams["SETTINGS"]["COLUMNS"]) && count($arParams["SETTINGS"]["COLUMNS"])<=0)
) {

	return;

}

$arResult = Array();
$arResult["COLUMNS"] = Array();

foreach($arParams["SETTINGS"]["COLUMNS"] as $arColumn) {

	$arResult["COLUMNS"][$arColumn["NAME"]] = Array(
		"TYPE" => $arColumn["TYPE"],
	);

	if($arColumn["TYPE"]=="select")	{
		$arResult["COLUMNS"][$arColumn["NAME"]]["VALUES"] = is_array($arColumn["VALUES"])?$arColumn["VALUES"]:Array();
	} elseif($arColumn["TYPE"]=="link") {
		$arResult["COLUMNS"][$arColumn["NAME"]] = $arColumn;
	}

}

$this->IncludeComponentTemplate();
?>