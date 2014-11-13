<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	if($arParams["SECTION_CODE"]) {
		$res = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "CODE" => $arParams["SECTION_CODE"]), false, array("UF_SHORT_SEO_TEXT", "UF_LONG_SEO_TEXT"));
		if($section = $res->GetNext())
			$arResult["SECTION"] = $section;
	}
	$this->IncludeComponentTemplate();
}
?>