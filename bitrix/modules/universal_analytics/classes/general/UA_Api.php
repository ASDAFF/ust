<?php

/**
 * Description of UA_Api
 *
 * @author Lebedev Sergey
 */
class UA_Api
{

    public $user_id;
    //public $google_api;
    public $ua_users;

    //put your code here
    public function __construct()
    {
//        error_reporting(E_ERROR);
//        ini_set('display_errors', '1');

        require_once 'UA_Users.php';

        $this->ua_users = new UA_Users();


    }

    public function google_connect() //﻿base ﻿1{"access_token":"ya29.ZQAIrVHjjbRLlSEAAADjQoF10I31w7eazzCqou-VWDENSmw5F0s-Oa_3glD2Uc9aRluWqlTkoHSTmVJupyg","token_type":"Bearer","expires_in":3600,"refresh_token":"1\/ulCe5oYXW4_gZaZwMFOw6mFYoJWc6R78OwOgJEisr-E","created":1408377256}2
    {
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
    }

    public function __destruct()
    {
        ;
    }

    public function get_all_users()
    {
        
    }

    public function get_user_history(){

        return $this->ua_users->get_user_history();

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
        $userId=$this->ua_users->create_user();
        echo ($userId)? $userId : 'false';
        unset($userId);
    }

    public function check_user()
    {
        
    }

    public function save_user_history(){
        $save_user_history = $this->ua_users->save_user_history();
        $result = ($save_user_history) ? 'user history was saved' : 'false';
        echo $result;
    }

    public function test(){

        if($this->ua_users->visitorInfo()){

            $visitor=$this->ua_users->visitor;
            if(isset($visitor) && is_array($visitor)){
                foreach($visitor as $key=>$value){
                    echo"<b>$key :</b> $value<br>\n";
                }

//                $this->ua_users->update_user_properties();
//                $this->ua_users->create_user_point();
//                $this->ua_users->update_user_log();
            }
            else{
                echo "false";
            }
        }
        else{
            echo "false";
        }

        //unset($visitor);
    }

    public function update_user_properties(){
        $result = ($this->ua_users->update_user_properties())? 'properties updated' : 'false';
        echo $result;
    }

    public function create_user_point(){
        $create_user_point=$this->ua_users->create_user_point();
        $result = ($create_user_point)? $create_user_point : 'false';
        echo $result;
    }

    public function getXML(){

        $this->ua_users->getXML();

    }

    public function create_user_point_by_utm(){

        $create_user_point_by_utm = $this->ua_users->create_user_point_by_utm();

        $result = ($create_user_point_by_utm)? $create_user_point_by_utm : 'false';
        echo $result;

    }

    public function setUserIdCookie(){
        if(isset($_GET['userId'])){
            $userId=addslashes(htmlspecialchars(trim($_GET['userId'])));
            $domain=$_SERVER['SERVER_NAME'];
            if(setcookie('id_user_web',$userId,time()+60*60*24*1000,'/','.'.$domain)){
                exit('cookie id_user_web created');
            }
        }
    }

    public function get_info_by_banner_id(){
        $get_info_by_banner_id = $this->ua_users->get_info_by_banner_id();

        $result = ($get_info_by_banner_id)? $get_info_by_banner_id : 'false';
        print_r($result);
    }
}
