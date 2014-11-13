<?php

CModule::IncludeModule("iblock");

class UA_Users
{

    public $visitor='';
    public $id_user_web='';

    public function __construct()
    {

        require_once 'Array2XML.php';

        require_once 'UA_Google_Api.php';

        $this->google_api = new UA_Google_Api();

        require_once 'UA_Yandex_Api.php';

        $this->yandex_api = new UA_Yandex_Api();

        $this->id_user_web = $this->sessionWebUserId();

//        if($this->visitorInfo()){
//            $this->update_user_properties();
//            $this->create_user_point();
//            $this->update_user_log();
//        }

        //$this->save_user_history();
    }

    public static function test()
    {

    }

    public function create_user()
    {

        global $USER;
        $el = new CIBlockElement;

        $user_name = ($this->id_user_web)? "Пользователь #" . $this->id_user_web : "Пользователь";

        $PROP = array();
        $PROP["ID_USER_NUM"] = $this->id_user_web;
        $PROP["GEOINFO"] = "";
        $PROP["UTM_CAMPAIGN"] = "";
        $PROP["UTM_BANNER_REFERER"] = "";
        $PROP["UTM_WORD"] = "";
        $PROP["LANDING_PAGE"] = "";
        $PROP["PHONE_SHOWED"] = $this->get_phone_showed();
        $PROP["IP_ADRESS"] = $this->get_ip();
        $PROP["FIRST_DATE"] = date("Y.m.d H:i");

        $arLoadArray = Array(
            "EXTERNAL_ID" => $this->id_user_web,
            "MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
            "IBLOCK_ID" => $this->get_id_iblock(),
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $user_name,
            "ACTIVE" => "Y", // активен
        );
        unset($PROP);

        $user_id = $el->Add($arLoadArray);
        if(!isset($user_id) || $user_id==''){
            return false;
        }else{
            if(!$this->id_user_web){
                $this->update_user_name($user_id, "Пользователь #" . $user_id);
                $this->update_user_num($user_id, $user_id);
                return $user_id;
            }else{
                return $this->id_user_web;
            }
        }
        unset($user_id);
        return false;
    }


    public function create_user_point()
    {
        global $USER;

        if($this->id_user_web && $this->visitor){

            $visitor=$this->visitor;

            $point_name = "Поведение пользователя #".$this->id_user_web;

            $el = new CIBlockElement;
            $arSelect = Array("ID");
            $arFilter = Array(
                "IBLOCK_ID"=>148,
                "ACTIVE"=>"Y",
                "PROPERTY_ID_USER"=>$this->id_user_web,
                "PROPERTY_UTM_SOURCE"=>$visitor['source'],
                "PROPERTY_UTM_CAMPAIGN"=>$visitor['campaign'],
                "PROPERTY_UTM_WORD"=>$visitor['keyword'],
                "PROPERTY_UTM_WORD_PRICE"=>$visitor['price'],
                "PROPERTY_UTM_BANNER_REFERER"=>$visitor['fullReferrer'],
                "NAME"=>$point_name,
                "EXTERNAL_ID" => $this->id_user_web,
            );
            $res = $el->GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
            $res=$res->Fetch();

            $PROP = array();
            $PROP["ID_USER"] = $this->id_user_web;
            $PROP["UTM_SOURCE"] = $visitor['source'];
            $PROP["UTM_CAMPAIGN"] = $visitor['campaign'];
            $PROP["UTM_WORD"] = $visitor['keyword'];
            $PROP["UTM_WORD_PRICE"] = $visitor['price'];
            $PROP["UTM_BANNER_REFERER"] = $visitor['fullReferrer'];


            if(!isset($res['ID'])){
                $el = new CIBlockElement;

                $arLoadArray = Array(
                    "EXTERNAL_ID" => $this->id_user_web,
                    "MODIFIED_BY" => $USER->GetID(),
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => '148',
                    "PROPERTY_VALUES" => $PROP,
                    "NAME" => $point_name,
                    "ACTIVE" => "Y", // активен
                );

                if($el->Add($arLoadArray)){

                    unset($PROP);
                    unset($visitor);
                    unset($res);
                    unset($arLoadArray);

                    return 'user point created';
                }else{
                    //return $el->LAST_ERROR;
                }
            }else{
                $el = new CIBlockElement;

                $arLoadArray = Array(
                    "EXTERNAL_ID" => $this->id_user_web,
                    "MODIFIED_BY" => $USER->GetID(),
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => '148',
                    "PROPERTY_VALUES" => $PROP,
                    "NAME" => $point_name,
                    "ACTIVE" => "Y", // активен
                );

                if($el->Update($res['ID'], $arLoadArray)){


                    unset($PROP);
                    unset($visitor);
                    unset($res);
                    unset($arLoadArray);

                    return 'user point updated';
                }else{
                    //return $el->LAST_ERROR;
                }
            }
        }
        return false;
    }

