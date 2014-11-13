<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
TownDefinition();
IncludeTemplateLangFile(__FILE__);
?>
<!DOCTYPE html>
<html xml:lang="<?= LANGUAGE_ID ?>" lang="<?= LANGUAGE_ID ?>">
    <head>
        <meta charset="utf-8" />
        <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <?
        $page = $APPLICATION->GetCurPage();
        $arPage = explode("/", $page);
        if (substr_count($page, "o-kompanii") || substr_count($page, "akcii"))
        {
            if (CModule::IncludeModule("iblock"))
            {
                $dbRes = CIBlockElement::GetList(array("SORT" => "ASC"), array("CODE" => $arPage[count($arPage) - 2]));
                if ($arRes = $dbRes->Fetch())
                {
                    if (!empty($arRes))
                    {
                        echo "<meta property=\"og:description\" content=\"" . strip_tags($arRes["PREVIEW_TEXT"]) . "\">";
                        echo "<meta property=\"og:image\" content=\"http://ust-co.ru" . CFile::GetPath($arRes["DETAIL_PICTURE"]) . "\">";
                    }
                }
            }
        }
        ?> 
        <? /* <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
          <meta content="telephone=no" name="format-detection" /> */ ?>
        <? $APPLICATION->ShowHead(); ?>
        <title><? $APPLICATION->ShowTitle() ?></title>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="icon" href="/favicon.ico" type="image/x-icon" />

        <link rel="stylesheet" type="text/css" href="/design/css/nivo-slider.css" />
        <link rel="stylesheet" type="text/css" href="/design/css/fancybox/jquery.fancybox.css" />
        <link rel="stylesheet" type="text/css" href="/design/css/jquery.selectbox.css" />
        <link rel="stylesheet" type="text/css" href="/design/css/article.css" />
        <link rel="stylesheet" type="text/css" href="/design/css/kombox.css?=<?=rand();?>" />
        <? if ($_SERVER["REQUEST_URI"] != "/" && $_SERVER["SCRIPT_NAME"] != "/index.php"): ?>
            <link rel="stylesheet" type="text/css" href="/design/css/inside.css?var=12" />
        <? else: ?>
            <link rel="stylesheet" type="text/css" href="/design/css/main.css?var=12" />
        <? endif; ?>
        <link rel="stylesheet" type="text/css" href="/design/css/jquery.jscrollpane.css" />
        <link rel="stylesheet" type="text/css" href="/design/css/mirgorodskaja.css?var=12" />
        <link rel="stylesheet" type="text/css" href="/design/css/lebedev.css?var=12" />
        <link rel="stylesheet" type="text/css" href="/design/css/tmp.css?var=12" />
        <!--[if IE]>
            <link rel="stylesheet" type="text/css" href="/design/css/ie.css" />
        <![endif]-->
        <!--[if IE 8]>
            <link rel="stylesheet" type="text/css" href="/design/css/ie8.css" />
        <![endif]-->
        <!--[if IE 7]>
            <link rel="stylesheet" type="text/css" href="/design/css/ie7.css" />
            <link rel="stylesheet" type="text/css" href="/design/css/ie7_mirgorodskaja.css" />
        <![endif]-->
        <script type="text/javascript" src="/design/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="/design/js/jquery-ui-1.10.3.custom.js"></script>
        <script type="text/javascript" src="/design/js/jquery.fancybox.js"></script>
        <script type="text/javascript" src="/design/js/jquery.mousewheel.js"></script>
        <script type="text/javascript" src="/design/js/jquery.blackAndWhite.js"></script>
        <script type="text/javascript" src="/design/js/jquery.nivo.slider.js"></script>
        <script type="text/javascript" src="/design/js/jcarouselliteCustom.js"></script>   
        <script type="text/javascript" src="/design/js/jcarousellite_1.0.1.js"></script>  
        <script type="text/javascript" src="/design/js/jquery.placeholder.js"></script>
        <script type="text/javascript" src="/design/js/jquery.maskedinput.js"></script>
        <script type="text/javascript" src="/design/js/jquery.selectbox-0.2.js"></script>
        <script type="text/javascript" src="/design/js/jquery.cycle.all.js"></script>
        <script type="text/javascript" src="/design/js/cloud-zoom.1.0.2.js"></script>
        <? if (defined("HISTORY_PAGE")): ?>
            <script type="text/javascript" src="/design/js/jQueryRotate3.js"></script>
            <script type="text/javascript" src="/design/js/easing.js"></script>
        <? endif; ?>
        <script type="text/javascript" src="/design/js/jquery.jscrollpane.js"></script>
        <script type="text/javascript" src="/design/js/swfobject.js"></script>
        <script type="text/javascript" src="/design/js/functions.js"></script>
        <script type="text/javascript" src="/design/js/mirgorodskaja.js?var=12"></script>
        <script type="text/javascript" src="/design/js/lebedev.js?var=12"></script>
        <script type="text/javascript" src="/design/js/share42.js"></script>
        <script type="text/javascript" src="/design/js/kombox.js?var=12"></script>
        <script type="text/javascript" src="/design/js/ion.rangeSlider.js"></script>
        <script type="text/javascript" src="/design/js/jquery.cookie.js"></script>
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <? if (defined("FILIALS_AND_DEALERS")): ?>            
            <link rel="stylesheet" type="text/css" href="/design/map/css/map.css">
            <link rel="stylesheet" type="text/css" href="/design/map/css/regions.css">
            <link rel="stylesheet" type="text/css" href="/design/map/css/cities.css">
            <link rel="stylesheet" type="text/css" href="/design/map/css/dots.css">
            <script type="text/javascript" src="/design/map/js/jquery.main.js?var=12"></script>
        <? endif; ?>
    </head>
    <body>
        <!--[if lte IE 9]>
            <div id="ie7">Вы используете устаревший браузер, для корректной работы сайта рекомендуем Вам установить один из следующих браузеров: <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie" target="_blank" title="Internet Explorer">Internet Explorer 10</a>, <a href="http://www.mozilla.org/en-US/" title="Firefox" target="_blank">Firefox</a>, <a href="http://www.opera.com/ru" title="Opera" target="_blank">Opera</a>, <a href="https://www.google.com/intl/ru/chrome/browser/" title="Chrome" target="_blank">Chrome</a></div>
        <![endif]-->
        <div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
        <div class="main-wrapper">
            <header>
                <div class="wrapper">
                    <div class="logo">
                       <? if ($_SERVER["REQUEST_URI"] != "/"): ?> <a href="http://<?=$_SERVER["HTTP_HOST"]?>" title="Logo"><img src="/images/logo.png" width="205" height="39"  alt="Универсал Спецтехника"/></a><? else: ?><img src="/images/logo.png" width="205" height="39"  alt="Универсал Спецтехника"><? endif; ?></div>
                    <div class="phones">
                        <div class="nums">
                            <div class="row"><? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include/phone.php"), false); ?></div>
                            <div class="row"><?= GetPhoneFromTown() ?></div>
                        </div>
                        <div class="region-change">
                            <div class="row">Звонки по России бесплатно </div>
                            <div class="row">Выберите город: <a href="#" id="change_sity" class="cities-link"><? if (isset($_SESSION["SELECTED_TOWN"])): ?><?= $_SESSION["SELECTED_TOWN"] ?><? else: ?>Город не определен<? endif; ?></a></div>
                        </div>                        
                    </div>
                    <div class="quick-links">
                        <div class="row"><a href="#" class="callback-link">Заказать звонок</a></div>
                        <div class="row"><a href="#" class="quick_select_techn">Быстрый выбор машины</a></div>
                    </div>
                    <div class="links">
                        <div class="left">
                            <div class="row mail">
                                <span>&nbsp;</span>
                                <a class="e-mail-1" title="Написать письмо" href="mailto:info@ust-co.ru?subject=Заявка с сайта ust-co.ru">info@ust-co.ru</a>
                            </div>
                            <div class="row"><a href="/akcii/" class="actions"><span></span>Акции</a></div>
                        </div>
                        <div class="right">
                            <a class="ico map-icon" href="/filialy/"></a>
                            <div class="row"><a href="/filialy/">Филиалы</a></div>
                            <div class="row"><a href="/dilery-skladskoj-tehniki/">Дилеры</a></div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="top-nav">
                <div class="wrapper">
                    <?
                    $APPLICATION->IncludeComponent("bitrix:menu", "top", array(
                        "ROOT_MENU_TYPE" => "top",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_TIME" => "36000000",
                        "MENU_CACHE_USE_GROUPS" => "N",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MAX_LEVEL" => "3",
                        "CHILD_MENU_TYPE" => "child",
                        "USE_EXT" => "Y",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N"
                            ), false
                    );
                    ?>
                    <?
                    $APPLICATION->IncludeComponent(
                            "bitrix:search.form", ".default", Array(
                        "USE_SUGGEST" => "N",
                        "PAGE" => "/poisk/"
                            ), false
                    );
                    ?>
                    <div class="social">
                        <a href="http://www.youtube.com/user/universalspec/" class="soc soc-yt" title="Универсал Спецтехника на YouTube" target="_blank" rel="nofollow"></a>
                        <a href="https://www.facebook.com/pages/Универсал-Спецтехника/168428986565663" class="soc soc-fb" title="Универсал Спецтехника на Facebook" target="_blank" rel="nofollow"></a>
                        <a href="https://twitter.com/Universalspec" class="soc soc-tw" title="Универсал Спецтехника в Twitter" target="_blank" rel="nofollow"></a>
<a href="http://instagram.com/universalspetstehnika#" class="soc soc-ins" title="Универсал Спецтехника в Instagram" target="_blank" rel="nofollow"></a>
                    </div>
                    <? /* <div class="lang">
                      <a href="#">Eng</a>
                      </div> */ ?>
                    <div class="up-button"><a href="#">наверх</a></div>
                </div>
            </div>
            <div class="page-content<? if ($_SERVER["REQUEST_URI"] == "/" || $_SERVER["SCRIPT_NAME"] == "/index.php"): ?> main<? endif; ?>">
                <? if ($_SERVER["REQUEST_URI"] != "/" && $_SERVER["SCRIPT_NAME"] != "/index.php"): ?>
                    <?= ShowLeftBar() ?>
                    <?
                    $APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", array(
                        "START_FROM" => "0",
                        "PATH" => "",
                        "SITE_ID" => SITE_ID
                            ), false, Array('HIDE_ICONS' => 'Y')
                    );
                    ?>                    
                    <?= ShowTitleAndBanner() ?>
                <? endif; ?>