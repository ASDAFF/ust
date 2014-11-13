<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	if(!empty($arResult["ITEMS"])) {
		foreach($arResult["ITEMS"] as $key => $arItem) {
			$arResult["ITEMS"][$key]["PREVIEW_PICTURE_RESIZE"] = CFile::ResizeImageGet( 
				$arItem["PREVIEW_PICTURE"], 
				array("width" => 184, "height" => 143), 
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true 
			);
			if(!empty($arItem["DATE_CREATE"])) 
				$arResult["ITEMS"][$key]["DATE_CREATE_STRING"] = CIBlockFormatProperties::DateFormat("j F Y", MakeTimeStamp($arItem["DATE_CREATE"], CSite::GetDateFormat()));
		}
	}
	
?>