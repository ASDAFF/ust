<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(count($arResult['ELEMENTS']) > 0 && $arResult["ITEMS_COUNT_SHOW"]): 
$arParams['MESSAGE_ALIGN'] = isset($arParams['MESSAGE_ALIGN']) ? $arParams['MESSAGE_ALIGN'] : 'LEFT';
$arParams['MESSAGE_TIME'] = intval($arParams['MESSAGE_TIME']) >= 0 ? intval($arParams['MESSAGE_TIME']) : 5;

CJSCore::Init(array("popup"));
$cntClosedProperty = 0;
?>
 

<div class="kombox-filter navesnoe" id="kombox-filter">
<?/*
if ($USER->IsAdmin()){
echo "<pre>";
print_r($arResult);
echo "</pre>";    
} 
*/?>
    <form name="<?echo $arParams["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get"<?if($arResult['IS_SEF']):?> data-sef="yes"<?endif;?>>
        <script>
            var komboxSmartFilter = new KomboxSmartFilter('<?echo $arResult["FORM_ACTION"]?>', '<?echo $arParams['MESSAGE_ALIGN']?>', <?echo $arParams['MESSAGE_TIME']?>);
        </script>
        <?foreach($arResult["HIDDEN"] as $arItem):?>
            <input
                type="hidden"
                name="<?echo $arItem["CONTROL_NAME"]?>"
                id="<?echo $arItem["CONTROL_ID"]?>"
                value="<?echo $arItem["HTML_VALUE"]?>"
            />
        <?endforeach;?>
        <ul> 
        <?if(!empty($arResult["section_filtered"])):?>
        <li class="lvl1 select_left">
                <div class="kombox-filter-property-head" style="padding: 1.8em 4px 5px; width:90px;">
                    <span class="kombox-filter-property-name">Тип техники:</span>
                </div> 
          
                <div class="vid_rabot" style="padding: 5px 0 0 15px;">
                    <select class="kombox-subsection" style="width: 324px; padding: 5px 0 0 15px;">
                        <option class="kombox-subsection-option" for="<?=$ar["CONTROL_ID"]?>" style="width: 324px;" id="<?="section"."_".$arResult["parent_section_id"]?>" value="0" <?if($ar["ID"] == $arParams["SECTION_ID"]):?>selected="selected"<?endif?>>Все</option>
                    <ul>
                        <?foreach($arResult["section_filtered"] as $val => $ar):?>
                        <li class="<?echo $ar["DISABLED"]? 'kombox-disabled ': ''?><?echo $ar["CHECKED"]? 'kombox-checked': ''?>">                               
                                <option class="kombox-subsection-option" for="<?=$ar["CONTROL_ID"]?>" style="width: 324px;" id="<?="section"."_".$ar["ID"]?>" value="<?=$ar["SECTION_PAGE_URL"];?>" <?if($ar["ID"] == $arParams["SECTION_ID"]):?>selected="selected"<?endif?>><?=$ar["NAME"];?></option>
                        </li>
                        <?endforeach;?>
                    </ul>
                    </select>
                    <div style="display: none; " >
                        <ul>
                            <?foreach($arItem["VALUES"] as $val => $ar):?>
                            <li class="<?echo $ar["DISABLED"]? 'kombox-disabled ': ''?><?echo $ar["CHECKED"]? 'kombox-checked': ''?>">
                                <input  
                                    type="checkbox"
                                    value="<?echo $ar["HTML_VALUE"]?>"
                                    name="<?echo $ar["CONTROL_NAME"]?>"
                                    id="<?echo $ar["CONTROL_ID"]?>"
                                    <?echo $ar["CHECKED"]? 'checked="checked"': ''?>
                                    onclick="komboxSmartFilter.click(this)" 
                                    data-value="<?echo $ar["CONTROL_NAME_ALT"]?>"
                                /> 
                            </li>
                            <?endforeach;?>
                        </ul>    
                    </div>
                </div>
        </li>
        <?endif?>
        <?if($arResult["ITEMS"]["998"]):?>
        <li class="lvl1 select_right" data-id="<?echo $arResult["ITEMS"]["998"]["CODE_ALT"].'-'.$arResult["ITEMS"]["998"]["ID"]?>">
                <div class="kombox-filter-property-head" style="padding: 1.8em 10px 5px 5px; width: 80px;">
                    <span class="kombox-filter-property-name"><?=$arResult["ITEMS"]["998"]["NAME"]?>:</span>
                </div>        
                <div class = "vid_rabot" style="padding: 5px 0 0 0px; float: left;">
                    <select name="<?=$arResult["ITEMS"]["998"]["CODE_ALT"]?>" class="kombox-select" style="width: 324px; padding: 5px 0 0 15px;">
                        <option id="0" class="kombox_option" value="0">Все</option>
                    <ul>
                        <?foreach($arResult["ITEMS"]["998"]["VALUES"] as $val => $ar):?>
                        <li class="<?echo $ar["DISABLED"]? 'kombox-disabled ': ''?><?echo $ar["CHECKED"]? 'kombox-checked': ''?>">                               
                            <option class="kombox_option" for="<?=$ar["CONTROL_ID"]?>" style="width: 324px;" id="<?=$ar["CONTROL_NAME_ALT"];?>" value="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?></option>
                        </li>
                        <?endforeach;?>
                    </ul>
                    </select>
                
                    <div style="display: none; " class="kombox-input-check">
                        <ul>
                            <?foreach($arResult["ITEMS"]["998"]["VALUES"] as $val => $ar):?>
                            <li class="<?echo $ar["DISABLED"]? 'kombox-disabled ': ''?><?echo $ar["CHECKED"]? 'kombox-checked': ''?>">
                                <input
                                    class="kombox-hidden-input"     
                                    type="checkbox"
                                    value="<?echo $ar["HTML_VALUE"]?>"
                                    name="<?echo $ar["CONTROL_NAME"]?>"
                                    id="<?echo $ar["CONTROL_ID"]?>"
                                    <?echo $ar["CHECKED"]? 'checked="checked"': ''?>
                                    onclick="komboxSmartFilter.click(this)" 
                                    data-value="<?echo $ar["CONTROL_NAME_ALT"]?>"
                                /> 
                            </li>
                            <?endforeach;?>
                        </ul>    
                    </div>
                </div>
        </li>
        <?endif?>
        <div class="kombox-clear"></div> 
        <div id="modef" class="elements_finded">
            <div style="float:left;">
                <strong style="padding-top: 25px; display: inline-block; position: relative;"><?echo GetMessage("KOMBOX_CMP_FILTER_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?></strong>
            </div>
            <div style="float: right; width: 400px;">                
                <a href="<?echo $arResult["FILTER_URL"]?>" style="text-decoration: none;padding-top: 5px; position:reltive; display:flex; width: 150px; float: left;"><br><div id="sort_button_div"><?echo GetMessage("KOMBOX_CMP_FILTER_FILTER_SHOW")?></div></a>
                <span class="ecke"></span>
                <?if($arResult['SET_FILTER']):?>
                    <a href="<?=$arResult["DELETE_URL"]?>" class="kombox-del-filter"><?=GetMessage("KOMBOX_CMP_FILTER_DEL_FILTER")?></a> 
                <?endif;?>
            </div>
        </div> 
            <li>
                <div class="kombox-filter-show-properties kombox-show" style="display: none;">
                    <?if($cntClosedProperty):?>
                    <a href=""><span class="kombox-show"><?=GetMessage("KOMBOX_CMP_FILTER_SHOW_FILTER")?></span><span class="kombox-hide"><?=GetMessage("KOMBOX_CMP_FILTER_HIDE_FILTER")?></span></a>
                    <?endif;?>
                </div>
                <div class="kombox-filter-submit" style="display: none;"> 
                    <input type="submit" id="set_filter" name="set_filter" value="<?=GetMessage("KOMBOX_CMP_FILTER_SET_FILTER")?>" />
                    <?if($arResult['SET_FILTER']):?>
                    <a href="<?=$arResult["DELETE_URL"]?>" class="kombox-del-filter"><?=GetMessage("KOMBOX_CMP_FILTER_DEL_FILTER")?></a> 
                    <?endif;?>
                </div>
                <div class="kombox-clear">&nbsp;</div>
            </li>
        </ul>
    </form>
    <div class="kombox-loading"></div>
</div>
<?endif;?>