    public function update_user_name($user_id=null, $name=null)
    {
        if(isset($user_id) && isset($user_id)){
            global $USER;
            $el = new CIBlockElement;
            $arLoadArray = Array(
                "MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
                "IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
                "IBLOCK_ID" => $this->get_id_iblock(),
                "NAME" => $name,
                "ACTIVE" => "Y" // активен
            );
            $el->Update($user_id, $arLoadArray);
        }
        else{
            return false;
        }
    }

    public function update_user_properties(){
        global $USER;

        if($this->id_user_web && $this->visitor){
            $visitor=$this->visitor;
//            if($this->id_user_web){
//                $user_id=$this->id_user_web;
//            }else{
//                $user_id=$visitor['dimension1'];
//            }
//
//            if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='ust-co.ru'){
//                file_get_contents("http://u-st.ru/universal_analytics/api.php?action=setUserIdCookie&userId=".$user_id);
//            }
//
//            if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='u-st.ru'){
//                file_get_contents("http://ust-co.ru/universal_analytics/api.php?action=setUserIdCookie&userId=".$user_id);
//            }

            $el = new CIBlockElement;
            $arSelect = Array("ID","PROPERTY_LANDING_PAGE","PROPERTY_FIRST_DATE","PROPERTY_PHONE_SHOWED","PROPERTY_UTM_WORD");
            $arFilter = Array("IBLOCK_ID"=>145, "ACTIVE"=>"Y", "EXTERNAL_ID"=>$this->id_user_web);
            $res = $el->GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
            $res=$res->Fetch();
            if(!isset($res['ID'])){
                $el = new CIBlockElement;

                $user_name = ($this->id_user_web)? "Пользователь #" . $this->id_user_web : "Пользователь";

                $PROP = array();
                $PROP["ID_USER_NUM"] = $this->id_user_web;
                $PROP["GEOINFO"] = "";
                $PROP["UTM_CAMPAIGN"] = "";
                $PROP["UTM_BANNER_REFERER"] = "";
                $PROP["UTM_WORD"] = "";
                $PROP["LANDING_PAGE"] = "";
                $PROP["PHONE_SHOWED"] = $this->get_phone_showed();
                $PROP["IP_ADRESS"] = $this->get_ip();
                $PROP["FIRST_DATE"] = date("Y.m.d H:i");

                $arLoadArray = Array(
                    "EXTERNAL_ID" => $this->id_user_web,
                    "MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
                    "IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
                    "IBLOCK_ID" => $this->get_id_iblock(),
                    "PROPERTY_VALUES" => $PROP,
                    "NAME" => $user_name,
                    "ACTIVE" => "Y", // активен
                );
                unset($PROP);

                $el->Add($arLoadArray);
            }

            $res = $el->GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
            $res=$res->Fetch();

            $el = new CIBlockElement;
            $id = $res['ID'];
            $PROP = array();
            $PROP["ID_USER_NUM"] = $this->id_user_web;
            $PROP["GEOINFO"] = $visitor['country'];
            //$PROP["UTM_CAMPAIGN"] = $visitor['campaign'];
            //$PROP["UTM_BANNER_REFERER"] = $visitor['fullReferrer'];

            $utmWord=($res['PROPERTY_UTM_WORD_VALUE']=='' || !isset($res['PROPERTY_UTM_WORD_VALUE']))? $visitor['keyword'] : $res['PROPERTY_UTM_WORD_VALUE'];

            $PROP["UTM_WORD"] = $utmWord;

            $landingPage=($res['PROPERTY_LANDING_PAGE_VALUE']=='' || !isset($res['PROPERTY_LANDING_PAGE_VALUE']))? $visitor['pagePath'] : $res['PROPERTY_LANDING_PAGE_VALUE'];

            $PROP["LANDING_PAGE"] = $landingPage;

            $PROP["LAST_PAGE"] = $visitor['pagePath'];

            $PROP["PHONE_SHOWED"] = $res['PROPERTY_PHONE_SHOWED_VALUE'];
            $PROP["IP_ADRESS"] = $this->get_ip();
            $PROP["FIRST_DATE"] = $res['PROPERTY_FIRST_DATE_VALUE'];
            $PROP["LAST_DATE"] = $visitor['date'];

            $arLoadArray = Array(
                "MODIFIED_BY" => $USER->GetID(), // элемент изменен текущим пользователем
                "IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
                "IBLOCK_ID" => $this->get_id_iblock(),
                "PROPERTY_VALUES" => $PROP,
                "ACTIVE" => "Y" // активен
            );
            unset($utmWord);
            unset($landingPage);
            unset($PROP);
            unset($visitor);
            unset($res);
            if($el->LAST_ERROR){
                //print $el->LAST_ERROR;
            }
            return $el->Update($id, $arLoadArray);

        }
    }

