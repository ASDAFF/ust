<?
	if($arParams['ADD_SECTIONS_CHAIN'] && !empty($arResult['NAME'])) {
		$arResult['SECTION']['PATH'][] = array(
			'NAME' => $arResult['NAME'],
			'PATH' => ''
		);
	}
	if(!empty($arResult["PROPERTIES"]["PHOTO"]["VALUE"])) {
		foreach($arResult["PROPERTIES"]["PHOTO"]["VALUE"] as $key => $photo) {
			$arResult["PHOTO"][] = array_merge(array("description" => $arResult["PROPERTIES"]["PHOTO"]["DESCRIPTION"][$key]), CFile::ResizeImageGet( 
				$photo, 
				array("width" => 520, "height" => 254), 
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true 
			));
		}
	}
?>