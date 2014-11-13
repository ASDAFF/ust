<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	
	CModule::IncludeModule("iblock");
	CModule::IncludeModule("wl.propgroup");
	
	// Подтягиваем элемент инфоблока
	$tempBlockRes = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ID" => $arParams["IBLOCK_ELEMENT"], "ACTIVE"=>"Y"));
	$tempBlock = $tempBlockRes->GetNextElement();
	$arFields = $tempBlock->GetFields();
	
	// Подтягиванием свойства элемента инфоблока
	$arOrder = array($arParams["PROPERTY_SORT_FIELD"] => $arParams["PROPERTY_SORT_ORDER"]);
	$arProps = $tempBlock->GetProperties($arOrder);
	
	// Подтягиваем группы свойств
	$arResult["GROUPS"] = Array();
	
	$arOrder = array($arParams["GROUP_SORT_FIELD"] => $arParams["GROUP_SORT_ORDER"]);
    $rsData = CPropGroup::GetList($arOrder);
    while($arRes = $rsData->Fetch())
	{
		$arSections = CPropGroup::DecodeBoundElements($arRes["BOUND_SECTIONS"]);
		if ((in_array(strval($arFields["IBLOCK_SECTION_ID"]), $arSections) || $arParams["SECTION_CONTROL"] != "Y") && ($arRes["ACTIVE"] == "Y"))
		{
			$arResult["GROUPS"][$arRes['ID']] = $arRes;
			$arResult["GROUPS"][$arRes['ID']]["BOUND_SECTIONS"] = $arSections;
			$arResult["GROUPS"][$arRes['ID']]["BOUND_PROPERTIES"] = Array();
			
			$BoundProperties = CPropGroup::DecodeBoundElements($arRes["BOUND_PROPERTIES"]);
			// echo "<pre>";
			// print_r($arProps);
			// echo "</pre>";
			foreach ($arProps as $key => $curProp)
				if (in_array($curProp["ID"], $BoundProperties))
				{
					/*echo "<pre>";
					print_r($curProp);
					echo "</pre>";*/
					if (!empty($curProp["NAME"]) && !empty($curProp["VALUE"]))
						$arResult["GROUPS"][$arRes['ID']]["BOUND_PROPERTIES"][] = $curProp;
					$arProps[$key]["USED"] = "Y";
				}
			
			// Если не привязано ни одно свойство, удаляем группу из $arResult
			if (empty($arResult["GROUPS"][$arRes['ID']]["BOUND_PROPERTIES"]))
				unset($arResult["GROUPS"][$arRes['ID']]);
		}
		else 
		{
			$BoundProperties = CPropGroup::DecodeBoundElements($arRes["BOUND_PROPERTIES"]);
			foreach ($arProps as $key => $curProp)
				if (in_array($curProp["ID"], $BoundProperties))
					$arProps[$key]["USED"] = "Y";
		}
	}
	
	// Выбираем свойства, не привязанные ни к одной из групп
	$arResult["FREE_PROPS"] = Array();
	
	foreach ($arProps as $curProp)
	{
		if (empty($curProp["USED"]) && !empty($curProp["NAME"]) && !empty($curProp["VALUE"]))
			$arResult["FREE_PROPS"][] = $curProp;
	}

	$this->IncludeComponentTemplate();
?>