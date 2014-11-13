<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (count($arResult['ITEMS']) > 0): ?>

    <?
    //print "<pre asdasd style='display:none'>";
    //  print_r($arResult); 40101810200000010022
    //  print "</pre>";
    ?>
    <div class="news-page">
        <?
        foreach ($arResult["ITEMS"] as $arItem):
            if (isset($arItem["LINK_DOMAIN"]))
            {
                $arItem["DETAIL_PAGE_URL"] = "http://" . $arItem["LINK_DOMAIN"] . $arItem["DETAIL_PAGE_URL"];
                $link_domain = $arItem["LINK_DOMAIN"];
            }
            else
            {
                $arItem["DETAIL_PAGE_URL"] = "http://" . DEFAULT_DOMAIN . $arItem["DETAIL_PAGE_URL"];

                $link_domain = DEFAULT_DOMAIN;
            }


            if ($_SERVER["HTTP_HOST"] != $link_domain)
            {
                $rel = 'rel="nofollow"';
                $noindex = true;
            }
            else
            {
                $rel = "";
                $noindex = false;
            }
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <div class="image">
                    <? if ($noindex): ?><!--noindex--><? endif; ?><a <?=$rel?> class="image border_gray" href="<?= $arItem["DETAIL_PAGE_URL"] ?>" title="<?= $arItem["NAME"] ?>">
                        <table><tr><td>
                                    <? if (!empty($arItem["PREVIEW_PICTURE_RESIZE"]["src"])): ?>
                                        <img src="<?= $arItem["PREVIEW_PICTURE_RESIZE"]["src"] ?>" width="<?= $arItem["PREVIEW_PICTURE_RESIZE"]["width"] ?>" height="<?= $arItem["PREVIEW_PICTURE_RESIZE"]["height"] ?>" title="<?= $arItem["NAME"] ?>" alt="<?= $arItem["NAME"] ?>" />
                                    <? else: ?>
                                        <img src="<?= getImageNoPhoto(184, 117) ?>" title="<?= $arItem["NAME"] ?>" alt="<?= $arItem["NAME"] ?>" />
        <? endif; ?>
                                </td></tr></table>
                    </a><? if ($noindex): ?><!--/noindex--><? endif; ?>
                </div>
                <div class="description">
                    <? if ($noindex): ?><!--noindex--><? endif; ?><a <?=$rel?> class="title" href="<?= $arItem["DETAIL_PAGE_URL"] ?>" title="<?= $arItem["NAME"] ?>"><?= $arItem["NAME"] ?></a><? if ($noindex): ?><!--/noindex--><? endif; ?>
                    <div class="date"><?= $arItem["DISPLAY_ACTIVE_FROM"] ?></div>
                    <div class="date"><?= $arItem["CATEGORY"] ?></div>
                    <div class="short-text">
                        <? if ($arItem["PREVIEW_TEXT_TYPE"] == "text"): ?>
                            <?= $arItem["PREVIEW_TEXT"]; ?>
                        <? else: ?>
            <?= strip_tags($arItem["PREVIEW_TEXT"]); ?>
        <? endif; ?>
                    </div>
                    <div class="link"><? if ($noindex): ?><!--noindex--><? endif; ?><a <?=$rel?> href="<?= $arItem["DETAIL_PAGE_URL"] ?>">подробнее<span>&raquo;</span></a><? if ($noindex): ?><!--/noindex--><? endif; ?></div>
                </div>
                <div class="clear"></div>
            </div>
    <? endforeach; ?>
    </div>
    <div class="hr"></div>
    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?>
    <? endif; ?>
<? else: ?>
    <div class="clear"></div>
    <p class="errortext">К сожалению, в данном разделе нет доступной информации.</p>
    <div class="clear"></div>
<? endif; ?>