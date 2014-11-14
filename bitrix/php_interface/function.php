<?

function pr($v, $show = true)
{
    global $USER;
    if ($USER->IsAdmin())
    {

        if ($show)
            echo "<div style='position:fixed; border:solid 1px red; bottom:50%; right:30px; z-index:9999'><pre>";
        else
            echo "<div style='display:none;'><pre>";
        print_r($v);
        echo "</div></pre>";
    }
}

/* Счетчик обратного отсчета */

function downcounter($date)
{
    $check_time = strtotime($date) - time();
    if ($check_time <= 0)
    {
        return false;
    }

    $days = floor($check_time / 86400);
    $hours = floor(($check_time % 86400) / 3600);
    $minutes = floor(($check_time % 3600) / 60);
    $seconds = $check_time % 60;

    $str = '';
    if ($days > 0)
        $str .= declension($days, array('день', 'дня', 'дней')) . ' ';
    /* if($hours > 0) $str .= $hours.':';
      if($minutes > 0) $str .= $minutes.':';
      if($seconds > 0) $str .= $seconds; */

    return $str;
}

/* Функция склонения слов */

function declension($digit, $expr, $onlyword = false)
{
    if (!is_array($expr))
        $expr = array_filter(explode(' ', $expr));
    if (empty($expr[2]))
        $expr[2] = $expr[1];
    $i = preg_replace('/[^0-9]+/s', '', $digit) % 100;
    if ($onlyword)
        $digit = '';
    if ($i >= 5 && $i <= 20)
        $res = $digit . ' ' . $expr[2];
    else
    {
        $i%=10;
        if ($i == 1)
            $res = $digit . ' ' . $expr[0];
        elseif ($i >= 2 && $i <= 4)
            $res = $digit . ' ' . $expr[1];
        else
            $res = $digit . ' ' . $expr[2];
    }
    return trim($res);
}

function getDateWithTimeText($dateFrom, $dateTo, $flag = true)
{
    $MONTH = array(
        "01" => " января ",
        "02" => " февраля ",
        "03" => " марта ",
        "04" => " апреля ",
        "05" => " мая ",
        "06" => " июня ",
        "07" => " июля ",
        "08" => " августа ",
        "09" => " сентября ",
        "10" => " октября ",
        "11" => " ноября ",
        "12" => " декабря "
    );
    $arFromDT = explode(" ", $dateFrom);
    $arToDT = explode(" ", $dateTo);
    $arFrom = explode(".", $arFromDT[0]);
    $arTo = explode(".", $arToDT[0]);
    $arFrom[0] = (int) $arFrom[0];
    $arTo[0] = (int) $arTo[0];

    if ((!isset($arFromDT[1]) && !isset($arToDT[1])) || $flag === false)
    {
        if ($arFrom[0] == $arTo[0] && $arFrom[1] == $arTo[1] && $arFrom[2] == $arTo[2])
            $str = $arFrom[0] . $MONTH[$arFrom[1]] . $arFrom[2] . " г.";
        elseif ($arFrom[1] == $arTo[1] && $arFrom[2] == $arTo[2])
            $str = $arFrom[0] . " - " . $arTo[0] . $MONTH[$arFrom[1]] . $arFrom[2] . " г.";
        elseif ($arFrom[2] == $arTo[2] && $arFrom[1] != $arTo[1])
            $str = $arFrom[0] . $MONTH[$arFrom[1]] . " - " . $arTo[0] . $MONTH[$arTo[1]] . $arFrom[2] . " г.";
        elseif ($arFrom[2] != $arTo[2])
            $str = $arFrom[0] . $MONTH[$arFrom[1]] . $arFrom[2] . " г. - " . $arTo[0] . $MONTH[$arTo[1]] . $arTo[2] . " г.";
        return $str;
    }
    else
    {
        $arFromTime = explode(":", $arFromDT[1]);
        $arToTime = explode(":", $arToDT[1]);
        $str = $arFrom[0] . $MONTH[$arFrom[1]] . $arFrom[2] . " г. " . $arFromTime[0] . ":" . $arFromTime[1] . ' - ' . $arTo[0] . $MONTH[$arTo[1]] . $arTo[2] . " г. " . $arToTime[0] . ":" . $arToTime[1];
        return $str;
    }
}

function TownDefinition()
{
    if (!isset($_SESSION["SELECTED_TOWN"]))
    {
        $_SESSION["SELECTED_TOWN"] = "Москва";
    }
}

