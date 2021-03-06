<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
if(is_array($arResult["POST"]) && !empty($arResult["POST"]))
{
    $arSizes = array('width'=>20, 'height'=>20);
    $arCategoryList = CIdeaManagment::getInstance()->Idea()->GetCategoryList();
    $arStatusList = CIdeaManagment::getInstance()->Idea()->GetStatusList();
    $arResult["AUTHOR_AVATAR"] = array();

    foreach($arResult["POST"] as $key=>$arPost)
    {
        //Check dublicate
        $arResult["POST"][$key]["IS_DUBLICATE"] = false;
        if(array_key_exists("DATA", $arPost["POST_PROPERTIES"]) 
            && array_key_exists(CIdeaManagment::UFOriginalIdField, $arPost["POST_PROPERTIES"]["DATA"])
        )
            if(strlen(trim($arPost["POST_PROPERTIES"]["DATA"][CIdeaManagment::UFOriginalIdField]["VALUE"]))>0)
            {
                $DublicateValue = htmlspecialchars($arPost["POST_PROPERTIES"]["DATA"][CIdeaManagment::UFOriginalIdField]["VALUE"], ENT_QUOTES);
		if(strpos($DublicateValue, "://")!==false) //Link
                    $arResult["POST"][$key]["IS_DUBLICATE"] = $DublicateValue;
                else //Id
                    $arResult["POST"][$key]["IS_DUBLICATE"] = CComponentEngine::MakePathFromTemplate($arParams["PATH_TO_POST"], array("post_id" => $DublicateValue));
            }
        
        
        //Disable vote (reasosns: dublicate, completed status, not published)
        $arResult["POST"][$key]["DISABLE_VOTE"] = false;
        if($arResult["POST"][$key]["IS_DUBLICATE"] 
            ||ToLower($arStatusList[$arPost["POST_PROPERTIES"]["DATA"][CIdeaManagment::UFStatusField]["VALUE"]]["XML_ID"])=='completed'
            ||$arResult["POST"][$key]["PUBLISH_STATUS"] != BLOG_PUBLISH_STATUS_PUBLISH
        )    
            $arResult["POST"][$key]["DISABLE_VOTE"] = true;

        //Get official anwers
        if(
                array_key_exists("POST_PROPERTIES", $arPost) 
                && array_key_exists("DATA", $arPost["POST_PROPERTIES"]) 
                && array_key_exists("UF_ANSWER_ID", $arPost["POST_PROPERTIES"]["DATA"]) 
                && is_array($arPost["POST_PROPERTIES"]["DATA"][CIdeaManagment::UFAnswerIdField]) 
                && is_array($arPost["POST_PROPERTIES"]["DATA"][CIdeaManagment::UFAnswerIdField]["VALUE"])
        )
            $arResult["POST"][$key]["OFFICIAL_POST_ID"] = $arPost["POST_PROPERTIES"]["DATA"][CIdeaManagment::UFAnswerIdField]["VALUE"];
        else
            $arResult["POST"][$key]["OFFICIAL_POST_ID"] = array();

        //Get Avatars
        if(!array_key_exists($arPost["arUser"]["ID"], $arResult["AUTHOR_AVATAR"]))
        {
            if($arPost["arUser"]["PERSONAL_PHOTO"]>0)
                $arResult["AUTHOR_AVATAR"][$arPost["arUser"]["ID"]] = CFile::ResizeImageGet(
                    $arPost["arUser"]["PERSONAL_PHOTO"],
                    $arSizes,
                    BX_RESIZE_IMAGE_EXACT
                );
            else 
                $arResult["AUTHOR_AVATAR"][$arPost["arUser"]["ID"]]["src"] = $this->__folder.'/images/default_avatar.png';
        }

        //Prepare Category Info
        $Category = $arCategoryList[$arPost["POST_PROPERTIES"]["DATA"][CIdeaManagment::UFCategroryCodeField]["VALUE"]];
        $Category["NAME"] = trim($Category["NAME"]);
        $arCategorySequence = CIdeaManagment::getInstance()->Idea()->GetCategorySequence($Category["CODE"]);

        $arResult["POST"][$key]["IDEA_CATEGORY"] = array(
            "NAME" => false,
            "LINK" => false,
        );

        if(is_string($Category["NAME"]) && strlen($Category["NAME"])>0)
            $arResult["POST"][$key]["IDEA_CATEGORY"]["NAME"] = $Category["NAME"];

        if($arCategorySequence["CATEGORY_2"]!==false)
            $arResult["POST"][$key]["IDEA_CATEGORY"]["LINK"] = CComponentEngine::MakePathFromTemplate($arParams["AR_RESULT"]["PATH_TO_CATEGORY_2"], array("category_1" => $arCategorySequence["CATEGORY_1"], "category_2" => $arCategorySequence["CATEGORY_2"]));
        elseif($arCategorySequence["CATEGORY_1"]!==false)
            $arResult["POST"][$key]["IDEA_CATEGORY"]["LINK"] = CComponentEngine::MakePathFromTemplate($arParams["AR_RESULT"]["PATH_TO_CATEGORY_1"], array("category_1" => $arCategorySequence["CATEGORY_1"]));
    }
}
?>