<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {	
	
	if(empty($arParams["TYPE"]))
		$arParams["TYPE"] = "FILIALS";
	$componentpage = "list";
	if(isset($_REQUEST["TOWN"]) && strlen($_REQUEST["TOWN"]) > 0) {
		$selected_town = $_REQUEST["TOWN"];
		$res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y", "NAME" => $selected_town), false, false, array("ID", "NAME", "CODE"));
		if($town = $res->GetNext()) {
			$arParams["ID"] = $town["ID"];
			$arResult["TOWN_NAME"] = $town["NAME"];
			$componentpage = "element";
		}
	}
	elseif(isset($_REQUEST["city"]) && strlen($_REQUEST["city"]) > 0) {
		$selected_town = $_REQUEST["city"];
		$res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y", "CODE" => $selected_town), false, false, array("ID", "NAME", "CODE"));
		if($town = $res->GetNext()) {
			if(isset($_REQUEST["from_map"]) && $_REQUEST["from_map"] == "y")
				$componentpage = "list";
			else
				$componentpage = "element";
			$arParams["ID"] = $town["ID"];
			$arResult["TOWN_NAME"] = $town["NAME"];
		}
	}
	
	if($componentpage == "list") {
		if(!$arParams["ID"] && !empty($_SESSION["SELECTED_TOWN"])) {
			$arParams["NAME"] = $_SESSION["SELECTED_TOWN"];
			$arResult["TOWN_NAME"] = $_SESSION["SELECTED_TOWN"];
		}
	}
	
	//извлекаем только филиалы
	$property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID" => FILIALS, "CODE" => "TYPE", "XML_ID" => $arParams["TYPE"]));
	while($enum_fields = $property_enums->GetNext())
		$arParams["TYPE"] = $enum_fields["ID"];

	$this->IncludeComponentTemplate($componentpage);
}
?>