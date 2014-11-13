<?
	if($arParams['ADD_SECTIONS_CHAIN'] && !empty($arResult['NAME'])) {
		$arResult['SECTION']['PATH'][] = array(
			'NAME' => $arResult['NAME'],
			'PATH' => ''
		);
	}
	if(!empty($arResult["PROPERTIES"]["IMAGES"]["VALUE"])) {
		foreach($arResult["PROPERTIES"]["IMAGES"]["VALUE"] as $key => $photo) {
			$photos["IMAGE"] = CFile::ResizeImageGet( 
				$photo, 
				array("width" => 520, "height" => 254), 
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true 
			);
			$photos["NATURE"] = CFile::GetPath($photo);
			$photos["DESCRIPTION"] = $arResult["PROPERTIES"]["IMAGES"]["DESCRIPTION"][$key];
			$arResult["PHOTOS"][] = $photos;
		}
	}
        
      //  print "<pre style='display:none'>";       print_R($arResult["ACTIVE_TO"]);    print "</pre>";
	$arResult["TIME_LEFT"] = downcounter($arResult["ACTIVE_TO"]);
?>