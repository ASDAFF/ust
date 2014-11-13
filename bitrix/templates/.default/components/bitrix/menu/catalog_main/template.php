<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult["NEW"])): ?>
    <div class="categories">
        <div class="wrapper">
            <?
            $count = 0;
            $num = 0;
            // $size_column=3;
            
            $size_column = round(count($arResult["NEW"]) / 4);
            
            ?>
            <? foreach ($arResult["NEW"] as $key => $arItem): ?>

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

                // pr($page);
                $page = $APPLICATION->GetCurPage();
                if ($page == "/index.php" )
                {
                    $rel = "";
                    $noindex = false;
					 
                }
                ?>
                <? if ($count % $size_column == 0 || $count == 0 /*and $num <= $size_column*/): ?>
                    
                    <div class="col number_<?= $num ?>">
                    <? endif; ?>
                    <?
                    if (/* $arItem["DEPTH_LEVEL"] == 1 && $count < 12 */ true):
                        ?>              
                        <? if ($noindex): ?><!--noindex--><? endif; ?><a <?= $rel ?> href="<?= $arItem["LINK"] ?>" title="<?= $arItem["TEXT"] ?>"><?= $arItem["TEXT"] ?></a><? if ($noindex): ?><!--/noindex--><? endif; ?>
                    <? endif; ?> 
                    <?
                    //endif;
                    ?>
                    <? if (($count + 1) % $size_column == 0): ?>
                        <? $num++; ?>
                    </div>
                <? endif; ?>

                <? $count++; ?>
            <? endforeach; ?>
            
            <? if ($count < ($size_column * 4) and $_SERVER["HTTP_HOST"] != "generatory.ust-co.ru") : ?></div><? endif; ?>
        
        <? if ($_SERVER["HTTP_HOST"] == "u-st.ru"): ?></div><? endif; ?>

  
    <div class="clear"></div>			
    </div>
    </div>	
<? endif; ?>