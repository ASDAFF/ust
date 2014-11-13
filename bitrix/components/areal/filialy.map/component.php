<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	if(!isset($arParams["CACHE_TIME"]))
		$arParams["CACHE_TIME"] = 36000000;
	$arFilter = array("IBLOCK_ID" => FILIALS, "ACTIVE" => "Y", "PROPERTY_TYPE" => $arParams["TYPE"]);
	
	if ($this->StartResultCache(false, array($arFilter)))
	{
		$res = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array("ID", "NAME", "PROPERTY_TYPE", "PROPERTY_POINT_ON_MAP", "PROPERTY_TOWN", "PROPERTY_TOWN.NAME", "PROPERTY_TOWN.CODE"));
		while($element = $res->GetNext()) {
			$arResult["POINTS"][$element["PROPERTY_TOWN_CODE"]] = array(
				"NAME" => $element["PROPERTY_TOWN_NAME"],
				"POINT_ON_MAP" => explode(",", $element["PROPERTY_POINT_ON_MAP_VALUE"]),
				"LINK" => "/filialy/".$element["PROPERTY_TOWN_CODE"]."/",
				"SELECTED" => ($arParams["ID"] == $element["PROPERTY_TOWN_VALUE"]) ? 1 : 0
			);
		}
		$this->SetResultCacheKeys(array_keys($arResult));
		$this->IncludeComponentTemplate();		
	}
}
?>