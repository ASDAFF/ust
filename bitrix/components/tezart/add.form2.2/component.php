<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

# ====================== Проверка параметров ===========================================================================

# если не установлено время кеширования, установи его в 36000000
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if(strlen($arParams["IBLOCK_TYPE"])<=0)
 	$arParams["IBLOCK_TYPE"] = "banners";

$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);

# ====================== / Проверка параметров ===========================================================================

$arResult["MESSAGE"] = array();
$arResult["ERRORS"] = array();

# Кешироване выборки с БД	
//if($this->StartResultCache(false, array($arrFilter)))
{
	if(!CModule::IncludeModule("iblock"))
	{
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
	
	# =======================  Подготовка данных для формы =======================================================================================================================	

	#выбираем свойства инфоблока и формируем массив $arResult["FIELD_FORM"]
	$arOrder = array("id"=>"asc");
	$arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y");

	$obProps = CIBlockProperty::GetList($arOrder, arFilter);

	while($arrProps = $obProps->GetNext())
	{
		# если тип свойства список
		# PROPERTY_TYPE – хранится тип свойства
		# "S" - строка
		# "N" - число
		# "L" - список
		# "F" - файл
		# "G" - привязка к разделу
		# "E" - привязка к элементу 
		
		# MULTIPLE – тип свойства множественное или нет

		$arResult["FIELD_FORM"][$arrProps["ID"]] = $arrProps;
		
		# если задано пользовательское название поля
		if ($arParams["CUSTOM_TITLE_" . $arrProps["ID"]])
			$arResult["FIELD_FORM"][$arrProps["ID"]]["NAME"] = $arParams["CUSTOM_TITLE_" . $arrProps["ID"]];

		# если тип свойства список, выберем значения списка
		if($arrProps["PROPERTY_TYPE"] == "L")
		{
			$arOrder = array("id"=>"ASC");
			$arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "PROPERTY_ID" => $arrProps["ID"]);
			
			# выбераем значения списка
			$obPropsList = CIBlockPropertyEnum::GetList($arOrder, arFilter);
			
			while($arrPropsList = $obPropsList->GetNext())
			{
				$arResult["FIELD_FORM"][$arrPropsList["PROPERTY_ID"]]["LIST"][$arrPropsList["ID"]] = $arrPropsList;
			}
		}
		# если тип свойства привязка к разделу, выберем разделы инфоблока
		if($arrProps["PROPERTY_TYPE"] == "G")
		{
			$arOrder = array("left_margin"=>"asc");
			$arFilter = array(
				"IBLOCK_ID" => $arrProps["LINK_IBLOCK_ID"],
				"ACTIVE"  => 'Y',
			);

			$obPropsList = CIBlockSection::GetList($arOrder, $arFilter);

			while ($arrPropsList = $obPropsList->GetNext())
			{
				$arResult["FIELD_FORM"][$arrProps["ID"]]["LIST"][$arrPropsList["ID"]]["ID"] = $arrPropsList["ID"];
				$arResult["FIELD_FORM"][$arrProps["ID"]]["LIST"][$arrPropsList["ID"]]["PROPERTY_ID"] = $arrProps["ID"];
				$arResult["FIELD_FORM"][$arrProps["ID"]]["LIST"][$arrPropsList["ID"]]["VALUE"] = $arrPropsList["NAME"];
				$arResult["FIELD_FORM"][$arrProps["ID"]]["LIST"][$arrPropsList["ID"]]["PROPERTY_NAME"] = $arrProps["NAME"];
				$arResult["FIELD_FORM"][$arrProps["ID"]]["LIST"][$arrPropsList["ID"]]["PROPERTY_CODE"] = $arrProps["CODE"];
				$arResult["FIELD_FORM"][$arrProps["ID"]]["LIST"][$arrPropsList["ID"]]["RIGHT_MARGIN"] = $arrPropsList["RIGHT_MARGIN"];
            }	
		}

		# если тип свойства привязка к элементу, выберем элементы с инфоблока
		if($arrProps["PROPERTY_TYPE"] == "E")
		{
			$arOrder = array("SORT"=>"ASC", "NAME"=>"ASC");
			$arFilter = array("IBLOCK_ID" => $arrProps["LINK_IBLOCK_ID"], "ACTIVE" => "Y");
			$arGroupBy = false;
			$arNavStartParams = false;
			$arSelectFields = array('ID', 'NAME');

			$obPropsList = CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
				
			while ($arrPropsList = $obPropsList->GetNext())
			{
				$arResult["FIELD_FORM"][$arrProps["ID"]]["LIST"][$arrPropsList["ID"]]["ID"] = $arrPropsList["ID"];
				$arResult["FIELD_FORM"][$arrProps["ID"]]["LIST"][$arrPropsList["ID"]]["PROPERTY_ID"] = $arrProps["ID"];
				$arResult["FIELD_FORM"][$arrProps["ID"]]["LIST"][$arrPropsList["ID"]]["VALUE"] = $arrPropsList["NAME"];
				$arResult["FIELD_FORM"][$arrProps["ID"]]["LIST"][$arrPropsList["ID"]]["PROPERTY_NAME"] = $arrProps["NAME"];
				$arResult["FIELD_FORM"][$arrProps["ID"]]["LIST"][$arrPropsList["ID"]]["PROPERTY_CODE"] = $arrProps["CODE"];
			}
		}
	}
	# проставляем обязательность полей
	foreach ($arParams["PROPERTY_CODES_REQUIRED"] as $valPropReq)
	{
		 $arResult["FIELD_FORM"][$valPropReq]["IS_REQUIRED"] = "Y";
	}
	
	# определяем код captcha
	if ($arParams["USE_CAPTCHA"] == "Y" && $arParams["ID"] <= 0)
	{
		$arResult["CAPTCHA_CODE"] = htmlspecialchars($APPLICATION->CaptchaGetCode());
	}
	
	# заголовок формы
	if($arParams["TITLE_FORM"])
		$arResult["TITLE_FORM"] = $arParams["TITLE_FORM"];
	# ======================= / Подготовка данных для формы =======================================================================================================================	
	

	
	# ======================= Обработка данных формы =======================================================================================================================
	
	if (!empty($_REQUEST["iblock_submit"]))
	{
//		echo "<pre>";print_r($arParams);echo "</pre>";
//		echo "<pre>ggg";print_r($_REQUEST);echo "</pre>";
//		echo "<pre>";print_r($arResult["FIELD_FORM"]);echo "</pre>";
		
		# проверка обязательных полей
		foreach($arParams["PROPERTY_CODES_REQUIRED"] as $n => $name_prop)
		{
			if (!($_REQUEST["PROPERTY"][$name_prop]))
			{
				$arResult["ERRORS"][$arResult["FIELD_FORM"][$name_prop]["ID"]] = 'Поле "' . $arResult["FIELD_FORM"][$name_prop]["NAME"] . '" должно быть заполнено!';
			}
		}

		# проверка captcha
		if ($arParams["USE_CAPTCHA"] == "Y")
		{
			if (!$APPLICATION->CaptchaCheckCode($_REQUEST["captcha_word"], $_REQUEST["captcha_sid"]))
			{
				$arResult["ERRORS"][] = "Введено неверное слово с картинки!";
			}
		}

		# проверка E-mail
		if ($_REQUEST["PROPERTY"][intval($arParams["ID_PROPERTY_EMAIL"])])
		{
			if (!check_email($_REQUEST["PROPERTY"][intval($arParams["ID_PROPERTY_EMAIL"])]))
			{
				$arResult["ERRORS"][$arParams["ID_PROPERTY_EMAIL"]] = "Введен некорректный E-mail!";
			}
		}
		
		# если форма заполнена успешно, добавляем данные в инфоблок, отправляем данные по е-mail и выводим соответствующее сообщение
		if (count($arResult["ERRORS"]) == 0)
		{
			# добавляем данные в инфоблок
			$arNewElements["IBLOCK_ID"] = $arParams["IBLOCK_ID"];
			$arNewElements["ACTIVE"] = $arParams["STATUS_NEW"];
			$arNewElements["DATE_ACTIVE_FROM"] = date("d.m.Y");
			
			foreach($arParams["PROPERTY_CODES"] as $number=>$name_prop)
			{
				# если значия передаются в виде массива
				if (!is_array($_REQUEST['PROPERTY'][$name_prop]))
				{
					# если текстовое поле
					if ($arResult["FIELD_FORM"][$name_prop]["USER_TYPE"] == "HTML" || $arResult["FIELD_FORM"][$name_prop]["USER_TYPE"] == "TEXT")
					{
						$properties[$name_prop]["VALUE"]["TEXT"] = $_REQUEST['PROPERTY'][$name_prop];
					}
                    else
						$properties[$name_prop] = htmlspecialchars($_REQUEST['PROPERTY'][$name_prop]);
				}
				else
				{
					foreach($_REQUEST['PROPERTY'][$name_prop] as $arIndex)
					{
						$properties[$name_prop][] = htmlspecialchars($arIndex);
					}
				}
			}
			
			$arNewElements["PROPERTY_VALUES"] = $properties;
			
			# Формируем название нового элемента
			$arResult["CUSTOM_NAME"] = $arParams["CUSTOM_NAME"];
			if(preg_match_all("/#(.*)#/U", $arParams["CUSTOM_NAME"], $matches))
			{
				foreach($matches[1] as $n => $v)
				{
					$arResult["CUSTOM_NAME"] = str_ireplace($matches[0][$n], $_REQUEST["PROPERTY"][$matches[1][$n]], $arResult["CUSTOM_NAME"]);
				}
				$arNewElements["NAME"] = $arResult["CUSTOM_NAME"];
			}
			else
			{
				$arNewElements["NAME"] = "Новый элемент";
			}
			
			$el = new CIBlockElement;

			if($newElementId = $el->Add($arNewElements))
			{
				# сообщение об успешной отправки данных
				$arResult["MESSAGE"] = $arParams["FORM_MESSAGES"];
			}
			else
				$arResult["ADMIN_ERRORS"] = "Error: " . $arNewElements["NAME"] . "(".$el->LAST_ERROR.")";
			
			# отправляем данные по e-mail
			$strEmailFrom = COption::GetOptionString('main','email_from');
			$strEmailTo = $arParams["EMAIL"]?$arParams["EMAIL"]:$strEmailFrom;
			$siteName = COption::GetOptionString("main", "site_name");
			$serverName = SITE_SERVER_NAME?SITE_SERVER_NAME:$_SERVER["SERVER_NAME"];
			$urlEl = "http://" . $serverName . "/bitrix/admin/iblock_element_edit.php?WF=Y&ID=".$newElementId."&type=".$arParams["IBLOCK_TYPE"]."&IBLOCK_ID=".$arParams["IBLOCK_ID"];
			$strEmailSubj = $siteName." – ".htmlspecialchars($arParams["EMAIL_SUBJ"]);

			# формируем набор полей для отправки в письме
			$arEventFields["ID"] = $newElementId;
			
			foreach ($arParams["PROPERTY_CODES"] as $num => $prop)
			{
//				echo "<pre>";print_r("#".strtoupper($arResult["FIELD_FORM"][$prop]["CODE"])."# - ". $arResult["FIELD_FORM"][$prop]["NAME"]);echo "</pre>";
//				echo "<pre>";print_r($arResult["FIELD_FORM"][$prop]["NAME"] . " - " . "#" .strtoupper($arResult["FIELD_FORM"][$prop]["CODE"]) . "#");echo "</pre>";

				# проверка типа (если строка или текст)
				if ($arResult["FIELD_FORM"][$prop]["PROPERTY_TYPE"] == 'S' || $arResult["FIELD_FORM"][$prop]["PROPERTY_TYPE"] == 'T')
					$arEventFields[strtoupper($arResult["FIELD_FORM"][$prop]["CODE"])] = htmlspecialchars($_REQUEST["PROPERTY"][$arResult["FIELD_FORM"][$prop]["ID"]]);
				
				# если свойство список и множественное (чекбокс)
				elseif ($arResult["FIELD_FORM"][$prop]["PROPERTY_TYPE"] == 'L' && $arResult["FIELD_FORM"][$prop]["MULTIPLE"] == 'Y')
				{
					foreach ($_REQUEST["PROPERTY"][$prop] as $reqNum => $reqVal)
						$_REQUEST["PROPERTY"][$prop][$reqNum] = $arResult["FIELD_FORM"][$prop]["LIST"][$reqVal]["VALUE"];
					
					$arEventFields[strtoupper($arResult["FIELD_FORM"][$prop]["CODE"])] = implode(",", htmlspecialchars($_REQUEST["PROPERTY"][$prop]));
				}
				
				# если свойство список и не множественное (радиобатон)
				elseif ($arResult["FIELD_FORM"][$prop]["PROPERTY_TYPE"] == 'L' && $arResult["FIELD_FORM"][$prop]["MULTIPLE"] == 'N')
					$arEventFields[strtoupper($arResult["FIELD_FORM"][$prop]["CODE"])] = $arResult["FIELD_FORM"][$prop]["LIST"][$_REQUEST["PROPERTY"][$prop][0]]["VALUE"];

				# если свойство привязка к элементу
				elseif ($arResult["FIELD_FORM"][$prop]["PROPERTY_TYPE"] == 'E')
					$arEventFields[strtoupper($arResult["FIELD_FORM"][$prop]["CODE"])] = $arResult["FIELD_FORM"][$prop]["LIST"][$_REQUEST["PROPERTY"][$prop]]["VALUE"];

				# если свойство привязка к разделу
				elseif ($arResult["FIELD_FORM"][$prop]["PROPERTY_TYPE"] == 'G')
					$arEventFields[strtoupper($arResult["FIELD_FORM"][$prop]["CODE"])] = $arResult["FIELD_FORM"][$prop]["LIST"][$_REQUEST["PROPERTY"][$prop]]["VALUE"];
			}
			
			#допоплнительные поля
			$arEventFields["URL"] = $urlEl;

			$arEventFields["EMAILTO"] = implode(",", $strEmailTo);

			$arEventFields["SUBJ"] = $strEmailSubj;
			
			$eventType = $arParams["EVENT_TYPE"];
			
//			echo "<pre>";print_r($_REQUEST);echo "</pre>";
//			echo "<pre>";print_r($arEventFields);echo "</pre>";
			
			CEvent::Send($eventType, SITE_ID, $arEventFields);
		}
	}
	
//	if ($_REQUEST["ajax"] == 'Y') && check_bitrix_sessid())
	if (($_REQUEST["ajax"] == 'Y') && ($_REQUEST["callback"] == 'Y'))
	{
		# сообщение об успешной отправки данных
//		$arResult["MESSAGE"] = iconv('windows-1251', 'UTF-8', $arResult["MESSAGE"]);
		echo json_encode(array("message" => $arResult["MESSAGE"]));
		
	}
	else
	{
		# Подключаем шаблон
		$this->IncludeComponentTemplate();
	}
}
?>
