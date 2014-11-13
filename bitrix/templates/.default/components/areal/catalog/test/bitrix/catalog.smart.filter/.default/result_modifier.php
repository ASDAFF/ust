<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	if(!empty($arParams["IBLOCK_ID"])) {
		if(isset($arParams["SECTION_ID"]))
			$filter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $arParams["SECTION_ID"]);
		if(isset($arParams["SECTION_CODE"]))
			$filter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "CODE" => $arParams["SECTION_CODE"]);
		$sections_selected = CIBlockSection::GetList(array("SORT" => "ASC"), $filter, false);
		if($section_selected = $sections_selected->GetNext()) {
			if($section_selected["DEPTH_LEVEL"] == 1)		
				$arResult["SELECTED_TYPE"] = $section_selected["ID"];
			elseif($section_selected["DEPTH_LEVEL"] == 2) {
				$arResult["SELECTED_TYPE"] = $section_selected["IBLOCK_SECTION_ID"];
				$arResult["SELECTED_VIEW"] = $section_selected["ID"];
			}
		}
		/*Все разделы для селектов*/
		$sections = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "<=DEPTH_LEVEL" => 2), false);
		while($section = $sections->GetNext()) {
			if($section["DEPTH_LEVEL"] == 1)
				$arResult["TYPE"][] = array(
					"ID" => $section["ID"],
					"NAME" => $section["NAME"],
					"CODE" => $section["CODE"],
					"SECTION_PAGE_URL" => $section["SECTION_PAGE_URL"]
				);
			elseif($section["DEPTH_LEVEL"] == 2) 
				$arResult["VIEW"][] = array(
					"ID" => $section["ID"],
					"NAME" => $section["NAME"],
					"CODE" => $section["CODE"],
					"SECTION_PAGE_URL" => $section["SECTION_PAGE_URL"],
					"IBLOCK_SECTION_ID" => $section["IBLOCK_SECTION_ID"]
				);
		}
	}
?>