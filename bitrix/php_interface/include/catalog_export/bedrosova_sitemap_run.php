<?IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/bedrosova.sitemap/export_setup_templ.php");?>
<title>SITEMAP</title>
<?

require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/bedrosova.sitemap/classes/general/sitemap.php");

$SiteMap = new CSiteMapExport();
$SiteMap->SiteMapExport ($SETUP_SERVER_NAME, $SETUP_FILE_NAME, $SITEMAP_EXPORT, $SITEMAP2_EXPORT, $SITEMAP_PRIORI, $SITEMAP2_PRIORI);

?>
