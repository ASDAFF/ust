<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="actions-tags">
    <a href="/akcii/" title="Все" <? if (!$arResult["SELECTING"]): ?> class="active" <? endif; ?>>Все</a>
    <? if (!empty($arResult["FILTER"])): ?>
        <? foreach ($arResult["FILTER"] as $key => $filter): ?>
            <?php
            if (isset($filter["LINK_DOMAIN"]))
            {
                //   print "<pre style='display:none'> "; print_r($arItem["LINK_DOMAIN"]); print "</pre>";
                $filter["SECTION_PAGE_URL"] = "http://" . $filter["LINK_DOMAIN"] . $filter["SECTION_PAGE_URL"];
                $link_domain = $filter["LINK_DOMAIN"];
            }
            else
            {
                $filter["SECTION_PAGE_URL"] = "http://" . DEFAULT_DOMAIN . $filter["SECTION_PAGE_URL"];
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
            ?>
             <? if ($noindex): ?><!--noindex--><? endif; ?> <a <?=$rel?> href="<?= $filter["SECTION_PAGE_URL"] ?>" <? if ($filter["SELECTED"] == 1): ?> class="active" <? endif; ?> title="<?= $filter["NAME"] ?>"><?= $filter["NAME"] ?></a>  <? if ($noindex): ?><!--/noindex--><? endif; ?>

        <? endforeach; ?>
    <? endif; ?>

    <div class="title">Показаны текущие акции <? if ($arResult["SELECTING"] == 1): ?>в категории 
            <b><? foreach ($arResult["FILTER"] as $filter): ?><? if ($filter["SELECTED"] == 1): ?><?= $filter["NAME"] ?><? break; ?><? endif; ?><? endforeach; ?></b>
        <? else: ?>по всем категориям<? endif; ?>:</div>
</div>