<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
//pr($arResult["ITEMS"], false);
?>
<? if (count($arResult["ITEMS"]) > 0): ?>
    <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?>
    <? endif; ?>
    <div class="clear"></div>
    <div class="catalog-plate catalog">
        <? foreach ($arResult["ITEMS"] as $key => $arItem): ?>
            <div class="items <? if (($key + 1) % 2 == 0): ?>last<? endif; ?>" id="item_<?= $arItem["ID"] ?>">
                <? if (!empty($arItem["PROPERTIES"]["ACTIONS"]["VALUE"])): ?>
                    <span class="action popup"><?= $arItem["PROPERTIES"]["ACTIONS"]["NAME"] ?></span>
                <? endif; ?>
                <? if (!empty($arItem["PROPERTIES"]["NEW"]["VALUE"])): ?>
                    <span class="new popup"><?= $arItem["PROPERTIES"]["NEW"]["NAME"] ?></span>
                <? endif; ?>
                <? if (!empty($arItem["PROPERTIES"]["SEASONAL"]["VALUE"])): ?>
                    <span class="season popup"><?= $arItem["PROPERTIES"]["SEASONAL"]["NAME"] ?></span>
                <? endif; ?>	
                <? if (!empty($arItem["PROPERTIES"]["SALE"]["VALUE"])): ?>
                    <span class="sale popup"><?= $arItem["PROPERTIES"]["SALE"]["NAME"] ?></span>
                <? endif; ?>	
                <? if (!empty($arItem["PROPERTIES"]["CREDIT"]["VALUE"])): ?>
                    <span class="credit popup"><?= $arItem["PROPERTIES"]["CREDIT"]["NAME"] ?></span>
                <? endif; ?>
                <a class="image" href="<?= $arItem["DETAIL_PAGE_URL"] ?>" title="<?= $arItem["NAME"] ?>"><table><tr><td>
                                <? if (!empty($arItem["PICTURE"]["src"])): ?>
                                    <img src="<?= $arItem["PICTURE"]["src"] ?>" width="<?= $arItem["PICTURE"]["width"] ?>" height="<?= $arItem["PICTURE"]["height"] ?>" alt="<?= $arItem["NAME"] ?>" title="<?= $arItem["NAME"] ?>" />
                                <? else: ?>
                                    <img src="<?= getImageNoPhoto(160, 224) ?>" alt="<?= $arItem["NAME"] ?>" title="<?= $arItem["NAME"] ?>" />
                                <? endif; ?>
                            </td></tr></table></a>
                <div class="info">
                    <div class="description">
                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="title" title="<?= $arItem["NAME"] ?>"><span><?= $arItem["PROPERTIES"]["SHORT_NAME"]["VALUE"] ? $arItem["PROPERTIES"]["SHORT_NAME"]["VALUE"] : $arItem["NAME"] ?></span></a>
                        <span class="brand"><?= $arItem["BRAND"] ?></span>
                        <? if (!empty($arItem["PROPERTIES"]["PRICE"]["VALUE"])): ?>
                            <? if (!empty($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"])): ?>
                                <span class="old_price"><?= CurrencyFormat($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"], "RUB") ?></span>
                            <? endif; ?>
                            <span class="price"><?= CurrencyFormat($arItem["PsROPERTIES"]["PRICE"]["VALUE"], "RUB") ?></span>
                        <? endif; ?>
                        <div class="characteristics">
                            <? foreach ($arItem["PROPERTIES"] as $prop_key => $arProp): ?>
                                <? if (!in_array($prop_key, $arParams["PERMANENT_PROPERTY"]) && !empty($arProp["VALUE"])): ?>
                                    <? if ($arProp["NAME"] != "Видео" and $arProp["NAME"] != "Фотографии" and $arProp["NAME"] != "Короткое название" and $arProp["NAME"] != "Вам могут быть интересны"): ?>
                                        <p><?= $arProp["NAME"] ?> <span><?= $arProp["VALUE"] ?></span></p>
                                    <? endif; ?>
                                <? endif; ?>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <input type="hidden" name="item_name" value="<?= $arItem["NAME"] ?>" />
                    <button name="ordering">Запрос цены</button>
                </div>
                <div class="clear"></div>
                <div class="comparison"></div>
            </div>
        <? endforeach; ?>	
    </div>
    <div class="hr"></div>
    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?>
    <? endif; ?>
<? else: ?>
    <div class="clear"></div>
    <p class="errortext">К сожалению, в данном разделе нет доступных товаров.</p>
    <div class="clear"></div>
<? endif; ?>