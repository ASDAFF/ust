<?
IncludeModuleLangFile(__FILE__);

class CAllCurrency
{
/*
* @deprecated deprecated since catalog 9.0.0
* @see CCurrency::GetByID()
*/
	function GetCurrency($currency)
	{
		return CCurrency::GetByID($currency);
	}

	function CheckFields($ACTION, &$arFields, $strCurrencyID = false)
	{
		global $APPLICATION;
		global $DB;

		$arMsg = array();

		if ('UPDATE' != $ACTION && 'ADD' != $ACTION)
			return false;

		if ('UPDATE' == $ACTION)
		{
			if (strlen($strCurrencyID) <= 0)
			{
				$arMsg[] = array('id' => 'CURRENCY','text' => GetMessage('BT_MOD_CURR_ERR_CURR_CUR_ID_BAD'));
			}
			else
			{
				$strCurrencyID = substr($strCurrencyID, 0, 3);
			}
		}

		if (is_set($arFields, "CURRENCY") || 'ADD' == $ACTION)
		{
			if (!is_set($arFields, "CURRENCY"))
			{
				$arMsg[] = array('id' => 'CURRENCY','text' => GetMessage('BT_MOD_CURR_ERR_CURR_CUR_ID_ABSENT'));
			}
			else
			{
				$arFields["CURRENCY"] = substr($arFields["CURRENCY"], 0, 3);
			}
		}

		if ('ADD' == $ACTION)
		{
			if (!preg_match("~^[a-z]{3}$~i", $arFields["CURRENCY"]))
			{
				$arMsg[] = array('id' => 'CURRENCY','text' => GetMessage('BT_MOD_CURR_ERR_CURR_CUR_ID_LAT'));
			}
			else
			{
				$db_result = $DB->Query("select 'x' FROM b_catalog_currency where UPPER(CURRENCY) = UPPER('".$DB->ForSql($arFields["CURRENCY"])."')");
				if ($db_result->Fetch())
				{
					$arMsg[] = array('id' => 'CURRENCY','text' => GetMessage('BT_MOD_CURR_ERR_CURR_CUR_ID_EXISTS'));
				}
				else
				{
					$arFields["CURRENCY"] = strtoupper($arFields["CURRENCY"]);
				}
			}
		}

		if (is_set($arFields, 'AMOUNT_CNT') || 'ADD' == $ACTION)
		{
			if (!isset($arFields['AMOUNT_CNT']))
			{
				$arMsg[] = array('id' => 'AMOUNT_CNT','text' => GetMessage('BT_MOD_CURR_ERR_CURR_AMOUNT_CNT_ABSENT'));
			}
			elseif (0 >= intval($arFields['AMOUNT_CNT']))
			{
				$arMsg[] = array('id' => 'AMOUNT_CNT','text' => GetMessage('BT_MOD_CURR_ERR_CURR_AMOUNT_CNT_BAD'));
			}
			else
			{
				$arFields['AMOUNT_CNT'] = intval($arFields['AMOUNT_CNT']);
			}
		}

		if (is_set($arFields, 'AMOUNT') || 'ADD' == $ACTION)
		{
			if (!isset($arFields['AMOUNT']))
			{
				$arMsg[] = array('id' => 'AMOUNT','text' => GetMessage('BT_MOD_CURR_ERR_CURR_AMOUNT_ABSENT'));
			}
			else
			{
				$arFields['AMOUNT'] = doubleval($arFields['AMOUNT']);
				if (!(0 < $arFields['AMOUNT']))
				{
					$arMsg[] = array('id' => 'AMOUNT','text' => GetMessage('BT_MOD_CURR_ERR_CURR_AMOUNT_BAD'));
				}
			}
		}

		if (is_set($arFields,'SORT') || 'ADD' == $ACTION)
		{
			$arFields['SORT'] = intval($arFields['SORT']);
			if (0 >= $arFields['SORT'])
				$arFields['SORT'] = 100;
		}

		if (isset($arFields['DATE_UPDATE']))
			unset($arFields['DATE_UPDATE']);

		if (!empty($arMsg))
		{
			$obError = new CAdminException($arMsg);
			$APPLICATION->ResetException();
			$APPLICATION->ThrowException($obError);
			return false;
		}
		return true;
	}