    public function update_user_num($user_id, $user_num)
    {
        CIBlockElement::SetPropertyValueCode($user_id, "ID_USER_NUM", $user_num);
    }

    private function get_id_iblock()
    {
        $res = CIBlock::GetList(
                        Array(), Array(
                    'TYPE' => 'universal_analytics',
                    'SITE_ID' => SITE_ID,
                    'ACTIVE' => 'Y',
                    "CNT_ACTIVE" => "Y",
                    "CODE" => 'ua_users'
                        ), true
        );
        $ar_res = $res->Fetch();
        return $ar_res["ID"];
    }

    private function get_ip()
    {
        return $_SERVER["REMOTE_ADDR"];
    }

    private function get_phone_showed()
    {
        return $_SESSION["PROPERTY_PHONE_VALUE"];
    }

    //put your code here

    public function get_user_history(){

        $request=(!empty($_GET['REQUEST_URI'])) ? urldecode($_GET['REQUEST_URI']) : $_SERVER["REQUEST_URI"];
        if(preg_match("!(.*?)\?!si",$request,$reqArr)){
            $request=$reqArr['1'];
        }

        if(isset($request) && preg_match("!catalog/.*?/!si",$request) && $this->id_user_web){

// && isset($_SESSION['crumbs'])

            $sectionsNames=get_sections_name_on_code();
            $elementsNames=get_catalog_element_name_on_code();



            preg_match("!catalog/(.*?)/$!si",$request,$requestArr);
            $requestArr=explode("/",$requestArr[1]);

            $historyArr=array();

            $category=(isset($requestArr['0'])) ? $requestArr['0'] : false ;

            //setcookie('category',serialize($sectionsNames[$category]),time()+10,'/','.u-st.ru');

            if(isset($category) && $category!=='' && isset($sectionsNames[$category])){

                $historyArr['CATEGORY']=array(
                    //"ID" => $category,
                    "NAME" => $sectionsNames[$category],
                );

            }

            $product=(isset($requestArr['1'])) ? $requestArr['1'] : false ;

            if(!empty($product) && isset($elementsNames[$product])){

                $historyArr['PRODUCT']=array(
                    //"ID" => $category,
                    "NAME" => $elementsNames[$product],
                );

            }
            unset($request);
            unset($category);
            unset($product);

            return $historyArr;
        }
        else{
            return false;
        }
    }

