<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
global $DB;
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

if(!CModule::IncludeModule("iblock"))
{
	ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
	return;
}
/*************************************************************************
	Processing of received parameters
*************************************************************************/
unset($arParams["IBLOCK_TYPE"]); //was used only for IBLOCK_ID setup with Editor
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);

$arParams["NAME"]=trim($arParams["NAME"]);
if(strlen($arParams["NAME"])<=0)
	$arParams["NAME"] = "CATALOG_COMPARE_LIST";

if(strlen($arParams["ELEMENT_SORT_FIELD"])<=0)
	$arParams["ELEMENT_SORT_FIELD"]="sort";

if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENT_SORT_ORDER"]))
	$arParams["ELEMENT_SORT_ORDER"]="asc";

$arParams["ACTION_VARIABLE"]=trim($arParams["ACTION_VARIABLE"]);
if(strlen($arParams["ACTION_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["ACTION_VARIABLE"]))
	$arParams["ACTION_VARIABLE"] = "action";

$arParams["PRODUCT_ID_VARIABLE"]=trim($arParams["PRODUCT_ID_VARIABLE"]);
if(strlen($arParams["PRODUCT_ID_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["PRODUCT_ID_VARIABLE"]))
	$arParams["PRODUCT_ID_VARIABLE"] = "id";

$arParams["SECTION_ID_VARIABLE"]=trim($arParams["SECTION_ID_VARIABLE"]);
if(strlen($arParams["SECTION_ID_VARIABLE"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["SECTION_ID_VARIABLE"]))
	$arParams["SECTION_ID_VARIABLE"] = "SECTION_ID";

if(!is_array($arParams["PROPERTY_CODE"]))
	$arParams["PROPERTY_CODE"] = array();
foreach($arParams["PROPERTY_CODE"] as $k=>$v)
	if($v==="")
		unset($arParams["PROPERTY_CODE"][$k]);

if(!is_array($arParams["FIELD_CODE"]))
	$arParams["FIELD_CODE"] = array();
foreach($arParams["FIELD_CODE"] as $k=>$v)
	if($v==="")
		unset($arParams["FIELD_CODE"][$k]);


if(!in_array("NAME", $arParams["FIELD_CODE"]))
	$arParams["FIELD_CODE"][]="NAME";
if(!is_array($arParams["PRICE_CODE"]))
	$arParams["PRICE_CODE"] = array();

$arParams["DISPLAY_ELEMENT_SELECT_BOX"] = $arParams["DISPLAY_ELEMENT_SELECT_BOX"]=="Y";
if (empty($arParams["ELEMENT_SORT_FIELD_BOX"]))
	$arParams["ELEMENT_SORT_FIELD_BOX"]="sort";
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENT_SORT_ORDER_BOX"]))
	$arParams["ELEMENT_SORT_ORDER_BOX"]="asc";
if (empty($arParams["ELEMENT_SORT_FIELD_BOX2"]))
	$arParams["ELEMENT_SORT_FIELD_BOX2"] = "id";
if (!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENT_SORT_ORDER_BOX2"]))
	$arParams["ELEMENT_SORT_ORDER_BOX2"] = "desc";

if (empty($arParams['HIDE_NOT_AVAILABLE']))
	$arParams['HIDE_NOT_AVAILABLE'] = 'N';
elseif ('Y' != $arParams['HIDE_NOT_AVAILABLE'])
	$arParams['HIDE_NOT_AVAILABLE'] = 'N';


if($arParams["LINK_IBLOCK_ID"] >  0 && strlen($arParams["LINK_PROPERTY_SID"]) > 0)
{
	if(!is_array($arParams["LINK_PROPERTY_CODE"]))
		$arParams["LINK_PROPERTY_CODE"] = array();
	foreach($arParams["LINK_PROPERTY_CODE"] as $k=>$v)
		if($v==="")
			unset($arParams["LINK_PROPERTY_CODE"][$k]);
	if(!is_array($arParams["LINK_FIELD_CODE"]))
		$arParams["LINK_FIELD_CODE"] = array();
	foreach($arParams["LINK_FIELD_CODE"] as $k=>$v)
		if($v==="")
			unset($arParams["LINK_FIELD_CODE"][$k]);
}
else
{
	unset($arParams["LINK_PROPERTY_CODE"]);
	unset($arParams["LINK_FIELD_CODE"]);
}


$arID = array();
if(isset($_REQUEST["ID"]))
{
	$arID = $_REQUEST["ID"];
	if(!is_array($arID))
		$arID = array($arID);
}
$arPR = array();
if(isset($_REQUEST["pr_code"]))
{
	$arPR = $_REQUEST["pr_code"];
	if(!is_array($arPR))
		$arPR = array($arPR);
}
$arOF = array();
if(isset($_REQUEST["of_code"]))
{
	$arOF = $_REQUEST["of_code"];
	if(!is_array($arOF))
		$arOF = array($arOF);
}
$arOP = array();
if(isset($_REQUEST["op_code"]))
{
	$arOP = $_REQUEST["op_code"];
	if(!is_array($arOP))
		$arOP = array($arOP);
}

$arResult = array();

/*************************************************************************
			Handling the Compare button
*************************************************************************/
if(isset($_REQUEST["action"]))
{
	switch($_REQUEST["action"])
	{
		case "ADD_TO_COMPARE_RESULT":
			if(
				intval($_REQUEST["id"]) > 0
				&& !array_key_exists($_REQUEST["id"], $_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"])
			)
			{
				$arOffers = CIBlockPriceTools::GetOffersIBlock($arParams["IBLOCK_ID"]);
				$OFFERS_IBLOCK_ID = $arOffers? $arOffers["OFFERS_IBLOCK_ID"]: 0;

				//SELECT
				$arSelect = array(
					"ID",
					"IBLOCK_ID",
					"IBLOCK_SECTION_ID",
					"NAME",
					"DETAIL_PAGE_URL",
				);
				//WHERE
				$arFilter = array(
					"ID" => intval($_REQUEST["id"]),
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"IBLOCK_LID" => SITE_ID,
					"IBLOCK_ACTIVE" => "Y",
					"ACTIVE_DATE" => "Y",
					"ACTIVE" => "Y",
					"CHECK_PERMISSIONS" => "Y",
					"MIN_PERMISSION" => "R"
				);
				if($OFFERS_IBLOCK_ID > 0)
					$arFilter["IBLOCK_ID"] = array($arParams["IBLOCK_ID"], $OFFERS_IBLOCK_ID);
				else
					$arFilter["IBLOCK_ID"] = $arParams["IBLOCK_ID"];

				$rsElement = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
				$rsElement->SetUrlTemplates($arParams["DETAIL_URL"]);
				$arElement = $rsElement->GetNext();

				$arMaster = false;
				if($arElement && $arElement["IBLOCK_ID"] == $OFFERS_IBLOCK_ID)
				{
					$rsMasterProperty = CIBlockElement::GetProperty($arElement["IBLOCK_ID"], $arElement["ID"], array(), array("ID" => $arOffers["OFFERS_PROPERTY_ID"], "EMPTY" => "N"));
					if($arMasterProperty = $rsMasterProperty->Fetch())
					{
						$rsMaster = CIBlockElement::GetList(
							array()
							,array(
								"ID" => $arMasterProperty["VALUE"],
								"IBLOCK_ID" => $arMasterProperty["LINK_IBLOCK_ID"],
								"ACTIVE" => "Y",
							)
						,false, false, $arSelect);
						$rsMaster->SetUrlTemplates($arParams["DETAIL_URL"]);
						$arMaster = $rsMaster->GetNext();
					}
				}

				if($arMaster)
				{
					$arMaster["NAME"] = $arElement["NAME"];
					$arMaster["DELETE_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=DELETE_FROM_COMPARE_RESULT&id=".$arMaster["ID"], array("action", "id")));
					$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"][$_REQUEST["id"]] = $arMaster;
				}
				elseif($arElement)
				{
					$arElement["DELETE_URL"] = htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=DELETE_FROM_COMPARE_RESULT&id=".$arElement["ID"], array("action", "id")));
					$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"][$_REQUEST["id"]] = $arElement;
				}
			}
			break;
		case "DELETE_FROM_COMPARE_RESULT":
			foreach($arID as $ID)
				unset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"][$ID]);
			break;
		case "ADD_FEATURE":
			foreach($arPR as $ID)
				unset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_PROP"][$ID]);

			foreach($arOF as $ID)
				unset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_OFFER_FIELD"][$ID]);

			foreach($arOP as $ID)
				unset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_OFFER_PROP"][$ID]);
			break;
		case "DELETE_FEATURE":
			foreach($arPR as $ID)
				$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_PROP"][$ID]=true;

			foreach($arOF as $ID)
				$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_OFFER_FIELD"][$ID]=true;

			foreach($arOP as $ID)
				$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_OFFER_PROP"][$ID]=true;
			break;
	}
}

if(!isset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DIFFERENT"]))
	$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DIFFERENT"] = false;
if(isset($_REQUEST["DIFFERENT"]))
	$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DIFFERENT"] = $_REQUEST["DIFFERENT"]=="Y";
$arResult["DIFFERENT"] = $_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DIFFERENT"];

$arCompare = $_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"];

if(is_array($arCompare) && count($arCompare)>0)
{
	if(!array_key_exists("DELETE_PROP", $_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]) || !is_array($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_PROP"])) {
		$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_PROP"] = array();
	}

	if(!array_key_exists("DELETE_OFFER_FIELD", $_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]) || !is_array($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_OFFER_FIELD"])) {
		$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_OFFER_FIELD"] = array();
	}

	if(!array_key_exists("DELETE_OFFER_PROP", $_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]])	|| !is_array($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_OFFER_PROP"])) {
		$_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_OFFER_PROP"] = array();
	}
	
	$arSelect = array(
		"ID",
		"IBLOCK_ID",
		"IBLOCK_SECTION_ID",
		"DETAIL_PAGE_URL",
		"PROPERTY_*",
	);
	
	$arSort = array(
		$arParams["ELEMENT_SORT_FIELD"] => $arParams["ELEMENT_SORT_ORDER"],
		"ID" => "DESC",
	);
	
	if(is_array($arCompare)) {
		$arResult["ITEMS"] = array();
		foreach($arCompare as $key_sec => $sec) {
			$arFilter = array(
				"ID" => $sec,
				"IBLOCK_LID" => SITE_ID,
				"IBLOCK_ACTIVE" => "Y",
				"ACTIVE_DATE" => "Y",
				"ACTIVE" => "Y",
				"CHECK_PERMISSIONS" => "Y",
				"IBLOCK_ID" => $arParams["IBLOCK_ID"]
			);
			$rsElements = CIBlockElement::GetList($arSort, $arFilter, false, false, array_merge($arSelect, $arParams["FIELD_CODE"]));			
			
			$arResult["DELETED_PROPERTIES"] = array();
			$arResult["SHOW_PROPERTIES"][$key_sec] = array();
			
			while($obElement = $rsElements->GetNextElement())
			{
				$arItem = $obElement->GetFields();

				$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arItem["IBLOCK_ID"], $arItem["ID"]);
				$arItem["IPROPERTY_VALUES"] = $ipropValues->getValues();

				$arItem["PREVIEW_PICTURE"] = (0 < $arItem["PREVIEW_PICTURE"] ? CFile::GetFileArray($arItem["PREVIEW_PICTURE"]) : false);
				if ($arItem["PREVIEW_PICTURE"])
				{
					$arItem["PREVIEW_PICTURE"]["ALT"] = $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"];
					if ($arItem["PREVIEW_PICTURE"]["ALT"] == "")
						$arItem["PREVIEW_PICTURE"]["ALT"] = $arItem["NAME"];
					$arItem["PREVIEW_PICTURE"]["TITLE"] = $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"];
					if ($arItem["PREVIEW_PICTURE"]["TITLE"] == "")
						$arItem["PREVIEW_PICTURE"]["TITLE"] = $arItem["NAME"];
				}
                

                if(!empty($arItem["PROPERTIES"]["SERIES"]["VALUE"])) {
                    $serieses = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arItem["PROPERTIES"]["SERIES"]["LINK_IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $arItem["PROPERTIES"]["SERIES"]["VALUE"]), false, false, array("ID", "PREVIEW_PICTURE"));
                    if($series = $serieses->GetNext()) {
                        if(!empty($series["PREVIEW_PICTURE"])) {
                            $arItem["PICTURE"] = CFile::ResizeImageGet( 
                                $series["PREVIEW_PICTURE"], 
                                array("width" => 90, "height" => 90), 
                                BX_RESIZE_IMAGE_PROPORTIONAL,
                                true 
                            );
                        }
                    }
                }
                elseif(!empty($arItem["IBLOCK_SECTION_ID"])) {
                    $sections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arItem["IBLOCK_ID"], "ID" => $arItem["IBLOCK_SECTION_ID"]), false);
                    if($section = $sections->GetNext())
                        $arItem["PICTURE"] = CFile::ResizeImageGet( 
                            $section["PICTURE"], 
                            array("width" => 90, "height" => 90), 
                            BX_RESIZE_IMAGE_PROPORTIONAL,
                            true 
                        );
                }

				$arItem["FIELDS"] = array();
				foreach($arParams["FIELD_CODE"] as $code)
					if(array_key_exists($code, $arItem))
						$arItem["FIELDS"][$code] = $arItem[$code];

				if(count($arParams["PROPERTY_CODE"]) > 0)
					$arItem["PROPERTIES"] = $obElement->GetProperties();

				$arItem["DISPLAY_PROPERTIES"] = array();
				foreach($arParams["PROPERTY_CODE"] as $pid)
				{
					if(!array_key_exists($pid, $_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_PROP"]))
						$arItem["DISPLAY_PROPERTIES"][$pid] = CIBlockFormatProperties::GetDisplayValue($arItem, $arItem["PROPERTIES"][$pid], "catalog_out");
					if(array_key_exists($pid, $_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["DELETE_PROP"]))
					{
						if(!array_key_exists($pid, $arResult["DELETED_PROPERTIES"]))
						{
							$arResult["DELETED_PROPERTIES"][$pid] = $arItem["PROPERTIES"][$pid];
						}
					}
					else
					{
						if(!array_key_exists($pid, $arResult["SHOW_PROPERTIES"]) && $arItem["DISPLAY_PROPERTIES"][$pid]["VALUE"] != "")
						{  
							$arResult["SHOW_PROPERTIES"][$key_sec][$pid] = $arItem["DISPLAY_PROPERTIES"][$pid];
						}
					}
				}
				
				$arResult["ITEMS"][$arItem["IBLOCK_SECTION_ID"]][] = $arItem;
                 /*echo "111<pre>"; print_r($arItem["PROPERTIES"]["PHOTO"]); echo "</pre>";
                 $count = $arItem["PROPERTIES"]["PHOTO"]*/
			}
            $arResult['IBLOCK_SECTION_ID'][] = $arItem["IBLOCK_SECTION_ID"];
			    foreach($arResult["IBLOCK_SECTION_ID"] as $arItem){
                    $res=CIBlockSection::GetList( Array("SORT"=>"ASC"), Array('ID'=>$arItem), false );
                    while($test = $res->GetNext()){
                        $arResult["IBLOCK_SECTION_CUST"][$arItem] = array(
                            "ID" => $arItem,
                            "NAME" => $test["NAME"]
                        );
                    }
                    
                }
            
		} 
	}
	
	$this->IncludeComponentTemplate();
}
else
{
	ShowNote(GetMessage("CATALOG_COMPARE_LIST_EMPTY"));
}

?>