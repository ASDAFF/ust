<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	if(empty($arParams["TYPE"]))
		$arParams["TYPE"] = "DEALERS";
		
	//извлекаем только диллеров
	$property_enums = CIBlockPropertyEnum::GetList(Array("DEF" => "DESC", "SORT" => "ASC"), Array("IBLOCK_ID" => FILIALS, "CODE" => "TYPE", "XML_ID" => $arParams["TYPE"]));
	while($enum_fields = $property_enums->GetNext())
		$type = $enum_fields["ID"];
		
	$res = CIBlockElement::GetList(array("PROPERTY_TOWN.SORT" => "ASC", "PROPERTY_TOWN.NAME" => "ASC"), array("IBLOCK_ID" => FILIALS, "ACTIVE" => "Y", "PROPERTY_TYPE" => $type), false, false, array("NAME", "SORT", "PROPERTY_TYPE", "PROPERTY_ADDRESS", "PROPERTY_PHONE", "PROPERTY_EMAIL", "PROPERTY_TOWN", "PROPERTY_TOWN.SORT", "PROPERTY_TOWN.NAME", "PROPERTY_TOWN.CODE", "PROPERTY_WEB_SITE", "PROPERTY_POINT_ON_MAP", "PROPERTY_WORK_SHEDULE"));
	while($element = $res->GetNext()) {
		$arResult["DEALERS"][$element["PROPERTY_TOWN_CODE"]][] = array(
			"ID" => $element["ID"],
			"NAME" => $element["NAME"],
			"ADDRESS" => $element["PROPERTY_ADDRESS_VALUE"],
			"PHONE" => $element["PROPERTY_PHONE_VALUE"],
			"EMAIL" => explode("@", $element["PROPERTY_EMAIL_VALUE"]),
			"WORK_SHEDULE" => $element["~PROPERTY_WORK_SHEDULE_VALUE"],
			"WEB_SITE" => $element["PROPERTY_WEB_SITE_VALUE"],
			"POINT_ON_MAP" => explode(",", $element["PROPERTY_POINT_ON_MAP_VALUE"])
		);
		$arResult["TOWNS"][$element["PROPERTY_TOWN_CODE"]]["NAME"] = $element["PROPERTY_TOWN_NAME"];
	}
	
	foreach($arResult["TOWNS"] as $key => $value)
		if($value["NAME"] == $_SESSION["SELECTED_TOWN"])
			$arResult["TOWNS"][$key]["SELECTED"] = 1;
		else
			$arResult["TOWNS"][$key]["SELECTED"] = 0;
	
	$this->IncludeComponentTemplate();
}
?>