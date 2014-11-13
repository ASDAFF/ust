<?
class CKomboxFilter
{
	private static $bSefMode = false;
	private static $sDocPath2 = '';
	
	public static function IsSefMode() {
        return self::$bSefMode;
    }
	
	public static function GetCurPage($get_index_page=null)
	{
		if (null === $get_index_page)
		{
			if (defined('BX_DISABLE_INDEX_PAGE'))
				$get_index_page = !BX_DISABLE_INDEX_PAGE;
			else
				$get_index_page = true;
		}

		$str = self::$sDocPath2;

		if (!$get_index_page)
		{
			if (($i = strpos($str, '/index.php')) !== false)
				$str = substr($str, 0, $i).'/';
		}

		return $str;
	}
	
	public static function GetCurPageParam($strParam="", $arParamKill=array(), $get_index_page=null)
    {
        $sUrlPath = self::GetCurPage($get_index_page);
        $strNavQueryString = DeleteParam($arParamKill);
        if($strNavQueryString <> "" && $strParam <> "")
            $strNavQueryString = "&".$strNavQueryString;
        if($strNavQueryString == "" && $strParam == "")
            return $sUrlPath;
        else
            return $sUrlPath."?".$strParam.$strNavQueryString;
    }
	
	public static function OnBeforeProlog() {
        $strPath = COption::GetOptionString('kombox.filter', "sef_paths");
		$arPath = explode(';', $strPath);
		
		if(!count($arPath))
			$arPath = array($strPath);
		
		global $APPLICATION;
		$requestURL = $APPLICATION->GetCurPage(true);
		$arUrlParts = explode('/', $requestURL);
		
		foreach($arPath as $path)
		{
			$path = trim($path);
			if(strlen($path))
			{
				if(strpos($requestURL, $path) === 0)
				{
					self::$bSefMode = true;
					break;
				}
			}
		}
		
		if(in_array('filter', $arUrlParts) && self::$bSefMode)
		{
			self::$sDocPath2 = $requestURL;
			
			$requestURL = '/';
			foreach($arUrlParts as $part)
			{
				if(!strlen(trim($part)))continue;
				if($part == 'filter')break;
				$requestURL .= $part.'/';
			}
			$requestURL .= 'index.php';
			
			$param = $APPLICATION->GetCurParam();

			if(strlen($param))
			{
				$requestURL .= '?'.$param;
			}
			
			$APPLICATION->SetCurPage($requestURL);
		}
    }
}
?>