function StrPosCustom($haystack, $needle)
{
    $strpos = strpos(mb_strtolower($haystack), mb_strtolower($needle));
    if ($strpos !== false)
    {
        $first_part = substr($haystack, 0, strlen($needle));
        $second_part = substr($haystack, strlen($needle), strlen($haystack) - strlen($needle));
        return "<span>" . $first_part . "</span>" . $second_part;
    }
    else
        return false;
}

/* Определение телефона в зависимости от города */

function GetPhoneFromTown()
{
    print "<span class='town-num'>";
    TownDefinition();
    if (!empty($_SESSION["SELECTED_TOWN"]) && CModule::IncludeModule("iblock"))
    {
        $res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y", "NAME" => $_SESSION["SELECTED_TOWN"]), false, false, array("ID", "NAME", "PROPERTY_HEADER_PHONE"));
        if ($town = $res->GetNext())
            if (!empty($town["PROPERTY_HEADER_PHONE_VALUE"]))
                return $town["PROPERTY_HEADER_PHONE_VALUE"];
            else
                return false;
    }

    print "</span>";
}

/* Ресайз картинки, где нет фотографии */

function getImageNoPhoto($width, $height)
{
    $width = intVal($width);
    $height = intVal($height);
    $destinationFile = "/design/images/no-photo/pic" . $width . "x" . $height . ".jpg";
    if (!file_exists($_SERVER["DOCUMENT_ROOT"] . $destinationFile))
    {
        $sourceFile = "/design/images/no-photo/no-photo.jpg";
        $full_sourceFile = $_SERVER["DOCUMENT_ROOT"] . $sourceFile;
        $full_destinationFile = $_SERVER["DOCUMENT_ROOT"] . $destinationFile;
        $wmMini = CFile::ResizeImageFile(
                        $full_sourceFile, $full_destinationFile, array('width' => $width, 'height' => $height), BX_RESIZE_IMAGE_PROPORTIONAL, array(), false, false
        );
        if ($wmMini)
            return $destinationFile;
        else
            return $sourceFile;
    }
    else
        return $destinationFile;
}

/* Показывает сотрировку по количеству в разделах */

function ShowPagging($current)
{
    global $APPLICATION;
    $pagging_array = array(6 => 6, 12 => 12, 24 => 24, 10000 => "Все");
    echo '<div class="paging">';
    echo '<form name="sort" action="' . $APPLICATION->GetCurPage(false) . '" method="get" class="on-page">';
    echo '<span>Количество на странице:</span>';
    echo '<select name="quantity">';
    foreach ($pagging_array as $key => $pag)
    {
        echo '<option ';
        if ($key == $current)
            echo 'selected="selected"';
        echo 'value="' . $key . '">' . $pag . '</option>';
    }
    echo '</select>';
    echo '</form>';
    echo '</div>';
    return true;
}

function GetSubDomains()
{
    
}

function redirect301($old, $new)
{
    $url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    if ($url == $old)
    {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $new);
        exit();
    }
}

function setMetaTagsOnUrl($meta_array)
{

    // print "1";
    global $APPLICATION;
    $url_ar = explode("?", $_SERVER['REQUEST_URI']);
    $link = $_SERVER['REQUEST_URI'];
    if (strpos($link, "?") !== false)
    {
        $ar_uri = explode("?", $_SERVER['REQUEST_URI']);
        $link = $ar_uri[0];
    }
    if (isset($meta_array[$link]))
    {
        $meta_tags = $meta_array[$link];
        $APPLICATION->SetPageProperty("title", $meta_tags["title"]);
        $APPLICATION->SetTitle($meta_tags["h1"]); 
        $APPLICATION->SetPageProperty("keywords", $meta_tags["keywords"]);
        $APPLICATION->SetPageProperty("description", $meta_tags["description"]);
    }
}

function get_domains()
{
    if (CModule::IncludeModule("iblock"))
    {
        $obCache = new CPHPCache();
        $cacheLifetime = 360000;
        $cacheID = 'domains';
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
        {
            $vars = $obCache->GetVars();

            $domains = $vars['domains'];
        }
        elseif ($obCache->StartDataCache())
        {
            $filter_subdomain = array('IBLOCK_ID' => SUBDOMAIN, 'ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y');
            $ar_sel_domain = array("ID", "PROPERTY_URL", "PROPERTY_CODE_FILE");
            $els_domain = CIBlockElement::GetList(array(), $filter_subdomain, false, false, $ar_sel_domain);


            while ($el_domain = $els_domain->GetNext())
            {
                $domains[$el_domain["ID"]] = $el_domain["PROPERTY_URL_VALUE"];
                $domains["code_files"][$el_domain["PROPERTY_URL_VALUE"]] = $el_domain["PROPERTY_CODE_FILE_VALUE"];
            }
            $obCache->EndDataCache(array('domains' => $domains));
        }
        return $domains;
    }
}

