<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(!empty($arResult["ITEMS"]))
	foreach($arResult["ITEMS"] as $key => $arItem) {
		if(!empty($arItem["IBLOCK_SECTION_ID"]) && !in_array($arItem["IBLOCK_SECTION_ID"], $sections_id)) {
			$sections_id[] = $arItem["IBLOCK_SECTION_ID"];
		}
		if(!empty($arItem["PROPERTIES"]["SERIES"]["VALUE"]))
		$arResult["ARENDA"][$arItem["IBLOCK_SECTION_ID"]][$arItem["PROPERTIES"]["SERIES"]["VALUE"]][] = $arItem;
	}

	if(!empty($sections_id)) {
		$sections = CIBlockSection::GetList(array("SORT" => "ASC", "ID" => "ASC"), array("IBLOCK_ID" => $arItem["IBLOCK_ID"], "ID" => $sections_id), false);
		while($section = $sections->GetNext())
			$arResult["SECTIONS"][$section["ID"]] = array(
				"NAME" => $section["NAME"],
				"PICTURE" => CFile::ResizeImageGet( 
					$section["PICTURE"], 
					array("width" => 201, "height" => 105), 
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true 
				)
			);
	}
	//добавить это в кеш
	$this->__component->SetResultCacheKeys(array("SECTIONS", "ARENDA"));
?>