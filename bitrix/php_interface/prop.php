<?

class UserDataComplexString
{

    // инициализация пользовательского свойства для инфоблока
    function GetIBlockPropertyDescription()
    {
        return array(
            "PROPERTY_TYPE" => "S",
            "USER_TYPE" => "complex_string",
            "DESCRIPTION" => "Комплексная строка",
            'GetPropertyFieldHtml' => array('UserDataComplexString', 'GetPropertyFieldHtml'),
            'GetAdminListViewHTML' => array('UserDataComplexString', 'GetAdminListViewHTML'),
            "ConvertToDB" => array("UserDataComplexString", "ConvertToDB"),
            "ConvertFromDB" => array("UserDataComplexString", "ConvertFromDB"),
        );
    }

    // представление свойства
    function getViewHTML($name, $value)
    {
        return '';
    }

    // редактирование свойства
    function getEditHTML($name, $value, $is_ajax = false)
    {
        $uid = 'x' . uniqid();
        $dom_id = $uid;
        $val = print_r($value, true);
        $complexCount = 0;
// should return a string
        $returnStr .= "";
        $returnStr .= '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>';
        $returnStr .= '<style type="text/css">';
        $returnStr .= '.lvl2_wrap{';
        $returnStr .= 'padding-left: 30px;';
        $returnStr .= '}';
        $returnStr .= '.desc_wrap, .sort_wrap{';
        $returnStr .= 'display: inline-block;';
        $returnStr .= '}';
        $returnStr .= '</style>';
        if (!is_array($value) || empty($value))
        {
            $returnStr .= '<div class="complex" id="complex_item" index="0">';
            $returnStr .= '<div class="desc_wrap lvl1">';
            $returnStr .= '<p>Заголовок</p>';
            $returnStr .= "<input type=\"text\" class=\"lvl1\" name=\"{$name}[0][desc]\">";
            $returnStr .= '</div>';
            $returnStr .= '<div class="sort_wrap">';
            $returnStr .= '<p>Сортировка</p>';
            $returnStr .= "<input type=\"text\" class=\"sort lvl1\" name=\"{$name}[0][sort]\">";
            $returnStr .= '</div>';
            $returnStr .= '<br>';
            $returnStr .= '<div class="lvl2_wrap">';
            $returnStr .= '<div class="lvl2_item" index="0">';
            $returnStr .= '<div class="desc_wrap">';
            $returnStr .= '<p>Подзаголовок</p>';
            $returnStr .= "<input type=\"text\" class=\"lvl2\" name=\"{$name}[0][items][0][title]\">";
            $returnStr .= '</div>';
            $returnStr .= '<div class="desc_wrap">';
            $returnStr .= '<p>Сортировка</p>';
            $returnStr .= "<input type=\"text\" class=\"sort\" name=\"{$name}[0][items][0][sort]\">";
            $returnStr .= '</div>';
            $returnStr .= '<div class="text_wrap">';
            $returnStr .= '<p>Текст</p>';
            //$returnStr .= "<input type=\"text\" class=\"lvl2\" name=\"{$name}[0][items][0][text]\">";
            $returnStr .= "<textarea rows=\"6\" cols=\"45\" class=\"lvl2\" name=\"{$name}[0][items][0][text]\"></textarea>";
            $returnStr .= '</div>';
            $returnStr .= '</div>';
            $returnStr .= '<input type="button" value="+" class="item_add">';
            $returnStr .= '</div>';
            $returnStr .= '<br>';
            $returnStr .= '</div>';
        }
        else
        {
            foreach ($value as $k => $v)
            {
                $returnStr .= '<div class="complex" id="complex_item" index="' . $k . '">';
                $returnStr .= '<div class="desc_wrap lvl1">';
                $returnStr .= '<p>Заголовок</p>';
                $returnStr .= "<input type=\"text\" class=\"lvl1\" name=\"{$name}[{$k}][desc]\" value=\"{$v['desc']}\">";
                $returnStr .= '</div>';
                $returnStr .= '<div class="sort_wrap lvl1">';
                $returnStr .= '<p>Сортировка</p>';
                $returnStr .= "<input type=\"text\" class=\"sort\" name=\"{$name}[{$k}][sort]\" value=\"{$v['sort']}\">";
                $returnStr .= '</div>';
                $returnStr .= '<br>';
                $returnStr .= '<div class="lvl2_wrap">';
                if(empty($v['items']))
                {
                    $returnStr .= '<div class="lvl2_item" index="0">';
                    $returnStr .= '<div class="desc_wrap">';
                    $returnStr .= '<p>Подзаголовок</p>';
                    $returnStr .= "<input type=\"text\" class=\"lvl2\" name=\"{$name}[{$k}][items][0][title]\">";
                    $returnStr .= '</div>';
                    $returnStr .= '<div class="desc_wrap">';
                    $returnStr .= '<p>Сортировка</p>';
                    $returnStr .= "<input type=\"text\" class=\"sort\" name=\"{$name}[{$k}][items][0][sort]\">";
                    $returnStr .= '</div>';
                    $returnStr .= '<div class="text_wrap">';
                    $returnStr .= '<p>Текст</p>';
                    //$returnStr .= "<input type=\"text\" class=\"lvl2\" name=\"{$name}[0][items][0][text]\">";
                    $returnStr .= "<textarea rows=\"6\" cols=\"45\" class=\"lvl2\" name=\"{$name}[{$k}][items][0][text]\"></textarea>";
                    $returnStr .= '</div>';
                    $returnStr .= '</div>';
                }
                foreach ($v['items'] as $kk => $vv)
                {
                    $returnStr .= '<div class="lvl2_item" index="' . $kk . '">';
                    $returnStr .= '<div class="desc_wrap">';
                    $returnStr .= '<p>Подзаголовок</p>';
                    $returnStr .= "<input type=\"text\" class=\"lvl2\" name=\"{$name}[{$k}][items][{$kk}][title]\" value=\"{$vv['title']}\">";
                    $returnStr .= '</div>';
                    $returnStr .= '<div class="desc_wrap">';
                    $returnStr .= '<p>Сортировка</p>';
                    $returnStr .= "<input type=\"text\" class=\"sort\" name=\"{$name}[{$k}][items][{$kk}][sort]\" value=\"{$vv['sort']}\">";
                    $returnStr .= '</div>';
                    $returnStr .= '<div class="text_wrap">';
                    $returnStr .= '<p>Текст</p>';
// $returnStr .=               "<input type=\"text\" class=\"lvl2\" name=\"{$name}[{$k}][items][{$kk}][text]\" value=\"{$vv['text']}\">";
                    $returnStr .= "<textarea rows=\"6\" cols=\"45\" class=\"lvl2\" name=\"{$name}[{$k}][items][{$kk}][text]\">{$vv['text']}</textarea>";
                    $returnStr .= '</div>';
                    $returnStr .= '</div>';
                }
                $returnStr .= '<input type="button" value="+" class="item_add">';
                $returnStr .= '</div>';
                $returnStr .= '<br>';
                $returnStr .= '</div>';
            }
        }
        $returnStr .= '<input type="button" value="+" class="complex_add">';
        $returnStr .= '<script>';
        $returnStr .= '$(document).ready(function(){';
        $returnStr .= '$(document).on("click", ".item_add", function(){';
        $returnStr .= "var name = '{$name}';";
        $returnStr .= 'var item_templ = $(".lvl2_item:first").clone();';
        $returnStr .= 'var item_index = parseInt($(this).siblings("div:last").attr("index"));';
        $returnStr .= 'var item_parent_index = parseInt($(this).parents("#complex_item").attr("index"));';
        $returnStr .= '$("input", item_templ).val("");';
        $returnStr .= '$("textarea", item_templ).val("");';
        $returnStr .= '$("div:nth-child(1) input", item_templ).attr("name", name+"["+item_parent_index+"][items]["+(item_index+1)+"][title]");';
        $returnStr .= '$("div:nth-child(2) input", item_templ).attr("name", name+"["+item_parent_index+"][items]["+(item_index+1)+"][sort]");';
        $returnStr .= '$("div:nth-child(3) textarea", item_templ).attr("name", name+"["+item_parent_index+"][items]["+(item_index+1)+"][text]");';
        $returnStr .= '$(item_templ).attr("index", item_index+1);';
        $returnStr .= 'item_templ.insertBefore(this);';
        $returnStr .= '});';

        $returnStr .= '$(document).on("click", ".complex_add", function(){';
        $returnStr .= "var name = '{$name}';";
        $returnStr .= 'var complex_templ = $("#complex_item:first").clone();';
        $returnStr .= 'var item_templ = $(".lvl2_item:first").clone();';
        $returnStr .= 'var button = $(".item_add:first").clone();';
        $returnStr .= 'var complex_index = parseInt($(this).siblings("#complex_item:last").attr("index"));';
        $returnStr .= '$(".lvl2_wrap", complex_templ).html("");';

        $returnStr .= '$("div:nth-child(1) input", item_templ).attr("name", name+"["+(complex_index+1)+"][items][0][title]");';
        $returnStr .= '$("div:nth-child(2) input", item_templ).attr("name", name+"["+(complex_index+1)+"][items][0][sort]");';
        $returnStr .= '$("div:nth-child(3) textarea", item_templ).attr("name", name+"["+(complex_index+1)+"][items][0][text]");';

        $returnStr .= '$(".lvl2_wrap", complex_templ).html(item_templ);';
        $returnStr .= '$(".lvl1:nth-child(1) input", complex_templ).attr("name", name+"["+(complex_index+1)+"][desc]");';
        $returnStr .= '$(".lvl1:nth-child(2) input", complex_templ).attr("name", name+"["+(complex_index+1)+"][sort]");';
        $returnStr .= '$("input", complex_templ).val("");';
        $returnStr .= '$("textarea", complex_templ).val("");';
        $returnStr .= 'complex_templ.attr("index", complex_index+1);';
        $returnStr .= 'button.insertAfter(item_templ);';
        $returnStr .= 'complex_templ.insertBefore(this);';
        $returnStr .= '});';
        $returnStr .= '});';
        $returnStr .= '</script>';

        return $returnStr;
    }

