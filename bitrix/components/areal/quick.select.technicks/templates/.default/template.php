<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="title">Подбор техники.:</div>
<? if (!empty($arResult["SECTIONS"])): ?>
    <div class="catalog_quick">

        <? foreach ($arResult["SECTIONS"] as $key => $arSection): ?>
            <?php
            if ($arSection["LINK_DOMAIN"] != "")
            {
                $arSection["SECTION_PAGE_URL"] = "http://" . $arSection["LINK_DOMAIN"] . $arSection["SECTION_PAGE_URL"];
                $link_domain = $arSection["LINK_DOMAIN"];
            }
            else
            {
                $arSection["SECTION_PAGE_URL"] = "http://" . DEFAULT_DOMAIN . $arSection["SECTION_PAGE_URL"];
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
            // pr($page);
            if ($page == "/index.php")
            {
                $rel = "";
                $noindex = false;
            }
            ?>
            <div class="line <? if (($key + 1) % 2 == 0): ?> grey <? endif; ?>">
                <? if ($noindex): ?><!--noindex--><? endif; ?><a <?= $rel ?> href="<?= $arSection["SECTION_PAGE_URL"] ?>" class="image service-icon<? if (count($arSection["ITEMS"]) > 0): ?> open_section<? endif; ?>" title="<?= $arSection["NAME"] ?>" id="s_<?= $arSection["ID"] ?>">
                <? if (!empty($arSection["PICTURE"]["src"])): ?>
                        <div class="img">
                            <table><tr><td>
                                        <img detail-pic="<?= $arSection["DETAIL_PICTURE"]["src"] ?>"  src="<?= $arSection["PICTURE"]["src"] ?>" width="<?= $arSection["PICTURE"]["width"] ?>" height="<?= $arSection["PICTURE"]["height"] ?>" alt="<?= $arSection["NAME"] ?>" title="<?= $arSection["NAME"] ?>" />
                                    </td></tr></table>
                        </div>
                    <? endif; ?>
                    <div class="name"><span><?= $arSection["NAME"] ?></span><? if (count($arSection["ITEMS"]) > 0): ?><span class="arrow"></span><? endif; ?></div>
                </a> <? if ($noindex): ?><!--/noindex--><? endif; ?>
            </div>
            <? if (count($arSection["ITEMS"]) > 0): ?>
                <div class="second_sections" id="line_<?= $arSection["ID"] ?>">
                    <? foreach ($arSection["ITEMS"] as $k => $arItem): ?>

                        <?php
                        if (isset($arItem["LINK_DOMAIN"]))
                        {
                            $arItem["SECTION_PAGE_URL"] = "http://" . $arItem["LINK_DOMAIN"] . $arItem["SECTION_PAGE_URL"];
                            $link_domain = $arItem["LINK_DOMAIN"];
                        }
                        else
                        {
                            $arItem["SECTION_PAGE_URL"] = "http://" . DEFAULT_DOMAIN . $arItem["SECTION_PAGE_URL"];
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

                        if ($page == "/index.php")
                        {
                            $rel = "";
                            $noindex = false;
                        }
                        ?>
                        <? if ($noindex): ?><!--noindex--><? endif; ?> <a  <?= $rel ?> href="<?= $arItem["SECTION_PAGE_URL"] ?>" title="<?= $arItem["NAME"] ?>">
                            <table><tr><td>
                                        <? if (!empty($arItem["PICTURE"]["src"])): ?>
                                            <img src="<?= $arItem["PICTURE"]["src"] ?>" width="<?= $arItem["PICTURE"]["width"] ?>" height="<?= $arItem["PICTURE"]["height"] ?>" alt="<?= $arItem["NAME"] ?>" title="<?= $arItem["NAME"] ?>" />
                                        <? else: ?>
                                            <img src="<?= getImageNoPhoto(100, 70) ?>" alt="<?= $arItem["NAME"] ?>" title="<?= $arItem["NAME"] ?>" />
                                        <? endif; ?>
                                    </td></tr></table>
                            <span><?= $arItem["NAME"] ?></span>
                        </a><? if ($noindex): ?><!--/noindex--><? endif; ?>
                        <? if (($k + 1) % 6 == 0): ?><div class="clear"></div><? endif; ?>
                    <? endforeach; ?>
                    <div class="clear"></div>
                </div>
            <? endif; ?>
        <? endforeach; ?>
    </div>
<? else: ?>
    <p>К сожалению, доступной информации не найдено.</p>
<? endif; ?>