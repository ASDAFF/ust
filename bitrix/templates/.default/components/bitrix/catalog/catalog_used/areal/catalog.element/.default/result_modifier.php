<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
/*Обработка фотографий*/
	$arPhoto = $arResult["ITEMS"][0]["PROPERTIES"]["PHOTOS"]["VALUE"];
		if(!empty($arPhoto)) {
			foreach($arPhoto as $photo) {
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
				unset($arResult["ITEMS"][0]["PROPERTIES"]["PHOTOS"]);
				$arResult["PICTURES"][] = $photos;
			}
		}
	unset($arResult["GROUPING_CHARS"][0]["ITEMS"]["PHOTOS"]);
?>