	function Add($arFields)
	{
		global $DB;
		global $CACHE_MANAGER;

		foreach (GetModuleEvents("currency", "OnBeforeCurrencyAdd", true) as $arEvent)
		{
			if (ExecuteModuleEventEx($arEvent, array(&$arFields))===false)
				return false;
		}

		if (!CCurrency::CheckFields('ADD', $arFields))
			return false;

		$arInsert = $DB->PrepareInsert("b_catalog_currency", $arFields);

		$strSql = "insert into b_catalog_currency(".$arInsert[0].", DATE_UPDATE) values(".$arInsert[1].", ".$DB->GetNowFunction().")";
		$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		$CACHE_MANAGER->Clean("currency_currency_list");
		$rsLangs = CLanguage::GetList(($by="lid"), ($order="asc"));
		while ($arLang = $rsLangs->Fetch())
		{
			$CACHE_MANAGER->Clean("currency_currency_list_".$arLang['LID']);
		}
		$CACHE_MANAGER->Clean("currency_base_currency");

		foreach (GetModuleEvents("currency", "OnCurrencyAdd", true) as $arEvent)
		{
			ExecuteModuleEventEx($arEvent, array($arFields['CURRENCY'], $arFields));
		}

		return $arFields["CURRENCY"];
	}

	function Update($currency, $arFields)
	{
		global $DB;
		global $CACHE_MANAGER;

		foreach (GetModuleEvents("currency", "OnBeforeCurrencyUpdate", true) as $arEvent)
		{
			if (ExecuteModuleEventEx($arEvent, array($currency, &$arFields))===false)
				return false;
		}

		if (!CCurrency::CheckFields('UPDATE', $arFields, $currency))
			return false;

		$strCurrencyID = substr($currency, 0, 3);
		if (is_set($arFields, 'CURRENCY'))
			unset($arFields['CURRENCY']);
		$strUpdate = $DB->PrepareUpdate("b_catalog_currency", $arFields);
		if (!empty($strUpdate))
		{
			$strSql = "update b_catalog_currency set ".$strUpdate.", DATE_UPDATE = ".$DB->GetNowFunction()." where CURRENCY = '".$DB->ForSql($strCurrencyID)."' ";
			$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

			$CACHE_MANAGER->Clean("currency_base_currency");
			$CACHE_MANAGER->Clean("currency_currency_list");
			$rsLangs = CLanguage::GetList(($by="lid"), ($order="asc"));
			while ($arLang = $rsLangs->Fetch())
			{
				$CACHE_MANAGER->Clean("currency_currency_list_".$arLang['LID']);
			}

			if (defined("BX_COMP_MANAGED_CACHE"))
				$CACHE_MANAGER->ClearByTag("currency_id_".$strCurrencyID);
		}

		foreach (GetModuleEvents("currency", "OnCurrencyUpdate", true) as $arEvent)
		{
			ExecuteModuleEventEx($arEvent, array($currency, $arFields));
		}

		return $strCurrencyID;
	}

	function Delete($currency)
	{
		global $DB;
		global $stackCacheManager;
		global $CACHE_MANAGER;

		if (3 < strlen($currency))
			return false;
		$currency = substr($currency, 0, 3);

		$bCanDelete = true;
		foreach(GetModuleEvents("currency", "OnBeforeCurrencyDelete", true) as $arEvent)
		{
			if(ExecuteModuleEventEx($arEvent, array($currency))===false)
				return false;
		}

		foreach(GetModuleEvents("currency", "OnCurrencyDelete", true) as $arEvent)
		{
			ExecuteModuleEventEx($arEvent, array($currency));
		}

		$stackCacheManager->Clear("currency_currency_lang");
		$stackCacheManager->Clear("currency_rate");
		$CACHE_MANAGER->Clean("currency_currency_list");
		$rsLangs = CLanguage::GetList(($by="lid"), ($order="asc"));
		while ($arLang = $rsLangs->Fetch())
		{
			$CACHE_MANAGER->Clean("currency_currency_list_".$arLang['LID']);
		}
		$CACHE_MANAGER->Clean("currency_base_currency");

		$DB->Query("delete from b_catalog_currency_lang where CURRENCY = '".$DB->ForSQL($currency)."'", true);
		$DB->Query("delete from b_catalog_currency_rate where CURRENCY = '".$DB->ForSQL($currency)."'", true);

		if (defined("BX_COMP_MANAGED_CACHE"))
			$CACHE_MANAGER->ClearByTag("currency_id_".$currency);

		return $DB->Query("delete from b_catalog_currency where CURRENCY = '".$DB->ForSQL($currency)."'", true);
	}

