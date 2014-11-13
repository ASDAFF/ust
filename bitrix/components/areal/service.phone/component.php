<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$res = CIBlockElement::GetList(array("PROPERTY_TOWN.NAME" => "ASC"), array("IBLOCK_ID" => SERVICE_PHONE, "ACTIVE" => "Y"), false, false, array("ID", "NAME", "PROPERTY_PHONE", "PROPERTY_TOWN.NAME"));
	while($element = $res->GetNext()) {
		$arResult["PHONE"][$element["PROPERTY_TOWN_NAME"]] = implode(",<br />", $element["PROPERTY_PHONE_VALUE"]);
	}
	$this->IncludeComponentTemplate();
}
?>