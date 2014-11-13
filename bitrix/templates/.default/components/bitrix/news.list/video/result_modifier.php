<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(!empty($arResult["ITEMS"])) {
	foreach($arResult["ITEMS"] as $key => $arItem) {
		if(!empty($arItem["PREVIEW_PICTURE"])) {
			$arResult["ITEMS"][$key]["PREVIEW_PICTURE_RESIZE"] = CFile::ResizeImageGet( 
				$arItem["PREVIEW_PICTURE"], 
				array("width" => 233, "height" => 149), 
				BX_RESIZE_IMAGE_EXACT,
				true 
			);
		}
	}
}
?>