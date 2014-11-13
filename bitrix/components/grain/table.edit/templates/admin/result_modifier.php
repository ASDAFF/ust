<?

$arResult["USE_FILEMAN"] = $USER->CanDoOperation('fileman_view_file_structure');


if($arResult["USE_FILEMAN"]) {

	$bUseFileman = false;

	foreach($arParams["SETTINGS"]["COLUMNS"] as $arColumn) 
		if($arColumn["TYPE"]=="filepath" && $arColumn["ALLOW_FILEMAN"]=="Y")
			$bUseFileman = true;

	$arResult["USE_FILEMAN"] = $bUseFileman;

}


$arResult["USE_MEDIALIB"] = CMedialib::CanDoOperation('medialib_access', 0);

if($arResult["USE_MEDIALIB"]) {

	$bUseMedialib = false;

	foreach($arParams["SETTINGS"]["COLUMNS"] as $arColumn) 
		if($arColumn["TYPE"]=="filepath" && $arColumn["ALLOW_MEDIALIB"]=="Y")
			$bUseMedialib = true;

	$arResult["USE_MEDIALIB"] = $bUseMedialib;

}


?>