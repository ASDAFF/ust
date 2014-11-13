<?php

class UA_Google_Api
{

    public $service;

    public function __construct()
    {

        try
        {
            require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/universal_analytics/classes/general/google_api/Google_Client.php";
            //require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/universal_analytics/classes/general/google_api/contrib/Google_PlusService.php";
            require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/universal_analytics/classes/general/google_api/contrib/Google_AnalyticsService.php";

            define('CLIENT_ID', '690774311217-5330jht1g6t4ru43hkfnl4iql3navo4g.apps.googleusercontent.com');
            define('SERVICE_EMAIL', '690774311217-5330jht1g6t4ru43hkfnl4iql3navo4g@developer.gserviceaccount.com');
            define('KEY_FILE', $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/universal_analytics/classes/general/google_api/universal-analytics-7c6b237397cf.p12');
            define('ANALYTICS_SCOPE', 'https://www.googleapis.com/auth/analytics.readonly');
            define('GA_PROFILE_ID', '85944074');
            //define('GA_PROFILE_ID', '90298370');

            $client = new Google_Client();
            //$client->setApplicationName('Ust analytics');
            $client->setClientId(CLIENT_ID);
            $client->setAccessType('offline_access');

            $client->setAssertionCredentials(
                    new Google_AssertionCredentials(
                    SERVICE_EMAIL, array(ANALYTICS_SCOPE), file_get_contents(KEY_FILE)
                    )
            );
            $client->setUseObjects(true);

            $this->service = new Google_AnalyticsService($client);

            return $this->service;
        }
        catch (Exception $e)
        {
            setcookie('error',serialize($e->getMessage()),time()+3600,'/','.'.$_SERVER['HTTP_HOST']);
        }

    }

    public static function test()
    {
        print "test";
    }

    //put your code here
}
