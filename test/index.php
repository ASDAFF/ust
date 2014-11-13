<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тест");
 
?><?
$file = $_SERVER["DOCUMENT_ROOT"].'/test.txt';
$text= "123";
file_put_contents($file, $text);
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>