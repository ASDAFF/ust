<?
global $MESS;
$PathInstall = str_replace("\\", "/", __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen("/index.php"));
IncludeModuleLangFile($PathInstall."/install.php");
include($PathInstall."/version.php");

if (class_exists("kombox_filter")) return;

class kombox_filter extends CModule
{
	var $MODULE_ID = "kombox.filter";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $PARTNER_NAME;
	var $PARTNER_URI;
	public $MODULE_GROUP_RIGHTS = 'N';

	function __construct()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->PARTNER_NAME = GetMessage("KOMBOX_MODULE_FILTER_INSTALL_NAME");
		$this->PARTNER_URI = "http://filter.kombox.ru/";

		$this->MODULE_NAME = GetMessage("KOMBOX_MODULE_FILTER_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("KOMBOX_MODULE_FILTER_DESCRIPTION");

	}

	function DoInstall()
	{
		if ($GLOBALS["APPLICATION"]->GetGroupRight("main") >= "W")
		{
			$GLOBALS["APPLICATION"]->IncludeAdminFile(GetMessage("KOMBOX_MODULE_FILTER_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kombox.filter/install/step1.php");
		}
	}

	function DoUninstall()
	{
		if ($GLOBALS["APPLICATION"]->GetGroupRight("main") >= "W")
		{
			$GLOBALS["APPLICATION"]->IncludeAdminFile(GetMessage("KOMBOX_MODULE_FILTER_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kombox.filter/install/unstep1.php");
		}
	}
}
?>