<?

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("test");
?><?
$_SESSION["test_write"]="1234";
?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>