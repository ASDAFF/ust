<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$text = file_get_contents($arResult["FILE"]);
$arText = explode("#CAT#", $text);
if(!empty($arText[0]))
echo $arText[0];
if(!empty($arText[1])) {
	echo "<div class='more-text'>";
	echo $arText[1];
	echo "</div>";
	echo "<div class='overlay-grad'></div><div class='show-more'><a href='#' class='expand'>Развернуть</a></div>";
}
?>