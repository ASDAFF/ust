<?if(isset($_REQUEST["print"]) && $_REQUEST["print"] == "y") {
	define("PRINT", "Y");
	define("ONLOAD", "N");
}
define("FILIALS_AND_DEALERS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("HIDE_MENU", "Y");
$APPLICATION->SetTitle("Филиалы УСТ");?>
<?$APPLICATION->IncludeComponent("areal:filialy.dealers", ".default", Array( 
	"TYPE" => "FILIALS"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>