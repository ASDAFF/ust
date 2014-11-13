<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$sections = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => HISTORY, "ACTIVE" => "Y"), false);
	while($section = $sections->GetNext()) {
		unset($arPhoto);
		unset($photos);
		unset($elements);
		unset($element);
		$elements = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => HISTORY, "ACTIVE" => "Y", "SECTION_ID" => $section["ID"]), false, false, array("ID", "NAME", "PREVIEW_PICTURE", "SECTION_ID"));
		while($element = $elements->GetNext())
			$photos[] = $element;
		if(count($photos) == 2) {
			$width = 401; $height = 236;
		}
		if(count($photos) == 3) {
			$width = 259; $height = 152;
		}
		if(count($photos) >= 4) {
			$width = 193; $height = 114;
		}
		foreach($photos as $photo) {
			$arPhoto[] = array(
				"NAME" => $photo["NAME"],
				"PICTURE" => CFile::ResizeImageGet(
					$photo["PREVIEW_PICTURE"], 
					array('width' => $width, 'height' => $height), 
					BX_RESIZE_IMAGE_PROPORTIONAL, 
					true
				)
			);
		}
		$arResult["HISTORY"][] = array(
			"ID" => $section["ID"],
			"NAME" => $section["NAME"],
			"SORT" => $section["SORT"],
			"DESCRIPTION" => $section["DESCRIPTION"],
			"DESCRIPTION_TYPE" => $section["DESCRIPTION_TYPE"],
			"PHOTO" => $arPhoto
		);
	}

	$this->IncludeComponentTemplate();
}
?>