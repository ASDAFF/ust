   <?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

if($arCurrentValues["IBLOCK_ID"] > 0)
	$bWorkflowIncluded = CIBlock::GetArrayByID($arCurrentValues["IBLOCK_ID"], "WORKFLOW") == "Y" && CModule::IncludeModule("workflow");
else
	$bWorkflowIncluded = CModule::IncludeModule("workflow");

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock=array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
{
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}

$arSorts = Array("ASC"=>"По возрастанию", "DESC"=>"По убыванию");

$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"]));
while ($arr=$rsProp->Fetch())
{
	$arProperty[$arr["ID"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S", "F", "E", "G")))
	{
		$arProperty_LNSF[$arr["ID"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}

$arVirtualProperties = $arProperty_LNSF;

$arGroups = array();
$rsGroups = CGroup::GetList($by="c_sort", $order="asc", Array("ACTIVE" => "Y"));
while ($arGroup = $rsGroups->Fetch())
{
	$arGroups[$arGroup["ID"]] = $arGroup["NAME"];
}

$arActiveNew = array("N" => "Не активно", "Y" => "Активно");

$arAllowEdit = array("CREATED_BY" => GetMessage("IBLOCK_CREATED_BY"), "PROPERTY_ID" => GetMessage("IBLOCK_PROPERTY_ID"));

# список почтовых событий
$arFilter = array(
    "LID"     => "ru"
);

$rsET = CEventType::GetList($arFilter);

while ($arET = $rsET->Fetch())
{
    $arEvent[$arET["EVENT_NAME"]] = $arET["NAME"];
}

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "Тип инфоблока для добавления нового элемента",
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"DEFAULT" => "entities",
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "ID инфоблока",
			"TYPE" => "LIST",
			"VALUES" => $arIBlock,
			"DEFAULT" => '={$_REQUEST["ID"]}',
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		),
		"STATUS_NEW" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "Статус добавленного элемента",
			"TYPE" => "LIST",
			"DEFAULT" => "DESC",
			"VALUES" => $arActiveNew,
		),
		"CUSTOM_NAME" => Array(
			"PARENT" => "FIELDS",
			"NAME" => "Свойство для названия",
			"TYPE" => "STRING",
			"DEFAULT" => "Заявка формы обратной связи #ID#",
			"COLS" => 50,
		),
		"PROPERTY_CODES" => array(
			"PARENT" => "FIELDS",
			"NAME" => "Поля, выводимые для занесения данных.",
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty_LNSF,
		),
		"PROPERTY_CODES_REQUIRED" => array(
			"PARENT" => "FIELDS",
			"NAME" => "Обязательные поля, выводимые для занесения данных",
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "N",
			"VALUES" => $arProperty_LNSF,
		),
		"ID_PROPERTY_EMAIL" => array(
			"PARENT" => "FIELDS",
			"NAME" => "ID свойства, в котором храниться E-mail",
			"TYPE" => "STRING",
		),
	),
);

//echo "<pre>";print_r($arComponentParameters["GROUPS"]);echo "</pre>";
/*["SETTINGS"] = 
      "SETTINGS" => array(
         "NAME" => GetMessage("SETTINGS_PHR")
      ),
      "PARAMS" => array(
         "NAME" => GetMessage("PARAMS_PHR")
      ),
   ),                           */


$arComponentParameters["PARAMETERS"]["SEND_MAIL"] = array(
	"PARENT" => "PARAMS",
	"NAME" => "Оповещать почтовым сообщением",
	"TYPE" => "CHECKBOX",
);

$arComponentParameters["PARAMETERS"]["EVENT_TYPE"] = array(
	"PARENT" => "PARAMS",
	"NAME" => "Типы почтовых событий",
	"TYPE" => "LIST",
	"VALUES" => $arEvent,
);

$arComponentParameters["PARAMETERS"]["EMAIL_SUBJ"] = array(
	"PARENT" => "PARAMS",
	"NAME" => "Заголовок письма при отправки",
	"TYPE" => "TEXT",
);

$arComponentParameters["PARAMETERS"]["EMAIL"] = array(
	"PARENT" => "PARAMS",
	"NAME" => "E-mail куда отправлять оповещение",
	"TYPE" => "STRING",
	"MULTIPLE" => "Y",
);

$arComponentParameters["PARAMETERS"]["TITLE_FORM"] = array(
	"PARENT" => "PARAMS",
	"NAME" => "Заголовок формы",
	"TYPE" => "TEXT",
);

$arComponentParameters["PARAMETERS"]["FORM_MESSAGES"] = array(
	"PARENT" => "PARAMS",
	"NAME" => "Сообщение об успешной отправке",
	"TYPE" => "TEXT",
);

$arComponentParameters["PARAMETERS"]["BUTTON_NAME"] = array(
	"PARENT" => "PARAMS",
	"NAME" => "Название кнопки",
	"TYPE" => "TEXT",
);

/*$arComponentParameters["PARAMETERS"]["USE_RADIOBUTTON"] = array(
	"PARENT" => "PARAMS",
	"NAME" => "Использовать radiobutton",
	"TYPE" => "STRING",
	"DEFAULT" => "N",
);

$arComponentParameters["PARAMETERS"]["VALUE_RADIO"] = array(
	"PARENT" => "PARAMS",
	"NAME" => "Значения radiobutton",
	"TYPE" => "STRING",
	"MULTIPLE" => "Y",
	"SIZE" => 2
);

$arComponentParameters["PARAMETERS"]["BASE_PROPERTY_FOR_RADIO"] = array(
	"PARENT" => "PARAMS",
	"NAME" => "Массив исходных свойств",
	"TYPE" => "STRING",
	"MULTIPLE" => "Y",
);

$arComponentParameters["PARAMETERS"]["OTHER_PROPERTY_FOR_RADIO"] = array(
	"PARENT" => "PARAMS",
	"NAME" => "Массив заменяемых свойств",
	"TYPE" => "STRING",
	"MULTIPLE" => "Y",
);*/

$arComponentParameters["PARAMETERS"]["USE_CAPTCHA"] = array(
	"PARENT" => "PARAMS",
	"NAME" => "Использовать captcha",
	"TYPE" => "CHECKBOX",
);

$arComponentParameters["PARAMETERS"]["CACHE_TIME"] = array(
	"PARENT" => "CACHE",
	"DEFAULT"=>36000000,
	"SORT"=>1000,
);




?>
