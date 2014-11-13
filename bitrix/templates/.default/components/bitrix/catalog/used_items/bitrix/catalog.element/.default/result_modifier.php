<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();?>
<?
if($arParams['ADD_SECTIONS_CHAIN'] && !empty($arResult['NAME'])) {
	$arResult['SECTION']['PATH'][] = array(
		'NAME' => $arResult['NAME'],
		'PATH' => $arResult["DETAIL_PAGE_URL"]
	);
}
if(!empty($arResult["PROPERTIES"]["PHOTO"]["VALUE"])) {
	foreach($arResult["PROPERTIES"]["PHOTO"]["VALUE"] as $photo) {
		unset($photos);
		$photos["NATURE"] = CFile::GetPath($photo);
		$photos["STANDART"] = CFile::ResizeImageGet( 
			$photo, 
			array("width" => 388, "height" => 278), 
			BX_RESIZE_IMAGE_PROPORTIONAL,
			true 
		);
		$photos["SMALL"] = CFile::ResizeImageGet( 
			$photo, 
			array("width" => 125, "height" => 90), 
			BX_RESIZE_IMAGE_PROPORTIONAL,
			true 
		);
		$arResult["PICTURES"][] = $photos;
	}
	if(!empty($arResult["PROPERTIES"]["LOCATION"]["VALUE"])) {
		$res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y", "ID" => $arResult["PROPERTIES"]["LOCATION"]["VALUE"]), false, false, array("ID", "NAME"));
		if($element = $res->GetNext())
			$arResult["PROPERTIES"]["LOCATION"]["VALUE"] = $element["NAME"];
	}
	
	if(!empty($arResult['IBLOCK_SECTION_ID'])) {
		$res = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arResult["IBLOCK_ID"], "ID" => $arResult['IBLOCK_SECTION_ID']), false);
		if($sec = $res->GetNext())
			$arResult["IBLOCK_SECTION_NAME"] = $sec["NAME"];
	}
}

?>