<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arParams["form_index"] = randstring(4);

$arParams["FORM_ID"] = "REPLIER".$arParams["form_index"];
$arParams["jsObjName"] = "oLHE";
$arParams["LheId"] = "idLHE".$arParams["form_index"];

$arParams["tabIndex"] = intVal(intval($arParams["TAB_INDEX"]) > 0 ? $arParams["TAB_INDEX"] : 10);


$arParams["EDITOR_CODE_DEFAULT"] = ($arParams["EDITOR_CODE_DEFAULT"] == "Y" ? "Y" : "N");
$arResult["QUESTIONS"] = array_values($arResult["QUESTIONS"]);



if ($arParams['AJAX_POST']=='Y' && ($_REQUEST["save_product_review"] == "Y"))
{
	ob_start();
}
?>
