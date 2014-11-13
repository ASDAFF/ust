<?if(!check_bitrix_sessid()) return;?>
<?
CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kombox.filter/install/components/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/", true, true);
CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kombox.filter/install/js/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/", true, true);
RegisterModule("kombox.filter");
RegisterModuleDependences("main", "OnBeforeProlog", "kombox.filter", "CKomboxFilter", "OnBeforeProlog");
echo CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));
?>
<form action="<?echo $APPLICATION->GetCurPage()?>" method="get">
<p>
	<input type="hidden" name="lang" value="<?echo LANG?>" />
	<input type="submit" name="" value="<?echo GetMessage("MOD_BACK")?>" />
</p>
<form>