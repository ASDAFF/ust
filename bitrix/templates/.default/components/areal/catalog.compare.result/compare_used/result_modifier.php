<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
foreach($arResult["IBLOCK_SECTION_ID"] as &$arId){
	foreach ($arResult["ITEMS"][$arId] as &$arItem) {
		if(!empty($arItem["FIELDS"]["DETAIL_PICTURE"])){
			$arItem["PICTURE"] = CFile::GetFileArray($arItem["FIELDS"]["DETAIL_PICTURE"]);
			$arItem["PICTURE"]["HEIGHT"] = "90";
			$arItem["PICTURE"]["WIDTH"] = "";
		}
	}
}	
?>