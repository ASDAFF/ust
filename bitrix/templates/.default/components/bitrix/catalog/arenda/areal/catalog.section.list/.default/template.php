<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult["SECTIONS"])): ?>
    <div class="bu-price">
        <div class="tabs-plate">
            <div class="tabs" id="tab_link">
                <ul>
                    <? foreach ($arResult["SECTIONS"] as $key => $arSection): ?>
                        <?php
                        if (isset($arSection["LINK_DOMAIN"]))
                        {
                            //   print "<pre style='display:none'> "; print_r($arItem["LINK_DOMAIN"]); print "</pre>";
                            $arSection["SECTION_PAGE_URL"] = "http://" . $arSection["LINK_DOMAIN"] . $arSection["SECTION_PAGE_URL"];
                            $link_domain = $arSection["LINK_DOMAIN"];
                        }
                        else
                        {
                            $arSection["SECTION_PAGE_URL"] = "http://" . DEFAULT_DOMAIN . $arSection["SECTION_PAGE_URL"];
                            $link_domain = DEFAULT_DOMAIN;
                        }
                        $arResult["SECTIONS"][$key]["SECTION_PAGE_URL"] = $arSection["SECTION_PAGE_URL"];

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
                        ?>
                        <? if ($arSection["DEPTH_LEVEL"] == 1): ?>
                            <li <? if ($arSection["SELECTED"] == 1): ?>class="active_li"<? endif; ?>><? if ($noindex): ?><!--noindex--><? endif; ?><a <?=$rel ?> href="<?= $arSection["SECTION_PAGE_URL"] ?>"><?= $arSection["NAME"] ?></a><? if ($noindex): ?><!--/noindex--><? endif; ?></li>
                        <? endif; ?>
                    <? endforeach; ?>	
                    <a id="selected" href=""></a>					
                    <div class="clear"></div>
                </ul>
            </div>
        </div>	
        <?
        foreach ($arResult["SECTIONS"] as $section)
        {
            if ($section["DEPTH_LEVEL"] == 1 && $section["SELECTED"] == 1)
            {
                $default_all_url = $section["SECTION_PAGE_URL"];
            }
        }
        ?>
        <div class="technics-list">
            <? $flag = 0;
            $key_sec = 0;
            ?>
            <? foreach ($arResult["SECTIONS"] as $arSec): ?>
        <? if ($arSec["IBLOCK_SECTION_ID"] == $arResult["SELECTING_SECTION"] && $arSec["DEPTH_LEVEL"] == 2): ?>
                    <div class="item<? if (($key_sec + 1) % 3 == 0): ?> last<? endif; ?>">
                        <a href="<?= $arSec["SECTION_PAGE_URL"] ?>" <? if ($arSec["SELECTED"] == 1): ?><? $flag = 1; ?>class="active"<? endif; ?>><?= $arSec["NAME"] ?></a>
                    </div>
                    <? $key_sec++; ?>
                <? endif; ?>
    <? endforeach; ?>
            <div class="item<? if (($key_sec) % 3 == 0): ?> last<? endif; ?>">
                <a href="<?= $default_all_url ?>" <? if ($flag == 0): ?>class="active"<? endif; ?>>Показать все </a>
            </div>
            <div class="clear"></div>
        </div>
    </div>
<? endif; ?>