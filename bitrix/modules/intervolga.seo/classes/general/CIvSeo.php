<?php

IncludeModuleLangFile(__FILE__);

/**
 * Class CIvSeo
 */
class CIvSeo {

    var $LAST_ERROR="";

    public static $exclude_options=array('login', 'back_url_admin', 'clear_cache', 'logout_butt', 'bitrix_include_areas'); //Опции для исключения

    private function CheckFields($arFields) {
        /**
         * @global CDatabase $DB
         * @var
         */
        global $DB;
        $this->LAST_ERROR = "";
        $aMsg = array();

        if(strlen($arFields["URL"]) == 0){
            $aMsg[] = array("id"=>"URL", "text"=>GetMessage("intervolga.seo_CHECK_FIELD_URL_REQUIRED"));
        }
        else{
            $arOrder=array();
            $arFilter = array(
                "URL" => $arFields["URL"],
                "LID" => $arFields["LID"]
            );
            $rsSeo = CIvSeo::GetList($arOrder,$arFilter);
            while($el = $rsSeo->Fetch()) {
                if($el["ID"]!=$arFields["ID"])
                {
                    $aMsg[] = array("id"=>"URL", "text"=>GetMessage("intervolga.seo_DUPLICATE_URL"));
                    break;
                }
            }
        }

        if(strlen($arFields["LID"]) > 0)
        {
            $r = CLang::GetByID($arFields["LID"]);
            if(!$r->Fetch())
                $aMsg[] = array("id"=>"LID", "text"=>GetMessage("intervolga.seo_class_rub_err_lang"));
        }
        else
            $aMsg[] = array("id"=>"LID", "text"=>GetMessage("intervolga.seo_class_rub_err_lang2"));

        if(!empty($aMsg))
        {
            $e = new CAdminException($aMsg);
            $GLOBALS["APPLICATION"]->ThrowException($e);
            $this->LAST_ERROR = $e->GetString();
            return false;
        }
        return true;
    }

    /**
     * @param array $arFields
     * @return int|bool ID ���������� ��������
     */
    public function Add($arFields)
    {
        /**
         * @global CDatabase $DB
         */
        global $DB;

        if(!self::CheckFields($arFields))
            return false;

        $ID = $DB->Add("iv_seo", $arFields);

        return $ID;
    }

    /**
     * @param $arFields
     * @return true
     */
    public function Update($ID, $arFields)
    {
        /**
         * @global CDatabase $DB
         */
        global $DB;
        $ID = intval($ID);
        $arFields["ID"]=$ID;

        if(!$this->CheckFields($arFields))
            return false;

        unset($arFields["ID"]);

        $strUpdate = $DB->PrepareUpdate("iv_seo", $arFields);
        if($strUpdate!="")
        {
            $strSql = "UPDATE iv_seo SET ".$strUpdate." WHERE ID=".$ID;
            $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
        }
        return true;
    }

    /**
     * @param $arFields
     * @return bool ���� �� ������� ������
     */
    public static function Delete($ID)
    {
        global $DB;

        if(intval($ID) <= 0)
            return false;

        $res = $DB->Query("DELETE FROM iv_seo WHERE ID=".$ID, false, "File: ".__FILE__."<br>Line: ".__LINE__);

        return $res;
    }

