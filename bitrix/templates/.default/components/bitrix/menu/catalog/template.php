<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
<style>
    ul.sub ul.sub, li.sub li.sub {
        margin-left: 20px;
    }
    .side-nav ul ul ul {
        font-style: italic;
        margin-top: 0px!important;
        margin-bottom: 0px!important;
    }
</style>

    
    <? foreach ($arResult as $key => $arItem): ?>
        <?php
        if ($arItem["LINK"] == "/catalog/bu-stroitelnaya-tehnika/")
            $arItem["LINK_DOMAIN"] = "u-st.ru";

        if (isset($arItem["LINK_DOMAIN"]))
        {
            //   print "<pre style='display:none'> "; print_r($arItem["LINK_DOMAIN"]); print "</pre>";
            $arItem["LINK"] = "http://" . $arItem["LINK_DOMAIN"] . $arItem["LINK"];
            $link_domain = $arItem["LINK_DOMAIN"];
        }
        else
        {
            $arItem["LINK"] = "http://" . DEFAULT_DOMAIN . $arItem["LINK"];
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
        $page = $APPLICATION->GetCurPage();
        if ($page == "/index.php"  )
        {
            $rel = "";
            $noindex = false;
        }
        ?>
        <? if ($arItem["DEPTH_LEVEL"] == 1): ?>
            <li <? if ($arItem["SELECTED"] && $arItem["SELECTED_THIS"] == 0): ?> class="active" <? endif; ?>>
            <? if ($noindex): ?><!--noindex--><? endif; ?><a <?= $rel ?> href="<?= $arItem["LINK"] ?>">
                    <span class="link"><?= $arItem["TEXT"] ?></span>
                    <span class="bg"></span>
                </a><? if ($noindex): ?><!--/noindex--><? endif; ?>
        <? endif; ?>
        <? if ($arItem["DEPTH_LEVEL"] >= 2): ?>

            <? if ($arResult[$key - 1]["DEPTH_LEVEL"] < $arItem["DEPTH_LEVEL"]): ?>
                <ul class="sub <? if ($arResult[$key - 1]["SELECTED"]): ?>active<? endif; ?>">
            <? endif; ?>

                <li  class="<? if ($arItem["SELECTED"] == 1): ?>active<? endif; ?>" >
                <? if ($noindex): ?><!--noindex--><? endif; ?> <a <?= $rel ?> href="<?= $arItem["LINK"] ?>">
                        <span class="link"><?= $arItem["TEXT"] ?></span>
                        <span class="bg"></span>
                    </a><? if ($noindex): ?><!--/noindex--><? endif; ?>
                </li>

            <? if ((int)$arResult[$key + 1]["DEPTH_LEVEL"] < (int)$arItem["DEPTH_LEVEL"] || !isset($arResult[$key + 1])): ?>
                </ul>
            <? endif; ?>

            <? if (((int)$arItem["DEPTH_LEVEL"] - (int)$arResult[$key + 1]["DEPTH_LEVEL"]) == 2): ?>
                </ul>
            <? endif; ?>

            <? if (((int)$arItem["DEPTH_LEVEL"] - (int)$arResult[$key + 1]["DEPTH_LEVEL"]) == 3): ?>
                </ul>
                </ul>
            <? endif; ?>

        <? endif; ?>
        <? if (($arItem["DEPTH_LEVEL"] == 1 && $arResult[$key + 1]["DEPTH_LEVEL"] <= $arItem["DEPTH_LEVEL"]) || $arResult[$key + 1]["DEPTH_LEVEL"] < $arItem["DEPTH_LEVEL"] || !isset($arResult[$key + 1])): ?>
            </li>
        <? endif; ?>
    <? endforeach; ?>
<? endif; ?>