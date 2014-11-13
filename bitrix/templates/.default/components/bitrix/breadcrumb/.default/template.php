<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//delayed function must return a string
if(empty($arResult))
	return "";

$strReturn = '<div class="crumbs"  xmlns:v="http://rdf.data-vocabulary.org/#">';
//$arResult=array_pop($arResult);

$arr=array();

for($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++)
{
    
     if($arResult[$index]["TITLE"]==$arResult[$index+1]["TITLE"]) $index++;
         
         
	if($index > 0)
		$strReturn .= '/';

	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);

    $arr[$index]=array();
    $arr[$index]['LINK']=$arResult[$index]["LINK"];
    $arr[$index]['TITLE']=$title;

//    $strReturn .= '<span  typeof="v:Breadcrumb"><a href="'.$arResult[$index]["LINK"].'" title="'.$title.'"  rel="v:url"  property="v:title">'.$title.'</a></span>';


	if($arResult[$index]["LINK"] <> "" and $index!=count($arResult)/*-1*/)
		$strReturn .= '<span  typeof="v:Breadcrumb"><a href="'.$arResult[$index]["LINK"].'" title="'.$title.'"  rel="v:url"  property="v:title">'.$title.'</a></span>';
	else
		$strReturn .= '<span  typeof="v:Breadcrumb">' .$title .'</span>';


}

$strReturn .= '</div>';

$_SESSION['crumbs']=$arr;

return $strReturn;
?>
