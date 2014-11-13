<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// echo "<pre>";
// print_r($arResult);
// echo "</pre>";
if(count($arResult['ELEMENTS']) > 0 && $arResult["ITEMS_COUNT_SHOW"]): 
$arParams['MESSAGE_ALIGN'] = isset($arParams['MESSAGE_ALIGN']) ? $arParams['MESSAGE_ALIGN'] : 'LEFT';
$arParams['MESSAGE_TIME'] = intval($arParams['MESSAGE_TIME']) >= 0 ? intval($arParams['MESSAGE_TIME']) : 5;

CJSCore::Init(array("popup"));
//$APPLICATION->AddHeadScript("/bitrix/js/kombox/filter/ion.rangeSlider.js");
//$APPLICATION->AddHeadScript("/bitrix/js/kombox/filter/jquery.cookie.js");
$cntClosedProperty = 0;
?>
<?  /*
$FILTER_ID_SECTIONS = "S";
 

if(isset($_REQUEST["ajax"]) && $_REQUEST["ajax"] === "y")
    $_CHECK = &$_REQUEST;
elseif(isset($_REQUEST["del_filter"]))
    $_CHECK = array();
elseif(isset($_GET["set_filter"]))
    $_CHECK = &$_GET;
elseif($arParams["SAVE_IN_SESSION"] && isset($_SESSION[$component->FILTER_NAME][$component->SECTION_ID]))
    $_CHECK = $_SESSION[$component->FILTER_NAME][$component->SECTION_ID];
else
    $_CHECK = array();
 
$rs_section = CIBlockSection::GetList(
    array(),
    array("IBLOCK_ID"=>$component->IBLOCK_ID, "SECTION_ID"=>$component->SECTION_ID),
    false, array()
);
if($rs_section->SelectedRowsCount() > 0) {
    $ar_section = $rs_section->GetNext();
 
    $ar_section_root = CIBlockSection::GetList(
        array(),
        array("IBLOCK_ID"=>$component->IBLOCK_ID, "ID"=>$ar_section["IBLOCK_SECTION_ID"]),
        false, array()
    )->GetNext();
 
    $ar_filter = array_merge(
        array(
            "IBLOCK_ID" => $component->IBLOCK_ID,
        ),
        $ar_section["IBLOCK_SECTION_ID"] ?
        array(
            ">LEFT_MARGIN"  => $ar_section_root["LEFT_MARGIN"],
            "<RIGHT_MARGIN" => $ar_section_root["RIGHT_MARGIN"],
            ">DEPTH_LEVEL" => $ar_section_root["DEPTH_LEVEL"],
        ) : array()
    );
    $rs_sections = CIBlockSection::GetList(array("left_margin"=>"asc"), $ar_filter, false, array());
 
    if($rs_sections->SelectedRowsCount() > 0) {
        $data = array();
        $data["NAME"] = "Вид техники";
        $data["PROPERTY_TYPE"] = "E";
        $select_depth = false;
        while($_ar_section = $rs_sections->GetNext()) {
            $name = htmlspecialcharsbx($component->FILTER_NAME."_".$FILTER_ID_SECTIONS."_".abs(crc32($_ar_section["ID"])));
            $checked = isset($_CHECK[$name]) && $_CHECK[$name];
 
            // включаем в фильтр вложенные секции
            if($select_depth !== false) {
                if($_ar_section["DEPTH_LEVEL"] > $select_depth) {
                    // $checked = true;
                    $section_ids[] = $_ar_section["ID"];
                } else {
                    $select_depth = false;
                }
            }
 
            if($checked) {
                if(!in_array($_ar_section["ID"], $section_ids)) {
                    $section_ids[] = $_ar_section["ID"];
                    $select_depth = $_ar_section["DEPTH_LEVEL"];
                }
            }
            $value = array(
                "FILTER_SECTION_ID" => $_ar_section["ID"],
                "DISABLED" => false,
                "CONTROL_ID"   => $name,
                "CONTROL_NAME" => $name,
                "HTML_VALUE" => "Y", //$_ar_section["ID"],
                "VALUE" => $_ar_section["NAME"],
                "CHECKED" => $checked,
                "DEPTH_LEVEL" => $_ar_section["DEPTH_LEVEL"] - $ar_section["DEPTH_LEVEL"],
            );
            $data["VALUES"][] = $value;
        }
        array_unshift($arResult["ITEMS"], $data);
    }
}
// удаляем скрытые инпуты выбранных секций (их не должно быть)
foreach($arResult["HIDDEN"] as $arKey => $arItem) {
    if(strpos($arItem["CONTROL_ID"], $component->FILTER_NAME."_".$FILTER_ID_SECTIONS) === 0) {
        unset($arResult["HIDDEN"][$arKey]);
    }
}
 
// добавляем правило в фильтр товаров (id'шники выбранных секции)
global ${$component->FILTER_NAME};
$gFilter = &${$component->FILTER_NAME};
if(!empty($section_ids)) {
    $gFilter[] = array(
        "LOGIC" => "AND",
        "SECTION_ID" => $section_ids
    );
}
*/?>
<?/*$arResult["FORM_NEW_ACTION"] = explode("/", $arResult["FORM_ACTION"]);
$arResult["NEW_FORM_ACTION"] = array_splice($arResult["FORM_NEW_ACTION"], -1);
$arResult["FORM_ACTION"] = implode("/", $arResult["FORM_NEW_ACTION"]);*/
?>
 
