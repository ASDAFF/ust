<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	/* Определяем что за страница: список филиалов, детальная страница филиалов, диллеры */
	$filter = array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y");
	if($arParams["TYPE"] == "FILIALS") {
		if(isset($_REQUEST["TOWN"]) && strlen($_REQUEST["TOWN"]) > 0) {
			//если сабмит формы, которая внизу
			$filter = array_merge($filter, array("NAME" => $_REQUEST["TOWN"]));			
		}
		elseif(isset($_REQUEST["city"]) && strlen($_REQUEST["city"]) > 0) {
			//если в url определяется город
			$filter = array_merge($filter, array("CODE" => $_REQUEST["city"]));			
			$componentpage = "detail_filial";
		}
		else {
			//иначе
			if(!$arParams["ID"] && !empty($_SESSION["SELECTED_TOWN"])) {
				$filter = array_merge($filter, array("NAME" => $_SESSION["SELECTED_TOWN"]));
			}
		}
	}
	else {
		$filter = array_merge($filter, array("NAME" => $_SESSION["SELECTED_TOWN"]));
	}
	$res = CIBlockElement::GetList(array(), $filter, false, false, array("ID", "NAME", "CODE"));
	if($town = $res->GetNext()) {
		$arResult["ACTIVE_TOWN_ID"] = $town["ID"];
		$arResult["ACTIVE_TOWN_NAME"] = $town["NAME"];
		$arResult["ACTIVE_TOWN_CODE"] = $town["CODE"];
	}
	
		$arSelect = array("ID", "NAME", "SORT", "PREVIEW_TEXT",	"PREVIEW_TEXT_TYPE", "PROPERTY_TYPE", "PROPERTY_SHORT_ADDRESS", "PROPERTY_ADDRESS", "PROPERTY_PHONE", "PROPERTY_WORK_SHEDULE", "PROPERTY_TOWN", "PROPERTY_TOWN.CODE", "PROPERTY_TOWN.NAME", "PROPERTY_TOWN.SORT", "PROPERTY_EMAIL", "PROPERTY_SERVISES", "PROPERTY_WEB_SITE", "PROPERTY_POINTS_ON_MAP");
		
		$property_enums = CIBlockPropertyEnum::GetList(Array("DEF" => "DESC", "SORT" => "ASC"), Array("IBLOCK_ID" => FILIALS, "CODE" => "TYPE", "XML_ID" => $arParams["TYPE"]));
		while($enum_fields = $property_enums->GetNext())
			$arParams["TYPE_ID"] = $enum_fields["ID"];
		
	if($componentpage != "detail_filial") {
		//ищем подходящий по фильтру город
		$res = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "NAME", "CODE"));
		if($town = $res->GetNext()) {
			$arResult["ACTIVE_TOWN"] = $town["CODE"];
		}
		
		$res = CIBlockElement::GetList(array("PROPERTY_TOWN.SORT" => "ASC", "PROPERTY_TOWN.NAME" => "ASC"), array("IBLOCK_ID" => FILIALS, "ACTIVE" => "Y", "PROPERTY_TYPE" => $arParams["TYPE_ID"]), false, false, $arSelect);
		while($element = $res->GetNext()) {
			/* Точки на карте */
			if(!empty($element["PROPERTY_POINTS_ON_MAP_VALUE"])) {
				$arResult["POINTS"][$element["PROPERTY_TOWN_CODE"]] = array(
					"NAME" => $element["PROPERTY_TOWN_NAME"],
					"POINT" => $element["PROPERTY_POINTS_ON_MAP_VALUE"]
				);
			}
			
			/* Дилеры */
			if($arParams["TYPE"] == "DEALERS") {
				$arResult["DEALERS"][$element["PROPERTY_TOWN_CODE"]][] = array(
					"ID" => $element["ID"],
					"TOWN_NAME" => $element["PROPERTY_TOWN_NAME"],
					"NAME" => $element["NAME"],
					"ADDRESS" => $element["PROPERTY_SHORT_ADDRESS_VALUE"] ? $element["PROPERTY_SHORT_ADDRESS_VALUE"] : $element["PROPERTY_ADDRESS_VALUE"],
					"PHONE" => $element["PROPERTY_PHONE_VALUE"],
					"EMAIL" => explode("@", $element["PROPERTY_EMAIL_VALUE"]),
					"WORK_SHEDULE" => $element["~PROPERTY_WORK_SHEDULE_VALUE"],
					"WEB_SITE" => $element["PROPERTY_WEB_SITE_VALUE"],
					"POINT_ON_MAP" => explode(",", $element["PROPERTY_POINT_ON_MAP_VALUE"])
				);
				$arResult["TOWNS"][$element["PROPERTY_TOWN_CODE"]]["NAME"] = $element["PROPERTY_TOWN_NAME"];
			}
			
			/*Филиалы*/
                       
			if($arParams["TYPE"] == "FILIALS") {
				$arEmail = array();
				unset($arService);
				unset($services);
				unset($service);
				if(!empty($element["PROPERTY_EMAIL_VALUE"])) {
					$arEmail = explode("@", $element["PROPERTY_EMAIL_VALUE"]);
				}
				if(!empty($element["PROPERTY_SERVISES_VALUE"])) {
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
							
				$arResult["FILIAL"][$element["PROPERTY_TOWN_CODE"]] = array(
					"NAME" => $element["NAME"],
					"TOWN_NAME" => $element["PROPERTY_TOWN_NAME"],
					"LINK" => "/filialy/".$element["PROPERTY_TOWN_CODE"]."/",
					"ADDRESS" => $element["~PROPERTY_SHORT_ADDRESS_VALUE"] ? $element["~PROPERTY_SHORT_ADDRESS_VALUE"] : $element["~PROPERTY_ADDRESS_VALUE"],
					"PHONE" => $element["~PROPERTY_PHONE_VALUE"],
					"WORK_SHEDULE" => $element["~PROPERTY_WORK_SHEDULE_VALUE"],
					"PREVIEW_TEXT" => $element["PREVIEW_TEXT"],
					"PREVIEW_TEXT_TYPE" => $element["PREVIEW_TEXT_TYPE"],
					"EMAIL" => $arEmail,
					"SERVICES" => $arService
				);
			}
			
		}
		$componentpage = mb_strtolower($arParams["TYPE"]);
	}
	
	$this->IncludeComponentTemplate($componentpage);
}
?>