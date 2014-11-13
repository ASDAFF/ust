<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (is_array($arResult["SECTION"]["PATH"]))
{
    $s = array_pop($arResult["SECTION"]["PATH"]);
    $APPLICATION->SetTitle($s["NAME"]);
}
?>