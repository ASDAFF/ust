<?
IncludeModuleLangFile(__FILE__);

class CAllCurrencyLang
{
	const SEP_EMPTY = 'N';
	const SEP_DOT = 'D';
	const SEP_COMMA = 'C';
	const SEP_SPACE = 'S';
	const SEP_NBSPACE = 'B';

	static protected $arSeparators = array(
		SEP_EMPTY => '',
		SEP_DOT => '.',
		SEP_COMMA => ',',
		SEP_SPACE => ' ',
		SEP_NBSPACE => ' '
	);

	static protected $arDefaultValues = array(
		'FORMAT_STRING' => '#',
		'DEC_POINT' => '.',
		'THOUSANDS_SEP' => ' ',
		'DECIMALS' => 2,
		'THOUSANDS_VARIANT' => '',
		'HIDE_ZERO' => 'N'
	);

	static protected $arCurrencyFormat = array();

	public function Add($arFields)
	{
		global $DB;
		global $stackCacheManager;
		global $CACHE_MANAGER;

		$arInsert = $DB->PrepareInsert("b_catalog_currency_lang", $arFields);

		$strSql = "insert into b_catalog_currency_lang(".$arInsert[0].") values(".$arInsert[1].")";
		$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		$stackCacheManager->Clear("currency_currency_lang");
		$CACHE_MANAGER->Clean("currency_currency_list");
		$CACHE_MANAGER->Clean("currency_currency_list_".substr($arFields['LID'], 0, 2));

		return true;
	}

	public function Update($currency, $lang, $arFields)
	{
		global $DB;
		global $stackCacheManager;
		global $CACHE_MANAGER;

		$strUpdate = $DB->PrepareUpdate("b_catalog_currency_lang", $arFields);
		if (!empty($strUpdate))
		{
			$strSql = "update b_catalog_currency_lang set ".$strUpdate." where CURRENCY = '".$DB->ForSql($currency, 3)."' and LID='".$DB->ForSql($lang, 2)."'";
			$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

			$stackCacheManager->Clear("currency_currency_lang");
			$CACHE_MANAGER->Clean("currency_currency_list");
			$CACHE_MANAGER->Clean("currency_currency_list_".substr($lang, 0, 2));
			if (isset($arFields['LID']))
				$CACHE_MANAGER->Clean("currency_currency_list_".substr($arFields['LID'], 0, 2));
		}

		return true;
	}

	public function Delete($currency, $lang)
	{
		global $DB;
		global $stackCacheManager;
		global $CACHE_MANAGER;

		$stackCacheManager->Clear("currency_currency_lang");
		$CACHE_MANAGER->Clean("currency_currency_list");
		$CACHE_MANAGER->Clean("currency_currency_list_".substr($lang, 0, 2));

		$strSql = "delete from b_catalog_currency_lang where CURRENCY = '".$DB->ForSql($currency, 3)."' and LID = '".$DB->ForSql($lang, 2)."'";
		$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		return true;
	}

	public function GetByID($currency, $lang)
	{
		global $DB;

		$strSql = "select * from b_catalog_currency_lang where CURRENCY = '".$DB->ForSql($currency, 3)."' and LID = '".$DB->ForSql($lang, 2)."'";
		$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		if ($res = $db_res->Fetch())
			return $res;

		return false;
	}

	public function GetCurrencyFormat($currency, $lang = LANGUAGE_ID)
	{
		global $DB;
		global $stackCacheManager;

		if (defined("CURRENCY_SKIP_CACHE") && CURRENCY_SKIP_CACHE)
		{
			$arCurrencyLang = CCurrencyLang::GetByID($currency, $lang);
		}
		else
		{
			$cacheTime = CURRENCY_CACHE_DEFAULT_TIME;
			if (defined("CURRENCY_CACHE_TIME"))
				$cacheTime = intval(CURRENCY_CACHE_TIME);

			$strCacheKey = $currency."_".$lang;

			$stackCacheManager->SetLength("currency_currency_lang", 20);
			$stackCacheManager->SetTTL("currency_currency_lang", $cacheTime);
			if ($stackCacheManager->Exist("currency_currency_lang", $strCacheKey))
			{
				$arCurrencyLang = $stackCacheManager->Get("currency_currency_lang", $strCacheKey);
			}
			else
			{
				$arCurrencyLang = CCurrencyLang::GetByID($currency, $lang);
				$stackCacheManager->Set("currency_currency_lang", $strCacheKey, $arCurrencyLang);
			}
		}

		return $arCurrencyLang;
	}

