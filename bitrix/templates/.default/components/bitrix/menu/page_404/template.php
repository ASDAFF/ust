<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <? $count = 0; ?>
     <? foreach ($arResult as $key => $arItem): ?>
     	<? if (strpos($arItem['LINK'],'/catalog/' !== false)): ?>
        	<? $arResult2[]=$arItem;?>
            
        <? endif;?>
        
     <? endforeach;?>
    <pre>
       <? // print_r($arResult);?>
        </pre>
    <? foreach ($arResult2 as $key => $arItem): ?>
    
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
        <? $count++; ?>
        
    <? endforeach; ?>
    </div>
<? endif; ?>
