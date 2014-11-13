<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if(!isset($arParams["AJAX"])):?>
    <div class="catalog-filter">
        <div class="catalog-params">
<?endif;?>
            <?if(!empty($arResult["TYPE"])):?>
                <div class="field">
                    <label>Тип техники:</label>
                    <select name="type">
                        <?foreach($arResult["TYPE"] as $key_type => $arType):?>
                            <option id="<?=$key_type?>" value="<?=$arType["SECTION_PAGE_URL"]?>"<?if($key_type == $arResult["SELECTED_TYPE"]):?> selected="selected"<?endif;?>><?=$arType["NAME"]?></option>
                        <?endforeach;?>
                    </select>
                </div>
            <?endif;?>
            <?if(!empty($arResult["VIEW"])):?>
                <div class="field last">
                    <label>Вид техники:</label>
                    <select name="view">
                        <option id="0" value="<?=$arResult["TYPE"][$arResult["SELECTED_TYPE"]]["SECTION_PAGE_URL"]?>" <?if(!isset($arResult["SELECTED_VIEW"])):?>selected="selected"<?endif;?>>Все</option>
                        <?foreach($arResult["VIEW"] as $key_view => $arView):?>
                            <?if($arView["IBLOCK_SECTION_ID"] == $arResult["SELECTED_TYPE"]):?>
                                <option id="<?=$key_view?>" value="<?=$arView["SECTION_PAGE_URL"]?>"<?if(isset($arResult["SELECTED_VIEW"]) && $key_view == $arResult["SELECTED_VIEW"]):?> selected="selected"<?endif;?>><?=$arView["NAME"]?></option>
                            <?endif;?>
                        <?endforeach;?>
                    </select>
                </div>
            <?endif;?>
            <div class="clear"></div>
            <?$count_brand = COption::GetOptionInt("ust", "catalog_filter_count_brand");?>
            <?if(!empty($arResult["VIEW"]) && !empty($arResult["BRANDS"])):?>    
                <div class="field last brands">
                    <label class="name">Бренд:</label>
                    <?if(count($arResult["BRANDS"]) > 1 && isset($count_brand) && (count($arResult["BRANDS"]) <= $count_brand)):?>
                        <?foreach($arResult["BRANDS"] as $key_brand => $arBrand):?>
                            <label class="checkbox label_check">
                                <input type="checkbox" name="brand[]" value="<?=$key_brand?>" <?if(in_array($key_brand, $arResult["SELECTED_BRAND"])):?>checked="checked"<?endif;?>><?=$arBrand["NAME"]?>
                            </label>
                        <?endforeach;?>
                    <?elseif(count($arResult["BRANDS"]) == 1):?>
                        <label class="checkbox">
                            <?=$arResult["BRANDS"][key($arResult["BRANDS"])]["NAME"]?>
                        </label>
                    <?else:?>
                        <select name="brand">
                            <option id="0" value="0" <?if(!isset($arResult["SELECTED_BRAND"])):?>selected="selected"<?endif;?>>Все</option>
                            <?foreach($arResult["BRANDS"] as $key_brand => $arBrand):?>
                                <option id="<?=$key_brand?>" value="<?=$key_brand?>" <?if(in_array($key_brand, $arResult["SELECTED_BRAND"])):?> selected="selected"<?endif;?>><?=$arBrand["NAME"]?></option>
                            <?endforeach;?>
                        </select>
                    <?endif;?>
                </div>
            <?endif;?>
            <div class="clear"></div>
<?if(!isset($arParams["AJAX"])):?>
        </div>
        <div id="chosen"></div>
        <button type="submit" class="right" name="apply" value="ok">Подобрать</button>
        <div class="clear"></div>
    </div>
<?endif;?>