    public function save_user_history(){

        $historyArr=$this->get_user_history();

        if(!empty($historyArr) && is_array($historyArr) && count($historyArr)>0){
            global $USER;

            $categoryName = ($historyArr['CATEGORY']['NAME'])? $historyArr['CATEGORY']['NAME'] : '';
            $productName = ($historyArr['PRODUCT']['NAME'])? $historyArr['PRODUCT']['NAME'] : '';

            $name="История поведения пользователя #".$this->id_user_web;

            $el = new CIBlockElement;

            //"PROPERTY_LAST_CATEGORY_ID","PROPERTY_LAST_PRODUCT_ID",
            $arSelect = Array("ID","PROPERTY_LAST_CATEGORY_NAME","PROPERTY_LAST_PRODUCT_NAME");
            $arFilter = Array("IBLOCK_ID"=>149, "ACTIVE"=>"Y", "PROPERTY_ID_USER"=>$this->id_user_web, "PROPERTY_LAST_CATEGORY_NAME"=>$categoryName, "PROPERTY_LAST_PRODUCT_NAME"=>$productName, "NAME" => $name);

            $res = $el->GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
            $res=$res->Fetch();

            if(!isset($res['ID'])){

                $el = new CIBlockElement;

                $PROP = array();
                $PROP["ID_USER"] = $this->id_user_web;
                $PROP["LAST_CATEGORY_NAME"] = $categoryName;
                $PROP["LAST_PRODUCT_NAME"] = $productName;

                $arLoadArray = Array(
                    //"EXTERNAL_ID" => $this->id_user_web,
                    "IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
                    "IBLOCK_ID" => 149,
                    "PROPERTY_VALUES" => $PROP,
                    "ACTIVE" => "Y", // активен
                    "NAME" => $name,
                );

                unset($categoryName);
                unset($productName);
                unset($PROP);
                unset($historyArr);
                unset($res);

                if($el->LAST_ERROR){
                    //return $el->LAST_ERROR;
                }

                return $el->Add($arLoadArray);
            }
            else{
                $el = new CIBlockElement;
                $id= $res['ID'];
                $PROP = array();
                $PROP["ID_USER"] = $this->id_user_web;
                $PROP["LAST_CATEGORY_NAME"] = $res['PROPERTY_LAST_CATEGORY_NAME_VALUE'];
                $PROP["LAST_PRODUCT_NAME"] = $res['PROPERTY_LAST_PRODUCT_NAME_VALUE'];

                $arLoadArray = Array(
                    //"EXTERNAL_ID" => $this->id_user_web,
                    "IBLOCK_SECTION_ID" => false, // элемент лежит в корне раздела
                    "IBLOCK_ID" => 149,
                    "PROPERTY_VALUES" => $PROP,
                    "ACTIVE" => "Y", // активен
                    "NAME" => $name,
                );

                unset($PROP);
                unset($historyArr);
                unset($res);

                if($el->LAST_ERROR){
                    //return $el->LAST_ERROR;
                }

                return $el->Update($id, $arLoadArray);
            }

        }
        else{
            return false;
        }
    }

