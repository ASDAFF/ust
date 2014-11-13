<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
if(CModule::IncludeModule("iblock")) {
	if(!$arParams["PREVIEW_PICTURE_WIDTH"])
		$arParams["PREVIEW_PICTURE_WIDTH"] = 140;
	if(!$arParams["PREVIEW_PICTURE_HEIGHT"])
		$arParams["PREVIEW_PICTURE_HEIGHT"] = 110;
		
	$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);
	if(!isset($arParams["CACHE_TIME"]))
		$arParams["CACHE_TIME"] = 36000000;
		
	if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	{
		$arrFilter = array();
	}
	else
	{
		$arrFilter = $GLOBALS[$arParams["FILTER_NAME"]];
		if(!is_array($arrFilter))
			$arrFilter = array();
	}
		
	if ($this->StartResultCache(false, array($arrFilter)))
	{
		if(!CModule::IncludeModule("iblock"))
		{
			$this->AbortResultCache();
			ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
			return;
		}
		if(is_numeric($arParams["IBLOCK_ID"]))
		{
			$rsIBlock = CIBlock::GetList(array(), array(
				"ACTIVE" => "Y",
				"ID" => $arParams["IBLOCK_ID"],
			));
		}
		else
		{
			$rsIBlock = CIBlock::GetList(array(), array(
				"ACTIVE" => "Y",
				"CODE" => $arParams["IBLOCK_ID"],
				"SITE_ID" => SITE_ID,
			));
		}
	
		if($arResult = $rsIBlock->GetNext())
		{
			$arSelect = array(
				"ID",
				"IBLOCK_ID",
				"IBLOCK_SECTION_ID",
				"NAME",
				"ACTIVE_FROM",
				"PREVIEW_PICTURE",
				"CREATED",
				"DETAIL_PAGE_URL"
			);
			
			$arFilter = array (
				"IBLOCK_ID" => $arResult["ID"],
				"IBLOCK_LID" => SITE_ID,
				"ACTIVE" => "Y",
				"CHECK_PERMISSIONS" => "Y",
				"ACTIVE_DATE" => "Y"
			);
			$arSort = array(
				$arParams["SORT_BY1"]=>$arParams["SORT_ORDER1"],
				$arParams["SORT_BY2"]=>$arParams["SORT_ORDER2"],
			);
			if(!array_key_exists("ID", $arSort))
				$arSort["ID"] = "DESC";
				
			$res = CIBlockElement::GetList($arSort, array_merge($arFilter, $arrFilter), false, false, $arSelect);
			while($element = $res->GetNext()) {
				$arItem["NAME"] = $element["NAME"];
				$arItem["DETAIL_PAGE_URL"] = $element["DETAIL_PAGE_URL"];
				$arItem["DATE"] = $element["ACTIVE_FROM"] ? (CIBlockFormatProperties::DateFormat("j F Y", MakeTimeStamp($element["ACTIVE_FROM"], CSite::GetDateFormat()))) : (CIBlockFormatProperties::DateFormat("j F Y", MakeTimeStamp($element["CREATED"], CSite::GetDateFormat())));
				
				$arItem["PREVIEW_PICTURE"] = CFile::ResizeImageGet( 
					$element["PREVIEW_PICTURE"], 
					array("width" => $arParams["PREVIEW_PICTURE_WIDTH"], "height" => $arParams["PREVIEW_PICTURE_HEIGHT"]), 
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true 
				);
				$arItem["DETAIL_PICTURE"] = CFile::GetPath($element["PREVIEW_PICTURE"]);
				$arResult["ITEMS"][] = $arItem;
			}
		}
		if(!$arParams["NEWS_COUNT"]) $arParams["NEWS_COUNT"] = count($arResult["ITEMS"]);
		$this->SetResultCacheKeys(array_keys($arResult));
		$this->IncludeComponentTemplate();		
	}
	if($arParams["SET_TITLE"] == "Y")
		$APPLICATION->SetTitle($arResult["NAME"]); 
}
?>