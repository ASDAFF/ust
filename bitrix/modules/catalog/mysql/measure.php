<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/general/measure.php");

class CCatalogMeasure
	extends CCatalogMeasureAll
{
	public static function add($arFields)
	{
		global $DB;
		if(!self::CheckFields('ADD',$arFields))
			return false;

		$arInsert = $DB->PrepareInsert("b_catalog_measure", $arFields);
		$strSql =
			"INSERT INTO b_catalog_measure (".$arInsert[0].") ".
				"VALUES(".$arInsert[1].")";

		$res = $DB->Query($strSql, true, "File: ".__FILE__."<br>Line: ".__LINE__);
		if(!$res)
			return false;
		$lastId = intval($DB->LastID());
		return $lastId;
	}

	static function getList($arOrder = array(), $arFilter = array(), $arGroupBy = false, $arNavStartParams = false, $arSelectFields = array())
	{
		global $DB;

		if(count($arSelectFields) <= 0)
			$arSelectFields = array("ID", "CODE", "MEASURE_TITLE", "SYMBOL_RUS", "SYMBOL_INTL", "SYMBOL_LETTER_INTL", "IS_DEFAULT");
		if(!in_array('CODE', $arSelectFields))
			$arSelectFields[] = 'CODE';

		$arFields = array(
			"ID" => array("FIELD" => "CM.ID", "TYPE" => "int"),
			"CODE" => array("FIELD" => "CM.CODE", "TYPE" => "int"),
			"MEASURE_TITLE" => array("FIELD" => "CM.MEASURE_TITLE", "TYPE" => "string"),
			"SYMBOL_RUS" => array("FIELD" => "CM.SYMBOL_RUS", "TYPE" => "string"),
			"SYMBOL_INTL" => array("FIELD" => "CM.SYMBOL_INTL", "TYPE" => "string"),
			"SYMBOL_LETTER_INTL" => array("FIELD" => "CM.SYMBOL_LETTER_INTL", "TYPE" => "string"),
			"IS_DEFAULT" => array("FIELD" => "CM.IS_DEFAULT", "TYPE" => "char"),
		);
		$arSqls = CCatalog::PrepareSql($arFields, $arOrder, $arFilter, $arGroupBy, $arSelectFields);
		$arSqls["SELECT"] = str_replace("%%_DISTINCT_%%", "", $arSqls["SELECT"]);

		if(is_array($arGroupBy) && count($arGroupBy) == 0)
		{
			$strSql =
				"SELECT ".$arSqls["SELECT"]." ".
					"FROM b_catalog_measure CM ".
					"	".$arSqls["FROM"]." ";
			if(strlen($arSqls["WHERE"]) > 0)
				$strSql .= "WHERE ".$arSqls["WHERE"]." ";
			if(strlen($arSqls["GROUPBY"]) > 0)
				$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			if($arRes = $dbRes->Fetch())
				return $arRes["CNT"];
			else
				return false;
		}
		$strSql =
			"SELECT ".$arSqls["SELECT"]." ".
				"FROM b_catalog_measure CM ".
				"	".$arSqls["FROM"]." ";
		if(strlen($arSqls["WHERE"]) > 0)
			$strSql .= "WHERE ".$arSqls["WHERE"]." ";
		if(strlen($arSqls["GROUPBY"]) > 0)
			$strSql .= "GROUP BY ".$arSqls["GROUPBY"]." ";
		if(strlen($arSqls["ORDERBY"]) > 0)
			$strSql .= "ORDER BY ".$arSqls["ORDERBY"]." ";


		if(is_array($arNavStartParams) && intval($arNavStartParams["nTopCount"])<=0)
		{
			$strSql_tmp =
				"SELECT COUNT('x') as CNT ".
					"FROM b_catalog_measure CM ".
					"	".$arSqls["FROM"]." ";
			if(strlen($arSqls["WHERE"]) > 0)
				$strSql_tmp .= "WHERE ".$arSqls["WHERE"]." ";
			if(strlen($arSqls["GROUPBY"]) > 0)
				$strSql_tmp .= "GROUP BY ".$arSqls["GROUPBY"]." ";

			$dbRes = $DB->Query($strSql_tmp, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			$cnt = 0;
			if(strlen($arSqls["GROUPBY"]) <= 0)
			{
				if($arRes = $dbRes->Fetch())
					$cnt = $arRes["CNT"];
			}
			else
			{
				$cnt = $dbRes->SelectedRowsCount();
			}

			$dbRes = new CDBResult();

			$dbRes->NavQuery($strSql, $cnt, $arNavStartParams);
		}
		else
		{
			if(is_array($arNavStartParams) && intval($arNavStartParams["nTopCount"])>0)
				$strSql .= "LIMIT ".intval($arNavStartParams["nTopCount"]);

			$dbRes = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		}
		$dbMeasure = new CCatalogMeasureResult($dbRes);
		return $dbMeasure;
	}
}