    public function visitorInfo(){

        if($this->id_user_web){

            $this->update_user_log();

            $this->visitor='';
            //unset($_SESSION['visitor']);
            $visitor=array();

            $GAKeys=array('dimension1','keyword','pagePath','country','campaign','hits','pageviews','fullReferrer','source');

            $charLine=$this->generateCharLine(10);

            $result = $this->google_api->service->data_ga->get(
                'ga:' . GA_PROFILE_ID, date("Y-m-d"), date("Y-m-d"), 'ga:hits,ga:pageviews', array(
                    'dimensions'        => 'ga:dateHour,ga:minute,ga:dimension1,ga:keyword,ga:pagePath,ga:country,ga:campaign',
                    'filters'           => 'ga:dimension1=='.$this->id_user_web.',ga:dateHour=='.date("YmdH").',ga:dimension1=='.$charLine,
                    'sort'              => '-ga:dateHour,-ga:minute',
                    'max-results'       => '1'
                )
            );

            $result2 = $this->google_api->service->data_ga->get(
                'ga:' . GA_PROFILE_ID, date("Y-m-d"), date("Y-m-d"), 'ga:hits,ga:pageviews', array(
                    'dimensions'        => 'ga:dateHour,ga:minute,ga:dimension1,ga:keyword,ga:fullReferrer,ga:source',
                    'filters'           => 'ga:dimension1=='.$this->id_user_web.',ga:dateHour=='.date("YmdH").',ga:dimension1=='.$charLine,
                    'sort'              => '-ga:dateHour,-ga:minute',
                    'max-results'       => '1'
                )
            );

            if(isset($result2->rows['0'])){
                $results2=array(
                    $result2->rows['0']['4'],
                    $result2->rows['0']['5'],

                );
                $result->rows['0']=array_merge($result->rows['0'],$results2);
            }
            if(isset($result->rows)){
                $result=$result->rows['0'];
                if(isset($result['0']) && isset($result['1'])){
                    preg_match("!([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})!si",$result['0'].$result['1'],$dateArr);
                    $date=$dateArr['1'].'.'.$dateArr['2'].'.'.$dateArr['3'].' '.$dateArr['4'].':'.$dateArr['5'];
                    unset($result['0']);
                    unset($result['1']);
                    $result=array_values($result);

                }else{
                    $date=date("Y.m.d H:i");
                }

                foreach($GAKeys as $key=>$value){
                    if(isset($result[$key])){
                        $visitor[$value]=$result[$key];
                    }else{
                        unset($visitor[$value]);
                    }
                }
                $visitor['date']=$date;

                if(isset($visitor['source']) && $visitor['source']=='yandex'){
                    //Yandex API
//                    print_r($this->yandex_api->totals);


                }

                //$_SESSION['visitor']=$visitor;
                if($visitor['dimension1']==$this->id_user_web){
                    $this->visitor=$visitor;
                    unset($visitor);
                    unset($result);
                    unset($result2);

                    $this->update_user_properties();
                    $this->create_user_point();

                    return true;
                }else{
                    $this->visitor=false;
                    unset($visitor);
                    unset($result);
                    unset($result2);
                    return false;
                }


                //


            }
            else{
                $this->visitor=false;
                return false;
            }
        }
        else{
            $this->visitor=false;
            return false;
        }
    }

    public function update_user_log(){
// &&
        if($this->id_user_web){

            $logsPath=$_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/universal_analytics/classes/general/user_logs/log_".$this->id_user_web.".xml";
            $log=file_get_contents($logsPath);
            $xml = simplexml_load_string($log);

            $json=json_encode($xml);

            $array = json_decode($json,true);
            //$array=$array['root'];

            $logName='logInfo'.time();

            $webpage = (!empty($_GET['REQUEST_URI'])) ? $_GET['REQUEST_URI'] : 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

            if(!preg_match("!action\=test!si",$webpage)){
                $array[$logName]=array();

                $array[$logName]['timestamp']=date("d.m.Y H:i:s");
                $array[$logName]['webpage']=$webpage;
                if (function_exists('http_response_code')) {
                    $array[$logName]['responsecode']=http_response_code();
                }

                $xml = Array2XML::createXML('root', $array);
                $data=$xml->saveXML();
                $handle = fopen($logsPath, "w+");
                fwrite($handle, $data);
                fclose($handle);

            }else{
                echo $webpage;
            }

        }

    }

    public function getXML(){
        if(isset($_GET['datatype'])){

            $datatype=addslashes(htmlspecialchars(trim($_GET['datatype'])));
            $userId=addslashes(htmlspecialchars(trim($_GET['userId'])));




            switch ($datatype)
            {
                case "user_points":
                {
                    $outArr=$this->get_user_points_from_infoblock($userId);
                    break;
                }
                case "user_forms":
                {
                    $outArr=$this->get_user_forms_from_infoblock($userId);
                    break;
                }
                case "user_history":
                {
                    $outArr=$this->get_user_history_from_infoblock($userId);
                    break;
                }
                case "user":
                {
                    $outArr=$this->get_user_from_infoblock($userId);
                    break;
                }
                default:
                    {
                    $outArr=array('error'=>'unknown datatype');
                    }
            }
            if(isset($outArr)){
                //exit(print_r($outArr));
                header ("Content-Type:text/xml");

                $xml = Array2XML::createXML('root', $outArr);
                exit($xml->saveXML());
            }
        }
    }

