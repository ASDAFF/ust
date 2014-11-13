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

            //define('CLIENT_ID', '402523488309-p4md3quj96ehgeabephhp0arr9tsdfph.apps.googleusercontent.com');
            define('CLIENT_ID', '690774311217-5330jht1g6t4ru43hkfnl4iql3navo4g.apps.googleusercontent.com');
            
           // define('SERVICE_EMAIL', '402523488309-p4md3quj96ehgeabephhp0arr9tsdfph@developer.gserviceaccount.com');
             define('SERVICE_EMAIL', '690774311217-5330jht1g6t4ru43hkfnl4iql3navo4g@developer.gserviceaccount.com');
            
            define('KEY_FILE', $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/universal_analytics/classes/general/google_api/ust-analytics-e5014d659691.p12');
            define('ANALYTICS_SCOPE', 'https://www.googleapis.com/auth/analytics.readonly');
            //define('GA_PROFILE_ID', '85944074');
            define('GA_PROFILE_ID', '90298370');

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


            /*  $res=$this->service->data_ga->get(
              'ga:'.GA_PROFILE_ID, '2014-08-01', '2014-08-05', 'ga:visits', array(
              'dimensions' => 'ga:source,ga:date',
              'sort' => 'ga:date,-ga:visits',
              'filters' => 'ga:visits>100',
              'max-results' => '100'
              )
              );

              print_r($res); */
            return $this->service;
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public static function test()
    {
        print "test";
    }

    //put your code here
}
