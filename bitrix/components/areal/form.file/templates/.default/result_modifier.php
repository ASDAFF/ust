<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<? 
if(!CModule::IncludeModule("iblock")) 
    return;
    $arOrder = Array("SORT"=>"ASC");
    $arFilter = Array("IBLOCK_ID"=> 152, "ACTIVE" => "Y");
    $properties = CIBlockProperty::GetList($arOrder, $arFilter);
    while ($prop_fields = $properties->GetNext())
    {
        $arResult["PROPS"][] = $prop_fields;        
    }   
    $k = 0;
    foreach($arResult["PROPS"] as $ar_res){
        
        if($ar_res["PROPERTY_TYPE"] == "L"){
            $property_enums = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("PROPERTY_ID"=> $ar_res["ID"]));            
            while($enum_fields = $property_enums->GetNext())
            {
                $ar_res["VAL_LIST"][] = $enum_fields;                
                $arResult["PROPS"][$k]["VAL_LIST"] = $ar_res["VAL_LIST"];
                //echo $enum_fields["ID"]." - ".$enum_fields["VALUE"]."<br>";
            }
        }
        $k++;
    }
    //echo '<pre>'; print_r($arResult["PROPS"]); echo '</pre>';        
?>