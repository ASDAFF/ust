<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$text = file_get_contents($arResult["FILE"]);
$arText = explode("#CAT#", $text);
if(!empty($arText[0]))
echo "<div>".$arText[0]."</div>";
if(!empty($arText[1])) {
	echo "<div class='more_description'>";
	echo $arText[1];
	echo "</div>";
	echo "<p><a href='#' class='catalog_more' >Подробнее &raquo;</a></p>";
}
?>