    private function get_user_points_from_infoblock($userId=null){

        if(isset($userId) && $userId!==''){

            $el = new CIBlockElement;

            $arFilter = Array("IBLOCK_ID"=>148, "ACTIVE"=>"Y", "EXTERNAL_ID"=>$userId);
            $arSelect = Array("ID","NAME","PROPERTY_UTM_SOURCE","PROPERTY_UTM_CAMPAIGN","PROPERTY_UTM_WORD","PROPERTY_UTM_WORD_PRICE","PROPERTY_UTM_BANNER_REFERER");

            $rslt = $el->GetList(Array(), $arFilter, false, Array("nPageSize"=>1000), $arSelect);
            //$res=$rslt->Fetch();

            $n=1;
            $results=array();
            while ($result = $rslt->GetNext()){
                if(isset($result['ID'])){

                    foreach($result as $key=>$value){
                        if(preg_match("!\~(.*?)$!si",$key,$keyArr)){
                            $result[$keyArr['1']]=$value;
                            unset($result[$key]);
                        }
                    }

                    foreach($result as $key=>$value){
                        if(preg_match("!^PROPERTY_(.*?)_VALUE$!si",$key,$keyArr)){
                            $result[$keyArr['1']]=$value;
                            unset($result[$key]);
                        }
                        if(preg_match("!^PROPERTY_(.*?)_VALUE_ID$!si",$key)){
                            unset($result[$key]);
                        }
                    }
                    $results["point$n"]=$result;

                }
                $n++;
            }
            return $results;

        }
        else{
            return array('error'=>'not found userID');
        }
    }

    private function get_user_from_infoblock($userId=null){

        if(isset($userId) && $userId!==''){

            $el = new CIBlockElement;

            $arFilter = Array("IBLOCK_ID"=>145, "ACTIVE"=>"Y", "EXTERNAL_ID"=>$userId);
            $arSelect = Array("ID","NAME","PROPERTY_ID_USER_NUM","PROPERTY_GEOINFO","PROPERTY_UTM_CAMPAIGN","PROPERTY_LANDING_PAGE","PROPERTY_PHONE_SHOWED","PROPERTY_IP_ADRESS","PROPERTY_FIRST_DATE","PROPERTY_LAST_DATE","PROPERTY_LAST_PAGE");

            $res = $el->GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
            $res=$res->Fetch();
            if(isset($res['ID'])){

                foreach($res as $key=>$value){
                    if(preg_match("!^PROPERTY_(.*?)_VALUE$!si",$key,$keyArr)){
                        $res[$keyArr['1']]=$value;
                        unset($res[$key]);
                    }
                    if(preg_match("!^PROPERTY_(.*?)_VALUE_ID$!si",$key)){
                        unset($res[$key]);
                    }
                }

                return $res;
            }
            else{
                return array('error'=>'wrong userID');
            }
        }
        else{
            return array('error'=>'not found userID');
        }
    }

    private function get_user_history_from_infoblock($userId=null){

        if(isset($userId) && $userId!==''){

            $history=array();

            $el = new CIBlockElement;

            $arFilter = Array("IBLOCK_ID"=>149, "ACTIVE"=>"Y", "PROPERTY_ID_USER"=>$userId);
            $arSelect = Array("ID","NAME","PROPERTY_ID_USER","PROPERTY_LAST_CATEGORY_NAME","PROPERTY_LAST_PRODUCT_NAME");

            $result = $el->GetList(Array(), $arFilter, false, array("nPageSize"=>1000), $arSelect);
            //$res=$res->Fetch();

            $n=0;
            while($res=$result->GetNext()){
                if(isset($res['ID'])){

                    $n++;

                    foreach($res as $key=>$value){
                        if(preg_match("!\~(.*?)$!si",$key,$keyArr)){
                            $res[$keyArr['1']]=$value;
                            unset($res[$key]);
                        }
                    }
                    foreach($res as $key=>$value){
                        if(preg_match("!^PROPERTY_(.*?)_VALUE$!si",$key,$keyArr)){
                            $res[$keyArr['1']]=$value;
                            unset($res[$key]);
                        }
                        if(preg_match("!^PROPERTY_(.*?)_VALUE_ID$!si",$key)){
                            unset($res[$key]);
                        }
                    }

                    $history["history$n"] =$res;

                    //exit(print_r($forms));
                }
            }

            return $history;
        }
        else{
            return array('error'=>'not found userID');
        }
    }

