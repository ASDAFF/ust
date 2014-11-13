<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
<? global $USER;
if ($USER->IsAdmin()) : ?>
<pre>
<? //print_r($arResult);?>
</pre>
<? endif;?>
    <? $count = 0; ?>
     <? foreach ($arResult as $key => $arItem): ?>
     	<? if ($arItem["DEPTH_LEVEL"] <= 3 && $arItem['LINK'] != '/catalog/' && $arItem['LINK'] != '/arenda/' && $arItem['LINK'] != '/catalog/navesnoe-oborudovanie/'): ?>
        	<? $arResult2[]=$arItem;?>
        <? endif;?>
     <? endforeach;?>
    
    <? $size_column = intVal(count($arResult2) / 4); ?>
    <? foreach ($arResult2 as $key => $arItem): ?>
    
        <? if (($count % $size_column == 0 || $count == 0) && $column < 4): ?>
            <div class="col">
                <? $column++; ?>
            <? endif; ?>	
            <?php
            if (isset($arItem["LINK_DOMAIN"]))
            {
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
            if ($page == "/index.php" )
            {
                $rel = "";
                $noindex = false;
            }
            ?>
            <? if ($noindex): ?><!--noindex--><? endif; ?>
            
            <a <?= $rel ?> <? if ($arItem["DEPTH_LEVEL"] == 1): ?>class="bold"<? else: ?>class="small"<? endif; ?> href="<?= $arItem["LINK"] ?>" title="<?= $arItem["TEXT"] ?>"><?= $arItem["TEXT"] ?></a>  <? if ($noindex): ?><!--/noindex--><? endif; ?>
            <? if ((($count + 1) % $size_column == 0 && $column < 4) || (!isset($arResult[$count + 1]) && $column == 4)): ?>
            </div>
        <? endif; ?>
        <? $count++; ?>
        
    <? endforeach; ?>
    </div>
<? endif; ?>