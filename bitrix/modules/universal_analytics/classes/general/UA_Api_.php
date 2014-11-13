<?php

/**
 * Description of UA_Api
 *
 * @author Lebedev Sergey
 */
class UA_Api
{

    public $user_id;
    public $google_api;
    public $ua_users;

    //put your code here
    public function __construct()
    {
        error_reporting(E_ERROR);
        ini_set('display_errors', '1');

        require_once 'UA_Google_Api.php';
        require_once 'UA_Yandex_Api.php';
        require_once 'UA_Users.php';
        $this->ua_users = new UA_Users();
    }

    public function google_connect() //﻿base ﻿1{"access_token":"ya29.ZQAIrVHjjbRLlSEAAADjQoF10I31w7eazzCqou-VWDENSmw5F0s-Oa_3glD2Uc9aRluWqlTkoHSTmVJupyg","token_type":"Bearer","expires_in":3600,"refresh_token":"1\/ulCe5oYXW4_gZaZwMFOw6mFYoJWc6R78OwOgJEisr-E","created":1408377256}2
    {
        $this->google_api = new UA_Google_Api();
        $this->get_test_res_google();
    }

    public function get_test_res_google()
    {
//        $res = $this->google_api->service->data_ga->get(
//                //'ga:' . GA_PROFILE_ID, '2014-08-01', '2014-08-05', 'ga:visits', array(
//                'ga:' . GA_PROFILE_ID, date("Y-m-d"), date("Y-m-d"), 'ga:sessions,ga:hits', array(
//                'dimensions' => 'ga:keyword,ga:date,ga:hour,ga:minute,ga:pagePath,ga:dimension1',
//                'max-results' => '500'
//                )
//        );
//        print "<pre>";
//        print_r($res->query);
//        print_r($res->rows);
//        print "</pre>";

        $optParams = array(
            'dimensions' => 'rt:pagePath,rt:country,rt:keyword'
        );


        try {
            $results = $this->google_api->service->data_realtime->get(
                'ga:90298370',
                'rt:pageviews',
                $optParams);
            $this->printRealtimeReport($results);
        } catch (apiServiceException $e) {
            // Handle API service exceptions.
            $error = $e->getMessage();
            if(isset($error)){
                print_r($error);
            }
        }



        /**
         * 2. Print out the Real-Time Data
         * The components of the report can be printed out as follows:
         */



    }

    function printRealtimeReport($results) {
        $this->printReportInfo($results);
        $this->printQueryInfo($results);
        $this->printProfileInfo($results);
        $this->printColumnHeaders($results);
        $this->printDataTable($results);
        $this->printTotalsForAllResults($results);
    }

    function printDataTable(&$results) {
        $table='';
        if (count($results->getRows()) > 0) {
            $table .= '<table>';

            // Print headers.
            $table .= '<tr>';

            foreach ($results->getColumnHeaders() as $header) {
                $table .= '<th>' . $header->name . '</th>';
            }
            $table .= '</tr>';

            // Print table rows.
            foreach ($results->getRows() as $row) {
                $table .= '<tr>';
                foreach ($row as $cell) {
                    $table .= '<td>'
                        . htmlspecialchars($cell, ENT_NOQUOTES)
                        . '</td>';
                }
                $table .= '</tr>';
            }
            $table .= '</table>';

        } else {
            $table .= '<p>No Results Found.</p>';
        }
        print $table;
    }

    function printColumnHeaders(&$results) {
        $html = '';
        $headers = $results->getColumnHeaders();

        foreach ($headers as $header) {
            $html .= <<<HTML
<pre>
Column Name       = {$header->getName()}
Column Type       = {$header->getColumnType()}
Column Data Type  = {$header->getDataType()}
</pre>
HTML;
        }
        print $html;
    }

    function printQueryInfo(&$results) {
        $query = $results->getQuery();
        $html = <<<HTML
<pre>
Ids         = {$query->getIds()}
Metrics     = {$query->getMetrics()}
Dimensions  = {$query->getDimensions()}
Sort        = {$query->getSort()}
Filters     = {$query->getFilters()}
Max Results = {$query->getMax_results()}
</pre>
HTML;

        print $html;
    }

    function printProfileInfo(&$results) {
        $profileInfo = $results->getProfileInfo();

        $html = <<<HTML
<pre>
Account ID               = {$profileInfo->getAccountId()}
Web Property ID          = {$profileInfo->getWebPropertyId()}
Internal Web Property ID = {$profileInfo->getInternalWebPropertyId()}
Profile ID               = {$profileInfo->getProfileId()}
Profile Name             = {$profileInfo->getProfileName()}
Table ID                 = {$profileInfo->getTableId()}
</pre>
HTML;

        print $html;
    }

    function printReportInfo(&$results) {
        $html = <<<HTML
  <pre>
Kind                  = {$results->getKind()}
ID                    = {$results->getId()}
Self Link             = {$results->getSelfLink()}
Total Results         = {$results->getTotalResults()}
</pre>
HTML;

        print $html;
    }

    function printTotalsForAllResults(&$results) {
        $totals = $results->getTotalsForAllResults();
        $html='';
        foreach ($totals as $metricName => $metricTotal) {
            $html .= "Metric Name  = $metricName\n";
            $html .= "Metric Total = $metricTotal";
        }

        print $html;
    }

    public function __destruct()
    {
        ;
    }

    public function get_all_users()
    {
        
    }

    public function get_user_history()
    {
        
    }

    public function get_user_points()
    {
        
    }

    public function get_user_data_google_ua()
    {
        
    }

    public function get_user_data_yandex()
    {
        
    }

    public function save_user_data_form()
    {
        
    }

    public function save_data_user_form()
    {
        
    }

    public function save_data_google_ua()
    {
        
    }

    public function save_user_data_yandex()
    {
        
    }

    public function authorize($user_id)
    {
        $this->user_id = $user_id;
    }

    public function create_user()
    {
        return $this->ua_users->create_user();
        //$this->ua_users->test();
        
    }

    public function check_user()
    {
        
    }

    public static function test()
    {
        print "test";
    }

}
