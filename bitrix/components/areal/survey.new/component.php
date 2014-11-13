<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
    
    if(!isset($arParams["AJAX"]) || $arParams["AJAX"] != "Y") {
        $cache = new CPHPCache();
        $cache_time = 3600;
        $arResult = array();
        $cache_dir_id = 'surveys';
        $cache_dir_path = '/surveys/';
        if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
           $res = $cache->GetVars();
           if (is_array($res["surveys"]) && (count($res["surveys"]) > 0))
              $arResult = array_merge($arResult, $res["surveys"]);
        }
        else {
            $arResult["INRTODUCTION"] = COption::GetOptionString("ust", "SURVEY_INTRODUCTION");
            $arResult["INTRODUCTION_TYPE"] = COption::GetOptionString("ust", "SURVEY_INTRODUCTION_TYPE");
            $answers = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => SURVEY_ANSWER, "ACTIVE" => "Y"), false, false, array("ID", "NAME", "SORT", "PREVIEW_PICTURE"));
            while($answer = $answers->GetNext())
                $arResult["ANSWERS"][] = array("NAME" => $answer["NAME"], "PICTURE" => CFile::GetPath($answer["PREVIEW_PICTURE"]));
            
            $questions = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => SURVEY_QUESTIONS, "ACTIVE" => "Y"), false, false, array("ID", "NAME", "SORT"));
            while($question = $questions->GetNext())
                $arResult["QUESTIONS"][$question["ID"]] = $question["NAME"];
            if ($cache_time > 0) {
                $cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
                $cache->EndDataCache(array("surveys" => $arResult));
            }
        }    
        $this->IncludeComponentTemplate();
    }
    else {
        $el = new CIBlockElement;
        $PROP["TOWN"] = $_REQUEST["TOWN"];
        $PROP["BUY_EARLY"] = $_REQUEST["BUY_EARLY"];
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
        /*$PROP["PHONE"] = $_REQUEST["PHONE"];
        $PROP["EMAIL"] = $_REQUEST["EMAIL"];*/
        
        if(!empty($_REQUEST["ANSWERS"])) {
            foreach($_REQUEST["ANSWERS"] as $key => $answering) {
                unset($answers);
                unset($answer);
                $answers = CIBlockElement::GetList(array(), array("IBLOCK_ID" => SURVEY_QUESTIONS, "ACTIVE" => "Y", "ID" => $key), false, false, array("ID", "NAME"));
                if($answer = $answers->GetNext())                    
                    $PROP["ANSWERS"][] = Array(
                        "VALUE" => $answer["NAME"],
                        "DESCRIPTION" => $answering
                    );                
            }
        }        
        $arCallback = Array(
            "IBLOCK_ID" => SURVEY_FORM,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $_REQUEST["PROPS_NAME"],
            "ACTIVE" => "Y",
            "PREVIEW_TEXT" => $_REQUEST["PROPS_MESSAGE"],
            "PREVIEW_TEXT_TYPE" => "text"
        );        
        if($ID = $el->Add($arCallback)) {
            if(!empty($arCallback["PROPERTY_VALUES"]["ANSWERS"]))
                foreach($arCallback["PROPERTY_VALUES"]["ANSWERS"] as $ANSWERS) {
                    $CONTENT[] = "<b>".$ANSWERS["VALUE"]."</b>: ".$ANSWERS["DESCRIPTION"]."<br />";
                }
            $arSend = array(
                "ID" => $ID,
                "NAME" => $PROP["NAME"],
                "EMAIL" => $PROP["EMAIL"] ? $PROP["EMAIL"] : "Email не указан",
                "PHONE" => $PROP["PHONE"] ? $PROP["PHONE"] : "Телефон не указан",
                "TOWN" => $_REQUEST["TOWN"] ? $_REQUEST["TOWN"] : "Город не указан",
                "BUY_EARLY" => $_REQUEST["BUY_EARLY"],
                "MESSAGE" => $PROP["MESSAGE"] ? $PROP["MESSAGE"] : "Комментарий не указан",
                "CONTENT" => implode("", $CONTENT)
            );
            CEvent::Send("WEB_FORM", "s1", $arSend, "Y", 80);
        }
        else $arResult["ERROR"] = $el->LAST_ERROR;
        
        if(empty($arResult["ERROR"])) {
            $GRATITUDE_TYPE = COption::GetOptionString("ust", "SURVEY_GRATITUDE_TYPE");
            if($GRATITUDE_TYPE == "text") 
                $arResult["GRATITUDE"] = "<p>".COption::GetOptionString("ust", "SURVEY_GRATITUDE")."</p>";
            else
                $arResult["GRATITUDE"] = COption::GetOptionString("ust", "SURVEY_GRATITUDE");
            echo json_encode($arResult);
        } 
    }
}
?>