    private function get_user_forms_from_infoblock($userId=null){

        if(isset($userId) && $userId!==''){

            $forms=array();

            $el = new CIBlockElement;

            $arFilter = Array("IBLOCK_ID"=>146, "ACTIVE"=>"Y", "EXTERNAL_ID"=>$userId);
            $arSelect = Array("ID","NAME","PROPERTY_ID_USER","PROPERTY_ID_FORM","PROPERTY_DATA");

            $result = $el->GetList(Array(), $arFilter, false, array(), $arSelect);
            //$res=$res->Fetch();

            $n=0;
            while($res=$result->GetNext()){
                if(isset($res['ID'])){

                    $n++;
                    //$xml = simplexml_load_string();

                    //$json=json_encode();


                    foreach($res as $key=>$value){
                        if(preg_match("!\~(.*?)$!si",$key,$keyArr)){
                            $res[$keyArr['1']]=$value;
                            unset($res[$key]);
                        }
                    }

                    $array = json_decode($res['PROPERTY_DATA_VALUE'],true);
                    $res['DATA']=$array;
                    unset($res['PROPERTY_DATA_VALUE']);

                    foreach($res as $key=>$value){
                        if(preg_match("!^PROPERTY_(.*?)_VALUE$!si",$key,$keyArr)){
                            $res[$keyArr['1']]=$value;
                            unset($res[$key]);
                        }
                        elseif(preg_match("!^PROPERTY_(.*?)_VALUE_ID$!si",$key)){
                            unset($res[$key]);
                        }
                    }

                    $forms["form$n"] =$res;

                    //exit(print_r($forms));
                }
            }

            return $forms;
        }
        else{
            return array('error'=>'not found userID');
        }
    }

    private function sessionWebUserId(){
        if(isset($_SESSION["id_user_web"]) && $_SESSION["id_user_web"]!==''){
            return $_SESSION["id_user_web"];
        }
        else{
            unset($_SESSION["id_user_web"]);
            return false;
        }
    }

    private  function generateCharLine($len){
        $charline=array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
        $line='';
        for($i=0; $i<$len; $i++){
            $line.=$charline[array_rand($charline)];
        }
        return $line;
    }

    public function create_user_point_by_utm(){


        $source=addslashes(htmlspecialchars(trim($_GET['source'])));
        $campaign=addslashes(htmlspecialchars(trim($_GET['campaign'])));
        $content=addslashes(htmlspecialchars(trim($_GET['content'])));
        $banner=addslashes(htmlspecialchars(trim($_GET['banner'])));
        $url=addslashes(htmlspecialchars(trim($_GET['REQUEST_URI'])));

        global $USER;

        if(!empty($source) && $source=='yandex'){
            $banner_info=$this->get_info_by_yandex_banner_id($banner);
        }


        if($this->id_user_web && is_object($banner_info)){

            $this->update_user_log();

            $point_name = "Поведение пользователя #".$this->id_user_web;

            $el = new CIBlockElement;
            $arSelect = Array("ID");
            $arFilter = Array(
                "IBLOCK_ID"=>148,
                "ACTIVE"=>"Y",
                "PROPERTY_ID_USER"=>$this->id_user_web,
                "PROPERTY_UTM_SOURCE"=>$source,
                "PROPERTY_UTM_CAMPAIGN"=>$banner_info->CampaignID,
                "PROPERTY_UTM_WORD"=>$banner_info->Phrase,
                "PROPERTY_UTM_WORD_PRICE"=>$banner_info->CurrentOnSearch,
                "PROPERTY_UTM_BANNER_REFERER"=>$url,
                "NAME"=>$point_name,
                "EXTERNAL_ID" => $this->id_user_web,
            );
            $res = $el->GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
            $res=$res->Fetch();

            $PROP = array();
            $PROP["ID_USER"] = $this->id_user_web;
            $PROP["UTM_SOURCE"] = $source;
            $PROP["UTM_CAMPAIGN"] = $banner_info->CampaignID;
            $PROP["UTM_WORD"] = $banner_info->Phrase;
            $PROP["UTM_WORD_PRICE"] = $banner_info->CurrentOnSearch;
            $PROP["UTM_BANNER_REFERER"] = $url;



            if(!isset($res['ID'])){
                $el = new CIBlockElement;

                $arLoadArray = Array(
                    "EXTERNAL_ID" => $this->id_user_web,
                    "MODIFIED_BY" => $USER->GetID(),
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => '148',
                    "PROPERTY_VALUES" => $PROP,
                    "NAME" => $point_name,
                    "ACTIVE" => "Y", // активен
                );

                if($el->Add($arLoadArray)){

                    unset($PROP);
                    unset($visitor);
                    unset($res);
                    unset($arLoadArray);

                    return 'user point by utm created';
                }else{
                    //return $el->LAST_ERROR;
                }
            }else{
                $el = new CIBlockElement;

                $arLoadArray = Array(
                    "EXTERNAL_ID" => $this->id_user_web,
                    "MODIFIED_BY" => $USER->GetID(),
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => '148',
                    "PROPERTY_VALUES" => $PROP,
                    "NAME" => $point_name,
                    "ACTIVE" => "Y", // активен
                );

                if($el->Update($res['ID'], $arLoadArray)){


                    unset($PROP);
                    unset($visitor);
                    unset($res);
                    unset($arLoadArray);

                    return 'user point by utm updated';
                }else{
                    //return $el->LAST_ERROR;
                }
            }
        }
        return false;
    }