function get_sections_url_on_name()
{
    if (CModule::IncludeModule("iblock"))
    {
        $obCache = new CPHPCache();
        $domains = get_domains();
        $cacheLifetime = 36000;
        $cacheID = 'sectionsUF';
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
        {
            $vars = $obCache->GetVars();
            $sections_UF = $vars['sectionsUF'];
        }
        elseif ($obCache->StartDataCache())
        {


            $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y"), false, array("NAME", "UF_URL"));
            while ($section_UF = $sections->GetNext())
            {
                $sections_UF[$section_UF["NAME"]] = $section_UF["UF_URL"];
            }



            $sections_arenda = CIBlockSection::GetList(array(), array("IBLOCK_ID" => ARENDA, "ACTIVE" => "Y"), false, array("NAME", "UF_URL", "CODE"));
            while ($section_Name_arenda = $sections_arenda->GetNext())
            {
                $sections_Name_arenda[$section_Name_arenda["NAME"]] = $section_Name_arenda["UF_URL"];
            }


            $sections_news = CIBlockSection::GetList(array(), array("IBLOCK_ID" => NEWS, "ACTIVE" => "Y"), false, array("NAME", "UF_URL", "CODE"));
            while ($section_Name_news = $sections_news->GetNext())
            {
                $sections_Name_news[$section_Name_news["NAME"]] = $section_Name_news["UF_URL"];
            }

            $sections_articles = CIBlockSection::GetList(array(), array("IBLOCK_ID" => ARTICLES, "ACTIVE" => "Y"), false, array("NAME", "UF_URL", "CODE"));
            while ($section_Name_articles = $sections_articles->GetNext())
            {
                $sections_Name_articles[$section_Name_articles["NAME"]] = $section_Name_articles["UF_URL"];
            }

            $sections_UF = array_merge($sections_UF, $sections_Name_arenda, $sections_Name_news, $sections_Name_articles);

            /* */

            foreach ($sections_UF as $key => $section_UF)
            {
                if (is_array($section_UF))
                {
                    $sections_UF[$key] = $section_UF[0];
                }
            }

            $obCache->EndDataCache(array('sectionsUF' => $sections_UF));
        }
        return $sections_UF;
    }
}

function get_sections_data_on_name()
{
    if (CModule::IncludeModule("iblock"))
    {
        $obCache = new CPHPCache();
        $domains = get_domains();
        $cacheLifetime = 360000;
        $cacheID = 'allSectionsData';
        $cachePath = '/' . $cacheID;
        if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
        {
            $vars = $obCache->GetVars();
            $sections_Data = $vars['allSectionsData'];
        }
        elseif ($obCache->StartDataCache())
        {


            $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y"), false, array("NAME", "UF_URL", "DESCRIPTION", "PICTURE", "DETAIL_PICTURE", "CODE"));
            while ($section_Data = $sections->GetNext())
            {
                $sections_Data[$section_Data["NAME"]] = $section_Data;
            }



            $sections_arenda = CIBlockSection::GetList(array(), array("IBLOCK_ID" => ARENDA, "ACTIVE" => "Y"), false, array("NAME", "UF_URL", "DESCRIPTION", "PICTURE", "DETAIL_PICTURE", "CODE"));
            while ($section_Name_arenda = $sections_arenda->GetNext())
            {
                $sections_Name_arenda[$section_Name_arenda["NAME"]] = $section_Name_arenda;
            }


            $sections_news = CIBlockSection::GetList(array(), array("IBLOCK_ID" => NEWS, "ACTIVE" => "Y"), false, array("NAME", "UF_URL", "DESCRIPTION", "PICTURE", "DETAIL_PICTURE", "CODE"));
            while ($section_Name_news = $sections_news->GetNext())
            {
                $sections_Name_news[$section_Name_news["NAME"]] = $section_Name_news;
            }

            $sections_articles = CIBlockSection::GetList(array(), array("IBLOCK_ID" => ARTICLES, "ACTIVE" => "Y"), false, array("NAME", "UF_URL", "DESCRIPTION", "PICTURE", "DETAIL_PICTURE", "CODE"));
            while ($section_Name_articles = $sections_articles->GetNext())
            {
                $sections_Name_articles[$section_Name_articles["NAME"]] = $section_Name_articles;
            }


            $sections_Data = $sections_Data + $sections_Name_arenda + $sections_Name_news + $sections_Name_articles;


            $obCache->EndDataCache(array('allSectionsData' => $sections_Data));
        }
        return $sections_Data;
    }
}

