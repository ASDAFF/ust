<?

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
//$APPLICATION->SetTitle("test");
?><?

//var_dump(checkUrl('/test/'));
//var_dump(CSite::InDir('/test/'));
/* $meta_array = array(
  "/test12/" => array("h1" => "h1", "title" => "title", "keywords" => "keywords", "description" => "description"),
  ); */

$string = 'cup';
$name = 'coffee';
$str = 'This is a $string with my $name in it.';
 
@eval("\$str = \"$str\";");
echo $str. "\n";
?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>