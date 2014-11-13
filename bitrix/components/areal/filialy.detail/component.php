<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	
	if(!isset($arParams["CACHE_TIME"]))
		$arParams["CACHE_TIME"] = 36000000;
	
	$arSelect = array("ID", "NAME", "PREVIEW_TEXT",	"DETAIL_TEXT", "PREVIEW_TEXT_TYPE", "DETAIL_TEXT_TYPE", "PROPERTY_TYPE", "PROPERTY_TOWN", "PROPERTY_TOWN.NAME", "PROPERTY_ADDRESS",	"PROPERTY_PHONE", "PROPERTY_WORK_SHEDULE_DETAIL", "PROPERTY_EMAIL", "PROPERTY_WEB_SITE", "PROPERTY_SERVISES", "PROPERTY_HOW_TO_GET", "PROPERTY_MAP", "PROPERTY_PHOTO",	"PROPERTY_CONSULTANT", "PROPERTY_CONSULTANT_PHONE",	"PROPERTY_CONSULTANT_EMAIL", "PROPERTY_SHORT_DETAIL_LINK");
	
	if(!empty($arParams["ID"]) && $arParams["ID"] > 0)
		$arFilter = array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y", "ID" => $arParams["ID"]);
	elseif(!empty($arParams["NAME"]) && strlen($arParams["NAME"]) > 0)
		$arFilter = array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y", "NAME" => $arParams["NAME"]);
	
	if ($this->StartResultCache(false, array($arFilter, $arSelect)))
	{	
		//ищем подходящий по фильтру город
		$res = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "NAME", "CODE"));
		if($town = $res->GetNext()) {
			$town_id = $town["ID"];
			$town_link = "/filialy/".$town["CODE"]."/";
		}
		
		$res = CIBlockElement::GetList(array("PROPERTY_TOWN.SORT" => "ASC", "PROPERTY_TOWN.NAME" => "ASC"), array("IBLOCK_ID" => FILIALS, "ACTIVE" => "Y", "PROPERTY_TYPE" => $arParams["TYPE"], "PROPERTY_TOWN" => $town_id), false, array(), $arSelect);
		if($element = $res->GetNext()) {
			$arEmail = array();
			if(!empty($element["PROPERTY_EMAIL_VALUE"])) {
				$arEmail = explode("@", $element["PROPERTY_EMAIL_VALUE"]);
			}
			if(!empty($element["PROPERTY_SERVISES_VALUE"])) {
				unset($arService);
				unset($services);
				unset($service);
				$services = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => ICONS_ABOUT_COMPANY, "ACTIVE" => "Y", "ID" => $element["PROPERTY_SERVISES_VALUE"]), false, false, array("NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_URL"));
				while($service = $services->GetNext()) {
					$arService[] = array(
						"NAME" => $service["NAME"],
						"PICTURE" => CFile::ResizeImageGet( 
							$service["PREVIEW_PICTURE"], 
							array("width" => 30, "height" => 30), 
							BX_RESIZE_IMAGE_PROPORTIONAL,
							true 
						),
						"DETAIL" => CFile::ResizeImageGet( 
							$service["DETAIL_PICTURE"], 
							array("width" => 30, "height" => 30), 
							BX_RESIZE_IMAGE_PROPORTIONAL,
							true 
						),
						"URL" => $service["PROPERTY_URL_VALUE"]
					);
				}
			}
			if(!$element["PROPERTY_MAP_VALUE"]) {
				$full_address = $element["PROPERTY_TOWN_NAME"]." ".$element["~PROPERTY_ADDRESS_VALUE"];
				$content = json_decode(file_get_contents("http://geocode-maps.yandex.ru/1.x/?format=json&geocode=".$full_address."&results=1"));
				$point = explode(" ", $content->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);
			}
			else {
				$point_array = explode(",", $element["PROPERTY_MAP_VALUE"]);
				$point = array($point_array[1], $point_array[0]);
			}
			
			unset($photos);
			if(!empty($element["PROPERTY_PHOTO_VALUE"])) {
				$photos = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => FILIALS_PHOTO, "ACTIVE" => "Y", "ID" => $element["PROPERTY_PHOTO_VALUE"]), false, false, array("ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_SHOW_BIG"));
				while($photo = $photos->GetNext()) {					
					$PICTURES[] = array(
						"NAME" => $photo["NAME"],
						"SHOW_BIG" => $photo["PROPERTY_SHOW_BIG_VALUE"] ? 1 : 0,
						"NATURE" => CFile::GetPath($photo["PREVIEW_PICTURE"]),
						"STANDART" => CFile::ResizeImageGet( 
							$photo["PREVIEW_PICTURE"], 
							array("width" => 465, "height" => 169), 
							BX_RESIZE_IMAGE_EXACT,
							true 
						),
						"SMALL" => CFile::ResizeImageGet( 
							$photo["PREVIEW_PICTURE"], 
							array("width" => 153, "height" => 124), 
							BX_RESIZE_IMAGE_EXACT,
							true 
						)
					);
				}
			}
			
			unset($consultant);
			if(!empty($element["PROPERTY_CONSULTANT_VALUE"]) || !empty($element["PROPERTY_CONSULTANT_PHONE_VALUE"]) || !empty($element["PROPERTY_CONSULTANT_EMAIL_VALUE"])) {
				$consultant = array(
					"NAME" => $element["PROPERTY_CONSULTANT_VALUE"],
					"PHONE" => $element["PROPERTY_CONSULTANT_PHONE_VALUE"],
					"EMAIL" => $element["PROPERTY_CONSULTANT_EMAIL_VALUE"],
				);
			}
			$arResult["FILIAL"] = array(
				"NAME" => $element["NAME"],
				"ID" => $element["ID"],
				"LINK" => $town_link,
				"ADDRESS" => $element["~PROPERTY_ADDRESS_VALUE"],
				"PHONE" => $element["~PROPERTY_PHONE_VALUE"],
				"WORK_SHEDULE" => $element["~PROPERTY_WORK_SHEDULE_DETAIL_VALUE"],
				"HOW_TO_GET" => $element["~PROPERTY_HOW_TO_GET_VALUE"],
				"PREVIEW_TEXT" => $element["PREVIEW_TEXT"],
				"PREVIEW_TEXT_TYPE" => $element["PREVIEW_TEXT_TYPE"],
				"SHORT_DETAIL_LINK" => $element["PROPERTY_SHORT_DETAIL_LINK_VALUE"],
				"DETAIL_TEXT" => $element["DETAIL_TEXT"],
				"DETAIL_TEXT_TYPE" => $element["DETAIL_TEXT_TYPE"],
				"EMAIL" => $arEmail,
				"SERVICES" => $arService,
				"POINT_ON_MAP" => $point,
				"PHOTO" => $PICTURES,
				"CONSULTANT" => $consultant
			);
		}
		$this->SetResultCacheKeys(array_keys($arResult));
		$this->IncludeComponentTemplate();		
	}	
	
	$APPLICATION->SetTitle($arResult["FILIAL"]["NAME"]);
	$APPLICATION->AddChainItem($arResult["FILIAL"]["NAME"], $arResult["FILIAL"]["LINK"]);
}
?>