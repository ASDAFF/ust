<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//echo "111<pre>"; print_r($arResult["ITEMS"]); echo "</pre>";?>
<script type="text/javascript" src="/bitrix/components/areal/catalog.compare.result/templates/.default/compare.jquery.js"></script>
<script type="text/javascript" src="/bitrix/components/areal/catalog.compare.result/templates/.default/jquery.jscrollpane.min.js"></script>
<script type="text/javascript" src="/bitrix/components/areal/catalog.compare.result/templates/.default/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/bitrix/components/areal/catalog.compare.result/templates/.default/mwheelIntent.js"></script>
<link rel="stylesheet" type="text/css" href="/bitrix/components/areal/catalog.compare.result/templates/.default/jquery.jscrollpane.css">

<script>
$(function () {
    $('td.0').attr('width','210px');
    $('th.0').attr('width','210px');    
});
</script>
<div class="compare">
    <ul class="navigation">
    <?foreach($arResult["IBLOCK_SECTION_CUST"] as $arItem):?>
        <li>
           <a name="compare_table" class="compare_table_a" href="#<?=$arItem["ID"]?>" ><?=$arItem["NAME"]?></a>
        </li>
        <?endforeach?>
    </ul>

<?foreach($arResult["IBLOCK_SECTION_ID"] as $arId):?>
    <div class="compare-table" id="<?=$arId?>" style="">
        <!--noindex-->
            <p class="p-chars">
                <?if($arResult["DIFFERENT"]):?>
                    <a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("DIFFERENT=N",array("DIFFERENT")))?>" rel="nofollow"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></a>
                <?else:?>
                    <?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?>
                <?endif?>
                &nbsp;|&nbsp;
                
                <?if(!$arResult["DIFFERENT"]):?>
                    <a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("DIFFERENT=Y",array("DIFFERENT")))?>" rel="nofollow"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></a>
                <?else:?>
                    <?=GetMessage("CATALOG_ONLY_DIFFERENT")?>
                <?endif?>
            </p>
        <!--/noindex-->
        <?if(!empty($arResult["DELETED_PROPERTIES"]) || !empty($arResult["DELETED_OFFER_FIELDS"]) || !empty($arResult["DELETED_OFFER_PROPS"])):?>
            <!--noindex-->
                <p>
                    <?=GetMessage("CATALOG_REMOVED_FEATURES")?>:
                    <?foreach($arResult["DELETED_PROPERTIES"] as $arProperty):?>
                        <a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=ADD_FEATURE&pr_code=".$arProperty["CODE"],array("op_code","of_code","pr_code","action")))?>" rel="nofollow"><?=$arProperty["NAME"]?></a>
                    <?endforeach?>
                    
                    <?foreach($arResult["DELETED_OFFER_FIELDS"] as $code):?>
                        <a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=ADD_FEATURE&of_code=".$code,array("op_code","of_code","pr_code","action")))?>" rel="nofollow"><?=GetMessage("IBLOCK_FIELD_".$code)?></a>
                    <?endforeach?>
                    
                    <?foreach($arResult["DELETED_OFFER_PROPERTIES"] as $arProperty):?>
                        <a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=ADD_FEATURE&op_code=".$arProperty["CODE"],array("op_code","of_code","pr_code","action")))?>" rel="nofollow"><?=$arProperty["NAME"]?></a>
                    <?endforeach?>
                </p>
            <!--/noindex-->
        <?endif?>
        <?if(count($arResult["SHOW_PROPERTIES"])>0):?>
            <p>
                <form action="<?=$APPLICATION->GetCurPage()?>" method="get" style="display: none;">
                    <?=GetMessage("CATALOG_REMOVE_FEATURES")?>:
                    <br />
                    <?foreach($arResult["SHOW_PROPERTIES"][$arId] as $arProperty):?>
                        <input type="checkbox" name="pr_code[]" value="<?=$arProperty["CODE"]?>" /><?=$arProperty["NAME"]?>
                        <br />
                    <?endforeach?>
                    
                    <?foreach($arResult["SHOW_OFFER_FIELDS"][$arId] as $code):?>
                        <input type="checkbox" name="of_code[]" value="<?=$code?>" /><?=GetMessage("IBLOCK_FIELD_".$code)?>
                        <br />
                    <?endforeach?>
                    
                    <?foreach($arResult["SHOW_OFFER_PROPERTIES"][$arId] as $arProperty):?>
                        <input type="checkbox" name="op_code[]" value="<?=$arProperty["CODE"]?>" /><?=$arProperty["NAME"]?>
                        <br />
                    <?endforeach?>
                    
                    <input type="hidden" name="action" value="DELETE_FEATURE" />
                    <input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
                    <input type="submit" value="<?=GetMessage("CATALOG_REMOVE_FEATURES")?>">
                </form>
            </p>
        <?endif?>
    <br />
    <form action="<?=$APPLICATION->GetCurPage()?>" method="get" class="data-form <?=$arId?>">
        <?//foreach($arResult["IBLOCK_SECTION_ID"] as $arId):?>
            <table class="data-table" cellspacing="0" cellpadding="0" border="0">
                <thead>
                <tr>
                        <th valign="top" class="first-col">Фото</th>
                        <?$count = count($arResult["ITEMS"][$arId]);?>
                        <?$i=0;?>
                        <?foreach($arResult["ITEMS"][$arId] as $arElement):?>
                        <td valign="top" class="<?=$i?>" id="white">
                            <div class="div_compare">
                                <div class="comparison comparison_<?=$i?>"><a title="Удалить из сравнения" id="<?=$arElement["ID"]?>" href="#" class="comparis del disable"></a></div>
                                <div height = "90" class="top_img"><img src="<?=$arElement["PICTURE"]["SRC"]?>" alt="" height="90"></div>
                                <button class="order order_<?=$i?>" name="ordering">Заказать</button>
                                <input value="model" name="type_element" type="hidden">
                                <input class="element" type="hidden" value="<?=$arElement["PROPERTIES"]["SHORT_NAME"]["VALUE"]?>" name="element_<?=$arElement["ID"]?>">
                            </div>
                        </td>
                            <?//echo "111<pre>"; print_r($arElement); echo "</pre>";?>
                        <?$i++;?>
                        <?endforeach?>    
                    </tr>
