<?
IncludeModuleLangFile(__FILE__);

/**
 * Class CCatalogMeasureAll
 */
class CCatalogMeasureAll
{
	/**
	 * @param $action
	 * @param $arFields
	 * @return bool
	 */
	protected static function checkFields($action, &$arFields)
	{
		if((is_set($arFields, "IS_DEFAULT")) && (($arFields["IS_DEFAULT"]) == 'Y'))
		{
			$dbMeasure = CCatalogMeasure::getList(array(), array("IS_DEFAULT" => 'Y'), false, false, array('ID'));
			while($arMeasure = $dbMeasure->Fetch())
			{
				if(!self::update($arMeasure["ID"], array("IS_DEFAULT" => 'N')))
					return false;
			}
		}
		return true;
	}

	/**
	 * @param $id
	 * @param $arFields
	 * @return bool|int
	 */
	public static function update($id, $arFields)
	{
		$id = intval($id);
		if($id < 0 || !self::checkFields('UPDATE', $arFields))
			return false;
		global $DB;
		$strUpdate = $DB->PrepareUpdate("b_catalog_measure", $arFields);
		$strSql = "UPDATE b_catalog_measure SET ".$strUpdate." WHERE ID = ".$id;
		if(!$DB->Query($strSql, true, "File: ".__FILE__."<br>Line: ".__LINE__))
			return false;
		return $id;
	}

	/**
	 * @param $id
	 * @return bool
	 */
	public static function delete($id)
	{
		global $DB;
		$id = intval($id);
		if($id > 0)
		{
			if($DB->Query("DELETE FROM b_catalog_measure WHERE ID = ".$id." ", true))
				return true;
		}
		return false;
	}
}

/**
 * Class CCatalogMeasureResult
 */
class CCatalogMeasureResult extends CDBResult
{
	/**
	 * @param $res
	 */
	function CCatalogMeasureResult($res)
	{
		parent::CDBResult($res);
	}

	/**
	 * @return array
	 */
	function Fetch()
	{
		$res = parent::Fetch();
		if($res)
		{
			if($res["MEASURE_TITLE"] == '')
			{
				$tmpTitle = CCatalogMeasureClassifier::getMeasureTitle($res["CODE"], 'MEASURE_TITLE');
				$res["MEASURE_TITLE"] = ($tmpTitle == '') ? $res["SYMBOL_INTL"] : $tmpTitle;
			}
			if($res["SYMBOL_RUS"] == '')
			{
				$tmpSymbol = CCatalogMeasureClassifier::getMeasureTitle($res["CODE"], 'SYMBOL_RUS');
				$res["SYMBOL_RUS"] = ($tmpSymbol == '') ? $res["SYMBOL_INTL"] : $tmpSymbol;
			}
		}

		return $res;
	}
}