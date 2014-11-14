<?
session_start();
// ?KEYSSID=c6e997d38cbf40970234883cebe6b4b7
// print '<pre HTTP_REFERER style="display:none">'; print_R($_SERVER["HTTP_REFERER"]); print_r($_COOKIE);print '</pre>';
/*
  $new_doamin = "u-st.ru";
  //setcookie( "TestCookie", "1", time()+(60*60*24*30),"/");
  //unset($_COOKIE['KEYSSID']);
  $debug = "";
  if (!isset($_COOKIE['KEYSSID']))
  {
  if (!isset($_GET["KEYSSID"]))
  {
  // если мы зашли первый раз - то устанавливаем куку сессии для всех доменов
  // session_start();
  $sid = session_id();
  setcookie("KEYSSID", $sid, time() + (60 * 60 * 24 * 30), "/");
  $debug = $debug . "1";
  //print "<pre style='display:none'>";print_r($_SERVER["SERVER_NAME"]); print "</pre>";
  if ($_SERVER["SERVER_NAME"] == "ust-co.ru")
  {
  $uri = $_SERVER["REQUEST_URI"];
  if (strpos("?", $uri) !== false)
  {
  $link_uri = $uri . "&KEYSSID=" . $sid;
  }
  else
  {
  $link_uri = $uri . "?KEYSSID=" . $sid;
  }
  if (isset($link_uri))
  {
  header("Location: http://" . $new_doamin . $link_uri);
  header("Location: http://ust-co.ru" . $link_uri);
  }
  }
  }
  else
  {
  setcookie("KEYSSID", $_GET['KEYSSID'], time() + (60 * 60 * 24 * 30), "/");
  setcookie('PHPSESSID', $_GET['KEYSSID']);
  $sid = $_GET['KEYSSID'];
  // session_start();
  $debug = $debug . "3";
  }
  }
  else
  {
  $debug = $debug . "2";
  // setcookie('PHPSESSID', $_COOKIE['KEYSSID'], time() + (60 * 60 * 24 * 30), "/");
  // session_start();
  }
  /*
  if (isset($_GET["KEYSSID"]))
  {
  setcookie('PHPSESSID', $_GET["KEYSSID"], time() + (60 * 60 * 24 * 30), "/");
  }

  if (isset($_GET["debug"]))
  {
  header("Location: http://u-st.ru/catalog/stroitelnaya-tekhnika/?KEYSSID=f679e88540623712d4361621713d81f4");
  ob_start();
  print ($debug . "sadsa");
  print_R($_COOKIE);
  $str = ob_get_contents();
  ob_clean();
  file_put_contents("/home/u/unispec/unispec.bget.ru/public_html/test.txt", $str);
  } */
require_once('prop.php');
require_once('const.php');
require_once('function.php');
require_once('meta_tags.php');
require_once('uafunctions.php');
require_once('/home/u/unispec/unispec.bget.ru/public_html/bitrix/php_interface/redirect.php');

AddEventHandler('main', 'OnBeforeEventSend', "OnBeforeEventSendHandler");
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "OnAfterIBlockElementAddHandler");
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "OnAfterIBlockElementAddHandler");
AddEventHandler("main", "OnBeforeEventAdd", array("MailHandler", "OnBeforeEventAddHandler"));

function checkUrl($page)
{
    $url = $_SERVER["REQUEST_URI"];
    if ($page == $url)
        return true;
    else
        return FALSE;
}

function OnBeforeEventSendHandler(&$arFields)
{
    $footer = '<br /><p>С уважением, ' . $arFields["SITE_NAME"] . '<br />' . getPhone() . '<br /><a href="http://' . $arFields["SERVER_NAME"] . '">' . $arFields["SERVER_NAME"] . '</p>';
    $arFields["FOOTER"] = $footer;
}

function getPhone()
{
    if (strpos($_SERVER["HTTP_REFERER"], "yandex") !== false || isset($_SESSION["REFERER_YANDEX"]))
    {
        $phone = PHONE_YANDEX;
        $_SESSION["REFERER_YANDEX"] = "1";
    }
    else
    {
        $phone = PHONE_DEFAULT;
    }
    return $phone;
}

function ShowTitleAndFormsComponent()
{
    global $APPLICATION;
    ob_start();
    if ($APPLICATION->GetProperty('ORDER_FORM_RIGHT') == "Y")
    {
        echo "<h1>" . $APPLICATION->GetTitle() . "</h1>";
        $APPLICATION->IncludeComponent("areal:form.order", ".default");
    }
    else
    {
        echo "<h1>" . $APPLICATION->GetTitle() . "</h1>";
    }
    $contentTime = ob_get_contents();
    ob_end_clean();
    return $contentTime;
}

function ShowTitleAndForms()
{
    global $APPLICATION;
    $APPLICATION->AddBufferContent("ShowTitleAndFormsComponent");
}

function ShowLeftBar()
{
    global $APPLICATION;
    $APPLICATION->AddBufferContent("ShowLeftBarComponent");
}

