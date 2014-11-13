<?
header("Access-Control-Allow-Origin: *");
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
switch ($_REQUEST['FormType'])
{
    case 'setTown':
        $arResult["STATUS"] = 0;
        if($_REQUEST["ID"] > 0) {
            if(CModule::IncludeModule("iblock")) {
                $res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y", "ID" => $_REQUEST["ID"]), false, false, array("ID", "NAME"));
                if($element = $res->GetNext()) {                    
                    $_SESSION["SELECTED_TOWN"] = $element["NAME"];
                    $arResult["STATUS"] = 1;
                }
            }
        }
        echo json_encode($arResult);
    break;
    
    case 'getTownArray':
        $arResult["STATUS"] = 0;
        if(!empty($_REQUEST["SearchText"]) && CModule::IncludeModule("iblock")) {
            $secs = CIBlockElement::GetList(array("NAME" => "ASC"), array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y", "NAME" => $_REQUEST["SearchText"]."%"), false, false, array("ID", "NAME"));
            while($element = $secs->GetNext()) {
                $arResult["TOWNS"][] = array("ID" => $element["ID"], "NAME" => StrPosCustom($element["NAME"], $_REQUEST["SearchText"]));
            }
            if(!empty($arResult["TOWNS"]))
                $arResult["STATUS"] = 1;
            else {
                $arResult["STATUS"] = 0;
                $arResult["ERROR"] = "Не найдено ни одного города.";
            }
        }        
        echo json_encode($arResult);
    break;
    
    case 'CallbackSend':
        $arResult["STATUS"] = 0;
        if(!empty($_REQUEST) && CModule::IncludeModule("iblock")) {
            //if($USER->IsAdmin()){
                if(!empty($_REQUEST["PROPS_NAME"]) && !empty($_REQUEST["PROPS_PHONE"]) && !empty($_REQUEST["PROPS_MESSAGE"])) {
                    $el = new CIBlockElement;
                    foreach($_REQUEST as $key=>$arProperts){            
                        $pos = strpos($key, "PROPS_");
                        if($pos !== false){
                            $res = substr($key, 6);
                            $PROP[$res] = $arProperts;
                        } 
                        $pos1 = strpos($key, 'PLIST_');
                        if($pos1 !== false){
                            $res1 = substr($key, 6);
                            $PROP[$res1]["VALUE"] = $arProperts;                
                            $ar_resul = CIBlockPropertyEnum::GetByID($arProperts);
                            $PROP[$res1]["NAME"] = $ar_resul["VALUE"];                
                        }       
                    } 
                    //$PROP["COMMENT"]["VALUE"] = array ("TEXT" => $_REQUEST["COMMENT"], "TYPE" => "text");
                    $arCallback = Array(
                        "IBLOCK_ID" => CALLBACK,
                        "PROPERTY_VALUES" => $PROP,
                        "NAME" => $_REQUEST["PROPS_NAME"],
                        "ACTIVE" => "Y"
                    );                
                    if($ID = $el->Add($arCallback)) {
                        if(!empty($PROP["TIME"])) {
                            $times = CIBlockPropertyEnum::GetList(
                                array(),
                                array("IBLOCK_ID" => CALLBACK, "PROPERTY_ID" => "TIME", "ID" => $PROP["TIME"])
                            );
                            if($time = $times->GetNext()) {
                                $timed = $time["VALUE"];
                            }
                        }
                        $arSend = array(
                            "NAME" => $PROP["NAME"],
                            "PHONE" => $PROP["PHONE"],
                            "TOWN" => $PROP["TOWN"],
                            "TIME" => $timed,
                            "COMMENT" => $PROP["MESSAGE"]
                        );
                        CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 69);
                        $arResult["STATUS"] = 1;
                    }
                    else $arResult["ERROR"] = $el->LAST_ERROR;
                }
                else
                    $arResult["ERROR"] = "Вы не заполнили обязательные поля.";   
                }    
            
           /* else{         
            if(!empty($_REQUEST["NAME"]) && !empty($_REQUEST["PHONE"]) && !empty($_REQUEST["COMMENT"])) {
                $el = new CIBlockElement;
                $PROP["PHONE"] = $_REQUEST["PHONE"];
                $PROP["TOWN"] = $_REQUEST["TOWN"];
                $PROP["TIME"] = $_REQUEST["TIME"];
                $PROP["COMMENT"]["VALUE"] = array ("TEXT" => $_REQUEST["COMMENT"], "TYPE" => "text");
                $arCallback = Array(
                    "IBLOCK_ID" => CALLBACK,
                    "PROPERTY_VALUES" => $PROP,
                    "NAME" => $_REQUEST["NAME"],
                    "ACTIVE" => "Y"
                );                
                if($ID = $el->Add($arCallback)) {
                    if(!empty($_REQUEST["TIME"])) {
                        $times = CIBlockPropertyEnum::GetList(
                            array(),
                            array("IBLOCK_ID" => CALLBACK, "PROPERTY_ID" => "TIME", "ID" => $_REQUEST["TIME"])
                        );
                        if($time = $times->GetNext()) {
                            $timed = $time["VALUE"];
                        }
                    }
                    $arSend = array(
                        "NAME" => $_REQUEST["NAME"],
                        "PHONE" => $_REQUEST["PHONE"],
                        "TOWN" => $_REQUEST["TOWN"],
                        "TIME" => $timed,
                        "COMMENT" => $_REQUEST["COMMENT"]
                    );
                    CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 69);
                    $arResult["STATUS"] = 1;
                }
                else $arResult["ERROR"] = $el->LAST_ERROR;
            }
            else
                $arResult["ERROR"] = "Вы не заполнили обязательные поля.";
            } 
        }    */
        echo json_encode($arResult);
    break;
    
    case 'orderFormSend':
        $_REQUEST["ajax"] = "Y";
    
            $APPLICATION->IncludeComponent("areal:form.order.new", "popup", array("THEME_TYPE" => $_REQUEST["THEME_TYPE"]));    
    
    break;
    
    case 'getSurvey':
    
            $APPLICATION->IncludeComponent("areal:survey.new", ".default", array("AJAX" => "Y"));    
    
    break;
    
    case 'setServiceSession':
        if(!isset($_SESSION["SERVICE_WINDOW"]))
            $_SESSION["SERVICE_WINDOW"] = 1;
        else
            $_SESSION["SERVICE_WINDOW"]++;
        if($_SESSION["SERVICE_WINDOW"] > 0) $arResult["STATUS"] = 1;
        echo json_encode($arResult);
    break;
    
    case 'getServiceCenters':
        $APPLICATION->IncludeComponent("areal:service.address", ".default", array("TOWN_NAME" => $_REQUEST["Town"]));
    break;
    
    case "addCompare":
        if($_REQUEST["ID"] && CModule::IncludeModule("iblock")) {
            $res = CIBlockElement::GetList(array(), array("ID" => $_REQUEST["ID"]), false, false, array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID"));
            while($element = $res->GetNext()) {
                if(!in_array($element["ID"], $_SESSION["CATALOG_COMPARE_LIST"][$element["IBLOCK_ID"]]["ITEMS"][$element["IBLOCK_SECTION_ID"]]))
                    $_SESSION["CATALOG_COMPARE_LIST"][$element["IBLOCK_ID"]]["ITEMS"][$element["IBLOCK_SECTION_ID"]][] = $element["ID"];
                $arResult["STATUS"] = 1;
                $arResult["COUNT"] = count($_SESSION["CATALOG_COMPARE_LIST"][$element["IBLOCK_ID"]]["ITEMS"][$element["IBLOCK_SECTION_ID"]]);
            }
        }
        else $arResult["STATUS"] = 0;
        echo json_encode($arResult);
                
                
    break;
    
    case "delCompare":
        if($_REQUEST["ID"] && CModule::IncludeModule("iblock")) {
            $res = CIBlockElement::GetList(array(), array("ID" => $_REQUEST["ID"]), false, false, array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID"));
            while($element = $res->GetNext()) {        
                foreach($_SESSION["CATALOG_COMPARE_LIST"][$element["IBLOCK_ID"]]["ITEMS"][$element["IBLOCK_SECTION_ID"]] as $key => $val)
                    if($val == $element["ID"])
                        unset($_SESSION["CATALOG_COMPARE_LIST"][$element["IBLOCK_ID"]]["ITEMS"][$element["IBLOCK_SECTION_ID"]][$key]);
                    $count = count($_SESSION["CATALOG_COMPARE_LIST"][$element["IBLOCK_ID"]]["ITEMS"][$element["IBLOCK_SECTION_ID"]]);
                    if ($count == 0)
                        unset($_SESSION["CATALOG_COMPARE_LIST"][$element["IBLOCK_ID"]]["ITEMS"][$element["IBLOCK_SECTION_ID"]]);
                $arResult["STATUS"] = 1;
                $arResult["COUNT"] = count($_SESSION["CATALOG_COMPARE_LIST"][$element["IBLOCK_ID"]]["ITEMS"][$element["IBLOCK_SECTION_ID"]]);
            }
        }
        else $arResult["STATUS"] = 0;
        echo json_encode($arResult);
    break;
    
    case "checkCatalogFilter":
        $APPLICATION->IncludeComponent("areal:catalog.filter", ".default", array("IBLOCK_ID" => CATALOG, "AJAX" => "Y"));
    break;
    
    case "checkCatalogCount":
        if(!empty($_REQUEST) && CModule::IncludeModule("iblock")) {
            if($_REQUEST["View"] > 0) $section_id = $_REQUEST["View"];
            else $section_id = $_REQUEST["Type"];
            if(!empty($_REQUEST["Brands"])) {
                $brands = CIBlockElement::GetList(array(), array("IBLOCK_ID" => BRANDES, "CODE" => $_REQUEST["Brands"]), false, false, array("ID", "CODE"));
                while($brand = $brands->GetNext()) {
                    $brands_id[] = $brand["ID"];
                }
            }
            $arResult["COUNT"] = CIBlockElement::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y", "PROPERTY_BRAND" => $brands_id, "SECTION_ID" => $section_id, "INCLUDE_SUBSECTIONS" => "Y"), array(), false, array("ID", "IBLOCK_SECTION_ID", "PROPERTY_BRAND"));
            $arResult["STATUS"] = 1;
        }
        else $arResult["STATUS"] = 0;
        echo json_encode($arResult);
    break;
    
    case "updateComparisonList":
        if(isset($_SESSION["CATALOG_COMPARE_LIST"][CATALOG]["ITEMS"]) && !empty($_SESSION["CATALOG_COMPARE_LIST"][CATALOG]["ITEMS"]))
        {    
            $arResult["COUNT2"] = count($_SESSION["CATALOG_COMPARE_LIST"][CATALOG]["ITEMS"]);
            $i=0;
            foreach ($_SESSION["CATALOG_COMPARE_LIST"][CATALOG]["ITEMS"] as $sections) {
                foreach ($sections as $items) {
                    $item[$i] = $items;    
                    $i++;
                }
            }
            $arResult["items"] = $item;
            $arResult["COUNT"] = $i;
        }else
            $arResult["COUNT"] = 0;
        echo json_encode($arResult);
    break;
    
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>