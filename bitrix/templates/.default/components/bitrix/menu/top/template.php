<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>

    <nav><ul><? foreach ($arResult as $key => $arItem): ?>

                <?php
                if ($arItem["LINK"] == "/catalog/bu-stroitelnaya-tehnika/")
                    $arItem["LINK_DOMAIN"] = "u-st.ru";
                if ($arItem["LINK"] == "/finansovye-uslugi/specpredlozheniya/")
                    $arItem["LINK_DOMAIN"] = "ust-co.ru";


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
                
                /*
                $page = $APPLICATION->GetCurPage();
                if ($page == "/index.php")
                {
                    $rel = "";
                    $noindex = false;
                }
                */
                ?>

                <? if ($arItem["DEPTH_LEVEL"] == 1): ?>

                    <? $description = $arItem["DESCRIPTION"]; ?>
                    <li> <? if ($noindex): ?><!--noindex--><? endif; ?><a <?= $rel ?> href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a><? if ($noindex): ?><!--/noindex--><? endif; ?><? endif; ?>
                <? if ($arItem["DEPTH_LEVEL"] == 2): ?>
                    <? $description = array("TEXT" => $arItem["DESCRIPTION"], "TYPE" => $arItem["DESCRIPTION_TYPE"]); ?>
                    <? if ($arResult[$key - 1]["DEPTH_LEVEL"] < $arItem["DEPTH_LEVEL"]): ?>
                        <div class="nav-hover-popup">
                            <div class="items">
                                <div class="close-item"><? if ($noindex): ?><!--noindex--><? endif; ?><a <?= $rel ?>   href="#"><span></span></a><? if ($noindex): ?><!--/noindex--><? endif; ?></div>
                            <? endif; ?>
                            <div class="item">
                                <? if ($noindex): ?><!--noindex--><? endif; ?><a  <?= $rel ?>  href="<?= $arItem["LINK"] ?>"<? if ($arItem["DETAIL_PICTURE"]["src"]): ?> class="image service-icon"<? endif; ?>>
                                <? if ($arItem["DETAIL_PICTURE"]["src"]): ?> 
                                        <table><tr><td>
                                                    <img detail-pic="<?= $arItem["DETAIL_PICTURE"]["src"] ?>"  src="<?= $arItem["PICTURE"]["src"] ?>" width="<?= $arItem["PICTURE"]["width"] ?>" height="<?= $arItem["PICTURE"]["height"] ?>" alt="<?= $arItem["TEXT"] ?>" title="<?= $arItem["TEXT"] ?>" />
                                                </td></tr></table>
                                    <? endif; ?>
                                    <span class="name"><?= $arItem["TEXT"] ?></span>
                                    <!--div class="clear"></div-->
                                </a><? if ($noindex): ?><!--/noindex--><? endif; ?>
                            <? endif; ?>
                            <? if ($arItem["DEPTH_LEVEL"] == 3): ?>
                                <? if ($arResult[$key - 1]["DEPTH_LEVEL"] < $arItem["DEPTH_LEVEL"]): ?>
                                    <div class="depth-3 <? if (!empty($description["TEXT"])): ?>max<? endif; ?>">
                                        
                                        <? if ($noindex): ?><!--noindex--><? endif; ?>
                                        <div class="list">
                                            <p><b>Все</b></p>
                                            <ul>
                                            <? endif; ?>
                                            <li><a <?= $rel ?>  href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a></li>
                                            <? if ($arResult[$key + 1]["DEPTH_LEVEL"] < $arItem["DEPTH_LEVEL"]): ?>
                                            </ul>
                                        </div>
                                        <? if ($noindex): ?><!--/noindex--><? endif; ?>
                                        
                                        <? if (!empty($description["TEXT"])): ?>
                                            <? if ($noindex): ?><!--noindex--><? endif; ?>
                                                <div class="description">
                                                    <div>
                                                        <? if ($description["TYPE"] == "html"): ?>
                                                            <div><?echo (($noindex)?(preg_replace('/<a href=/','<a rel="nofollow" href=',$description["TEXT"])):($description["TEXT"])); ?></div>
                                                        <? else: ?>
                                                            <p><?echo (($noindex)?(preg_replace('/<a href=/','<a rel="nofollow" href=',$description["TEXT"])):($description["TEXT"])); ?></p>
                                                        <? endif; ?>
                                                    </div>
                                                </div>
                                            <? if ($noindex): ?><!--/noindex--><? endif; ?>
                                        <? endif; ?>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            <? endif; ?>
                        <? endif; ?>
                        <? if ($arItem["DEPTH_LEVEL"] == 2 && $arResult[$key + 1]["DEPTH_LEVEL"] <= $arItem["DEPTH_LEVEL"]): ?>
                        </div>						
                        <? if ($arResult[$key + 1]["DEPTH_LEVEL"] < $arItem["DEPTH_LEVEL"]): ?>
                        </div>
                        </div>
                    <? endif; ?>
                <? endif; ?><? if ($arResult[$key + 1]["DEPTH_LEVEL"] == 1 || !isset($arResult[$key + 1])): ?></li><? endif; ?>
            <? endforeach; ?>
        </ul>
    </nav>
<? endif; ?>