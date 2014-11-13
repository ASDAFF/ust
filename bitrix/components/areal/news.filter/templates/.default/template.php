<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <div class="news-filter">
        <? if (!empty($arResult['SECTIONS'])): ?>
            <ul class="sections">
                <li><a href="<?= $arParams["IBLOCK_URL"] ?>" title="Все разделы" <? if (!$arParams["SECTION_CODE"]): ?>class="active"<? endif; ?>>Все</a></li>
                <? foreach ($arResult['SECTIONS'] as $key => $section): ?>
                    <?php
                    if ($section["LINK_DOMAIN"] != "")
                    {
                        $section['SECTION_PAGE_URL'] = "http://" . $section["LINK_DOMAIN"] . $section['SECTION_PAGE_URL'];
                        $link_domain=$section["LINK_DOMAIN"];
                    }
                    else
                    {//
                        $section['SECTION_PAGE_URL'] = "http://" . DEFAULT_DOMAIN . $section['SECTION_PAGE_URL'];
                        $link_domain=DEFAULT_DOMAIN;
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
                    //pr($rel);
                    ?>
                    <li><? if ($noindex): ?><!--noindex--><? endif; ?><a <?=$rel?> href="<?= $section['SECTION_PAGE_URL'] ?>" <? if ($section["SELECTED"] == 1): ?> class="active" <? endif; ?> title="<?= $section['NAME'] ?>"><?= $section['NAME'] ?></a><? if ($noindex): ?><!--/noindex--><? endif; ?></li>
                <? endforeach; ?>
                <div class="clear"></div>
            </ul>
        <? endif; ?>
        <? if (!empty($arResult["YEARS"])): ?>	
            <ul class="years">
                <li><a href="<?= $APPLICATION->GetCurPageParam("", array("year", "month"), false) ?>" <? if (!$_REQUEST["year"]): ?>class="active"<? endif; ?> title="Все года">Все</a></li>
                <? foreach ($arResult['YEARS'] as $year => $month): ?>
                    <li><a href="<?= $APPLICATION->GetCurPageParam("year=" . $year, array("year", "month"), false) ?>" <? if ($_REQUEST["year"] == $year): ?>class="active"<? endif; ?> title="<?= $year ?> год"><?= $year ?></a></li>
                <? endforeach; ?>
                <div class="clear"></div>
            </ul>
            <? if ($_REQUEST["year"] > 0): ?>
                <ul class="monthes">	
                    <li><a href="<?= $APPLICATION->GetCurPageParam("", array("month"), false) ?>" <? if (!$_REQUEST["month"]): ?>class="active"<? endif; ?> title="Все месяцы">Все</a></li>			
                    <? foreach ($arResult['YEARS'][$_REQUEST["year"]] as $number => $month): ?>
                        <li><a href="<?= $APPLICATION->GetCurPageParam("month=" . $number, array("month"), false) ?>" <? if ($_REQUEST["month"] == $number): ?>class="active"<? endif; ?> title="<?= $month ?> год"><?= $month ?></a></li>
                    <? endforeach; ?>
                    <div class="clear"></div>
                </ul>
            <? endif; ?>
        <? endif; ?>
    </div>
<? endif; ?>