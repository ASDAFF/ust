<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)   die();
if (CModule::IncludeModule("iblock"))
{
    if (CModule::IncludeModule("universal_analytics"))
    {
        $API = new UA_Api();

        $action = $_GET["action"];
        switch ($action)    // element $foo[1] doesn't defined 
        {
            case "create_user":
            {
                $API->create_user();
                break;
            }
            case "get_xml_google_data_user":
            { 
                break;
            }
            case "set_session_id_user_web":
            {
                $_SESSION["id_user_web"]=$_GET["id_user_web"];
                print('done');
                break;
            }
            case "get_xml_yandex_data_user":
            {
                
            }
            case "test":
            {
                $API->test();
                break;
            }
            case "getXML":
            {
                $xml=$API->getXML();

                break;
            }
            case "setUserIdCookie":
            {
                $API->setUserIdCookie();
                break;
            }
            case "create_user_point_by_utm":
            {
                $API->create_user_point_by_utm();
                break;
            }
            case "get_info_by_banner_id":
            {
                $API->get_info_by_banner_id();
                break;
            }
            case "save_user_history":
            {
                $API->save_user_history();
                break;
            }
            default:
            { 
                    
            }
        }
    }
}