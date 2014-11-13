<?define("FILIALS_AND_DEALERS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("HIDE_MENU", "Y");
$APPLICATION->SetTitle("Дилеры по складской технике");?>
<?$APPLICATION->IncludeComponent("areal:filialy.dealers", ".default", array("TYPE" => "DEALERS"));?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>