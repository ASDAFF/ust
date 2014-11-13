<?
IncludeModuleLangFile(__FILE__);
Class intervolga_seo extends CModule
{
	const MODULE_ID = 'intervolga.seo';
	var $MODULE_ID = 'intervolga.seo'; 
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
    var $MODULE_GROUP_RIGHTS = "Y";

	var $strError = '';

    var $errors;

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("intervolga.seo_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("intervolga.seo_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("intervolga.seo_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("intervolga.seo_PARTNER_URI");
	}

	function InstallDB($arParams = array())
	{

        global $DB, $DBType, $APPLICATION;
        $this->errors = false;

        if(!$DB->Query("SELECT 'x' FROM iv_seo WHERE 1=0", true))
        {
            $this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/db/".$DBType."/install.sql");
        }
        if($this->errors !== false)
        {
            $APPLICATION->ThrowException(implode("<br>", $this->errors));
            return false;
        }
        else
        {
            RegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'CIntervolgaSeo', 'OnBuildGlobalMenu');
            RegisterModuleDependences('main', 'OnPanelCreate', self::MODULE_ID, 'CIntervolgaSeo', 'SeoOnPanelCreate');
            RegisterModuleDependences("main", "OnEpilog", self::MODULE_ID, "CIntervolgaSeo", "SeoOnEpilog");
            RegisterModuleDependences("main", "OnEndBufferContent", self::MODULE_ID, "CIntervolgaSeo", "SeoOnEndBufferContent");
            RegisterModuleDependences("main", "OnPageStart", self::MODULE_ID, "CIntervolgaSeo", "wwwRedirect");
        }

		return true;
	}

	function UnInstallDB($arParams = array())
	{
        global $DB, $DBType, $APPLICATION;
        $this->errors = false;

        if(!array_key_exists("save_tables", $arParams) || ($arParams["save_tables"] != "Y"))
        {
            $this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/db/".$DBType."/uninstall.sql");
        }

        if($this->errors !== false)
        {
            $APPLICATION->ThrowException(implode("<br>", $this->errors));
            return false;
        }

		UnRegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'CIntervolgaSeo', 'OnBuildGlobalMenu');
        UnRegisterModuleDependences('main', 'OnPanelCreate', self::MODULE_ID, 'CIntervolgaSeo', 'SeoOnPanelCreate');
        UnRegisterModuleDependences("main", "OnEpilog", self::MODULE_ID, "CIntervolgaSeo", "SeoOnEpilog");
        UnRegisterModuleDependences("main", "OnEndBufferContent", self::MODULE_ID, "CIntervolgaSeo", "SeoOnEndBufferContent");
        UnRegisterModuleDependences("main", "OnPageStart", self::MODULE_ID, "CIntervolgaSeo", "wwwRedirect");
        
		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles($arParams = array())
	{
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/admin'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.' || $item == 'menu.php')
						continue;
					file_put_contents($file = $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.self::MODULE_ID.'_'.$item,
					'<'.'? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/'.self::MODULE_ID.'/admin/'.$item.'");?'.'>');
				}
				closedir($dir);
			}
		}
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.')
						continue;
					CopyDirFiles($p.'/'.$item, $_SERVER['DOCUMENT_ROOT'].'/bitrix/components/'.$item, $ReWrite = True, $Recursive = True);
				}
				closedir($dir);
			}
		}
		return true;
	}

	function UnInstallFiles()
	{
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/admin'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.')
						continue;
					unlink($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.self::MODULE_ID.'_'.$item);
				}
				closedir($dir);
			}
		}
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/components'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.' || !is_dir($p0 = $p.'/'.$item))
						continue;

					$dir0 = opendir($p0);
					while (false !== $item0 = readdir($dir0))
					{
						if ($item0 == '..' || $item0 == '.')
							continue;
						DeleteDirFilesEx('/bitrix/components/'.$item.'/'.$item0);
					}
					closedir($dir0);
				}
				closedir($dir);
			}
		}
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;

        $POST_RIGHT = $APPLICATION->GetGroupRight(self::MODULE_ID);
        if($POST_RIGHT == "W")
        {
            if($this->InstallDB()) {
                $this->InstallFiles();
                RegisterModule(self::MODULE_ID);
                return true;
            };
            $GLOBALS["errors"] = $this->errors;
        }
        return false;
	}

	function DoUninstall()
	{
        global $DB, $DOCUMENT_ROOT, $APPLICATION, $step;

        $POST_RIGHT = $APPLICATION->GetGroupRight(self::MODULE_ID);
        if($POST_RIGHT == "W")
        {
            $step = IntVal($step);
            if($step < 1)
            {
                $APPLICATION->IncludeAdminFile(GetMessage("intervolga.seo_UNINST_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/uninst.php");
            } else {
                UnRegisterModule(self::MODULE_ID);
                $this->UnInstallDB(array(
                                        "save_tables" => $_REQUEST["save_tables"],
                                   ));
                $this->UnInstallFiles();
            }
        }
	}
}
?>
