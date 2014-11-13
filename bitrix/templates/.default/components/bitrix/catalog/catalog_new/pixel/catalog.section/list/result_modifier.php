<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(!empty($arResult["ITEMS"])) {
	foreach($arResult["ITEMS"] as $key => $arItem) {
		unset($serieses);
		unset($series);
		unset($sections);
		unset($section);
		if(!empty($arItem["PICTURE_ID"])) {
			$arResult["ITEMS"][$key]["PICTURE"] = CFile::ResizeImageGet( 
				$arItem["PICTURE_ID"], 
				array("width" => 100, "height" => 50), 
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true 
			);
		}
		else {
			if(!empty($arItem["PROPERTIES"]["SERIES"]["VALUE"])) {
				$serieses = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arItem["PROPERTIES"]["SERIES"]["LINK_IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $arItem["PROPERTIES"]["SERIES"]["VALUE"]), false, false, array("ID", "PREVIEW_PICTURE"));
				if($series = $serieses->GetNext()) {
					if(!empty($series["PREVIEW_PICTURE"])) {
						$arResult["ITEMS"][$key]["PICTURE"] = CFile::ResizeImageGet( 
							$series["PREVIEW_PICTURE"], 
							array("width" => 100, "height" => 50), 
							BX_RESIZE_IMAGE_PROPORTIONAL,
							true 
						);
					}
				}
			}
			elseif(!empty($arItem["IBLOCK_SECTION_ID"])) {
				$sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arItem["IBLOCK_ID"], "ID" => $arItem["IBLOCK_SECTION_ID"]), false);
				if($section = $sections->GetNext())
					$arResult["ITEMS"][$key]["PICTURE"] = CFile::ResizeImageGet( 
						$section["PICTURE"], 
						array("width" => 100, "height" => 50), 
						BX_RESIZE_IMAGE_PROPORTIONAL,
						true 
					);
			}
		}
		if(!empty($arItem["PROPERTIES"]["BRAND"]["VALUE"])) {
			$brands = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arItem["PROPERTIES"]["BRAND"]["LINK_IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $arItem["PROPERTIES"]["BRAND"]["VALUE"]), false, false, array("ID", "NAME"));
			if($brand = $brands->GetNext()) {
				$arResult["ITEMS"][$key]["BRAND"] = $brand["NAME"];
			}
		}		
		
		if(!empty($arItem["PROPERTIES"]["GROUP_PAGE"]["VALUE"])) {
			if(!empty($arItem["PROPERTIES"]["DISPLAY_AS_SERIES"]["VALUE"]))
				$arResult["ITEMS"][$key]["DETAIL_PAGE_URL"] = $arResult["SECTION_PAGE_URL"].$arItem["PROPERTIES"]["GROUP_PAGE"]["VALUE"]."/";
		}
	}
}
?>