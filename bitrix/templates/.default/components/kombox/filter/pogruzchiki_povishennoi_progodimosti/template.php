<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(count($arResult['ELEMENTS']) > 0 && $arResult["ITEMS_COUNT_SHOW"]): 
$arParams['MESSAGE_ALIGN'] = isset($arParams['MESSAGE_ALIGN']) ? $arParams['MESSAGE_ALIGN'] : 'LEFT';
$arParams['MESSAGE_TIME'] = intval($arParams['MESSAGE_TIME']) >= 0 ? intval($arParams['MESSAGE_TIME']) : 5;

CJSCore::Init(array("popup"));
$cntClosedProperty = 0;
?>
 

<div class="kombox-filter" id="kombox-filter">
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
        <?if(!empty($arResult["section_filter"])):?> 
        <li class="lvl1 select_left">
                <div class="kombox-filter-property-head" style="width: 90px; padding: 1.8em 5px 5px;">
                    <span class="kombox-filter-property-name">Вид техники:</span>
                </div> 
         
                <div id="vil_vid" style="padding: 5px 0 0 15px;">
                    <select class="kombox-section" style="width: 324px; padding: 5px 0 0 15px;">
                    <ul>
                        <?foreach($arResult["section_filter"] as $val => $ar):?>
                        <li class="<?echo $ar["DISABLED"]? 'kombox-disabled ': ''?><?echo $ar["CHECKED"]? 'kombox-checked': ''?>">                                                                           
                                <option class="kombox-section-option" for="<?=$ar["CONTROL_ID"]?>" style="width: 324px;" id="<?="section"."_".$ar["ID"]?>" value="<?=$ar["SECTION_PAGE_URL"];?>" <?if(($ar["ID"] == $arParams["SECTION_ID"]) || ($ar["ID"] == $arResult["parent_section_id"])):?>selected="selected"<?endif?>><?=$ar["NAME"];?></option>
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
        <?if(!empty($arResult["section_filtered"])):?>
        <li class="lvl1 select_right">
                <div class="kombox-filter-property-head" style="padding: 1.8em 4px 5px; width:90px;">
                    <span class="kombox-filter-property-name">Тип техники:</span>
                </div> 
          
                <div id="vil_type" style="padding: 5px 0 0 15px;">
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
        <div class="kombox-clear"></div>
        
        <?if((count($arResult["ITEMS"]["212"]["VALUES"]) == 1) && ($arResult["ITEMS"]["212"]["CODE_ALT"] == "brand")):?>  
        <li class="lvl1 select_left" data-id="<?=$arResult["ITEMS"]["212"]["CODE_ALT"].'-'.$arResult["ITEMS"]["212"]["ID"]?>">
                <div class="kombox-filter-property-head">
                    <span class="kombox-filter-property-name"><?=$arResult["ITEMS"]["212"]["NAME"]?></span>
                </div> 
        <?foreach($arResult["ITEMS"]["212"]["VALUES"] as $val => $ar):?>    
            <div class="solo_brand"><?=$ar["VALUE"];?></div>
        <?endforeach;?>
        </li>            
        <?elseif((count($arResult["ITEMS"]["212"]["VALUES"]) <= COption::GetOptionInt("ust", "catalog_filter_count_brand")) && ($arResult["ITEMS"]["212"]["CODE_ALT"] == "brand") && (count($arResult["ITEMS"]["212"]["VALUES"]) != 1)):?>
        <li class="lvl1 select_left" data-id="<?echo $arResult["ITEMS"]["212"]["CODE_ALT"].'-'.$arResult["ITEMS"]["212"]["ID"]?>">
                <div class="kombox-filter-property-head">
                    <span class="kombox-filter-property-name"><?=$arResult["ITEMS"]["212"]["NAME"]?></span>
                </div> 
                <div class="kombox-combo" data-name="<?=$arResult["ITEMS"]["212"]["CODE_ALT"]?>">                
                    <ul>
                        <?foreach($arResult["ITEMS"]["212"]["VALUES"] as $val => $ar):?>
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
                            <?if(!$arResult["ITEMS"]["212"]["ID"]):?>
                            <label for="<?=$ar["CONTROL_ID"]?>" class="label_checked checkbox_off"><font><?echo $ar["VALUE"];?></font> <span>(<?echo $arResult["section_filter"]["CNT"];?>)</span></label>
                            <?else:?>
                            <label for="<?=$ar["CONTROL_ID"]?>" class="label_checked checkbox_off"><font><?echo $ar["VALUE"];?></font> <span>(<?echo $ar["CNT"];?>)</span></label>
                            <?endif?>
                        </li>
                        <?endforeach;?>
                    </ul>
                </div>
        </li>     
        <?elseif($arResult["ITEMS"]["212"]["CODE_ALT"] == "brand"):/**/?>
        <?/*if($arResult["ITEMS"]["212"]):*/?>
        <li class="lvl1 select_left" data-id="<?echo $arResult["ITEMS"]["212"]["CODE_ALT"].'-'.$arResult["ITEMS"]["212"]["ID"]?>">
                <div class="kombox-filter-property-head" style="padding: 1.8em 48px 5px 5px;">
                    <span class="kombox-filter-property-name"><?=$arResult["ITEMS"]["212"]["NAME"]?>:</span>
                </div>        
                <div id="brand_holder" style="padding: 5px 0 0 15px;">
                    <select name="<?=$arResult["ITEMS"]["212"]["CODE_ALT"]?>" class="kombox-select" style="width: 324px; padding: 5px 0 0 15px;">
                        <option id="0" class="kombox_option" value="0">Все</option>
                    <ul>
                        <?foreach($arResult["ITEMS"]["212"]["VALUES"] as $val => $ar):?>
                        <li class="<?echo $ar["DISABLED"]? 'kombox-disabled ': ''?><?echo $ar["CHECKED"]? 'kombox-checked': ''?>">                               
                            <option class="kombox_option" for="<?=$ar["CONTROL_ID"]?>" style="width: 324px;" id="<?=$ar["CONTROL_NAME_ALT"];?>" value="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?></option>
                        </li>
                        <?endforeach;?>
                    </ul>
                    </select>
                
                    <div style="display: none; " class="kombox-input-check">
                        <ul>
                            <?foreach($arResult["ITEMS"]["212"]["VALUES"] as $val => $ar):?>
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
        <?if($arResult["ITEMS"]["763"]["PROPERTY_TYPE"] == "N" || isset($arResult["ITEMS"]["763"]["PRICE"])):?>
            <?if(isset($arResult["ITEMS"]["763"]["VALUES"]["MIN"]["VALUE"]) && isset($arResult["ITEMS"]["763"]["VALUES"]["MAX"]["VALUE"]) && $arResult["ITEMS"]["763"]["VALUES"]["MAX"]["VALUE"] > $arResult["ITEMS"]["763"]["VALUES"]["MIN"]["VALUE"]):?>
            <li class="lvl1<?if($arResult["ITEMS"]["763"]["CLOSED"]):?> closed<?endif;?> select_right" data-id="<?echo $arResult["ITEMS"]["763"]["CODE_ALT"].'-'.$arResult["ITEMS"]["763"]["ID"]?>">
                <div class="kombox-filter-property-head kombox-middle" style="width: 147px; padding: 1.8em 10px 5px 5px;">
                    <?if(strlen($arResult["ITEMS"]["763"]['HINT'])):?>
                    <span class="kombox-filter-property-hint"></span>
                    <div class="kombox-filter-property-hint-text"><?echo $arResult["ITEMS"]["763"]['HINT']?></div>
                    <?endif;?>
                    <span class="kombox-filter-property-name"><?echo $arResult["ITEMS"]["763"]["NAME"]?>:</span>   
                </div>
                <div class="kombox-num" data-name="<?=$arResult["ITEMS"]["763"]["CODE_ALT"]?>">
                    <table style="width: 100%;">
                        <tr>
                            <?
                            $minValue = !empty($arResult["ITEMS"]["763"]["VALUES"]["MIN"]["HTML_VALUE"]) ? $arResult["ITEMS"]["763"]["VALUES"]["MIN"]["HTML_VALUE"] : $arResult["ITEMS"]["763"]["VALUES"]["MIN"]["VALUE"];
                            $maxValue = !empty($arResult["ITEMS"]["763"]["VALUES"]["MAX"]["HTML_VALUE"]) ? $arResult["ITEMS"]["763"]["VALUES"]["MAX"]["HTML_VALUE"] : $arResult["ITEMS"]["763"]["VALUES"]["MAX"]["VALUE"];
                            ?>
                            <td class="kombox-from" style="display: none;">
                                <?echo GetMessage("KOMBOX_CMP_FILTER_FROM")?> 
                                <input 
                                    class="kombox-input kombox-num-from" 
                                    type="text" 
                                    name="<?echo $arResult["ITEMS"]["763"]["VALUES"]["MIN"]["CONTROL_NAME"]?>" 
                                    id="<?echo $arResult["ITEMS"]["763"]["VALUES"]["MIN"]["CONTROL_ID"]?>" 
                                    value="<?echo $arResult["ITEMS"]["763"]["VALUES"]["MIN"]["HTML_VALUE"]?>" 
                                    size="5" 
                                />
                            </td>
                            <td class="kombox-range"> 
                                <div  
                                    data-value="<?echo $minValue?>;<?=$maxValue?>" 
                                    data-min="<?echo $arResult["ITEMS"]["763"]["VALUES"]["MIN"]["VALUE"]?>" 
                                    data-max="<?echo $arResult["ITEMS"]["763"]["VALUES"]["MAX"]["VALUE"]?>" 
                                    data-range-from="<?echo $arResult["ITEMS"]["763"]["VALUES"]["MIN"]["RANGE_VALUE"]?>" 
                                    data-range-to="<?echo $arResult["ITEMS"]["763"]["VALUES"]["MAX"]["RANGE_VALUE"]?>" 
                                >
                                </div>
                            </td>
                            <td class="kombox-to" style="display: none">
                                <?echo GetMessage("KOMBOX_CMP_FILTER_TO")?>  
                                <input 
                                    class="kombox-input kombox-num-to" 
                                    type="text" 
                                    name="<?echo $arResult["ITEMS"]["763"]["VALUES"]["MAX"]["CONTROL_NAME"]?>" 
                                    id="<?echo $arResult["ITEMS"]["763"]["VALUES"]["MAX"]["CONTROL_ID"]?>" 
                                    value="<?echo $arResult["ITEMS"]["763"]["VALUES"]["MAX"]["HTML_VALUE"]?>" 
                                    size="5" 
                                />
                                 <?=$arResult["ITEMS"]["763"]['UNITS']?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="kombox-clear"></div>
            </li>
            <?elseif(count($arResult["ITEMS"]["763"]["VALUES"]) > 1):?>
            <li class="lvl1<?if($arResult["ITEMS"]["763"]["CLOSED"]):?> closed<?endif;?> select_right" data-id="<?echo $arResult["ITEMS"]["763"]["CODE_ALT"].'-'.$arResult["ITEMS"]["763"]["ID"]?>">
                <div class="kombox-filter-property-head kombox-middle">
                    <?if(strlen($arResult["ITEMS"]["763"]['HINT'])):?>
                    <span class="kombox-filter-property-hint"></span>
                    <div class="kombox-filter-property-hint-text"><?echo $arResult["ITEMS"]["763"]['HINT']?></div>
                    <?endif;?>
                    <span class="kombox-filter-property-name"><?echo $arResult["ITEMS"]["763"]["NAME"]?></span>   
                </div>
                <div class="solo_brand"><?=(int)$arResult["ITEMS"]["763"]["VALUES"]["MIN"]["VALUE"];?></div>
            </li> 
            <?else:?>
            <li class="lvl1<?if($arResult["ITEMS"]["763"]["CLOSED"]):?> closed<?endif;?> select_right" data-id="<?echo $arResult["ITEMS"]["763"]["CODE_ALT"].'-'.$arResult["ITEMS"]["763"]["ID"]?>">
                <div class="kombox-filter-property-head kombox-middle">
                    <?if(strlen($arResult["ITEMS"]["763"]['HINT'])):?>
                    <span class="kombox-filter-property-hint"></span>
                    <div class="kombox-filter-property-hint-text"><?echo $arResult["ITEMS"]["763"]['HINT']?></div>
                    <?endif;?>
                    <span class="kombox-filter-property-name"><?echo $arResult["ITEMS"]["763"]["NAME"]?></span>   
                </div>
                <?foreach($arResult["ITEMS"]["763"]["VALUES"] as $val => $ar):?>    
                        <div class="solo_brand"><?=(int)$ar["VALUE"];?></div>
                <?endforeach;?>
                <div class="kombox-clear"></div>
            </li>
            <?endif;?>
        <?endif;?>
        <?if(($arResult["ITEMS"]["1212"]["CODE_ALT"] == "pto_tip_privoda") && (!empty($arResult["ITEMS"]["1212"]["VALUES"]))):?>
        <li class="lvl1<?if($arResult["ITEMS"]["1212"]["CLOSED"]):?> closed<?endif;?> privod" data-id="<?echo $arResult["ITEMS"]["1212"]["CODE_ALT"].'-'.$arResult["ITEMS"]["1212"]["ID"]?>">
                <div class="kombox-filter-property-head kombox-middle" style="float: left; padding: 10px 10px 5px 5px;">
                    <?if(strlen($arResult["ITEMS"]["1212"]['HINT'])):?>
                    <span class="kombox-filter-property-hint"></span>
                    <div class="kombox-filter-property-hint-text"><?echo $arResult["ITEMS"]["1212"]['HINT']?></div>
                    <?endif;?>
                    <span class="kombox-filter-property-name"><?echo $arResult["ITEMS"]["1212"]["NAME"]?>:</span>   
                </div>
                <div class="kombox-combo" data-name="<?=$arResult["ITEMS"]["1212"]["CODE_ALT"]?>" style="float:right; width:600px; padding: 0;">                
                    <ul>
                        <?foreach($arResult["ITEMS"]["1212"]["VALUES"] as $val => $ar):?>
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
                            <?if(!$arResult["ITEMS"]["1212"]["ID"]):?>
                            <label for="<?=$ar["CONTROL_ID"]?>" class="label_checked checkbox_off"><font style="padding-top: 8px;"><?echo $ar["VALUE"];?></font> <span style="padding-top: 8px; display: none;">(<?echo $arResult["section_filter"]["CNT"];?>)</span></label>
                            <?else:?>
                            <label for="<?=$ar["CONTROL_ID"]?>" class="label_checked checkbox_off"><font style="padding-top: 8px;"><?echo $ar["VALUE"];?></font> <span style="padding-top: 8px; display: none;">(<?echo $ar["CNT"];?>)</span></label>
                            <?endif?>
                        </li>
                        <?endforeach;?>
                    </ul> 
                <div class="kombox-clear"></div>
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