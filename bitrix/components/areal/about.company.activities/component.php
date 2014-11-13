<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$elements = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => ICONS_ABOUT_COMPANY, "ACTIVE" => "Y"), false, false, array("NAME", "PREVIEW_PICTURE", "PROPERTY_URL"));
	while($element = $elements->GetNext())
		$arResult["SECTIONS"][] = array(
			"NAME" => $element["NAME"],
			"PICTURE" => CFile::ResizeImageGet( 
				$element["PREVIEW_PICTURE"], 
				array("width" => 30, "height" => 30), 
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true 
			),
			"URL" => $element["PROPERTY_URL_VALUE"]
		);

	$this->IncludeComponentTemplate();
}
?>