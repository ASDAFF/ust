<?

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("");
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/xml.php');
?><?

// $dbElemList = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID"=>"54", "XML_ID"=>$arItem["XML_ID"]));
// while($arElemList = $dbElemList->Fetch()){
// 	if(!empty($arElemList)) {
// 		echo "<pre>";
// 		$dbprops = CIBlockElement::GetProperty(54, $arElemList["ID"]);
// 		while($arProps = $dbprops->Fetch()){
// 			$arElemList["PROPERTIES"][$arProps["CODE"]] = $arProps;
// 		}
// 		$arElemList["DETAIL_PICTURE"] = CFile::GetFileArray($arElemList["DETAIL_PICTURE"]);
// 		//print_r($arElemList);
// 		echo "</pre>";
// 	}	
// }

CModule::IncludeModule("catalog");
//находим xml файл. файлы с другим расширением игнорируются
$dir = $_SERVER["DOCUMENT_ROOT"] . "/1c/in/";
if (is_dir($dir))
{
    if ($dh = opendir($dir))
    {
        while (($file = readdir($dh)) !== false)
        {
            $ext = explode(".", $file);
            if ($ext[1] == "xml")
                $importFile = $file;
        }
        closedir($dh);
    }
}
echo $importFile;
$fileError = 0;
$xmlError = 0;
$okCount = 0;
$neokCount = 0;
$upd_count = 0;
$prodCNT = 0;
$priceCNT = 0;
$deactivCount = 0;
//преобразуем xml в массив
$xml = new CDataXML();
$xml->Load($dir . $importFile);
$arData = $xml->GetArray();
if (empty($arData))
{
    $fileError = 1;
}
else
{
    $arResult = array();
    $arXML_IDs = array();
    $arLOTs = array();
    $arSections = array();
    $count = 0;
    //читаем товары в массив
    foreach ($arData["XML"]["#"]["Модели"][0]["#"]["Модель"] as $arModel)
    {
        foreach ($arModel["#"] as $key => $attr)
        {
            if ($key == "Комплектация")
            {
                foreach ($attr[0]["#"]["Комплектующая"] as $compl)
                {
                    $arResult[$count][$key][] = array(
                        "XML_ID" => $compl["#"]["XML_ID"][0]["#"],
                        "VALUE" => $compl["#"]["Название"][0]["#"]
                    );
                }
            }
            else
                $arResult[$count][$key] = $attr[0]["#"];
        }
        array_push($arXML_IDs, $arModel["#"]["XML_ID"][0]["#"]);
        array_push($arLOTs, $arModel["#"]["Лот"][0]["#"]);
        $count++;
    }
    if (empty($arResult))
        $xmlError = 1;
    else
    {
        //читаем существующие в ИБ разделы чтоб не плодить дубли
        $arSectXML_IDs = array();
        $dbSectList = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => "54"));
        while ($arSectList = $dbSectList->GetNext())
        {
            $arSectXML_IDs[$arSectList["XML_ID"]] = $arSectList["ID"];
        }
        //читаем разделы в массив
        $countRoot = 0;
        foreach ($arData["XML"]["#"]["Разделы"][0]["#"]["Раздел"] as $arSection)
        {
            $arSections[$countRoot]["NAME"] = $arSection["#"]["Название"][0]["#"];
            $arSections[$countRoot]["XML_ID"] = $arSection["#"]["XML_ID"][0]["#"];
            $dbSectList = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => "54", "XML_ID" => $arSections[$countRoot]["XML_ID"]));
            //создаем корневые разделы
            $arSectList = $dbSectList->Fetch();

            if (empty($arSectList))
            {
                $rootSection = new CIBlockSection;
                $arFields = array(
                    "ACTIVE" => "Y",
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => 54,
                    "NAME" => $arSections[$countRoot]["NAME"],
                    "CODE" => "bu_" . CUtil::translit($arSections[$countRoot]["NAME"], "ru", array("replace_space" => "_", "replace_other" => "")),
                    "XML_ID" => $arSections[$countRoot]["XML_ID"]
                );
                $SECTION_ID = $rootSection->Add($arFields);
                $countRoot++;
            }

            $countChildren = 0;

            foreach ($arSection["#"]["Разделы"][0]["#"]["Раздел"] as $arSubSection)
            {
                //if($arSection["#"]["Название"][0]["#"] == "Строительная техника") continue;
                $arSections[$countRoot]["CHILDREN"][$countChildren]["NAME"] = $arSubSection["#"]["Название"][0]["#"];
                $arSections[$countRoot]["CHILDREN"][$countChildren]["XML_ID"] = $arSubSection["#"]["XML_ID"][0]["#"];
                $dbSectList = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => "54", "XML_ID" => $arSections[$countRoot]["CHILDREN"][$countChildren]["XML_ID"]));
                //создаем дочерние разделы
                $arSectList = $dbSectList->Fetch();

                if (empty($arSectList))
                {
                    $childSection = new CIBlockSection;
                    $arFields = array(
                        "ACTIVE" => "Y",
                        "IBLOCK_SECTION_ID" => $SECTION_ID,
                        "IBLOCK_ID" => 54,
                        "NAME" => $arSections[$countRoot]["CHILDREN"][$countChildren]["NAME"],
                        "CODE" => "bu_" . CUtil::translit($arSections[$countRoot]["CHILDREN"][$countChildren]["NAME"], "ru", array("replace_space" => "_", "replace_other" => "")),
                        "XML_ID" => $arSections[$countRoot]["CHILDREN"][$countChildren]["XML_ID"]
                    );
                    $CHILD_SECTION_ID = $childSection->Add($arFields);
                    if (!in_array($CHILD_SECTION_ID, $arSectXML_IDs))
                        $arSectXML_IDs[$arSections[$countRoot]["CHILDREN"][$countChildren]["XML_ID"]] = $CHILD_SECTION_ID;
                    $countChildren++;
                }
            }
        }

        $dbElemList = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => "54"));
        while ($arElem = $dbElemList->GetNext())
        {
            //деактивация отсутствующих в XML элементов
            // if(!in_array($arElem["XML_ID"], $arLOTs)) {
            // 	$elem = new CIBlockElement;
            // 	$upadatedElem = $elem->Update($arElem["ID"], array("ACTIVE"=>"N"));
            // 	$deactivCount++;
            // }
        }

        foreach ($arResult as $arItem)
        {
            // 		echo "<pre>";
            // print_r($arItem);
            // echo "</pre>";
            $arNames = "";
            $newName = $arItem["Название"];
            //свойства
            $arElemFields = array(
                "MODEL" => $arItem["Модель"],
                "BRAND" => $arItem["Бренд"],
                "YEAR" => $arItem["ГодВыпуска"],
                "KPP" => $arItem["КПП"],
                "WORKED" => $arItem["Наработка"],
                "HEIGHT" => $arItem["ВысотаПодьема"],
                "MAX_WEIGHT" => $arItem["Грузоподьемность"],
                "TOWER" => $arItem["Мачта"],
                "CITY" => $arItem["Город"],
                "COMPLECT" => $arItem["Комплектация"],
                "PRICE" => $arItem["Цена"],
                "PHOTOS" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/1c/photo/" . $arItem["Фото"]),
                "DETAIL_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/1c/photo/" . $arItem["Фото"]),
                "LOT" => $arItem["Лот"]
            );

            //if(is_array($arElemFields["PHOTOS"])) echo "array<br/>";
            //if(empty($arItem["Фото"])) echo "nophoto";
            // $toAdd = 0;
            //проверяем или элемент уже есть в ИБ
            $update = 0;
            $dbElemList = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => "54", "PROPERTY_LOT" => $arItem["Лот"], "ACTIVE" => "Y"));
            while ($arElemList = $dbElemList->Fetch())
            {
                if (!empty($arElemList))
                {
                    // $listFields = array(
                    // 	"MODEL" => $arElemList["PROPERTY_MODEL_VALUE"],
                    // 	"BRAND" => $arElemList["PROPERTY_BRAND_VALUE"],
                    // 	"YEAR" =>$arElemList["PROPRTY_YEAR_VALUE"],
                    // 	"KPP" => $arElemList["PROPERTY_KPP_VALUE"],
                    // 	"WORKED" => $arElemList["PROPRTY_WORKED_VALUE"],
                    // 	"HEIGHT" => $arElemList["PROPERTY_HEIGHT_VALUE"],
                    // 	"MAX_WEIGHT" => $arElemList["PROPERTY_MAX_WEIGHT_VALUE"],
                    // 	"TOWER" => $arElemList["PROPRTY_TOWER_VALUE"],
                    // 	"CITY" => $arElemList["PROPRTY_CITY_VALUE"],
                    // 	"COMPLECT" => $arElemList["PROPERTY_COMPLECT_VALUE"],
                    // 	"PRICE" => 
                    // );
                    // if()
                    $upEl = new CIBlockElement;
                    $upEl->Update($arElemList["ID"], array("NAME" => $newName));
                    $upEl->SetPropertyValuesEx($arElemList["ID"], "54", $arElemFields);
                    $upd_count++;
                    $update = 1;
                    // if(CIBlockElement::Update($arElemList["ID"], array("NAME"=>$newName)) && 
                    // 	CIBlockElement::SetPropertyValuesEx($arElemList["ID"], 54, $arElemFields)) $upd_count++;
                    // echo "<pre>";
                    // print_r($arElemList);
                    // echo "</pre>";
                    // $dublCnt = 0;
                    // $dbprops = CIBlockElement::GetProperty(54, $arElemList["ID"]);
                    // while($arProps = $dbprops->Fetch()){
                    // 	$arElemList["PROPERTIES"][$arProps["CODE"]] = $arProps;
                    // }
                    // foreach($arElemFields as $key=>$value){
                    // 		if($key != "COMPLECT" && $key != "PHOTOS" && $value == $arElemList["PROPERTIES"][$key]["VALUE"]) $dublCnt++;
                    // 	}
                    // //$arElemList["DETAIL_PICTURE"] = CFile::GetFileArray($arElemList["DETAIL_PICTURE"]);
                    // //if(isset($arElemList["DETAIL_PICTURE"]) && !empty($arItem["Фото"]) && $arElemList["DETAIL_PICTURE"]["ORIGINAL_NAME"] == $arItem["Фото"]) $dublCnt++;
                    // //$arNames[] = $arElemList["NAME"];
                    // if($dublCnt == 10) {
                    // 	$toAdd = 2;    //если 1 - это дубль, который нужно добавить
                    // 	break;
                    // } else $toAdd = 1; //если 2 - элемент уже есть в ИБ
                }
                //если 0 - элемента еще нет в ИБ
                //echo $dublCnt."<br/>";
            }

            // if($toAdd == 1){
            // 	// $half = explode(";", );
            // 	// $newName .= "-2";
            // 	//echo "added ".$arItem["XML_ID"]."<br/>";
            // } elseif($toAdd == 2) {continue;}
            if ($update == 1)
                continue;
            $el = new CIBlockElement;
            //поля
            $sim_code = $arItem["Название"] . "_" . $arItem["Лот"];
            $arLoadProductArray = Array(
                "MODIFIED_BY" => $USER->GetID(),
                "IBLOCK_SECTION_ID" => $arSectXML_IDs[$arItem["РазделКаталога"]],
                "IBLOCK_ID" => 54,
                "PROPERTY_VALUES" => $arElemFields,
                "NAME" => $newName,
                "CODE" => CUtil::translit($sim_code, "ru", array("replace_space" => "_", "replace_other" => "")),
                "ACTIVE" => "Y",
                "PREVIEW_TEXT" => "",
                "DETAIL_TEXT" => $arItem["Описание"],
                "DETAIL_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/1c/photo/" . $arItem["Фото"]),
                "XML_ID" => $arItem["XML_ID"],
                "LOT" => $arItem["Лот"]
            );

            if ($PRODUCT_ID = $el->Add($arLoadProductArray))
            {
                $okCount++;
                //unlink($_SERVER["DOCUMENT_ROOT"]."/1c/photo/".$arItem["Фото"]); //удаление фото товара
            }
            else
            {
                $neokCount++;
                $error .= $el->LAST_ERROR . " (XML_ID: " . $arItem["XML_ID"] . ")\n";
            }
            // //торговые свойства
            // if(CCatalogProduct::Add(array("ID"=>$PRODUCT_ID, "QUANTITY"=>1, "QUANTITY_TRACE"=>"N"))) $prodCNT++;
            // //цена
            // $arPrice = array(
            // 	"PRODUCT_ID" => $PRODUCT_ID,
            //     "CATALOG_GROUP_ID" => 1,
            //     "PRICE" => $arItem["Цена"],
            //     "CURRENCY" => "RUB"
            // );
            // if(CPrice::Add($arPrice)) $priceCNT++;
            // else $priceErr = $APPLICATION->GetException();
        }
    }
}
//пишем логи
if ($fileError)
    $fileErrorText = "Ошибка файла";
if ($xmlError)
    $xmlErrorText = "Ошибка XML";
$logContent = date("H:i:s") . ": Товаров в файле: " . $count . " Товаров добавлено: " . $okCount . " Товаров обновлено: " . $upd_count . " Товаров деактивировано: " . $deactivCount . " Ошибок добавления: " . $neokCount . " Ошибки: " . $error . " " . $fileErrorText . " " . $xmlErrorText . "\n";
$logFile = $_SERVER["DOCUMENT_ROOT"] . "/1c/logs_bitrix/log_" . date("Y-m-d") . ".txt";
file_put_contents($logFile, $logContent, FILE_APPEND | LOCK_EX);
//переносим XML файл в соотв. папку (после окончательного тестирования раскоментировать)
if ($importFile)
{
    if ($fileError + $xmlError > 0)
        rename($dir . $importFile, $_SERVER["DOCUMENT_ROOT"] . "/1c/in_bad/" . $importFile);
    else
        rename($dir . $importFile, $_SERVER["DOCUMENT_ROOT"] . "/1c/in_history/" . $importFile);
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>