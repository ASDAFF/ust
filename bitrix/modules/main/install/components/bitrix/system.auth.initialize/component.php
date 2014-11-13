<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponent $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

$arParams["USERID_VAR"] = trim($arParams["USERID_VAR"]);
if(strlen($arParams["USERID_VAR"]) <= 0)
	$arParams["USERID_VAR"] = "user_id";

$arParams["CHECKWORD_VAR"] = trim($arParams["CHECKWORD_VAR"]);
if(strlen($arParams["CHECKWORD_VAR"]) <= 0)
	$arParams["CHECKWORD_VAR"] = "checkword";

$arResult["~USER_ID"] = $_REQUEST[$arParams["USERID_VAR"]];
$arResult["USER_ID"] = intval($arResult["~USER_ID"]);

$arResult["~CHECKWORD"] = trim($_REQUEST[$arParams["CHECKWORD_VAR"]]);
$arResult["CHECKWORD"] = htmlspecialcharsbx($arResult["~CHECKWORD"]);

$arResult["MESSAGE_CODE"] = array();

if($USER->IsAuthorized())
{
	$arResult["MESSAGE_TEXT"] .= GetMessage("CC_MAIN_REG_INIT_MESSAGE_AUTHORIZED")."<br>";
	$arResult["MESSAGE_CODE"][] = "E02";
	$arResult["SHOW_FORM"] = false;
}
else
{
	$rsUser = false;
	if($arResult["USER_ID"] > 0)
	{
		$rsUser = CUser::GetByID($arResult["~USER_ID"]);
	}

	if($rsUser && $arResult["USER"] = $rsUser->GetNext())
	{

		if(strlen($arResult["USER"]["LAST_LOGIN"]) > 0)
		{
			$arResult["MESSAGE_TEXT"] .= GetMessage("CC_MAIN_REG_INIT_MESSAGE_AUTH_SUCCESS")."<br>";
			$arResult["MESSAGE_CODE"][] = "E30";
			$arResult["SHOW_FORM"] = false;
		}

		if($arResult["USER"]["ACTIVE"] !== "Y" && count($arResult["MESSAGE_CODE"]) <= 0)
		{
			$arResult["MESSAGE_TEXT"] .= GetMessage("CC_MAIN_REG_INIT_MESSAGE_INACTIVE")."<br>";
			$arResult["MESSAGE_CODE"][] = "E03";
			$arResult["SHOW_FORM"] = false;
		}
		elseif(count($arResult["MESSAGE_CODE"]) <= 0 && $_SERVER["REQUEST_METHOD"] == "POST" && strlen($_POST["confirm"]) > 0 && check_bitrix_sessid())
		{

			$arResult["USER"]["NAME"] = trim($_POST["NAME"]);
			$arResult["USER"]["LAST_NAME"] = trim($_POST["LAST_NAME"]);
			$arResult["USER"]["WORK_COMPANY"] = trim($_POST["WORK_COMPANY"]);
			$arResult["USER"]["WORK_PHONE"] = trim($_POST["WORK_PHONE"]);

			$arResult["PASSWORD"] = $_POST["PASSWORD"];
			$arResult["CONFIRM_PASSWORD"] = $_POST["CONFIRM_PASSWORD"];


			if(strlen($arResult["USER"]["NAME"]) <= 0)
			{
				$arResult["MESSAGE_TEXT"] .= GetMessage("CC_MAIN_REG_INIT_MESSAGE_NAME_EMPTY")."<br>";
				$arResult["MESSAGE_CODE"][] = "E21";
				$arResult["SHOW_FORM"] = true;
			}

			if(strlen($arResult["USER"]["LAST_NAME"]) <= 0)
			{
				$arResult["MESSAGE_TEXT"] .= GetMessage("CC_MAIN_REG_INIT_MESSAGE_LAST_NAME_EMPTY")."<br>";
				$arResult["MESSAGE_CODE"][] = "E22";
				$arResult["SHOW_FORM"] = true;
			}

			$arResult["GROUP_POLICY"] = CUser::GetGroupPolicy($arResult["USER"]["ID"]);

			if(strlen($_POST["PASSWORD"]) <= 0)
			{
				$arResult["MESSAGE_TEXT"] .= GetMessage("CC_MAIN_REG_INIT_MESSAGE_PASSWORD_EMPTY")."<br>";
				$arResult["MESSAGE_CODE"][] = "E07";
				$arResult["SHOW_FORM"] = true;
			}
			elseif($_POST["PASSWORD"] !== $_POST["CONFIRM_PASSWORD"])
			{
				$arResult["MESSAGE_TEXT"] .= GetMessage("CC_MAIN_REG_INIT_MESSAGE_PASSWORD_NOT_CONFIRMED")."<br>";
				$arResult["MESSAGE_CODE"][] = "E08";
				$arResult["SHOW_FORM"] = true;
			}

			$salt = substr($arResult["USER"]["CHECKWORD"], 0, 8);

			if(strlen($arResult["CHECKWORD"]) <= 0)
			{
				$arResult["MESSAGE_TEXT"] .= GetMessage("CC_MAIN_REG_INIT_MESSAGE_CHECKWORD_EMPTY")."<br>";
				$arResult["MESSAGE_CODE"][] = "E04";
				$arResult["SHOW_FORM"] = true;
			}
			elseif(strlen($arResult["USER"]["CHECKWORD"])<=0 || $arResult["USER"]["CHECKWORD"] != $salt.md5($salt.$arResult["~CHECKWORD"]))
			{
				$arResult["MESSAGE_TEXT"] .= GetMessage("CC_MAIN_REG_INIT_MESSAGE_CHECKWORD_WRONG");
				$arResult["MESSAGE_CODE"][] = "E05";
				$arResult["SHOW_FORM"] = true;
			}
			elseif(count($arResult["MESSAGE_CODE"]) <= 0)
			{

				$arFields = array("PASSWORD"=>$_POST["PASSWORD"], "CONFIRM_CODE"=>"", "NAME"=>$arResult["USER"]["NAME"], "LAST_NAME"=>$arResult["USER"]["LAST_NAME"]);

				if (strlen(trim($_POST["WORK_COMPANY"])) > 0)
					$arFields["WORK_COMPANY"] = trim($_POST["WORK_COMPANY"]);

				if (strlen(trim($_POST["WORK_PHONE"])) > 0)
					$arFields["WORK_PHONE"] = trim($_POST["WORK_PHONE"]);

				if (is_array($_FILES["PERSONAL_PHOTO"]))
					$arFields["PERSONAL_PHOTO"] = $_FILES["PERSONAL_PHOTO"];

				$obUser = new CUser;
				$obUser->Update($arResult["USER"]["ID"], $arFields);
				$strError .= $obUser->LAST_ERROR;
				if (strlen($strError) <= 0)
				{
					$db_events = GetModuleEvents("main", "OnUserInitialize", true);
					foreach($db_events as $arEvent)
					{
						ExecuteModuleEventEx($arEvent, array($arResult["USER"]["ID"], $arFields));
					}

					$obUser->Authorize($arResult["USER"]["ID"], $_POST["USER_REMEMBER"] == "Y");
					LocalRedirect(SITE_DIR);
				}
				else
				{
					$arResult["MESSAGE_TEXT"] .= $strError;
					$arResult["MESSAGE_CODE"][] = "E10";
					$arResult["SHOW_FORM"] = true;
				}
			}
		}
		elseif(count($arResult["MESSAGE_CODE"]) <= 0)
			$arResult["SHOW_FORM"] = true;
	}
	else
	{
		$arResult["MESSAGE_TEXT"] .= GetMessage("CC_MAIN_REG_INIT_MESSAGE_NO_USER");
		$arResult["MESSAGE_CODE"][] = "E01";
		$arResult["SHOW_FORM"] = false;
	}
}

$arResult["~FORM_ACTION"] = $APPLICATION->GetCurPageParam();
$arResult["FORM_ACTION"] = htmlspecialcharsbx($arResult["~FORM_ACTION"]);

$this->IncludeComponentTemplate();
