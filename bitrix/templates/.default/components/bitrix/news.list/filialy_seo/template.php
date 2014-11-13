<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$isDefault = 1;
foreach($arResult["ITEMS"] as $arItem){
	$dbDomain = CIBlockElement::GetByID($arItem["PROPERTIES"]["PODDOMEN"]["VALUE"]);
	if($arDomain = $dbDomain->Fetch()){
		if($arDomain["NAME"] == $_SERVER["HTTP_HOST"]) {
			echo $arItem["PROPERTIES"]["SEO_TEXT"]["VALUE"];
			$isDefault = 0;
			break;
		}
	}
}
if($isDefault == 1):?>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "catalog", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/filialy.php"), false);?>
<?endif;?>