    // редактирование свойства в форме (главный модуль)
    function GetEditFormHTML($arUserField, $arHtmlControl)
    {
        return self::getEditHTML($arHtmlControl['NAME'], $arHtmlControl['VALUE'], false);
    }

    // редактирование свойства в списке (главный модуль)
    function GetAdminListEditHTML($arUserField, $arHtmlControl)
    {
        return self::getViewHTML($arHtmlControl['NAME'], $arHtmlControl['VALUE'], true);
    }

    // представление свойства в списке (главный модуль, инфоблок)
    function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName)
    {
        return self::getViewHTML($strHTMLControlName['VALUE'], $value['VALUE']);
    }

    // редактирование свойства в форме и списке (инфоблок)
    function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        return $strHTMLControlName['MODE'] == 'FORM_FILL' ? self::getEditHTML($strHTMLControlName['VALUE'], $value['VALUE'], false) : self::getViewHTML($strHTMLControlName['VALUE'], $value['VALUE'])
        ;
    }

//Сохранение в БД
    function ConvertToDB($arProperty, $value)
    {
        $return = false;
        if (is_array($value))
        {
            foreach ($value["VALUE"] as $k => $v)
            {
                if (empty($v["sort"]))
                {
                    $value["VALUE"][$k]["sort"] = 500;
                }
                foreach ($v["items"] as $kk => $vv)
                {
                    if (empty($vv["text"]) && empty($vv["title"]))
                    {
                        unset($value["VALUE"][$k]["items"][$kk]);
                    }
                    else
                    {
                        if (empty($vv["sort"]))
                        {
                            $value["VALUE"][$k]["items"][$kk]["sort"] = 500;
                        }
                    }
                }
                if (empty($value["VALUE"][$k]["desc"]))
                {
                    unset($value["VALUE"][$k]);
                }
            }
            if (is_array($value) || !empty($value))
            {
                $return = array("VALUE" => serialize($value["VALUE"]),);
                if (strlen(trim($value["DESCRIPTION"])) > 0)
                    $return["DESCRIPTION"] = trim($value["DESCRIPTION"]);
            }
        }
        return $return;
    }

//Извлечение из БД
    function ConvertFromDB($arProperty, $value)
    {
        $return = false;
        if (!is_array($value["VALUE"]))
        {
            $return = array("VALUE" => unserialize($value["VALUE"]),);
            if ($value["DESCRIPTION"])
                $return["DESCRIPTION"] = trim($value["DESCRIPTION"]);
        }

        usort($return["VALUE"], function($a, $b)
        {
            return ($a['sort'] <= $b['sort']) ? -1 : 1;
        });    /**/
        foreach ($return["VALUE"] as &$value)
        {
            usort($value["items"], function($a, $b)
            {
                return ($a['sort'] <= $b['sort']) ? -1 : 1;
            });
        }

        return $return;
    }

}

// добавляем тип для инфоблока
AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("UserDataComplexString", "GetIBlockPropertyDescription"));
?>