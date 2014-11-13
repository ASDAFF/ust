<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
if(CModule::IncludeModule("iblock")) {

	global ${$arParams["FILTER_NAME"]};

	if($arParams["AJAX"] == "Y") {		
		if(!empty($_REQUEST["View"]))
			$curr_section = $_REQUEST["View"];
		else 
			$curr_section = $_REQUEST["Type"];
		$filter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $curr_section);
	}
	else {
		if(isset($arParams["SECTION_ID"]))
			$filter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $arParams["SECTION_ID"]);
		if(isset($arParams["SECTION_CODE"]))
			$filter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "CODE" => $arParams["SECTION_CODE"]);
	}
		
	if(!empty($arParams["IBLOCK_ID"])) {
		$sections_selected = CIBlockSection::GetList(array("SORT" => "ASC"), $filter, false);
		if($section_selected = $sections_selected->GetNext()) {
			if($section_selected["DEPTH_LEVEL"] == 1)		
				$arResult["SELECTED_TYPE"] = $section_selected["ID"];
			elseif($section_selected["DEPTH_LEVEL"] == 2) {
				$arResult["SELECTED_TYPE"] = $section_selected["IBLOCK_SECTION_ID"];
				$arResult["SELECTED_VIEW"] = $section_selected["ID"];
			}
		}
		if(isset($_REQUEST["Brands"]) && !empty($_REQUEST["Brands"]) && $arParams["AJAX"] == "Y") {
			$arResult["SELECTED_BRAND"] = $_REQUEST["Brands"];
		}
		/*Все разделы для селектов*/
		$sections = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "<=DEPTH_LEVEL" => 2), false);
		while($section = $sections->GetNext()) {
			$flag = 1;
			if(!empty($arResult["SELECTED_BRAND"])) {
				$brands = CIBlockElement::GetList(array(), array("IBLOCK_ID" => BRANDES, "ACTIVE" => "Y", "CODE" => $_REQUEST["Brands"]), false, false, array("ID", "CODE"));
				while($brand = $brands->GetNext())
					$brand_id[] = $brand["ID"];
				$count = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "SECTION_ID" => $section["ID"], "INCLUDE_SUBSECTIONS" => "Y", "PROPERTY_BRAND" => $brand_id), array());
				if(!$count || $count == 0)
					$flag = 0;
			}
			if($section["DEPTH_LEVEL"] == 1) {
				$arResult["TYPE"][$section["ID"]] = array(
					"NAME" => $section["NAME"],
					"CODE" => $section["CODE"],
					"SECTION_PAGE_URL" => $section["SECTION_PAGE_URL"]
				);
			}
			elseif($section["DEPTH_LEVEL"] == 2 && $flag == 1) {
				$arResult["VIEW"][$section["ID"]] = array(
					"NAME" => $section["NAME"],
					"CODE" => $section["CODE"],
					"SECTION_PAGE_URL" => $section["SECTION_PAGE_URL"],
					"IBLOCK_SECTION_ID" => $section["IBLOCK_SECTION_ID"]
				);
			}
		}
				
		$res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "SECTION_ID" => (($arResult["SELECTED_VIEW"] > 0) ? $arResult["SELECTED_VIEW"] : $arResult["SELECTED_TYPE"]), "INCLUDE_SUBSECTIONS" => "Y"), false, false, array("ID", "PROPERTY_BRAND", "IBLOCK_SECTION_ID"));
		while($element = $res->GetNext())
			$brands_id[] = $element["PROPERTY_BRAND_VALUE"];
		/*Бренды*/
		if(!empty($brands_id)) {
			$brands = CIBlockElement::GetList(array(), array("IBLOCK_ID" => BRANDES, "ACTIVE" => "Y", "ID" => $brands_id), false, false, array("ID", "CODE", "NAME"));
			while($brand = $brands->GetNext()) {
				$arResult["BRANDS"][$brand["CODE"]] = array(
					"NAME" => $brand["NAME"],
					"ID" => $brand["ID"]
				);
			}
		}
	}
	if(isset($_REQUEST["brand"]) && !empty($_REQUEST["brand"]) && $arParams["AJAX"] != "Y") {
		$arResult["SELECTED_BRAND"] = explode(",", $_REQUEST["brand"]);		
		
		foreach($arResult["SELECTED_BRAND"] as $brand_sel)
			$brands_array[] = $arResult["BRANDS"][$brand_sel]["ID"];
		
		${$arParams["FILTER_NAME"]} = array("PROPERTY_BRAND" => $brands_array);
	}	
	$this->IncludeComponentTemplate();	
}
?>