    /**
     * @param array $arOrder
     * @param array $arFilter
     */
    public static function GetList($arOrder=array(), $arFilter=array())
    {
        /**
         * @global CDatabase $DB
         */
        global $DB;

        $arSqlFilter = array();
        foreach($arFilter as $key=>$val)
        {
            if(strlen($val)<=0)
                continue;

            $key = strtoupper($key);
            switch($key)
            {
                case "ID":
                case "LID":
                case "ACTIVE":
                case "CANONICAL":
                case "URL":
                case "H1":
                case "TITLE":
                case "DESCRIPTION":
                case "KEYWORDS":
                case "TEXT1":
                case "TEXT2":
                case "TEXT3":
                    $arSqlFilter[] = "R.".$key." = '".$DB->ForSql($val)."'";
                    break;
                case "%TEXT1":
                    $arSqlFilter[] = "R.TEXT like '%".$DB->ForSql($val)."%'";
                    break;
                case "%TEXT2":
                    $arSqlFilter[] = "R.TEXT like '%".$DB->ForSql($val)."%'";
                    break;
                case "%TEXT3":
                    $arSqlFilter[] = "R.TEXT like '%".$DB->ForSql($val)."%'";
                    break;
                case "LURL":
                    $arSqlFilter[] = "R.URL like '".$DB->ForSql($val)."%'";
                    break;
            }
        }


        $arSqlOrder = array();
        foreach($arOrder as $key=>$val)
        {
            $ord = (strtoupper($val) <> "ASC"? "DESC": "ASC");
            $key = strtoupper($key);

            switch($key)
            {
                case "ID":
                case "ACTIVE":
                case "CANONICAL":
                case "URL":
                case "H1":
                case "TITLE":
                case "DESCRIPTION":
                case "KEYWORDS":
                case "TEXT1":
                case "TEXT2":
                case "TEXT3":
                    $arSqlOrder[] = "R.".$key." ".$ord;
                    break;
            }
        }
        if(count($arSqlOrder) == 0)
            $arSqlOrder[] = "R.ID DESC";
        $sOrder = "\nORDER BY ".implode(", ",$arSqlOrder);

        if(count($arSqlFilter) == 0)
            $sFilter = "";
        else
            $sFilter = "\nWHERE ".implode("\nAND ", $arSqlFilter);

        $strSql = "
			SELECT
				R.ID
				,R.LID
				,R.ACTIVE
				,R.CANONICAL
				,R.URL
				,R.H1
				,R.TITLE
				,R.DESCRIPTION
				,R.KEYWORDS
				,R.TEXT1
				,R.TEXT2
				,R.TEXT3
			FROM
				iv_seo R
			".$sFilter.$sOrder;

        return $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
    }

    public static function GetByID($ID)
    {
        /**
         * @global CDatabase $DB
         */
        global $DB;

        $ID = intval($ID);

        $strSql = "SELECT
             R.ID
            ,R.LID
            ,R.ACTIVE
            ,R.CANONICAL
            ,R.URL
            ,R.H1
            ,R.TITLE
            ,R.DESCRIPTION
            ,R.KEYWORDS
			,R.TEXT1
			,R.TEXT2
			,R.TEXT3
        FROM
            iv_seo R
        WHERE
            R.ID = ".$ID."";

        return $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
    }

    /**
     * ���������� ������ ������ ��� ������������ url [� SITE]
     * ���� �� ������ ����, ����� ���������� ����� ��� �������� �����
     *
     * @param $url
     * @return $CDatabaseResult
     */
    public static function GetForURL($url, $LID = false)
    {
        /**
         * @global CDatabase $DB
         */
        global $DB;

        if(!$LID)
            $LID = SITE_ID;

        $sFilter = "WHERE ACTIVE = 'Y' AND R.URL = '". $DB->ForSql($url)."' AND LID = '".$DB->ForSql($LID)."'";

        $strSql = "
			SELECT
				R.ID
				,R.LID
				,R.ACTIVE
				,R.CANONICAL
				,R.URL
				,R.H1
				,R.TITLE
				,R.DESCRIPTION
				,R.KEYWORDS
				,R.TEXT1
				,R.TEXT2
				,R.TEXT3
			FROM
				iv_seo R
			".$sFilter. "LIMIT 1";

        return $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
    }

    public static function UrlReplacer($url, $varname, $value) // ��������/������� ��������� � URL
    {
        $url = preg_replace('~&amp;~', '&', $url);
        if (is_array($varname)) {
            foreach ($varname as $i => $n) {
                $v = (is_array($value))
                    ? ( isset($value[$i]) ? $value[$i] : NULL )
                    : $value;
                $url = self::UrlReplacer($url, $n, $v);
            }
            return $url;
        }

        preg_match('/^([^?]+)(\?.*?)?(#.*)?$/', $url, $matches);
        $gp = (isset($matches[2])) ? $matches[2] : ''; // GET-���������
        if (!$gp) return $url;

        $pattern = "/([?&])$varname=.*?(?=&|#|\z)/";
        if (preg_match($pattern, $gp)) {
            $substitution = ($value !== '') ? "\${1}$varname=" . preg_quote($value) : '';
            $newgp = preg_replace($pattern, $substitution, $gp); // ����� GET-���������
            $newgp = preg_replace('/^&/', '?', $newgp);
        }
        else    {
            if(!$value || $value == '') return $url;
            $s = ($gp) ? '&' : '?';
            $newgp = $gp.$s.$varname.'='.$value;
        }

        $anchor = (isset($matches[3])) ? $matches[3] : '';
        $newurl = $matches[1].$newgp.$anchor;
        return $newurl;
    }

}
