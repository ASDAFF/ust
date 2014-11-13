<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$APPLICATION->RestartBuffer();
unset($arResult["COMBO"]);
unset($arResult["ELEMENTS"]);
unset($arResult["ELEMENTS_SKU"]);
echo CUtil::PHPToJSObject($arResult);
?>