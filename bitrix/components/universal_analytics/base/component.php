<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

/*if (CModule::IncludeModule("iblock"))
{

    if (CModule::IncludeModule("universal_analytics"))
    {

        if(!isset($_SESSION["ua_id_user"]))
        {
          //  $API=new UA_Api();
            //$API->add_user();
            //$API->google_connect();
        }
    }  
}*/
// && $_GET['action']!=='setUserIdCookie'
if($_GET['action']!=='getXML'){
    $this->IncludeComponentTemplate();
}
?>