<!--                    <tr>
                        <td valign="top" >&nbsp;<a href="ust.flash-of-genius.ru/catalog/"><< вернуться в каталог</a></td>
                        
                        <?foreach($arResult["ITEMS"][$arId] as $arElement):?>
                            <td valign="top" width="<?=round(100/count($arResult["ITEMS"][$arId]))?>%">
                                <input type="checkbox" name="ID[]" value="<?=$arElement["ID"]?>" />
                                <!--<div class="comparison"><a title="Сравнение" id="<?=$arElement["ID"]?>" href="#" class="comparis del disable">Убрать из сравнения</a></div>
                                
                            </td>
                        <?endforeach?>    
                    </tr>-->
                    
                    <?foreach($arResult["ITEMS"][$arId]["FIELDS"] as $code=>$field):?>
                        <tr>
                            <th valign="top" >
                                <?=GetMessage("IBLOCK_FIELD_".$code)?>
                            </th>
                            <?foreach($arResult["ITEMS"][$arId] as $arElement):?>
                                <td valign="top">
                                    <?switch($code):
                                        case "NAME":?>
                                            <a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement[$code]?></a>
                                            <?if($arElement["CAN_BUY"]):?>
                                                <!--noindex-->
                                                    <br />
                                                    <a href="<?=$arElement["BUY_URL"]?>" rel="nofollow"><?=GetMessage("CATALOG_COMPARE_BUY"); ?></a>
                                                <!--/noindex-->
                                            <?elseif((count($arResult["PRICES"]) > 0) || is_array($arElement["PRICE_MATRIX"])):?>
                                            <br />
                                            <?=GetMessage("CATALOG_NOT_AVAILABLE")?>
                                            <?endif;
                                        break;
                                        case "PREVIEW_PICTURE":
                                        case "DETAIL_PICTURE":
                                            if(is_array($arElement["FIELDS"][$code])):?>
                                                <a href="<?=$arElement["DETAIL_PAGE_URL"]?>">
                                                    <img
                                                        border="0"
                                                        src="<?=$arElement["FIELDS"][$code]["SRC"]?>"
                                                        width="<?=$arElement["FIELDS"][$code]["WIDTH"]?>"
                                                        height="<?=$arElement["FIELDS"][$code]["HEIGHT"]?>"
                                                        alt="<?=$arElement["FIELDS"][$code]["ALT"]?>"
                                                        title="<?=$arElement["FIELDS"][$code]["TITLE"]?>"
                                                    />
                                                </a>
                                            <?endif;
                                        break;
                                        default:
                                            echo $arElement["FIELDS"][$code];
                                            break;
                                    endswitch;?>
                                </td>
                            <?endforeach?>
                        </tr>
                    <?endforeach;?>
                </thead>
                    <tr>
                            <?$i = 0;?>
                            <?foreach($arResult["ITEMS"][$arId]["PRICES"] as $code=>$arPrice):?>
                                <?if($arPrice["CAN_ACCESS"]):?>
                                    <tr>
                                        <th valign="top" >
                                            <?=$arResult["PRICES"][$code]["TITLE"]?>
                                        </th>
                                        <?foreach($arResult["ITEMS"][$arId] as $arElement):?>
                                            <td valign="top" class="<?=$i?>">
                                                <?if($arElement["PRICES"][$code]["CAN_ACCESS"]):?>
                                                    <b><?=$arElement["PRICES"][$code]["PRINT_DISCOUNT_VALUE"]?></b>
                                                <?endif;?>
                                            </td>
                                        <?endforeach?>
                                    </tr>
                                <?endif;?>
                            <?endforeach;?>
                            
                            <?foreach($arResult["SHOW_PROPERTIES"][$arId] as $code=>$arProperty):
                                $arCompare = Array();
                                foreach($arResult["ITEMS"][$arId] as $arElement)
                                {
                                    $arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
                                    if(is_array($arPropertyValue))
                                    {
                                        sort($arPropertyValue);
                                        $arPropertyValue = implode(" / ", $arPropertyValue);
                                    }
                                    $arCompare[] = $arPropertyValue;
                                }
                                $diff = (count(array_unique($arCompare)) > 1 ? true : false);
                                if($diff || !$arResult["DIFFERENT"]):?>
                                    <tr>
                                        <th valign="top" class="first-col" >
                                            <?=$arProperty["NAME"]?>
                                        </th>
                                        <?$i = 0;?>
                                        <?foreach($arResult["ITEMS"][$arId] as $arElement):?>
                                            <?if($diff):?>
                                                <td valign="top" class="<?=$i?>">
                                                    <?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
                                                </td>
                                            <?else:?>
                                                <th valign="top" class="<?=$i?>">
                                                    <?=(is_array($arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
                                                </th>
                                            <?endif?>
                                        <?$i++;?>
                                        <?endforeach?>
                                    </tr>
                                <?endif?>
                            <?endforeach;?>

                            <?foreach($arResult["SHOW_OFFER_FIELDS"] as $code):
                                $arCompare = Array();
                                foreach($arResult["ITEMS"][$arId] as $arElement)
                                {
                                    $Value = $arElement["OFFER_FIELDS"][$code];
                                    if(is_array($Value))
                                    {
                                        sort($Value);
                                        $Value = implode(" / ", $Value);
                                    }
                                    $arCompare[] = $Value;
                                }
                                $diff = (count(array_unique($arCompare)) > 1 ? true : false);
                                if($diff || !$arResult["DIFFERENT"]):?>
                                    <tr>
                                        <th valign="top" >
                                            &nbsp;<?=GetMessage("IBLOCK_FIELD_".$code)?>
                                        </th>
                                        <?foreach($arResult["ITEMS"][$arId] as $arElement):?>
                                            <?if($diff):?>
                                                <td valign="top">
                                                    <?=(is_array($arElement["OFFER_FIELDS"][$code])? implode("/ ", $arElement["OFFER_FIELDS"][$code]): $arElement["OFFER_FIELDS"][$code])?>
                                                </td>
                                            <?else:?>
                                                <th valign="top">
                                                    <?=(is_array($arElement["OFFER_FIELDS"][$code])? implode("/ ", $arElement["OFFER_FIELDS"][$code]): $arElement["OFFER_FIELDS"][$code])?>
                                                </th>
                                            <?endif?>
                                        <?endforeach?>
                                    </tr>
                                <?endif?>
                            <?endforeach;?>
                            
                            <?foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty):
                                $arCompare = Array();
                                foreach($arResult["ITEMS"][$arId] as $arElement)
                                {
                                    $arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
                                    if(is_array($arPropertyValue))
                                    {
                                        sort($arPropertyValue);
                                        $arPropertyValue = implode(" / ", $arPropertyValue);
                                    }
                                    $arCompare[] = $arPropertyValue;
                                }
                                $diff = (count(array_unique($arCompare)) > 1 ? true : false);
                                if($diff || !$arResult["DIFFERENT"]):?>
                                    <tr>
                                        <th valign="top" >
                                            <?=$arProperty["NAME"]?>
                                        </th>
                                        <?foreach($arResult["ITEMS"][$arId] as $arElement):?>
                                            <?if($diff):?>
                                                <td valign="top">
                                                    <?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
                                                </td>
                                            <?else:?>
                                                <th valign="top">
                                                    <?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
                                                </th>
                                            <?endif?>
                                        <?endforeach?>
                                    </tr>
                                <?endif?>
                            <?endforeach;?>
                    </tr>
                                        <tr>
                        <td valign="top" class="first-col white">&nbsp;<a href="/catalog/">&laquo; вернуться в каталог</a></td>
                        <?$i=0;?>
                        <?foreach($arResult["ITEMS"][$arId] as $arElement):?>
                            <td valign="top" width="<?=round(100/count($arResult["ITEMS"][$arId]))?>%" class="white">
                                <input hidden type="checkbox" name="element_<?=$arElement["ID"]?>" id="element_<?=$arElement["ID"]?>" value="<?=$arElement["PROPERTIES"]["SHORT_NAME"]["VALUE"]?>" class="checkbox <?=$i?>" />
                                <label class="chechbox_compare" for="element_<?=$arElement["ID"]?>"></label>
                            </td>
                        <?$i++;?>
                        <?endforeach?>    
                    </tr>
            </table>
        <?//endforeach?>
        <br />
        <!--<input type="submit" value="<?=GetMessage("CATALOG_REMOVE_PRODUCTS")?>" />-->
        <input type="hidden" name="action" value="DELETE_FROM_COMPARE_RESULT" />
        <input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
    </form>
    <button class="order-choosed" name="ordering">Заказать выбранное</button>
    <input value="model" name="type_element" type="hidden">
    </div>
    <!--<br />-->
    <?if(count($arResult["ITEMS_TO_ADD"])>0):?>
    <p>
    <form action="<?=$APPLICATION->GetCurPage()?>" method="get">
        <input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
        <input type="hidden" name="action" value="ADD_TO_COMPARE_RESULT" />
        <select name="id">
        <?foreach($arResult["ITEMS_TO_ADD"] as $ID=>$NAME):?>
            <option value="<?=$ID?>"><?=$NAME?></option>
        <?endforeach?>
        </select>
        <input type="submit" value="<?=GetMessage("CATALOG_ADD_TO_COMPARE_LIST")?>" />
    </form>
    </p>
    <?endif?>
<?endforeach?>
</div>
<div id="order_catalog" class="dialog">
    <form class="protection ajax" action="/catalog/stroitelnaya-tekhnika/ekskavator-john-deere-e-210lc/" method="post" name="order_catalog">
    <div class="title">Форма заказа</div>
    <input type="hidden" value="085aa66349ac0395a55c18fd0d158537" id="sessid" name="sessid">    <input type="hidden" value="orderFormSend" name="FormType">
    <input type="hidden" value="" name="URL">
    <input type="hidden" value="katalog" name="THEME_TYPE">
    <div class="line">
        <label class="top3">ФИО и Название компании<span class="required">*</span></label>
        <input type="text" value="" class="required" name="NAME">
        <div class="clear"></div>
    </div>
    <div class="line">
        <label>Телефон</label>
        <input type="text" value="" class="phone" name="PHONE">
        <div class="clear"></div>
    </div>
    <div class="line">
        <label>E-mail<span class="required">*</span></label>
        <input type="text" value="" class="email required" name="EMAIL">
        <div class="clear"></div>
    </div>
    <div class="line autocomplete small">
        <label>Город</label>                
        <input type="text" value="Москва" name="TOWN" class="autocomplete town">
        <div class="b-places"></div>
        <div class="clear"></div>
    </div>
            <div class="line">
            <label>Тема<span class="required">*</span></label>
            <select name="THEME" sb="27961130" style="display: none;">
                                    <option value="Заказ">Заказ</option>
                                    <option value="Кредит">Кредит</option>
                                    <option value="Купить Б/У">Купить Б/У</option>
                                    <option value="Вопрос">Вопрос</option>
                                    <option value="Аренда">Аренда</option>
                            </select><!--<div id="sbHolder_27961130" class="sbHolder"><a id="sbToggle_27961130" href="#" class="sbToggle"></a><a id="sbSelector_27961130" href="#" class="sbSelector">Заказ</a><ul id="sbOptions_27961130" class="sbOptions" style="display: none;"><li><a href="#Заказ" rel="Заказ" class="sbFocus">Заказ</a></li><li><a href="#Кредит" rel="Кредит">Кредит</a></li><li><a href="#Купить Б/У" rel="Купить Б/У">Купить Б/У</a></li><li><a href="#Вопрос" rel="Вопрос">Вопрос</a></li><li><a href="#Аренда" rel="Аренда">Аренда</a></li></ul></div>-->
            <div class="clear"></div>
        </div>
        <div class="line">
        <label>Описание</label>
        <input type="text" readonly="" value="" name="DESCRIPTION">
        <div class="clear"></div>
    </div>
    <div class="line">
        <label class="message">Комментарий</label>                
        <textarea placeholder="Ваши комментарии" name="MESSAGE"></textarea>
        <div class="clear"></div>
    </div>
    <p class="hint">* - поля, обязательные для заполнения.</p>
    <div class="line">
        <button name="send" type="submit">Отправить</button>
        <div class="clear"></div>
    </div>
<input type="hidden" value="085aa66349ac0395a55c18fd0d158537" name="jssid"></form></div>