    public function get_info_by_yandex_banner_id($banner_id=null){
        $banner_id=($banner_id)? $banner_id : addslashes(htmlspecialchars(trim($_GET['banner_id'])));

        $yandex_get_token_url = "https://oauth.yandex.ru/token";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $yandex_get_token_url);
        curl_setopt($ch, CURLOPT_HEADER, 1); //посмотреть результат запроса
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=password&username=imedia-ust&password=Thohtej0&client_id=7bf3665ab3944914940f24ff52923438&client_secret=e669584345544c12aebf7557d84bdc94');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curl = curl_exec($ch);
        curl_close($ch);

        preg_match("!.*?({.*?})$!si",$curl,$json);
        $arr=json_decode($json['1']);

        $token=$arr->access_token;

        $method = 'GetBannerPhrasesFilter';

        $params = array(
            'BannerIDS' => array($banner_id,),
            'FieldsNames' => array(
                'CurrentOnSearch',
                'Phrase',
            ),
        );

        # перекодировка строковых данных в UTF-8
        function utf8($struct) {
            foreach ($struct as $key => $value) {
                if (is_array($value)) {
                    $struct[$key] = utf8($value);
                }
                elseif (is_string($value)) {
                    $struct[$key] = utf8_encode($value);
                }
            }
            return $struct;
        }

        $request = array(
            'token'=> $token,
            'method'=> $method,
            'param'=> utf8($params),
        );

        $request = json_encode($request);

        $opts = array(
            'http'=>array(
                'method'=>"POST",
                'content'=>$request,
            )
        );

//        $context = stream_context_create($opts);
//
//        $result = file_get_contents('https://api.direct.yandex.ru/v4/json/', 0, $context);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.direct.yandex.ru/v4/json/');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        $headers = array('Content-type: application/json; charset=utf-8','Expect:');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $query = array(
            "method"         => $method,
            "locale"         => "ru",
            "login"          => 'imedia-ust',
            "application_id" => '7bf3665ab3944914940f24ff52923438',
            "token"          => $token,
            "param"         => utf8($params)
        );

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($query));

        $result = curl_exec($curl);

        curl_close($curl);

        $result=json_decode($result, true);

        if(isset($result['data'][0])){
            $result=$result['data'][0];
            $arr = array(
                'PhraseID'          => $result['PhraseID'],
                'CurrentOnSearch'   => round($result['CurrentOnSearch'],1),
                'CampaignID'        => $result['CampaignID'],
                'BannerID'          => $result['BannerID'],
                'Phrase'            => $result['Phrase'],
            );
            return (object)$arr;
        }

        return false;
        //return serialize($result);
    }
}
