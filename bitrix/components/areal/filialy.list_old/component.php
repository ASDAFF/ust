<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	
	if(!isset($arParams["CACHE_TIME"]))
		$arParams["CACHE_TIME"] = 36000000;
	
	$arSelect = array("ID", "NAME", "PREVIEW_TEXT",	"PREVIEW_TEXT_TYPE", "PROPERTY_TYPE", "PROPERTY_ADDRESS", "PROPERTY_PHONE", "PROPERTY_WORK_SHEDULE", "PROPERTY_TOWN", "PROPERTY_TOWN.CODE", "PROPERTY_TOWN.NAME", "PROPERTY_EMAIL", "PROPERTY_SERVISES");
	
	if(!empty($arParams["ID"]) && $arParams["ID"] > 0)
		$arFilter = array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y", "ID" => $arParams["ID"]);
	elseif(!empty($arParams["NAME"]) && strlen($arParams["NAME"]) > 0)
		$arFilter = array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y", "NAME" => $arParams["NAME"]);
	
	if ($this->StartResultCache(false, array($arFilter, $arSelect)))
	{
		//ищем подходящий по фильтру город
		$res = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "NAME", "CODE"));
		if($town = $res->GetNext()) {
			$arResult["ACTIVE_TOWN"] = $town["CODE"];
		}
		
		$res = CIBlockElement::GetList(array("PROPERTY_TOWN.SORT" => "ASC", "PROPERTY_TOWN.NAME" => "ASC"), array("IBLOCK_ID" => FILIALS, "ACTIVE" => "Y", "PROPERTY_TYPE" => $arParams["TYPE"]), false, false, $arSelect);
		while($element = $res->GetNext()) {
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
							array("width" => 28, "height" => 28), 
							BX_RESIZE_IMAGE_PROPORTIONAL,
							true 
						),
						"DETAIL" => CFile::ResizeImageGet( 
							$service["DETAIL_PICTURE"], 
							array("width" => 28, "height" => 28), 
							BX_RESIZE_IMAGE_PROPORTIONAL,
							true 
						),
						"URL" => $service["PROPERTY_URL_VALUE"]
					);
				}
			}
						
			$arResult["FILIAL"][] = array(
				"NAME" => $element["NAME"],
				"TOWN_NAME" => $element["PROPERTY_TOWN_NAME"],
				"TOWN_ID" => $element["PROPERTY_TOWN_CODE"],
				"LINK" => "/filialy/".$element["PROPERTY_TOWN_CODE"]."/",
				"ADDRESS" => $element["~PROPERTY_ADDRESS_VALUE"],
				"PHONE" => $element["~PROPERTY_PHONE_VALUE"],
				"WORK_SHEDULE" => $element["~PROPERTY_WORK_SHEDULE_VALUE"],
				"PREVIEW_TEXT" => $element["PREVIEW_TEXT"],
				"PREVIEW_TEXT_TYPE" => $element["PREVIEW_TEXT_TYPE"],
				"EMAIL" => $arEmail,
				"SERVICES" => $arService
			);
		}
		//$this->SetResultCacheKeys(array_keys($arResult));
		$this->IncludeComponentTemplate();		
	}
}
?>