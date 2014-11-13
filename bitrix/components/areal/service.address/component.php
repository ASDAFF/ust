<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$filter = array("IBLOCK_ID" => FILIALS, "ACTIVE" => "Y", "PROPERTY_TYPE" => 36);
	if(!empty($arParams["TOWN_NAME"]))
		$filter = array_merge($filter, array("PROPERTY_TOWN.NAME" => $arParams["TOWN_NAME"]."%"));		
		
	$res = CIBlockElement::GetList(array("PROPERTY_TOWN.NAME" => "ASC"), $filter, false, false, array("ID", "NAME", "PROPERTY_TOWN", "PROPERTY_TOWN.NAME", "PROPERTY_ADDRESS", "PROPERTY_PHONE", "PROPERTY_WORK_SHEDULE", "PROPERTY_EMAIL"));
	while($element = $res->GetNext()) {
		$arEmail = array();
		if(!empty($element["PROPERTY_EMAIL_VALUE"])) {
			$arEmail = explode("@", $element["PROPERTY_EMAIL_VALUE"]);
		}
		$arResult["SERVICE"][$element["PROPERTY_TOWN_NAME"]][] = array(
			"ADDRESS" => $element["PROPERTY_ADDRESS_VALUE"],
			"PHONE" => $element["~PROPERTY_PHONE_VALUE"],
			"SHEDULE_WORK" => $element["~PROPERTY_WORK_SHEDULE_VALUE"],
			"EMAIL" => $arEmail
		);
	}
		
	$this->IncludeComponentTemplate();
}
?>