	function GetByID($currency)
	{
		global $DB;

		$strSql = "select CUR.* from b_catalog_currency CUR where CUR.CURRENCY = '".$DB->ForSQL($currency, 3)."'";
		$db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

		if ($res = $db_res->Fetch())
			return $res;

		return false;
	}


	function GetBaseCurrency()
	{
		global $DB;
		global $CACHE_MANAGER;

		$baseCurrency = "";

		if (defined("CURRENCY_SKIP_CACHE") && CURRENCY_SKIP_CACHE)
		{
			$strSql = "select CURRENCY from b_catalog_currency where AMOUNT = 1 ";
			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			if ($arRes = $dbRes->Fetch())
				$baseCurrency = $arRes["CURRENCY"];
		}
		else
		{
			$cacheTime = CURRENCY_CACHE_DEFAULT_TIME;
			if (defined("CURRENCY_CACHE_TIME"))
				$cacheTime = intval(CURRENCY_CACHE_TIME);

			if ($CACHE_MANAGER->Read(CURRENCY_CACHE_TIME, "currency_base_currency"))
			{
				$baseCurrency = $CACHE_MANAGER->Get("currency_base_currency");
			}
			else
			{
				$strSql = "select CURRENCY from b_catalog_currency where AMOUNT = 1 ";
				$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
				if ($arRes = $dbRes->Fetch())
					$baseCurrency = $arRes["CURRENCY"];

				$CACHE_MANAGER->Set("currency_base_currency", $baseCurrency);
			}
		}

		return $baseCurrency;
	}

	function SelectBox($sFieldName, $sValue, $sDefaultValue = "", $bFullName = True, $JavaFunc = "", $sAdditionalParams = "")
	{
		$s = '<select name="'.$sFieldName.'"';
		if ('' != $JavaFunc) $s .= ' onchange="'.$JavaFunc.'"';
		if ('' != $sAdditionalParams) $s .= ' '.$sAdditionalParams.' ';
		$s .= '>'."\n";
		$found = false;

		$dbCurrencyList = CCurrency::GetList(($by="sort"), ($order="asc"));
		while ($arCurrency = $dbCurrencyList->Fetch())
		{
			$found = ($arCurrency["CURRENCY"] == $sValue);
			$s1 .= '<option value="'.$arCurrency["CURRENCY"].'"'.($found ? ' selected':'').'>'.htmlspecialcharsbx($arCurrency["CURRENCY"]).(($bFullName)?(' ('.htmlspecialcharsbx($arCurrency["FULL_NAME"]).')'):"").'</option>'."\n";
		}
		if ('' != $sDefaultValue)
			$s .= "<option value='' ".($found ? "" : "selected").">".htmlspecialcharsbx($sDefaultValue)."</option>";
		return $s.$s1.'</select>';
	}

	function GetList(&$by, &$order, $lang = LANGUAGE_ID)
	{
		global $DB;
		global $CACHE_MANAGER;

		if (defined("CURRENCY_SKIP_CACHE") && CURRENCY_SKIP_CACHE
			|| StrToLower($by) == "name"
			|| StrToLower($by) == "currency"
			|| StrToLower($order) == "desc")
		{
			$dbCurrencyList = CCurrency::__GetList($by, $order, $lang);
		}
		else
		{
			$by = "sort";
			$order = "asc";

			$lang = substr($lang, 0, 2);

			$cacheTime = CURRENCY_CACHE_DEFAULT_TIME;
			if (defined("CURRENCY_CACHE_TIME"))
				$cacheTime = intval(CURRENCY_CACHE_TIME);

			if ($CACHE_MANAGER->Read($cacheTime, "currency_currency_list_".$lang))
			{
				$arCurrencyList = $CACHE_MANAGER->Get("currency_currency_list_".$lang);
				$dbCurrencyList = new CDBResult();
				$dbCurrencyList->InitFromArray($arCurrencyList);
			}
			else
			{
				$arCurrencyList = array();
				$dbCurrencyList = CCurrency::__GetList($by, $order, $lang);
				while ($arCurrency = $dbCurrencyList->Fetch())
					$arCurrencyList[] = $arCurrency;

				$CACHE_MANAGER->Set("currency_currency_list_".$lang, $arCurrencyList);

				$dbCurrencyList = new CDBResult();
				$dbCurrencyList->InitFromArray($arCurrencyList);
			}
		}

		return $dbCurrencyList;
	}
}
?>