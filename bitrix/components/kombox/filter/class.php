<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CKomboxCatalogFilter extends CBitrixComponent
{
	var $IBLOCK_ID = 0;
	var $SKU_IBLOCK_ID = 0;
	var $SKU_PROPERTY_ID = 0;
	var $SECTION_ID = 0;
	var $FILTER_NAME = "";
	var $arTranslitParams = array();
	protected $currencyCache = array();
	
	public function onPrepareComponentParams($arParams)
	{
		global $APPLICATION;
		$arParams["CACHE_TIME"] = isset($arParams["CACHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000;
		$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
		$arParams["SECTION_ID"] = intval($arParams["SECTION_ID"]);
		$arParams["PRICE_CODE"] = is_array($arParams["PRICE_CODE"])? $arParams["PRICE_CODE"]: array();
		$arParams['CONVERT_CURRENCY'] = (isset($arParams['CONVERT_CURRENCY']) && 'Y' == $arParams['CONVERT_CURRENCY'] ? true : false);
		$arParams['CURRENCY_ID'] = trim(strval($arParams['CURRENCY_ID']));
		
		if(in_array('STORES', $arParams['FIELDS']) && COption::GetOptionString('catalog', 'default_use_store_control', 'N') !== 'Y')
			$arParams['FIELDS'] = array_diff($arParams['FIELDS'], array('STORES'));
			
		$arParams["SAVE_IN_SESSION"] = $arParams["SAVE_IN_SESSION"] == "Y";
		$arParams["HIDE_NOT_AVAILABLE"] = $arParams["HIDE_NOT_AVAILABLE"] == "Y";
		$arParams["CACHE_GROUPS"] = $arParams["CACHE_GROUPS"] !== "N";
		$arParams["INSTANT_RELOAD"] = $arParams["INSTANT_RELOAD"] === "Y";
		$arParams["IS_SEF"] = $arParams["IS_SEF"]== "Y"? "Y": "N";
        $arParams["SEF_BASE_URL"] = isset($arParams["SEF_BASE_URL"])? $arParams["SEF_BASE_URL"]: "/catalog/";
        $arParams["SECTION_PAGE_URL"] = isset($arParams["SECTION_PAGE_URL"])? $arParams["SECTION_PAGE_URL"]: "#SECTION_ID#/";
		$arParams["PAGE_URL"] = strlen($arParams["PAGE_URL"])? $arParams["PAGE_URL"]: "";
		
		if ('' == $arParams['CURRENCY_ID'])
		{
			$arParams['CONVERT_CURRENCY'] = false;
		}
		elseif (!$arParams['CONVERT_CURRENCY'])
		{
			$arParams['CURRENCY_ID'] = '';
		}
		
		if(
			strlen($arParams["FILTER_NAME"]) <= 0
			|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])
		)
		{
			$arParams["FILTER_NAME"] = "arrFilter";
		}
		
		if($arParams["IS_SEF"] == "Y"){
            $arVariables = array();
            
            $engine = new CComponentEngine($this);
            if (CModule::IncludeModule('iblock'))
            {
                    $engine->addGreedyPart("#SECTION_CODE_PATH#");
                    $engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
            }
            
            $componentPage = $engine->guessComponentPath(
                    $arParams["SEF_BASE_URL"],
                    array(
                            "section" => $arParams["SECTION_PAGE_URL"],
                    ),
                    $arVariables
            );    

            if(isset($arVariables["SECTION_ID"]))
                $arParams["SECTION_ID"] = $arVariables["SECTION_ID"];
            else if(isset($arVariables["SECTION_CODE"])) 
                $arParams["SECTION_CODE"] = $arVariables["SECTION_CODE"];
        }
		
        if(strlen($arParams["SECTION_CODE"])>0) {
            if(CModule::IncludeModule("iblock")) {
                $rsSections = CIBlockSection::GetList(array(), array("CODE" => $arParams["SECTION_CODE"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]));
                $arSection = $rsSections->GetNext();
                $arParams["SECTION_ID"] = $arSection["ID"];
            }
        }

		return $arParams;
	}

	public function executeComponent()
	{
		$this->IBLOCK_ID = $this->arParams["IBLOCK_ID"];
		$this->SECTION_ID = $this->arParams["SECTION_ID"];
		$this->FILTER_NAME = $this->arParams["FILTER_NAME"];

		if(CModule::IncludeModule("catalog"))
		{
			$arCatalog = CCatalogSKU::GetInfoByProductIBlock($this->IBLOCK_ID);
			if (!empty($arCatalog) && is_array($arCatalog))
			{
				$this->SKU_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
				$this->SKU_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];
			}
		}
		
		$this->arTranslitParams = array("replace_space"=>"_","replace_other"=>"_");

		return parent::executeComponent();
	}

	public function getIBlockItems($IBLOCK_ID)
	{
		$items = array();
		
		foreach(CIBlockSectionPropertyLink::GetArray($IBLOCK_ID, $this->SECTION_ID) as $PID => $arLink)
		{
			if($arLink["SMART_FILTER"] !== "Y")
				continue;

			$rsProperty = CIBlockProperty::GetByID($PID);
			$arProperty = $rsProperty->Fetch();
			if($arProperty)
			{
				$items[$arProperty["ID"]] = array(
					"ID" => $arProperty["ID"],
					"IBLOCK_ID" => $arProperty["IBLOCK_ID"],
					"CODE" => $arProperty["CODE"],
					"CODE_ALT" => ToLower(CUtil::translit($arProperty["CODE"], "ru", $this->arTranslitParams)),
					"NAME" => $arProperty["NAME"],
					"PROPERTY_TYPE" => $arProperty["PROPERTY_TYPE"],
					"HINT" => $arProperty["HINT"],
					"SORT" => $arProperty["SORT"],
					"VALUES" => array(),
				);

				if($arProperty["PROPERTY_TYPE"] == "N")
				{
					$items[$arProperty["ID"]]["VALUES"] = array(
						"MIN" => array(
							"CONTROL_ID" => htmlspecialcharsbx($this->FILTER_NAME."_".$arProperty["ID"]."_MIN"),
							"CONTROL_NAME" => htmlspecialcharsbx($this->FILTER_NAME."_".$arProperty["ID"]."_MIN"),
						),
						"MAX" => array(
							"CONTROL_ID" => htmlspecialcharsbx($this->FILTER_NAME."_".$arProperty["ID"]."_MAX"),
							"CONTROL_NAME" => htmlspecialcharsbx($this->FILTER_NAME."_".$arProperty["ID"]."_MAX"),
						),
					);
				}
			}
		}
		return $items;
	}

	public function getPriceItems()
	{
		$items = array();
		if(CModule::IncludeModule("catalog"))
		{
			$rsPrice = CCatalogGroup::GetList();
			while($arPrice = $rsPrice->Fetch())
			{
				if(
					($arPrice["CAN_ACCESS"] == "Y" || $arPrice["CAN_BUY"] == "Y")
					&& in_array($arPrice["NAME"], $this->arParams["PRICE_CODE"])
				)
				{
					$items[$arPrice["NAME"]] = array(
						"ID" => $arPrice["ID"],
						"CODE" => $arPrice["NAME"],
						"CODE_ALT" => ToLower(CUtil::translit($arPrice["NAME"], "ru", $this->arTranslitParams)),
						"NAME" => $arPrice["NAME_LANG"],
						"PRICE" => true,
						"SORT" => $arPrice["SORT"],
						"VALUES" => array(
							"MIN" => array(
								"CONTROL_ID" => htmlspecialcharsbx($this->FILTER_NAME."_P".$arPrice["ID"]."_MIN"),
								"CONTROL_NAME" => htmlspecialcharsbx($this->FILTER_NAME."_P".$arPrice["ID"]."_MIN"),
							),
							"MAX" => array(
								"CONTROL_ID" => htmlspecialcharsbx($this->FILTER_NAME."_P".$arPrice["ID"]."_MAX"),
								"CONTROL_NAME" => htmlspecialcharsbx($this->FILTER_NAME."_P".$arPrice["ID"]."_MAX"),
							),
						),
					);
				}
			}
			
			if(count($items) == 1){
				foreach($items as &$item)
					$item["CODE_ALT"] = "price";
				unset($item);
			}
		}
		return $items;
	}

	public function getResultItems()
	{
		$items = $this->getIBlockItems($this->IBLOCK_ID);
		
		foreach(array_reverse($this->arParams['FIELDS']) as $field)
		{
			switch($field)
			{
				case 'STORES':
					$items = array(
						'STORES' => array(
							"ID" => 'STORES',
							"IBLOCK_ID" => $this->IBLOCK_ID,
							"CODE" => 'STORES',
							"CODE_ALT" => 'stores',
							"NAME" => GetMessage('KOMBOX_CMP_FILTER_STORES_NAME'),
							"PROPERTY_TYPE" => 'STORES',
							"HINT" => '',
							"VALUES" => array(),
						)
					) + $items;
					break;
				case 'SECTIONS':
					$items = array(
						'SECTIONS' => array(
							"ID" => 'SECTIONS',
							"IBLOCK_ID" => $this->IBLOCK_ID,
							"CODE" => 'SECTIONS',
							"CODE_ALT" => 'sections',
							"NAME" => GetMessage('KOMBOX_CMP_FILTER_SECTIONS_NAME'),
							"PROPERTY_TYPE" => 'SECTIONS',
							"HINT" => '',
							"VALUES" => array(),
						)
					) + $items;
					break;
			}
		}
		
		foreach($items as &$arItem){
			if(in_array($arItem['CODE'], $this->arParams["CLOSED_PROPERTY_CODE"]) && strlen($arItem['CODE']))
				$arItem['CLOSED'] = true;
			else
				$arItem['CLOSED'] = false;
				
			$arItem['SKU'] = false;
		}
		unset($arItem);
		
		$this->arResult["PROPERTY_COUNT"] = count($items);

		if (!empty($this->arParams["PRICE_CODE"]))
		{
			foreach($this->getPriceItems() as $PID => $arItem)
			{
				$items = array($PID => $arItem) + $items;
			}
		}
		
		return $items;
	}
	
	public function getResultSkuItems()
	{
		$items = array();
		if($this->SKU_IBLOCK_ID)
		{
			foreach($this->getIBlockItems($this->SKU_IBLOCK_ID) as $PID => $arItem)
			{
				if(in_array($arItem['CODE'], $this->arParams["CLOSED_OFFERS_PROPERTY_CODE"]) && strlen($arItem['CODE']))
					$arItem['CLOSED'] = true;
				else
					$arItem['CLOSED'] = false;
					
				$arItem['SKU'] = true;
				$items[$PID] = $arItem;
			}
			$this->arResult["PROPERTY_COUNT"] += count($items);
			$this->arResult["SKU_PROPERTY_COUNT"] = count($items);
		}
		
		return $items;
	}

	public function fillItemPrices(&$resultItem, $arElement)
	{
		$price = $arElement["CATALOG_PRICE_".$resultItem["ID"]];
		$currency = $arElement["CATALOG_CURRENCY_".$resultItem["ID"]];
		
		if(strlen($price))
		{
			if($this->arParams['CONVERT_CURRENCY'] && $currency != $this->arParams['CURRENCY_ID']){
				$price = $this->ConvertCurrency($price, $currency, $this->arParams['CURRENCY_ID']);
			}
			
			if(!isset($resultItem["VALUES"]["MIN"]) || !array_key_exists("VALUE", $resultItem["VALUES"]["MIN"]) || doubleval($resultItem["VALUES"]["MIN"]["VALUE"]) > doubleval($price))
				$resultItem["VALUES"]["MIN"]["VALUE"] = $price;

			if(!isset($resultItem["VALUES"]["MAX"]) || !array_key_exists("VALUE", $resultItem["VALUES"]["MAX"]) || doubleval($resultItem["VALUES"]["MAX"]["VALUE"]) < doubleval($price))
				$resultItem["VALUES"]["MAX"]["VALUE"] = $price;
		}
		
		if(strlen($currency))
		{
			$resultItem["CURRENCIES"][$currency] = $this->getCurrencyFullName($currency);
		}
		
		return $price;
	}

	public function fillItemValues(&$resultItem, $arProperty, $cnt = false)
	{
		static $cacheL = array();
		static $cacheE = array();
		static $cacheG = array();
		static $cacheStore = array();
		static $cacheSect = array();

		$key = $arProperty;
		$PROPERTY_TYPE = $resultItem["PROPERTY_TYPE"];
		$PROPERTY_ID = $resultItem["ID"];

		if($PROPERTY_TYPE == "F")
		{
			return null;
		}
		elseif($PROPERTY_TYPE == "N")
		{
			if(!isset($resultItem["VALUES"]["MIN"]) || !array_key_exists("VALUE", $resultItem["VALUES"]["MIN"]) || doubleval($resultItem["VALUES"]["MIN"]["VALUE"]) > doubleval($key))
				$resultItem["VALUES"]["MIN"]["VALUE"] = $key;
			
			if(!isset($resultItem["VALUES"]["MAX"]) || !array_key_exists("VALUE", $resultItem["VALUES"]["MAX"]) || doubleval($resultItem["VALUES"]["MAX"]["VALUE"]) < doubleval($key))
				$resultItem["VALUES"]["MAX"]["VALUE"] = $key;
				
			return null;
		}
		elseif($PROPERTY_TYPE == "E" && $key <= 0)
		{
			return null;
		}
		elseif($PROPERTY_TYPE == "G" && $key <= 0)
		{
			return null;
		}
		elseif(strlen($key) <= 0)
		{
			return null;
		}

		$value_id = 0;
		
		switch($PROPERTY_TYPE)
		{
		case "L":
			if(!isset($cacheL[$PROPERTY_ID]))
			{
				$cacheL[$PROPERTY_ID] = array();
				$rsEnum = CIBlockPropertyEnum::GetList(array("SORT"=>"ASC", "VALUE"=>"ASC"), array("PROPERTY_ID" => $PROPERTY_ID));
				while ($enum = $rsEnum->Fetch())
					$cacheL[$PROPERTY_ID][$enum["ID"]] = $enum;
			}
			$sort = $cacheL[$PROPERTY_ID][$key]["SORT"];
			$value = $cacheL[$PROPERTY_ID][$key]["VALUE"];
			$value_id = $cacheL[$PROPERTY_ID][$key]["ID"];
			break;
		case "STORES":
			if(!isset($cacheStore[$key]))
			{
				$cacheStore = array();
				$rsStore = CCatalogStore::GetList(array("SORT"=>"ASC", "VALUE"=>"ASC"), array("ACTIVE" => "Y"), false, false, array('ID', 'TITLE', 'SORT'));
				while ($arStore = $rsStore->Fetch())
					$cacheStore[$arStore["ID"]] = $arStore;
			}
			$sort = $cacheStore[$key]["SORT"];
			$value = $cacheStore[$key]["TITLE"];
			$value_id = $cacheStore[$key]["ID"];
			break;
		case "SECTIONS":
			if(!isset($cacheSect[$key]))
			{
				$cacheSect = array();
				$rsSections = CIBlockSection::GetList(array(), array("ID" => $key), false, array('ID', 'NAME', 'SORT'));
				while ($arSection = $rsSections->Fetch())
					$cacheSect[$arSection["ID"]] = $arSection;
			}
			$sort = $cacheSect[$key]["SORT"];
			$value = $cacheSect[$key]["NAME"];
			$value_id = $cacheSect[$key]["ID"];
			break;
		case "E":
			if(!isset($cacheE[$key]))
			{
				$arLinkFilter = array (
					"ID" => $key,
					"ACTIVE" => "Y",
					"ACTIVE_DATE" => "Y",
					"CHECK_PERMISSIONS" => "Y",
				);
				$rsLink = CIBlockElement::GetList(array(), $arLinkFilter, false, false, array("ID","IBLOCK_ID","NAME","SORT"));
				$cacheE[$key] = $rsLink->Fetch();
			}
			$value = $cacheE[$key]["NAME"];
			$sort = $cacheE[$key]["SORT"];
			$value_id = $cacheE[$key]["ID"];
			break;
		case "G":
			if(!isset($cacheG[$key]))
			{
				$arLinkFilter = array (
					"ID" => $key,
					"GLOBAL_ACTIVE" => "Y",
					"CHECK_PERMISSIONS" => "Y",
				);
				$rsLink = CIBlockSection::GetList(array(), $arLinkFilter, false, array("ID","IBLOCK_ID","NAME","LEFT_MARGIN","DEPTH_LEVEL"));
				$cacheG[$key] = $rsLink->Fetch();
			}
			$value = str_repeat(".", $cacheG["DEPTH_LEVEL"]).$cacheG[$key]["NAME"];
			$sort = $cacheG[$key]["LEFT_MARGIN"];
			$value_id = $cacheG[$key]["ID"];
			break;
		default:
			$value = $key;
			$sort = 0;
			break;
		}

		$value = htmlspecialcharsex($value);
		$sort = intval($sort);
		$value_id = intval($value_id);
		
		$resultItem["VALUES"][$key] = array(
			"CONTROL_ID" => htmlspecialcharsbx($this->FILTER_NAME."_".$PROPERTY_ID."_".abs(crc32($key))),
			"CONTROL_NAME" => htmlspecialcharsbx($this->FILTER_NAME."_".$PROPERTY_ID."_".abs(crc32($key))),
			"CONTROL_NAME_ALT" => ToLower(CUtil::translit($value, "ru", $this->arTranslitParams)),
			"HTML_VALUE" => "Y",
			"VALUE" => $value,
			"SORT" => $sort,
			"UPPER" => ToUpper($value),
			"CNT" => $cnt
		);
		
		if($value_id){
			$resultItem["VALUES"][$key]["VALUE_ID"] = $value_id;
		}

		return $key;
	}
	
	function fillVariants(&$arVariants, &$arItems, &$el, $id = false)
	{
		$result = array();
		if($id === false)
			$id = $el['ID'];
			
		foreach($arItems as $PID => &$arItem)
		{
			$key = $el[$PID];
			$result[$PID] = $el[$PID];
			
			if(!is_array($arVariants[$PID]))
				$arVariants[$PID] = array();
			
			if($key !== false){		
				if(!is_array($key))
					$key = array($key);
				foreach($key as $k)
				{						
					if(empty($k))continue;
					
					if(!isset($arVariants[$PID][$k]))
						$arVariants[$PID][$k] = array('ID' => array(), 'CNT' => 0);
					
					if(!$arVariants[$PID][$k]['ID'][$id]){
						$arVariants[$PID][$k]['ID'][$id] = true;
						$arVariants[$PID][$k]['CNT']++;
					}
				}
			}
			
			//delete empty value
			if(is_array($result[$PID]))
			{
				if(!count($result[$PID]))
					unset($result[$PID]);
			}
			elseif(empty($result[$PID]))
				unset($result[$PID]);
		}
		return $result;
	}
		
	function contractionValues(&$arVariants)
	{
		foreach($this->arResult["ITEMS"] as $PID => &$arItem)
		{
			if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"]))
			{
				foreach ($arVariants[$PID] as $key => $value)
				{
					if($key<$arItem['VALUES']['MIN']['RANGE_VALUE'] || empty($arItem['VALUES']['MIN']['RANGE_VALUE'])){
						$arItem['VALUES']['MIN']['RANGE_VALUE'] = $key;
					}
					if($key>$arItem['VALUES']['MAX']['RANGE_VALUE'] || empty($arItem['VALUES']['MAX']['RANGE_VALUE'])){
						$arItem['VALUES']['MAX']['RANGE_VALUE'] = $key;
					}
				}
			}
			else
			{
				foreach ($arItem["VALUES"] as $key => &$arValue)
				{
					$cnt = $arVariants[$PID][$key]['CNT'];
					if($cnt)
						$arValue['CNT'] = $cnt;
					else
						$arValue["DISABLED"] = true;
						
				}
				unset($arValue);
			}
		}
		unset($arItem);
	}
	
	function generatePropertiesFilter()
	{
		global ${$this->FILTER_NAME};
		$arrFilter = ${$this->FILTER_NAME};
		
		if(!is_array($arrFilter))
			$arrFilter = array();
		
		if($this->arParams['HIDE_NOT_AVAILABLE'])
			$arrFilter['CATALOG_AVAILABLE'] = 'Y';

		if($this->arResult["SKU_PROPERTY_COUNT"] > 0)
		{
			if(!isset($arrFilter["OFFERS"]))
				$arrFilter["OFFERS"] = array();
			
			if($this->arParams['HIDE_NOT_AVAILABLE'])
				$arrFilter['OFFERS']['CATALOG_AVAILABLE'] = 'Y';
		}
		
		foreach($this->arResult["ITEMS"] as $PID => &$arItem)
		{
			if(isset($arItem["PRICE"]))
			{
				if(strlen($arItem["VALUES"]["MIN"]["HTML_VALUE"]) && strlen($arItem["VALUES"]["MAX"]["HTML_VALUE"]))
					$arrFilter["><CATALOG_PRICE_".$arItem["ID"]] = array($arItem["VALUES"]["MIN"]["HTML_VALUE"], $arItem["VALUES"]["MAX"]["HTML_VALUE"]);
				elseif(strlen($arItem["VALUES"]["MIN"]["HTML_VALUE"]))
					$arrFilter[">=CATALOG_PRICE_".$arItem["ID"]] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
				elseif(strlen($arItem["VALUES"]["MAX"]["HTML_VALUE"]))
					$arrFilter["<=CATALOG_PRICE_".$arItem["ID"]] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
			}
			elseif($arItem["PROPERTY_TYPE"] == "SECTIONS")
			{
				$sections = array();
				foreach($arItem["VALUES"] as $key => $ar)
				{
					if($ar["CHECKED"])
					{
						$sections[] = htmlspecialcharsback($key);
					}
				}
				
				if(count($sections))
					$arrFilter["SECTION_ID"] = $sections;
			}
			elseif($arItem["PROPERTY_TYPE"] == "N")
			{
				if ($arItem["IBLOCK_ID"] == $this->SKU_IBLOCK_ID)
					$filter = &$arrFilter["OFFERS"];
				else
					$filter = &$arrFilter;

				if(strlen($arItem["VALUES"]["MIN"]["HTML_VALUE"]) && strlen($arItem["VALUES"]["MAX"]["HTML_VALUE"]))
					$filter["><PROPERTY_".$PID] = array($arItem["VALUES"]["MIN"]["HTML_VALUE"], $arItem["VALUES"]["MAX"]["HTML_VALUE"]);
				elseif(strlen($arItem["VALUES"]["MIN"]["HTML_VALUE"]))
					$filter[">=PROPERTY_".$PID] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
				elseif(strlen($arItem["VALUES"]["MAX"]["HTML_VALUE"]))
					$filter["<=PROPERTY_".$PID] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
			}
			else
			{
				if ($arItem["IBLOCK_ID"] == $this->SKU_IBLOCK_ID)
					$filter = &$arrFilter["OFFERS"];
				else
					$filter = &$arrFilter;

				foreach($arItem["VALUES"] as $key => $ar)
				{
					if($ar["CHECKED"])
					{
						$filterKey = "=PROPERTY_".$PID;
						if(!array_key_exists($filterKey, $filter))
							$filter[$filterKey] = array(htmlspecialcharsback($key));
						else
							$filter[$filterKey][] = htmlspecialcharsback($key);
					}
				}
			}
		}
		
		if(intval($this->SECTION_ID)){
			if(!isset($arrFilter['SECTION_ID']))
				$arrFilter['SECTION_ID'] = $this->SECTION_ID;
			
			$arrFilter['INCLUDE_SUBSECTIONS'] = 'Y';
		}
		
		return $arrFilter;
	}

	function makeFilter($gFilter)
	{
		$arFilter = array(
			"IBLOCK_ID" => $this->IBLOCK_ID,
			"ACTIVE_DATE" => "Y",
			"ACTIVE" => "Y",
			"CHECK_PERMISSIONS" => "Y"
		);
		
		if(intval($this->SECTION_ID)){
			if(!isset($gFilter['SECTION_ID']))
				$arFilter['SECTION_ID'] = $this->SECTION_ID;
			
			$arFilter['INCLUDE_SUBSECTIONS'] = 'Y';
		}
		
		if($this->arParams['HIDE_NOT_AVAILABLE'])
			$arFilter['CATALOG_AVAILABLE'] = 'Y';
		
		if(is_array($gFilter["OFFERS"]))
		{
			if(!empty($gFilter["OFFERS"]))
			{
				$arSubFilter = $gFilter["OFFERS"];
				$arSubFilter["IBLOCK_ID"] = $this->SKU_IBLOCK_ID;
				$arSubFilter["ACTIVE_DATE"] = "Y";
				$arSubFilter["ACTIVE"] = "Y";
				if($this->arParams['HIDE_NOT_AVAILABLE'])
					$arSubFilter['CATALOG_AVAILABLE'] = 'Y';
				$arFilter["=ID"] = CIBlockElement::SubQuery("PROPERTY_".$this->SKU_PROPERTY_ID, $arSubFilter);
			}

			$arPriceFilter = array();
			foreach($gFilter as $key => $value)
			{
				if(preg_match('/^(>=|<=|><)CATALOG_PRICE_/', $key))
				{
					$arPriceFilter[$key] = $value;
					unset($gFilter[$key]);
				}
			}

			if(!empty($arPriceFilter))
			{
				$arSubFilter = $arPriceFilter;
				$arSubFilter["IBLOCK_ID"] = $this->SKU_IBLOCK_ID;
				$arSubFilter["ACTIVE_DATE"] = "Y";
				$arSubFilter["ACTIVE"] = "Y";
				$arFilter[] = array(
					"LOGIC" => "OR",
					array($arPriceFilter),
					"=ID" => CIBlockElement::SubQuery("PROPERTY_".$this->SKU_PROPERTY_ID, $arSubFilter),
				);
			}

			unset($gFilter["OFFERS"]);
		}

		return array_merge($gFilter, $arFilter);
	}
	
	function checkElement(&$allCHECKED, &$arElement, &$hash){
		$result = true;
		foreach($allCHECKED as $id => $arValue){
			$hash[$id] = true;
			
			if(!isset($arElement[$id])){
				$result = false;
				$hash[$id] = false;
			}
			
			$arProperty = &$this->arResult['ITEMS'][$id];
			if($arProperty["PROPERTY_TYPE"] == "N" || isset($arProperty["PRICE"]))
			{
				if(isset($arValue['MIN']))
				{
					if($arElement[$id]<$arValue['MIN']){
						$result = false;
						$hash[$id] = false;
					}
				}
				
				if(isset($arValue['MAX']))
				{
					if($arElement[$id]>$arValue['MAX']){
						$result = false;
						$hash[$id] = false;
					}
				}
			}
			else
			{
				if(is_array($arElement[$id]))
				{
					if(!count(array_intersect($arElement[$id], $arValue))){
						$result = false;
						$hash[$id] = false;
					}
				}
				else
				{
					if($arValue[$arElement[$id]] != $arElement[$id]){
						$result = false;
						$hash[$id] = false;
					}
				}
			}
		}
		return $result;
	}
	
	function checkElementByHash(&$allCHECKED, &$hash, $PID = false){
		foreach($allCHECKED as $id => $arValue){
			if($id == $PID)continue;

			if($hash[$id] == false){
				return false;
			}
		}
		return true;
	}
	
	public function _sort($v1, $v2)
	{
		if ($v1["SORT"] > $v2["SORT"])
			return 1;
		elseif ($v1["SORT"] < $v2["SORT"])
			return -1;
		elseif ($v1["UPPER"] > $v2["UPPER"])
			return 1;
		elseif ($v1["UPPER"] < $v2["UPPER"])
			return -1;
		else
			return 0;
	}
	
	public function _natsort($v1, $v2)
	{
		$sort = strnatcasecmp($v1["SORT"], $v2["SORT"]);
		if ($sort > 0)
			return 1;
		elseif ($sort < 0)
			return -1;
		else
			return strnatcasecmp($v1["UPPER"], $v2["UPPER"]);
	}
	
	public function getCurrencyFullName($currencyId)
	{
		if (!isset($this->currencyCache[$currencyId]))
		{
			$currencyInfo = CCurrencyLang::GetById($currencyId, LANGUAGE_ID);
			if ($currencyInfo["FULL_NAME"] != "")
				$this->currencyCache[$currencyId] = $currencyInfo["FULL_NAME"];
			else
				$this->currencyCache[$currencyId] = $currencyId;
		}
		return $this->currencyCache[$currencyId];
	}
	
	function ConvertCurrency($valSum, $curFrom, $curTo, $valDate = "") 
    { 
        static $arConvertParams = array();
		$key = $curFrom.'-'.$curTo.'-'.$valDate;
		if(!isset($arConvertParams[$key]))
		{
			global $DB; 
			if (strlen($valDate)<=0) 
				$valDate = date("Y-m-d"); 
			list($dpYear, $dpMonth, $dpDay) = split("-", $valDate, 3); 
			$dpDay += 1; 
			$valDate = date("Y-m-d", mktime(0, 0, 0, $dpMonth, $dpDay, $dpYear)); 

			$curFromRate = 0; 
			$curFromRateCnt = 0; 
			$strSql =  
				"SELECT C.AMOUNT, C.AMOUNT_CNT, CR.RATE, CR.RATE_CNT ". 
				"FROM b_catalog_currency C ". 
				"    LEFT JOIN b_catalog_currency_rate CR ". 
				"        ON (C.CURRENCY = CR.CURRENCY AND CR.DATE_RATE < '".$valDate."') ". 
				"WHERE C.CURRENCY = '".$DB->ForSql($curFrom)."' ". 
				"ORDER BY DATE_RATE DESC"; 
			$db_res = $DB->Query($strSql); 
			if ($res = $db_res->Fetch()) 
			{ 
				$curFromRate = DoubleVal($res["RATE"]); 
				$curFromRateCnt = IntVal($res["RATE_CNT"]); 
				if ($curFromRate<=0) 
				{ 
					$curFromRate = DoubleVal($res["AMOUNT"]); 
					$curFromRateCnt = IntVal($res["AMOUNT_CNT"]); 
				} 
			} 

			$curToRate = 0; 
			$curToRateCnt = 0; 
			$strSql =  
				"SELECT C.AMOUNT, C.AMOUNT_CNT, CR.RATE, CR.RATE_CNT ". 
				"FROM b_catalog_currency C ". 
				"    LEFT JOIN b_catalog_currency_rate CR ". 
				"        ON (C.CURRENCY = CR.CURRENCY AND CR.DATE_RATE < '".$valDate."') ". 
				"WHERE C.CURRENCY = '".$DB->ForSql($curTo)."' ". 
				"ORDER BY DATE_RATE DESC"; 
			$db_res = $DB->Query($strSql); 
			if ($res = $db_res->Fetch()) 
			{ 
				$curToRate = DoubleVal($res["RATE"]); 
				$curToRateCnt = DoubleVal($res["RATE_CNT"]); 
				if ($curToRate<=0) 
				{ 
					$curToRate = DoubleVal($res["AMOUNT"]); 
					$curToRateCnt = IntVal($res["AMOUNT_CNT"]); 
				} 
			}

			$arConvertParams[$key] = array(
				'curFromRate' 		=> $curFromRate,
				'curToRateCnt' 		=> $curToRateCnt,
				'curToRate' 		=> $curToRate,
				'curFromRateCnt' 	=> $curFromRateCnt
			);		
		}
		
        return DoubleVal(DoubleVal($valSum)*$arConvertParams[$key]['curFromRate']*$arConvertParams[$key]['curToRateCnt']/$arConvertParams[$key]['curToRate']/$arConvertParams[$key]['curFromRateCnt']); 
    }
	
	function IsSefMode(&$_SEF)
	{
		$bSefMode = false;

		if(CModule::IncludeModule('kombox.filter')){
			$bSefMode = CKomboxFilter::IsSefMode();
		}
		
		return $bSefMode;
	}
	
	function GetSefModeParams()
	{
		$_SEF = array();
		if(CModule::IncludeModule('kombox.filter'))
		{
			$requestURL = CKomboxFilter::GetCurPage(true);
			$arUrlParts = explode('/', $requestURL);
			if(in_array('filter', $arUrlParts)){
				$requestURL = '/';
				$arItems = array();
				foreach($this->arResult["ITEMS"] as $PID => $arItem)
				{
					$arItems[$arItem['CODE_ALT']] = $PID;
				}
				
				$set_filter = false;
				foreach($arUrlParts as $part)
				{
					if($set_filter && strlen(trim($part)))
					{
						$arParamParts = explode('-', $part);
						$ParamCode = $arParamParts[0];
						
						if(strlen($ParamCode) && isset($arItems[$ParamCode]))
						{
							unset($arParamParts[0]);
							$bOr = true;
							$arParamValues = array();
							$minValue = false;
							$maxValue = false;
							$bFrom = false;
							$bTo = false;
							
							foreach($arParamParts as $value)
							{
								if(strlen($value))
								{
									if($value == 'or' && $bOr == false)
									{
										$bOr = true;
										continue;
									}
									else
									{
										$arParamValues[] = $value;
									}
									
									if($value == 'from')
										$bFrom = true;
										
									if($value == 'to')
										$bTo = true;
									
									if(doubleval($value))
									{
										if($bTo)
											$maxValue = doubleval($value);
										
										if($bFrom && !$bTo)
											$minValue = doubleval($value);
									}
								}
							}
							
							$arItem = $this->arResult["ITEMS"][$arItems[$ParamCode]];
							
							if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"]))
							{
								if($minValue !== false)
									$_SEF[$arItem["VALUES"]["MIN"]["CONTROL_NAME"]] = $minValue;

								if($maxValue !== false)
									$_SEF[$arItem["VALUES"]["MAX"]["CONTROL_NAME"]] = $maxValue;
							}
							else
							{
								foreach($arItem["VALUES"] as $key => $ar)
								{
									if(in_array($ar['CONTROL_NAME_ALT'], $arParamValues))
										$_SEF[$ar["CONTROL_NAME"]] = $ar["HTML_VALUE"];
								}
							}
						}
					}
					elseif($part == 'filter')
						$set_filter = true;
				}
			}
		}
		
		return $_SEF;
	}
}
?>