function ShowLeftBarComponent()
{
    global $APPLICATION;
    ob_start();
    $content = "";
    echo '<div class="wrapper">';
    if ($APPLICATION->GetProperty("HIDE_MENU") != "Y")
    {
        echo '<div class="a-side">';
        echo '<div class="side-nav">';
        echo '<ul>';
        $APPLICATION->IncludeComponent("bitrix:menu", "left", array(
            "ROOT_MENU_TYPE" => "left",
            "MENU_CACHE_TYPE" => "N",
            "MENU_CACHE_TIME" => "36000000",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "MENU_CACHE_GET_VARS" => array(
            ),
            "MAX_LEVEL" => "4",
            "CHILD_MENU_TYPE" => "child",
            "USE_EXT" => "Y",
            "DELAY" => "N",
            "ALLOW_MULTI_SELECT" => "N"
                ), false
        );
        $APPLICATION->IncludeComponent(
                "bitrix:menu", "catalog", array(
            "ROOT_MENU_TYPE" => "catalog",
            "MENU_CACHE_TYPE" => "A",
            "MENU_CACHE_TIME" => "360000",
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
        echo '</ul>';
        echo '</div>';
        $APPLICATION->IncludeComponent("areal:banner.flash", "template1", Array(
                ), false
        );
        //24904661
        echo '<div class="shares">';
        echo '<a onclick="yaCounter24904661.reachGoal(\'osen\'); return true;" href="/files/2014Sm.pdf" download="/files/2014Sm.pdf">';
        echo '<img src="http://ust-co.ru/design/images/spec_p.png" width="290" height="117" alt="Скачать прайс" title="Скачать прайс"></a>';
        echo '</div>';
        echo '<div class="print-catalog">';
        echo '<a  onclick="yaCounter24904661.reachGoal(\'print_catalog_online\'); return true;"  class="open_catalog" href="/files/catalogue_2014-2.pdf" target="_blank" rel="nofollow" title="Смотреть печатный каталог">ПЕЧАТНЫЙ КАТАЛОГ<span class="open_catalog">смотреть онлайн версию</span></a><a class="save_catalog"  onclick="yaCounter24904661.reachGoal(\'print_catalog_download\'); return true;"  href="/files/catalogue_2014-2.pdf" download="/files/catalogue_2014-2.pdf" title="Скачать печатный каталог"> <span class="save_catalog">скачать (PDF)</span></a>';
        echo '</div>';
        echo '<div class="take-survey">'; 
        echo '<a href="#" id="take-survey" title="Пройти опрос">Пройдите опрос<span>Ваш ответ поможет нам стать лучше</span></a>';
        $APPLICATION->IncludeComponent("areal:survey.new", ".default");
        echo '</div>';
        if ($APPLICATION->GetProperty("NEWS_LEFT") != "N")
        {
            $APPLICATION->IncludeComponent("bitrix:news.list", "news_left", array(
                "IBLOCK_TYPE" => "about_company",
                "IBLOCK_ID" => "18",
                "NEWS_COUNT" => "3",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "CHECK_DATES" => "Y",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "j F Y",
                "SET_TITLE" => "N",
                "SET_STATUS_404" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N"
                    ), false
            );
        }
        echo '</div>';
        echo '<div class="content">';
    }
    if ($APPLICATION->GetProperty("SHARE") == "Y" || $APPLICATION->GetProperty("PRINT") == "Y")
    {
        echo '<div class="subscribe-plate">';
        if ($APPLICATION->GetProperty("SHARE") == "Y")
            echo '<span class="share">Поделиться</span><div class="share42init"></div>';
        if ($APPLICATION->GetProperty("PRINT") == "Y")
            echo '<a href="' . $APPLICATION->GetCurPageParam("print=y", array("print"), false) . '" class="print" target="_blank" title="Распечатать">Распечатать</a>';
        echo '</div>';
    }
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

function ShowTitleAndBanner()
{
    global $APPLICATION;
    $APPLICATION->AddBufferContent("ShowTitleAndBannerComponent");
}

function ShowTitleAndBannerComponent()
{
    global $APPLICATION;
    ob_start();
    $contentBanner = "";
    echo '<div class="compare header">';
    if (defined("CATALOG_COMPARE_SHOW") && !empty($_SESSION["CATALOG_COMPARE_LIST"][CATALOG]["ITEMS"]))
    {
        $count = 0;
        foreach ($_SESSION["CATALOG_COMPARE_LIST"][CATALOG]["ITEMS"] as $section)
            $count = $count + count($section);
        echo '<a href="/catalog/compare/" title="Перейти в список сравнения">Сравнение (<span>' . $count . '</span>)</a>';
    }
    echo '</div>';
    echo '<div class="clear"></div>';
    if ($APPLICATION->GetProperty("HIDE_MENU") != "Y")
    {
        echo '<h1>';
       if ($APPLICATION->GetPageProperty("h1") != "") echo $APPLICATION->GetPageProperty("h1");
		 else echo $APPLICATION->GetTitle();
        echo '</h1>';
        echo '<hr />';
        $APPLICATION->IncludeComponent("areal:banners.secondary", "template2", Array(
                ), false
        );
    }
    $contentBanner = ob_get_contents();
    ob_end_clean();
    return $contentBanner;
}

function OnAfterIBlockElementAddHandler(&$arFields)
{
    if (CModule::IncludeModule("iblock"))
    {
        AddMessage2Log(print_r($arFields, true));
        if ($arFields["IBLOCK_ID"] == CATALOG && $arFields["RESULT"])
        {
            $elements = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arFields["IBLOCK_ID"], "ID" => $arFields["ID"]), false, false, array("ID", "PROPERTY_GROUP_PAGE", "PROPERTY_SERIES", "PROPERTY_SERIES.CODE"));
            if ($element = $elements->GetNext())
                AddMessage2Log(print_r($element, true));
            if (empty($element["PROPERTY_GROUP_PAGE_VALUE"]) && !empty($element["PROPERTY_SERIES_VALUE"]))
                CIBlockElement::SetPropertyValuesEx($element["ID"], false, array("GROUP_PAGE" => $element["PROPERTY_SERIES_CODE"]));
        }
    }
}

AddEventHandler('main', 'OnEpilog', '_Check404Error', 1);

function _Check404Error()
{
    global $APPLICATION;
    $cp = $APPLICATION->GetCurPage();
    // if (!defined('ADMIN_SECTION') && (defined('CATALOG_ERROR_404')) && (ERROR_404 == 'Y') && ($cp != '/404_2.php'))
    if (!defined('ADMIN_SECTION') and strpos($cp, "catalog") !== false && ($cp != '/404_2.php') && (ERROR_404 == 'Y'))
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        CHTTP::SetStatus("404 Not Found");
        //include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php");
        include($_SERVER["DOCUMENT_ROOT"] . "/404_2.php");
        include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php");
    }
}

