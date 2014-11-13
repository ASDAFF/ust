<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	if(!empty($arResult["ITEMS"])) {
		foreach($arResult["ITEMS"] as $key => $arItem) {
			$arResult["ITEMS"][$key]["PREVIEW_PICTURE_RESIZE"] = CFile::ResizeImageGet( 
				$arItem["PREVIEW_PICTURE"], 
				array("width" => 278, "height" => 139), 
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true 
			);
			$arResult["ITEMS"][$key]["TIME_LEFT"] = downcounter($arItem["ACTIVE_TO"]);
		}
	}
	
?>