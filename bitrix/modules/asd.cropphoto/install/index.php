<?
global $MESS;
$PathInstall = str_replace('\\', '/', __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen('/index.php'));
IncludeModuleLangFile($PathInstall.'/install.php');
include($PathInstall.'/version.php');

if (class_exists('asd_cropphoto')) return;

class asd_cropphoto extends CModule
{
	var $MODULE_ID = "asd.cropphoto";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $PARTNER_NAME;
	public $PARTNER_URI;
	public $MODULE_GROUP_RIGHTS = 'N';
	public $NEED_MAIN_VERSION = '12.0.0';
	public $NEED_MODULES = array();

	public function __construct()
	{
		$arModuleVersion = array();

		$path = str_replace('\\', '/', __FILE__);
		$path = substr($path, 0, strlen($path) - strlen('/index.php'));
		include($path.'/version.php');

		if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}

		$this->PARTNER_NAME = GetMessage("ASD_PARTNER_NAME");
		$this->PARTNER_URI = 'http://www.d-it.ru/solutions/modules/';

		$this->MODULE_NAME = GetMessage('ASD_CR_MODULE_NAME');
		$this->MODULE_DESCRIPTION = GetMessage('ASD_CR_MODULE_DESCRIPTION');
	}


	public function InstallDB() {
		RegisterModuleDependences('main', 'OnEndBufferContent', $this->MODULE_ID, 'CASDcropphoto', 'OnEndBufferContent');
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/install/js/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/js/asd.cropphoto/', true, true);
		CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/install/tools/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/tools/', true, true);
		RegisterModule($this->MODULE_ID);
	}

	public function UnInstallDB() {
		global $DB;
		UnRegisterModuleDependences('main', 'OnEndBufferContent', $this->MODULE_ID, 'CASDcropphoto', 'OnEndBufferContent');
		DeleteDirFilesEx('/bitrix/tools/asd_cropphoto.php');
		DeleteDirFilesEx('/bitrix/js/asd.cropphoto/css');
		DeleteDirFilesEx('/bitrix/js/asd.cropphoto/');
		UnRegisterModule($this->MODULE_ID);
	}

	public function DoInstall()
	{
		if ($GLOBALS['APPLICATION']->GetGroupRight('main') < 'W')
			return;

		if (is_array($this->NEED_MODULES) && !empty($this->NEED_MODULES))
			foreach ($this->NEED_MODULES as $module)
				if (!IsModuleInstalled($module))
					$this->ShowForm('ERROR', GetMessage('ASD_NEED_MODULES', array('#MODULE#' => $module)));

		if (strlen($this->NEED_MAIN_VERSION)<=0 || version_compare(SM_VERSION, $this->NEED_MAIN_VERSION)>=0)
		{
			$this->InstallDB();
			$this->ShowForm('OK', GetMessage('MOD_INST_OK'));
		}
		else
			$this->ShowForm('ERROR', GetMessage('ASD_NEED_RIGHT_VER', array('#NEED#' => $this->NEED_MAIN_VERSION)));
	}

	public function DoUninstall()
	{
		if ($GLOBALS['APPLICATION']->GetGroupRight('main') < 'W')
			return;

		$this->UnInstallDB();
		$this->ShowForm('OK', GetMessage('MOD_UNINST_OK'));
	}

	private function ShowForm($type, $message, $buttonName='')
	{
		global $APPLICATION, $adminPage, $USER, $adminMenu, $adminChain;

		$PathInstall = str_replace('\\', '/', __FILE__);
		$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen('/index.php'));
		IncludeModuleLangFile($PathInstall.'/install.php');

		$APPLICATION->SetTitle(GetMessage('ASD_CR_MODULE_NAME'));
		include($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');
		echo CAdminMessage::ShowMessage(array('MESSAGE' => $message, 'TYPE' => $type));
		?>
		<form action="<?= $APPLICATION->GetCurPage()?>" method="get">
		<p>
			<input type="hidden" name="lang" value="<?= LANG?>" />
			<input type="submit" value="<?= strlen($buttonName) ? $buttonName : GetMessage('MOD_BACK')?>" />
		</p>
		</form>
		<?
		include($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');
		die();
	}

	private function ShowDataSaveForm()
	{
		global $APPLICATION, $adminPage, $USER, $adminMenu, $adminChain;

		$PathInstall = str_replace('\\', '/', __FILE__);
		$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen('/index.php'));
		IncludeModuleLangFile($PathInstall.'/install.php');

		$APPLICATION->SetTitle(GetMessage('ASD_CR_MODULE_NAME'));
		include($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');
		?>
		<form action="<?= $APPLICATION->GetCurPage()?>" method="get">
			<?= bitrix_sessid_post()?>
			<input type="hidden" name="lang" value="<?= LANG?>" />
			<input type="hidden" name="id" value="<?= $this->MODULE_ID?>" />
			<input type="hidden" name="uninstall" value="Y" />
			<input type="hidden" name="step" value="2" />
			<?CAdminMessage::ShowMessage(GetMessage('MOD_UNINST_WARN'))?>
			<input type="submit" name="inst" value="<?echo GetMessage('MOD_UNINST_DEL')?>" />
		</form>
		<?
		include($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');
		die();
	}
}
?>