<?
IncludeModuleLangFile(__FILE__);

class ust extends CModule {
 
    var $MODULE_ID = 'ust';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
 	
    /*** Инициализация модуля для страницы "Управление модулями"*/
    public function ust() {
       $arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}
		else {
			$this->MODULE_VERSION = "1.0.0";
			$this->MODULE_VERSION_DATE = "2013-12-11 17:00:00";
		}
		
		$this->PARTNER_NAME = 'AREALIDEA';
		$this->PARTNER_URI = "http://www.arealidea.ru/";
		$this->MODULE_NAME = GetMessage("FLOW_MODULE_NAME2");
		$this->MODULE_DESCRIPTION = GetMessage("FLOW_MODULE_DESCRIPTION2");
		$this->MODULE_CSS = "/bitrix/modules/ust/ust_settings.css";
    }
 
 
 
    /*** Устанавливаем модуль*/
    public function DoInstall() {
		if(CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ust/install/admin/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/"))
			RegisterModule( $this->MODULE_ID );
    }
 
    /*** Удаляем модуль*/
    public function DoUninstall() {
        UnRegisterModule( $this->MODULE_ID );
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/ust/install/admin/");
    }
	
	function GetModuleRightList() {
		$arr = array(
			"reference_id" => array("D","R","U","W"),
			"reference" => array(
				"[D] ".GetMessage("FLOW_DENIED"),
				"[R] ".GetMessage("FLOW_READ"),
				"[U] ".GetMessage("FLOW_MODIFY"),
				"[W] ".GetMessage("FLOW_WRITE"))
			);
		return $arr;
	}
 
}?>