<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <? foreach ($arResult as $key => $arItem): ?>


        <? //-----------1-----------?>
        <? if ($arItem["DEPTH_LEVEL"] == 1 && $arItem["SELECTED"] == 1 && $arItem["LINK"] != "/catalog/"): ?>
            <?
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
           // pr($page);
            if ($page == "/index.php")
            {
                $rel = "";
                $noindex = false;
            }
            //pr($link_domain);
            ?>
            <li <? if ($arItem["SELECTED"] == 1): ?> class="active" <? endif; ?>>
                <? if ($noindex): ?><!--noindex--><? endif; ?><a  <?= $rel ?> href="<?= $arItem["LINK"] ?>">
                    <span class="link"><?= $arItem["TEXT"] ?></span>
                    <span class="bg"></span>
                </a> <? if ($noindex): ?><!--/noindex--><? endif; ?>
                <?
                if ($arItem["SELECTED"] == 1)
                {
                    $flag = 1;
                }
                else
                    $flag = 0;
                ?>
            <? elseif ($arItem["DEPTH_LEVEL"] == 1 && $arItem["LINK"] != "/catalog/"): ?>
                <? $flag = 0; ?>
            <? endif; ?>
            <? //-----------2-----------?>
            <? if ($arItem["DEPTH_LEVEL"] == 2 && $flag == 1): ?>
                <?
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

                if ($page == "/index.php" and $_SERVER["HTTP_HOST"] != DEFAULT_DOMAIN)
                {
                    $rel = "";
                    $noindex = false;
                }
                ?>
                <? if ($arResult[$key - 1]["DEPTH_LEVEL"] < $arItem["DEPTH_LEVEL"] && $arResult[$key - 1]["SELECTED"] == 1): ?>
                    <ul <? if ($arResult[$key - 1]["SELECTED"] == 1): ?> class="active" <? endif; ?> >
                    <? endif; ?>
                    <li <? if ($arItem["SELECTED"] == 1 && $arItem['UNSELECTED'] == false): ?> class="active" <? endif; ?> >
                        <? if ($noindex): ?><!--noindex--><? endif; ?> <a <?= $rel ?> href="<?= $arItem["LINK"] ?>">
                            <span class="link"><?= $arItem["TEXT"] ?></span>
                            <span class="bg"></span>
                        </a> <? if ($noindex): ?><!--/noindex--><? endif; ?>
                        <?
                        if ($arItem["SELECTED"] == 1)
                        {
                            $flag1 = 1;
                        }
                        else
                            $flag1 = 0;
                        ?>
                    <? elseif ($arItem["DEPTH_LEVEL"] == 2): ?>
                        <? $flag1 = 0; ?>
                    <? endif; ?>
                    <? //-----------3-----------?>
                    <? if ($arItem["DEPTH_LEVEL"] == 3 && $flag1 == 1): ?>
                        <?
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

                        if ($page == "/index.php" and $_SERVER["HTTP_HOST"] != DEFAULT_DOMAIN)
                        {
                            $rel = "";
                            $noindex = false;
                        }
                        ?>
                        <? if ($arResult[$key - 1]["DEPTH_LEVEL"] < $arItem["DEPTH_LEVEL"] && $arResult[$key - 1]["SELECTED"] == 1): ?>
                            <ul <? if ($arResult[$key - 1]["SELECTED"] == 1): ?> class="active" <? endif; ?> >
                            <? endif; ?>

                            <li <? if ($arItem["SELECTED"] == 1): ?> class="active" <? endif; ?> >
                                <? if ($noindex): ?><!--noindex--><? endif; ?><a <?= $rel ?> href="<?= $arItem["LINK"] ?>">
                                    <span class="link"><?= $arItem["TEXT"] ?></span>
                                    <span class="bg"></span>
                                </a> <? if ($noindex): ?><!--/noindex--><? endif; ?>
                                <? if ($arResult[($key + 1)]["DEPTH_LEVEL"] <= $arItem["DEPTH_LEVEL"]): ?>
                                </li>
                            <? endif; ?>
                            <? if ($arResult[$key + 1]["DEPTH_LEVEL"] < $arItem["DEPTH_LEVEL"]): ?>
                            </ul>
                        <? endif; ?>
                    <? endif; ?>
                    <? //-----------2-----------?>
                    <? if ($arItem["DEPTH_LEVEL"] == 2 && $flag == 1): ?>
                        <?
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

                        if ($page == "/index.php" and $_SERVER["HTTP_HOST"] != DEFAULT_DOMAIN)
                        {
                            $rel = "";
                            $noindex = false;
                        }
                        ?>
                        <? if ($arItem["DEPTH_LEVEL"] == 2 && $arResult[($key + 1)]["DEPTH_LEVEL"] <= $arItem["DEPTH_LEVEL"] || ($arResult[$key + 1]["DEPTH_LEVEL"] < $arItem["DEPTH_LEVEL"]) || !isset($arResult[$key + 1])): ?>
                        </li>
                    <? endif; ?>
                    <?
                    if ($arResult[$key + 1]["DEPTH_LEVEL"] < $arItem["DEPTH_LEVEL"]
                    ):
                        ?>
                    </ul>
                <? endif; ?>
            <? endif; ?>
            <? //-----------1----------- ?>
            <? if (($arItem["DEPTH_LEVEL"] == 1 && ($arResult[$key + 1]["DEPTH_LEVEL"] <= $arItem["DEPTH_LEVEL"] && $arItem["SELECTED"] == 1)) || ($arResult[$key + 1]["DEPTH_LEVEL"] < $arItem["DEPTH_LEVEL"]) || !isset($arResult[$key + 1])): ?>
            </li>
        <? endif; ?>	
    <? endforeach; ?>
<? endif; ?>