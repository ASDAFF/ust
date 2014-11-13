<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?/* 
    $arFilter = array('CODE' => 'stroitelnaya-tekhnika', 'IBLOCK_ID' => $arParams["IBLOCK_ID"], 'IBLOCK_TYPE' => 'catalog');
    $arGroupBy = false;
    $arNavStartParams = false;
    $arSelectFields = array('ID', 'IBLOCK_ID', 'CODE', 'NAME');

    $rcs = CIBlockSection::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
    while ($arSect = $rcs->GetNext())
       {
        $arResult["section_filter"]["ID"] = $arSect["ID"];   //id секции для вывода вида техники
        $arResult["section_filter"]["CODE"] = $arSect["CODE"];
       }    
    $arFilter1 = array('CNT_ACTIVE' => "Y");       
    $rcs1 = CIBlockSection::GetSectionElementsCount($arResult["section_filter"]["ID"], $arFilter1);
        $arResult["section_filter"]["CNT"] = $rcs1;   //кол-во эл-тов в секции    
?>
<?
//пример выборки дерева подразделов для раздела 
$rsParentSection = CIBlockSection::GetByID($arResult["section_filter"]["ID"]);
if ($arParentSection = $rsParentSection->GetNext())
{
   $arFilter3 = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); 
   $rsSect3 = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter3);
   while ($arSect3 = $rsSect3->GetNext())
   {
       $arResult["section_filtered"][] =  $arSect3;

   }  
}
$counter = 0;
foreach($arResult["section_filtered"] as $counter=>$arSecfil)  {     
    $arFilter4 = array('CNT_ACTIVE' => "Y");   
    $rcs2 = CIBlockSection::GetSectionElementsCount($arResult["section_filtered"][$counter]["ID"], $arFilter4);
    $arResult["section_filtered"][$counter]["CNT"] = $rcs2;  
    $counter++;
}    
*/?>
<? 
    //echo "<pre>";
    //print_r($arResult["section_filtered"]);
    //echo "</pre>"; // получаем подразделы 
?>
<?
    $rsc2 = CIBlockSection::GetNavChain($arParams["IBLOCK_ID"] ,$arParams["SECTION_ID"], array());
    while ($arSectionFiltered = $rsc2->GetNext())
           {   
               $arResult["section_filtered2"][] = $arSectionFiltered;
           }      
    $result4 = array_slice($arResult["section_filtered2"], -2, 1);
    $arResult["parent_section_id"] = $result4[0]["ID"];
    //echo "<pre>";
    //print_r($result4[0]["ID"]);
    //echo "</pre>";
    
    $arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_ID"], 'SECTION_ID'=>false, 'ACTIVE' => 'Y');
    $arGroupBy = array();
    $arNavStartParams = false;
    $arSelect = array('ID', 'CODE', 'NAME', 'SECTION_PAGE_URL');
    $counter = 0;
    $rcs = CIBlockSection::GetList(array(), $arFilter, false ,$arSelect, $arNavStartParams);
    while ($arSect = $rcs->GetNext())
       {   
           $arResult["section_filter"][$counter]["ID"] = $arSect["ID"];   //id секции для вывода вида техники
           $arResult["section_filter"][$counter]["NAME"] = $arSect["NAME"];   //id секции для вывода вида техники
           $arResult["section_filter"][$counter]["CODE"] = $arSect["CODE"];
           $arResult["section_filter"][$counter]["SECTION_PAGE_URL"] = $arSect["SECTION_PAGE_URL"];
           $counter++;
       }    
    
    $rsParentSection1 = CIBlockSection::GetByID($arParams["SECTION_ID"]);
    if ($arParentSection = $rsParentSection1->GetNext())
    {
       $arFilter4 = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL'], 'DEPTH_LEVEL' =>$arParentSection['DEPTH_LEVEL'] + 1, 'ACTIVE' => 'Y'); 
       $rsSect4 = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter4);
       while ($arSect4 = $rsSect4->GetNext())
       {
           $arResult["preview_section_filtered"][] =  $arSect4;
       }  
    }         
//echo count($arResult["preview_section_filtered"]);      
if(count($arResult["preview_section_filtered"]) > 0){
    $rsParentSection = CIBlockSection::GetByID($arParams["SECTION_ID"]);
    if ($arParentSection = $rsParentSection->GetNext())
    {   
       $arFilter3 = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'],'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL'], 'DEPTH_LEVEL' =>$arParentSection['DEPTH_LEVEL'] + 1, 'ACTIVE' => 'Y'); 
       $rsSect3 = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter3);
       while ($arSect3 = $rsSect3->GetNext())
       {
           $arFilters = array("CNT_ACTIVE" => "Y");
           $rescount = CIBlockSection::GetSectionElementsCount($arSect3["ID"], $arFilters);
           
           if($rescount != 0){
               $arResult["section_filtered"][] =  $arSect3;
           }
           //echo '<pre>'; print_r($rescount); echo '</pre>';
           //$arResult["section_filtered"][] =  $arSect3;
       }  
    }    
}        
else{      
    $result2 = CIBlockSection::GetNavChain($arParams["IBLOCK_ID"] ,$arParams["SECTION_ID"], array());
    while ($arSect4 = $result2->GetNext())
           {   
               $arResult["section_filtered2"][] = $arSect4;
           }      
    $result3 = array_slice($arResult["section_filtered2"], -2, 1);
    //echo "<pre>";
    //print_r($result3);
    //echo "</pre>";
    $arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_ID"], 'SECTION_ID'=>$result3[0]["ID"], 'ACTIVE' => 'Y');
    $arGroupBy = array();
    $arNavStartParams = false;
    $arSelect = array('ID', 'CODE', 'NAME', 'SECTION_PAGE_URL');
    $rcs = CIBlockSection::GetList(array(), $arFilter, false ,$arSelect, $arNavStartParams);
    while ($arSect = $rcs->GetNext())
       {   
           //$rescount1 = CIBlockSection::GetSectionElementsCount($arSect3["ID"]);
           //if($rescount1 != 0){
               $arResult["section_filtered"][] =  $arSect;
           //}
            //$arResult["section_filtered"][] =  $arSect;
       }       
}   
unset($arResult["ITEMS"]["1350"]);  
// echo "<pre>";
// print_r($arResult);
// echo "</pre>";     
?>
