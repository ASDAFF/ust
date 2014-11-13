<?php

class UA_Yandex_Api
{

    public $metrika;

    public function __construct()
    {
        try{
            $token=$this->getToken();

            $id='24904661';

            $today=date("Ymd");

            $id_user_web=$_SESSION["id_user_web"];

            $dimensions='ym:s:paramsLevel1,ym:s:UTMCampaign,ym:s:UTMSource,ym:s:startURL,ym:s:time,ym:s:endURL';

            $metrics='ym:s:pageviews,ym:s:avgParams';

            $filters="ym:s:paramsLevel1=='".$id_user_web."'";

            $limit='1';

            $sort='-ym:s:time';

            $metrika_url = "http://beta.api-metrika.yandex.ru/stat/v1/data?oauth_token=$token&id=$id&accuracy=medium&date1=$today&date2=$today&dimensions=$dimensions&metrics=$metrics&filters=$filters&limit=$limit&sort=$sort";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $metrika_url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            $metrika = curl_exec ($ch);
            curl_close ($ch);

            $this->metrika = json_decode($metrika);

            return $this->metrika;
        }
        catch (Exception $e)
        {
            echo $e->getMessage() ;
        }
    }

    public function getToken(){
        $yandex_get_token_url = "https://oauth.yandex.ru/token";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $yandex_get_token_url);
        curl_setopt($ch, CURLOPT_HEADER, 1); //посмотреть результат запроса
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=password&username=ust-co.ru&password=htlfyjevtshjby77&client_id=30def0a75a404b009e168b1416551d2f&client_secret=45374a49fa9b4698b5c75a206bff37f8');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curl = curl_exec($ch);
        curl_close($ch);

        preg_match("!.*?({.*?})$!si",$curl,$json);
        $arr=json_decode($json['1']);

        return $arr->access_token;
    }


}
