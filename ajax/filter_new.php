<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?//echo "<pre>";
//echo ($_REQUEST['var1']);
//echo "</pre>";
$res = CIBlockSection::GetByID($_REQUEST['var1']);
while($ar_res = $res->GetNext()){
    //echo "<pre>";
    //print_r($ar_res);
    //echo "</pre>";
    $_REQUEST["THIS_SECTION"] = $ar_res["SECTION_PAGE_URL"];
}

$iblock_id = 6;
$iblock_code = "catalog";
$template_name = ".default";
if(isset($_REQUEST["var2"]) && $_REQUEST["var2"] == "54") {
    $iblock_id = 54;
    $iblock_code = "used";
    $template_name = "horizontal_filter_test1";
}       

$arFilter = array('IBLOCK_ID' => $iblock_id, "ID" => $_REQUEST['var1']);
$db_list = CIBlockSection::GetList(Array("SORT"=>"ASC"), $arFilter, false, array("UF_TEMPLATE"));
if($ar_result_uf = $db_list->GetNext())
    {
        $_REQUEST["UF_TEMPLATE"] = $ar_result_uf["UF_TEMPLATE"];
    }
//$arResult["ELEMENTS_IN_SECTION"] = CIBlockSection::GetSectionElementsCount($_REQUEST['var1'], array());
/*if(empty($_REQUEST["UF_TEMPLATE"])){
    $_REQUEST["UF_TEMPLATE"] = "horizontal_filter_test";
} */  
    $APPLICATION->IncludeComponent(
        "kombox:filter",
        //($ar_result_uf["UF_TEMPLATE"])?$ar_result_uf["UF_TEMPLATE"]:"horizontal_filter_test",
        ($ar_result_uf["UF_TEMPLATE"])?$ar_result_uf["UF_TEMPLATE"]:$template_name,
        //"horizontal_filter_test",
        Array(
        "IBLOCK_TYPE" => $iblock_code,
        "IBLOCK_ID" => $iblock_id,
        "SECTION_ID" => $_REQUEST['var1'],//"190",//$_REQUEST["SECTION_ID"],//
        "FILTER_NAME" => "arrFilter",
        "PRICE_CODE" => $arParams["PRICE_CODE"],
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_NOTES" => "",
        "CACHE_GROUPS" => "Y",
        "SAVE_IN_SESSION" => "N",
        "CLOSED_PROPERTY_CODE" => array(),
        "CLOSED_OFFERS_PROPERTY_CODE" => array()
        ),
        $component 
    ); 
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>