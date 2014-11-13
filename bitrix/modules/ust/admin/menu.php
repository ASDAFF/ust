<?
// подключим языковой файл
IncludeModuleLangFile(__FILE__);

$aMenu = array(
    "parent_menu" => "global_menu_content",
    "icon" => "util_menu_icon",
    "page_icon" => "util_page_icon",
    "sort" => 400,
    "text" => GetMessage("FLOW_MENU_MAIN"),
    "title" => GetMessage("FLOW_MENU_MAIN_TITLE"),
    "url" => "ust_setting_admin.php?lang=".LANG    
);
return $aMenu;

?>