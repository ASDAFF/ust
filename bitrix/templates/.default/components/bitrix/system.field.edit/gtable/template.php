<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

if($arParams["~arUserField"]["MULTIPLE"]=="Y") {

	echo GUserPropertyTable::GetEditFormHTMLMulty(
		array(
			"SETTINGS" => $arParams['~arUserField']["SETTINGS"],
		),
		array(
			"NAME" => $arParams['~arUserField']["FIELD_NAME"],
			"VALUE" => $arParams["bVarsFromForm"]?$arResult["VALUE"]:$arParams['~arUserField']["VALUE"],
		)
	);

} else {

	echo GUserPropertyTable::GetEditFormHTML(
		array(
			"SETTINGS" => $arParams['~arUserField']["SETTINGS"],
		),
		array(
			"NAME" => $arParams['~arUserField']["FIELD_NAME"],
			"VALUE" => $arParams["bVarsFromForm"]?$arResult["VALUE"]:$arParams['~arUserField']["VALUE"],
		)
	);

}

?>