<?//echo "<pre>";
//print_r($_REQUEST["UF_TEMPLATE"]);
//echo "</pre>";?>
<?//$arResult["FORM_ACTION"] = $_REQUEST["THIS_SECTION"];?> 
<?//echo "<pre>";
//print_r($arResult["parent_section_id"]);
//echo "</pre>";?>
<?//unset($_POST);?>
<div class="kombox-filter" id="kombox-filter">
    <form name="<?echo $arParams["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get"<?if($arResult['IS_SEF']):?> data-sef="yes"<?endif;?>>
        <script>
            var komboxSmartFilter = new KomboxSmartFilter('<?echo $arResult["FORM_ACTION"]?>', '<?echo $arParams['MESSAGE_ALIGN']?>', <?echo $arParams['MESSAGE_TIME']?>);
        </script>
        <input type="hidden" name="iblock_id" value="54" id="iblock_id" />
        <?foreach($arResult["HIDDEN"] as $arItem):?>
            <input
                type="hidden"
                name="<?echo $arItem["CONTROL_NAME"]?>"
                id="<?echo $arItem["CONTROL_ID"]?>"
                value="<?echo $arItem["HTML_VALUE"]?>"
            />
        <?endforeach;?>
        <ul> 
        <?//echo "<pre>";
        //print_r($arResult["ITEMS"]);
        //echo "</pre>";?>
        <?/*?>
        <?if(!empty($arResult["section_filter"])):?> 
        <li class="lvl1 select_left">
                <div class="kombox-filter-property-head">
                    <span class="kombox-filter-property-name">Тип техники</span>
                    <span class="for_modef"></span>
                </div> 
         
                <div style="padding: 5px 0 0 15px;">
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
        
        <div class="kombox-clear">&nbsp;</div>
        </li>
        <?endif?>
        <?*/?>
        <?if(!empty($arResult["section_filtered"])):?>
                <li class="lvl1 select_left">
                <div class="kombox-filter-property-head">
                    <span class="kombox-filter-property-name">Тип техники:</span>
                    <span class="for_modef1"></span>
                </div> 
          
                <div style="padding: 5px 0 0 15px;">
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
        
        <?/*?><div class="kombox-clear">&nbsp;</div><?*/?>
        </li>
        <?endif?>
        <?/*?><div class="kombox-clear"></div><?*/?>
        <?$element_conter = 0;?>  
        <?foreach($arResult["ITEMS"] as $arItem):?>
        <?if(isset($arItem["PRICE"])) continue;?>
        <?if($arItem["CLOSED"])$cntClosedProperty++;?>
        <?if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"])):?>
        <?
             if($arItem["VALUES"]["MAX"]["VALUE"] <= $arItem["VALUES"]["MIN"]["VALUE"]):
                $arItem["VALUES"]["MAX"]["VALUE"] = 100;
                $arItem["VALUES"]["MIN"]["VALUE"] = 0;
        ?>
            <script>
                $(document).ready(function(){
                    $(".irs-slider").hide();
                });
            </script>
        <?endif;?>
            <?if(isset($arItem["VALUES"]["MIN"]["VALUE"]) && isset($arItem["VALUES"]["MAX"]["VALUE"]) && $arItem["VALUES"]["MAX"]["VALUE"] > $arItem["VALUES"]["MIN"]["VALUE"]):?>
            <li class="lvl1<?if($arItem["CLOSED"]):?> closed<?endif;?> <?if($element_conter%2):?>select_left<?else:?>select_right<?endif?>" data-id="<?echo $arItem["CODE_ALT"].'-'.$arItem["ID"]?>">
                <div class="kombox-filter-property-head kombox-middle">
                    <?if(strlen($arItem['HINT'])):?>
                    <span class="kombox-filter-property-hint"></span>
                    <div class="kombox-filter-property-hint-text"><?echo $arItem['HINT']?></div>
                    <?endif;?>
                    <span class="kombox-filter-property-name"><?echo $arItem["NAME"]?>:</span>
                    <span class="for_modef1"></span>    
                </div>
                <div class="kombox-num" data-name="<?=$arItem["CODE_ALT"]?>">
                    <table>
                        <tr>
                            <?
                            $minValue = !empty($arItem["VALUES"]["MIN"]["HTML_VALUE"]) ? $arItem["VALUES"]["MIN"]["HTML_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"];
                            $maxValue = !empty($arItem["VALUES"]["MAX"]["HTML_VALUE"]) ? $arItem["VALUES"]["MAX"]["HTML_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"];
                            ?>
                            <td class="kombox-from">
                                <?//echo GetMessage("KOMBOX_CMP_FILTER_FROM")?> 
                                <input 
                                    class="kombox-input kombox-num-from" 
                                    type="hidden" 
                                    name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" 
                                    id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>" 
                                    value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>" 
                                    size="5" 
                                />
                            </td>
                            <td class="kombox-range"> 
                                <div  
                                    data-value="<?echo $minValue?>;<?=$maxValue?>" 
                                    data-min="<?echo $arItem["VALUES"]["MIN"]["VALUE"]?>" 
                                    data-max="<?echo $arItem["VALUES"]["MAX"]["VALUE"]?>" 
                                    data-range-from="<?echo $arItem["VALUES"]["MIN"]["RANGE_VALUE"]?>" 
                                    data-range-to="<?echo $arItem["VALUES"]["MAX"]["RANGE_VALUE"]?>" 
                                >
                                </div>
                            </td>
                            <td class="kombox-to">
                                <?//echo GetMessage("KOMBOX_CMP_FILTER_TO")?>  
                                <input 
                                    class="kombox-input kombox-num-to" 
                                    type="hidden" 
                                    name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" 
                                    id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>" 
                                    value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>" 
                                    size="5" 
                                />
                                 <?=$arItem['UNITS']?>
                            </td>
                        </tr>
                    </table>

                </div>
                <div class="kombox-clear"></div>
            </li>
            <?endif;?>    
        <?elseif(!empty($arItem["VALUES"]) && !isset($arItem["PRICE"])):?>
            <li class="lvl1<?if($arItem["CLOSED"]):?> closed<?endif;?> <?if($element_conter%2):?>select_left<?else:?>select_right<?endif?>" data-id="<?echo $arItem["CODE_ALT"].'-'.$arItem["ID"]?>">
                <div class="kombox-filter-property-head">
                    <?if(strlen($arItem['HINT'])):?>
                    <span class="kombox-filter-property-hint"></span>
                    <div class="kombox-filter-property-hint-text"><?echo $arItem['HINT']?></div>
                    <?endif;?>
                    <span class="kombox-filter-property-name"><?echo $arItem["NAME"]?>:</span>
                    <span class="for_modef1"></span>
                </div>
                <?/*if(!$arItem["ID"]):?> 
                <div style="padding: 5px 0 0 15px;">
                    <select class="kombox-section" style="width: 324px; padding: 5px 0 0 15px;">
                        <option id="0" value="0" selected>Все</option>
                    <ul>
                        <?foreach($arItem["VALUES"] as $val => $ar):?>
                        <li class="<?echo $ar["DISABLED"]? 'kombox-disabled ': ''?><?echo $ar["CHECKED"]? 'kombox-checked': ''?>">                               
                                <option class="kombox-section-option" for="<?=$ar["CONTROL_ID"]?>" style="width: 324px;" id="<?="section"."_".$ar["FILTER_SECTION_ID"]?>" value="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?></option>
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
                </div>     */?>
                <?//echo '<pre>'; print_r($arItem); echo '</pre>';?>
                <?/*if((count($arItem["VALUES"]) == 1) && ($arItem["CODE_ALT"] == "brand")):?>  
                    <?foreach($arItem["VALUES"] as $val => $ar):?>    
                        <div class="solo_brand"><?=$ar["VALUE"];?></div>
                    <?endforeach;*/?>    
                <?if($arItem["CODE_ALT"] == "brand"):?>
                <div style="padding: 5px 0 0 15px;">
                    <select name="<?=$arItem["CODE_ALT"]?>" class="kombox-select" style="width: 324px; padding: 5px 0 0 15px;">
                        <option id="0" class="kombox_option" value="0">Все</option>
                    <ul>
                        <?foreach($arItem["VALUES"] as $val => $ar):?>
                        <li class="<?echo $ar["DISABLED"]? 'kombox-disabled ': ''?><?echo $ar["CHECKED"]? 'kombox-checked': ''?>">                               
                            <option class="kombox_option" for="<?=$ar["CONTROL_ID"]?>" style="width: 324px;" id="<?=$ar["CONTROL_NAME_ALT"];?>" value="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?></option>
                            <?/*
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
                            <label for="<?=$ar["CONTROL_ID"]?>" class="checkbox label_checked checkbox_off"><font><?echo $ar["VALUE"];?></font> <span>(<?echo $ar["CNT"];?>)</span></label>
                            */?>
                        </li>
                        <?endforeach;?>
                    </ul>
                    </select>
                
                    <div style="display: none; " class="kombox-input-check">
                        <ul>
                            <?foreach($arItem["VALUES"] as $val => $ar):?>
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
                <?elseif((count($arItem["VALUES"]) > 0)):?>
                <div style="padding: 5px 0 0 15px;">
                    <select name="<?=$arItem["CODE_ALT"]?>" class="kombox-select-element" style="width: 324px; padding: 5px 0 0 15px;">
                        <option id="0" class="kombox_option-element" value="0">Все</option>
                    <ul>
                        <?foreach($arItem["VALUES"] as $val => $ar):?>
                        <li class="<?echo $ar["DISABLED"]? 'kombox-disabled ': ''?><?echo $ar["CHECKED"]? 'kombox-checked': ''?>">                               
                            <option class="kombox_option-element" for="<?=$ar["CONTROL_ID"]?>" style="width: 324px;" id="<?=$ar["CONTROL_NAME_ALT"];?>" value="<?=$ar["VALUE"];?>" <?echo $ar["CHECKED"]? 'selected="selected"': ''?>><?=$ar["VALUE"];?></option>
                        </li>
                        <?endforeach;?>
                    </ul>
                    </select>
                
                    <div style="display: none; " class="kombox-input-check-element">
                        <ul>
                            <?foreach($arItem["VALUES"] as $val => $ar):?>
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
                <?else:?> 
                <div class="kombox-combo" data-name="<?=$arItem["CODE_ALT"]?>">                
                    <ul>
                        <?foreach($arItem["VALUES"] as $val => $ar):?>
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
                            <?if(!$arItem["ID"]):?>
                            <label for="<?=$ar["CONTROL_ID"]?>" class="label_checked checkbox_off"><font><?echo $ar["VALUE"];?></font> <span>(<?echo $arResult["section_filter"]["CNT"];?>)</span></label>
                            <?else:?>
                            <label for="<?=$ar["CONTROL_ID"]?>" class="label_checked checkbox_off"><font><?echo $ar["VALUE"];?></font> <span>(<?echo $ar["CNT"];?>)</span></label>
                            <?endif?>
                            <?/*<label for="<?echo $ar["CONTROL_ID"]?>"><?echo $ar["VALUE"];?> <span>(<?echo $ar["CNT"];?>)</span></label>*/?>
                        </li>
                        <?endforeach;?>
                    </ul>
                </div> 
                <?endif?>
                
                <div class="kombox-clear">&nbsp;</div>
            </li>
        <?endif;?>
        <?$element_conter++?>
        <?endforeach;?>
        <div class="kombox-clear"></div>  
            <li>
                <div class="kombox-filter-show-properties kombox-show" style="display: none;">
                    <?if($cntClosedProperty):?>
                    <a href=""><span class="kombox-show"><?=GetMessage("KOMBOX_CMP_FILTER_SHOW_FILTER")?></span><span class="kombox-hide"><?=GetMessage("KOMBOX_CMP_FILTER_HIDE_FILTER")?></span></a>
                    <?endif;?>
                </div>
                <div id="modef" class="elements_finded">
                    <strong><?echo GetMessage("KOMBOX_CMP_FILTER_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?></strong>
                    <a href="<?echo $arResult["FILTER_URL"]?>"><br><div id="sort_button_div"><?echo GetMessage("KOMBOX_CMP_FILTER_FILTER_SHOW")?></div></a>
                    <span class="ecke"></span>
                </div> 
                 <?if($arResult['SET_FILTER']):?>
                      <div class="kombox-clear"></div>  
                     <a href="<?=$arResult["DELETE_URL"]?>" class="kombox-del-filter"><?=GetMessage("KOMBOX_CMP_FILTER_DEL_FILTER")?></a> 
                <?endif;?>
                <div class="sort_count" id="modef" style="display: none;">
                    <?echo GetMessage("KOMBOX_CMP_FILTER_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
                </div>
                
                <div id="sort_button_div" style="display: none;">
                     <input type="submit" id="sort_button" name="set_filter" value="<?=GetMessage("KOMBOX_CMP_FILTER_SET_FILTER")?>"/>
                     <?if($arResult['SET_FILTER']):?>
                      <div class="kombox-clear"></div>  
                     <a href="<?=$arResult["DELETE_URL"]?>" class="kombox-del-filter"><?=GetMessage("KOMBOX_CMP_FILTER_DEL_FILTER")?></a> 
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
        <div class="modef" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?>>
            <div class="modef-wrap">
                <?echo GetMessage("KOMBOX_CMP_FILTER_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
                <a href="<?echo $arResult["FILTER_URL"]?>"><?echo GetMessage("KOMBOX_CMP_FILTER_FILTER_SHOW")?></a>
                <span class="ecke"></span>
            </div>
        </div>
    </form>
    <div class="kombox-loading"></div>
</div>
<?endif;?>
<!--<div id="chosen"></div>-->