function get_sections_url_on_code()
{
    $obCache = new CPHPCache();
    $cacheLifetime = 360000;
    $cacheID = 'sectionsCode';
    $cachePath = '/' . $cacheID;
    if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
    {
        $vars = $obCache->GetVars();
        $sections_Code = $vars['sectionsCode'];
    }
    elseif ($obCache->StartDataCache())
    {
        $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y"), false, array("NAME", "UF_URL", "CODE"));
        while ($section_Code = $sections->GetNext())
        {
            $sections_Code[$section_Code["CODE"]] = $section_Code["UF_URL"];
        }


        $sections_arenda = CIBlockSection::GetList(array(), array("IBLOCK_ID" => ARENDA, "ACTIVE" => "Y"), false, array("NAME", "UF_URL", "CODE"));
        while ($section_Code_arenda = $sections_arenda->GetNext())
        {
            $sections_Code_arenda[$section_Code_arenda["CODE"]] = $section_Code_arenda["UF_URL"];
        }


        $sections_news = CIBlockSection::GetList(array(), array("IBLOCK_ID" => NEWS, "ACTIVE" => "Y"), false, array("NAME", "UF_URL", "CODE"));
        while ($section_Code_news = $sections_news->GetNext())
        {
            $sections_Code_news[$section_Code_news["CODE"]] = $section_Code_news["UF_URL"];
        }

        $sections_articles = CIBlockSection::GetList(array(), array("IBLOCK_ID" => ARTICLES, "ACTIVE" => "Y"), false, array("NAME", "UF_URL", "CODE"));
        while ($section_Code_articles = $sections_articles->GetNext())
        {
            $sections_Code_articles[$section_Code_articles["CODE"]] = $section_Code_articles["UF_URL"];
        }
        /*
         */

        $sections_Code = array_merge($sections_Code, $sections_Code_arenda, $sections_Code_news, $sections_Code_articles); /**/
        foreach ($sections_Code as $key => $section_Code)
        {
            if (is_array($section_Code))
            {
                $sections_Code[$key] = $section_Code[0];
            }
        }
        $obCache->EndDataCache(array('sectionsCode' => $sections_Code));
    }
    return $sections_Code;
}

function get_sections_url_on_id()
{
    $obCache = new CPHPCache();
    $cacheLifetime = 360000;
    $cacheID = 'sectionsURLID';
    $cachePath = '/' . $cacheID;
    if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
    {
        $vars = $obCache->GetVars();
        $sections_Id = $vars['sectionsURLID'];
    }
    elseif ($obCache->StartDataCache())
    {
        $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => CATALOG, "ACTIVE" => "Y"), false, array("ID","NAME", "UF_URL"));
        while ($section_Id = $sections->GetNext())
        {
            $sections_Id[$section_Id["ID"]] = $section_Id["UF_URL"];
        }
        $obCache->EndDataCache(array('sectionsURLID' => $sections_Id));
    }
    return $sections_Id;
}

function cache_property_request($property_id, $iblock_id, $section_id) {
	$obCache = new CPHPCache();
	$cacheLifetime = 86400*7; $cacheID = 'is_with_friend'.$section_id.'_'.$property_id; $cachePath = '/'.$cacheID;
		if( $obCache->InitCache($cacheLifetime, $cacheID, $cachePath) )
			{
				$vars = $obCache->GetVars();
				$is_with_friend = $vars['is_with_friend'.$section_id]; 
			}
			elseif( $obCache->StartDataCache()  )
				{ 		 
					$proh = 0;
					$arSelect = Array("ID");
					$arFilter = Array("IBLOCK_ID"=>$iblock_id, "SECTION_ID"=>$section_id);
					$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
					while($ob = $res->GetNextElement()){ 
					$arFields = $ob->GetFields();  
					$db_props = CIBlockElement::GetProperty($iblock_id, $arFields["ID"], array("sort" => "asc"), Array("ID"=>$property_id));
					if($ar_props = $db_props->Fetch()) {
					if ($proh == 0) {
						$cur_val = $ar_props["VALUE"];
						$proh++;
					}
					else if ($cur_val != $ar_props["VALUE"]) {
								$is_with_friend = false;
								break;
							}
						}
					}
					$obCache->EndDataCache(array('is_with_friend'.$section_id => $is_with_friend));
				}
	return $is_with_friend;
}
?>