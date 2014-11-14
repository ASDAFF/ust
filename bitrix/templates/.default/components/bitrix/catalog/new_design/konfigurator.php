<?
$file = $_SERVER['DOCUMENT_ROOT']."/bitrix/templates/.default/components/bitrix/catalog/new_design/test_t.txt";
file_put_contents($file, print_r($_POST, true));
?>