	public function GetList(&$by, &$order, $currency = "")
	{
		global $DB;

		$strSql = "select CURL.* from b_catalog_currency_lang CURL ";

		if ('' != $currency)
		{
			$strSql .= "where CURL.CURRENCY = '".$DB->ForSql($currency, 3)."' ";
		}

		if (strtolower($by) == "currency") $strSqlOrder = " order by CURL.CURRENCY ";
		elseif (strtolower($by) == "name") $strSqlOrder = " order by CURL.FULL_NAME ";
		else
		{
			$strSqlOrder = " order BY CURL.LID ";
			$by = "lang";
		}

		if ($order=="desc")
			$strSqlOrder .= " desc ";
		else
			$order = "asc";

		$strSql .= $strSqlOrder;
		$res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		return $res;
	}

	public static function GetDefaultValues()
	{
		return self::$arDefaultValues;
	}

	public static function GetSeparatorTypes($boolFull = false)
	{
		$boolFull = (true == $boolFull);
		if ($boolFull)
		{
			return array(
				SEP_EMPTY => GetMessage('BT_CUR_LANG_SEP_VARIANT_EMPTY'),
				SEP_DOT => GetMessage('BT_CUR_LANG_SEP_VARIANT_DOT'),
				SEP_COMMA => GetMessage('BT_CUR_LANG_SEP_VARIANT_COMMA'),
				SEP_SPACE => GetMessage('BT_CUR_LANG_SEP_VARIANT_SPACE'),
				SEP_NBSPACE => GetMessage('BT_CUR_LANG_SEP_VARIANT_NBSPACE')
			);
		}
		return array(
			SEP_EMPTY,
			SEP_DOT,
			SEP_COMMA,
			SEP_SPACE,
			SEP_NBSPACE
		);
	}

	public static function CurrencyFormat($price, $currency, $useTemplate)
	{
		$boolAdminSection = (defined('ADMIN_SECTION') && true === ADMIN_SECTION);
		$result = '';
		$useTemplate = !!$useTemplate;
		if ($useTemplate)
		{
			foreach(GetModuleEvents('currency', 'CurrencyFormat', true) as $arEvent)
			{
				$result = ExecuteModuleEventEx($arEvent, array($price, $currency));
			}
		}
		if ('' != $result)
			return $result;

		if (!isset($price) || '' === $price)
			return '';

		$currency = (string)$currency;

		if (!isset(self::$arCurrencyFormat[$currency]))
		{
			$arCurFormat = CCurrencyLang::GetCurrencyFormat($currency);
			if (false === $arCurFormat)
			{
				$arCurFormat = self::$arDefaultValues;
			}
			else
			{
				if (!isset($arCurFormat['DECIMALS']))
					$arCurFormat['DECIMALS'] = self::$arDefaultValues['DECIMALS'];
				$arCurFormat['DECIMALS'] = intval($arCurFormat['DECIMALS']);
				if (!isset($arCurFormat['DEC_POINT']))
					$arCurFormat['DEC_POINT'] = self::$arDefaultValues['DEC_POINT'];
				if (!empty($arCurFormat['THOUSANDS_VARIANT']) && isset(self::$arSeparators[$arCurFormat['THOUSANDS_VARIANT']]))
				{
					$arCurFormat['THOUSANDS_SEP'] = self::$arSeparators[$arCurFormat['THOUSANDS_VARIANT']];
				}
				elseif (!isset($arCurFormat['THOUSANDS_SEP']))
				{
					$arCurFormat['THOUSANDS_SEP'] = self::$arDefaultValues['THOUSANDS_SEP'];
				}
				if (!isset($arCurFormat['FORMAT_STRING']))
				{
					$arCurFormat['FORMAT_STRING'] = self::$arDefaultValues['FORMAT_STRING'];
				}
				elseif ($boolAdminSection)
				{
					$arCurFormat["FORMAT_STRING"] = strip_tags(preg_replace(
						'#<script[^>]*?>.*?</script[^>]*?>#is',
						'',
						$arCurFormat["FORMAT_STRING"]
					));
				}
				if (!isset($arCurFormat['HIDE_ZERO']) || empty($arCurFormat['HIDE_ZERO']))
					$arCurFormat['HIDE_ZERO'] = self::$arDefaultValues['HIDE_ZERO'];
			}
			self::$arCurrencyFormat[$currency] = $arCurFormat;
		}
		else
		{
			$arCurFormat = self::$arCurrencyFormat[$currency];
		}
		$intDecimals = $arCurFormat['DECIMALS'];
		if (!$boolAdminSection && 'Y' == $arCurFormat['HIDE_ZERO'])
		{
			if (round($price, $arCurFormat["DECIMALS"]) == round($price, 0))
				$intDecimals = 0;
		}
		$price = number_format($price, $intDecimals, $arCurFormat['DEC_POINT'], $arCurFormat['THOUSANDS_SEP']);
		if (self::SEP_NBSPACE == $arCurFormat['THOUSANDS_VARIANT'])
			$price = str_replace(' ', '&nbsp;', $price);

		return (
			$useTemplate
			? str_replace('#', $price, $arCurFormat['FORMAT_STRING'])
			: str_replace(',', '.', $price)
		);
	}
}
?>