AddEventHandler('main', 'OnEpilog', 'setMetaTags', 100);

function setMetaTags()
{
    global $s_meta_array;
    setMetaTagsOnUrl($s_meta_array);
}

//
$domains = get_domains();
//pr($domains,FALSE);
if (isset($domains["code_files"][$_SERVER["HTTP_HOST"]]))
{
    $script_name = str_replace(".php", "_" . $domains["code_files"][$_SERVER["HTTP_HOST"]] . ".php", $_SERVER["SCRIPT_FILENAME"]);


    if (file_exists($script_name))
    {

        AddEventHandler('main', 'OnEpilog', '_CheckPageDomain', 1);
    }
}

function _CheckPageDomain()
{
    $domains = get_domains();
    $script_name = str_replace(".php", "_" . $domains["code_files"][$_SERVER["HTTP_HOST"]] . ".php", $_SERVER["SCRIPT_FILENAME"]);
    global $APPLICATION;
    $APPLICATION->RestartBuffer();
    include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php");
    include($script_name);
    include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php"); /**/
}

//$domains = get_domains();
//pr($_SERVER["SCRIPT_FILENAME"], false);
//header("Location:   /servis/tekhnicheskoe-obsluzhivanie/" );

/* Редиректы */


if (CModule::IncludeModule("iblock"))
{
    $ar_url = explode("/", $_SERVER["REQUEST_URI"]);
    $code = $ar_url[2];

    if (strpos($_SERVER["REQUEST_URI"], "novosti") !== FALSE or strpos($_SERVER["REQUEST_URI"], "statyi") !== FALSE)
    {
        $code = $ar_url[3];
    }

    $domains = get_domains();
    $sections_Code = get_sections_url_on_code();


    // print "<div style='display:none'>";    print_r($sections_Code["bu-stroitelnaya-tekhnika"]); print "</div>";

    if ($code != "")
    {

        if (isset($sections_Code[$code]))
        {
            $name_domain = $domains[$sections_Code[$code]];
        }
        else
        {
            $name_domain = "ust-co.ru";
        }
    }
    //
    //  pr($sections_Code,FALSE);
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
    {
        $is_ajax = true;
    }
    else
    {
        $is_ajax = false;
    }/* */


    if ($_SERVER["REQUEST_URI"] != "/" and strpos($_SERVER["REQUEST_URI"], "filialy") === false and strpos($_SERVER["REQUEST_URI"], ".txt") === false /* and strpos($_SERVER["REQUEST_URI"], "bu-stroitelnaya-tehnika") === false */ and strpos($_SERVER["REQUEST_URI"], "compare") === false /* and strpos($_SERVER["REQUEST_URI"], "arenda") === false */ and $_GET["ajax"] != "y" and $is_ajax == false and strpos($_SERVER["REQUEST_URI"], "akcii") === false)
    {
        if ($_SERVER["HTTP_HOST"] != $name_domain and isset($name_domain))
        {
            $new_link = "http://" . $name_domain . $_SERVER["REQUEST_URI"];
            header("HTTP/1.1 301 Moved Permanently");
            header("Location:  $new_link");
            exit();
        }
        elseif (!isset($name_domain) and $_SERVER["HTTP_HOST"] != "ust-co.ru")
        {
            $new_link = "http://ust-co.ru" . $_SERVER["REQUEST_URI"];
            header("HTTP/1.1 301 Moved Permanently");
            header("Location:  $new_link");
            exit();
        }
    }
}




require_once('formfunctions.php');
?>