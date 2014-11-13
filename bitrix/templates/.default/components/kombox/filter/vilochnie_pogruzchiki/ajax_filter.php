<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?    
    $arFilter = array('CODE' => 'stroitelnaya-tekhnika', 'IBLOCK_ID' => 6, 'IBLOCK_TYPE' => 'catalog');
    $arGroupBy = false;
    $arNavStartParams = false;
    $arSelectFields = array('ID', 'IBLOCK_ID', 'CODE', 'NAME');

    $rcs = CIBlockSection::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
    while ($arSect = $rcs->GetNext())
       {
                echo "<pre>";
                print_r($arSect);
                echo "</pre>"; 
        //$arResult["section_filter"]["ID"] = $arSect["ID"];   //id секции для вывода вида техники
        //$arResult["section_filter"]["CODE"] = $arSect["CODE"];
       }  
       
?>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>